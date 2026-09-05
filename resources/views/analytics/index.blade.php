@extends('layouts.app')
@section('title', 'Analytics')
@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Analytics</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded shadow p-4">
                <div class="text-sm text-gray-500">Total Transaksi (30 hari)</div>
                <div class="text-2xl font-bold">{{ number_format($metrics['count']) }}</div>
            </div>
            <div class="bg-white rounded shadow p-4">
                <div class="text-sm text-gray-500">Pendapatan (30 hari)</div>
                <div class="text-2xl font-bold">Rp {{ number_format($metrics['revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded shadow p-4">
                <div class="text-sm text-gray-500">Rata-rata/Transaksi</div>
                <div class="text-2xl font-bold">Rp
                    {{ number_format($metrics['count'] ? $metrics['revenue'] / $metrics['count'] : 0, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded shadow p-4">
                <div class="font-semibold mb-2">7 Hari Terakhir</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th class="p-2 text-left">Tanggal</th>
                                <th class="p-2 text-right">Transaksi</th>
                                <th class="p-2 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daily as $d)
                                <tr class="border-t">
                                    <td class="p-2">{{ \Carbon\Carbon::parse($d->d)->format('d/m') }}</td>
                                    <td class="p-2 text-right">{{ number_format($d->c) }}</td>
                                    <td class="p-2 text-right">Rp {{ number_format($d->s, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded shadow p-4">
                <div class="font-semibold mb-2">Top Produk</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th class="p-2 text-left">Produk</th>
                                <th class="p-2 text-right">Qty</th>
                                <th class="p-2 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top as $t)
                                <tr class="border-t">
                                    <td class="p-2">{{ optional($t->product)->name }}</td>
                                    <td class="p-2 text-right">{{ number_format($t->qty) }}</td>
                                    <td class="p-2 text-right">Rp {{ number_format($t->revenue, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded shadow p-4">
                <div class="font-semibold mb-2">Jam Ramai</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th class="p-2 text-left">Jam</th>
                                <th class="p-2 text-right">Transaksi</th>
                                <th class="p-2 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hours as $h)
                                <tr class="border-t">
                                    <td class="p-2">{{ str_pad($h->h, 2, '0', STR_PAD_LEFT) }}:00</td>
                                    <td class="p-2 text-right">{{ number_format($h->c) }}</td>
                                    <td class="p-2 text-right">Rp {{ number_format($h->s, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded shadow p-4">
                <div class="font-semibold mb-2">Performa Kasir</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th class="p-2 text-left">Kasir</th>
                                <th class="p-2 text-right">Transaksi</th>
                                <th class="p-2 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cashiers as $c)
                                <tr class="border-t">
                                    <td class="p-2">{{ optional($c->user)->name }}</td>
                                    <td class="p-2 text-right">{{ number_format($c->c) }}</td>
                                    <td class="p-2 text-right">Rp {{ number_format($c->s, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
