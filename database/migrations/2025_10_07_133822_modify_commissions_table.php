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
        Schema::table('commissions', function (Blueprint $table) {
            // 1. Ganti nama kolom 'amount' menjadi 'commission_amount'
            $table->renameColumn('amount', 'commission_amount');

            // 2. Tambahkan kolom baru setelah 'commission_amount'
            $table->decimal('rate', 5, 2)->default(0)->after('commission_amount');
            $table->text('notes')->nullable()->after('status');

            // 3. Ubah kolom 'booking_id' agar bisa nullable
            // Penting: pastikan Anda sudah menginstal 'doctrine/dbal'
            // Jika belum, jalankan: composer require doctrine/dbal
            $table->foreignId('booking_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            // Logika untuk mengembalikan perubahan jika migrasi di-rollback
            $table->renameColumn('commission_amount', 'amount');
            $table->dropColumn(['rate', 'notes']);
            $table->foreignId('booking_id')->nullable(false)->change();
        });
    }
};