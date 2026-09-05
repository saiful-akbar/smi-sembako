<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { if (!Schema::hasTable('product_stocks')) { Schema::create('product_stocks', function (Blueprint $t) {
$t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->foreignId('store_id')->constrained()->cascadeOnDelete(); $t->integer('stock')->default(0); $t->timestamps(); }); }
if (!Schema::hasTable('stock_transfers')) { Schema::create('stock_transfers', function (Blueprint $t) { $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->foreignId('from_store_id')->constrained('stores')->cascadeOnDelete(); $t->foreignId('to_store_id')->constrained('stores')->cascadeOnDelete(); $t->integer('qty'); $t->foreignId('user_id')->constrained('users')->cascadeOnDelete(); $t->timestamp('transferred_at')->nullable(); $t->timestamps(); }); } }
public function down(): void { Schema::dropIfExists('stock_transfers'); Schema::dropIfExists('product_stocks'); } };
