<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('promotions', function (Blueprint $t) {
$t->id(); $t->string('name'); $t->enum('type',['time','bundle','coupon','flash','seasonal'])->default('time'); $t->timestamp('start_at')->nullable(); $t->timestamp('end_at')->nullable(); $t->json('config')->nullable(); $t->boolean('active')->default(true); $t->timestamps(); });
Schema::create('promotion_products', function (Blueprint $t) { $t->id(); $t->foreignId('promotion_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->timestamps(); }); }
public function down(): void { Schema::dropIfExists('promotion_products'); Schema::dropIfExists('promotions'); } };
