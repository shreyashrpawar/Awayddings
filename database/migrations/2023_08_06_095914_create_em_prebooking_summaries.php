<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmPrebookingSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_prebooking_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->double('total_amount');
            $table->double('budget');
            $table->string('user_remarks')->nullable();
            $table->string('admin_remarks')->nullable();
            $table->integer('status');
            $table->string('bride_name')->nullable()->default(null);
            $table->string('groom_name')->nullable()->default(null);
            $table->unsignedBigInteger('pre_booking_summary_status_id');
            $table->integer('pax');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pre_booking_summary_status_id')->references('id')->on('pre_booking_summary_statuses');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('em_prebooking_summaries');
    }
}
