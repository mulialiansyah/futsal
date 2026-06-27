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
    /**
     * Jam operasional lapangan (06.00 - 23.00).
     */
    private const JAM_BUKA  = 6;
    private const JAM_TUTUP = 23;
    private const PENDING_EXPIRE_MINUTES = 60;

    public function index()
    {
        $bookings = Booking::with(['lapangan', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('customer.booking.index', compact('bookings'));
    }

    public function create()
    {
        $lapangans = Lapangan::all();

        $jamOperasional = collect(range(self::JAM_BUKA, self::JAM_TUTUP))
            ->map(fn($jam) => sprintf('%02d:00', $jam));

        return view('customer.booking.create', compact('lapangans', 'jamOperasional'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id'  => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after:today', // minimal H+1 = menolak hari-H
            'jam_mulai'    => 'required|date_format:H:i',
            'jam_selesai'  => 'required|date_format:H:i|after:jam_mulai',
        ]);


        // Validasi minimal H+2
        // Saat ini kita tolak jika tanggal main <= hari ini + 1 (berarti booking minimal 2 hari ke depan).
        $tanggal = Carbon::parse($request->tanggal_main);
        if ($tanggal->lessThanOrEqualTo(Carbon::today()->addDay())) {
            return back()->withErrors(['tanggal_main' => 'Booking minimal harus H+2 (2 hari setelah hari ini).'])->withInput();
        }


        // Cek bentrok jadwal di lapangan yang sama.
        // Slot dianggap "terkunci" jika:
        //  - status_booking = pending/dp_dibayar/lunas
        //  - khusus pending, slot terkunci hanya sampai pending_expires_at

        $bentrok = Booking::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal_main', $request->tanggal_main)
            ->where('status_booking', '!=', 'batal')
            ->where(function ($q) {
                // pending terkunci hanya sampai pending_expires_at
                $q->where(function ($q1) {
                    $q1->whereIn('status_booking', ['dp_dibayar', 'lunas']);
                })->orWhere(function ($q2) {
                    $q2->where('status_booking', 'pending')
                        ->whereNotNull('pending_expires_at')
                        ->where('pending_expires_at', '>', now());
                });
            })
            ->where(function ($query) use ($request) {
                // overlap
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors(['jam_mulai' => 'Slot waktu tersebut sudah dipesan. Pilih jam lain.'])->withInput();
        }

        // Hitung total harga di SERVER berdasarkan harga lapangan (jangan percaya input client!)
        $lapangan  = Lapangan::findOrFail($request->lapangan_id);
        $durasiJam = Carbon::parse($request->jam_mulai)->diffInMinutes(Carbon::parse($request->jam_selesai)) / 60;
        $totalHarga = (int) round($durasiJam * ($lapangan->harga_per_jam ?? 0));

        $pendingExpiresAt = now()->addMinutes(self::PENDING_EXPIRE_MINUTES);

        Booking::create([
            'user_id'               => Auth::id(),
            'lapangan_id'           => $request->lapangan_id,
            'tanggal_main'          => $request->tanggal_main,
            'jam_mulai'             => $request->jam_mulai,
            'jam_selesai'           => $request->jam_selesai,
            'total_harga'           => $totalHarga,
            'status_booking'        => 'pending',
            'pending_expires_at'   => $pendingExpiresAt,
        ]);

        return redirect()->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibuat! Segera lakukan pembayaran DP.');
    }

    public function show(Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa lihat
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['lapangan', 'pembayaran']);
        return view('customer.booking.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->update(['status_booking' => 'batal']);
        return redirect()->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
