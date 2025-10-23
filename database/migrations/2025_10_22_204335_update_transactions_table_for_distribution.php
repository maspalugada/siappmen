<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan hanya jika kolom belum ada
            if (!Schema::hasColumn('transactions', 'instrument_id')) {
                $table->foreignId('instrument_id')->nullable()->constrained('instruments')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'from_unit_id')) {
                $table->foreignId('from_unit_id')->nullable()->constrained('units')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'to_unit_id')) {
                $table->foreignId('to_unit_id')->nullable()->constrained('units')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'status')) {
                $table->enum('status', ['pending', 'delivered', 'received', 'returned'])
                    ->default('pending');
            }

            if (!Schema::hasColumn('transactions', 'occurred_at')) {
                $table->timestamp('occurred_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'instrument_id')) {
                $table->dropForeign(['instrument_id']);
                $table->dropColumn('instrument_id');
            }

            if (Schema::hasColumn('transactions', 'from_unit_id')) {
                $table->dropForeign(['from_unit_id']);
                $table->dropColumn('from_unit_id');
            }

            if (Schema::hasColumn('transactions', 'to_unit_id')) {
                $table->dropForeign(['to_unit_id']);
                $table->dropColumn('to_unit_id');
            }

            if (Schema::hasColumn('transactions', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('transactions', 'occurred_at')) {
                $table->dropColumn('occurred_at');
            }
        });
    }
};
