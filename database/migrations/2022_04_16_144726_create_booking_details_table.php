<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_summaries_id');
            $table->date('date');
            $table->unsignedBigInteger('hotel_chargable_type_id');
            $table->double('rate');
            $table->integer('qty');
            $table->double('threshold');
            $table->foreign('booking_summaries_id')->references('id')->on('booking_summaries');
            $table->foreign('hotel_chargable_type_id')->references('id')->on('hotel_chargable_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_details');
    }
}
