<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('status');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_holder')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            //
        });
    }
};
