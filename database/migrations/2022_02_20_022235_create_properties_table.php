<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias_name')->nullable();
            $table->string('featured_image')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->text('description')->nullable();
            $table->text('gmap_embedded_code')->nullable();
//            $table->double('default_db_ocp_rate')->comment('Double occupancy Rate');
//            $table->double('default_triple_ocp_rate')->comment('Triple occupancy Rate');
//            $table->integer('default_db_ocp_room_count')->comment('Double occupancy Room Count');
//            $table->integer('default_triple_ocp_room_count')->comment('Triple occupancy Room Count');
            $table->boolean('status')->default(1)->comment('0 is active 1 is active');

            $table->timestamps();
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
