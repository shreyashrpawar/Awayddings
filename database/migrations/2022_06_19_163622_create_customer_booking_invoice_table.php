<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBookingInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_booking_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_summary_id');
            $table->string('invoice_url');
            $table->foreign('booking_summary_id')->references('id')->on('booking_summaries');
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
        Schema::dropIfExists('customer_booking_invoice');
    }
}
