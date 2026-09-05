@extends('layouts.app')

@section('title', 'POS / Kasir')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="posApp()" x-init="initProducts()">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">POS / Kasir</h1>
            <p class="text-gray-600">Pilih produk dan proses transaksi</p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" placeholder="Cari nama atau SKU produk..." x-model.debounce.300ms="search"
                    @input="searchProducts()"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">

                <input type="text" placeholder="Scan / ketik barcode SKU lalu Enter"
                    @keydown.enter.prevent="addBySku($event.target.value); $event.target.value='';"
                    class="flex-1 px-3 py-2 border border-indigo-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 order-first sm:order-none">

                <select x-model="category" @change="searchProducts()"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Products -->
            <div class="lg:col-span-2 bg-white shadow rounded-lg p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="p in products" :key="p.id">
                        <div class="border rounded-lg p-3 hover:shadow-sm"
                            :class="{ 'border-red-300': p.stock <= 5, 'opacity-60 pointer-events-none': p.stock === 0 }">
                            <div class="font-medium text-gray-900" x-text="p.name"></div>
                            <div class="text-sm text-gray-500" x-text="p.sku"></div>
                            <div class="mt-1 text-indigo-700 font-semibold" x-text="formatCurrency(p.sell_price)"></div>
                            <div class="text-xs mt-1"
                                :class="p.stock === 0 ? 'text-red-600' : (p.stock <= 5 ? 'text-orange-600' : 'text-gray-500')">
                                Stok: <span x-text="p.stock"></span>
                            </div>
                            <button
                                class="mt-3 w-full inline-flex items-center justify-center px-3 py-1.5 border border-indigo-300 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 rounded-md transition-colors duration-200 text-sm"
                                @click="addToCart(p)" :disabled="p.stock === 0">Tambah</button>
                        </div>
                    </template>
                    <template x-if="!products || products.length===0">
                        <div class="col-span-full text-center text-sm text-gray-500 py-6">Tidak ada produk aktif. Coba
                            tambah/aktifkan produk atau bersihkan filter.</div>
                    </template>
                </div>
            </div>

            <!-- Cart -->
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Keranjang</h3>
                <template x-if="cart.length === 0">
                    <p class="text-sm text-gray-500">Belum ada item.</p>
                </template>
                <div class="space-y-3 max-h-[55vh] overflow-auto custom-scrollbar">
                    <template x-for="(item, idx) in cart" :key="item.product_id">
                        <div class="border rounded p-3">
                            <div class="flex justify-between">
                                <div>
                                    <div class="font-medium" x-text="item.name"></div>
                                    <div class="text-xs text-gray-500" x-text="item.sku"></div>
                                </div>
                                <button class="text-red-600 text-sm" @click="removeItem(idx)">Hapus</button>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button class="px-2 py-1 border rounded" @click="decQty(idx)">-</button>
                                    <input type="number" min="1" class="w-16 px-2 py-1 border rounded"
                                        x-model.number="item.qty" @change="recalc()">
                                    <button class="px-2 py-1 border rounded" @click="incQty(idx)">+</button>
                                </div>
                                <div class="font-semibold" x-text="formatCurrency(item.qty * item.price)"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-4 border-t pt-3 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span x-text="formatCurrency(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Diskon</span>
                        <input type="number" min="0" class="w-28 px-2 py-1 border rounded text-right"
                            x-model.number="discount_total" @input="recalc()">
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Pajak</span>
                        <input type="number" min="0" class="w-28 px-2 py-1 border rounded text-right"
                            x-model.number="tax_total" @input="recalc()">
                    </div>
                    <div class="flex items-center gap-3 text-sm pt-1">
                        <span class="font-medium">Metode:</span>
                        <label class="inline-flex items-center gap-1"><input type="radio" name="payment_method"
                                value="cash" x-model="payment_method"> <span>Cash</span></label>
                        <label class="inline-flex items-center gap-1"><input type="radio" name="payment_method"
                                value="midtrans" x-model="payment_method"> <span>Midtrans</span></label>
                        <label class="inline-flex items-center gap-1"><input type="radio" name="payment_method"
                                value="split" x-model="payment_method"> <span>Split</span></label>
                    </div>
                    <div x-show="payment_method==='split'" class="mt-2 flex items-center gap-2 text-sm">
                        <label class="text-slate-700">Cash:</label>
                        <input name="split_cash_amount" x-model.number="split_cash_amount" type="number" min="0"
                            step="0.01" class="w-36 px-2 py-1 border rounded text-right" placeholder="0" />
                        <span class="text-xs text-slate-500">Sisa via Midtrans: <strong
                                x-text="formatCurrency(Math.max(0, (grand_total||0) - (split_cash_amount||0)))"></strong></span>
                    </div>
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <label class="text-slate-700">Kupon:</label>
                        <input id="coupon" type="text" class="w-36 px-2 py-1 border rounded"
                            placeholder="NAMA/KODE" />
                        <button type="button" class="px-2 py-1 border rounded" @click="applyPromos()">Terapkan
                            Promo</button>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span>Total (dibayar)</span>
                        <span
                            x-text="formatCurrency(payment_method==='split' ? Math.max(0, (grand_total||0) - (split_cash_amount||0)) : grand_total)"></span>
                    </div>
                    <template x-if="payment_method==='split'">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Ringkasan</span>
                            <span>
                                <span class="line-through mr-2" x-text="formatCurrency(grand_total)"></span>
                                <span>= Cash </span><span
                                    x-text="formatCurrency(Math.min(split_cash_amount||0, grand_total||0))"></span>
                                <span> + Midtrans </span><span
                                    x-text="formatCurrency(Math.max(0, (grand_total||0) - (split_cash_amount||0)))"></span>
                            </span>
                        </div>
                    </template>
                </div>

                <form id="checkoutForm" action="{{ route('pos.checkout') }}" method="POST"
                    @submit.prevent="submitCheckout">
                    @csrf
                    <input type="hidden" name="subtotal" :value="subtotal">
                    <input type="hidden" name="discount_total" :value="discount_total">
                    <input type="hidden" name="tax_total" :value="tax_total">
                    <input type="hidden" name="grand_total" :value="grand_total">
                    <input type="hidden" name="payment_method" :value="payment_method">
                    <div id="cartFields"></div>
                    <button type="submit" :disabled="cart.length === 0"
                        class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 text-green-700 bg-green-50 hover:bg-green-100 hover:border-green-400 rounded-md transition-colors duration-200 text-sm">
                        Proses Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    @php
        $initialProducts = $products
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'sku' => $p->sku,
                    'name' => $p->name,
                    'sell_price' => (float) $p->sell_price,
                    'stock' => (int) $p->stock,
                ];
            })
            ->values();
    @endphp

    @php
        $snapBase = config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $snapClientKey = config('midtrans.client_key');
    @endphp

    @if (!empty($snapClientKey))
        <script src="{{ $snapBase }}" data-client-key="{{ $snapClientKey }}"></script>
    @endif

    <script>
        function posApp() {
            return {
                products: @json($initialProducts),
                search: '',
                category: '',
                cart: [],
                subtotal: 0,
                discount_total: 0,
                tax_total: 0,
                grand_total: 0,
                payment_method: 'cash',
                split_cash_amount: 0,

                initProducts() {
                    this.recalc();
                    if (!this.products || this.products.length === 0) {
                        this.searchProducts();
                    }
                },

                formatCurrency(v) {
                    v = Number(v || 0);
                    return 'Rp ' + v.toLocaleString('id-ID');
                },

                async searchProducts() {
                    const params = new URLSearchParams();
                    if (this.search) params.set('search', this.search);
                    if (this.category) params.set('category', this.category);
                    try {
                        const res = await fetch(`{{ route('pos.search') }}?${params.toString()}`);
                        const data = await res.json();
                        this.products = data.map(p => ({
                            id: p.id,
                            sku: p.sku,
                            name: p.name,
                            sell_price: parseFloat(p.sell_price),
                            stock: parseInt(p.stock, 10)
                        }));
                    } catch (e) {
                        console.error(e);
                    }
                },

                addToCart(p) {
                    const found = this.cart.find(i => i.product_id === p.id);
                    const currentQty = found ? found.qty : 0;
                    if (currentQty + 1 > (p.stock ?? 0)) {
                        alert('Stok tidak mencukupi.');
                        return;
                    }
                    if (found) {
                        found.qty += 1;
                    } else {
                        this.cart.push({
                            product_id: p.id,
                            sku: p.sku,
                            name: p.name,
                            qty: 1,
                            price: p.sell_price,
                            discount: 0,
                            max: (p.stock ?? 0)
                        });
                    }
                    this.recalc();
                },
                removeItem(idx) {
                    this.cart.splice(idx, 1);
                    this.recalc();
                },
                incQty(idx) {
                    this.cart[idx].qty += 1;
                    this.recalc();
                },
                decQty(idx) {
                    if (this.cart[idx].qty > 1) {
                        this.cart[idx].qty -= 1;
                        this.recalc();
                    }
                },

                recalc() {
                    this.subtotal = this.cart.reduce((s, i) => s + (i.qty * i.price) - (i.discount || 0), 0);
                    this.grand_total = Math.max(0, this.subtotal - (this.discount_total || 0) + (this.tax_total || 0));
                },

                async applyPromos() {
                    try {
                        const tokenMeta = document.querySelector("meta[name='csrf-token']");
                        const body = {
                            cart: this.cart.map(i => ({
                                product_id: i.product_id,
                                qty: i.qty,
                                price: i.price
                            })),
                            coupon: document.getElementById('coupon')?.value || ''
                        };
                        const res = await fetch("{{ route('promotions.apply') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': tokenMeta?.content || ''
                            },
                            body: JSON.stringify(body)
                        });
                        const data = await res.json();
                        if (data && typeof data.total_discount !== 'undefined') {
                            this.discount_total = parseFloat(data.total_discount) || 0;
                            this.recalc();
                            if (Array.isArray(data.applied) && data.applied.length) {
                                alert('Promo diterapkan: ' + data.applied.join(', '));
                            } else if ((this.discount_total || 0) === 0) {
                                alert('Kupon tidak cocok atau tidak aktif.');
                            }
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Gagal menerapkan promo');
                    }
                },

                async submitCheckout() {
                    const form = document.getElementById('checkoutForm');
                    const fields = document.getElementById('cartFields');
                    fields.innerHTML = '';
                    this.cart.forEach((item, idx) => {
                        for (const [k, v] of Object.entries({
                                product_id: item.product_id,
                                qty: item.qty,
                                price: item.price,
                                discount: item.discount || 0
                            })) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `cart[${idx}][${k}]`;
                            input.value = v;
                            fields.appendChild(input);
                        }
                    });

                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const doPost = async () => {
                        const fd = new FormData(form);
                        // Pastikan nilai split cash terkirim meski input tersembunyi
                        if (this.payment_method === 'split') {
                            fd.set('split_cash_amount', this.split_cash_amount || 0);
                        }
                        return fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': tokenMeta?.content || '',
                                'Accept': 'application/json'
                            },
                            body: fd
                        });
                    };
                    try {
                        let res = await doPost();
                        if (res.status === 419) {
                            try {
                                const t = await fetch('{{ url('/csrf-token') }}');
                                const j = await t.json();
                                if (tokenMeta) tokenMeta.content = j.token;
                                res = await doPost();
                            } catch (_) {}
                        }
                        const data = await res.json();
                        if (data.success) {
                            const receiptUrl = `{{ url('pos/receipt') }}/${data.sale_id}`;
                            const willUseMidtrans = (this.payment_method === 'midtrans' || this.payment_method ===
                                'split');

                            if (willUseMidtrans) {
                                try {
                                    const mid = await fetch(`{{ url('payments/midtrans/create') }}/${data.sale_id}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': tokenMeta?.content || ''
                                        }
                                    });
                                    const m = await mid.json();
                                    if (m.mock) {
                                        // Simulasi sukses di mode dev: arahkan ke struk
                                        window.open(receiptUrl, '_blank');
                                        alert('Mode dev: transaksi Midtrans disimulasikan');
                                        this.cart = [];
                                        this.discount_total = 0;
                                        this.tax_total = 0;
                                        this.payment_method = 'cash';
                                        this.recalc();
                                    } else if (m.token && window.snap && typeof window.snap.pay === 'function') {
                                        const self = this;
                                        window.snap.pay(m.token, {
                                            onSuccess: function() {
                                                alert('Pembayaran berhasil.');
                                                window.open(receiptUrl, '_blank');
                                                self.cart = [];
                                                self.discount_total = 0;
                                                self.tax_total = 0;
                                                self.payment_method = 'cash';
                                                self.recalc();
                                            },
                                            onPending: function() {
                                                alert(
                                                    'Pembayaran pending. Selesaikan di aplikasi pembayaran.');
                                            },
                                            onError: function() {
                                                alert('Pembayaran gagal.');
                                            },
                                            onClose: function() {
                                                // User menutup popup tanpa menyelesaikan pembayaran
                                            }
                                        });
                                    } else if (m.redirect_url) {
                                        // Redirect style: buka halaman Midtrans; struk dibuka hanya saat sukses (tidak otomatis)
                                        window.open(m.redirect_url, '_blank');
                                    } else {
                                        alert('Gagal memulai Midtrans' + (m && m.message ? (': ' + m.message) : '.'));
                                    }
                                } catch (e) {
                                    console.error(e);
                                    alert('Gagal membuat transaksi Midtrans');
                                }
                            } else {
                                // Cash: langsung arahkan ke struk
                                window.open(receiptUrl, '_blank');
                                alert('Transaksi berhasil.');
                                this.cart = [];
                                this.discount_total = 0;
                                this.tax_total = 0;
                                this.payment_method = 'cash';
                                this.recalc();
                            }
                        } else {
                            alert(data.message || 'Gagal menyimpan transaksi');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan saat checkout');
                        console.error(e);
                    }
                }
            }
        }
    </script>
@endsection
