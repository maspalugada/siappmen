<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            if (!Schema::hasColumn('instruments', 'qr_code')) {            
                $table->string('qr_code')->unique()->nullable()->after('code');
            }
            if (!Schema::hasColumn('instruments', 'status')) {
                
                $table->enum('status', ['available', 'in_use', 'dirty', 'steril'])->default('available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            //
        });
    }
};
