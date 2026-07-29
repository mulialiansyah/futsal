<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Notifikasi;
use App\Models\PenutupanLapangan;
use Illuminate\Http\Request;

class KetersediaanController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::orderBy('nama_lapangan')->get();
        $penutupans = PenutupanLapangan::with('lapangan')
            ->orderByDesc('tanggal_mulai')
            ->paginate(10);

        return view('admin.ketersediaan.index', compact('lapangans', 'penutupans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:200',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
            'keterangan.required' => 'Alasan penutupan lapangan wajib diisi.',
        ]);

        // Cek apakah sudah ada penutupan yang overlap
        $overlap = PenutupanLapangan::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal_mulai', '<=', $request->tanggal_selesai)
            ->where('tanggal_selesai', '>=', $request->tanggal_mulai)
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors(['tanggal_mulai' => 'Lapangan ini sudah ditutup pada rentang tanggal tersebut.'])
                ->withInput();
        }

        $penutupan = PenutupanLapangan::create($request->only([
            'lapangan_id',
            'tanggal_mulai',
            'tanggal_selesai',
            'keterangan',
        ]));

        $lapangan = Lapangan::find($request->lapangan_id);

        // Cari booking aktif yang terdampak
        $bookingsTerdampak = Booking::with('user')->where('lapangan_id', $request->lapangan_id)
            ->whereIn('status_booking', ['pending', 'dp_dibayar', 'lunas'])
            ->whereDate('tanggal_main', '>=', $request->tanggal_mulai)
            ->whereDate('tanggal_main', '<=', $request->tanggal_selesai)
            ->get();

        foreach ($bookingsTerdampak as $b) {
            $tglBooking = $b->tanggal_main->isoFormat('D MMMM YYYY');
            $jam = substr($b->jam_mulai, 0, 5);
            $alasan = $request->keterangan;

            // Tolak pembayaran pending yang terkait booking ini
            $b->pembayarans()->where('status_verifikasi', 'pending')->update(['status_verifikasi' => 'ditolak']);

            $statusSebelumnya = $b->status_booking;

            if ($statusSebelumnya === 'dp_dibayar' || $statusSebelumnya === 'lunas') {
                // Booking sudah bayar -> beri pilihan Refund atau Pindah Lapangan (3x24 jam)
                $b->update([
                    'status_booking' => 'menunggu_keputusan_customer',
                    'original_status' => $statusSebelumnya,
                    'opsi_deadline' => \Carbon\Carbon::now()->addDays(3),
                    'alasan_penutupan' => $alasan,
                    'payment_deadline' => null,
                    'pelunasan_deadline' => null,
                ]);

                Notifikasi::kirim(
                    $b->user_id,
                    'Lapangan Ditutup — Pilih Opsi Refund / Pindah Lapangan ⚠️',
                    "Pemberitahuan: Lapangan {$lapangan->nama_lapangan} pada tanggal {$tglBooking} jam {$jam} ditutup sementara karena: {$alasan}. Silakan buka detail booking untuk memilih Opsi Refund Dana atau Pindah Lapangan dalam waktu 3x24 jam.",
                    'penutupan'
                );

                Notifikasi::kirimKeAdmin(
                    'Penutupan Lapangan Terdampak Booking ⚠️',
                    "Booking {$lapangan->nama_lapangan} ({$tglBooking} jam {$jam}) milik {$b->user->name} terdampak penutupan lapangan. Customer telah dikirimi pemberitahuan untuk memilih Refund atau Pindah Lapangan (Deadline: 3x24 jam).",
                    'booking'
                );
            } else {
                // Booking belum bayar (pending) -> batal
                $b->update([
                    'status_booking' => 'batal',
                    'payment_deadline' => null,
                    'pelunasan_deadline' => null,
                ]);

                Notifikasi::kirim(
                    $b->user_id,
                    'Booking Lapangan Dibatalkan ❌',
                    "Pemberitahuan: Lapangan {$lapangan->nama_lapangan} yang Anda sewa pada tanggal {$tglBooking} jam {$jam} terpaksa ditutup sementara oleh pengelola karena: {$alasan}. Booking Anda otomatis dibatalkan.",
                    'penutupan'
                );
            }
        }

        return redirect()->route('admin.ketersediaan.index')
            ->with('success', "Lapangan \"{$lapangan->nama_lapangan}\" berhasil ditutup!");
    }

    public function destroy(PenutupanLapangan $ketersediaan)
    {
        $namaLapangan = $ketersediaan->lapangan->nama_lapangan;
        $ketersediaan->delete();

        return redirect()->route('admin.ketersediaan.index')
            ->with('success', "Penutupan lapangan \"{$namaLapangan}\" berhasil dihapus. Lapangan kembali tersedia.");
    }
}
