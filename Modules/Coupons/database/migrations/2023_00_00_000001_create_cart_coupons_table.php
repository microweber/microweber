<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('cart_coupons')) {
            Schema::create('cart_coupons', function (Blueprint $table) {
                $table->increments('id');
                $table->string('coupon_name')->nullable();
                $table->string('coupon_code')->nullable();;
                $table->string('discount_type')->nullable();;
                $table->integer('discount_value')->nullable();;
                $table->integer('total_amount')->nullable();;
                $table->integer('uses_per_coupon')->nullable();;
                $table->integer('uses_per_customer')->nullable();;
                $table->integer('is_active')->nullable();;
                $table->timestamps();
            });
        }

    }

};
