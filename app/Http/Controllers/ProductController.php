<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $category = $request->category;
        
        $products = Product::with('category')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($category, function($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->orderBy('name')
            ->paginate(10);
            
        $categories = Category::orderBy('name')->get();
        
        return view('products.index', compact('products', 'categories', 'search', 'category'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function adjustStock(\Illuminate\Http\Request $request, Product $product)
    {
        $data = $request->validate(['new_stock' => ['required','integer','min:0']]);
        $product->update(['stock' => $data['new_stock']]);
        return back()->with('success', 'Stok produk diperbarui.');
    }
}