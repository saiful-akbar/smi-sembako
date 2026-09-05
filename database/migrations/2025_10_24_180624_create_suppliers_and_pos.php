<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('suppliers', function (Blueprint $t) {
$t->id(); $t->string('name'); $t->string('email')->nullable(); $t->string('phone')->nullable(); $t->text('address')->nullable(); $t->timestamps(); });
Schema::create('purchase_orders', function (Blueprint $t) { $t->id(); $t->foreignId('supplier_id')->constrained()->cascadeOnDelete(); $t->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete(); $t->string('code')->unique(); $t->date('expected_date')->nullable(); $t->enum('status',['draft','ordered','received','cancelled'])->default('draft'); $t->decimal('total',12,2)->default(0); $t->timestamps(); });
Schema::create('purchase_order_items', function (Blueprint $t) { $t->id(); $t->foreignId('purchase_order_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->integer('qty'); $t->decimal('unit_cost',12,2); $t->decimal('line_total',12,2); $t->timestamps(); }); }
public function down(): void { Schema::dropIfExists('purchase_order_items'); Schema::dropIfExists('purchase_orders'); Schema::dropIfExists('suppliers'); } };
