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

        // Assert booking lunas dibatalkan
        $bookingLunas->refresh();
        $this->assertSame('batal', $bookingLunas->status_booking);
        $this->assertNull($bookingLunas->payment_deadline);
        $this->assertNull($bookingLunas->pelunasan_deadline);

        // Assert booking pending dibatalkan
        $bookingPending->refresh();
        $this->assertSame('batal', $bookingPending->status_booking);
        $this->assertNull($bookingPending->payment_deadline);
        $this->assertNull($bookingPending->pelunasan_deadline);

        // Assert notifikasi terkirim
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $customer->id,
            'judul' => 'Booking Dibatalkan & Refund Dana ❌',
        ]);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $customer->id,
            'judul' => 'Booking Lapangan Dibatalkan ❌',
        ]);
    }
}
