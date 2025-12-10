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
            $table->foreignId('mice_kit_id')->nullable()->constrained('mice_kits')->onDelete('cascade');
            $table->string('booking_code')->nullable();
            $table->string('event_name')->nullable(); // Nama event untuk MICE booking
            $table->integer('pax')->nullable(); // Jumlah peserta untuk MICE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['mice_kit_id']);
            $table->dropColumn(['mice_kit_id', 'booking_code', 'event_name', 'pax']);
        });
    }
};
