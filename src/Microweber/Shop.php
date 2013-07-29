<?php
namespace Microweber;
event_bind('mw_db_init_default', mw('Microweber\Shop')->db_init());

/**
 *
 * Shop module api
 *
 * @package        modules
 * @subpackage        shop
 * @since        Version 0.1
 */

// ------------------------------------------------------------------------


class Shop
{

    function __construct()
    {
        if (!defined("MODULE_DB_SHOP")) {
            define('MODULE_DB_SHOP', MW_TABLE_PREFIX . 'cart');
        }

        if (!defined("MODULE_DB_SHOP_ORDERS")) {
            define('MODULE_DB_SHOP_ORDERS', MW_TABLE_PREFIX . 'cart_orders');
        }

        if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
            define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
        }
        if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
            define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
        }
    }

    public function get_cart($params)
    {

        $params2 = array();
        if (!isset($_SESSION)) {
            return false;
        }

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $table = MODULE_DB_SHOP;
        $params['table'] = $table;

        if (!defined('MW_ORDERS_SKIP_SID')) {

            if (is_admin() == false) {
                $params['session_id'] = session_id();
            } else {
                if (isset($params['session_id']) and is_admin() == true) {

                } else {
                    $params['session_id'] = session_id();

                }
            }

            if (isset($params['no_session_id']) and is_admin() == true) {
                unset($params['session_id']);
                //	$params['session_id'] = session_id();
            } else {

            }
        }

        $get = mw('db')->get($params);
        //return $get;

        $return = array();
        if (is_array($get)) {
            foreach ($get as $item) {
                if (isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
                    $item['custom_fields_data'] = mw('format')->base64_to_array($item['custom_fields_data']);

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
                $return[] = $item;
            }

        }
        if (empty($return)) {
            $return = false;
        }

        return $return;
        //  d($params);

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
        if (is_admin() == false) {
            $params['session_id'] = session_id();
            if (!isset($params['payment_verify_token'])) {
            }
        }

        $table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;


        return mw('db')->get($params);

    }

    public function checkout_confirm_email_send($order_id, $to = false, $no_cache = false)
    {

        $ord_data = get_orders('one=1&id=' . $order_id);
        if (is_array($ord_data)) {

            $order_email_enabled = mw('option')->get('order_email_enabled', 'orders');

            if ($order_email_enabled == true) {
                $order_email_subject = mw('option')->get('order_email_subject', 'orders');
                $order_email_content = mw('option')->get('order_email_content', 'orders');
                $order_email_cc = mw('option')->get('order_email_cc', 'orders');

                if ($order_email_subject == false or trim($order_email_subject) == '') {
                    $order_email_subject = "Thank you for your order!";
                }

                if ($to == false) {

                    $to = $ord_data['email'];
                }
                if ($order_email_content != false and trim($order_email_subject) != '') {

                    if (!empty($ord_data)) {
                        $cart_items = get_cart('fields=title,qty,price,custom_fields_data&order_id=' . $ord_data['id'] . '&session_id=' . session_id());
                        $order_items_html = mw('format')->array_to_ul($cart_items);

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
                        // schedule a global scope function:
                        $scheduler->registerShutdownEvent("\Microweber\email\Sender::send", $to, $order_email_subject, $order_email_content, true, $no_cache, $cc);

                        //\Microweber\email\Sender::send($to, $order_email_subject, $order_email_content, true, $no_cache, $cc);
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
                //d($q);
                \mw('db')->q($q);
                checkout_confirm_email_send($ord);
                $q = " UPDATE $table_orders SET
			order_completed='y'
			WHERE order_completed='n' AND
			id='{$ord}' AND
			session_id='{$sid}'  ";
                //d($q);
                \mw('db')->q($q);

                checkout_confirm_email_send($ord);

            }

            mw('cache')->delete('cart/global');
            mw('cache')->delete('cart_orders/global');
            if (isset($_GET['return_to'])) {
                $return_to = urldecode($_GET['return_to']);
                mw('url')->redirect($return_to);
            }
        }
        $checkout_errors = array();
        $check_cart = get_cart($cart);
        if (!is_array($check_cart)) {

            if (mw('url')->is_ajax()) {
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
                    $gw_check = payment_options('payment_gw_' . $data['payment_gw']);
                    if (is_array($gw_check[0])) {
                        $gateway = $gw_check[0];
                    } else {
                        //error('No such payment gateway is activated');
                        $checkout_errors['payment_gw'] = 'No such payment gateway is activated';
                    }

                }
            }

            $shiping_country = false;
            $shiping_cost_max = false;
            $shiping_cost = false;
            $shiping_cost_above = false;
            if (isset($_SESSION['shiping_country'])) {
                $shiping_country = $_SESSION['shiping_country'];
            }
            if (isset($_SESSION['shiping_cost_max'])) {
                $shiping_cost_max = $_SESSION['shiping_cost_max'];
            }
            if (isset($_SESSION['shiping_cost'])) {
                $shiping_cost = $_SESSION['shiping_cost'];
            }
            if (isset($_SESSION['shiping_cost_above'])) {
                $shiping_cost_above = $_SESSION['shiping_cost_above'];
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

            $posted_fields = array();
            $place_order = array();
            //$place_order['order_id'] = "ORD-" . date("YmdHis") . '-' . $cart['session_id'];

            $return_url_after = '';
            if (mw('url')->is_ajax()) {
                $place_order['url'] = mw('url')->current(true);
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
            } elseif (isset($_SERVER['HTTP_REFERER'])) {
                $place_order['url'] = $_SERVER['HTTP_REFERER'];
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
            } else {
                $place_order['url'] = mw('url')->current();

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
            $amount = cart_sum();
            if ($amount == 0) {
                $checkout_errors['cart_sum'] = 'Cart sum is 0?';
            }

            if (!empty($checkout_errors)) {

                return array('error' => $checkout_errors);
            }

            $place_order['amount'] = $amount;
            $place_order['currency'] = mw('option')->get('currency', 'payments');

            if (isset($data['shipping_gw'])) {
                $place_order['shipping_service'] = $data['shipping_gw'];
            }

            if (intval($shiping_cost_above) > 0 and intval($shiping_cost_max) > 0) {
                if ($amount > $shiping_cost_above) {
                    $shiping_cost = $shiping_cost_max;
                }
            }

            $place_order['shipping'] = $shiping_cost;

            $items_count = cart_sum(false);
            $place_order['items_count'] = $items_count;

            $cart_checksum = md5($sid . serialize($check_cart));

            $place_order['payment_verify_token'] = $cart_checksum;

            define('FORCE_SAVE', $table_orders);

            $temp_order = \mw('db')->save($table_orders, $place_order);
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

                    $mw_return_url = mw('url')->api_link('checkout') . '?mw_payment_success=1' . $return_url_after;
                    $mw_cancel_url = mw('url')->api_link('checkout') . '?mw_payment_failure=1' . $return_url_after;
                    $mw_ipn_url = mw('url')->api_link('checkout_ipn') . '?payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'];

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

                // \mw('db')->q($q);
                if (!empty($checkout_errors)) {

                    return array('error' => $checkout_errors);
                }

                $ord = \mw('db')->save($table_orders, $place_order);

                $q = " UPDATE $cart_table SET
		order_id='{$ord}'
		WHERE order_completed='n'  AND session_id='{$sid}'  ";

                \mw('db')->q($q);

                if (isset($place_order['order_completed']) and $place_order['order_completed'] == 'y') {
                    $q = " UPDATE $cart_table SET
			order_completed='y', order_id='{$ord}'

			WHERE order_completed='n'  AND session_id='{$sid}' ";

                    \mw('db')->q($q);

                    if (isset($place_order['is_paid']) and $place_order['is_paid'] == 'y') {
                        $q = " UPDATE $table_orders SET
				order_completed='y'
				WHERE order_completed='n' AND
				id='{$ord}' AND session_id='{$sid}' ";
                        \mw('db')->q($q);
                    }

                    mw('cache')->delete('cart/global');
                    mw('cache')->delete('cart_orders/global');
                    after_checkout($ord);
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

        $providers = mw('option')->get('option_group=payments' . $option_key_q);
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

                        $mod_infp = get_modules_from_db('ui=any&one=1&module=' . $title);

                        if (!empty($mod_infp)) {
                            $value = $mod_infp;
                            $value['gw_file'] = $title;
                        } else {
                            $value['name'] = $title;
                        }
                        //
                        $valid[] = $value;

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

        if (is_admin() == false) {
            $cart['session_id'] = session_id();
        }
        $cart['order_completed'] = 'n';

        $cart['one'] = 1;
        $cart['limit'] = 1;
        $checkz = get_cart($cart);

        if ($checkz != false and is_array($checkz)) {
            // d($checkz);
            $table = MODULE_DB_SHOP;
            \mw('db')->delete_by_id($table, $id = $cart['id'], $field_name = 'id');
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

        //if (is_admin() == false) {
        $cart['session_id'] = session_id();
        //}
        $cart['order_completed'] = 'n';

        $cart['one'] = 1;
        $cart['limit'] = 1;
        $checkz = get_cart($cart);

        if ($checkz != false and is_array($checkz)) {
            // d($checkz);
            $cart['qty'] = intval($data['qty']);
            $table = MODULE_DB_SHOP;
            mw_var('FORCE_SAVE', $table);

            $cart_s = \mw('db')->save($table, $cart);
            return ($cart_s);
            //   \mw('db')->delete_by_id($table, $id = $cart['id'], $field_name = 'id');
        } else {

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

        if (!isset($data['for']) or !isset($data['for_id'])) {

            mw_error('Invalid data');
        }

        $data['for'] = mw('db')->assoc_table_name($data['for']);

        $for = $data['for'];
        $for_id = intval($data['for_id']);

        $update_qty = 0;

        if ($for_id == 0) {

            mw_error('Invalid data');
        }

        if ($data['for'] == 'content') {
            $cont = mw('content')->get_by_id($for_id);

            if ($cont == false) {
                mw_error('Invalid product?');
            } else {
                if (is_array($cont) and isset($cont['title'])) {
                    $data['title'] = $cont['title'];
                }
            }
        }

        if (isset($data['qty'])) {
            $update_qty = intval($data['qty']);
            unset($data['qty']);
        }

        $cfs = array();
        $cfs = mw('fields')->get($for, $for_id, 1);
        if ($cfs == false) {

            mw_error('Invalid data');
        }

        $add = array();
        $prices = array();
        $found_price = false;
        $skip_keys = array();
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
                            /*
                             if ($item== 'ala' and is_array($item)) {

                             if (is_array($cf['custom_field_values'])) {

                             $vi = 0;
                             foreach ($item as $ik => $item_value) {

                             if (in_array($item_value, $cf['custom_field_values'])) {

                             //	$cf1 = $cf;
                             //$cf1['custom_field_values'] = $item_value;
                             $item[$ik] = $item_value;

                             } else {
                             unset($item[$ik]);
                             }

                             $vi++;
                             }
                             }
                             // d($item);
                             } else {*/

                            //if($cf['custom_field_type'] != 'price'){
                            if (is_array($cf['custom_field_values'])) {
                                if (in_array($item, $cf['custom_field_values'])) {
                                    $found = true;
                                }

                            }

                            if ($found == false and $cf['custom_field_value'] != $item) {
                                unset($item);
                            }
                            //}
                        } else {
                            //	$skip_keys[] = $k;
                            //break(1);
                            //	unset($item );
                        }

                        //   d($k);
                        //
                        //}
                    } elseif (isset($cf['custom_field_type']) and $cf['custom_field_type'] == 'price') {
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
                            // d($item);
                        } else {
                            // unset($item);
                        }
                    }
                    if ($found_price == false) {
                        $found_price = $prices[0];

                    }

                }

                if (isset($item)) {
                    if ($found == true) {
                        if ($k != 'price' and !in_array($k, $skip_keys)) {
                            $add[$k] = ($item);
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
            $cart['custom_fields_data'] = mw('format')->array_to_base64($add);
            $cart['order_completed'] = 'n';
            $cart['session_id'] = session_id();
            //$cart['one'] = 1;
            $cart['limit'] = 1;
            //  $cart['no_cache'] = 1;
            $checkz = get_cart($cart);
            // d($checkz);
            if ($checkz != false and is_array($checkz) and isset($checkz[0])) {
                //    d($check);
                $cart['id'] = $checkz[0]['id'];
                if ($update_qty > 0) {
                    $cart['qty'] = $checkz[0]['qty'] + $update_qty;
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
            //
            mw_var('FORCE_SAVE', $table);

            $cart_s = \mw('db')->save($table, $cart);
            return ($cart_s);
        } else {
            mw_error('Invalid cart items');
        }

        //  d($data);
        exit;
    }


    static function checkout_confirm_email_test($params)
    {

        if (!isset($params['to'])) {
            $email_from = mw('option')->get('email_from', 'email');
            if ($email_from == false) {
                return array('error' => 'You must set up your email');
            }
        } else {
            $email_from = $params['to'];

        }
        $ord_data = get_orders('order_completed=y&limit=50');
        if (is_array($ord_data[0])) {
            shuffle($ord_data);
            $ord_test = $ord_data[0];
            checkout_confirm_email_send($ord_test['id'], $to = $email_from, true);
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

        $hostname = get_domain_from_str($_SERVER['REMOTE_ADDR']);
        $cache_gr = 'ipn';
        $cache_id = $hostname . md5(serialize($data));

        mw('cache')->save($data, $cache_id, $cache_gr);

        //$data = mw('cache')->get($cache_id, $cache_gr);

        //d($payment_verify_token);
        $ord_data = get_orders('no_cache=1&limit=1&tansaction_id=[is]NULL&payment_verify_token=' . $payment_verify_token . '');
        // d($ord_data);.
        $payment_verify_token = mw('db')->escape_string($payment_verify_token);
        $table = MODULE_DB_SHOP_ORDERS;
        $q = " SELECT  * FROM $table WHERE payment_verify_token='{$payment_verify_token}'  AND transaction_id IS NULL  LIMIT 1";

        $ord_data = \mw('db')->query($q);

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
        $gw_process = MW_MODULES_DIR . $data['payment_gw'] . '_checkout_ipn.php';
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
            //$update_order['debug'] = 1;
            //d($update_order);
            //d($data);
            $ord = \mw('db')->save($table_orders, $update_order);
            checkout_confirm_email_send($ord);
            if ($ord > 0) {

                $q = " UPDATE $cart_table SET
			order_completed='y', order_id='{$ord}'
			WHERE order_completed='n'   ";
                //d($q);
                \mw('db')->q($q);

                $q = " UPDATE $table_orders SET
			order_completed='y'
			WHERE order_completed='n' AND
			id='{$ord}'  ";
                //	 d($q);
                \mw('db')->q($q);
                mw('cache')->delete('cart/global');
                mw('cache')->delete('cart_orders/global');
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
        $sumq = \mw('db')->query($sumq);
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
        if (is_admin() == false) {

            mw_mw_error("You must be admin");
        }

        $table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;

        //  d($params);
        return \mw('db')->save($table, $params);

    }

    public function delete_client($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MODULE_DB_SHOP_ORDERS;

        if (isset($data['email'])) {
            $c_id = mw('db')->escape_string($data['email']);
            $q = "DELETE FROM $table WHERE email='$c_id' ";
            $res = \mw('db')->q($q);
            //\mw('db')->delete_by_id($table, $c_id, 'email');
            mw('cache')->delete('cart_orders/global');
            return $res;
            //d($c_id);
        }
    }


    public function delete_order($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MODULE_DB_SHOP_ORDERS;

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            \mw('db')->delete_by_id($table, $c_id);
            $table2 = MODULE_DB_SHOP;
            $q = "DELETE FROM $table2 WHERE order_id=$c_id ";
            $res = \mw('db')->q($q);
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

        $cache_content = mw('cache')->get($function_cache_id, 'db');

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

        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        // \mw('Microweber\DbUtils')->add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id'));

        \mw('Microweber\DbUtils')->add_table_index('session_id', $table_name, array('session_id'));

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

        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        // \mw('Microweber\DbUtils')->add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id'));

        \mw('Microweber\DbUtils')->add_table_index('session_id', $table_name, array('session_id'));


        $table_name = MODULE_DB_SHOP_SHIPPING_TO_COUNTRY;

        $fields_to_add = array();
        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('is_active', "char(1) default 'y'");

        $fields_to_add[] = array('shiping_cost', 'float default NULL');
        $fields_to_add[] = array('shiping_cost_max', 'float default NULL');
        $fields_to_add[] = array('shiping_cost_above', 'float default NULL');

        $fields_to_add[] = array('shiping_country', 'TEXT default NULL');
        $fields_to_add[] = array('position', 'int(11) default NULL');


        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);


        mw('cache')->save(true, $function_cache_id, $cache_group = 'db');

        return true;

        //print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
    }


    public function create_mw_shop_default_options()
    {

        $function_cache_id = __FUNCTION__;

        $cache_content = mw('cache')->get($function_cache_id, $cache_group = 'db');
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

            $ch = mw('option')->set_default($val);
            if ($ch == true) {

                $changes = true;
            }
        }
        if ($changes == true) {

            mw('cache')->delete('options/global');
        }
        mw('cache')->save('--true--', $function_cache_id, $cache_group = 'db');

        return true;
    }


    public function currency_symbol($curr = false, $key = 3)
    {


        if ($curr == false) {
            $curr = mw('option')->get('currency', 'payments');
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


        $remote_host = 'http://api.microweber.net';
        $service = "/service/currency/?from=" . $from . "&to=" . $to;
        $remote_host_s = $remote_host . $service;
        // d($remote_host_s);
        $get_remote = mw('url')->download($remote_host_s);
        if ($get_remote != false) {
            return floatval($get_remote);
        }

    }


    function currency_format($amount, $curr = false)
    {

        if ($curr == false) {

            $curr = mw('option')->get('currency', 'payments');
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


}