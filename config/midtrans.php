<?php
return [
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    // Setel ke true untuk memaksa mock; default false agar sandbox bisa dipakai di local
    'use_mock' => env('MIDTRANS_USE_MOCK', false),
    // Daftar metode pembayaran yang diizinkan (sesuaikan dengan yang aktif di Dashboard)
    'enabled_payments' => explode(',', env('MIDTRANS_ENABLED_PAYMENTS', 'qris,gopay,shopeepay,bca_va,bni_va,bri_va,permata_va,credit_card')),
];
