<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === MASTER DATA ===
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // kode unik alat
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_serialized')->default(false);
            $table->timestamps();
        });

        Schema::create('pouches', function (Blueprint $table) {
            $table->id();
            $table->string('pouch_code')->unique(); // kode QR di pouch
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['clean','dirty','in_use','lost'])->default('clean');
            $table->timestamps();
        });

        // === TRANSAKSI & ORDER ===
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->date('date_request');
            $table->date('date_return_planned')->nullable();
            $table->enum('status', ['pending','approved','rejected','completed','cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->integer('qty')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'return_dirty',   // pengembalian instrumen kotor
                'pickup',         // pengambilan kotor oleh CSSD
                'borrow',         // peminjaman
                'distribute',     // distribusi steril
                'handover'        // serah terima antar petugas
            ]);
            $table->foreignId('reference_id')->nullable(); // misal order_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // aktor (CSSD/unit)
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('occurred_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->integer('qty')->default(1);
            $table->boolean('is_complete')->default(true);
            $table->integer('damaged_qty')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('pouches');
        Schema::dropIfExists('instruments');
        Schema::dropIfExists('units');
    }
};
