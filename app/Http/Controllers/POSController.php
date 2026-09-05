<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->active()->orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('pos.index', compact('products', 'categories'));
    }

    public function searchProducts(Request $request)
    {
        $search = $request->search;
        $category = $request->category;

        $products = Product::query()
            ->active()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->select(['id', 'sku', 'name', 'sell_price', 'stock', 'category_id'])
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    public function checkout(CheckoutRequest $request)
    {
        $validated = $request->validated();
        $method = $validated['payment_method'];

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'code' => 'SALE-' . now()->format('Ymd') . '-' . Str::upper(Str::ulid()),
                'date_time' => now(),
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount_total'],
                'tax' => $validated['tax_total'],
                'grand_total' => $validated['grand_total'],
                'created_by' => Auth::id(),
                'payment_status' => $method === 'midtrans' ? 'unpaid' : ($method === 'split' ? 'partial' : 'paid'),
            ]);

            foreach ($validated['cart'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }
                $lineTotal = ($item['price'] * $item['qty']) - ($item['discount'] ?? 0);
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => 0,
                    'line_total' => $lineTotal,
                ]);
                StockService::reduceStock($item['product_id'], $item['qty'], 'sale', $sale->id);
            }

            // Payments
            if ($method === 'cash') {
                Payment::create([
                    'sale_id' => $sale->id,
                    'method' => 'cash',
                    'amount' => $sale->grand_total
                ]);
            } elseif ($method === 'split') {
                $cash = (float) ($validated['split_cash_amount'] ?? 0);
                if ($cash > 0) {
                    Payment::create([
                        'sale_id' => $sale->id,
                        'method' => 'cash',
                        'amount' => min($cash, (float)$sale->grand_total)
                    ]);
                }
                // Re-evaluate status based on paid amount
                $paid = (float) $sale->payments()->sum('amount');
                if ($paid <= 0) {
                    $sale->payment_status = 'unpaid';
                } elseif ($paid < (float) $sale->grand_total) {
                    $sale->payment_status = 'partial';
                } else {
                    $sale->payment_status = 'paid';
                }
                $sale->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'sale_code' => $sale->code,
                'message' => 'Transaksi berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function receipt($id)
    {
        $sale = Sale::with(['user', 'saleItems.product'])->findOrFail($id);
        return view('pos.receipt', compact('sale'));
    }
}

