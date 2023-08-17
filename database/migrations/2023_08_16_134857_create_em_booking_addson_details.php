<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmBookingAddsonDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_booking_addson_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('em_booking_summaries_id');
            $table->unsignedBigInteger('em_addon_facility_id')->nullable();
            $table->unsignedBigInteger('facility_details_id')->nullable();
            $table->double('total_amount');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('em_booking_summaries_id')->references('id')->on('em_booking_summaries');
            $table->foreign('em_addon_facility_id')->references('id')->on('em_addon_facility');
            $table->foreign('facility_details_id')->references('id')->on('em_addon_facility_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('em_booking_addson_details');
    }
}
