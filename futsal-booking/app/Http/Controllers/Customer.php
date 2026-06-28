<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    private const JAM_BUKA  = 6;
    private const JAM_TUTUP = 23;

    /**
     * Daftar booking milik user
     */
    public function index()
    {
        $bookings = Booking::with(['lapangan', 'pembayaran'])
                           ->where('user_id', Auth::id())
                           ->latest()
                           ->get();
        return view('customer.booking.index', compact('bookings'));
    }

    /**
     * Form buat booking baru
     */
    public function create(Request $request)
    {
        $lapangans = Lapangan::all();

        // Generate jam operasional 06:00 - 23:00
        $jamOperasional = collect(range(self::JAM_BUKA, self::JAM_TUTUP))
            ->map(fn ($jam) => sprintf('%02d:00', $jam));

        $selectedLapanganId = old('lapangan_id', $request->query('lapangan_id'));

        return view('customer.booking.create', compact('lapangans', 'jamOperasional', 'selectedLapanganId'));
    }

    /**
     * Simpan booking baru (set payment_deadline = now + 1 jam)
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'lapangan_id'  => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after:today',
            'jam_mulai'    => 'required|date_format:H:i',
            'jam_selesai'  => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek minimal H-2 (2 hari sebelum main)
        $tanggal = Carbon::parse($request->tanggal_main);
        if ($tanggal->lessThanOrEqualTo(Carbon::today()->addDay())) {
            return back()
                ->withErrors(['tanggal_main' => 'Booking minimal harus H-2 (2 hari sebelum main).'])
                ->withInput();
        }

        // ===== CEK BENTROK JADWAL =====
        $bentrok = Booking::where('lapangan_id', $request->lapangan_id)
                          ->where('tanggal_main', $request->tanggal_main)
                          ->whereIn('status_booking', ['pending', 'dp_dibayar', 'lunas'])
                          ->where(function ($query) use ($request) {
                              $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                                    ->orWhere(function ($q) use ($request) {
                                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                                          ->where('jam_selesai', '>=', $request->jam_selesai);
                                    });
                          })->exists();

        if ($bentrok) {
            return back()
                ->withErrors(['jam_mulai' => 'Slot waktu tersebut sudah dipesan. Pilih jam lain.'])
                ->withInput();
        }

        // ===== HITUNG HARGA DI SERVER (JANGAN PERCAYA INPUT CLIENT) =====
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = Carbon::parse($request->jam_selesai);
        $durasiJam = $jamSelesai->diffInMinutes($jamMulai) / 60;
        $totalHarga = (int) round($durasiJam * ($lapangan->harga_per_jam ?? 0));

        // ===== CREATE BOOKING + SET PAYMENT DEADLINE = NOW + 1 JAM =====
        Booking::create([
            'user_id'            => Auth::id(),
            'lapangan_id'        => $request->lapangan_id,
            'tanggal_main'       => $request->tanggal_main,
            'jam_mulai'          => $request->jam_mulai,
            'jam_selesai'        => $request->jam_selesai,
            'total_harga'        => $totalHarga,
            'status_booking'     => 'pending',
            'payment_deadline'   => Carbon::now()->addHour(),  // 🔑 PENTING: AUTO-RELEASE DEADLINE
        ]);

        return redirect()->route('customer.booking.index')
                         ->with('success', 'Booking berhasil dibuat! Lakukan pembayaran dalam 1 jam.');
    }

    /**
     * Detail booking
     */
    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['lapangan', 'pembayaran']);
        return view('customer.booking.show', compact('booking'));
    }

    /**
     * Batalkan booking
     */
    public function destroy(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->update(['status_booking' => 'batal']);
        return redirect()->route('customer.booking.index')
                         ->with('success', 'Booking berhasil dibatalkan.');
    }
}