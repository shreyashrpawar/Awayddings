<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_payment_details', function (Blueprint $table) {
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_payment_details', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
            $table->dropColumn('transaction_status');
        });
    }
};
