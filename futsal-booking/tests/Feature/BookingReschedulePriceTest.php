<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\Tarif;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingReschedulePriceTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Lapangan $lapanganA;
    protected Lapangan $lapanganB;
    protected Lapangan $lapanganC;

    protected function setUp(): void
    {
        parent::setUp();

        $venue = Venue::create([
            'name' => 'Venue Utama',
            'address' => 'Jl. Futsal No. 1',
            'open_time' => '08:00:00',
            'close_time' => '23:00:00',
        ]);

        $this->customer = User::factory()->create(['role' => 'penyewa']);

        $this->lapanganA = Lapangan::create([
            'venue_id' => $venue->id,
            'nama_lapangan' => 'Lapangan A (Standar)',
            'jenis_lapangan' => 'Vinyl',
            'tipe_venue' => 'Indoor',
            'harga_per_jam' => 100000,
            'kategori' => 'Standar',
        ]);

        $this->lapanganB = Lapangan::create([
            'venue_id' => $venue->id,
            'nama_lapangan' => 'Lapangan B (Mahal)',
            'jenis_lapangan' => 'Vinyl',
            'tipe_venue' => 'Indoor',
            'harga_per_jam' => 150000,
            'kategori' => 'Expensive',
        ]);

        $this->lapanganC = Lapangan::create([
            'venue_id' => $venue->id,
            'nama_lapangan' => 'Lapangan C (Murah)',
            'jenis_lapangan' => 'Vinyl',
            'tipe_venue' => 'Indoor',
            'harga_per_jam' => 50000,
            'kategori' => 'Cheap',
        ]);

        // Seed tariffs
        foreach (['weekday', 'weekend'] as $tipeHari) {
            Tarif::create([
                'kategori' => 'Standar',
                'tipe_hari' => $tipeHari,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '23:00:00',
                'harga' => 100000,
            ]);

            Tarif::create([
                'kategori' => 'Expensive',
                'tipe_hari' => $tipeHari,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '23:00:00',
                'harga' => 150000,
            ]);

            Tarif::create([
                'kategori' => 'Cheap',
                'tipe_hari' => $tipeHari,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '23:00:00',
                'harga' => 50000,
            ]);
        }
    }

    public function test_reschedule_to_cheaper_field_fails_validation(): void
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
            'opsi_deadline' => Carbon::now()->addDays(3),
        ]);

        // Reschedule to Cheap field (Rp50.000) should be blocked because it's cheaper than Rp100.000
        $response = $this->actingAs($this->customer)
            ->post(route('customer.booking.process-reschedule', $booking), [
                'lapangan_id' => $this->lapanganC->id,
                'tanggal_main' => Carbon::tomorrow()->addDay()->toDateString(),
                'jam_mulai' => '10:00',
                'durasi_jam' => 1,
            ]);

        $response->assertSessionHasErrors('jam_mulai');
        
        $booking->refresh();
        $this->assertSame($this->lapanganA->id, $booking->lapangan_id); // Unchanged
    }

    public function test_reschedule_to_expensive_or_equal_field_succeeds(): void
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
            'opsi_deadline' => Carbon::now()->addDays(3),
        ]);

        // Reschedule to Expensive field (Rp150.000) should succeed
        $newDate = Carbon::tomorrow()->addDay()->toDateString();
        $response = $this->actingAs($this->customer)
            ->post(route('customer.booking.process-reschedule', $booking), [
                'lapangan_id' => $this->lapanganB->id,
                'tanggal_main' => $newDate,
                'jam_mulai' => '12:00',
                'durasi_jam' => 1,
            ]);

        $response->assertRedirect(route('customer.booking.show', $booking));
        
        $booking->refresh();
        $this->assertSame($this->lapanganB->id, $booking->lapangan_id);
        $this->assertSame(150000, $booking->total_harga);
    }

    public function test_download_dp_receipt_pdf(): void
    {
        // Booking with lunas status
        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'lunas',
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.booking.download-dp', $booking));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        // Pending status booking should fail to download DP receipt
        $pendingBooking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '13:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'pending',
        ]);

        $responsePending = $this->actingAs($this->customer)
            ->get(route('customer.booking.download-dp', $pendingBooking));

        $responsePending->assertRedirect();
        $responsePending->assertSessionHas('error');
    }

    public function test_payment_proof_upload_validation(): void
    {
        Storage::fake('public');

        $booking = Booking::create([
            'user_id' => $this->customer->id,
            'lapangan_id' => $this->lapanganA->id,
            'tanggal_main' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:00:00',
            'duration_hours' => 1,
            'total_harga' => 100000,
            'metode_pembayaran' => 'midtrans',
            'status_booking' => 'pending',
        ]);

        $pembayaran = Pembayaran::create([
            'booking_id' => $booking->id,
            'nominal' => 50000,
            'metode_pembayaran' => 'midtrans',
            'midtrans_order_id' => 'ORDER-12345',
            'status_verifikasi' => 'pending',
        ]);

        // Upload PDF (invalid extension) should fail
        $invalidFile = UploadedFile::fake()->create('bukti.pdf', 500, 'application/pdf');
        $responsePdf = $this->actingAs($this->customer)
            ->post(route('customer.pembayaran.store', $booking), [
                'midtrans_order_id' => 'ORDER-12345',
                'bukti_transfer' => $invalidFile,
            ]);
        $responsePdf->assertSessionHasErrors('bukti_transfer');

        // Upload PNG of 3MB (over 2MB) should fail
        $largeFile = UploadedFile::fake()->create('bukti.png', 3000, 'image/png');
        $responseLarge = $this->actingAs($this->customer)
            ->post(route('customer.pembayaran.store', $booking), [
                'midtrans_order_id' => 'ORDER-12345',
                'bukti_transfer' => $largeFile,
            ]);
        $responseLarge->assertSessionHasErrors('bukti_transfer');

        // Upload valid PNG under 2MB should succeed
        $validFile = UploadedFile::fake()->create('bukti.png', 1000, 'image/png');
        $responseValid = $this->actingAs($this->customer)
            ->post(route('customer.pembayaran.store', $booking), [
                'midtrans_order_id' => 'ORDER-12345',
                'bukti_transfer' => $validFile,
            ]);
        $responseValid->assertRedirect(route('customer.booking.show', $booking));
        $responseValid->assertSessionHas('success');
    }

    public function test_admin_can_store_and_delete_lapangan_with_correct_validations(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Store valid lapangan
        $responseStore = $this->actingAs($admin)
            ->post(route('admin.lapangan.store'), [
                'nama_lapangan' => 'New Court',
                'kategori' => 'standar',
                'jenis_lapangan' => 'vinyl',
                'tipe_venue' => 'indoor',
                'image' => UploadedFile::fake()->create('court.jpg', 500, 'image/jpeg'),
            ]);

        $responseStore->assertRedirect(route('admin.lapangan.index'));
        $this->assertDatabaseHas('lapangans', ['nama_lapangan' => 'New Court']);

        $lapangan = Lapangan::where('nama_lapangan', 'New Court')->first();

        // 2. Upload invalid size (> 2MB)
        $responseInvalidSize = $this->actingAs($admin)
            ->put(route('admin.lapangan.update', $lapangan), [
                'nama_lapangan' => 'New Court updated',
                'kategori' => 'standar',
                'jenis_lapangan' => 'vinyl',
                'tipe_venue' => 'indoor',
                'image' => UploadedFile::fake()->create('court.jpg', 3000, 'image/jpeg'), // 3MB
            ]);
        $responseInvalidSize->assertSessionHasErrors('image');

        // 3. Upload invalid file type (e.g. text file or unrecognized format)
        $responseInvalidType = $this->actingAs($admin)
            ->put(route('admin.lapangan.update', $lapangan), [
                'nama_lapangan' => 'New Court updated',
                'kategori' => 'standar',
                'jenis_lapangan' => 'vinyl',
                'tipe_venue' => 'indoor',
                'image' => UploadedFile::fake()->create('court.txt', 100, 'text/plain'),
            ]);
        $responseInvalidType->assertSessionHasErrors('image');

        // 4. Delete lapangan
        $responseDelete = $this->actingAs($admin)
            ->delete(route('admin.lapangan.destroy', $lapangan));

        $responseDelete->assertRedirect(route('admin.lapangan.index'));
        $this->assertDatabaseMissing('lapangans', ['id' => $lapangan->id]);
    }
}
