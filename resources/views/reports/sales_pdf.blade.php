<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        .muted { color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
        .summary { margin-top: 10px; }
        .summary td { border: none; padding: 3px 0; }
    </style>
;</head>
<body>
    <h1>Laporan Penjualan</h1>
    <div class="muted">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>

    <table class="summary">
        <tr><td>Total Transaksi:</td><td class="right">{{ number_format($totalSales) }}</td></tr>
        <tr><td>Total Pendapatan:</td><td class="right">Rp {{ number_format($totalRevenue,0,',','.') }}</td></tr>
        <tr><td>Total Discount:</td><td class="right">Rp {{ number_format($totalDiscount,0,',','.') }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th class="right">Subtotal</th>
                <th class="right">Discount</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
        @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->code }}</td>
                <td>{{ $sale->date_time->format('d/m/Y H:i') }}</td>
                <td>{{ optional($sale->user)->name }}</td>
                <td class="right">Rp {{ number_format($sale->subtotal,0,',','.') }}</td>
                <td class="right">Rp {{ number_format($sale->discount,0,',','.') }}</td>
                <td class="right">Rp {{ number_format($sale->grand_total,0,',','.') }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="right">Tidak ada data</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

