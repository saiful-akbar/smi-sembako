<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Validation\Rule;

class StoresController extends Controller
{
  public function index()
  {
    $stores = Store::orderBy('name')->paginate(12);
    return view('stores.index', compact('stores'));
  }

  public function create()
  {
    return view('stores.create');
  }

  public function store(Request $r)
  {
    $data = $r->validate(['name' => 'required|string|max:255', 'code' => ['required', 'string', 'max:50', Rule::unique('stores', 'code')], 'address' => ['nullable', 'string'], 'phone' => ['nullable', 'string', 'max:50']]);
    Store::create($data);
    return back()->with('success', 'Toko ditambahkan.');
  }

  public function edit(Store $store)
  {
    return view('stores.edit', compact('store'));
  }

  public function update(Request $r, Store $store)
  {
    $data = $r->validate(['name' => 'required|string|max:255', 'code' => ['required', 'string', 'max:50', Rule::unique('stores', 'code')->ignore($store->id)], 'address' => ['nullable', 'string'], 'phone' => ['nullable', 'string', 'max:50']]);
    $store->update($data);
    return back()->with('success', 'Toko diperbarui.');
  }

  public function destroy(Store $store)
  {
    $store->delete();
    return back()->with('success', 'Toko dihapus.');
  }
}
