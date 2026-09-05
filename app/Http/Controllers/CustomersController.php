<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Customer, CustomerPoint};
use Illuminate\Validation\Rule;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->paginate(12);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['nullable', 'email', Rule::unique('customers', 'email')],
            'phone' => ['nullable', 'max:50'],
            'member_code' => ['nullable', Rule::unique('customers', 'member_code')]
        ]);

        Customer::create($validated);

        return back()->with('success', 'Pelanggan ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['nullable', 'email', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'max:50'],
            'member_code' => ['nullable', Rule::unique('customers', 'member_code')->ignore($customer->id)]
        ]);

        $customer->update($validated);

        return back()->with('success', 'Pelanggan diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('success', 'Pelanggan dihapus.');
    }

    public function addPoints(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer',
            'reason' => 'nullable|string'
        ]);

        CustomerPoint::create([
            'customer_id' => $customer->id,
            'points' => $validated['points'],
            'reason' => $validated['reason'] ?? null
        ]);

        return back()->with('success', 'Poin ditambahkan.');
    }
}