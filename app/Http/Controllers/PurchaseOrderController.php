<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{PurchaseOrder, PurchaseOrderItem, Supplier, Product, Store};
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $pos = PurchaseOrder::with('supplier')->orderByDesc('id')->paginate(12);
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->limit(100)->get();
        return view('purchases.index', compact('pos', 'suppliers', 'products'));
    }
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->limit(100)->get();
        $stores = Store::orderBy('name')->get();
        return view('purchases.create', compact('suppliers', 'products', 'stores'));
    }
    public function store(Request $r)
    {
        $r->validate(['supplier_id' => 'required|exists:suppliers,id', 'store_id' => 'required|exists:stores,id', 'items' => 'required|array|min:1', 'items.*.product_id' => 'required|exists:products,id', 'items.*.qty' => 'required|integer|min:1', 'items.*.unit_cost' => 'required|numeric|min:0']);
        DB::transaction(function () use ($r) {
            $nextId = PurchaseOrder::max('id') + 1;
            $code = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $po = PurchaseOrder::create(['supplier_id' => $r->supplier_id, 'store_id' => $r->store_id, 'code' => $code, 'status' => 'ordered', 'total' => 0]);
            $total = 0;
            foreach ($r->items as $it) {
                $line = (float)$it['qty'] * (float)$it['unit_cost'];
                PurchaseOrderItem::create(['purchase_order_id' => $po->id, 'product_id' => $it['product_id'], 'qty' => $it['qty'], 'unit_cost' => $it['unit_cost'], 'line_total' => $line]);
                $total += $line;
            }
            $po->total = $total;
            $po->save();
        });
        return back()->with('success', 'PO dibuat.');
    }
    public function edit(PurchaseOrder $purchase)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->limit(100)->get();
        $stores = Store::orderBy('name')->get();
        return view('purchases.edit', compact('purchase', 'suppliers', 'products', 'stores'));
    }
    public function update(Request $r, PurchaseOrder $purchase)
    {
        if ($purchase->status === 'received') {
            return back()->with('error', 'PO yang sudah diterima tidak dapat diedit.');
        }
        $r->validate(['supplier_id' => 'required|exists:suppliers,id', 'store_id' => 'required|exists:stores,id', 'items' => 'required|array|min:1', 'items.*.product_id' => 'required|exists:products,id', 'items.*.qty' => 'required|integer|min:1', 'items.*.unit_cost' => 'required|numeric|min:0']);
        DB::transaction(function () use ($r, $purchase) {
            $purchase->items()->delete();
            $total = 0;
            foreach ($r->items as $it) {
                $line = (float)$it['qty'] * (float)$it['unit_cost'];
                PurchaseOrderItem::create(['purchase_order_id' => $purchase->id, 'product_id' => $it['product_id'], 'qty' => $it['qty'], 'unit_cost' => $it['unit_cost'], 'line_total' => $line]);
                $total += $line;
            }
            $purchase->supplier_id = $r->supplier_id;
            $purchase->store_id = $r->store_id;
            $purchase->total = $total;
            $purchase->save();
        });
        return back()->with('success', 'PO diperbarui.');
    }
    public function destroy(PurchaseOrder $purchase)
    {
        if ($purchase->status === 'received') {
            return back()->with('error', 'PO yang sudah diterima tidak dapat dihapus.');
        }
        $purchase->delete();
        return back()->with('success', 'PO dihapus.');
    }

    public function receive(PurchaseOrder $purchase)
    {
        if ($purchase->status === "received") {
            return back()->with('success', 'PO sudah diterima.');
        }

        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $it) {
                \App\Services\StockService::addStock($it->product_id, (int)$it->qty, 'receive_po');
            }
            $purchase->status = 'received';
            $purchase->save();
        });

        return back()->with('success', 'Stok berhasil ditambahkan dari PO.');
    }
}
