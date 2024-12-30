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
        Schema::create('restaurant_balances', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->float('total_balance', 8, 2)->default(0); // Total balance for the restaurant
            $table->float('cash_on_delivery_balance', 8, 2)->default(0); // Balance from COD payments
            $table->float('stripe_balance', 8, 2)->default(0); // Balance from Stripe payments

            $table->timestamps(); // Created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_balances');
    }
};
