<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get basic statistics
        $totalProducts = \App\Models\Product::count();
        $totalSales = \App\Models\Sale::count();
        $totalRevenue = \App\Models\Sale::sum('grand_total');
        $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)->where('is_active', true)->count();
        $outOfStock = \App\Models\Product::where('stock', '=', 0)->where('is_active', true)->count();
        $criticalProducts = \App\Models\Product::where('stock', '<=', 5)->where('is_active', true)->orderBy('stock')->limit(5)->get();
        
        // Get recent sales
        $recentSales = \App\Models\Sale::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'user',
            'totalProducts', 
            'totalSales', 
            'totalRevenue', 
            'lowStockProducts',
            'outOfStock',
            'criticalProducts',
            'recentSales'
        ));
    }
}
