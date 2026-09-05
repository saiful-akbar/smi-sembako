<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { if (!Schema::hasTable('payments')) { Schema::create('payments', function (Blueprint $t) {
$t->id(); $t->foreignId('sale_id')->constrained()->cascadeOnDelete(); $t->enum('method',['cash','qris','ewallet','bank_transfer','credit_card'])->default('cash'); $t->decimal('amount',12,2); $t->string('provider')->nullable(); $t->string('provider_ref')->nullable(); $t->json('meta')->nullable(); $t->timestamps(); }); }
if (!Schema::hasTable('receipts')) { Schema::create('receipts', function (Blueprint $t) { $t->id(); $t->foreignId('sale_id')->constrained()->cascadeOnDelete(); $t->string('channel')->default('print'); $t->string('destination')->nullable(); $t->timestamp('sent_at')->nullable(); $t->json('meta')->nullable(); $t->timestamps(); }); } }
public function down(): void { Schema::dropIfExists('receipts'); Schema::dropIfExists('payments'); } };
