<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('cart_orders')) {
            return;
        }

        Schema::create('cart_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_reference_id')->nullable();
            $table->float('amount')->nullable();
            $table->string('order_status')->nullable()->default('new');

            $table->string('currency')->nullable();
            $table->string('currency_code')->nullable();
            $table->longText('first_name')->nullable();
            $table->longText('last_name')->nullable();
            $table->longText('email')->nullable();
            $table->string('country')->nullable();
            $table->text('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->longText('address')->nullable();
            $table->longText('address2')->nullable();
            $table->text('phone')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('session_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('order_completed')->nullable();
            $table->integer('is_paid')->nullable();

            $table->text('url')->nullable();
            $table->string('user_ip')->nullable();
            $table->integer('items_count')->nullable();
            $table->longText('custom_fields_data')->nullable();


            $table->string('rel_id')->nullable();
            $table->string('rel_type')->nullable();
            $table->float('price')->nullable();
            $table->longText('other_info')->nullable();
            $table->longText('promo_code')->nullable();
            $table->integer('skip_promo_code')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value')->nullable();
            $table->float('taxes_amount')->nullable();

            // Payment-related columns
            $table->longText('transaction_id')->nullable();
            $table->string('payment_provider')->nullable();
            $table->integer('payment_provider_id')->nullable();
            $table->string('payment_verify_token')->nullable();
            $table->float('payment_amount')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('payment_status')->nullable();
        //    $table->float('payment_shipping')->nullable();

            $table->longText('payment_data')->nullable();


            //shipping

            $table->string('shipping_provider')->nullable();
            $table->integer('shipping_provider_id')->nullable();
            $table->float('shipping_amount')->nullable();


            // Timestamps
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();




        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_orders');
    }
};
