<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use App\Models\Supplier; use Illuminate\Validation\Rule;
class SuppliersController extends Controller {
    public function index(){ $suppliers = Supplier::orderBy('name')->paginate(12); return view('suppliers.index', compact('suppliers')); }
    public function create(){ return view('suppliers.create'); }
    public function store(Request $r){ $data = $r->validate(['name'=>'required|string|max:255','email'=>['nullable','email','max:255',Rule::unique('suppliers','email')],'phone'=>['nullable','max:50'],'address'=>['nullable','string']]); Supplier::create($data); return back()->with('success','Supplier ditambahkan.'); }
    public function edit(Supplier $supplier){ return view('suppliers.edit', compact('supplier')); }
    public function update(Request $r, Supplier $supplier){ $data = $r->validate(['name'=>'required|string|max:255','email'=>['nullable','email','max:255',Rule::unique('suppliers','email')->ignore($supplier->id)],'phone'=>['nullable','max:50'],'address'=>['nullable','string']]); $supplier->update($data); return back()->with('success','Supplier diperbarui.'); }
    public function destroy(Supplier $supplier){ $supplier->delete(); return back()->with('success','Supplier dihapus.'); }
}
