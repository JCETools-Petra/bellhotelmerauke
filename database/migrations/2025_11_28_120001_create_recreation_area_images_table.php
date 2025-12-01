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

        Schema::create('recreation_area_images', function (Blueprint $table) {

            $table->id();

            $table->foreignId('recreation_area_id')->constrained()->onDelete('cascade');

            $table->string('path');

            $table->string('caption')->nullable();

            $table->integer('order')->default(0);

            $table->timestamps();

        });

    }

 

    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('recreation_area_images');

    }

};