<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Shop;

use DB;
use MicroweberPackages\Utils\Http\Http;

/**
 * Shop module api.
 *
 * @since             Version 0.1
 */
// ------------------------------------------------------------------------

class ShopManager
{
    public $app;
    public $tables = array();
    public $table_prefix = false;
    public $no_cache = false;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = app();
        }

        $this->set_table_names();
    }

    public function set_table_names($tables = false)
    {
        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['cart'])) {
            $tables['cart'] = 'cart';
        }
        if (!isset($tables['cart_orders'])) {
            $tables['cart_orders'] = 'cart_orders';
        }

        if (!isset($tables['cart_shipping'])) {
            $tables['cart_shipping'] = 'cart_shipping';
        }

        $this->tables = $tables;
    }

    /**
     * Add item to cart - uses CartManager's update_cart function
     */
    public function add_to_cart($data)
    {
        if (!isset($data['product_id']) || !isset($data['qty'])) {
            return array('error' => 'Invalid data');
        }

        $product = get_content_by_id($data['product_id']);
        if (!$product) {
            return array('error' => 'Product not found');
        }

        return $this->app->cart_manager->update_cart([
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
            'rel_id' => $data['product_id'],
            'qty' => $data['qty']
        ]);
    }

    /**
     * Sum cart items amount or quantity - uses CartManager's sum function
     */
    public function sum($return_amount = true)
    {
        return $this->app->cart_manager->sum($return_amount);
    }
    public function cart_sum($return_amount = true)
    {
        return $this->sum($return_amount);
    }

    public function checkout($data)
    {
        return $this->app->checkout_manager->checkout($data);
    }

    public function place_order($place_order)
    {
        return $this->app->order_manager->place_order($place_order);
    }

    public function get_order_by_id($id = false)
    {
        return $this->app->order_manager->get_by_id($id);
    }

    public function empty_cart()
    {
        return $this->app->cart_manager->empty_cart();
    }

    public function get_cart($params = false)
    {
        return $this->app->cart_manager->get($params);
    }

    public function remove_cart_item($data)
    {
        return $this->app->cart_manager->remove_item($data);
    }

    public function update_cart_item_qty($data)
    {
        return $this->app->cart_manager->update_item_qty($data);
    }

    public function update_cart($data)
    {
        return $this->app->cart_manager->update_cart($data);
    }

    public function payment_options($option_key = false)
    {
        return $this->app->checkout_manager->payment_options($option_key);
    }

    public function cart_total()
    {
        return $this->app->cart_manager->total();
    }

    public function update_quantities($order_id = false)
    {
        return $this->app->order_manager->update_quantities($order_id);
    }

    public function order_items($order_id = false)
    {
        return $this->app->order_manager->get_items($order_id);
    }

    public function get_orders($params = false)
    {
        return $this->app->order_manager->get($params);
    }

    public function get_product_prices($product_id = false, $return_full_custom_fields_array = false)
    {
        if (!$product_id) {
            $product_id = product_id();
        }

        $prices = app()->content_repository->getCustomFieldsByType($product_id,'price');

        if ($return_full_custom_fields_array) {
            return $prices;
        }

        if ($prices) {
            $return = array();
            foreach ($prices as $price_data) {
                $i = 0;
                if (isset($price_data['name']) and isset($price_data['value'])) {
                    $i++;
                    $name = $price_data['name'];
                    if(isset($return[$name])){
                        $name = $name . ' ' . $i;
                    }
                    $return[$name] = $price_data['value'];
                }
            }
            return $return;
        }
    }

    public function get_product_price($content_id = false)
    {
        if (!$content_id) {
            $content_id = CONTENT_ID;
        }
        $prices = $this->get_product_prices($content_id);
        if ($prices and is_array($prices) and !empty($prices)) {
            $vals2 = array_values($prices);
            $val1 = reset($vals2);
            return $val1;
        }
        return false;
    }

    public function get_default_currency()
    {
        $curr = $this->app->option_manager->get('currency', 'payments');
        if (!$curr) {
            $curr = 'USD';
        }
        return $curr;
    }

    public function currency_format($amount, $currency = false)
    {
        if(is_array($amount)){
            return;
        }

        if (!$currency) {
            $currency = $this->get_default_currency();
        }

        $need_float = false;
        if (str_contains($amount, '.')) {
            $need_float = true;
        }
        if (str_contains($amount, ',')) {
            $need_float = true;
        }

        if ($need_float) {
            $amount = floatval($amount);
        }
        $sym = $this->currency_symbol($currency);

        if ($sym == '') {
            $sym = $currency;
        }

        $decimals = $need_float ? 2 : 0;

        if (!is_numeric($amount)) {
            return $amount;
        }

        switch ($currency) {
            case 'EUR':
                $formatted = number_format($amount, $decimals, ',', ' ');
                break;
            case 'GBP':
            case 'BGN':
            case 'RUB':
                $formatted = number_format($amount, $decimals, '.', ' ');
                break;
            case 'BRL':
                $formatted = number_format($amount, $decimals, ',', '.');
                break;
            default:
                $formatted = number_format($amount, $decimals, '.', ',');
        }

        $cur_pos = $this->app->option_manager->get('currency_symbol_position', 'payments');

        switch ($cur_pos) {
            case 'before':
                return $sym . ' ' . $formatted;
            case 'after':
                return $formatted . ' ' . $sym;
            default:
                return $sym . ' ' . $formatted;
        }
    }

    public function currency_symbol($curr = false, $key = 3)
    {
        if ($curr == false) {
            $curr = $this->app->option_manager->get('currency', 'payments');
        }

        $all_cur = $this->currency_get();
        if (is_array($all_cur)) {
            foreach ($all_cur as $value) {
                if (in_array($curr, $value)) {
                    if ($key == false) {
                        return $value;
                    }
                    return $value[$key];
                }
            }
        }

        return $curr;
    }

    public function currency_get()
    {
        static $currencies_list = false;

        if ($currencies_list) {
            return $currencies_list;
        }

        $cur_file = dirname(MW_PATH) . DS . 'Utils' . DS . 'ThirdPartyLibs' . DS . 'currencies.csv';
        if (is_file($cur_file)) {
            if (($handle = fopen($cur_file, 'r')) !== false) {
                $res = array();
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $res[] = $data;
                }
                fclose($handle);
                $currencies_list = $res;
                return $res;
            }
        }
    }

    public function checkout_url()
    {
        $template_dir = $this->app->template_manager->dir();
        $file = $template_dir . 'checkout.php';
        if (is_file($file)) {
            $default_url = $this->app->url_manager->site('checkout');
        } else {
            $default_url = route('checkout.contact_information');
            $checkout_url = $this->app->option_manager->get('checkout_url', 'shop');
            if ($checkout_url != false and trim($checkout_url) != '') {
                $default_url = $checkout_url;
            }
        }

        $checkout_url_sess = $this->app->user_manager->session_get('checkout_url');

        if ($checkout_url_sess == false) {
            return $default_url;
        }
        return $this->app->url_manager->site($checkout_url_sess);
    }

    public function redirect_to_checkout()
    {
        $url = $this->checkout_url();
        return app()->url_manager->redirect($url);
    }
}
