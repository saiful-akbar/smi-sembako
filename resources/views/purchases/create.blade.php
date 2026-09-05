@extends('layouts.app')

@section('title', 'Buat Purchase Order')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Buat Purchase Order</h1>
            <p class="text-gray-600">Buat purchase order baru untuk restock produk</p>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('purchases.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">
                            Supplier <span class="text-red-500">*</span>
                        </label>
                        <select id="supplier_id" name="supplier_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="store_id" class="block text-sm font-medium text-gray-700">
                            Toko <span class="text-red-500">*</span>
                        </label>
                        <select id="store_id" name="store_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Toko</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        @error('store_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expected_date" class="block text-sm font-medium text-gray-700">
                            Tanggal Diharapkan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="expected_date" name="expected_date" required
                            value="{{ old('expected_date', now()->addDays(7)->format('Y-m-d')) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('expected_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Items <span class="text-red-500">*</span>
                        </label>
                        <div id="items-container">
                            <!-- Items will be added here dynamically -->
                        </div>
                        <button type="button" id="add-item-btn"
                            class="mt-2 inline-flex items-center px-3 py-1.5 text-sm border border-indigo-300 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 rounded-md transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Item
                        </button>
                        @error('items')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('purchases.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 hover:border-gray-400 rounded-md transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
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
                        Buat PO
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    // Pass products data from PHP to JavaScript
    const products = @json($products);

    let itemIndex = 0;

    function addItem(productId = '', qty = 1, unitCost = 0) {
        const container = document.getElementById('items-container');

        // Build the product options HTML
        let productOptions = '<option value="">Pilih Produk</option>';
        products.forEach(product => {
            const selected = productId == product.id ? 'selected' : '';
            productOptions +=
                `<option value="${product.id}" ${selected}>${product.name} (${product.sku})</option>`;
        });

        const itemDiv = document.createElement('div');
        itemDiv.className = 'item-row flex gap-4 items-end mb-4 p-4 border border-gray-200 rounded-md';
        itemDiv.innerHTML = `
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">Produk</label>
            <select name="items[${itemIndex}][product_id]" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                ${productOptions}
            </select>
        </div>
        <div class="w-24">
            <label class="block text-sm font-medium text-gray-700">Qty</label>
            <input type="number" name="items[${itemIndex}][qty]" value="${qty}" min="1" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="w-32">
            <label class="block text-sm font-medium text-gray-700">Harga Beli</label>
            <input type="number" name="items[${itemIndex}][unit_cost]" value="${unitCost}" min="0" step="0.01" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="w-16">
            <button type="button" onclick="removeItem(this)"
                class="w-full px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
        container.appendChild(itemDiv);
        itemIndex++;
    }

    function removeItem(button) {
        button.closest('.item-row').remove();
    }

    // Make sure DOM is loaded before attaching event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const addButton = document.getElementById('add-item-btn');
        if (addButton) {
            addButton.addEventListener('click', function(e) {
                e.preventDefault();
                addItem();
            });
        }

        // Add one item by default
        addItem();
    });
</script>
