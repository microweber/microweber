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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use MicroweberPackages\Currency\Currency;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Notifications\ProductOutOfStockNotification;
use MicroweberPackages\User\Models\User;
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
        /*
        if (!isset($tables['cart_taxes'])) {
            $tables['cart_taxes'] = 'cart_taxes';
        }*/
        $this->tables = $tables;
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

    /**
     * @param bool $return_amount
     *
     * @return array|false|float|int|mixed
     */
    public function cart_sum($return_amount = true)
    {
        return $this->app->cart_manager->sum($return_amount);
    }

    public function cart_total()
    {
        return $this->app->cart_manager->total();
    }

    /**
     * Remove quantity from product.
     *
     * On completed order this function deducts the product quantities.
     *
     * @param bool|string $order_id
     *                              The id of the order that is completed
     *
     * @return bool
     *              True if quantity is updated
     */
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
            $product_id = CONTENT_ID;
        }

        $for = 'content';

        $for_id = $product_id;

        $cf_params = array();
        $cf_params['rel_type'] = $for;
        $cf_params['rel_id'] = $for_id;
        $cf_params['type'] = 'price';
        $cf_params['return_full'] = true;

        //$prices = $this->app->fields_manager->get($cf_params);

        $prices =    app()->content_repository->getCustomFieldsByType($for_id,'price');

        $custom_field_items = $prices;
        $override = $this->app->event_manager->trigger('mw.shop.get_product_prices', $custom_field_items);
        if (is_array($override)) {

            foreach ($override as $resp) {
                if (is_array($resp) and !empty($resp)) {
                    foreach ($resp as $price_index => $price_item) {
                        $prices[$price_index] = array_merge($prices[$price_index], $price_item);
                    }
                }
            }
        }

        if ($return_full_custom_fields_array) {
            return $prices;
        }

        if ($prices) {
            $return = array();
            foreach ($prices as $price_data) {
                $i = 0;
                if (isset($price_data['name']) and isset($price_data['value'])) {
                    $i++ ;
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
        } else {
            return false;
        }
    }


    public function checkout_confirm_email_test($params)
    {
        if (!isset($params['to'])) {
            $email_from = get_email_from();
            if ($email_from == false) {
                return array('error' => 'You must set up your email');
            }
        } else {
            $email_from = $params['to'];
        }
        $ord_data = $this->get_orders('limit=50');

        if (!$ord_data) {
            return array('error' => 'No orders found.');
        }

        if (is_array($ord_data[0])) {
            shuffle($ord_data);
            $ord_test = $ord_data[0];

            $send = $this->app->checkout_manager->confirm_email_send($ord_test['id'], $to = $email_from, true, true);
            if ($send) {
                return 'Email is send successfully to <b>'.$to.'</b>.';
            }
        }
    }

    public function checkout_ipn($data)
    {
        return $this->app->checkout_manager->checkout_ipn($data);

    }

    private function get_domain_from_str($address)
    {
        $address = gethostbyaddr($address);
        $parsed_url = parse_url($address);
        if (!isset($parsed_url['host'])) {
            if (isset($parsed_url['path'])) {
                $parsed_url['host'] = $parsed_url['path'];
            }
        }
        $check = $this->esip($parsed_url['host']);
        $host = $parsed_url['host'];
        if ($check == false) {
            if ($host != '') {
                $host = $this->domain_name($host);
            } else {
                $host = $this->domain_name($address);
            }
        }

        return $host;
    }

    private function esip($ip_addr)
    {
        if (preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr)) {
            $parts = explode('.', $ip_addr);
            foreach ($parts as $ip_parts) {
                if (intval($ip_parts) > 255 || intval($ip_parts) < 0) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    private function domain_name($domainb)
    {
        $bits = explode('/', $domainb);
        if ($bits[0] == 'http:' || $bits[0] == 'https:') {
            $domainb = $bits[2];
        } else {
            $domainb = $bits[0];
        }
        unset($bits);
        $bits = explode('.', $domainb);
        $idz = count($bits);
        $idz -= 3;
        if (strlen($bits[($idz + 2)]) == 2) {
            $url = $bits[$idz] . '.' . $bits[($idz + 1)] . '.' . $bits[($idz + 2)];
        } elseif (strlen($bits[($idz + 2)]) == 0) {
            $url = $bits[($idz)] . '.' . $bits[($idz + 1)];
        } else {
            $url = $bits[($idz + 1)] . '.' . $bits[($idz + 2)];
        }

        return $url;
    }

    public function update_order($params = false)
    {
        return $this->app->order_manager->save($params);
    }

    public function delete_client($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            $this->app->error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->tables['cart_orders'];
        if (isset($data['email'])) {
            $c_id = $this->app->database_manager->escape_string($data['email']);
            $res = $this->app->database_manager->delete_by_id($table, $c_id, 'email');
            $this->app->cache_manager->delete('cart_orders');

            return $res;
        }
    }

    public function delete_order($data)
    {
        return $this->app->order_manager->delete_order($data);
    }

    public function currency_get_for_paypal()
    {
        $curencies = array('USD', 'EUR', 'GBP', 'AUD', 'CAD', 'JPY', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'MYR', 'BRL', 'PHP', 'TWD', 'THB', 'TRY');

        return $curencies;
    }

    public function currency_convert_rate($from, $to)
    {
        return;
        $function_cache_id = __FUNCTION__ . md5($from . $to);
        $cache_group = 'shop';
        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
        if (($cache_content) != false) {
            return $cache_content;
        }
        if ($to == '') {
            $to = $from;
        }

        $remote_host = 'http://api.microweber.com';
        $service = '/service/currency/?from=' . $from . '&to=' . $to;
        $remote_host_s = $remote_host . $service;

        $curl = new Http();
        $curl->set_timeout(3);
        $curl->url($remote_host_s);
        $get_remote = $curl->get();

        if ($get_remote != false) {
            $this->app->cache_manager->save($get_remote, $function_cache_id, $cache_group);

            return floatval($get_remote);
        }
    }

    public function get_default_currency()
    {

        $curr = $this->app->option_manager->get('currency', 'payments');
        if (!$curr) {
            $curr = 'USD';
        }
        return $curr;

    }
    public function currency_format($amount, $curr = false)
    {
        if(is_array($amount)){
            return;
        }


        $curr = $this->get_default_currency();

        $need_float = true;
        if (strstr($amount, '.')) {
            $need_float = false;
        }
        if ($need_float) {
            $amount = floatval($amount);
        }
        $sym = $this->currency_symbol($curr);

        if ($sym == '') {
            $sym = $curr;
        }
        $currency_symbol_decimal = get_option('currency_symbol_decimal', 'payments');
        $cur_pos = $this->app->option_manager->get('currency_symbol_position', 'payments');

        $use_number_format = false;


        if ($currency_symbol_decimal == 'when_needed') {
            if ($amount) {
                $is_round = is_numeric($amount) && intval($amount) == $amount;
                if ($is_round) {
                    $amount = intval($amount);
                } else {
                    $use_number_format = true;
                }
            }
        } else {
            $use_number_format = true;

        }


        $decimals = 0;
        if($use_number_format){
            $decimals = 2;
        }

        if (!is_numeric($amount)) {
            return $amount;
        }

        switch ($curr) {
            case 'EUR':
                $curNumberFormat = number_format($amount, $decimals, ',', ' ');
                break;

            case 'GBP':
            case 'BGN':
            case 'RUB':
                $curNumberFormat = number_format($amount, $decimals, '.', ' ');
                break;

            case 'BRL':
                $curNumberFormat = number_format($amount, $decimals, ',', '.');
                break;

            default:
                $curNumberFormat = number_format($amount, $decimals, '.', ',');
                break;
        }

        switch ($cur_pos) {
            case 'before':
                $ret = $sym . ' ' . $curNumberFormat;
                break;
            case 'after':
                $ret = $curNumberFormat . ' ' . $sym;
                break;
            case 'default':
            default:
                switch ($curr) {
                    case 'BGN':
                    case 'RUB':
                        $ret = $curNumberFormat . ' ' . $sym;
                        break;
                    default:
                        $ret = $sym . ' ' . $curNumberFormat;
                        break;
                }
                break;
        }

        return $ret;
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
                    } else {
                        $sym = $value[$key];
                        return $sym;
                    }
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

        $row = 1;
        $cur_file = dirname(MW_PATH) . DS . 'Utils' . DS . 'ThirdPartyLibs' . DS . 'currencies.csv';
        if (is_file($cur_file)) {
            if (($handle = fopen($cur_file, 'r')) !== false) {
                $res = array();
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $itm = array();
                    $num = count($data);
                    ++$row;
                    for ($c = 0; $c < $num; ++$c) {
                        $itm[] = $data[$c];
                    }
                    $res[] = $itm;
                }
                fclose($handle);
                $currencies_list = $res;

                return $res;
            }
        }
    }


    public function redirect_to_checkout()
    {
        $url = $this->checkout_url();
        return app()->url_manager->redirect($url);
    }

    public function checkout_url()
    {
        $template_dir = $this->app->template->dir();
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
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }
    }

    /*public function create_mw_shop_default_options() {

        $function_cache_id = __FUNCTION__;

        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group = 'db');
        if (($cache_content)=='--true--'){
            return true;
        }

        $datas = array();

        $data = array();

        $data['name'] = 'Currency';
        $data['help'] = 'The website currency';
        $data['option_group'] = 'payments';
        $data['option_key'] = 'currency';
        $data['option_value'] = 'USD';
        $data['field_type'] = 'currency';

        $data['position'] = '1';
        $datas[] = $data;

        $data = array();

        $data['name'] = 'Payment currency';
        $data['help'] = 'Payment process in currency';
        $data['option_group'] = 'payments';
        $data['option_key'] = 'payment_currency';
        $data['option_value'] = 'USD';
        $data['field_type'] = 'currency';

        $data['position'] = '2';
        $datas[] = $data;

        $data['name'] = 'Payment currency rate';
        $data['help'] = 'Payment currency convert rate to site currency';
        $data['option_group'] = 'payments';
        $data['option_key'] = 'payment_currency_rate';
        $data['option_value'] = '1.2';
        $data['field_type'] = 'currency';

        $data['position'] = '3';
        $datas[] = $data;

        $changes = false;
        foreach ($datas as $val) {
            $ch = $this->app->option_manager->set_default($val);
            if ($ch==true){
                $changes = true;
            }
        }
        if ($changes==true){
            $this->app->cache_manager->delete('options');
        }
        $this->app->cache_manager->save('--true--', $function_cache_id, $cache_group = 'db');

        return true;
    }*/
}
