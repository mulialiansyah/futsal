<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HariLibur;
use App\Models\Lapangan;
use App\Models\Tarif;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    private const JAM_BUKA  = '08:00';
    private const JAM_TUTUP = '21:00';

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
     * Form buat booking baru.
     * Kirim juga data tarif & hari libur (buat preview harga real-time di JS).
     */
    public function create()
    {
        $lapangans = Lapangan::all();
        $tarifs    = Tarif::select('kategori', 'tipe_hari', 'jam_mulai', 'jam_selesai', 'harga')->get();
        $holidays  = HariLibur::pluck('tanggal')->map(fn ($d) => Carbon::parse($d)->toDateString())->values();

        return view('customer.booking.create', compact('lapangans', 'tarifs', 'holidays'));
    }

    /**
     * Simpan booking baru.
     * jam_selesai & total_harga DIHITUNG DI SERVER (jangan percaya input client).
     */
    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id'  => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after:today',
            'jam_mulai'    => 'required|date_format:H:i',
            'durasi_jam'   => 'required|integer|min:1|max:4',
        ]);

        // Validasi minimal H-2
        $tanggal = Carbon::parse($request->tanggal_main);
        if ($tanggal->lessThanOrEqualTo(Carbon::today()->addDay())) {
            return back()
                ->withErrors(['tanggal_main' => 'Booking minimal harus H-2 (2 hari sebelum main).'])
                ->withInput();
        }

        // Validasi jam mulai dalam jam operasional (08:00 - 21:00)
        if ($request->jam_mulai < self::JAM_BUKA || $request->jam_mulai > self::JAM_TUTUP) {
            return back()
                ->withErrors(['jam_mulai' => 'Jam mulai harus antara ' . self::JAM_BUKA . ' - ' . self::JAM_TUTUP . '.'])
                ->withInput();
        }

        // Hitung jam selesai di server
        $jamMulai   = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesai = $jamMulai->copy()->addHours((int) $request->durasi_jam);

        // Tolak kalau durasi bikin lewat jam tutup (21:00) atau lewat tengah malam
        if (!$jamSelesai->isSameDay($jamMulai) || $jamSelesai->format('H:i') > self::JAM_TUTUP) {
            return back()
                ->withErrors(['durasi_jam' => 'Durasi main melewati jam operasional (tutup pukul ' . self::JAM_TUTUP . ').'])
                ->withInput();
        }

        $jamMulaiStr   = $jamMulai->format('H:i');
        $jamSelesaiStr = $jamSelesai->format('H:i');

        // Cek bentrok jadwal
        $bentrok = Booking::where('lapangan_id', $request->lapangan_id)
                          ->where('tanggal_main', $request->tanggal_main)
                          ->whereIn('status_booking', ['pending', 'dp_dibayar', 'lunas'])
                          ->where(function ($query) use ($jamMulaiStr, $jamSelesaiStr) {
                              $query->whereBetween('jam_mulai', [$jamMulaiStr, $jamSelesaiStr])
                                    ->orWhereBetween('jam_selesai', [$jamMulaiStr, $jamSelesaiStr])
                                    ->orWhere(function ($q) use ($jamMulaiStr, $jamSelesaiStr) {
                                        $q->where('jam_mulai', '<=', $jamMulaiStr)
                                          ->where('jam_selesai', '>=', $jamSelesaiStr);
                                    });
                          })->exists();

        if ($bentrok) {
            return back()
                ->withErrors(['jam_mulai' => 'Slot waktu tersebut sudah dipesan. Pilih jam lain.'])
                ->withInput();
        }

        // Hitung harga via PricingService (kategori + hari + jam)
        $lapangan   = Lapangan::findOrFail($request->lapangan_id);
        $totalHarga = PricingService::hitungHarga($lapangan->kategori, $tanggal, $jamMulai, (int) $request->durasi_jam);

        // Create booking + payment deadline (auto-release)
        Booking::create([
            'user_id'          => Auth::id(),
            'lapangan_id'      => $request->lapangan_id,
            'tanggal_main'     => $request->tanggal_main,
            'jam_mulai'        => $jamMulaiStr,
            'jam_selesai'      => $jamSelesaiStr,
            'total_harga'      => $totalHarga,
            'status_booking'   => 'pending',
            'payment_deadline' => Carbon::now()->addHour(),
        ]);

        return redirect()->route('customer.booking.index')
                         ->with('success', 'Booking berhasil dibuat! Lakukan pembayaran dalam 1 jam.');
    }

    public function show(Booking $booking)
    {
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
