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
        Schema::create('property_images', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('image');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('property_images');
    }
};
