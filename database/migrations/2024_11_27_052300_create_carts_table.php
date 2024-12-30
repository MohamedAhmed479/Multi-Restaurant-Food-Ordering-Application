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
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // مفتاح أساسي

            $table->unsignedBigInteger('user_id')->nullable(); // مفتاح أجنبي للمستخدم (اختياري)
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('coupon_id')->nullable(); // مفتاح أجنبي للمنتج
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');


            $table->string('session_id')->nullable(); // لتخزين معرف الجلسة للزوار
            $table->foreign('session_id')->references('id')->on('sessions');

            $table->decimal('total_price', 10, 2)->default(0); // السعر الإجمالي
            $table->decimal('net_total_price', 10, 2)->default(0); // السعر الإجمالي

            $table->timestamps(); // تاريخ الإنشاء والتحديث

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
