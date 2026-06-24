<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SkemaHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
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
        $lapangans    = Lapangan::all();
        $skemaHargas  = SkemaHarga::orderBy('jam_mulai')->get();
        return view('customer.booking.create', compact('lapangans', 'skemaHargas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id'  => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after:today', // minimal H+1 = menolak hari-H
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'total_harga'  => 'required|integer|min:0',
        ]);

        // Validasi H-1: tolak jika tanggal main <= hari ini + 1
        $tanggal = Carbon::parse($request->tanggal_main);
        if ($tanggal->lessThanOrEqualTo(Carbon::today()->addDay())) {
            return back()->withErrors(['tanggal_main' => 'Booking minimal harus H-2 (2 hari sebelum main).'])->withInput();
        }

        // Cek bentrok jadwal di lapangan yang sama
        $bentrok = Booking::where('lapangan_id', $request->lapangan_id)
                          ->where('tanggal_main', $request->tanggal_main)
                          ->where('status_booking', '!=', 'batal')
                          ->where(function ($query) use ($request) {
                              $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                                    ->orWhere(function ($q) use ($request) {
                                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                                          ->where('jam_selesai', '>=', $request->jam_selesai);
                                    });
                          })->exists();

        if ($bentrok) {
            return back()->withErrors(['jam_mulai' => 'Slot waktu tersebut sudah dipesan. Pilih jam lain.'])->withInput();
        }

        Booking::create([
            'user_id'       => Auth::id(),
            'lapangan_id'   => $request->lapangan_id,
            'tanggal_main'  => $request->tanggal_main,
            'jam_mulai'     => $request->jam_mulai,
            'jam_selesai'   => $request->jam_selesai,
            'total_harga'   => $request->total_harga,
            'status_booking' => 'pending',
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
