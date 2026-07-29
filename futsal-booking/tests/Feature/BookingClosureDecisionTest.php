<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Tarif;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingClosureDecisionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Lapangan $lapanganA;
    protected Lapangan $lapanganB;

    protected function setUp(): void
    {
        parent::setUp();

        $venue = Venue::create([
            'name' => 'Venue Utama',
            'address' => 'Jl. Futsal No. 1',
            'open_time' => '08:00:00',
            'close_time' => '23:00:00',
        ]);

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'penyewa']);

        $this->lapanganA = Lapangan::create([
            'venue_id' => $venue->id,
            'nama_lapangan' => 'Lapangan A',
            'jenis_lapangan' => 'Vinyl',
            'tipe_venue' => 'Indoor',
            'harga_per_jam' => 100000,
            'kategori' => 'Standar',
        ]);

        $this->lapanganB = Lapangan::create([
            'venue_id' => $venue->id,
            'nama_lapangan' => 'Lapangan B',
            'jenis_lapangan' => 'Rumput Sintetis',
            'tipe_venue' => 'Outdoor',
            'harga_per_jam' => 120000,
            'kategori' => 'Standar',
        ]);

        // Seed tarif untuk kategori 'Standar' agar PricingService::hitungHarga tidak return 0
        foreach (['weekday', 'weekend'] as $tipeHari) {
            Tarif::create([
                'kategori' => 'Standar',
                'tipe_hari' => $tipeHari,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '23:00:00',
                'harga' => 100000,
            ]);
        }
    }

    public function test_field_closure_sets_status_to_menunggu_keputusan_customer_for_paid_bookings(): void
    {
        $tanggal = Carbon::tomorrow()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => $tanggal,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'lunas',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.ketersediaan.store'), [
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $tanggal,
            'keterangan' => 'Renovasi Lantai Lapangan',
        ]);

        $response->assertRedirect(route('admin.ketersediaan.index'));

        $booking->refresh();
        $this->assertSame('menunggu_keputusan_customer', $booking->status_booking);
        $this->assertSame('lunas', $booking->original_status);
        $this->assertSame('Renovasi Lantai Lapangan', $booking->alasan_penutupan);
        $this->assertNotNull($booking->opsi_deadline);
    }

    public function test_customer_can_choose_refund_option(): void
    {
        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '15:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'menunggu_keputusan_customer',
            'original_status' => 'lunas',
            'opsi_deadline' => Carbon::now()->addDays(3),
            'alasan_penutupan' => 'Lantai Rusak',
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.booking.choose-refund', $booking));

        $response->assertRedirect(route('customer.booking.show', $booking));

        $booking->refresh();
        $this->assertSame('menunggu_refund', $booking->status_booking);
        $this->assertNull($booking->opsi_deadline);
    }

    public function test_customer_can_reschedule_to_another_field_and_slot(): void
    {
        $oldDate = Carbon::tomorrow()->toDateString();
        $newDate = Carbon::tomorrow()->addDay()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => $oldDate,
            'jam_mulai' => '14:00:00',
            'jam_selesai' => '15:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'menunggu_keputusan_customer',
            'original_status' => 'lunas',
            'opsi_deadline' => Carbon::now()->addDays(3),
            'alasan_penutupan' => 'Pemeliharaan',
        ]);

        $response = $this->actingAs($this->customer)->post(route('customer.booking.process-reschedule', $booking), [
            'lapangan_id' => $this->lapanganB->id,
            'tanggal_main' => $newDate,
            'jam_mulai' => '16:00',
            'durasi_jam' => 1,
        ]);

        $response->assertRedirect(route('customer.booking.show', $booking));

        $booking->refresh();
        $this->assertSame($this->lapanganB->id, $booking->lapangan_id);
        $this->assertSame($newDate, $booking->tanggal_main->toDateString());
        $this->assertSame('16:00:00', $booking->jam_mulai);
        $this->assertSame('17:00:00', $booking->jam_selesai);
        $this->assertSame('lunas', $booking->status_booking);
        $this->assertNull($booking->opsi_deadline);
        $this->assertNull($booking->alasan_penutupan);
    }

    public function test_expired_closure_decision_automatically_falls_back_to_menunggu_refund(): void
    {
        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'menunggu_keputusan_customer',
            'original_status' => 'lunas',
            'opsi_deadline' => Carbon::now()->subMinutes(10), // Expired
            'alasan_penutupan' => 'Perbaikan Lampu',
        ]);

        $this->artisan('bookings:process-expired-closure-decisions')
            ->assertExitCode(0);

        $booking->refresh();
        $this->assertSame('menunggu_refund', $booking->status_booking);
        $this->assertNull($booking->opsi_deadline);
    }
}
