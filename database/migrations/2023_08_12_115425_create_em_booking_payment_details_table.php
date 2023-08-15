<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmBookingPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_booking_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('em_booking_payment_summaries_id');
            $table->date('date');
            $table->integer('installment_no');
            $table->double('amount');
            $table->string('payment_mode')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status')->default(0)->comment('0 is not paid and 1 is paid');
            $table->foreign('em_booking_payment_summaries_id', 'fk_payment_summaries_id')->references('id')->on('em_booking_payment_summaries');
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
        Schema::dropIfExists('em_booking_payment_details');
    }
}
