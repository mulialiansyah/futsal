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
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
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
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'total_harga' => 200000,
            'status_booking' => 'pending',
            'payment_deadline' => now()->addHour(),
        ]);

        $this->actingAs($secondCustomer)
            ->post(route('customer.booking.store'), [
                'lapangan_id' => $lapangan->id,
                'tanggal_main' => $tanggalMain->toDateString(),
                'jam_mulai' => '12:00',
                'durasi_jam' => 1,
            ])
            ->assertRedirect(route('customer.booking.index'));

        $this->assertDatabaseHas('bookings', [
            'user_id' => $secondCustomer->id,
            'lapangan_id' => $lapangan->id,
            'tanggal_main' => $tanggalMain->toDateString(),
            'jam_mulai' => '12:00:00',
            'jam_selesai' => '13:00:00',
            'status_booking' => 'pending',
        ]);
    }
}
