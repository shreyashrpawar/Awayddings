<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('hotel_chargable_type_id');
            $table->date('date');
            $table->double('amount');
            $table->integer('available')->default(0)->nullable();
            $table->integer('sold')->default(0)->nullable();
            $table->integer('block')->default(0)->nullable();
            $table->double('occupancy_percentage');
            $table->boolean('status')->default(1)->comment('0 is active 1 is active');
            $table->foreign('hotel_chargable_type_id')->references('id')->on('hotel_chargable_types');
            $table->foreign('property_id')->references('id')->on('properties');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_rates');
    }
}
