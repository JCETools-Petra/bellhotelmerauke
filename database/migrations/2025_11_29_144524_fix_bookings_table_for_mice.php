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
        Schema::table('bookings', function (Blueprint $table) {
            // 1. Tambahkan kolom 'note' jika belum ada
            if (!Schema::hasColumn('bookings', 'note')) {
                $table->text('note')->nullable()->after('pax');
            }

            // 2. Ubah 'room_id' agar Boleh Kosong (Nullable)
            // Ini PENTING karena Booking MICE tidak butuh room_id
            $table->unsignedBigInteger('room_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Kembalikan ke kondisi semula (hati-hati, ini bisa error jika ada data null)
            $table->dropColumn('note');
            $table->unsignedBigInteger('room_id')->nullable(false)->change();
        });
    }
};