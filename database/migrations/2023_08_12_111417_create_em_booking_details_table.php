<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('em_booking_summaries_id');
            $table->unsignedBigInteger('em_event_id')->nullable();
            $table->date('date');
            $table->time("start_time");
            $table->time("end_time");
            $table->unsignedBigInteger('em_artist_person_id')->nullable();
            $table->unsignedBigInteger('em_decor_id')->nullable();
            $table->double('artist_amount');
            $table->double('decor_amount');
            $table->double('total_amount');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('em_booking_summaries_id')->references('id')->on('em_booking_summaries');
            $table->foreign('em_artist_person_id')->references('id')->on('em_artist_persons');
            $table->foreign('em_decor_id')->references('id')->on('em_decorations');
            $table->foreign('em_event_id')->references('id')->on('em_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('em_booking_details');
    }
}
