<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockService
{
    public static function reduceStock($productId, $quantity, $reason = 'sale', $saleId = null)
    {
        return DB::transaction(function () use ($productId, $quantity, $reason, $saleId) {
            $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
            if ($product->stock < $quantity) {
                throw new \Exception("Stok tidak mencukupi. Stok tersedia: {$product->stock}");
            }
            $before = $product->stock;
            $product->decrement('stock', $quantity);
            $after = $product->stock - 0; // ensure int

            StockLog::create([
                'product_id' => $product->id,
                'change' => $after - $before,
                'before_stock' => $before,
                'after_stock' => $after,
                'reason' => 'adjustment',
                'user_id' => Auth::id(),
            ]);

            return $product->fresh();
        });
    }

    public static function addStock($productId, $quantity, $reason = 'adjustment', $saleId = null)
    {
        return DB::transaction(function () use ($productId, $quantity, $reason, $saleId) {
            $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
            $before = $product->stock;
            $product->increment('stock', $quantity);
            $after = $product->stock + 0;

            StockLog::create([
                'product_id' => $product->id,
                'change' => $quantity,
                'before_stock' => $before,
                'after_stock' => $after,
                'reason' => $reason,
                'sale_id' => $saleId,
                'user_id' => Auth::id(),
            ]);

            return $product->fresh();
        });
    }

    public static function adjustStock($productId, $newQuantity)
    {
        return DB::transaction(function () use ($productId, $newQuantity) {
            $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
            $before = $product->stock;
            $product->update(['stock' => $newQuantity]);
            $after = $product->stock;
            StockLog::create([
                'product_id' => $product->id,
                'change' => $after - $before,
                'before_stock' => $before,
                'after_stock' => $after,
                'reason' => 'adjustment',
                'user_id' => Auth::id(),
            ]);
            return $product->fresh();
        });
    }

    public static function getLowStockProducts($threshold = 5)
    {
        return Product::where('stock', '<=', $threshold)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->get();
    }

    public static function getOutOfStockProducts()
    {
        return Product::where('stock', 0)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public static function restoreStockForSale($sale)
    {
        return DB::transaction(function () use ($sale) {
            foreach ($sale->saleItems as $item) {
                self::addStock($item->product_id, $item->qty, 'restore_sale', $sale->id);
            }
        });
    }
}
