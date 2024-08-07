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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_payment_summaries_id');
            $table->integer('installment_no');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id');
            $table->string('payment_status');
            $table->json('meta');
            $table->string('providerReferenceId')->nullable();
            $table->string('merchantOrderId')->nullable();
            $table->string('checksum')->nullable();
            $table->timestamps();
            $table->foreign('booking_payment_summaries_id')->references('id')->on('booking_payment_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
