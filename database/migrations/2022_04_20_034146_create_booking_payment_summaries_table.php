<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPaymentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_payment_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_summaries_id');
            $table->integer('installment_no');
            $table->double('discount')->nullable(0);
            $table->double('amount');
            $table->double('paid');
            $table->double('due')->default(0);
            $table->integer('status')->default(1)->comment('0 is inactive and 1 is active');
            $table->foreign('booking_summaries_id')->references('id')->on('booking_summaries');
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
        Schema::dropIfExists('booking_payment_summaries');
    }
}
