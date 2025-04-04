<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCartCouponLogs extends Migration
{
    public function up()
    {
        Schema::table('cart_coupon_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_coupon_logs', 'discount_type')) {
                $table->string('discount_type')->after('coupon_code');
            }
            if (!Schema::hasColumn('cart_coupon_logs', 'discount_value')) {
                $table->decimal('discount_value', 10, 2)->after('discount_type');
            }
            if (!Schema::hasColumn('cart_coupon_logs', 'cart_total')) {
                $table->decimal('cart_total', 10, 2)->after('customer_ip');
            }
            if (!Schema::hasColumn('cart_coupon_logs', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->after('cart_total');
            }
        });
    }

    public function down()
    {
        Schema::table('cart_coupon_logs', function (Blueprint $table) {
            $table->dropColumn([
                'coupon_code',
                'coupon_id',
                'discount_type',
                'discount_value',
                'customer_email',
                'customer_ip',
                'cart_total',
                'discount_amount'
            ]);
        });
    }
}