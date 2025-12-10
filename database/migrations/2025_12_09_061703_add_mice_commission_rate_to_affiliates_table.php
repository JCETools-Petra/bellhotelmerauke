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
            // Menambahkan kolom mice_commission_rate setelah commission_rate default
            $table->decimal('mice_commission_rate', 5, 2)->default(0)->after('commission_rate');
        });
    }

    public function down()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('mice_commission_rate');
        });
    }
};
