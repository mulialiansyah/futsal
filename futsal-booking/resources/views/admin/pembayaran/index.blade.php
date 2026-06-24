@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran DP')

@section('content')
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 text-lg">Daftar Bukti Transfer DP</h3>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Pelanggan</th>
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Tanggal Main</th>
                    <th class="px-6 py-3 text-left">Nominal DP</th>
                    <th class="px-6 py-3 text-left">Bukti Transfer</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pembayarans as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $p->booking->user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $p->booking->lapangan->nama_lapangan }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($p->booking->tanggal_main)->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-6 py-4 font-semibold text-green-700">Rp {{ number_format($p->nominal_dp, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($p->bukti_transfer)
                            <a href="{{ asset('storage/'.$p->bukti_transfer) }}" target="_blank"
                               class="text-blue-600 hover:underline text-xs font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat Struk
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php $vc = ['menunggu'=>'bg-yellow-100 text-yellow-700','disetujui'=>'bg-green-100 text-green-700','ditolak'=>'bg-red-100 text-red-700']; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $vc[$p->status_verifikasi] ?? '' }}">
                            {{ ucfirst($p->status_verifikasi) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($p->status_verifikasi === 'menunggu')
                        <div class="flex justify-center gap-2">
                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $p) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status_verifikasi" value="disetujui">
                                <button class="bg-green-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-green-700 transition font-semibold">✓ Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $p) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status_verifikasi" value="ditolak">
                                <button class="bg-red-500 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-600 transition font-semibold">✗ Tolak</button>
                            </form>
                        </div>
                        @else
                            <span class="text-gray-400 text-xs">Sudah diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada pembayaran yang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection
