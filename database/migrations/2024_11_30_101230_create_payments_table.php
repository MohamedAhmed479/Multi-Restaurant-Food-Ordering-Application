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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Order reference

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade'); // Restaurant reference

            $table->float('amount_paid', 8, 2); // The actual amount paid for the restaurant's part
            $table->string('payment_method'); // Payment method (stripe, COD)
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable(); // Transaction ID for electronic payments (Stripe)
            $table->string('order_number')->nullable();
            $table->string('currency')->nullable();

            $table->string('status')->default('pending');
            $table->timestamps(); // Created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
