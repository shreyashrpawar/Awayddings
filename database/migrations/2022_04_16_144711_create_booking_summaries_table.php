<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pre_booking_summary_id');
            $table->unsignedBigInteger('property_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->double('total_amount');
            $table->double('amount');
            $table->double('discount')->default(0);
            $table->integer('pax');
            $table->string('user_remarks')->nullable();
            $table->string('admin_remarks')->nullable();
            $table->integer('status');

            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('property_id')->references('id')->on('properties');
            $table->foreign('pre_booking_summary_id')->references('id')->on('pre_booking_summaries');
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
        Schema::dropIfExists('booking_summaries');
    }
}
