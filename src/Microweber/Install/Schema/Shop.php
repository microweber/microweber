<?php namespace Microweber\Install\Schema;

class Shop 
{

    public function get()
    {
        return [
        	'cart' => [
				'title' => 'longText',
				'is_active' => "string",
				'rel_id' => 'integer',
				'rel' => 'string',
				'updated_on' => 'dateTime',
				'created_on' => 'dateTime',
				'price' => 'float',
				'currency' => 'string',
				'session_id' => 'string',
				'qty' => 'integer',
				'other_info' => 'longText',
				'order_completed' => "string",
				'order_id' => 'string',
				'skip_promo_code' => "string",
				'created_by' => 'integer',
				'custom_fields_data' => 'longText',
				'$index' => ['rel', 'rel_id']
			],

			'cart_orders' => [
		        'updated_on' => 'dateTime',
		        'created_on' => 'dateTime',
		        'country' => 'string',
		        'promo_code' => 'longText',
		        'amount' => 'float',
		        'transaction_id' => 'longText',
		        'shipping_service' => 'longText',
		        'shipping' => 'float',
		        'currency' => 'string',

        		'currency_code' => 'string',

        		'first_name' => 'longText',

        		'last_name' => 'longText',

        		'email' => 'longText',

        		'city' => 'longText',

        		'state' => 'longText',

        		'zip' => 'longText',
        		'address' => 'longText',
        		'address2' => 'longText',
        		'phone' => 'longText',

        		'created_by' => 'integer',
        		'edited_by' => 'integer',
        		'session_id' => 'string',
        		'order_completed' => "string",
        		'is_paid' => "string",
        		'url' => 'longText',
        		'user_ip' => 'string',
        		'items_count' => 'integer',
        		'custom_fields_data' => 'longText',

        		'payment_gw' => 'string',
        		'payment_verify_token' => 'string',
        		'payment_amount' => 'float',
        		'payment_currency' => 'string',

        		'payment_status' => 'string',

        		'payment_email' => 'longText',
        		'payment_receiver_email' => 'longText',

        		'payment_name' => 'longText',

        		'payment_country' => 'longText',

        		'payment_address' => 'longText',

        		'payment_city' => 'longText',
        		'payment_state' => 'longText',
        		'payment_zip' => 'longText',

        		'payer_id' => 'longText',

        		'payer_status' => 'longText',
        		'payment_type' => 'longText',
        		'order_status' => 'string',

        		'payment_shipping' => 'float',

        		'is_active' => "string",
        		'rel_id' => 'integer',
        		'rel' => 'string',
        		'price' => 'float',
        		'other_info' => 'longText',
        		'order_id' => 'string',
        		'skip_promo_code' => "string",

        		'$index' => ['session_id']
        	],

        	'cart_shipping' => [
        		'updated_on' => 'dateTime',
        		'created_on' => 'dateTime',
        		'is_active' => "string",

        		'shipping_cost' => 'float',
        		'shipping_cost_max' => 'float',
        		'shipping_cost_above' => 'float',

        		'shipping_country' => 'longText',
        		'position' => 'integer',
        		'shipping_type' => 'longText',


        		'shipping_price_per_size' => 'float',
        		'shipping_price_per_weight' => 'float',
        		'shipping_price_per_item' => 'float',
        		'shipping_price_custom' => 'float'
        	]
        ];
    }

}