<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HariLibur;
use App\Models\Lapangan;
use App\Models\Notifikasi;
use App\Models\PenutupanLapangan;
use App\Models\Tarif;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    private const JAM_BUKA = '08:00';

    private const JAM_TUTUP = '21:00';

    /**
     * Daftar booking milik user
     */
    public function index()
    {
        $bookings = Booking::with(['lapangan', 'pembayarans'])
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
        $tarifs = Tarif::select('kategori', 'tipe_hari', 'jam_mulai', 'jam_selesai', 'harga')->get();
        $holidays = HariLibur::pluck('tanggal')->map(fn ($d) => Carbon::parse($d)->toDateString())->values();

        // Data penutupan lapangan (yang masih aktif / akan datang)
        $penutupans = PenutupanLapangan::where('tanggal_selesai', '>=', Carbon::today())
            ->select('lapangan_id', 'tanggal_mulai', 'tanggal_selesai', 'keterangan')
            ->get()
            ->map(fn ($p) => [
                'lapangan_id' => $p->lapangan_id,
                'tanggal_mulai' => $p->tanggal_mulai->toDateString(),
                'tanggal_selesai' => $p->tanggal_selesai->toDateString(),
                'keterangan' => $p->keterangan,
            ]);

        return view('customer.booking.create', compact('lapangans', 'tarifs', 'holidays', 'penutupans'));
    }

    /**
     * Simpan booking baru.
     * jam_selesai & total_harga DIHITUNG DI SERVER (jangan percaya input client).
     */
    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi_jam' => 'required|integer|min:1|max:4',
            'metode_pembayaran' => 'required|in:midtrans,cash',
        ]);

        // Validasi minimal H-2
        $tanggal = Carbon::parse($request->tanggal_main);
        if ($tanggal->lessThanOrEqualTo(Carbon::today()->addDay())) {
            return back()
                ->withErrors(['tanggal_main' => 'Booking minimal harus H-2 (2 hari sebelum main).'])
                ->withInput();
        }

        // ===== CEK PENUTUPAN LAPANGAN =====
        if (PenutupanLapangan::isTutup($request->lapangan_id, $request->tanggal_main)) {
            $penutupan = PenutupanLapangan::where('lapangan_id', $request->lapangan_id)
                ->where('tanggal_mulai', '<=', $request->tanggal_main)
                ->where('tanggal_selesai', '>=', $request->tanggal_main)
                ->first();

            $alasan = $penutupan?->keterangan
                ? "Lapangan sedang ditutup: {$penutupan->keterangan}."
                : 'Lapangan tidak tersedia pada tanggal tersebut.';

            return back()
                ->withErrors(['lapangan_id' => $alasan])
                ->withInput();
        }

        // Booking hanya boleh dimulai dalam jam operasional: 08:00 sampai sebelum 21:00.
        // Gunakan hitungan menit agar 00:00--07:59 tidak keliru dianggap valid.
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $menitMulai = ($jamMulai->hour * 60) + $jamMulai->minute;
        $menitBuka = 8 * 60;
        $menitTutup = 21 * 60;

        if ($menitMulai < $menitBuka || $menitMulai >= $menitTutup) {
            return back()
                ->withErrors(['jam_mulai' => 'Jam mulai hanya tersedia pukul '.self::JAM_BUKA.' sampai sebelum '.self::JAM_TUTUP.'.'])
                ->withInput();
        }

        // Hitung jam selesai di server
        $jamSelesai = $jamMulai->copy()->addHours((int) $request->durasi_jam);

        // Tolak kalau durasi bikin lewat jam tutup (21:00) atau lewat tengah malam
        if (! $jamSelesai->isSameDay($jamMulai) || $jamSelesai->format('H:i') > self::JAM_TUTUP) {
            return back()
                ->withErrors(['durasi_jam' => 'Durasi main melewati jam operasional (tutup pukul '.self::JAM_TUTUP.').'])
                ->withInput();
        }

        // Gunakan format TIME lengkap agar perbandingan konsisten di semua driver database.
        $jamMulaiStr = $jamMulai->format('H:i:s');
        $jamSelesaiStr = $jamSelesai->format('H:i:s');

        // Cek bentrok jadwal
        $bentrok = Booking::where('lapangan_id', $request->lapangan_id)
            ->whereDate('tanggal_main', $request->tanggal_main)
            ->whereIn('status_booking', ['pending', 'dp_dibayar', 'lunas'])
            ->where(function ($query) use ($jamMulaiStr, $jamSelesaiStr) {
                $query->where('jam_mulai', '<', $jamSelesaiStr)
                    ->where('jam_selesai', '>', $jamMulaiStr);
            })->exists();

        if ($bentrok) {
            return back()
                ->withErrors(['jam_mulai' => 'Slot waktu tersebut sudah dipesan. Pilih jam lain.'])
                ->withInput();
        }

        // Hitung harga via PricingService (kategori + hari + jam)
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $totalHarga = PricingService::hitungHarga($lapangan->kategori, $tanggal, $jamMulai, (int) $request->durasi_jam);

        if ($totalHarga <= 0) {
            return back()
                ->withErrors(['jam_mulai' => 'Tarif untuk jadwal yang dipilih belum tersedia. Silakan pilih jadwal lain atau hubungi admin.'])
                ->withInput();
        }

        $bayarDiTempat = $request->metode_pembayaran === 'cash';

        // Booking cash tetap mengunci slot, tetapi tidak memiliki deadline DP.
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $request->lapangan_id,
            'tanggal_main' => $request->tanggal_main,
            'jam_mulai' => $jamMulaiStr,
            'jam_selesai' => $jamSelesaiStr,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_booking' => 'pending',
            'payment_deadline' => $bayarDiTempat ? null : Carbon::now()->addHour(),
        ]);

        // Kirim Notifikasi ke Customer
        Notifikasi::kirim(
            Auth::id(),
            'Booking Baru Dibuat ⚽',
            $bayarDiTempat
                ? "Booking untuk lapangan {$lapangan->nama_lapangan} pada tanggal ".Carbon::parse($request->tanggal_main)->isoFormat('D MMMM YYYY')." jam {$jamMulaiStr} berhasil dibuat. Silakan lakukan pembayaran cash di lokasi saat datang."
                : "Booking untuk lapangan {$lapangan->nama_lapangan} pada tanggal ".Carbon::parse($request->tanggal_main)->isoFormat('D MMMM YYYY')." jam {$jamMulaiStr} berhasil dibuat. Silakan lakukan pembayaran sebelum batas waktu.",
            'booking'
        );

        // Kirim Notifikasi ke Admin
        Notifikasi::kirimKeAdmin(
            $bayarDiTempat ? 'Booking Baru (Cash di Lokasi) 💵' : 'Booking Baru (DP Pending) ⚽',
            'Customer '.Auth::user()->name." membuat booking untuk lapangan {$lapangan->nama_lapangan} pada tanggal ".Carbon::parse($request->tanggal_main)->isoFormat('D MMMM YYYY')." jam {$jamMulaiStr}. ".($bayarDiTempat ? 'Pembayaran dipilih cash di lokasi.' : 'Menunggu pembayaran online.'),
            'booking'
        );

        return redirect()->route('customer.booking.success', $booking);
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['lapangan', 'pembayarans']);

        return view('customer.booking.show', compact('booking'));
    }

    /** Halaman konfirmasi setelah booking berhasil dibuat. */
    public function success(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['lapangan', 'user']);

        return view('customer.booking.success', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['user', 'lapangan']);
        $booking->update(['status_booking' => 'batal']);

        Notifikasi::kirimKeAdmin(
            'Booking Dibatalkan ❌',
            "Customer {$booking->user->name} membatalkan booking lapangan {$booking->lapangan->nama_lapangan} (tanggal ".Carbon::parse($booking->tanggal_main)->isoFormat('D MMMM YYYY')." jam {$booking->jam_mulai}).",
            'booking'
        );

        return redirect()->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
