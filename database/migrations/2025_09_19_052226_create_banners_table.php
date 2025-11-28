<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // Untuk menyimpan path gambar banner
            $table->string('link_url')->nullable(); // URL tujuan jika banner diklik (opsional)
            $table->boolean('is_active')->default(true); // Untuk mengaktifkan/menonaktifkan banner
            $table->integer('order')->default(0); // Untuk urutan tampilan (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};