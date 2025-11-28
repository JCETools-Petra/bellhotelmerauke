<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mice_inquiries', function (Blueprint $table) {
            // Ubah mice_room_id jadi nullable karena kita jual Paket (MiceKit), bukan Room
            $table->unsignedBigInteger('mice_room_id')->nullable()->change();
            
            // Tambahkan kolom baru
            $table->foreignId('affiliate_id')->nullable()->after('id'); // Link ke Affiliate
            $table->foreignId('mice_kit_id')->nullable()->after('mice_room_id'); // Link ke Paket MICE
            $table->date('event_date')->nullable()->after('event_type'); // Tanggal Event
            $table->integer('pax')->nullable()->after('event_date'); // Jumlah Orang
            $table->decimal('total_price', 15, 2)->nullable()->after('pax'); // Harga Deal
        });
    }

    public function down()
    {
        Schema::table('mice_inquiries', function (Blueprint $table) {
            $table->dropColumn(['affiliate_id', 'mice_kit_id', 'event_date', 'pax', 'total_price']);
            // Kembalikan mice_room_id jadi not null (hati-hati jika data sudah ada yang null)
            // $table->unsignedBigInteger('mice_room_id')->nullable(false)->change(); 
        });
    }
};
