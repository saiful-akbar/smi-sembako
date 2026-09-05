<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">

    <title>Struk Pembayaran - {{ $sale->code }}</title>
    
    {{-- CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 p-4">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <!-- MIDTRANS PAYMENT BUTTON & SCRIPT -->
        @if (isset($sale) && !empty($sale->midtrans_token) && $sale->payment_status !== 'paid')
            @php
                $snapBase = config('midtrans.is_production')
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js';
            @endphp
            <script src="{{ $snapBase }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                document.getElementById('pay-button').onclick = function() {
                    window.snap.pay("{{ $sale->midtrans_token }}", {
                        onSuccess: function(result) {
                            alert('Pembayaran berhasil!');
                            location.reload();
                        },
                        onPending: function(result) {
                            alert('Pembayaran pending!');
                            location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal!');
                        },
                        onClose: function() {
                            /* modal closed */
                        }
                    });
                };
            </script>
        @endif
        <!-- Header -->
        <div class="text-center mb-6 flex justify-start items-center flex-col">
            <img src="{{ logo() }}" alt="logo" width="150">
            <p class="text-gray-600">Struk Pembayaran</p>
            <div class="mt-2">
                <p class="text-sm text-gray-500">Kode: {{ $sale->code }}</p>
                <p class="text-sm text-gray-500">{{ $sale->date_time->format('d/m/Y H:i:s') }}</p>
                <p class="mt-2">
                    @php
                        $statusColor = match ($sale->payment_status) {
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'partial' => 'bg-amber-100 text-amber-700',
                            default => 'bg-rose-100 text-rose-700',
                        };
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $statusColor }}">Status:
                        {{ strtoupper($sale->payment_status) }}</span>
                </p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mb-4 pb-4 border-b">
            <p class="text-sm text-gray-600">Kasir: {{ $sale->user->name }}</p>
        </div>

        <!-- Items -->
        <div class="mb-4">
            <h3 class="font-semibold text-gray-900 mb-2">Detail Pembelian:</h3>
            @foreach ($sale->saleItems as $item)
                <div class="flex justify-between text-sm mb-2">
                    <div class="flex-1">
                        <p class="font-medium">{{ $item->product->name }}</p>
                        <p class="text-gray-500">
                            {{ $item->qty }} {{ ucwords(strtolower($item->product->unit)) }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-medium">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span>Subtotal:</span>
                <span>{{ $sale->formatted_subtotal }}</span>
            </div>

            @if ($sale->discount > 0)
                <div class="flex justify-between text-sm">
                    <span>Discount:</span>
                    <span>-{{ $sale->formatted_discount }}</span>
                </div>
            @endif

            @if ($sale->tax > 0)
                <div class="flex justify-between text-sm">
                    <span>Pajak:</span>
                    <span>{{ $sale->formatted_tax }}</span>
                </div>
            @endif

            <div class="flex justify-between font-bold text-lg border-t pt-2">
                <span>Total:</span>
                <span>{{ $sale->formatted_grand_total }}</span>
            </div>
        </div>

        @php
            $paid = (float) $sale->payments()->sum('amount');
            $due = max(0, (float) $sale->grand_total - $paid);
            $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
        @endphp

        <!-- Payments Summary -->
        <div class="mt-4 border-t pt-4">
            <h3 class="font-semibold text-gray-900 mb-2">Pembayaran:</h3>
            @if ($sale->payments && $sale->payments->count())
                <div class="space-y-1">
                    @foreach ($sale->payments as $p)
                        <div class="flex justify-between text-sm">
                            <span>{{ strtoupper($p->method) }}{{ $p->provider ? ' · ' . strtoupper($p->provider) : '' }}</span>
                            <span>{{ $fmt((float) $p->amount) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada pembayaran.</p>
            @endif

            <div class="mt-2 border-t pt-2 space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Dibayar</span>
                    <span class="font-medium">{{ $fmt($paid) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Sisa Tagihan</span>
                    <span class="font-medium">{{ $fmt($due) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 mb-2">Terima kasih atas kunjungan Anda!</p>
            <p class="text-xs text-gray-500">Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex space-x-2">
            <button onclick="window.print()" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 hover:border-gray-400 rounded-md transition-colors duration-200 text-sm">
                Cetak Struk
            </button>
            <button onclick="window.close()" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-indigo-300 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 rounded-md transition-colors duration-200 text-sm">
                Tutup
            </button>
        </div>
    </div>

    <style>
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .max-w-md {
                max-width: 100%;
                box-shadow: none;
                border: none;
            }

            button {
                display: none !important;
            }
        }
    </style>
</body>

</html>
