<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyDefaultRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_default_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('hotel_charagable_type_id');
            $table->date('date');
            $table->double('amount');
            $table->integer('qty')->default(0);
            $table->double('chargable_percentage')->nullable();
            $table->string('argument')->nullable();
            $table->timestamps();
            $table->foreign('property_id')->references('id')->on('properties');
            $table->foreign('hotel_charagable_type_id')->references('id')->on('hotel_chargable_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_default_rates');
    }
}
