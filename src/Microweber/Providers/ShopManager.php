<?php
namespace Microweber\Providers;

use DB;

//event_bind('mw_db_init_default', mw()->shop_manager->db_init());

/**
 *
 * Shop module api
 *
 * @package           modules
 * @subpackage        shop
 * @since             Version 0.1
 */

// ------------------------------------------------------------------------

api_expose_admin('shop/update_order');

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
        $this->tables = $tables;


    }


    public function checkout($data) {


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

            $amount = $this->cart_sum();
            if (!empty($checkout_errors)){
                return array('error' => $checkout_errors);
            }

            $place_order['amount'] = $amount;
            $place_order['currency'] = $this->app->option_manager->get('currency', 'payments');

            if (isset($data['shipping_gw'])){
                $place_order['shipping_service'] = $data['shipping_gw'];
            }
            $place_order['shipping'] = $shipping_cost;

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

            $place_order['item_name'] = 'Order id:' . ' ' . $place_order['id'];

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
        $sid = mw()->user_manager->session_id();
        if ($sid==false){
            return $sid;
        }

        $ord = $this->app->database_manager->save($this->tables['cart_orders'], $place_order);
        $place_order['id'] = $ord;

        DB::transaction(function () use ($sid, $ord, $place_order) {
            DB::table($this->tables['cart'])->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord]);

            if (isset($place_order['order_completed']) and $place_order['order_completed']==1){
                DB::table($this->tables['cart'])->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord, 'order_completed' => 1]);

                if (isset($place_order['is_paid']) and $place_order['is_paid']==1){
                    DB::table($this->tables['cart_orders'])->whereOrderCompleted(0)->whereSessionId($sid)->whereId($ord)->update(['order_completed' => 1]);
                }
                $this->app->cache_manager->delete('cart');
                $this->app->cache_manager->delete('cart_orders');
                if (isset($place_order['is_paid']) and $place_order['is_paid']==1){
                    $this->update_quantities($ord);
                }
                $this->after_checkout($ord);
            }
        });

        mw()->user_manager->session_set('order_id', $ord);

        return $ord;
    }

    public function confirm_email_send($order_id, $to = false, $no_cache = false, $skip_enabled_check = false) {

        $ord_data = $this->get_order_by_id($order_id);

        if (is_array($ord_data)){
            if ($skip_enabled_check==false){
                $order_email_enabled = $this->app->option_manager->get('order_email_enabled', 'orders');
            } else {
                $order_email_enabled = $skip_enabled_check;
            }
            if ($order_email_enabled==true){
                $order_email_subject = $this->app->option_manager->get('order_email_subject', 'orders');
                $order_email_content = $this->app->option_manager->get('order_email_content', 'orders');
                $order_email_cc = $this->app->option_manager->get('order_email_cc', 'orders');

                if ($order_email_subject==false or trim($order_email_subject)==''){
                    $order_email_subject = "Thank you for your order!";
                }
                if ($to==false){

                    $to = $ord_data['email'];
                }
                if ($order_email_content!=false and trim($order_email_subject)!=''){
                    if (!empty($ord_data)){
                        $cart_items = $this->get_cart('fields=title,qty,price,custom_fields_data&order_id=' . $ord_data['id'] . '&no_session_id=' . mw()->user_manager->session_id());
                        // $cart_items = $this->order_items($ord_data['id']);
                        $order_items_html = $this->app->format->array_to_ul($cart_items);
                        // dd($order_items_html);
                        $order_email_content = str_replace('{cart_items}', $order_items_html, $order_email_content);
                        foreach ($ord_data as $key => $value) {

                            if (!is_array($value) and is_string($key)){
                                if (strtolower($key)=='amount'){
                                    $value = number_format($value, 2);
                                }
                                $order_email_content = str_ireplace('{' . $key . '}', $value, $order_email_content);
                            }
                        }
                    }

                    //
                    if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))){

                        $sender = new \Microweber\Utils\MailSender();
                        $sender->send($to, $order_email_subject, $order_email_content);
                        $cc = false;
                        if (isset($order_email_cc) and (filter_var($order_email_cc, FILTER_VALIDATE_EMAIL))){
                            $cc = $order_email_cc;
                            $sender->send($cc, $order_email_subject, $order_email_content);

                        }

                        return true;

                    }

                }
            }
        }
    }

    public function get_order_by_id($id = false) {


        $table = $this->tables['cart_orders'];
        $params['table'] = $table;
        $params['one'] = true;

        $params['id'] = intval($id);

        $item = $this->app->database_manager->get($params);

        if (is_array($item) and isset($item['custom_fields_data']) and $item['custom_fields_data']!=''){
            $item = $this->_render_item_custom_fields_data($item);
        }

        return $item;

    }

    private function _render_item_custom_fields_data($item) {
        if (isset($item['custom_fields_data']) and $item['custom_fields_data']!=''){
            $item['custom_fields_data'] = $this->app->format->base64_to_array($item['custom_fields_data']);
            if (isset($item['custom_fields_data']) and is_array($item['custom_fields_data']) and !empty($item['custom_fields_data'])){
                $tmp_val = mw()->format->array_to_ul($item['custom_fields_data']);
                $item['custom_fields'] = $tmp_val;
            }
        }

        return $item;
    }

    public function get_cart($params = false) {


        $time = time();
        $clear_carts_cache = $this->app->cache_manager->get('clear_cache', 'cart/global');

        if ($clear_carts_cache==false or ($clear_carts_cache < ($time - 600))){
            // clears cache for old carts
            $this->app->cache_manager->delete('cart/global');
            $this->app->cache_manager->save($time, 'clear_cache', 'cart/global');
        }


        $params2 = array();

        if (is_string($params)){
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $table = $this->tables['cart'];
        $params['table'] = $table;
        $skip_sid = false;
        if (!defined('MW_API_CALL')){
            if (isset($params['order_id'])){
                $skip_sid = 1;
            }
        }
        if ($skip_sid==false){
            if (!defined('MW_ORDERS_SKIP_SID')){
                if ($this->app->user_manager->is_admin()==false){
                    $params['session_id'] = mw()->user_manager->session_id();
                } else {
                    if (isset($params['session_id']) and $this->app->user_manager->is_admin()==true){

                    } else {
                        $params['session_id'] = mw()->user_manager->session_id();
                    }
                }
                if (isset($params['no_session_id']) and $this->app->user_manager->is_admin()==true){
                    unset($params['session_id']);
                }
            }
        }
        if (!isset($params['rel']) and isset($params['for'])){
            $params['rel_type'] = $params['for'];
        } else if (isset($params['rel']) and !isset($params['rel_type'])){
            $params['rel_type'] = $params['rel'];
        }
        if (!isset($params['rel_id']) and isset($params['for_id'])){
            $params['rel_id'] = $params['for_id'];
        }

        $params['limit'] = 10000;
        if (!isset($params['order_completed'])){
            if (!isset($params['order_id'])){
                $params['order_completed'] = 0;
            }
        } elseif (isset($params['order_completed']) and $params['order_completed']==='any') {

            unset($params['order_completed']);
        }
        // $params['no_cache'] = 1;

        $get = $this->app->database_manager->get($params);
        if (isset($params['count']) and $params['count']!=false){
            return $get;
        }
        $return = array();
        if (is_array($get)){
            foreach ($get as $k => $item) {
                if (isset($item['rel_id']) and isset($item['rel_type']) and $item['rel_type']=='content'){
                    $item['content_data'] = $this->app->content_manager->data($item['rel_id']);
                }
                if (isset($item['custom_fields_data']) and $item['custom_fields_data']!=''){
                    $item = $this->_render_item_custom_fields_data($item);
                }
                if (isset($item['title'])){
                    $item['title'] = html_entity_decode($item['title']);
                    $item['title'] = strip_tags($item['title']);
                    $item['title'] = $this->app->format->clean_html($item['title']);
                    $item['title'] = htmlspecialchars_decode($item['title']);

                }
                $return[ $k ] = $item;
            }
        } else {
            $return = $get;
        }

        return $return;
    }

    public function recover_shopping_cart($sid = false, $ord_id = false) {
        if ($sid==false){
            return;
        }

        $cur_sid = mw()->user_manager->session_id();

        if ($cur_sid==false){
            return;
        } else {
            if ($cur_sid!=false){

                $c_id = $sid;

                $table = $this->tables['cart'];


                $params = array();
                $params['order_completed'] = 0;
                $params['session_id'] = $c_id;
                $params['table'] = $table;
                if ($ord_id!=false){
                    unset($params['order_completed']);
                    $params['order_id'] = intval($ord_id);
                }

                $will_add = true;
                $res = $this->app->database_manager->get($params);

                if (empty($res)){
                    //$params['order_completed'] = 1;
                    //  $res = $this->app->database_manager->get($params);
                }


                if (!empty($res)){
                    foreach ($res as $item) {
                        if (isset($item['id'])){
                            $data = $item;
                            unset($data['id']);
                            if (isset($item['order_id'])){
                                unset($data['order_id']);
                            }
                            if (isset($item['created_by'])){
                                unset($data['created_by']);
                            }
                            if (isset($item['updated_at'])){
                                unset($data['updated_at']);
                            }


                            if (isset($item['rel_type']) and isset($item['rel_id'])){
                                $is_ex_params = array();
                                $is_ex_params['order_completed'] = 0;
                                $is_ex_params['session_id'] = $cur_sid;
                                $is_ex_params['table'] = $table;
                                $is_ex_params['rel_type'] = $item['rel_type'];
                                $is_ex_params['rel_id'] = $item['rel_id'];
                                $is_ex_params['count'] = 1;

                                $is_ex = $this->app->database_manager->get($is_ex_params);

                                if ($is_ex!=false){
                                    $will_add = false;
                                }
                            }
                            $data['order_completed'] = 0;
                            $data['session_id'] = $cur_sid;
                            if ($will_add==true){
                                $s = $this->app->database_manager->save($table, $data);
                            }

                        }

                    }

                }

                if ($will_add==true){
                    $this->app->cache_manager->delete('cart');

                    $this->app->cache_manager->delete('cart_orders/global');
                }
            }
        }

    }

    public function payment_options($option_key = false) {

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
                            $value['gw_file'] = $title;
                            $valid[] = $value;
                        }


                    }
                }
            }

            return $valid;
        }


    }

    public function cart_sum($return_amount = true) {


        $sid = mw()->user_manager->session_id();
        $different_items = 0;
        $amount = floatval(0.00);
        $cart = $this->tables['cart'];
        $cart_table_real = $this->app->database_manager->real_table_name($cart);

        $sumq = " SELECT  price, qty FROM $cart_table_real WHERE order_completed=0  AND session_id='{$sid}'  ";
        $sumq = $this->app->database_manager->query($sumq);
        if (is_array($sumq)){
            foreach ($sumq as $value) {
                $different_items = $different_items + $value['qty'];
                $amount = $amount + (intval($value['qty']) * floatval($value['price']));
            }
        }
        if ($return_amount==false){
            return $different_items;
        }

        return $amount;
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
        $order_id = intval($order_id);
        if ($order_id==false){
            return;
        }
        $params = array();
        $table = $this->tables['cart'];
        $params['table'] = $table;
        $params['order_id'] = $order_id;
        $get = $this->app->database_manager->get($params);
        if (!empty($get)){
            foreach ($get as $k => $item) {
                if (is_array($item) and isset($item['custom_fields_data']) and $item['custom_fields_data']!=''){
                    $item = $this->_render_item_custom_fields_data($item);
                }
                $get[ $k ] = $item;
            }
        }


        return $get;
    }

    public function after_checkout($order_id, $suppress_output = true) {
        if ($suppress_output==true){
            ob_start();
        }
        if ($order_id==false or trim($order_id)==''){
            return array('error' => 'Invalid order ID');
        }

        $ord_data = $this->get_orders('one=1&id=' . $order_id);
        if (is_array($ord_data)){

            $ord = $order_id;
            $notification = array();
            $notification['module'] = "shop";
            $notification['rel_type'] = 'cart_orders';
            $notification['rel_id'] = $ord;
            $notification['title'] = "You have new order";
            $notification['description'] = "New order is placed from " . $this->app->url_manager->current(1);
            $notification['content'] = "New order in the online shop. Order id: " . $ord;
            $this->app->notifications_manager->save($notification);
            $this->app->log_manager->save($notification);
            $this->confirm_email_send($order_id);

        }
        if ($suppress_output==true){
            ob_end_clean();
        }
    }

    public function get_orders($params = false) {

        $params2 = array();
        if ($params==false){
            $params = array();
        }
        if (is_string($params)){
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if (defined('MW_API_CALL') and $this->app->user_manager->is_admin()==false){

            if (!isset($params['payment_verify_token'])){
                $params['session_id'] = mw()->user_manager->session_id();
            }

        }


        if (isset($params['keyword'])){
            $params['search_in_fields'] = array('first_name', 'last_name', 'email', 'city', 'state', 'zip', 'address', 'address2', 'phone', 'promo_code');
        }


        $table = $this->tables['cart_orders'];
        $params['table'] = $table;

        return $this->app->database_manager->get($params);

    }

    public function remove_cart_item($data) {

        if (!is_array($data)){
            $id = intval($data);
            $data = array('id' => $id);
        }


        if (!isset($data['id']) or $data['id']==0){
            return false;
        }

        $cart = array();
        $cart['id'] = intval($data['id']);

        if ($this->app->user_manager->is_admin()==false){
            $cart['session_id'] = mw()->user_manager->session_id();
        }
        $cart['order_completed'] = 0;

        $cart['one'] = 1;
        $cart['limit'] = 1;
        $check_cart = $this->get_cart($cart);

        if ($check_cart!=false and is_array($check_cart)){
            $table = $this->tables['cart'];
            $this->app->database_manager->delete_by_id($table, $id = $cart['id'], $field_name = 'id');
        } else {

        }
    }

    public function update_cart_item_qty($data) {

        if (!isset($data['id'])){
            $this->app->error('Invalid data');
        }
        if (!isset($data['qty'])){
            $this->app->error('Invalid data');
        }
        $cart = array();
        $cart['id'] = intval($data['id']);
        $cart['session_id'] = mw()->user_manager->session_id();

        $cart['order_completed'] = 0;
        $cart['one'] = 1;
        $cart['limit'] = 1;
        $check_cart = $this->get_cart($cart);
        if (isset($check_cart['rel_type']) and isset($check_cart['rel_id']) and $check_cart['rel_type']=='content'){
            $data_fields = $this->app->content_manager->data($check_cart['rel_id'], 1);
            if (isset($check_cart['qty']) and isset($data_fields['qty']) and $data_fields['qty']!='nolimit'){
                $old_qty = intval($data_fields['qty']);
                if (intval($data['qty']) > $old_qty){
                    return false;
                }
            }
        }
        if ($check_cart!=false and is_array($check_cart)){
            $cart['qty'] = intval($data['qty']);
            if ($cart['qty'] < 0){
                $cart['qty'] = 0;
            }
            $table = $this->tables['cart'];
            $cart_data_to_save = array();
            $cart_data_to_save['qty'] = $cart['qty'];
            $cart_data_to_save['id'] = $cart['id'];
            $cart_saved_id = $this->app->database_manager->save($table, $cart_data_to_save);

            return ($cart_saved_id);
        }
    }

    public function update_cart($data) {
        if (isset($data['content_id'])){
            $data['for'] = 'content';
            $for_id = $data['for_id'] = $data['content_id'];
        }
        $override = $this->app->event_manager->trigger('mw.shop.update_cart', $data);
        if (is_array($override)){
            foreach ($override as $resp) {
                if (is_array($resp) and !empty($resp)){
                    $data = array_merge($data, $resp);
                }
            }
        }
        if (!isset($data['for'])){
            $data['for'] = 'content';
        }
        $update_qty = 0;
        $update_qty_new = 0;

        if (isset($data['qty'])){
            $update_qty_new = $update_qty = intval($data['qty']);
            unset($data['qty']);
        }
        if (!isset($data['for']) or !isset($data['for_id'])){
            if (!isset($data['id'])){

//                if (!isset($data['title']) and !isset($data['price'])) {
//                    $this->app->error('Invalid data');
//
//                }

            } else {
                $cart = array();
                $cart['id'] = intval($data['id']);
                $cart['limit'] = 1;
                $data_existing = $this->get_cart($cart);
                if (is_array($data_existing) and is_array($data_existing[0])){
                    $data = array_merge($data, $data_existing[0]);
                }
            }
        }


        if (!isset($data['for']) and isset($data['rel_type'])){
            $data['for'] = $data['rel_type'];
        }
        if (!isset($data['for_id']) and isset($data['rel_id'])){
            $data['for_id'] = $data['rel_id'];
        }
        if (!isset($data['for']) and !isset($data['for_id'])){
            $this->app->error('Invalid for and for_id params');
        }

        $data['for'] = $this->app->database_manager->assoc_table_name($data['for']);
        $for = $data['for'];
        $for_id = intval($data['for_id']);
        if ($for_id==0){
            $this->app->error('Invalid data');
        }
        $cont_data = false;

        if ($update_qty > 0){
            $data['qty'] = $update_qty;
        }

        if ($data['for']=='content'){
            $cont = $this->app->content_manager->get_by_id($for_id);
            $cont_data = $this->app->content_manager->data($for_id);
            if ($cont==false){
                $this->app->error('Invalid product?');
            } else {
                if (is_array($cont) and isset($cont['title'])){
                    $data['title'] = $cont['title'];
                }
            }
        }

        if (isset($data['title']) and is_string($data['title'])){
            $data['title'] = (strip_tags($data['title']));
        }

        $found_price = false;
        $add = array();

        if (isset($data['custom_fields_data']) and is_array($data['custom_fields_data'])){
            $add = $data['custom_fields_data'];
        }

        $prices = array();

        $skip_keys = array();

        $content_custom_fields = array();
        $content_custom_fields = $this->app->fields_manager->get($for, $for_id, 1);


        if ($content_custom_fields==false){
            $content_custom_fields = $data;
            if (isset($data['price'])){
                $found_price = $data['price'];
            }
        } elseif (is_array($content_custom_fields)) {
            foreach ($content_custom_fields as $cf) {
                if (isset($cf['type']) and $cf['type']=='price'){
                    $prices[ $cf['name'] ] = $cf['value'];
                }
            }
        }


        foreach ($data as $k => $item) {
            if ($k!='for' and $k!='for_id' and $k!='title'){
                $found = false;
                foreach ($content_custom_fields as $cf) {
                    if (isset($cf['type']) and isset($cf['name']) and $cf['type']!='price'){
                        $key1 = str_replace('_', ' ', $cf['name']);
                        $key2 = str_replace('_', ' ', $k);
                        if (isset($cf['name']) and ($cf['name']==$k or $key1==$key2)){
                            $k = str_replace('_', ' ', $k);
                            $found = true;
                            if (is_array($cf['values'])){
                                if (in_array($item, $cf['values'])){
                                    $found = true;
                                }
                            }
                            if ($found==false and $cf['value']!=$item){
                                unset($item);
                            }
                        }
                    } elseif (isset($cf['type']) and $cf['type']=='price') {
                        if ($cf['value']!=''){
                            $prices[ $cf['name'] ] = $cf['value'];
                        }
                    } elseif (isset($cf['type']) and $cf['type']=='price') {
                        if ($cf['value']!=''){
                            $prices[ $cf['name'] ] = $cf['value'];

                        }
                    }
                }
                if ($found==false){
                    $skip_keys[] = $k;
                }

                if (is_array($prices)){
                    foreach ($prices as $price_key => $price) {
                        if (isset($data['price'])){
                            if ($price==$data['price']){
                                $found = true;
                                $found_price = $price;
                            }
                        } else if ($price==$item){
                            $found = true;
                            if ($found_price==false){
                                $found_price = $item;
                            }
                        }
                    }
                    if ($found_price==false){
                        $found_price = array_pop($prices);
                    } else {
                        if (count($prices) > 1){
                            foreach ($prices as $pk => $pv) {
                                if ($pv==$found_price){
                                    $add[ $pk ] = $this->currency_format($pv);
                                }
                            }
                        }
                    }
                }
                if (isset($item)){
                    if ($found==true){
                        if ($k!='price' and !in_array($k, $skip_keys)){
                            $add[ $k ] = $this->app->format->clean_html($item);
                        }
                    }
                }

            }
            // }
        }

        if ($found_price==false and is_array($prices)){
            $found_price = array_pop($prices);
        }
        if ($found_price==false){
            $found_price = 0;
        }


        if (is_array($prices)){
            ksort($add);
            asort($add);
            $table = $this->tables['cart'];
            $cart = array();
            $cart['rel_type'] = ($data['for']);
            $cart['rel_id'] = intval($data['for_id']);
            $cart['title'] = ($data['title']);
            $cart['price'] = floatval($found_price);

            $cart_return = $cart;
            $cart_return['custom_fields_data'] = $add;
            $cart['custom_fields_data'] = $this->app->format->array_to_base64($add);
            $cart['order_completed'] = 0;
            $cart['session_id'] = mw()->user_manager->session_id();

            $cart['limit'] = 1;
            $check_cart = $this->get_cart($cart);
            if ($check_cart!=false and is_array($check_cart) and isset($check_cart[0])){

                $cart['id'] = $check_cart[0]['id'];
                if ($update_qty > 0){
                    $cart['qty'] = $check_cart[0]['qty'] + $update_qty;
                } elseif ($update_qty_new > 0) {
                    $cart['qty'] = $update_qty_new;
                } else {
                    $cart['qty'] = $check_cart[0]['qty'] + 1;
                }

            } else {

                if ($update_qty > 0){
                    $cart['qty'] = $update_qty;
                } else {
                    $cart['qty'] = 1;
                }
            }

            if (isset($cont_data['qty']) and trim($cont_data['qty'])!='nolimit'){
                if (intval($cont_data['qty']) < intval($cart['qty'])){
                    $cart['qty'] = $cont_data['qty'];
                }
            }

            if (isset($data['other_info']) and is_string($data['other_info'])){
                $cart['other_info'] = strip_tags($data['other_info']);
            }

            if (isset($data['item_image']) and is_string($data['item_image'])){
                $cart['item_image'] = mw()->format->clean_xss(strip_tags($data['item_image']));
            }

            mw_var('FORCE_SAVE', $table);

            $cart_saved_id = $this->app->database_manager->save($table, $cart);
            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders/global');

            if (isset($cart['rel_type']) and isset($cart['rel_id']) and $cart['rel_type']=='content'){
                $cart_return['image'] = $this->app->media_manager->get_picture($cart['rel_id']);
                $cart_return['product_link'] = $this->app->content_manager->link($cart['rel_id']);

            }


            return array('success' => 'Item added to cart', 'product' => $cart_return);

            //   return ($cart_saved_id);
        } else {
            return array('error' => 'Invalid cart items');
        }

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

            return $this->confirm_email_send($ord_test['id'], $to = $email_from, true, true);
        }

    }

    function empty_cart() {
        $sid = mw()->user_manager->session_id();
        $cart_table = $this->tables['cart'];


        \Cart::where('order_completed', 0)->where('session_id', $sid)->delete();


        $this->no_cache = true;


        $this->app->cache_manager->delete('cart');

        $this->app->cache_manager->delete('cart_orders/global');

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

    /**
     * update_order
     *
     * updates order by parameters
     *
     * @package        modules
     * @subpackage     shop
     * @subpackage     shop\orders
     * @category       shop module api
     */
    public function update_order($params = false) {

        $params2 = array();
        if ($params==false){
            $params = array();
        }
        if (is_string($params)){
            $params - parse_params($params);
        }

        if (isset($params['is_paid'])){
            if ($params['is_paid']=='y'){
                $params['is_paid'] = 1;
            } elseif ($params['is_paid']=='n') {
                $params['is_paid'] = 0;
            }
        }

        $table = $this->tables['cart_orders'];
        $params['table'] = $table;
        $this->app->cache_manager->delete('cart_orders');

        return $this->app->database_manager->save($table, $params);
    }

    public function delete_client($data) {
        $adm = $this->app->user_manager->is_admin();
        if ($adm==false){
            $this->app->error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->tables['cart_orders'];
        if (isset($data['email'])){
            $c_id = $this->app->database_manager->escape_string($data['email']);
            $q = "DELETE FROM $table WHERE email='$c_id' ";
            $res = $this->app->database_manager->q($q);
            $this->app->cache_manager->delete('cart_orders/global');

            return $res;
        }
    }

    public function delete_order($data) {

        $adm = $this->app->user_manager->is_admin();

        if (defined('MW_API_CALL') and $adm==false){
            return $this->app->error('Not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->tables['cart_orders'];
        if (!is_array($data)){
            $data = array('id' => intval($data));
        }
        if (isset($data['is_cart']) and trim($data['is_cart'])!='false' and isset($data['id'])){
            $c_id = $this->app->database_manager->escape_string($data['id']);
            $table2 = $this->tables['cart'];

            $c_id = $this->app->database_manager->delete_by_id($table2, $c_id, 'session_id');


            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders');

            return $c_id;
        } else if (isset($data['id'])){
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id($table, $c_id);
            $table2 = $this->tables['cart'];

            $c_id = $this->app->database_manager->delete_by_id($table2, $data['id'], 'order_id');

            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders');

            return $c_id;
        }


    }


    public function currency_get_for_paypal() {
        $curencies = array('USD', 'EUR', 'GBP', 'AUD', 'CAD', 'JPY', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'MYR', 'BRL', 'PHP', 'TWD', 'THB', 'TRY');

        return $curencies;
    }

    public function currency_convert_rate($from, $to) {

        $function_cache_id = __FUNCTION__ . md5($from . $to);
        $cache_group = 'shop';
        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
        if (($cache_content)!=false){
            return $cache_content;
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
                        return $value[ $key ];
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

    public function create_mw_shop_default_options() {

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
    }

}