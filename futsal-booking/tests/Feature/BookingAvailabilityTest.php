<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Tarif;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_a_cash_booking_without_a_payment_deadline(): void
    {
        $customer = User::factory()->create();
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Cash',
            'kategori' => 'standar',
            'jenis_lapangan' => 'vinyl',
            'tipe_venue' => 'indoor',
        ]);
        $tanggalMain = Carbon::today()->addDays(2);

        Tarif::create([
            'kategori' => 'standar',
            'tipe_hari' => $tanggalMain->isWeekend() ? 'weekend' : 'weekday',
            'jam_mulai' => '08:00',
            'jam_selesai' => '21:00',
            'harga' => 100000,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('customer.booking.store'), [
                'lapangan_id' => $lapangan->id,
                'tanggal_main' => $tanggalMain->toDateString(),
                'jam_mulai' => '10:00',
                'durasi_jam' => 1,
                'metode_pembayaran' => 'cash',
            ]);

        $booking = Booking::where('user_id', $customer->id)->sole();

        $response->assertRedirect(route('customer.booking.success', $booking));
        $this->assertSame('cash', $booking->metode_pembayaran);
        $this->assertSame('pending', $booking->status_booking);
        $this->assertNull($booking->payment_deadline);
    }

    public function test_second_customer_cannot_book_an_overlapping_time_slot(): void
    {
        $firstCustomer = User::factory()->create();
        $secondCustomer = User::factory()->create();
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Uji',
            'kategori' => 'standar',
            'jenis_lapangan' => 'sintetis',
            'tipe_venue' => 'indoor',
        ]);
        $tanggalMain = Carbon::today()->addDays(2);

        Tarif::create([
            'kategori' => 'standar',
            'tipe_hari' => $tanggalMain->isWeekend() ? 'weekend' : 'weekday',
            'jam_mulai' => '08:00',
            'jam_selesai' => '21:00',
            'harga' => 100000,
        ]);
        Booking::create([
            'user_id' => $firstCustomer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'total_harga' => 200000,
            'status_booking' => 'pending',
            'payment_deadline' => now()->addHour(),
        ]);

        $this->actingAs($secondCustomer)
            ->post(route('customer.booking.store'), [
                'lapangan_id' => $lapangan->id,
                'tanggal_main' => $tanggalMain->toDateString(),
                'jam_mulai' => '11:00',
                'durasi_jam' => 1,
                'metode_pembayaran' => 'midtrans',
            ])
            ->assertSessionHasErrors('jam_mulai');

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_second_customer_can_book_a_time_slot_that_starts_when_the_first_ends(): void
    {
        $firstCustomer = User::factory()->create();
        $secondCustomer = User::factory()->create();
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Uji',
            'kategori' => 'standar',
            'jenis_lapangan' => 'sintetis',
            'tipe_venue' => 'indoor',
        ]);
        $tanggalMain = Carbon::today()->addDays(2);

        Tarif::create([
            'kategori' => 'standar',
            'tipe_hari' => $tanggalMain->isWeekend() ? 'weekend' : 'weekday',
            'jam_mulai' => '08:00',
            'jam_selesai' => '21:00',
            'harga' => 100000,
        ]);
        Booking::create([
            'user_id' => $firstCustomer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'total_harga' => 200000,
            'status_booking' => 'pending',
            'payment_deadline' => now()->addHour(),
        ]);

        $response = $this->actingAs($secondCustomer)
            ->post(route('customer.booking.store'), [
                'lapangan_id' => $lapangan->id,
                'tanggal_main' => $tanggalMain->toDateString(),
                'jam_mulai' => '12:00',
                'durasi_jam' => 1,
                'metode_pembayaran' => 'midtrans',
            ]);

        $createdBooking = Booking::where('user_id', $secondCustomer->id)->sole();
        $response->assertRedirect(route('customer.pembayaran.create', $createdBooking));

        $this->assertSame($lapangan->id, $createdBooking->lapangan_id);
        $this->assertSame($tanggalMain->toDateString(), $createdBooking->tanggal_main->toDateString());
        $this->assertSame('12:00', substr($createdBooking->jam_mulai, 0, 5));
        $this->assertSame('13:00', substr($createdBooking->jam_selesai, 0, 5));
        $this->assertSame('pending', $createdBooking->status_booking);
    }

    public function test_admin_closing_field_cancels_and_refunds_impacted_bookings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Test Tutup',
            'kategori' => 'standar',
            'jenis_lapangan' => 'sintetis',
            'tipe_venue' => 'indoor',
        ]);
        
        $tanggalMain = Carbon::today()->addDays(2);
        
        // Buat booking paid (status: lunas)
        $bookingLunas = Booking::create([
            'user_id' => $customer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:00:00',
            'total_harga' => 100000,
            'status_booking' => 'lunas',
            'payment_deadline' => null,
            'pelunasan_deadline' => null,
        ]);
        
        // Buat booking pending (status: pending)
        $bookingPending = Booking::create([
            'user_id' => $customer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '13:00:00',
            'total_harga' => 100000,
            'status_booking' => 'pending',
            'payment_deadline' => now()->addHour(),
            'pelunasan_deadline' => null,
        ]);

        // Kirim post request sebagai admin untuk menutup lapangan
        $response = $this->actingAs($admin)
            ->post(route('admin.ketersediaan.store'), [
                'lapangan_id' => $lapangan->id,
                'tanggal_mulai' => $tanggalMain->toDateString(),
                'tanggal_selesai' => $tanggalMain->toDateString(),
                'keterangan' => 'Perbaikan Lantai Lapangan',
            ]);

        $response->assertRedirect(route('admin.ketersediaan.index'));

        // Assert booking lunas diubah ke menunggu_keputusan_customer (bukan langsung batal)
        $bookingLunas->refresh();
        $this->assertSame('menunggu_keputusan_customer', $bookingLunas->status_booking);
        $this->assertSame('Perbaikan Lantai Lapangan', $bookingLunas->alasan_penutupan);
        $this->assertNotNull($bookingLunas->opsi_deadline);

        // Assert booking pending langsung dibatalkan (tidak ada uang yang sudah dibayar)
        $bookingPending->refresh();
        $this->assertSame('batal', $bookingPending->status_booking);
        $this->assertNull($bookingPending->payment_deadline);
        $this->assertNull($bookingPending->pelunasan_deadline);

        // Assert notifikasi terkirim ke customer yang lunas (decision notification)
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $customer->id,
            'judul' => 'Lapangan Ditutup — Pilih Opsi Refund / Pindah Lapangan ⚠️',
        ]);
    }

    public function test_check_slots_api_returns_available_slots_correctly(): void
    {
        $customer = User::factory()->create();
        $venue = \App\Models\Venue::create([
            'name' => 'Test Venue',
            'open_time' => '08:00:00',
            'close_time' => '12:00:00',
        ]);
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Test Slots',
            'kategori' => 'standar',
            'jenis_lapangan' => 'vinyl',
            'tipe_venue' => 'indoor',
            'venue_id' => $venue->id,
        ]);
        $tanggalMain = Carbon::today()->addDays(2);

        // Buat booking jam 09:00 - 10:00
        Booking::create([
            'user_id' => $customer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
            'total_harga' => 100000,
            'status_booking' => 'lunas',
        ]);

        $response = $this->actingAs($customer)
            ->get(route('customer.booking.check-slots', [
                'lapangan_id' => $lapangan->id,
                'tanggal' => $tanggalMain->toDateString(),
            ]));

        $response->assertOk();
        $response->assertJsonFragment(['jam_mulai' => '08:00', 'status' => 'available']);
        $response->assertJsonFragment(['jam_mulai' => '08:30', 'status' => 'available']); // 30-min slot
        $response->assertJsonFragment(['jam_mulai' => '09:00', 'status' => 'booked']);
        $response->assertJsonFragment(['jam_mulai' => '09:30', 'status' => 'booked']);    // 30-min sub-slot also booked
        $response->assertJsonFragment(['jam_mulai' => '10:00', 'status' => 'available']);
        $response->assertJsonFragment(['jam_mulai' => '11:00', 'status' => 'available']);
        $response->assertJsonFragment(['jam_mulai' => '11:30', 'status' => 'available']); // 30-min slot
    }

    public function test_booking_with_consecutive_busy_slots_fails(): void
    {
        $firstCustomer = User::factory()->create();
        $secondCustomer = User::factory()->create();
        $venue = \App\Models\Venue::create([
            'name' => 'Test Venue 2',
            'open_time' => '08:00:00',
            'close_time' => '23:00:00',
        ]);
        $lapangan = Lapangan::create([
            'nama_lapangan' => 'Lapangan Test Busy',
            'kategori' => 'standar',
            'jenis_lapangan' => 'vinyl',
            'tipe_venue' => 'indoor',
            'venue_id' => $venue->id,
        ]);
        $tanggalMain = Carbon::today()->addDays(2);

        Tarif::create([
            'kategori' => 'standar',
            'tipe_hari' => $tanggalMain->isWeekend() ? 'weekend' : 'weekday',
            'jam_mulai' => '08:00',
            'jam_selesai' => '23:00',
            'harga' => 100000,
        ]);

        // Booking jam 11:00 - 12:00
        Booking::create([
            'user_id' => $firstCustomer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '11:00:00',
            'jam_selesai' => '12:00:00',
            'total_harga' => 100000,
            'status_booking' => 'lunas',
        ]);

        // Booking jam 10:00 untuk durasi 2 jam (menabrak slot 11:00 yang booked) harus gagal
        $this->actingAs($secondCustomer)
            ->post(route('customer.booking.store'), [
                'lapangan_id' => $lapangan->id,
                'tanggal_main' => $tanggalMain->toDateString(),
                'jam_mulai' => '10:00',
                'durasi_jam' => 2,
                'metode_pembayaran' => 'midtrans',
            ])
            ->assertSessionHasErrors('jam_mulai');
    }
}
