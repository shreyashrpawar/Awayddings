<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceAndDescriptionToLightandsoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lightandsounds', function (Blueprint $table) {
            $table->double('price')->nullable()->after('id');
            $table->text('description')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lightandsounds', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('description');
        });
    }
}
