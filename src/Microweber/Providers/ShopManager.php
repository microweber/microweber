<?php
namespace Microweber\Providers;

use DB;
use Microweber\Providers\Shop\CartManager;


/**
 *
 * Shop module api
 *
 * @package           modules
 * @subpackage        shop
 * @since             Version 0.1
 */
// ------------------------------------------------------------------------


class ShopManager {
    public $app;
    public $tables = array();
    public $table_prefix = false;
    public $no_cache = false;


    function __construct($app = null) {

        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }

        $this->set_table_names();


    }

    public function set_table_names($tables = false) {

        if (!is_array($tables)){
            $tables = array();
        }
        if (!isset($tables['cart'])){
            $tables['cart'] = 'cart';
        }
        if (!isset($tables['cart_orders'])){
            $tables['cart_orders'] = 'cart_orders';
        }

        if (!isset($tables['cart_shipping'])){
            $tables['cart_shipping'] = 'cart_shipping';
        }
        if (!isset($tables['cart_taxes'])){
            $tables['cart_taxes'] = 'cart_taxes';
        }
        $this->tables = $tables;


    }


    public function checkout($data) {
        return $this->app->checkout_manager->checkout($data);

        $exec_return = false;
        $sid = mw()->user_manager->session_id();
        $sess_order_id = mw()->user_manager->session_get('order_id');
        $cart = array();
        $cart_table = $this->tables['cart'];
        $table_orders = $this->tables['cart_orders'];
        $cart['session_id'] = $sid;
        $cart['order_completed'] = 0;
        $cart['limit'] = 1;
        $mw_process_payment = true;
        $mw_process_payment_success = false;
        $mw_process_payment_failed = false;
        if (isset($_REQUEST['mw_payment_success'])){
            $mw_process_payment = false;
            $mw_process_payment_success = true;
            $exec_return = true;

        } else if (isset($_REQUEST['mw_payment_failure'])){
            $mw_process_payment_failed = true;
            $exec_return = true;
        }


        $cart_table_real = $this->app->database_manager->real_table_name($cart_table);
        $order_table_real = $this->app->database_manager->real_table_name($table_orders);

        if ($exec_return==true){
            if (isset($_REQUEST['return_to'])){
                $return_to = urldecode($_REQUEST['return_to']);
                $append = '?';
                if (strstr($return_to, '?')){
                    $append = '&';
                }
                if ($mw_process_payment_success==true){
                    $return_to = $return_to . $append . 'mw_payment_success=1';
                } elseif ($mw_process_payment_failed==true) {
                    $return_to = $return_to . $append . 'mw_payment_failure=1';
                }

                return $this->app->url_manager->redirect($return_to);
            }
        }

        $additional_fields = false;
        if (isset($data['for']) and isset($data['for_id'])){
            $additional_fields = $this->app->fields_manager->get($data['for'], $data['for_id'], 1);
        }

        $seach_address_keys = array('country', 'city', 'address', 'state', 'zip');
        $addr_found_from_search_in_post = false;

        if (isset($data) and is_array($data)){
            foreach ($data as $k => $v) {
                if (is_array($v)){
                    foreach ($seach_address_keys as $item) {
                        $case1 = ucfirst($item);
                        if (!isset($data[ $item ]) and (isset($v[ $item ]) or isset($v[ $case1 ]))){
                            $data[ $item ] = $v[ $item ];
                            if ($addr_found_from_search_in_post==false){
                                unset($data[ $k ]);
                            }
                            $addr_found_from_search_in_post = 1;

                        }
                    }

                }
            }
        }

        $save_custom_fields_for_order = array();
        if (is_array($additional_fields) and !empty($additional_fields)){
            foreach ($additional_fields as $cf) {
                if (isset($data) and is_array($data)){
                    foreach ($data as $k => $item) {
                        $key1 = str_replace('_', ' ', $cf['name']);
                        $key2 = str_replace('_', ' ', $k);
                        if ($key1==$key2){
                            $save_custom_fields_for_order[ $key1 ] = $this->app->format->clean_html($item);
                        }
                    }
                }
            }
        }


        $checkout_errors = array();
        $check_cart = $this->get_cart($cart);

        if (!is_array($check_cart)){
            $checkout_errors['cart_empty'] = 'Your cart is empty';
        } else {

            if (!isset($data['payment_gw']) and $mw_process_payment==true){
                $data['payment_gw'] = 'none';
            } else {
                if ($mw_process_payment==true){
                    $gw_check = $this->payment_options('payment_gw_' . $data['payment_gw']);
                    if (is_array($gw_check[0])){
                        $gateway = $gw_check[0];
                    } else {
                        $checkout_errors['payment_gw'] = 'No such payment gateway is activated';
                    }

                }
            }

            $shipping_country = false;
            $shipping_cost_max = false;
            $shipping_cost = false;
            $shipping_cost_above = false;

            if ((mw()->user_manager->session_get('shipping_country'))){
                $shipping_country = mw()->user_manager->session_get('shipping_country');
            }
            if ((mw()->user_manager->session_get('shipping_cost_max'))){
                $shipping_cost_max = mw()->user_manager->session_get('shipping_cost_max');
            }
            if ((mw()->user_manager->session_get('shipping_cost'))){
                $shipping_cost = mw()->user_manager->session_get('shipping_cost');
            }
            if ((mw()->user_manager->session_get('shipping_cost_above'))){
                $shipping_cost_above = mw()->user_manager->session_get('shipping_cost_above');
            }


            //post any of those on the form
            $flds_from_data = array('first_name', 'last_name', 'email', 'country', 'city', 'state', 'zip', 'address', 'address2', 'payment_email', 'payment_name', 'payment_country', 'payment_address', 'payment_city', 'payment_state', 'payment_zip', 'phone', 'promo_code', 'payment_gw');

            if (!isset($data['email']) or $data['email']==''){
                $checkout_errors['email'] = 'Email is required';
            }
            if (!isset($data['first_name']) or $data['first_name']==''){
                $checkout_errors['first_name'] = 'First name is required';
            }

            if (!isset($data['last_name']) or $data['last_name']==''){
                $checkout_errors['last_name'] = 'Last name is required';
            }

            if (isset($data['payment_gw']) and $data['payment_gw']!=''){
                $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);
            }

            $custom_order_id = $this->app->option_manager->get('custom_order_id', 'shop');
            $posted_fields = array();
            $place_order = array();
            $place_order['id'] = false;

            $return_url_after = '';
            if ($this->app->url_manager->is_ajax()){
                $place_order['url'] = $this->app->url_manager->current(true);
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
            } elseif (isset($_SERVER['HTTP_REFERER'])) {
                $place_order['url'] = $_SERVER['HTTP_REFERER'];
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
            } else {
                $place_order['url'] = $this->app->url_manager->current();

            }

            $place_order['session_id'] = $sid;
            $place_order['order_completed'] = 0;
            $items_count = 0;

            foreach ($flds_from_data as $value) {
                if (isset($data[ $value ]) and ($data[ $value ])!=false){
                    $place_order[ $value ] = $data[ $value ];
                    $posted_fields[ $value ] = $data[ $value ];

                }
            }

            $amount = $this->cart_total();
            $tax = $this->app->cart_manager->get_tax();

            if (!empty($checkout_errors)){
                return array('error' => $checkout_errors);
            }

            $place_order['amount'] = $amount;
            $place_order['allow_html'] = true;
            $place_order['currency'] = $this->app->option_manager->get('currency', 'payments');

            if (isset($data['shipping_gw'])){
                $place_order['shipping_service'] = $data['shipping_gw'];
            }
            $place_order['shipping'] = $shipping_cost;
            if ($tax!=0){
                $place_order['taxes_amount'] = $tax;
            }

            $items_count = $this->cart_sum(false);
            $place_order['items_count'] = $items_count;

            $cart_checksum = md5($sid . serialize($check_cart) . uniqid());

            $place_order['payment_verify_token'] = $cart_checksum;

            define('FORCE_SAVE', $table_orders);

            if (isset($save_custom_fields_for_order) and !empty($save_custom_fields_for_order)){
                $place_order['custom_fields_data'] = $this->app->format->array_to_base64($save_custom_fields_for_order);
            }

            if (!isset($place_order['shipping']) or $place_order['shipping']==false){
                $place_order['shipping'] = 0;
            }


            $temp_order = $this->app->database_manager->save($table_orders, $place_order);
            if ($temp_order!=false){
                $place_order['id'] = $temp_order;
            } else {
                $place_order['id'] = 0;
            }

            if ($custom_order_id!=false){
                foreach ($place_order as $key => $value) {
                    $custom_order_id = str_ireplace('{' . $key . '}', $value, $custom_order_id);
                }


                $custom_order_id = str_ireplace('{YYYYMMDD}', date('Ymd'), $custom_order_id);
                $custom_order_id = str_ireplace('{date}', date('Y-m-d'), $custom_order_id);

            }


            if ($custom_order_id!=false){
                $place_order['item_name'] = 'Order id:' . ' ' . $custom_order_id;
                $place_order['order_id'] = $custom_order_id;

            } else {
                $place_order['item_name'] = 'Order id:' . ' ' . $place_order['id'];

            }

            if ($mw_process_payment==true){
                $shop_dir = module_dir('shop');
                $shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;

                if ($data['payment_gw']!='none'){

                    $gw_process = modules_path() . $data['payment_gw'] . '_process.php';
                    if (!is_file($gw_process)){
                        $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'process.php', false);
                    }

                    $mw_return_url = $this->app->url_manager->api_link('checkout') . '?mw_payment_success=1&order_id=' . $place_order['id'] . '&payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . '&order_id=' . $place_order['id'] . $return_url_after;
                    $mw_cancel_url = $this->app->url_manager->api_link('checkout') . '?mw_payment_failure=1&order_id=' . $place_order['id'] . '&payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . '&order_id=' . $place_order['id'] . $return_url_after;
                    $mw_ipn_url = $this->app->url_manager->api_link('checkout_ipn') . '?payment_gw=' . $data['payment_gw'] . '&order_id=' . $place_order['id'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . $return_url_after;
//                    $place_order['success_url'] = $mw_return_url;
//                    $place_order['cancel_url'] = $mw_cancel_url;
//                    $place_order['notify_url'] = $mw_ipn_url;
                    if (is_file($gw_process)){
                        require_once $gw_process;
                    } else {
                        $checkout_errors['payment_gw'] = 'Payment gateway\'s process file not found.';
                    }
                } else {
                    $place_order['order_completed'] = 1;
                    $place_order['is_paid'] = 0;
                    $place_order['success'] = "Your order has been placed successfully!";
                }
                $place_order['order_status'] = 'pending';
                if (!empty($checkout_errors)){
                    return array('error' => $checkout_errors);
                }
                $ord = $this->place_order($place_order);
                $place_order['id'] = $ord;
            }

            if (isset($place_order) and !empty($place_order)){
                if (!isset($place_order['success'])){
                    $place_order['success'] = "Your order has been placed successfully!";
                }
                $return = $place_order;
                if (isset($place_order['redirect'])){
                    $return['redirect'] = $place_order['redirect'];
                }


                return $return;
            }

        }


        if (!empty($checkout_errors)){

            return array('error' => $checkout_errors);
        }

    }


    public function place_order($place_order) {
        return $this->app->order_manager->place_order($place_order);
    }


    public function confirm_email_send($order_id, $to = false, $no_cache = false, $skip_enabled_check = false) {
        return $this->app->checkout_manager->confirm_email_send($order_id, $to, $no_cache, $skip_enabled_check);
    }

    public function get_order_by_id($id = false) {
        return $this->app->order_manager->get_by_id($id);
    }


    function empty_cart() {
        return $this->app->cart_manager->empty_cart();
    }

    public function get_cart($params = false) {
        return $this->app->cart_manager->get($params);
    }


    public function remove_cart_item($data) {
        return $this->app->cart_manager->remove_item($data);
    }


    public function update_cart_item_qty($data) {
        return $this->app->cart_manager->update_item_qty($data);
    }

    public function update_cart($data) {
        return $this->app->cart_manager->update_cart($data);
    }

    public function payment_options($option_key = false) {
        return $this->app->checkout_manager->payment_options($option_key);

        $option_key_q = '';
        if (is_string($option_key)){
            $option_key_q = "&limit=1&option_key={$option_key}";

        }
        $providers = $this->app->option_manager->get_all('option_group=payments' . $option_key_q);

        $payment_modules = get_modules('type=payment_gateway');
        $str = 'payment_gw_';
        $l = strlen($str);
        $enabled_providers = array();
        if (!empty($payment_modules) and !empty($providers)){
            foreach ($payment_modules as $payment_module) {
                foreach ($providers as $value) {
                    if ($value['option_value']==1){
                        if (substr($value['option_key'], 0, $l)==$str){
                            $title = substr($value['option_key'], $l);
                            $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
                            $value['gw_file'] = $title;

                            if (isset($payment_module['module']) and $value['gw_file']==$payment_module['module']){
                                $payment_module['gw_file'] = $title;
                                $enabled_providers[] = $payment_module;
                            }
                        }
                    }
                }
            }

        }

        if (!empty($enabled_providers)){
            return $enabled_providers;
        }


        // the rest is for comaptibily and will be removed in the near future
        $str = 'payment_gw_';
        $l = strlen($str);
        if (is_array($providers)){
            $valid = array();
            foreach ($providers as $value) {
                if ($value['option_value']==1){
                    if (substr($value['option_key'], 0, $l)==$str){
                        $title = substr($value['option_key'], $l);
                        $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
                        $value['gw_file'] = $title;
                        $mod_infp = $this->app->modules->get('ui=any&one=1&module=' . $title);

                        if (!empty($mod_infp)){
                            $value = $mod_infp;
                            $title = str_replace('..', '', $title);

                            $value['gw_file'] = $title;
                            $valid[] = $value;
                        }


                    }
                }
            }

            return $valid;
        }


    }

    /**
     * @param bool $return_amount
     *
     * @return array|false|float|int|mixed
     */
    public function cart_sum($return_amount = true) {
        return $this->app->cart_manager->sum($return_amount);
    }


    public function cart_total() {
        return $this->app->cart_manager->total();
    }


    /**
     * Remove quantity from product
     *
     * On completed order this function deducts the product quantities.
     *
     * @param bool|string $order_id
     *   The id of the order that is completed
     *
     * @return boolean
     *   True if quantity is updated
     */
    function update_quantities($order_id = false) {
        $order_id = intval($order_id);
        if ($order_id==false){
            return;
        }
        $res = false;
        $ord_data = $this->get_order_by_id($order_id);

        $cart_data = $this->order_items($order_id);
        if (!empty($cart_data)){
            $res = array();
            foreach ($cart_data as $item) {
                if (isset($item['rel_type']) and isset($item['rel_id']) and $item['rel_type']=='content'){
                    $data_fields = $this->app->content_manager->data($item['rel_id'], 1);
                    if (isset($item['qty']) and isset($data_fields['qty']) and $data_fields['qty']!='nolimit'){
                        $old_qty = intval($data_fields['qty']);
                        $new_qty = $old_qty - intval($item['qty']);
                        $new_qty = intval($new_qty);
                        $notify = false;
                        $new_q = array();
                        $new_q['field_name'] = 'qty';
                        $new_q['content_id'] = $item['rel_id'];
                        if ($new_qty > 0){
                            $new_q['field_value'] = $new_qty;
                        } else {
                            $notify = true;
                            $new_q['field_value'] = '0';
                        }
                        $res[] = $new_q;
                        $upd_qty = $this->app->content_manager->save_content_data_field($new_q);
                        $res = true;
                        if ($notify){
                            $notification = array();
                            $notification['rel_type'] = 'content';
                            $notification['rel_id'] = $item['rel_id'];
                            $notification['title'] = "Your item is out of stock!";
                            $notification['description'] = "You sold all items you had in stock. Please update your quantity";
                            $this->app->notifications_manager->save($notification);
                        }
                    }
                }
            }
        }

        return $res;
    }

    public function order_items($order_id = false) {
        return $this->app->order_manager->get_items($order_id);
    }

    public function after_checkout($order_id, $suppress_output = true) {
        return $this->app->checkout_manager->after_checkout($order_id, $suppress_output);

    }

    public function get_orders($params = false) {
        return $this->app->order_manager->get($params);
    }


    public function checkout_confirm_email_test($params) {

        if (!isset($params['to'])){
            $email_from = $this->app->option_manager->get('email_from', 'email');
            if ($email_from==false){
                return array('error' => 'You must set up your email');
            }
        } else {
            $email_from = $params['to'];

        }
        $ord_data = $this->get_orders('limit=50');

        if (is_array($ord_data[0])){
            shuffle($ord_data);
            $ord_test = $ord_data[0];


            return $this->app->checkout_manager->confirm_email_send($ord_test['id'], $to = $email_from, true, true);
        }

    }


    public function checkout_ipn($data) {

        if (isset($data['payment_verify_token'])){
            $payment_verify_token = ($data['payment_verify_token']);
        }
        if (!isset($data['payment_gw'])){
            return array('error' => 'You must provide a payment gateway parameter!');
        }

        $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);

        $hostname = $this->get_domain_from_str($_SERVER['REMOTE_ADDR']);


        $payment_verify_token = $this->app->database_manager->escape_string($payment_verify_token);
        $table = $this->tables['cart_orders'];

        $query = array();
        $query['payment_verify_token'] = $payment_verify_token;
        if (isset($data['order_id'])){
            $query['id'] = intval($data['order_id']);
        } else {
            $query['transaction_id'] = '[null]';

        }
        $query['limit'] = 1;
        $query['table'] = $table;
        $query['no_cache'] = true;

        $ord_data = $this->app->database_manager->get($query);
        if (!isset($ord_data[0]) or !is_array($ord_data[0])){
            return array('error' => 'Order is completed or expired.');
        } else {
            $ord_data = $ord_data[0];
            $ord = $ord_data['id'];
        }

        $cart_table = $this->tables['cart'];
        $table_orders = $this->tables['cart_orders'];

        $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);
        $gw_process = modules_path() . $data['payment_gw'] . '_checkout_ipn.php';
        if (!is_file($gw_process)){
            $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'checkout_ipn.php', false);
        }
        if (!is_file($gw_process)){
            $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'notify.php', false);
        }

        $update_order = array();
        if (is_file($gw_process)){
            include $gw_process;
        } else {
            return array('error' => 'The payment gateway is not found!');
        }

        if (!empty($update_order) and isset($update_order['order_completed']) and trim($update_order['order_completed'])==1){
            $update_order['id'] = $ord;
            $update_order['payment_gw'] = $data['payment_gw'];
            $ord = $this->app->database_manager->save($table_orders, $update_order);
            $this->confirm_email_send($ord);
            if (isset($update_order['is_paid']) and $update_order['is_paid']==1){
                $this->update_quantities($ord);
            }
            if ($ord > 0){
                $this->app->cache_manager->delete('cart/global');
                $this->app->cache_manager->delete('cart_orders/global');
                //return true;
            }
        }

        if (isset($data['return_to'])){
            $return_to = urldecode($data['return_to']);

            $append = '?';
            if (strstr($return_to, '?')){
                $append = '&';
            }
            $return_to = $return_to . $append . 'mw_payment_success=1';

            return $this->app->url_manager->redirect($return_to);
        }

        return;
    }

    private function get_domain_from_str($address) {
        $address = gethostbyaddr($address);
        $parsed_url = parse_url($address);
        if (!isset($parsed_url['host'])){
            if (isset($parsed_url['path'])){
                $parsed_url['host'] = $parsed_url['path'];
            }
        }
        $check = $this->esip($parsed_url['host']);
        $host = $parsed_url['host'];
        if ($check==false){
            if ($host!=""){
                $host = $this->domain_name($host);
            } else {
                $host = $this->domain_name($address);
            }
        }

        return $host;
    }

    private function esip($ip_addr) {
        if (preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr)){
            $parts = explode(".", $ip_addr);
            foreach ($parts as $ip_parts) {
                if (intval($ip_parts) > 255 || intval($ip_parts) < 0){
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    private function domain_name($domainb) {
        $bits = explode('/', $domainb);
        if ($bits[0]=='http:' || $bits[0]=='https:'){
            $domainb = $bits[2];
        } else {
            $domainb = $bits[0];
        }
        unset($bits);
        $bits = explode('.', $domainb);
        $idz = count($bits);
        $idz -= 3;
        if (strlen($bits[ ($idz + 2) ])==2){
            $url = $bits[ $idz ] . '.' . $bits[ ($idz + 1) ] . '.' . $bits[ ($idz + 2) ];
        } else if (strlen($bits[ ($idz + 2) ])==0){
            $url = $bits[ ($idz) ] . '.' . $bits[ ($idz + 1) ];
        } else {
            $url = $bits[ ($idz + 1) ] . '.' . $bits[ ($idz + 2) ];
        }

        return $url;
    }


    public function update_order($params = false) {
        return $this->app->order_manager->save($params);

    }

    public function delete_client($data) {
        $adm = $this->app->user_manager->is_admin();
        if ($adm==false){
            $this->app->error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->tables['cart_orders'];
        if (isset($data['email'])){
            $c_id = $this->app->database_manager->escape_string($data['email']);
            $res = $this->app->database_manager->delete_by_id($table, $c_id, 'email');
            $this->app->cache_manager->delete('cart_orders/global');

            return $res;
        }
    }

    public function delete_order($data) {
        return $this->app->order_manager->delete_order($data);
    }


    public function currency_get_for_paypal() {
        $curencies = array('USD', 'EUR', 'GBP', 'AUD', 'CAD', 'JPY', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'MYR', 'BRL', 'PHP', 'TWD', 'THB', 'TRY');

        return $curencies;
    }

    public function currency_convert_rate($from, $to) {
        return;
        $function_cache_id = __FUNCTION__ . md5($from . $to);
        $cache_group = 'shop';
        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
        if (($cache_content)!=false){
            return $cache_content;
        }
        if ($to==''){
            $to = $from;
        }

        $remote_host = 'http://api.microweber.com';
        $service = "/service/currency/?from=" . $from . "&to=" . $to;
        $remote_host_s = $remote_host . $service;


        $curl = new \Microweber\Utils\Http();
        $curl->set_timeout(3);
        $curl->url($remote_host_s);
        $get_remote = $curl->get();


        if ($get_remote!=false){
            $this->app->cache_manager->save($get_remote, $function_cache_id, $cache_group);

            return floatval($get_remote);
        }
    }

    function currency_format($amount, $curr = false) {

        if ($curr==false){
            $curr = $this->app->option_manager->get('currency', 'payments');
        }
        $amount = floatval($amount);
        $sym = $this->currency_symbol($curr);

        if ($sym==''){
            $sym = $curr;
        }


        $cur_pos = $this->app->option_manager->get('currency_symbol_position', 'payments');

        switch ($cur_pos) {
            case "before":
                $ret = $sym . ' ' . number_format($amount, 2, ".", ",");
                break;
            case "after":
                $ret = number_format($amount, 2, ".", " ") . ' ' . $sym;

                break;
            case "default":
            default:
                switch ($curr) {
                    case "EUR":
                        $ret = "&euro; " . number_format($amount, 2, ",", " ");
                        break;
                    case "BGN":
                    case "RUB":
                        $ret = number_format($amount, 2, ".", " ") . ' ' . $sym;
                        break;
                    case "US":
                    case "USD":
                        $ret = "&#36; " . number_format($amount, 2, ".", ",");
                        break;
                    default:
                        $ret = $sym . ' ' . number_format($amount, 2, ".", ",");
                        break;
                }
                break;

        }

        return $ret;
    }

    public function currency_symbol($curr = false, $key = 3) {
        if ($curr==false){
            $curr = $this->app->option_manager->get('currency', 'payments');
        }
        $all_cur = $this->currency_get();
        if (is_array($all_cur)){
            foreach ($all_cur as $value) {
                if (in_array($curr, $value)){
                    if ($key==false){
                        return $value;
                    } else {
                        $sym = $value[ $key ];

                        return $sym;
                    }
                }
            }
        }
    }

    public function currency_get() {

        static $currencies_list = false;

        if ($currencies_list){
            return $currencies_list;
        }

        $row = 1;
        $cur_file = MW_PATH . 'Utils' . DS . 'lib' . DS . 'currencies.csv';
        if (is_file($cur_file)){
            if (($handle = fopen($cur_file, "r"))!==false){
                $res = array();
                while (($data = fgetcsv($handle, 1000, ","))!==false) {
                    $itm = array();
                    $num = count($data);
                    $row ++;
                    for ($c = 0; $c < $num; $c ++) {
                        $itm[] = $data[ $c ];
                    }
                    $res[] = $itm;
                }
                fclose($handle);
                $currencies_list = $res;

                return $res;
            }
        }
    }

    function checkout_url() {
        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'checkout.php';
        if (is_file($file)){
            $default_url = 'checkout';
        } else {
            $default_url = 'shop/checkout';
        }
        $checkout_url = $this->app->option_manager->get('checkout_url', 'shop');
        if ($checkout_url!=false and trim($checkout_url)!=''){
            $default_url = $checkout_url;
        }
        $checkout_url_sess = $this->app->user_manager->session_get('checkout_url');
        if ($checkout_url_sess==false){
            return $this->app->url_manager->site($default_url);
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
            $this->app->cache_manager->delete('options/global');
        }
        $this->app->cache_manager->save('--true--', $function_cache_id, $cache_group = 'db');

        return true;
    }*/


}