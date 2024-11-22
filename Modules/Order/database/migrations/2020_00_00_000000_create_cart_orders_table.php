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
            $table->timestamps();
            $table->string('order_id');
            $table->float('amount');
            $table->longText('transaction_id');
            $table->longText('shipping_service');
            $table->float('shipping');
            $table->string('currency');
            $table->string('currency_code');
            $table->longText('first_name');
            $table->longText('last_name');
            $table->longText('email');
            $table->string('country');
            $table->text('city');
            $table->string('state');
            $table->string('zip');
            $table->longText('address');
            $table->longText('address2');
            $table->text('phone');
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('session_id');
            $table->integer('customer_id');
            $table->integer('order_completed');
            $table->integer('is_paid');
            $table->text('url')->nullable();
            $table->string('user_ip');
            $table->integer('items_count');
            $table->longText('custom_fields_data')->nullable();
            $table->string('payment_gw');
            $table->string('payment_verify_token')->nullable();
            $table->float('payment_amount');
            $table->string('payment_currency');
            $table->string('payment_status');
            $table->text('payment_email')->nullable();
            $table->text('payment_receiver_email')->nullable();
            $table->text('payment_name')->nullable();
            $table->text('payment_country')->nullable();
            $table->text('payment_address')->nullable();
            $table->text('payment_city')->nullable();
            $table->string('payment_state')->nullable();
            $table->string('payment_zip')->nullable();
            $table->string('payment_phone')->nullable();
            $table->text('payer_id')->nullable();
            $table->text('payer_status')->nullable();
            $table->text('payment_type')->nullable();
            $table->longText('payment_data')->nullable();
            $table->string('order_status');
            $table->float('payment_shipping');
            $table->integer('is_active');
            $table->integer('rel_id')->nullable();
            $table->string('rel_type')->nullable();
            $table->float('price');
            $table->longText('other_info')->nullable();
            $table->longText('promo_code')->nullable();
            $table->integer('skip_promo_code')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value')->nullable();
            $table->float('taxes_amount')->nullable();
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_orders');
    }
};
