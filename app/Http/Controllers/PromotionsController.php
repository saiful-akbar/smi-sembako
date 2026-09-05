<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Promotion, Product};

class PromotionsController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderByDesc('id')->paginate(12);
        $products = Product::orderBy('name')->limit(100)->get();

        return view('promotions.index', compact('promotions', 'products'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->limit(100)->get();
        return view('promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:time,bundle,coupon,flash,seasonal',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'config' => 'nullable'
        ]);

        $configRaw = $request->input('config');
        if (is_string($configRaw) && trim($configRaw) !== '') {
            $decoded = json_decode($configRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['config' => 'Config harus berupa JSON valid'])->withInput();
            }
            $validated['config'] = $decoded;
        } elseif (is_array($configRaw)) {
            $validated['config'] = $configRaw;
        }

        $validated['active'] = true;
        Promotion::create($validated);

        return back()->with('success', 'Promo dibuat.');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::orderBy('name')->limit(100)->get();
        return view('promotions.edit', compact('promotion', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:time,bundle,coupon,flash,seasonal',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'config' => 'nullable'
        ]);

        $configRaw = $request->input('config');
        if (is_string($configRaw) && trim($configRaw) !== '') {
            $decoded = json_decode($configRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['config' => 'Config harus berupa JSON valid'])->withInput();
            }
            $validated['config'] = $decoded;
        } elseif (is_array($configRaw)) {
            $validated['config'] = $configRaw;
        }

        $promotion->update($validated);

        return back()->with('success', 'Promo diperbarui.');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->active = !$promotion->active;
        $promotion->save();

        return back()->with('success', 'Status promo diperbarui.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'Promo dihapus.');
    }

    public function apply(Request $request)
    {
        $cart = (array) $request->input('cart', []);
        $coupon = $request->input('coupon');
        $result = \App\Services\PromotionEngine::apply($cart, $coupon);

        return response()->json($result);
    }
}
