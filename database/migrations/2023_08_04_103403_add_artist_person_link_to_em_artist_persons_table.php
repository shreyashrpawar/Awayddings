<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArtistPersonLinkToEmArtistPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('em_artist_persons', function (Blueprint $table) {
            $table->string('artist_person_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('em_artist_persons', function (Blueprint $table) {
            $table->dropColumn('artist_person_link');
        });
    }
}
