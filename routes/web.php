<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('login'); });

// CSRF token endpoint for AJAX token refresh
Route::get('/csrf-token', function () { return response()->json(['token' => csrf_token()]); })->name('csrf.token');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Categories - Owner & Manager only
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Products - Owner & Manager only
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
        Route::put('/products/{product}/stock', [App\Http\Controllers\ProductController::class, 'adjustStock'])->name('products.stock');
    });

    // POS - All roles
    Route::get('/pos', [App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [App\Http\Controllers\POSController::class, 'searchProducts'])->name('pos.search');
    Route::post('/pos/checkout', [App\Http\Controllers\POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{id}', [App\Http\Controllers\POSController::class, 'receipt'])->name('pos.receipt');

    // Reports - Owner & Manager only
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/daily', [App\Http\Controllers\ReportController::class, 'daily'])->name('reports.daily');
    });

    // Users - Owner only
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy');
    });
});

// Landing (public)
Route::get('/landing', function () { return view('landing'); })->name('landing');

// Owner/Manager modules
Route::middleware(['auth','role:owner,manager'])->group(function(){
    // Stores
    Route::get('/stores', [App\Http\Controllers\StoresController::class,'index'])->name('stores.index');
    Route::get('/stores/create', [App\Http\Controllers\StoresController::class,'create'])->name('stores.create');
    Route::post('/stores', [App\Http\Controllers\StoresController::class,'store'])->name('stores.store');
    Route::get('/stores/{store}/edit', [App\Http\Controllers\StoresController::class,'edit'])->name('stores.edit');
    Route::put('/stores/{store}', [App\Http\Controllers\StoresController::class,'update'])->name('stores.update');
    Route::delete('/stores/{store}', [App\Http\Controllers\StoresController::class,'destroy'])->name('stores.destroy');

    // Customers
    Route::get('/customers', [App\Http\Controllers\CustomersController::class,'index'])->name('customers.index');
    Route::get('/customers/create', [App\Http\Controllers\CustomersController::class,'create'])->name('customers.create');
    Route::post('/customers', [App\Http\Controllers\CustomersController::class,'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [App\Http\Controllers\CustomersController::class,'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [App\Http\Controllers\CustomersController::class,'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [App\Http\Controllers\CustomersController::class,'destroy'])->name('customers.destroy');
    Route::post('/customers/{customer}/points', [App\Http\Controllers\CustomersController::class,'addPoints'])->name('customers.addPoints');

    // Suppliers / Purchases
    Route::get('/suppliers', [App\Http\Controllers\SuppliersController::class,'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [App\Http\Controllers\SuppliersController::class,'create'])->name('suppliers.create');
    Route::post('/suppliers', [App\Http\Controllers\SuppliersController::class,'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [App\Http\Controllers\SuppliersController::class,'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [App\Http\Controllers\SuppliersController::class,'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [App\Http\Controllers\SuppliersController::class,'destroy'])->name('suppliers.destroy');
    Route::get('/purchases', [App\Http\Controllers\PurchaseOrderController::class,'index'])->name('purchases.index');
    Route::get('/purchases/create', [App\Http\Controllers\PurchaseOrderController::class,'create'])->name('purchases.create');
    Route::post('/purchases', [App\Http\Controllers\PurchaseOrderController::class,'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}/edit', [App\Http\Controllers\PurchaseOrderController::class,'edit'])->name('purchases.edit');
    Route::put('/purchases/{purchase}', [App\Http\Controllers\PurchaseOrderController::class,'update'])->name('purchases.update');
    Route::delete('/purchases/{purchase}', [App\Http\Controllers\PurchaseOrderController::class,'destroy'])->name('purchases.destroy');
    Route::post('/purchases/{purchase}/receive', [App\Http\Controllers\PurchaseOrderController::class,'receive'])->name('purchases.receive');

    // Promotions
    Route::get('/promotions', [App\Http\Controllers\PromotionsController::class,'index'])->name('promotions.index');
    Route::get('/promotions/create', [App\Http\Controllers\PromotionsController::class,'create'])->name('promotions.create');
    Route::post('/promotions', [App\Http\Controllers\PromotionsController::class,'store'])->name('promotions.store');
    Route::get('/promotions/{promotion}/edit', [App\Http\Controllers\PromotionsController::class,'edit'])->name('promotions.edit');
    Route::put('/promotions/{promotion}', [App\Http\Controllers\PromotionsController::class,'update'])->name('promotions.update');
    Route::post('/promotions/{promotion}/toggle', [App\Http\Controllers\PromotionsController::class,'toggle'])->name('promotions.toggle');
    Route::delete('/promotions/{promotion}', [App\Http\Controllers\PromotionsController::class,'destroy'])->name('promotions.destroy');

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class,'index'])->name('analytics.index');
});

// Midtrans + Promotions apply endpoints
Route::post('/payments/midtrans/create/{sale}', [App\Http\Controllers\PaymentsController::class,'createSnap'])->name('midtrans.create');
Route::post('/payments/midtrans/callback', [App\Http\Controllers\PaymentsController::class, 'midtransCallback'])->name('midtrans.callback');
Route::post('/promotions/apply', [App\Http\Controllers\PromotionsController::class,'apply'])->middleware('auth')->name('promotions.apply');