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

    private const MAX_BOOKING_DAYS_AHEAD = 30;

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
     * Check available slots for a given field and date.
     */
    public function checkSlots(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|integer|exists:lapangans,id',
            'tanggal' => 'required|date',
        ]);

        $lapangan = Lapangan::with('venue')->findOrFail($request->lapangan_id);
        $venue = $lapangan->venue;

        $openTimeStr = $venue ? $venue->open_time : '08:00:00';
        $closeTimeStr = $venue ? $venue->close_time : '23:00:00';

        $openMinutes  = (int) explode(':', $openTimeStr)[0] * 60 + (int) explode(':', $openTimeStr)[1];
        $closeMinutes = (int) explode(':', $closeTimeStr)[0] * 60 + (int) explode(':', $closeTimeStr)[1];

        $tanggal = $request->tanggal;
        $isTutup = PenutupanLapangan::isTutup($lapangan->id, $tanggal);

        // Fetch bookings that are paid, lunas, or pending & unexpired
        $bookings = Booking::where('lapangan_id', $lapangan->id)
            ->whereDate('tanggal_main', $tanggal)
            ->where(function ($q) {
                $q->whereIn('status_booking', ['dp_dibayar', 'lunas'])
                  ->orWhere(function ($sq) {
                      $sq->where('status_booking', 'pending')
                         ->where('payment_deadline', '>', Carbon::now());
                  });
            })
            ->get();

        $slots = [];
        $now = Carbon::now();
        $isToday = Carbon::parse($tanggal)->isToday();

        // Generate slots per 30 minutes
        for ($min = $openMinutes; $min < $closeMinutes; $min += 30) {
            $startH = intdiv($min, 60);
            $startM = $min % 60;
            $endMin = $min + 30;
            $endH = intdiv($endMin, 60);
            $endM = $endMin % 60;

            $startTimeStr = sprintf('%02d:%02d', $startH, $startM);
            $endTimeStr   = sprintf('%02d:%02d', $endH, $endM);

            $status = 'available';

            if ($isTutup) {
                $status = 'tutup';
            } elseif ($isToday && $now->format('H:i') >= $startTimeStr) {
                $status = 'past';
            } else {
                foreach ($bookings as $b) {
                    $bStart = substr($b->jam_mulai, 0, 5);
                    $bEnd   = substr($b->jam_selesai, 0, 5);
                    // Slot overlaps if it starts within [bStart, bEnd)
                    if ($startTimeStr >= $bStart && $startTimeStr < $bEnd) {
                        $status = 'booked';
                        break;
                    }
                }
            }

            $slots[] = [
                'jam_mulai'  => $startTimeStr,
                'jam_selesai' => $endTimeStr,
                'status'     => $status,
            ];
        }

        return response()->json([
            'slots'      => $slots,
            'open_time'  => substr($openTimeStr, 0, 5),
            'close_time' => substr($closeTimeStr, 0, 5),
            'kategori'   => $lapangan->kategori,
        ]);
    }

    /**
     * Form buat booking baru.
     * Kirim juga data tarif & hari libur (buat preview harga real-time di JS).
     */
    public function create()
    {
        $lapangans = Lapangan::all();
        $venues = \App\Models\Venue::all();
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

        $dates = $this->getDatesWithPriceRange($lapangans, $tarifs);

        return view('customer.booking.create', compact('lapangans', 'venues', 'tarifs', 'holidays', 'penutupans', 'dates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi_jam' => 'required|integer|min:1|max:15',
            'metode_pembayaran' => 'required|in:midtrans,cash',
        ]);

        $tanggal = Carbon::parse($request->tanggal_main);
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);

        if ($tanggal->isToday()) {
            if (Carbon::now()->format('H:i') >= $jamMulai->format('H:i')) {
                return back()
                    ->withErrors(['jam_mulai' => 'Jam mulai harus di masa depan.'])
                    ->withInput();
            }
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

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $venue = $lapangan->venue;

        $openTimeStr = $venue ? $venue->open_time : '08:00:00';
        $closeTimeStr = $venue ? $venue->close_time : '23:00:00';

        $openHour = (int) explode(':', $openTimeStr)[0];
        $closeHour = (int) explode(':', $closeTimeStr)[0];

        $menitMulai = ($jamMulai->hour * 60) + $jamMulai->minute;
        $menitBuka = $openHour * 60;
        $menitTutup = $closeHour * 60;

        if ($menitMulai < $menitBuka || $menitMulai >= $menitTutup) {
            return back()
                ->withErrors(['jam_mulai' => 'Jam mulai harus berada di dalam jam operasional venue (' . substr($openTimeStr, 0, 5) . ' sampai sebelum ' . substr($closeTimeStr, 0, 5) . ').'])
                ->withInput();
        }

        // Hitung jam selesai di server
        $jamSelesai = $jamMulai->copy()->addHours((int) $request->durasi_jam);

        if (!$jamSelesai->isSameDay($jamMulai) || $jamSelesai->format('H:i') > substr($closeTimeStr, 0, 5)) {
            return back()
                ->withErrors(['durasi_jam' => 'Durasi main melewati jam operasional (tutup pukul ' . substr($closeTimeStr, 0, 5) . ').'])
                ->withInput();
        }

        $jamMulaiStr = $jamMulai->format('H:i:s');
        $jamSelesaiStr = $jamSelesai->format('H:i:s');

        // Gunakan DB Transaction + lockForUpdate untuk mencegah race condition (atomic locking)
        try {
            $booking = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $tanggal, $jamMulai, $jamMulaiStr, $jamSelesaiStr, $lapangan) {
                // Kunci baris booking yang overlap untuk tanggal ini
                $existingOverlap = Booking::where('lapangan_id', $request->lapangan_id)
                    ->whereDate('tanggal_main', $request->tanggal_main)
                    ->where(function ($query) {
                        $query->whereIn('status_booking', ['dp_dibayar', 'lunas'])
                            ->orWhere(function ($q) {
                                $q->where('status_booking', 'pending')
                                    ->where('payment_deadline', '>', Carbon::now());
                            });
                    })
                    ->where(function ($query) use ($jamMulaiStr, $jamSelesaiStr) {
                        $query->where('jam_mulai', '<', $jamSelesaiStr)
                            ->where('jam_selesai', '>', $jamMulaiStr);
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($existingOverlap) {
                    throw new \Exception('Slot waktu tersebut sudah dipesan atau sedang dikunci oleh pengguna lain. Silakan pilih waktu lain.');
                }

                // Hitung total harga
                $totalHarga = PricingService::hitungHarga($lapangan->kategori, $tanggal, $jamMulai, (int) $request->durasi_jam);
                if ($totalHarga <= 0) {
                    throw new \Exception('Tarif untuk jadwal yang dipilih belum tersedia. Silakan pilih jadwal lain.');
                }

                $bayarDiTempat = $request->metode_pembayaran === 'cash';
                $paymentDeadline = $bayarDiTempat ? null : Carbon::now()->addMinutes(10);

                return Booking::create([
                    'user_id' => Auth::id(),
                    'lapangan_id' => $request->lapangan_id,
                    'tanggal_main' => $request->tanggal_main,
                    'jam_mulai' => $jamMulaiStr,
                    'jam_selesai' => $jamSelesaiStr,
                    'duration_hours' => (int) $request->durasi_jam,
                    'total_harga' => $totalHarga,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_booking' => 'pending',
                    'payment_deadline' => $paymentDeadline,
                ]);
            });
        } catch (\Exception $e) {
            return back()
                ->withErrors(['jam_mulai' => $e->getMessage()])
                ->withInput();
        }

        $bayarDiTempat = $booking->metode_pembayaran === 'cash';

        // Kirim Notifikasi ke Customer
        Notifikasi::kirim(
            Auth::id(),
            'Booking Baru Dibuat ⚽',
            $bayarDiTempat
                ? "Booking untuk lapangan {$lapangan->nama_lapangan} pada tanggal " . Carbon::parse($request->tanggal_main)->isoFormat('D MMMM YYYY') . " jam {$jamMulaiStr} berhasil dibuat. Silakan lakukan pembayaran cash di lokasi saat datang."
                : "Booking untuk lapangan {$lapangan->nama_lapangan} pada tanggal " . Carbon::parse($request->tanggal_main)->isoFormat('D MMMM YYYY') . " jam {$jamMulaiStr} berhasil dibuat. Silakan lakukan pembayaran online dalam waktu 10 menit.",
            'booking'
        );

        // Kirim Notifikasi ke Admin
        Notifikasi::kirimKeAdmin(
            $bayarDiTempat ? 'Booking Baru (Cash di Lokasi) 💵' : 'Booking Baru (DP Pending) ⚽',
            'Customer ' . Auth::user()->name . " membuat booking untuk lapangan {$lapangan->nama_lapangan} pada tanggal " . Carbon::parse($request->tanggal_main)->isoFormat('D MMMM YYYY') . " jam {$jamMulaiStr}. " . ($bayarDiTempat ? 'Pembayaran dipilih cash di lokasi.' : 'Menunggu pembayaran online.'),
            'booking'
        );

        return redirect()->route(
            $bayarDiTempat ? 'customer.booking.success' : 'customer.pembayaran.create',
            $booking
        );
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

    /** Customer memilih Opsi Refund Dana */
    public function chooseRefund(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        if ($booking->status_booking !== 'menunggu_keputusan_customer') {
            return back()->with('error', 'Booking ini tidak dalam status menunggu keputusan.');
        }

        $validated = $request->validate([
            'refund_tujuan' => 'required|string|min:8|max:255',
        ], [
            'refund_tujuan.required' => 'Tujuan transfer refund wajib diisi (bank/e-wallet + nomor rekening + atas nama).',
            'refund_tujuan.min'      => 'Tujuan transfer minimal 8 karakter.',
            'refund_tujuan.max'      => 'Tujuan transfer maksimal 255 karakter.',
        ]);

        $booking->update([
            'refund_tujuan'  => $validated['refund_tujuan'],
            'status_booking' => 'menunggu_refund',
            'opsi_deadline'  => null,
        ]);
        $booking->load(['user', 'lapangan']);

        Notifikasi::kirimKeAdmin(
            'Permintaan Refund Dana 💰',
            "Customer {$booking->user->name} memilih opsi Refund Dana untuk booking lapangan {$booking->lapangan->nama_lapangan} (ID: #{$booking->id}). Tujuan transfer: {$validated['refund_tujuan']}. Segera proses pengembalian dana.",
            'booking'
        );

        Notifikasi::kirim(
            $booking->user_id,
            'Permintaan Refund Diterima ⌛',
            "Permintaan refund dana Anda untuk booking lapangan {$booking->lapangan->nama_lapangan} (#{$booking->id}) telah diterima. Tujuan transfer tercatat: {$validated['refund_tujuan']}. Admin akan segera memproses pengembalian dana.",
            'booking'
        );

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', 'Permintaan refund dana berhasil diajukan. Admin akan memproses pengembalian dana Anda.');
    }

    /** Customer membatalkan booking DP/Lunas & mengajukan Refund secara langsung */
    public function requestCancelRefund(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        if (!in_array($booking->status_booking, ['dp_dibayar', 'lunas'], true)) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan dengan refund.');
        }

        $validated = $request->validate([
            'refund_tujuan' => 'required|string|min:8|max:255',
        ], [
            'refund_tujuan.required' => 'Tujuan transfer refund wajib diisi (bank/e-wallet + nomor rekening + atas nama).',
            'refund_tujuan.min'      => 'Tujuan transfer minimal 8 karakter.',
            'refund_tujuan.max'      => 'Tujuan transfer maksimal 255 karakter.',
        ]);

        $booking->update([
            'refund_tujuan'  => $validated['refund_tujuan'],
            'status_booking' => 'menunggu_refund',
        ]);
        $booking->load(['user', 'lapangan']);
        $nominalDibayar = 'Rp '.number_format($booking->total_dibayar, 0, ',', '.');

        Notifikasi::kirimKeAdmin(
            'Booking Dibatalkan & Butuh Refund 💸',
            "Customer {$booking->user->name} membatalkan booking lapangan {$booking->lapangan->nama_lapangan} (#{$booking->id}) yang sudah dibayar {$nominalDibayar}. Tujuan transfer: {$validated['refund_tujuan']}. Segera proses refund.",
            'booking'
        );

        Notifikasi::kirim(
            $booking->user_id,
            'Pembatalan & Pengajuan Refund Diterima ⌛',
            "Booking {$booking->lapangan->nama_lapangan} (#{$booking->id}) telah dibatalkan. Pengajuan refund {$nominalDibayar} Anda tercatat dengan tujuan {$validated['refund_tujuan']}. Admin akan segera memproses transfer.",
            'booking'
        );

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', "Booking dibatalkan dan pengajuan refund {$nominalDibayar} telah dikirim. Admin akan memproses transfer maksimal 1x24 jam kerja.");
    }

    /** Form Pindah Lapangan / Reschedule */
    public function rescheduleForm(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        if ($booking->status_booking !== 'menunggu_keputusan_customer') {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Booking ini tidak dapat dipindahkan.');
        }

        $lapangans = Lapangan::all();
        $venues = \App\Models\Venue::all();
        $tarifs = Tarif::select('kategori', 'tipe_hari', 'jam_mulai', 'jam_selesai', 'harga')->get();
        $holidays = HariLibur::pluck('tanggal')->map(fn ($d) => Carbon::parse($d)->toDateString())->values();

        $penutupans = PenutupanLapangan::where('tanggal_selesai', '>=', Carbon::today())
            ->select('lapangan_id', 'tanggal_mulai', 'tanggal_selesai', 'keterangan')
            ->get()
            ->map(fn ($p) => [
                'lapangan_id' => $p->lapangan_id,
                'tanggal_mulai' => $p->tanggal_mulai->toDateString(),
                'tanggal_selesai' => $p->tanggal_selesai->toDateString(),
                'keterangan' => $p->keterangan,
            ]);

        $dates = $this->getDatesWithPriceRange($lapangans, $tarifs);

        return view('customer.booking.reschedule', compact('booking', 'lapangans', 'venues', 'tarifs', 'holidays', 'penutupans', 'dates'));
    }

    /** Proses Pindah Lapangan / Reschedule */
    public function processReschedule(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        if ($booking->status_booking !== 'menunggu_keputusan_customer') {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Booking ini tidak dapat dipindahkan.');
        }

        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi_jam' => 'required|integer|min:1|max:15',
        ]);

        $tanggal = Carbon::parse($request->tanggal_main);
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);

        if ($tanggal->isToday() && Carbon::now()->format('H:i') >= $jamMulai->format('H:i')) {
            return back()->withErrors(['jam_mulai' => 'Jam mulai harus di masa depan.'])->withInput();
        }

        if (PenutupanLapangan::isTutup($request->lapangan_id, $request->tanggal_main)) {
            return back()->withErrors(['lapangan_id' => 'Lapangan sedang ditutup pada tanggal tersebut.'])->withInput();
        }

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $venue = $lapangan->venue;

        $jamSelesai = $jamMulai->copy()->addHours((int) $request->durasi_jam);
        $jamMulaiStr = $jamMulai->format('H:i:s');
        $jamSelesaiStr = $jamSelesai->format('H:i:s');

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $booking, $tanggal, $jamMulai, $jamMulaiStr, $jamSelesaiStr, $lapangan) {
                // Check overlap excluding current booking ID
                $existingOverlap = Booking::where('lapangan_id', $request->lapangan_id)
                    ->where('id', '!=', $booking->id)
                    ->whereDate('tanggal_main', $request->tanggal_main)
                    ->where(function ($query) {
                        $query->whereIn('status_booking', ['dp_dibayar', 'lunas'])
                            ->orWhere(function ($q) {
                                $q->where('status_booking', 'pending')
                                    ->where('payment_deadline', '>', Carbon::now());
                            });
                    })
                    ->where(function ($query) use ($jamMulaiStr, $jamSelesaiStr) {
                        $query->where('jam_mulai', '<', $jamSelesaiStr)
                            ->where('jam_selesai', '>', $jamMulaiStr);
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($existingOverlap) {
                    throw new \Exception('Slot waktu tersebut sudah dipesan oleh pengguna lain. Silakan pilih waktu lain.');
                }

                $newTotalHarga = PricingService::hitungHarga($lapangan->kategori, $tanggal, $jamMulai, (int) $request->durasi_jam);
                if ($newTotalHarga <= 0) {
                    throw new \Exception('Tarif untuk jadwal yang dipilih belum tersedia.');
                }

                if ($newTotalHarga < $booking->total_harga) {
                    throw new \Exception('Harga jadwal baru tidak boleh lebih murah dari booking asli.');
                }

                $originalStatus = $booking->original_status ?? 'dp_dibayar';
                $newStatus = $originalStatus;

                if ($originalStatus === 'lunas' && $newTotalHarga > $booking->total_harga) {
                    $newStatus = 'dp_dibayar';
                }

                $booking->update([
                    'lapangan_id' => $request->lapangan_id,
                    'tanggal_main' => $request->tanggal_main,
                    'jam_mulai' => $jamMulaiStr,
                    'jam_selesai' => $jamSelesaiStr,
                    'duration_hours' => (int) $request->durasi_jam,
                    'total_harga' => $newTotalHarga,
                    'status_booking' => $newStatus,
                    'opsi_deadline' => null,
                    'alasan_penutupan' => null,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jam_mulai' => $e->getMessage()])->withInput();
        }

        $tglFormat = $tanggal->isoFormat('D MMMM YYYY');
        $booking->load(['user', 'lapangan']);

        Notifikasi::kirim(
            $booking->user_id,
            'Booking Berhasil Dipindahkan 🔄',
            "Booking Anda telah berhasil dipindahkan ke lapangan {$lapangan->nama_lapangan} pada tanggal {$tglFormat} jam " . substr($jamMulaiStr, 0, 5) . ".",
            'booking'
        );

        Notifikasi::kirimKeAdmin(
            'Pindah Lapangan oleh Customer 🔄',
            "Customer {$booking->user->name} memindahkan booking #{$booking->id} ke lapangan {$lapangan->nama_lapangan} (tanggal {$tglFormat} jam " . substr($jamMulaiStr, 0, 5) . ").",
            'booking'
        );

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', 'Booking berhasil dipindahkan ke jadwal baru!');
    }

    private function getDatesWithPriceRange($lapangans, $tarifs)
    {
        $dates = [];
        $today = Carbon::today();
        
        $holidayStrings = HariLibur::pluck('tanggal')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $maxDate = $today->copy()->addDays(self::MAX_BOOKING_DAYS_AHEAD);
        $penutupans = PenutupanLapangan::where('tanggal_mulai', '<=', $maxDate)
            ->where('tanggal_selesai', '>=', $today)
            ->get();

        for ($i = 0; $i <= self::MAX_BOOKING_DAYS_AHEAD; $i++) {
            $dateObj = $today->copy()->addDays($i);
            $dateStr = $dateObj->toDateString();

            $closedIds = $penutupans->filter(function($p) use ($dateStr) {
                return $dateStr >= $p->tanggal_mulai->toDateString() && $dateStr <= $p->tanggal_selesai->toDateString();
            })->pluck('lapangan_id')->toArray();

            $activeLaps = $lapangans->reject(fn($lap) => in_array($lap->id, $closedIds));

            $tipeHari = PricingService::isWeekend($dateObj, $holidayStrings) ? 'weekend' : 'weekday';

            $rates = [];
            foreach ($activeLaps as $lap) {
                $lapTarifs = $tarifs->where('kategori', $lap->kategori)->where('tipe_hari', $tipeHari);
                foreach ($lapTarifs as $t) {
                    $rates[] = (int) $t->harga;
                }
            }

            $priceRange = null;
            if (!empty($rates)) {
                $minPrice = min($rates);
                $maxPrice = max($rates);
                if ($minPrice === $maxPrice) {
                    $priceRange = 'Rp ' . number_format($minPrice / 1000, 0) . 'k';
                } else {
                    $priceRange = 'Rp ' . number_format($minPrice / 1000, 0) . 'k-' . number_format($maxPrice / 1000, 0) . 'k';
                }
            } else {
                $priceRange = 'Tutup';
            }

            $dates[] = [
                'value' => $dateStr,
                'day_name' => $dateObj->isoFormat('dddd'),
                'day' => $dateObj->isoFormat('D'),
                'month' => $dateObj->isoFormat('MMM'),
                'is_today' => $i === 0,
                'price_range' => $priceRange,
            ];
        }

        return $dates;
    }

    public function downloadDpReceipt(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        
        if (!in_array($booking->status_booking, ['dp_dibayar', 'lunas', 'menunggu_keputusan_customer', 'menunggu_refund', 'refund_selesai'])) {
            return redirect()->route('customer.booking.show', $booking)
                ->with('error', 'Bukti pembayaran belum tersedia karena pembayaran belum diverifikasi.');
        }

        $dpPayment = $booking->pembayarans()
            ->where('status_verifikasi', 'diterima')
            ->orderBy('created_at', 'asc')
            ->first();

        $dpNominal = $dpPayment ? $dpPayment->nominal : (int)ceil($booking->total_harga * 0.5);
        $sisaTagihan = max(0, $booking->total_harga - $dpNominal);
        $paymentDate = $dpPayment ? $dpPayment->updated_at->isoFormat('D MMMM YYYY HH:mm') : $booking->updated_at->isoFormat('D MMMM YYYY HH:mm');
        
        $isLunas = $booking->sisa_tagihan == 0;

        $booking->load(['user', 'lapangan.venue']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.booking.pembayaran_dp', compact('booking', 'dpNominal', 'sisaTagihan', 'paymentDate', 'isLunas'));
        
        $filename = $isLunas ? "Bukti_Lunas_Booking_{$booking->id}.pdf" : "Bukti_DP_Booking_{$booking->id}.pdf";
        return $pdf->download($filename);
    }
}
