<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Kolom untuk menyimpan ID affiliate (bisa null jika tamu biasa)
            $table->foreignId('affiliate_id')->nullable()->constrained()->onDelete('set null')->after('id');
            
            // Kolom untuk sumber booking
            $table->string('booking_source')->default('Guest')->after('affiliate_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['affiliate_id']);
            $table->dropColumn(['affiliate_id', 'booking_source']);
        });
    }
};