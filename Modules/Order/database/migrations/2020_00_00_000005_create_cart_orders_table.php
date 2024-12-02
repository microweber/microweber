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
            $table->string('order_id')->nullable();
            $table->float('amount')->nullable();
            $table->longText('transaction_id')->nullable();
            $table->longText('shipping_service')->nullable();
            $table->float('shipping')->nullable();
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
            $table->string('payment_gw')->nullable();
            $table->string('payment_verify_token')->nullable();
            $table->float('payment_amount')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('payment_status')->nullable();
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
            $table->string('order_status')->nullable();
            $table->float('payment_shipping')->nullable();;
            $table->integer('is_active')->nullable();
            $table->integer('rel_id')->nullable();
            $table->string('rel_type')->nullable();
            $table->float('price')->nullable();
            $table->longText('other_info')->nullable();
            $table->longText('promo_code')->nullable();
            $table->integer('skip_promo_code')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value')->nullable();
            $table->float('taxes_amount')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

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
