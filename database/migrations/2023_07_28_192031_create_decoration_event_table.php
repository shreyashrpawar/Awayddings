<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecorationEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_decoration_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('decoration_id');
            $table->unsignedBigInteger('event_id');
            // Add any other columns if necessary
            // $table->timestamps();

            // Define foreign keys
            $table->foreign('decoration_id')->references('id')->on('em_decorations')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('em_events')->onDelete('cascade');

            // Add a unique constraint to prevent duplicate entries
            $table->unique(['decoration_id', 'event_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decoration_event');
    }
}
