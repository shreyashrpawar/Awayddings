<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pre_booking_summaries_id');
            $table->date('date');
            $table->unsignedBigInteger('hotel_chargable_type_id');
            $table->double('rate');
            $table->integer('qty');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('pre_booking_summaries_id')->references('id')->on('pre_booking_summaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_booking_details');
    }
}
