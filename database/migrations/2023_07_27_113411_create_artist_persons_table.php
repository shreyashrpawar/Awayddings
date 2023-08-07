<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_artist_persons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price');
            $table->boolean('status')->default(1)->comment('0 is active 1 is active');
            $table->unsignedBigInteger('artist_id'); // Add the event_id column
            $table->index('artist_id'); // Add the index on event_id column
            $table->foreign('artist_id')->references('id')->on('em_artists')->onDelete('cascade');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artist_persons');
    }
}
