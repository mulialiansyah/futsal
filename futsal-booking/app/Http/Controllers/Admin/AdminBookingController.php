<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'lapangan', 'pembayarans'])->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_main', $request->tanggal);
        }

        $bookings = $query->paginate(5)->appends($request->query());

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'lapangan', 'pembayarans']);

        return view('admin.booking.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless(
            in_array($booking->status_booking, ['pending', 'dp_dibayar', 'lunas']),
            422,
            'Booking ini tidak dapat dibatalkan dari status saat ini.'
        );

        $booking->load(['user', 'lapangan']);
        $lapanganNama = $booking->lapangan->nama_lapangan;
        $tanggal = Carbon::parse($booking->tanggal_main)->isoFormat('D MMM YYYY');
        $jam = substr($booking->jam_mulai, 0, 5);

        // Tolak semua pembayaran pending
        $booking->pembayarans()->where('status_verifikasi', 'pending')->update(['status_verifikasi' => 'ditolak']);

        $booking->update([
            'status_booking'   => 'batal',
            'payment_deadline' => null,
            'pelunasan_deadline' => null,
        ]);

        Notifikasi::kirim(
            $booking->user_id,
            'Booking Dibatalkan oleh Admin ❌',
            "Booking lapangan {$lapanganNama} ({$tanggal} jam {$jam}) telah dibatalkan oleh admin."
                . ($booking->total_dibayar > 0 ? ' Silakan hubungi admin untuk informasi pengembalian dana.' : ''),
            'booking'
        );

        Notifikasi::kirimKeAdmin(
            'Booking Dibatalkan (oleh Admin) ❌',
            "Admin membatalkan booking {$lapanganNama} ({$tanggal} jam {$jam}) milik {$booking->user->name}.",
            'booking'
        );

        return redirect()->route('admin.booking.show', $booking)
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function confirmRefund(Booking $booking)
    {
        if (!in_array($booking->status_booking, ['menunggu_refund', 'menunggu_keputusan_customer'])) {
            return back()->with('error', 'Booking ini tidak dalam status menunggu refund.');
        }

        $booking->update([
            'status_booking' => 'batal',
            'opsi_deadline' => null,
            'payment_deadline' => null,
            'pelunasan_deadline' => null,
        ]);

        $booking->load(['user', 'lapangan']);

        Notifikasi::kirim(
            $booking->user_id,
            'Booking Dibatalkan (Menunggu Refund) ✅',
            "Booking lapangan {$booking->lapangan->nama_lapangan} (#{$booking->id}) dibatalkan. Silakan tunggu admin mengirim bukti transfer refund.",
            'booking'
        );

        return back()->with('success', 'Booking dibatalkan. Segera upload bukti transfer refund ke customer.');
    }

    public function storeRefund(Request $request, Booking $booking)
    {
        abort_unless($booking->bisa_direfund, 403, 'Booking ini tidak dapat diproses refund.');

        $maxNominal = (int) $booking->total_dibayar;
        $refundTujuanSudahAda = filled($booking->refund_tujuan);

        $rules = [
            'nominal_refund' => "required|numeric|min:1|max:{$maxNominal}",
            'bukti_refund'   => 'required|file|mimes:png,jpg,jpeg,pdf|max:5120',
            'catatan_refund' => 'nullable|string|max:1000',
        ];
        $messages = [
            'nominal_refund.required' => 'Nominal refund wajib diisi.',
            'nominal_refund.numeric'  => 'Nominal refund harus berupa angka.',
            'nominal_refund.min'      => 'Nominal refund minimal Rp 1.',
            'nominal_refund.max'      => 'Nominal refund maksimal Rp '.number_format($maxNominal, 0, ',', '.').' (total yang sudah dibayar customer).',
            'bukti_refund.required'   => 'Bukti transfer refund wajib diunggah.',
            'bukti_refund.file'       => 'Bukti transfer harus berupa file yang valid.',
            'bukti_refund.mimes'      => 'Format bukti transfer harus PNG, JPG, JPEG, atau PDF.',
            'bukti_refund.max'        => 'Ukuran file maksimal 5MB.',
            'catatan_refund.max'      => 'Catatan maksimal 1000 karakter.',
        ];

        if (! $refundTujuanSudahAda) {
            $rules['refund_tujuan'] = 'required|string|min:8|max:255';
            $messages['refund_tujuan.required'] = 'Tujuan transfer refund wajib diisi (customer belum memberikannya — silakan hubungi customer dan isi secara manual).';
            $messages['refund_tujuan.min']      = 'Tujuan transfer minimal 8 karakter.';
            $messages['refund_tujuan.max']      = 'Tujuan transfer maksimal 255 karakter.';
        }

        $validated = $request->validate($rules, $messages);

        $path = $request->file('bukti_refund')->store('refunds', 'public');
        $nominal = (int) $validated['nominal_refund'];
        $refundTujuanFinal = $refundTujuanSudahAda ? $booking->refund_tujuan : trim($validated['refund_tujuan']);

        $booking->prosesRefund([
            'nominal'           => $nominal,
            'bukti_refund_path' => $path,
            'catatan'           => $validated['catatan_refund'] ?? null,
            'refund_tujuan'     => $refundTujuanFinal,
        ]);

        $booking->load(['user', 'lapangan']);
        $nominalStr = 'Rp '.number_format($nominal, 0, ',', '.');
        $lapanganNama = $booking->lapangan->nama_lapangan;
        $tanggalRefund = $booking->tanggal_refund?->isoFormat('D MMM YYYY, HH:mm') ?? now()->isoFormat('D MMM YYYY, HH:mm');

        Notifikasi::kirim(
            $booking->user_id,
            'Refund Sudah Dikirimkan 💸',
            "Admin sudah mengirim refund sebesar {$nominalStr} untuk booking {$lapanganNama} (#{$booking->id}) pada {$tanggalRefund}. Silakan cek rekening/e-wallet Anda dan unduh bukti transfer di halaman detail booking.",
            'booking'
        );

        Notifikasi::kirimKeAdmin(
            'Refund Booking Berhasil Tercatat 💸',
            "Refund {$nominalStr} untuk booking {$lapanganNama} (#{$booking->id}) milik {$booking->user->name} sudah dicatat ke sistem.",
            'booking'
        );

        return redirect()->route('admin.booking.show', $booking)
            ->with('success', "Refund {$nominalStr} berhasil dicatat. Customer sudah mendapatkan notifikasi.");
    }
}
