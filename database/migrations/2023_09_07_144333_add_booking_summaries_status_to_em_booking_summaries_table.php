<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingSummariesStatusToEmBookingSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('em_booking_summaries', function (Blueprint $table) {
            $table->string('booking_summaries_status')->after('status');
            $table->string('booking_summaries_status_remarks')->after('booking_summaries_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('em_booking_summaries', function (Blueprint $table) {
            $table->string('booking_summaries_status');
            $table->string('booking_summaries_status_remarks');
        });
    }
}
