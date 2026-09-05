<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('stores', function (Blueprint $t) {
$t->id(); $t->string('name'); $t->string('code')->unique(); $t->string('address')->nullable(); $t->string('phone')->nullable(); $t->timestamps(); });
Schema::create('store_user', function (Blueprint $t) { $t->id(); $t->foreignId('store_id')->constrained()->cascadeOnDelete(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->timestamps(); }); }
public function down(): void { Schema::dropIfExists('store_user'); Schema::dropIfExists('stores'); } };
