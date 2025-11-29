<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Terhubung ke tabel users
            $table->string('referral_code')->unique(); // Kode unik mereka, misal: 'JOKO123'
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Persentase komisi, misal: 10.00%
            $table->string('status')->default('pending'); // pending, active, inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};