<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmAddonFacilityDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_addon_facility_details', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('em_addon_facility_id'); // Add the event_id column
            $table->index('em_addon_facility_id'); // Add the index on event_id column
            $table->boolean('status')->default(1)->comment('0 is active 1 is active');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('em_addon_facility_id')->references('id')->on('em_addon_facility')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('em_addon_facility_details');
    }
}
