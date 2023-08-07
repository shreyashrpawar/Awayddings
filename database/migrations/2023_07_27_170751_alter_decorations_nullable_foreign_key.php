<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDecorationsNullableForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('em_decorations', function (Blueprint $table) {
            // Drop existing foreign key constraint (if exists)
            $table->dropForeign(['em_event_id']);
            
            // Modify the foreign key column to be nullable
            $table->unsignedBigInteger('em_event_id')->nullable()->change();
            
            // Add the foreign key constraint again (if needed)
            // $table->foreign('foreign_key_column')->references('id')->on('related_table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_name', function (Blueprint $table) {
            // Revert the nullable foreign key column back to its original state (if necessary)
            // $table->unsignedBigInteger('foreign_key_column')->change();
            
            // Add back the foreign key constraint (if needed)
            // $table->foreign('foreign_key_column')->references('id')->on('related_table')->onDelete('cascade');
        });
    }
}
