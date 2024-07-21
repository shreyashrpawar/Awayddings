<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailSentToEmBookingPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('em_booking_payment_details', function (Blueprint $table) {
            $table->integer('email_sent')->default(0)->comment('0 is not sent and 1 is sent')->after('payment_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('em_booking_payment_details', function (Blueprint $table) {
            $table->dropColumn('email_sent');
        });
    }
}
