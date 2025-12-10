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
        Schema::table('price_overrides', function (Blueprint $table) {
            $table->enum('source', ['manual', 'hoteliermarket', 'api'])->default('manual')->after('price');
            $table->timestamp('api_synced_at')->nullable()->after('source');
            $table->string('external_reference_id')->nullable()->after('api_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_overrides', function (Blueprint $table) {
            $table->dropColumn(['source', 'api_synced_at', 'external_reference_id']);
        });
    }
};
