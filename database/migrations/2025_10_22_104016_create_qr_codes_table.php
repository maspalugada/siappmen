<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_type'); // contoh: 'order', 'instrument', 'pouch'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('qr_content'); // data terenkripsi (base64)
            $table->string('file_path')->nullable(); // lokasi file PNG di storage
            $table->unsignedBigInteger('generated_by')->nullable(); // user id
            $table->timestamps();

            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
