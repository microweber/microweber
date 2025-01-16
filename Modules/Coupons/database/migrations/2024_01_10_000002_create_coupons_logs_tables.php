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
                $table->integer('coupon_id')->nullable();;
                $table->string('customer_email')->nullable();;
                $table->string('customer_id')->nullable();;

                $table->string('coupon_code')->nullable();;
                $table->string('customer_ip')->nullable();;
                $table->integer('uses_count')->nullable();;
                $table->dateTime('use_date')->nullable();;
                $table->timestamps();

            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_coupon_logs');


    }
};
