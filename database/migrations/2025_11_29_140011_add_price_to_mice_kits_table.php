<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mice_kits', function (Blueprint $table) {
            // Menambahkan kolom harga setelah deskripsi
            $table->decimal('price', 12, 2)->default(0)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('mice_kits', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};