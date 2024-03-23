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
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->longText('description');
            $table->string('loc_city');
            $table->string('loc_latitude');
            $table->string('loc_longitude');
            $table->string('loc_address');
            $table->string('loc_state');
            $table->string('loc_zip');
            $table->string('loc_country');
            $table->string('price');
            $table->uuid('agent_id');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_sold')->default(false);
            $table->boolean('is_rented')->default(false);
            $table->enum('offer_type', ['sale', 'rent']);
            $table->string('slug')->unique();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
