<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mice_rooms', function (Blueprint $table) {
            $table->string('dimension')->nullable()->after('slug');
            $table->string('size_sqm')->nullable()->after('dimension');
            $table->integer('capacity_classroom')->nullable()->after('rate_details');
            $table->integer('capacity_theatre')->nullable()->after('capacity_classroom');
            $table->integer('capacity_ushape')->nullable()->after('capacity_theatre');
            $table->integer('capacity_round')->nullable()->after('capacity_ushape');
            $table->integer('capacity_board')->nullable()->after('capacity_round');

            // Hapus kolom capacity yang lama karena sudah digantikan
            $table->dropColumn('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('mice_rooms', function (Blueprint $table) {
            $table->dropColumn([
                'dimension',
                'size_sqm',
                'capacity_classroom',
                'capacity_theatre',
                'capacity_ushape',
                'capacity_round',
                'capacity_board',
            ]);

            // Kembalikan kolom capacity jika migrasi di-rollback
            $table->integer('capacity')->after('rate_details');
        });
    }
};