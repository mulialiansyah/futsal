<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran DP #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            padding: 30px;
            border-radius: 8px;
            background-color: #fff;
        }
        .header {
            border-bottom: 2px solid #2ecc71;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .header td {
            vertical-align: top;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2ecc71;
        }
        .title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .meta-table td {
            padding: 4px 0;
            font-size: 14px;
        }
        .meta-label {
            font-weight: bold;
            color: #666;
            width: 150px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th {
            background-color: #f9f9f9;
            border-bottom: 2px solid #eee;
            padding: 10px;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            text-align: left;
        }
        .details-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 6px 10px;
            font-size: 14px;
        }
        .summary-label {
            text-align: right;
            font-weight: bold;
            color: #666;
        }
        .summary-value {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background-color: #f1fcf6;
            color: #27ae60;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo">⚽ FUTSALKITE</div>
                    <div style="font-size: 12px; color: #777; margin-top: 5px;">
                        Platform Booking Lapangan Futsal Terbaik
                    </div>
                </td>
                <td class="title">
                    {{ $isLunas ? 'BUKTI PEMBAYARAN LUNAS' : 'BUKTI PEMBAYARAN DP' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Information -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nomor Booking</td>
            <td>: #{{ $booking->id }}</td>
            <td class="meta-label">Tanggal Bayar</td>
            <td>: {{ $paymentDate }}</td>
        </tr>
        <tr>
            <td class="meta-label">Nama Customer</td>
            <td>: {{ $booking->user->name }}</td>
            <td class="meta-label">Metode Pembayaran</td>
            <td style="text-transform: uppercase;">: {{ $booking->metode_pembayaran }}</td>
        </tr>
        <tr>
            <td class="meta-label">Email Customer</td>
            <td>: {{ $booking->user->email }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <!-- Detail Booking -->
    <h3 style="font-size: 15px; border-left: 3px solid #2ecc71; padding-left: 8px; margin-bottom: 12px; color: #333;">DETAIL JADWAL</h3>
    <table class="details-table">
        <thead>
            <tr>
                <th>Lapangan</th>
                <th>Tanggal Main</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Durasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">{{ $booking->lapangan->nama_lapangan }} ({{ $booking->lapangan->venue->name ?? 'Venue Utama' }})</td>
                <td>{{ $booking->tanggal_main->isoFormat('D MMMM YYYY') }}</td>
                <td>{{ substr($booking->jam_mulai, 0, 5) }}</td>
                <td>{{ substr($booking->jam_selesai, 0, 5) }}</td>
                <td>{{ $booking->duration_hours }} Jam</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary / Rincian Biaya -->
    <h3 style="font-size: 15px; border-left: 3px solid #2ecc71; padding-left: 8px; margin-bottom: 12px; color: #333;">RINCIAN PEMBAYARAN</h3>
    <table class="summary-table">
        <tr>
            <td class="summary-label">Total Harga Booking</td>
            <td class="summary-value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
        </tr>
        @if($isLunas)
            <tr class="total-row">
                <td class="summary-label" style="color: #27ae60;">Jumlah yang Dibayar (LUNAS)</td>
                <td class="summary-value" style="color: #27ae60; font-size: 16px;">Rp {{ number_format($booking->total_dibayar, 0, ',', '.') }}</td>
            </tr>
        @else
            <tr class="total-row">
                <td class="summary-label" style="color: #27ae60;">Jumlah yang Dibayar (DP)</td>
                <td class="summary-value" style="color: #27ae60; font-size: 16px;">Rp {{ number_format($dpNominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="summary-label" style="color: #c0392b;">Sisa yang Harus Dilunasi</td>
                <td class="summary-value" style="color: #c0392b;">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
            </tr>
        @endif
    </table>

    <!-- Footer -->
    <div class="footer">
        Terima kasih telah melakukan pemesanan lapangan melalui FutsalKite.<br>
        Harap tunjukkan dokumen bukti pembayaran {{ $isLunas ? 'lunas' : 'DP' }} ini kepada petugas lapangan saat hendak bermain.
    </div>
</div>

</body>
</html>
