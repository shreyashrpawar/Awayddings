<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePreBookingSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_booking_summaries', function (Blueprint $table) {
            $table->string('bride_name')->nullable()->default(null);
            $table->string('groom_name')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_booking_summaries', function (Blueprint $table) {
            $table->dropColumn('bride_name');
            $table->dropColumn('groom_name');
        });
    }
}
