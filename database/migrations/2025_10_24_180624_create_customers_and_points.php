<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('customers', function (Blueprint $t) {
$t->id(); $t->string('name'); $t->string('email')->nullable(); $t->string('phone')->nullable(); $t->string('member_code')->nullable()->unique(); $t->timestamps(); });
Schema::create('customer_points', function (Blueprint $t) { $t->id(); $t->foreignId('customer_id')->constrained()->cascadeOnDelete(); $t->integer('points'); $t->string('reason')->nullable(); $t->timestamps(); }); }
public function down(): void { Schema::dropIfExists('customer_points'); Schema::dropIfExists('customers'); } };
