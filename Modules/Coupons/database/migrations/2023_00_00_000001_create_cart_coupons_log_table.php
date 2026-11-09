<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        if (!Schema::hasTable('cart_coupon_logs')) {
            Schema::create('cart_coupon_logs', function (Blueprint $table) {

                $table->increments('id');
                $table->integer('coupon_id')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_id')->nullable();
                $table->string('customer_ip')->nullable();

                $table->string('coupon_code')->nullable();
                $table->string('discount_type')->nullable();
                $table->decimal('discount_value', 10, 2)->nullable()->default(0);
                $table->decimal('cart_total', 10, 2)->nullable()->default(0);
                $table->decimal('discount_amount', 10, 2)->nullable()->default(0);
                $table->integer('uses_count')->nullable();
                $table->dateTime('use_date')->nullable();
                $table->timestamps();


            });
        } else {
            // The table may already exist as a MINIMAL version created by the
            // microweber-update "old version 2.13" migration (coupon_id,
            // customer_*, coupon_code only). When that ran first, the create
            // above was skipped and the columns the Coupons module actually uses
            // were never added — e.g. "table cart_coupon_logs has no column named
            // discount_type" on a fresh install. Add any missing columns.
            Schema::table('cart_coupon_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('cart_coupon_logs', 'discount_type')) {
                    $table->string('discount_type')->nullable();
                }
                if (!Schema::hasColumn('cart_coupon_logs', 'discount_value')) {
                    $table->decimal('discount_value', 10, 2)->nullable()->default(0);
                }
                if (!Schema::hasColumn('cart_coupon_logs', 'cart_total')) {
                    $table->decimal('cart_total', 10, 2)->nullable()->default(0);
                }
                if (!Schema::hasColumn('cart_coupon_logs', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->nullable()->default(0);
                }
                if (!Schema::hasColumn('cart_coupon_logs', 'uses_count')) {
                    $table->integer('uses_count')->nullable();
                }
                if (!Schema::hasColumn('cart_coupon_logs', 'use_date')) {
                    $table->dateTime('use_date')->nullable();
                }
                if (!Schema::hasColumn('cart_coupon_logs', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_coupon_logs');
    }

};
