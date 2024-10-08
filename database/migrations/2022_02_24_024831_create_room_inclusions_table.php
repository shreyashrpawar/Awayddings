<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomInclusionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_inclusions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon_class')->nullable();
            $table->boolean('status')->default(1)->comment('0 is active 1 is active');
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
        Schema::dropIfExists('room_inclusions');
    }
}
