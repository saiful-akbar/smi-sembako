@extends('layouts.app')

@section('title', 'Buat Promosi')

@section('content')
    <div class="max-w-2xl mx-auto" x-data="{ type: 'coupon', mode: 'percent' }">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Buat Promosi</h1>
            <p class="text-gray-600">Buat promosi baru untuk menarik pelanggan</p>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('promotions.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nama Promosi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Contoh: Diskon Lebaran">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Tipe Promosi <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" x-model="type" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="coupon">Coupon</option>
                            <option value="time">Time-based</option>
                            <option value="bundle">Bundle</option>
                            <option value="flash">Flash Sale</option>
                            <option value="seasonal">Seasonal</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_at" class="block text-sm font-medium text-gray-700">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_at" name="start_at" required
                                value="{{ old('start_at', now()->format('Y-m-d')) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('start_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_at" class="block text-sm font-medium text-gray-700">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="end_at" name="end_at" required
                                value="{{ old('end_at', now()->addDays(30)->format('Y-m-d')) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('end_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Coupon Configuration -->
                    <template x-if="type === 'coupon'">
                        <div class="space-y-4 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900">Konfigurasi Kupon</h3>

                            <div>
                                <label for="coupon_code" class="block text-sm font-medium text-gray-700">
                                    Kode Kupon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="coupon_code" name="config[code]" required
                                    value="{{ old('config.code') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: SALE10">
                                @error('config.code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe Diskon <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="_disc_mode" value="percent" x-model="mode"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Persen (%)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="_disc_mode" value="amount" x-model="mode"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Nominal (Rp)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div x-show="mode==='percent'">
                                    <label for="discount_percent" class="block text-sm font-medium text-gray-700">
                                        Diskon (%) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="discount_percent" name="config[discount_percent]"
                                        min="0" max="100" step="0.01" x-show="mode==='percent'"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="10">
                                    @error('config.discount_percent')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div x-show="mode==='amount'">
                                    <label for="discount_amount" class="block text-sm font-medium text-gray-700">
                                        Diskon (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="discount_amount" name="config[discount_amount]"
                                        min="0" step="1" x-show="mode==='amount'"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="5000">
                                    @error('config.discount_amount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="product_ids" class="block text-sm font-medium text-gray-700">
                                    Batasi ke Produk (Opsional)
                                </label>
                                <select id="product_ids" name="config[product_ids][]" multiple
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Tekan Ctrl/Cmd untuk memilih multiple produk</p>
                            </div>
                        </div>
                    </template>

                    <!-- Other promotion types can be added here -->

                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" id="active" name="active" value="1" checked
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="active" class="ml-2 block text-sm text-gray-900">
                                Aktifkan promosi ini
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('promotions.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 hover:border-gray-400 rounded-md transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-indigo-300 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 rounded-md transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Buat Promosi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
