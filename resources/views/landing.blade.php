@extends('layouts.app')

@section('title', 'POS UMKM AI')

@section('content')
<div class="min-h-[80vh] flex items-center">
  <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
    <div>
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
        POS UMKM AI – Smart Retail Management for Local Businesses
      </h1>
      <p class="mt-6 text-gray-600 text-lg leading-relaxed">
        Sistem kasir modern dengan dukungan pembayaran digital, analitik real-time,
        multi-toko, CRM pelanggan, inventori & barcode, promosi, integrasi, dan PWA.
      </p>
      <div class="mt-8 flex gap-3">
        <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-3 rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition-all hover:translate-y-0.5">Masuk Aplikasi</a>
        <a href="#fitur" class="inline-flex items-center px-5 py-3 rounded-lg bg-white text-gray-800 border shadow-sm hover:bg-gray-50 transition-all hover:translate-y-0.5">Lihat Fitur</a>
      </div>
    </div>
    <div class="animate-hero-float">
      <img src="/images/pos-umkm-hero.svg" alt="POS UMKM AI – Modern illustration" class="w-full h-auto"/>
    </div>
  </div>
</div>

<div id="fitur" class="max-w-7xl mx-auto px-6 py-12">
  <h2 class="text-2xl font-bold text-gray-900 mb-6">Fitur Utama</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">1. Mobile Responsive & PWA</div><div class="text-gray-600 text-sm mt-1">Akses cepat, dukungan offline-ready.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">2. Payment Methods</div><div class="text-gray-600 text-sm mt-1">QRIS, E-wallet, dan Transfer Bank.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">3. Multi-Store Management</div><div class="text-gray-600 text-sm mt-1">Kelola cabang dalam satu dashboard.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">4. Customer CRM</div><div class="text-gray-600 text-sm mt-1">Riwayat transaksi dan segmentasi.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">5. Inventory & Barcode</div><div class="text-gray-600 text-sm mt-1">Stok, SKU, barcode & peringatan.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">6. Promotions & Discounts</div><div class="text-gray-600 text-sm mt-1">Voucher, happy-hour, dan bundling.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">7. Analytics Dashboard</div><div class="text-gray-600 text-sm mt-1">Grafik penjualan real-time.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">8. Receipt System</div><div class="text-gray-600 text-sm mt-1">Nota digital & kirim WhatsApp.</div></div>
    <div class="p-5 bg-white rounded-xl border shadow-sm hover:shadow-md transition"><div class="text-sm font-semibold text-gray-900">9. Integrations</div><div class="text-gray-600 text-sm mt-1">API & koneksi layanan pihak ketiga.</div></div>
  </div>
</div>
@endsection