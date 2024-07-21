<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmBookingPaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_booking_payment_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('em_booking_summaries_id');
            $table->integer('installment_no');
            $table->double('discount')->nullable(0);
            $table->double('amount');
            $table->double('paid');
            $table->double('due')->default(0);
            $table->integer('status')->default(1)->comment('0 is inactive and 1 is active');
            $table->foreign('em_booking_summaries_id')->references('id')->on('em_booking_summaries');
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
        Schema::dropIfExists('em_booking_payment_summaries');
    }
}
