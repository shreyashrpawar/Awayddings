<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmPrebookingAddsonArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_prebooking_addson_artist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('em_prebooking_summaries_id');
            $table->unsignedBigInteger('em_addson_artist_id')->nullable();
            $table->unsignedBigInteger('em_addson_artist_person_id')->nullable();
            $table->double('addson_artist_amount');
            $table->double('total_amount');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('em_prebooking_summaries_id')->references('id')->on('em_prebooking_summaries');
            $table->foreign('em_addson_artist_id')->references('id')->on('em_artists');
            $table->foreign('em_addson_artist_person_id')->references('id')->on('em_artist_persons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('em_prebooking_addson_artist');
    }
}
