<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('cart_coupons')) {
            Schema::create('cart_coupons', function (Blueprint $table) {
                $table->id();
                $table->string('coupon_name')->nullable();
                $table->string('coupon_code')->unique();
                $table->string('discount_type')->nullable();
                $table->decimal('discount_value', 10, 2);
                $table->decimal('total_amount', 10, 2)->nullable();
                $table->integer('uses_per_coupon')->nullable();
                $table->integer('uses_per_customer')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cart_coupon_logs')) {
            Schema::create('cart_coupon_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('coupon_id')->nullable();
                $table->string('coupon_code')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_ip')->nullable();
                $table->integer('uses_count')->default(0)->nullable();
                $table->timestamp('use_date')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('cart_orders')) {
            if (!Schema::hasColumn('cart_orders', 'coupon_code')) {
                Schema::table('cart_orders', function (Blueprint $table) {
                    $table->string('coupon_code')->nullable();
                    $table->string('discount_type')->nullable();
                    $table->decimal('discount_value', 10, 2)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_coupon_logs');
        Schema::dropIfExists('cart_coupons');


    }
};
