<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsStarterFieldsToHotelChargableTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_chargable_types', function (Blueprint $table) {
            $table->integer('is_single_qty')->default(0)->comment('0 is not is_single_qty and 1 is is_single_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_chargable_types', function (Blueprint $table) {
            $table->integer('is_single_qty');
        });
    }
}
