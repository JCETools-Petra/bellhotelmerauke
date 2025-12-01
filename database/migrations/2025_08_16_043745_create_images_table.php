<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // Untuk menyimpan path file gambar
            $table->unsignedBigInteger('imageable_id'); // ID dari room atau mice_room
            $table->string('imageable_type'); // Nama model (App\Models\Room atau App\Models\MiceRoom)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};