<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmCustomerBookingInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_customer_booking_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('em_booking_summary_id');
            $table->string('invoice_url');
            $table->foreign('em_booking_summary_id')->references('id')->on('em_booking_summaries');
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
        Schema::dropIfExists('em_customer_booking_invoices');
    }
}
