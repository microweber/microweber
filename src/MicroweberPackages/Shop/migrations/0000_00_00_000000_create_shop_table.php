<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()->database_manager->build_tables($this->getSchema());
    }

    public function getSchema()
    {
        return [
            'cart' => [
                'title' => 'longText',
                'is_active' => 'string',
                'rel_id' => 'integer',
                'rel_type' => 'string',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'price' => 'float',
                'currency' => 'string',
                'session_id' => 'string',
                'qty' => 'integer',
                'other_info' => 'longText',
                'order_completed' => 'integer',
                'order_id' => 'string',
                'skip_promo_code' => 'string',
                'created_by' => 'integer',
                'custom_fields_data' => 'longText',
                'custom_fields_json' => 'longText',
                'item_image' => 'string',
                'link' => 'string',
                'description' => 'longText',
                '$index' => ['rel_type', 'rel_id'],
            ],

            'cart_orders' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'order_id' => 'string',
                'amount' => 'float',
                'transaction_id' => 'longText',
                'shipping_service' => 'longText',
                'shipping' => 'float',
                'currency' => 'string',
                'currency_code' => 'string',
                'first_name' => 'longText',
                'last_name' => 'longText',
                'email' => 'longText',
                'country' => 'string',
                'city' => 'text',
                'state' => 'string',
                'zip' => 'string',
                'address' => 'longText',
                'address2' => 'longText',
                'phone' => 'text',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',
                'order_completed' => 'integer',
                'is_paid' => 'integer',
                'url' => 'text',
                'user_ip' => 'string',
                'items_count' => 'integer',
                'custom_fields_data' => 'longText',
                'payment_gw' => 'string',
                'payment_verify_token' => 'string',
                'payment_amount' => 'float',
                'payment_currency' => 'string',
                'payment_status' => 'string',
                'payment_email' => 'text',
                'payment_receiver_email' => 'text',
                'payment_name' => 'text',
                'payment_country' => 'text',
                'payment_address' => 'text',
                'payment_city' => 'text',
                'payment_state' => 'string',
                'payment_zip' => 'string',
                'payment_phone' => 'string',
                'payer_id' => 'text',
                'payer_status' => 'text',
                'payment_type' => 'text',
                'order_status' => 'string',
                'payment_shipping' => 'float',
                'is_active' => 'integer',
                'rel_id' => 'integer',
                'rel_type' => 'string',
                'price' => 'float',
                'other_info' => 'longText',
                'promo_code' => 'longText',
                'skip_promo_code' => 'integer',
                'coupon_id' => 'integer',
                'discount_type' => 'string',
                'discount_value' => 'float',
                'taxes_amount' => 'float',
                '$index' => ['session_id'],
            ],

            'cart_taxes' => [
                'tax_name' => 'text',
                'amount' => 'float',
                'tax_modifier' => 'string',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'tax_rules_json' => 'longText',
                'position' => 'integer',
            ],

            'cart_shipping' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'is_active' => 'string',


                'shipping_cost' => 'float',
                'shipping_cost_max' => 'float',
                'shipping_cost_above' => 'float',

                'shipping_country' => 'longText',
                'position' => 'integer',
                'shipping_type' => 'longText',

                'shipping_price_per_size' => 'float',
                'shipping_price_per_weight' => 'float',
                'shipping_price_per_item' => 'float',
                'shipping_price_custom' => 'float',
            ],


            'cart_clients' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',

                'first_name' => 'longText',
                'last_name' => 'longText',
                'email' => 'longText',
                'user_id' => 'integer',


            ],

            'cart_clients_address' => [
                'address_title' => 'string',

                'is_default' => 'string',
                'address_type' => 'string',

                'client_id' => 'integer',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'country' => 'text',
                'city' => 'text',
                'state' => 'string',
                'zip' => 'string',
                'address' => 'longText',
                'address2' => 'longText',
                'phone' => 'text',
                'address_notes' => 'longText',
            ],


            'cart_invoices' => [
                'total' => 'float',
                'amount' => 'float',
                'tax' => 'float',
                'discount' => 'float',
                'currency' => 'string',
                'status' => 'string',
                'sender_info' => 'longText',
                'receiver_info' => 'longText',
                'payment_info' => 'longText',
                'notes' => 'longText',
                'order_id' => 'integer',
                'client_id' => 'integer',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
            ],

            'cart_invoices_lines' => [
                'description' => 'longText',
                'qty' => 'integer',
                'amount' => 'float',
                'tax' => 'float',
                'invoice_id' => 'integer',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
            ]
        ];
    }
}