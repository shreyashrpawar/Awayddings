<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventIdToEmPrebookingSummaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('em_prebooking_summary_details', function (Blueprint $table) {
            $table->unsignedBigInteger('em_event_id')->nullable()->after('em_prebooking_summaries_id');
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
        Schema::table('em_prebooking_summary_details', function (Blueprint $table) {
            $table->dropForeign(['em_event_id']);
            $table->dropColumn('em_event_id');
        });
    }
}
