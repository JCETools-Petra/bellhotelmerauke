<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_overrides', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Tanggal berlakunya harga khusus
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Tipe kamar yang harganya diubah
            $table->decimal('price', 15, 2); // Harga khusus pada tanggal tersebut
            $table->timestamps();

            // Menambahkan unique constraint agar tidak ada harga ganda untuk kamar yang sama di tanggal yang sama
            $table->unique(['date', 'room_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_overrides');
    }
};