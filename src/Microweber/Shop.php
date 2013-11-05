<?php
namespace Microweber;
//event_bind('mw_db_init_default', mw('shop')->db_init());

    /**
     *
     * Shop module api
     *
     * @package        modules
     * @subpackage        shop
     * @since        Version 0.1
     */

// ------------------------------------------------------------------------

api_expose('shop/update_order');

class Shop
{
    public $app;

    function __construct($app = null)
    {


        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw('application');
        }


        if (!defined("MODULE_DB_SHOP")) {
            define('MODULE_DB_SHOP', MW_TABLE_PREFIX . 'cart');
        }

        if (!defined("MODULE_DB_SHOP_ORDERS")) {
            define('MODULE_DB_SHOP_ORDERS', MW_TABLE_PREFIX . 'cart_orders');
        }

        if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
            define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
        }


        $this->db_init();

    }

    public function order_items($order_id = false)
    {
        $order_id = intval($order_id);
        if ($order_id == false) {
            return;
        }
        $params = array();
        $table = MODULE_DB_SHOP;
        $params['table'] = $table;
        $params['order_id'] = $order_id;
        $get = $this->app->db->get($params);
        return $get;
    }

    public function get_cart($params = false)
    {

        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $table = MODULE_DB_SHOP;
        $params['table'] = $table;

        if (!defined('MW_ORDERS_SKIP_SID')) {

            if ($this->app->user->is_admin() == false) {
                $params['session_id'] = session_id();

            } else {
                if (isset($params['session_id']) and $this->app->user->is_admin() == true) {

                } else {
                    $params['session_id'] = session_id();

                }
            }

            if (isset($params['no_session_id']) and $this->app->user->is_admin() == true) {
                unset($params['session_id']);
                //	$params['session_id'] = session_id();
            } else {

            }
        }
        $params['limit'] = 10000;
        if (!isset($params['order_completed'])) {
            if (!isset($params['order_id'])) {
                $params['order_completed'] = 'n';
            }
        } elseif (isset($params['order_completed']) and  $params['order_completed'] == 'any') {
            unset($params['order_completed']);
        }
        // $params['debug'] = session_id();

        $get = $this->app->db->get($params);
        //return $get;

        $return = array();
        if (is_array($get)) {
            foreach ($get as $item) {

                if (isset($item['rel_id']) and isset($item['rel']) and $item['rel'] = 'content') {
                    $item['content_data'] = $this->app->content->data($item['rel_id']);


                }

                if (isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {

                    $item = $this->_render_item_custom_fields_data($item);


                }

                $return[] = $item;

            }

        } else {
            $return = $get;
        }


        return $return;


    }

    public function get_order_by_id($id = false)
    {


        $table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;
        $params['one'] = true;

        $params['id'] = intval($id);

        $item = $this->app->db->get($params);

        if (is_array($item) and isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {

            $item = $this->_render_item_custom_fields_data($item);


        }

        return $item;

    }

    public function get_orders($params = false)
    {

        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if (defined('MW_API_CALL') and $this->app->user->is_admin() == false) {

            if (!isset($params['payment_verify_token'])) {
                $params['session_id'] = session_id();
            }

        }

        $table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;


        return $this->app->db->get($params);

    }

    function update_quantities($order_id = false)
    {
        $order_id = intval($order_id);
        if ($order_id == false) {
            return;
        }
        $res = false;
        $ord_data = $this->get_order_by_id($order_id);

        $cart_data = $this->order_items($order_id);
        if (!empty($cart_data)) {
            $res = array();
            foreach ($cart_data as $item) {


                if (isset($item['rel']) and isset($item['rel_id']) and $item['rel'] == 'content') {

                    $data_fields = $this->app->content->data($item['rel_id'], 1);

                    if (isset($item['qty']) and isset($data_fields['qty']) and $data_fields['qty'] != 'nolimit') {
                        $old_qty = intval($data_fields['qty']);

                        $new_qty = $old_qty - intval($item['qty']);
                        mw_var('FORCE_SAVE_CONTENT_DATA_FIELD', 1);
                        $new_qty = intval($new_qty);


                        if (defined('MW_DB_TABLE_CONTENT_DATA')) {

                            $table_name_data = MW_DB_TABLE_CONTENT_DATA;
                            $notify = false;
                            mw_var('FORCE_ANON_UPDATE', $table_name_data);
                            $new_q = array();
                            $new_q['field_name'] = 'qty';
                            $new_q['content_id'] = $item['rel_id'];
                            if ($new_qty > 0) {

                                $new_q['field_value'] = $new_qty;


                            } else {
                                $notify = true;
                                $new_q['field_value'] = '0';


                            }
                            $res[] = $new_q;
                            $upd_qty = $this->app->content->save_content_data_field($new_q);
                            if ($notify) {
                                $notif = array();
                                //$notif['module'] = "content";
                                $notif['rel'] = 'content';
                                $notif['rel_id'] = $item['rel_id'];
                                $notif['title'] = "Your item is out of stock!";
                                $notif['description'] = "You sold all items you had in stock. Please update your quantity";
                                $notif = $this->app->notifications->save($notif);

                            }


                        }
                    }

                }


            }

        }


        return $res;
    }

    public function confirm_email_send($order_id, $to = false, $no_cache = false, $skip_enabled_check = false)
    {

        $ord_data = $this->get_order_by_id($order_id);
        if (is_array($ord_data)) {
            if ($skip_enabled_check == false) {
                $order_email_enabled = $this->app->option->get('order_email_enabled', 'orders');
            } else {
                $order_email_enabled = $skip_enabled_check;
            }
            if ($order_email_enabled == true) {
                $order_email_subject = $this->app->option->get('order_email_subject', 'orders');
                $order_email_content = $this->app->option->get('order_email_content', 'orders');
                $order_email_cc = $this->app->option->get('order_email_cc', 'orders');

                if ($order_email_subject == false or trim($order_email_subject) == '') {
                    $order_email_subject = "Thank you for your order!";
                }

                if ($to == false) {

                    $to = $ord_data['email'];
                }
                if ($order_email_content != false and trim($order_email_subject) != '') {

                    if (!empty($ord_data)) {
                        $cart_items = $this->get_cart('fields=title,qty,price,custom_fields_data&order_id=' . $ord_data['id'] . '&session_id=' . session_id());
                        $order_items_html = $this->app->format->array_to_ul($cart_items);

                        $order_email_content = str_replace('{cart_items}', $order_items_html, $order_email_content);


                        foreach ($ord_data as $key => $value) {
                            $order_email_content = str_ireplace('{' . $key . '}', $value, $order_email_content);

                        }
                    }
                    if (!defined('MW_ORDERS_SKIP_SID')) {
                        //		define('MW_ORDERS_SKIP_SID', 1);
                    }

                    $cc = false;
                    if (isset($order_email_cc) and (filter_var($order_email_cc, FILTER_VALIDATE_EMAIL))) {
                        $cc = $order_email_cc;

                    }

                    if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {

                        $scheduler = new \Microweber\Utils\Events();
                        $sender = new \Microweber\email\Sender();
                        // schedule a global scope function:
                        // $scheduler->registerShutdownEvent("email\Sender::send", $to, $order_email_subject, $order_email_content, true, $no_cache, $cc);

                        $sender::send($to, $order_email_subject, $order_email_content, true, $no_cache, $cc);
                    }

                }
            }
        }
    }

    public function checkout($data)
    {
        if (!session_id() and !headers_sent()) {
            session_start();
        }


        $exec_return = false;
        $sid = session_id();
        $cart = array();
        $cart_table = MODULE_DB_SHOP;
        $table_orders = MODULE_DB_SHOP_ORDERS;
        $cart['session_id'] = $sid;
        $cart['order_completed'] = 'n';
        $cart['limit'] = 1;
        $mw_process_payment = true;
        if (isset($_GET['mw_payment_success'])) {
            $mw_process_payment = false;
        }
        mw_var("FORCE_SAVE", $table_orders);


        if (isset($_REQUEST['mw_payment_success']) and intval($_REQUEST['mw_payment_success']) == 1 and isset($_SESSION['order_id'])) {

            $_SESSION['mw_payment_success'] = true;
            $ord = $_SESSION['order_id'];
            if ($ord > 0) {
                $q = " UPDATE $cart_table SET
			order_completed='y', order_id='{$ord}'
			WHERE order_completed='n'   AND session_id='{$sid}'  ";
                $this->app->db->q($q);

                /*if (isset($_REQUEST['token'])) {
                    $tok = $this->app->db->escape_string($_REQUEST['token']);
                    $q = " UPDATE $table_orders SET
			is_paid='y'
			WHERE id='{$ord}'   AND session_id='{$sid}'  AND payment_verify_token='{$tok}'  ";
                    $this->app->db->q($q);

                }*/
                $this->confirm_email_send($ord);
                $q = " UPDATE $table_orders SET
			order_completed='y'
			WHERE order_completed='n' AND
			id='{$ord}' AND
			session_id='{$sid}'  ";
                $this->app->db->q($q);
                $this->confirm_email_send($ord);
            }


            $this->app->cache->delete('cart/global');
            $this->app->cache->delete('cart_orders/global');
            //d($_REQUEST);
            $exec_return = true;
        } else if (isset($_REQUEST['mw_payment_failure']) and intval($_REQUEST['mw_payment_failure']) == 1) {
            $cur_sid = session_id();

            if ($cur_sid != false) {
                $ord_id = $_SESSION['order_id'];
                if (isset($_REQUEST['order_id']) and intval($_REQUEST['order_id']) == 0) {
                    $ord_id = intval($_REQUEST['order_id']);
                }

                $this->recover_shopping_cart($cur_sid, $ord_id);
            }
            $exec_return = true;

        }
        if ($exec_return == true) {
            if (isset($_REQUEST['return_to'])) {
                $return_to = urldecode($_REQUEST['return_to']);
                return $this->app->url->redirect($return_to);

            }
        }

        $additional_fields = false;
        if (isset($data['for']) and isset($data['for_id'])) {
            $additional_fields = $this->app->fields->get($data['for'], $data['for_id'], 1);
        }

        $seach_address_keys = array('country', 'city', 'address', 'state', 'zip');
        $addr_found_from_search_in_post = false;
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                foreach ($seach_address_keys as $item) {
                    $case1 = ucfirst($item);
                    if (!isset($data[$item]) and (isset($v[$item]) or isset($v[$case1]))) {
                        $data[$item] = $v[$item];
                        if ($addr_found_from_search_in_post == false) {
                            unset($data[$k]);
                        }
                        $addr_found_from_search_in_post = 1;

                    }
                }

            }

        }


        $save_custom_fields_for_order = array();
        if (is_array($additional_fields) and !empty($additional_fields)) {
            foreach ($additional_fields as $cf) {
                foreach ($data as $k => $item) {
                    $key1 = str_replace('_', ' ', $cf['custom_field_name']);
                    $key2 = str_replace('_', ' ', $k);
                    if ($key1 == $key2) {
                        $save_custom_fields_for_order[$key1] = $this->app->format->clean_html($item);

                    }

                }
            }
            // d($save_custom_fields_for_order);
        }


        $checkout_errors = array();
        $check_cart = $this->get_cart($cart);
        if (!is_array($check_cart)) {

            if ($this->app->url->is_ajax()) {
                //json_error('Your cart is empty');

            } else { //	error('Your cart is empty');

            }
            $checkout_errors['cart_empty'] = 'Your cart is empty';
        } else {

            if (!isset($data['payment_gw']) and $mw_process_payment == true) {

                $data['payment_gw'] = 'none';
                //error('No payment method is specified');
                //
            } else {
                if ($mw_process_payment == true) {
                    $gw_check = $this->payment_options('payment_gw_' . $data['payment_gw']);
                    if (is_array($gw_check[0])) {
                        $gateway = $gw_check[0];
                    } else {
                        //error('No such payment gateway is activated');
                        $checkout_errors['payment_gw'] = 'No such payment gateway is activated';
                    }

                }
            }

            $shipping_country = false;
            $shipping_cost_max = false;
            $shipping_cost = false;
            $shipping_cost_above = false;
            if (isset($_SESSION['shipping_country'])) {
                $shipping_country = $_SESSION['shipping_country'];
            }
            if (isset($_SESSION['shipping_cost_max'])) {
                $shipping_cost_max = $_SESSION['shipping_cost_max'];
            }
            if (isset($_SESSION['shipping_cost'])) {
                $shipping_cost = $_SESSION['shipping_cost'];
            }
            if (isset($_SESSION['shipping_cost_above'])) {
                $shipping_cost_above = $_SESSION['shipping_cost_above'];
            }


            //post any of those on the form
            $flds_from_data = array('first_name', 'last_name', 'email', 'country', 'city', 'state', 'zip', 'address', 'address2', 'payment_email', 'payment_name', 'payment_country', 'payment_address', 'payment_city', 'payment_state', 'payment_zip', 'phone', 'promo_code', 'payment_gw');

            if (!isset($data['email']) or $data['email'] == '') {
                $checkout_errors['email'] = 'Email is required';
            }
            if (!isset($data['first_name']) or $data['first_name'] == '') {
                $checkout_errors['first_name'] = 'First name is required';
            }

            if (!isset($data['last_name']) or $data['last_name'] == '') {
                $checkout_errors['last_name'] = 'Last name is required';
            }

            if (isset($data['payment_gw']) and $data['payment_gw'] != '') {
                $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);
            }


            $posted_fields = array();
            $place_order = array();
            //$place_order['order_id'] = "ORD-" . date("YmdHis") . '-' . $cart['session_id'];

            $return_url_after = '';
            if ($this->app->url->is_ajax()) {
                $place_order['url'] = $this->app->url->current(true);
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
            } elseif (isset($_SERVER['HTTP_REFERER'])) {
                $place_order['url'] = $_SERVER['HTTP_REFERER'];
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
            } else {
                $place_order['url'] = $this->app->url->current();

            }

            $place_order['session_id'] = $sid;

            $place_order['order_completed'] = 'n';
            $items_count = 0;

            foreach ($flds_from_data as $value) {
                if (isset($data[$value]) and ($data[$value]) != false) {
                    $place_order[$value] = $data[$value];
                    $posted_fields[$value] = $data[$value];

                }
            }


            //d($place_order);

            $amount = $this->cart_sum();
            if ($amount == 0) {
                $checkout_errors['cart_sum'] = 'Cart sum is 0?';
            }

            if (!empty($checkout_errors)) {

                return array('error' => $checkout_errors);
            }

            $place_order['amount'] = $amount;
            $place_order['currency'] = $this->app->option->get('currency', 'payments');

            if (isset($data['shipping_gw'])) {
                $place_order['shipping_service'] = $data['shipping_gw'];
            }


//            if (intval($shipping_cost_above) > 0 and intval($shipping_cost_max) > 0) {
//                if ($amount > $shipping_cost_above) {
//                    $shipping_cost = $shipping_cost_max;
//                }
//            }

            $place_order['shipping'] = $shipping_cost;

            $items_count = $this->cart_sum(false);
            $place_order['items_count'] = $items_count;

            $cart_checksum = md5($sid . serialize($check_cart) . uniqid());

            $place_order['payment_verify_token'] = $cart_checksum;

            define('FORCE_SAVE', $table_orders);

            if (isset($save_custom_fields_for_order) and !empty($save_custom_fields_for_order)) {
                $place_order['custom_fields_data'] = $this->app->format->array_to_base64($save_custom_fields_for_order);
            }


            $temp_order = $this->app->db->save($table_orders, $place_order);
            if ($temp_order != false) {
                $place_order['id'] = $temp_order;
            } else {
                $place_order['id'] = 0;
            }

            $place_order['item_name'] = 'Order id:' . ' ' . $place_order['id'];

            if ($mw_process_payment == true) {
                $shop_dir = module_dir('shop');
                $shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;

                if ($data['payment_gw'] != 'none') {

                    $gw_process = MW_MODULES_DIR . $data['payment_gw'] . '_process.php';
                    if (!is_file($gw_process)) {
                        $gw_process = normalize_path(MW_MODULES_DIR . $data['payment_gw'] . DS . 'process.php', false);

                    }

                    $mw_return_url = $this->app->url->api_link('checkout') . '?mw_payment_success=1&order_id=' . $place_order['id'] . $return_url_after;
                    $mw_cancel_url = $this->app->url->api_link('checkout') . '?mw_payment_failure=1&order_id=' . $place_order['id'] . $return_url_after;
                    $mw_ipn_url = $this->app->url->api_link('checkout_ipn') . '?payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'];

                    if (is_file($gw_process)) {
                        require_once $gw_process;
                    } else {
                        //error('Payment gateway\'s process file not found.');
                        $checkout_errors['payment_gw'] = 'Payment gateway\'s process file not found.';

                    }
                } else {
                    $place_order['order_completed'] = 'y';
                    $place_order['is_paid'] = 'n';

                    $place_order['success'] = "Your order has been placed successfully!";

                }
                // $q = " DELETE FROM $table_orders  	where order_completed='n'  and session_id='{$sid}' and is_paid='n' ";

                // $this->app->db->q($q);
                if (!empty($checkout_errors)) {

                    return array('error' => $checkout_errors);
                }


                $ord = $this->app->db->save($table_orders, $place_order);

                $q = " UPDATE $cart_table SET
		order_id='{$ord}'
		WHERE order_completed='n'  AND session_id='{$sid}'  ";

                $this->app->db->q($q);

                if (isset($place_order['order_completed']) and $place_order['order_completed'] == 'y') {
                    $q = " UPDATE $cart_table SET
			order_completed='y', order_id='{$ord}'

			WHERE order_completed='n'  AND session_id='{$sid}' ";

                    $this->app->db->q($q);

                    if (isset($place_order['is_paid']) and $place_order['is_paid'] == 'y') {
                        $q = " UPDATE $table_orders SET
				order_completed='y'
				WHERE order_completed='n' AND
				id='{$ord}' AND session_id='{$sid}' ";
                        $this->app->db->q($q);
                    }

                    $this->app->cache->delete('cart/global');
                    $this->app->cache->delete('cart_orders/global');


                    if (isset($place_order['is_paid']) and $place_order['is_paid'] == 'y') {
                        $this->update_quantities($ord);
                    }


                    $this->after_checkout($ord);
                    //$_SESSION['mw_payment_success'] = true;
                }

                $_SESSION['order_id'] = $ord;
            }

            //	exit();
            if (isset($place_order)) {
                return ($place_order);
            }

        }

        if (!empty($checkout_errors)) {

            return array('error' => $checkout_errors);
        }

        //d($check_cart);
    }


    public function payment_options($option_key = false)
    {

        $option_key_q = '';
        if (is_string($option_key)) {
            $option_key_q = "&limit=1&option_key={$option_key}";

        }
        $providers = $this->app->option->get_all('option_group=payments' . $option_key_q);

        $payment_modules = get_modules('type=payment_gateway');
        $str = 'payment_gw_';
        $l = strlen($str);
        $enabled_providers = array();
        if (!empty($payment_modules)) {
            foreach ($payment_modules as $payment_module) {
                foreach ($providers as $value) {
                    if ($value['option_value'] == 'y') {
                        if (substr($value['option_key'], 0, $l) == $str) {
                            $title = substr($value['option_key'], $l);
                            $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
                            $value['gw_file'] = $title;

                            if (isset($payment_module['module']) and $value['gw_file'] == $payment_module['module']) {
                                $payment_module['gw_file'] = $title;
                                $enabled_providers[] = $payment_module;
                            }
                        }
                    }
                }
            }

        }

        if (!empty($enabled_providers)) {
            return $enabled_providers;
        }


        // the rest is for comaptibily and will be removed in the near future
        $str = 'payment_gw_';
        $l = strlen($str);
        if (is_array($providers)) {
            $valid = array();
            foreach ($providers as $value) {
                if ($value['option_value'] == 'y') {
                    if (substr($value['option_key'], 0, $l) == $str) {
                        $title = substr($value['option_key'], $l);
                        $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
                        $value['gw_file'] = $title;

                        $mod_infp = $this->app->module->get('ui=any&one=1&module=' . $title);

                        if (!empty($mod_infp)) {
                            $value = $mod_infp;
                            $value['gw_file'] = $title;
                            $valid[] = $value;
                        } else {
                            // $value['name'] = $title;
                        }
                        //


                    }
                }
            }
            return $valid;
        }


    }

    public function remove_cart_item($data)
    {

        if (!isset($data['id'])) {
            mw_error('Invalid data');
        }
        if (!session_id() and !headers_sent()) {
            session_start();
        }
        $cart = array();
        $cart['id'] = intval($data['id']);

        if ($this->app->user->is_admin() == false) {
            $cart['session_id'] = session_id();
        }
        $cart['order_completed'] = 'n';

        $cart['one'] = 1;
        $cart['limit'] = 1;
        $checkz = $this->get_cart($cart);

        if ($checkz != false and is_array($checkz)) {
            // d($checkz);
            $table = MODULE_DB_SHOP;
            $this->app->db->delete_by_id($table, $id = $cart['id'], $field_name = 'id');
        } else {

        }
    }

    public function update_cart_item_qty($data)
    {

        if (!isset($data['id'])) {
            mw_error('Invalid data');
        }

        if (!isset($data['qty'])) {
            mw_error('Invalid data');
        }
        if (!session_id() and !headers_sent()) {
            session_start();
        }
        $cart = array();
        $cart['id'] = intval($data['id']);

        //if ($this->app->user->is_admin() == false) {
        $cart['session_id'] = session_id();
        //}
        $cart['order_completed'] = 'n';

        $cart['one'] = 1;
        $cart['limit'] = 1;
        $checkz = $this->get_cart($cart);

        if ($checkz != false and is_array($checkz)) {
            // d($checkz);
            $cart['qty'] = intval($data['qty']);


            $cart_s = $this->update_cart($cart);
            return ($cart_s);
            $table = MODULE_DB_SHOP;
            mw_var('FORCE_SAVE', $table);

            $cart_s = $this->app->db->save($table, $cart);
            return ($cart_s);
            //   $this->app->db->delete_by_id($table, $id = $cart['id'], $field_name = 'id');
        } else {

        }
    }

    public function recover_shopping_cart($sid = false, $ord_id = false)
    {
        if ($sid == false) {
            return;
        }

        if (!session_id() and !headers_sent()) {
            session_start();
        }
        $cur_sid = session_id();

        if ($cur_sid == false) {
            return;
        } else {
            if ($cur_sid != false) {
                // $c_id = $this->app->db->sanitize($sid);

                //$c_id = $this->app->db->escape_string($c_id);
                $c_id = $sid;

                $table = MODULE_DB_SHOP;


                $params = array();
                $params['order_completed'] = 'n';
                $params['session_id'] = $c_id;
                $params['table'] = $table;
                if ($ord_id != false) {
                    unset($params['order_completed']);
                    $params['order_id'] = intval($ord_id);
                    // $params['debug'] = intval($ord_id);

                }

                $will_add = true;
                $res = $this->app->db->get($params);

                if (empty($res)) {
                    //$params['order_completed'] = 'y';
                    //  $res = $this->app->db->get($params);
                }


                if (!empty($res)) {
                    foreach ($res as $item) {
                        if (isset($item['id'])) {
                            $data = $item;
                            unset($data['id']);
                            if (isset($item['order_id'])) {
                                unset($data['order_id']);
                            }
                            if (isset($item['created_by'])) {
                                unset($data['created_by']);
                            }
                            if (isset($item['updated_on'])) {
                                unset($data['updated_on']);
                            }


                            if (isset($item['rel']) and isset($item['rel_id'])) {
                                $is_ex_params = array();
                                $is_ex_params['order_completed'] = 'n';
                                $is_ex_params['session_id'] = $cur_sid;
                                $is_ex_params['table'] = $table;
                                $is_ex_params['rel'] = $item['rel'];
                                $is_ex_params['rel_id'] = $item['rel_id'];
                                $is_ex_params['count'] = 1;

                                $is_ex = $this->app->db->get($is_ex_params);

                                if ($is_ex != false) {
                                    $will_add = false;
                                }
                            }
                            $data['order_completed'] = 'n';
                            $data['session_id'] = $cur_sid;
                            if ($will_add == true) {
                                $s = $this->app->db->save($table, $data);
                            }

                        }

                    }

                }

                if ($will_add == true) {
                    $this->app->cache->delete('cart');

                    $this->app->cache->delete('cart_orders/global');
                }
            }
        }

    }

    public function update_cart($data)
    {

        if (!session_id() and !headers_sent()) {
            session_start();
        }

        if (isset($data['content_id'])) {
            $data['for'] = 'content';
            $for_id = $data['for_id'] = $data['content_id'];
        }

        if (!isset($data['for'])) {
            $data['for'] = 'content';
        }
        $update_qty = 0;
        $update_qty_new = 0;

        if (isset($data['qty'])) {
            $update_qty_new = intval($data['qty']);
            unset($data['qty']);
        }
        if (!isset($data['for']) or !isset($data['for_id'])) {
            if (!isset($data['id'])) {
                mw_error('Invalid data');
            } else {
                $cart = array();
                $cart['id'] = intval($data['id']);


                $cart['limit'] = 1;
                $data_existing = $this->get_cart($cart);
                if (is_array($data_existing) and is_array($data_existing[0])) {
                    $data = $data_existing[0];

                }
            }

        }

        if (!isset($data['for']) and isset($data['rel'])) {
            $data['for'] = $data['rel'];
        }
        if (!isset($data['for_id']) and isset($data['rel_id'])) {
            $data['for_id'] = $data['rel_id'];
        }


        if (!isset($data['for']) and !isset($data['for_id'])) {
            mw_error('Invalid for and for_id params');
        }


        $data['for'] = $this->app->db->assoc_table_name($data['for']);

        $for = $data['for'];
        $for_id = intval($data['for_id']);


        if ($for_id == 0) {

            mw_error('Invalid data');
        }
        $cont_data = false;


        if (!isset($data['for']) and isset($data['id'])) {


        }
        if ($update_qty > 0) {
            $data['qty'] = $update_qty;
        }


        if ($data['for'] == 'content') {
            $cont = $this->app->content->get_by_id($for_id);
            $cont_data = $this->app->content->data($for_id);
            if ($cont == false) {
                mw_error('Invalid product?');
            } else {
                if (is_array($cont) and isset($cont['title'])) {
                    $data['title'] = $cont['title'];
                }
            }


        }


        if (isset($data['title']) and is_string($data['title'])) {
            //  $data['title'] = html_entity_decode($data['title']);
            $data['title'] = strip_tags($data['title']);
           // $data['title'] = str_ireplace('&nbsp;', ' ', $data['title']);

        }


        $cfs = array();
        $cfs = $this->app->fields->get($for, $for_id, 1);
        if ($cfs == false) {

            mw_error('Invalid data');
        }

        $add = array();
        $prices = array();
        $found_price = false;
        $skip_keys = array();


        if (is_array($cfs)) {
            foreach ($cfs as $cf) {

                if (isset($cf['custom_field_type']) and $cf['custom_field_type'] == 'price') {

                    $prices[$cf['custom_field_name']] = $cf['custom_field_value'];
                }
            }
        }


        foreach ($data as $k => $item) {

            if ($k != 'for' and $k != 'for_id' and $k != 'title') {

                $found = false;

                foreach ($cfs as $cf) {

                    if (isset($cf['custom_field_type']) and $cf['custom_field_type'] != 'price') {
                        $key1 = str_replace('_', ' ', $cf['custom_field_name']);
                        $key2 = str_replace('_', ' ', $k);

                        if (isset($cf['custom_field_name']) and ($cf['custom_field_name'] == $k or $key1 == $key2)) {
                            $k = str_replace('_', ' ', $k);
                            $found = true;

                            if (is_array($cf['custom_field_values'])) {
                                if (in_array($item, $cf['custom_field_values'])) {
                                    $found = true;
                                }

                            }

                            if ($found == false and $cf['custom_field_value'] != $item) {
                                unset($item);
                            }

                        }

                    } elseif (isset($cf['custom_field_type']) and $cf['custom_field_type'] == 'price') {
                        if ($cf['custom_field_value'] != '') {

                            $prices[$cf['custom_field_name']] = $cf['custom_field_value'];

                        }
                        //$item[$cf['custom_field_name']] = $cf['custom_field_value'];
                        // unset($item[$k]);
                    } elseif (isset($cf['type']) and $cf['type'] == 'price') {
                        if ($cf['custom_field_value'] != '') {

                            $prices[$cf['custom_field_name']] = $cf['custom_field_value'];

                        }
                        //$item[$cf['custom_field_name']] = $cf['custom_field_value'];
                        // unset($item[$k]);
                    } else {
                        //unset($item);
                    }

                }
                if ($found == false) {
                    $skip_keys[] = $k;
                }

                if (is_array($prices)) {

                    foreach ($prices as $price_key => $price) {

                        if (isset($data['price'])) {

                            if ($price == $data['price']) {
                                $found = true;
                                $found_price = $price;

                            }
                        } else if ($price == $item) {
                            $found = true;
                            if ($found_price == false) {
                                $found_price = $item;
                            }

                        } else {
                            // unset($item);
                        }
                    }
                    if ($found_price == false) {
                        $found_price = array_pop($prices);

                    }

                }

                if (isset($item)) {
                    if ($found == true) {
                        if ($k != 'price' and !in_array($k, $skip_keys)) {
                            // $add[$k] = ($item);
                            $add[$k] = $this->app->format->clean_html($item);
                        }
                    }
                }

            }
            // }
        }
        if ($found_price == false) {
            // $found_price = 0;
            mw_error('Invalid data: Please post a "price" field with <input name="price"> ');
        }

        if (is_array($prices)) {
            ksort($add);
            asort($add);
            $table = MODULE_DB_SHOP;
            $cart = array();
            $cart['rel'] = ($data['for']);
            $cart['rel_id'] = intval($data['for_id']);
            $cart['title'] = ($data['title']);
            $cart['price'] = floatval($found_price);
            //d($add);
            // if (!empty($add)) {
            $cart['custom_fields_data'] = $this->app->format->array_to_base64($add);
            // }
            //d($cart['custom_fields_data']);
            $cart['order_completed'] = 'n';
            $cart['session_id'] = session_id();
            //  $cart['one'] = 1;
            $cart['limit'] = 1;
            //$cart['debug'] = 1;
            //     $cart['no_cache'] = 1;
            $checkz = $this->get_cart($cart);


            if ($checkz != false and is_array($checkz) and isset($checkz[0])) {

                $cart['id'] = $checkz[0]['id'];
                if ($update_qty > 0) {
                    $cart['qty'] = $checkz[0]['qty'] + $update_qty;
                } elseif ($update_qty_new > 0) {
                    $cart['qty'] = $update_qty_new;
                } else {
                    $cart['qty'] = $checkz[0]['qty'] + 1;
                }

                //
            } else {

                if ($update_qty > 0) {
                    $cart['qty'] = $update_qty;
                } else {
                    $cart['qty'] = 1;
                }
            }

            if (isset($cont_data['qty']) and trim($cont_data['qty']) != 'nolimit') {

                if (intval($cont_data['qty']) < intval($cart['qty'])) {
                    $cart['qty'] = $cont_data['qty'];
                }

            }

            mw_var('FORCE_SAVE', $table);

            //   $cart['debug'] = 1;
            $cart_s = $this->app->db->save($table, $cart);
            return ($cart_s);
        } else {
            mw_error('Invalid cart items');
        }


        exit;
    }

    public function after_checkout($order_id, $suppress_output = true)
    {
        if ($suppress_output == true) {
            ob_start();
        }
        if ($order_id == false or trim($order_id) == '') {
            return array('error' => 'Invalid order ID');
        }

        $ord_data = $this->get_orders('one=1&id=' . $order_id);
        if (isarr($ord_data)) {

            $ord = $order_id;
            $notif = array();
            $notif['module'] = "shop";
            $notif['rel'] = 'cart_orders';
            $notif['rel_id'] = $ord;
            $notif['title'] = "You have new order";
            $notif['description'] = "New order is placed from " . $this->app->url->current(1);
            $notif['content'] = "New order in the online shop. Order id: " . $ord;
            $this->app->notifications->save($notif);
            $this->app->log->save($notif);
            $this->confirm_email_send($order_id);

        }
        if ($suppress_output == true) {
            ob_end_clean();
        }
    }


    public function checkout_confirm_email_test($params)
    {

        if (!isset($params['to'])) {
            $email_from = $this->app->option->get('email_from', 'email');
            if ($email_from == false) {
                return array('error' => 'You must set up your email');
            }
        } else {
            $email_from = $params['to'];

        }
        $ord_data = $this->get_orders('order_completed=y&limit=50');
        if (is_array($ord_data[0])) {
            shuffle($ord_data);
            $ord_test = $ord_data[0];

            $this->confirm_email_send($ord_test['id'], $to = $email_from, true, true);
        }

    }


    public function checkout_ipn($data)
    {
        if (!session_id() and !headers_sent()) {
            //	session_start();
        }
        //$sid = session_id();

        // if (isset($_GET) and !empty($_GET) and !empty($_POST)) {
        // $data = $_REQUEST;
        // } else

        //if (isset($_POST) and !empty($_POST)) {
        //$data = $_POST;
        //}
        if (isset($data['payment_verify_token'])) {

            $payment_verify_token = ($data['payment_verify_token']);
        }

        if (!isset($data['payment_gw'])) {
            return array('error' => 'You must provide a payment gateway parameter!');
        }

        $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);

        $hostname = $this->get_domain_from_str($_SERVER['REMOTE_ADDR']);
        $cache_gr = 'ipn';
        $cache_id = $hostname . md5(serialize($data));

        $this->app->cache->save($data, $cache_id, $cache_gr);

        //$data = $this->app->cache->get($cache_id, $cache_gr);

        //$ord_data = $this->get_orders('no_cache=1&limit=1&tansaction_id=[is]NULL&payment_verify_token=' . $payment_verify_token . '');
        //cache_save($ord_data,__FUNCTION__,'debug');

        // d($ord_data);.
        $payment_verify_token = $this->app->db->escape_string($payment_verify_token);
        $table = MODULE_DB_SHOP_ORDERS;
        $q = " SELECT  * FROM $table WHERE payment_verify_token='{$payment_verify_token}'  AND transaction_id IS NULL  LIMIT 1";

        $ord_data = $this->app->db->query($q);

        if (!isset($ord_data[0]) or !is_array($ord_data[0])) {
            return array('error' => 'Order is completed or expired.');
        } else {

            $ord_data = $ord_data[0];
            $ord = $ord_data['id'];
        }

        $cart_table = MODULE_DB_SHOP;
        $table_orders = MODULE_DB_SHOP_ORDERS;

        //$shop_dir = module_dir('shop');
        //$shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;

        $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);


        $gw_process = MW_MODULES_DIR . $data['payment_gw'] . '_checkout_ipn.php';


        if (!is_file($gw_process)) {
            $gw_process = normalize_path(MW_MODULES_DIR . $data['payment_gw'] . DS . 'checkout_ipn.php', false);

        }


        $update_order = array();
        //$update_order['id'] = $ord;
        if (is_file($gw_process)) {
            include $gw_process;

        } else {
            return array('error' => 'The payment gateway is not found!');

        }
        if (!empty($update_order) and isset($update_order['order_completed']) and trim($update_order['order_completed']) == 'y') {
            $update_order['id'] = $ord;

            $update_order['payment_gw'] = $data['payment_gw'];
            mw_var('FORCE_SAVE', $table_orders);
            mw_var('FORCE_ANON_UPDATE', $table_orders);


            $ord = $this->app->db->save($table_orders, $update_order);
            $this->confirm_email_send($ord);


            if (isset($update_order['is_paid']) and $update_order['is_paid'] == 'y') {
                $this->update_quantities($ord);
            }
            if ($ord > 0) {

                $q = " UPDATE $cart_table SET
			order_completed='y', order_id='{$ord}'
			WHERE order_completed='n'   ";

                //$this->app->db->q($q);

                $q = " UPDATE $table_orders SET
			order_completed='y'
			WHERE order_completed='n' AND
			id='{$ord}'  ";

                // $this->app->db->q($q);
                $this->app->cache->delete('cart/global');
                $this->app->cache->delete('cart_orders/global');
                return true;
            }
        }
        //
        return false;
    }

    public function cart_sum($return_amount = true)
    {
        if (!session_id() and !headers_sent()) {
            session_start();
        }

        $sid = session_id();
        $diferent_items = 0;
        $amount = floatval(0.00);
        $cart = MODULE_DB_SHOP;
        $sumq = " SELECT  price, qty FROM $cart WHERE order_completed='n'  AND session_id='{$sid}'  ";
        $sumq = $this->app->db->query($sumq);
        if (is_array($sumq)) {
            foreach ($sumq as $value) {
                $diferent_items = $diferent_items + $value['qty'];
                $amount = $amount + (intval($value['qty']) * floatval($value['price']));
            }
        }
        if ($return_amount == false) {
            return $diferent_items;
        }
        return $amount;
    }

    /**
     * update_order
     *
     * updates order by parameters
     *
     * @package        modules
     * @subpackage    shop
     * @subpackage    shop\orders
     * @category    shop module api
     */
    public function update_order($params = false)
    {

        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if ($this->app->user->is_admin() == false) {

            mw_error("You must be admin");
        }

        $table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;

        return $this->app->db->save($table, $params);

    }

    public function delete_client($data)
    {

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MODULE_DB_SHOP_ORDERS;

        if (isset($data['email'])) {
            $c_id = $this->app->db->escape_string($data['email']);
            $q = "DELETE FROM $table WHERE email='$c_id' ";
            $res = $this->app->db->q($q);
            //$this->app->db->delete_by_id($table, $c_id, 'email');
            $this->app->cache->delete('cart_orders/global');
            return $res;
            //d($c_id);
        }
    }


    public function delete_order($data)
    {

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MODULE_DB_SHOP_ORDERS;

        if (isset($data['is_cart']) and  trim($data['is_cart']) != 'false' and isset($data['id'])) {
            $c_id = $this->app->db->escape_string($data['id']);
            //  $this->app->db->delete_by_id($table, $c_id);
            $table2 = MODULE_DB_SHOP;
            $q = "DELETE FROM $table2 WHERE session_id='$c_id' ";
            $this->app->cache->delete('cart');

            $this->app->cache->delete('cart_orders/global');
            $res = $this->app->db->q($q);
            return $c_id;
        } else if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->db->delete_by_id($table, $c_id);
            $table2 = MODULE_DB_SHOP;
            $q = "DELETE FROM $table2 WHERE order_id=$c_id ";
            $res = $this->app->db->q($q);


            $this->app->cache->delete('cart');
            $this->app->cache->delete('cart_orders/global');
            return $c_id;
            //d($c_id);
        }


    }

    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'shop' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = $this->app->cache->get($function_cache_id, 'db');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MODULE_DB_SHOP;

        $fields_to_add = array();
        $fields_to_add[] = array('title', 'TEXT default NULL');
        $fields_to_add[] = array('is_active', "char(1) default 'y'");
        $fields_to_add[] = array('rel_id', 'int(11) default NULL');
        $fields_to_add[] = array('rel', 'varchar(350)  default NULL ');
        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('price', 'float default NULL');
        $fields_to_add[] = array('currency', 'varchar(33)  default NULL ');
        $fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
        $fields_to_add[] = array('qty', 'int(11) default NULL');
        $fields_to_add[] = array('other_info', 'TEXT default NULL');
        $fields_to_add[] = array('order_completed', "char(1) default 'n'");
        $fields_to_add[] = array('order_id', 'varchar(255)  default NULL ');
        $fields_to_add[] = array('skip_promo_code', "char(1) default 'n'");
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('custom_fields_data', 'TEXT default NULL');

        $this->app->db->build_table($table_name, $fields_to_add);

        // $this->app->db->add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
        $this->app->db->add_table_index('rel', $table_name, array('rel'));
        $this->app->db->add_table_index('rel_id', $table_name, array('rel_id'));

        $this->app->db->add_table_index('session_id', $table_name, array('session_id'));

        $table_name = MODULE_DB_SHOP_ORDERS;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('country', 'varchar(255)  default NULL ');
        $fields_to_add[] = array('promo_code', 'TEXT default NULL');
        $fields_to_add[] = array('amount', 'float default NULL');
        $fields_to_add[] = array('transaction_id', 'TEXT default NULL');
        $fields_to_add[] = array('shipping_service', 'TEXT default NULL');
        $fields_to_add[] = array('shipping', 'float default NULL');
        $fields_to_add[] = array('currency', 'varchar(33)  default NULL ');

        $fields_to_add[] = array('currency_code', 'varchar(33)  default NULL ');

        $fields_to_add[] = array('first_name', 'TEXT default NULL');

        $fields_to_add[] = array('last_name', 'TEXT default NULL');

        $fields_to_add[] = array('email', 'TEXT default NULL');

        $fields_to_add[] = array('city', 'TEXT default NULL');

        $fields_to_add[] = array('state', 'TEXT default NULL');

        $fields_to_add[] = array('zip', 'TEXT default NULL');
        $fields_to_add[] = array('address', 'TEXT default NULL');
        $fields_to_add[] = array('address2', 'TEXT default NULL');
        $fields_to_add[] = array('phone', 'TEXT default NULL');

        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
        $fields_to_add[] = array('order_completed', "char(1) default 'n'");
        $fields_to_add[] = array('is_paid', "char(1) default 'n'");
        $fields_to_add[] = array('url', 'TEXT default NULL');
        $fields_to_add[] = array('user_ip', 'varchar(255)  default NULL ');
        $fields_to_add[] = array('items_count', 'int(11) default NULL');
        $fields_to_add[] = array('custom_fields_data', 'TEXT default NULL');

        $fields_to_add[] = array('payment_gw', 'TEXT  default NULL ');
        $fields_to_add[] = array('payment_verify_token', 'TEXT  default NULL ');
        $fields_to_add[] = array('payment_amount', 'float default NULL');
        $fields_to_add[] = array('payment_currency', 'varchar(255)  default NULL ');

        $fields_to_add[] = array('payment_status', 'varchar(255)  default NULL ');

        $fields_to_add[] = array('payment_email', 'TEXT default NULL');
        $fields_to_add[] = array('payment_receiver_email', 'TEXT default NULL');

        $fields_to_add[] = array('payment_name', 'TEXT default NULL');

        $fields_to_add[] = array('payment_country', 'TEXT default NULL');

        $fields_to_add[] = array('payment_address', 'TEXT default NULL');

        $fields_to_add[] = array('payment_city', 'TEXT default NULL');
        $fields_to_add[] = array('payment_state', 'TEXT default NULL');
        $fields_to_add[] = array('payment_zip', 'TEXT default NULL');

        $fields_to_add[] = array('payer_id', 'TEXT default NULL');

        $fields_to_add[] = array('payer_status', 'TEXT default NULL');
        $fields_to_add[] = array('payment_type', 'TEXT default NULL');
        $fields_to_add[] = array('order_status', 'varchar(255) default "pending" ');

        $fields_to_add[] = array('payment_shipping', 'float default NULL');

        $fields_to_add[] = array('is_active', "char(1) default 'y'");
        $fields_to_add[] = array('rel_id', 'int(11) default NULL');
        $fields_to_add[] = array('rel', 'varchar(350)  default NULL ');
        $fields_to_add[] = array('price', 'float default NULL');
        $fields_to_add[] = array('other_info', 'TEXT default NULL');
        $fields_to_add[] = array('order_id', 'varchar(255)  default NULL ');
        $fields_to_add[] = array('skip_promo_code', "char(1) default 'n'");

        $this->app->db->build_table($table_name, $fields_to_add);

        // $this->app->db->add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
        //  $this->app->db->add_table_index('rel', $table_name, array('rel'));
        //  $this->app->db->add_table_index('rel_id', $table_name, array('rel_id'));

        $this->app->db->add_table_index('session_id', $table_name, array('session_id'));


        $table_name = MODULE_DB_SHOP_SHIPPING_TO_COUNTRY;

        $fields_to_add = array();
        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('is_active', "char(1) default 'y'");

        $fields_to_add[] = array('shipping_cost', 'float default NULL');
        $fields_to_add[] = array('shipping_cost_max', 'float default NULL');
        $fields_to_add[] = array('shipping_cost_above', 'float default NULL');

        $fields_to_add[] = array('shipping_country', 'TEXT default NULL');
        $fields_to_add[] = array('position', 'int(11) default NULL');
        $fields_to_add[] = array('shipping_type', 'TEXT default NULL');


        $fields_to_add[] = array('shipping_price_per_size', 'float default NULL');
        $fields_to_add[] = array('shipping_price_per_weight', 'float default NULL');
        $fields_to_add[] = array('shipping_price_per_item', 'float default NULL');
        $fields_to_add[] = array('shipping_price_custom', 'float default NULL');

        $this->app->db->build_table($table_name, $fields_to_add);


        $this->app->cache->save(true, $function_cache_id, $cache_group = 'db');

        return true;

    }


    public function create_mw_shop_default_options()
    {

        $function_cache_id = __FUNCTION__;

        $cache_content = $this->app->cache->get($function_cache_id, $cache_group = 'db');
        if (($cache_content) == '--true--') {
            return true;
        }

        $table = MW_DB_TABLE_OPTIONS;

        mw_var('FORCE_SAVE', $table);
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
        //

        $changes = false;
        foreach ($datas as $val) {

            $ch = $this->app->option->set_default($val);
            if ($ch == true) {

                $changes = true;
            }
        }
        if ($changes == true) {

            $this->app->cache->delete('options/global');
        }
        $this->app->cache->save('--true--', $function_cache_id, $cache_group = 'db');

        return true;
    }


    public function currency_symbol($curr = false, $key = 3)
    {


        if ($curr == false) {
            $curr = $this->app->option->get('currency', 'payments');
        }


        $all_cur = $this->currency_get();
        if (is_array($all_cur)) {
            foreach ($all_cur as $value) {
                if (in_array($curr, $value)) {
                    if ($key == false) {
                        return $value;
                    } else {
                        return $value[$key];
                    }

                }
            }
        }

    }


    public function currency_get_for_paypal()
    {
        $curencies = array('USD', 'EUR', 'GBP', 'AUD', 'CAD', 'JPY', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'MYR', 'BRL', 'PHP', 'TWD', 'THB', 'TRY');

        return $curencies;
    }


    public function currency_get()
    {


        $curencies_list_memory = mw_var('curencies_list');
        if ($curencies_list_memory != false) {
            return $curencies_list_memory;
        }

        $row = 1;

        $cur_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'libs' . DS . 'currencies.csv';
        //d($cur_file);
        if (is_file($cur_file)) {
            if (($handle = fopen($cur_file, "r")) !== FALSE) {
                $res = array();
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $itm = array();

                    $num = count($data);
                    //   echo "<p> $num fields in line $row: <br /></p>\n";
                    $row++;
                    for ($c = 0; $c < $num; $c++) {
                        //echo $data[$c] . "<br />\n";
                        $itm[] = $data[$c];
                    }

                    $res[] = $itm;
                }

                fclose($handle);
                mw_var('curencies_list', $res);
                return $res;
            }
        }
    }


    public function currency_convert_rate($from, $to)
    {


        $remote_host = 'http://api.microweber.com';
        $service = "/service/currency/?from=" . $from . "&to=" . $to;
        $remote_host_s = $remote_host . $service;
        // d($remote_host_s);
        $get_remote = $this->app->url->download($remote_host_s);
        if ($get_remote != false) {
            return floatval($get_remote);
        }

    }


    function currency_format($amount, $curr = false)
    {

        if ($curr == false) {

            $curr = $this->app->option->get('currency', 'payments');
        }


        $amount = floatval($amount);
        $sym = $this->currency_symbol($curr);
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
                //  print $sym;
                $ret = $sym . ' ' . number_format($amount, 2, ".", ",");
                break;
        }
        return $ret;

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
        } else if (strlen($bits[($idz + 2)]) == 0) {
            $url = $bits[($idz)] . '.' . $bits[($idz + 1)];
        } else {
            $url = $bits[($idz + 1)] . '.' . $bits[($idz + 2)];
        }
        return $url;
    }

    private function get_domain_from_str($address)
    {
        //$address = 'clients1.sub3.google.co.uk';
        $address = gethostbyaddr($address);
        $parsed_url = parse_url($address);
        if (!isset($parsed_url['host'])) {
            if (isset($parsed_url['path'])) {
                $parsed_url['host'] = $parsed_url['path'];
            }
        }
        $check = $this->esip($parsed_url['host']);
        $host = $parsed_url['host'];
        if ($check == FALSE) {
            if ($host != "") {
                $host = $this->domain_name($host);
            } else {
                $host = $this->domain_name($address);
            }
        } else {


        }
        return $host;
    }

    private function esip($ip_addr)
    {
        //first of all the format of the ip address is matched
        if (preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr)) {
            //now all the intger values are separated
            $parts = explode(".", $ip_addr);
            //now we need to check each part can range from 0-255
            foreach ($parts as $ip_parts) {
                if (intval($ip_parts) > 255 || intval($ip_parts) < 0)
                    return FALSE;
                //if number is not within range of 0-255
            }
            return TRUE;
        } else
            return FALSE;
        //if format of ip address doesn't matches
    }


    private function _render_item_custom_fields_data($item)
    {
        if (isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
            $item['custom_fields_data'] = $this->app->format->base64_to_array($item['custom_fields_data']);

            $tmp_val = '';
            if (isset($item['custom_fields_data']) and is_array($item['custom_fields_data'])) {
                $tmp_val .= '<ul class="mw-custom-fields-cart-item">';
                foreach ($item['custom_fields_data'] as $cfk => $cfv) {
                    if (is_array($cfv)) {
                        $tmp_val .= '<li><span class="mw-custom-fields-cart-item-key-array-key">' . $cfk . '</span>';
                        $tmp_val .= '<ul class="mw-custom-fields-cart-item-array">';
                        foreach ($cfv as $cfk1 => $cfv1) {
                            $tmp_val .= '<li class="mw-custom-fields-elem"><span class="mw-custom-fields-cart-item-key">' . $cfk1 . ': </span><span class="mw-custom-fields-cart-item-value">' . $cfv1 . '</span></li>';
                        }
                        $tmp_val .= '</ul>';
                        $tmp_val .= '</li>';
                    } else {
                        $tmp_val .= '<li class="mw-custom-fields-elem"><span class="mw-custom-fields-cart-item-key">' . $cfk . ': </span><span class="mw-custom-fields-cart-item-value">' . $cfv . '</span></li>';
                    }
                }
                $tmp_val .= '</ul>';
                $item['custom_fields'] = $tmp_val;
            }
        }
        return $item;
    }


}