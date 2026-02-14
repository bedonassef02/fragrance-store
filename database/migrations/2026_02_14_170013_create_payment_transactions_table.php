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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->index(); // Gateway Transaction ID
            $table->string('gateway'); // paymob, fawry
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('EGP');
            $table->string('status')->index(); // pending, paid, failed, voided, refunded
            $table->json('response_data')->nullable(); // Full gateway response for debugging
            $table->timestamps();

            // Compound index for quick lookup of an order's transactions
            $table->index(['order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
