<?php

namespace Microweber\Providers\Shop;

class CheckoutManager
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'cart';
    public $tables = array();

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }

        $tables['cart'] = 'cart';

        $tables['cart_orders'] = 'cart_orders';

        $tables['cart_shipping'] = 'cart_shipping';

        $tables['cart_taxes'] = 'cart_taxes';

        $this->tables = $tables;
    }

    public function checkout($data)
    {
        $exec_return = false;
        $sid = $this->app->user_manager->session_id();
        $sess_order_id = $this->app->user_manager->session_get('order_id');
        $cart = array();
        $cart_table = $this->tables['cart'];
        $table_orders = $this->tables['cart_orders'];
        $cart['session_id'] = $sid;
        $cart['order_completed'] = 0;
        $cart['limit'] = 1;
        $mw_process_payment = true;
        $mw_process_payment_success = false;
        $mw_process_payment_failed = false;

        if (isset($_REQUEST['mw_payment_success']) or isset($_REQUEST['mw_payment_failure'])) {
            $update_order = $update_order_orig = $this->app->order_manager->get_by_id($sess_order_id);
            if (isset($update_order['payment_gw'])) {
                $gw_return = normalize_path(modules_path() . $update_order['payment_gw'] . DS . 'return.php', false);
                if (is_file($gw_return)) {
                    include $gw_return;
                    if ($update_order != $update_order_orig) {
                        $this->app->order_manager->save($update_order);
                    }
                }
            }

            if (isset($_REQUEST['mw_payment_success'])) {
                $mw_process_payment = false;
                $mw_process_payment_success = true;
                $exec_return = true;
            } elseif (isset($_REQUEST['mw_payment_failure'])) {
                $mw_process_payment_failed = true;
                $exec_return = true;
            }
        }

        $cart_table_real = $this->app->database_manager->real_table_name($cart_table);
        $order_table_real = $this->app->database_manager->real_table_name($table_orders);

        if ($exec_return == true) {
            $return_url = $this->app->user_manager->session_get('checkout_return_to_url');
            if (isset($_REQUEST['return_to']) and $_REQUEST['return_to'] != false) {
                $return_url = urldecode($_REQUEST['return_to']);
            }
            if ($return_url) {
                $return_to = $return_url;
                $append = '?';
                if (strstr($return_to, '?')) {
                    $append = '&';
                }
                if ($mw_process_payment_success == true) {
                    $return_to = $return_to . $append . 'mw_payment_success=1';
                } elseif ($mw_process_payment_failed == true) {
                    $return_to = $return_to . $append . 'mw_payment_failure=1';
                }

                return $this->app->url_manager->redirect($return_to);
            }
        }

        $additional_fields = false;
        if (isset($data['for']) and isset($data['for_id'])) {
            $additional_fields = $this->app->fields_manager->get($data['for'], $data['for_id'], 1);
        }

        $seach_address_keys = array('country', 'city', 'address', 'state', 'zip');
        $addr_found_from_search_in_post = false;

        if (isset($data) and is_array($data)) {
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
        }
        $save_custom_fields_for_order = array();
        if (is_array($additional_fields) and !empty($additional_fields)) {
            foreach ($additional_fields as $cf) {
                if (isset($data) and is_array($data)) {
                    foreach ($data as $k => $item) {
                        $key1 = str_replace('_', ' ', $cf['name']);
                        $key2 = str_replace('_', ' ', $k);
                        if ($key1 == $key2) {
                            $save_custom_fields_for_order[$key1] = $this->app->format->clean_html($item);
                        }
                    }
                }
            }
        }

        $checkout_errors = array();
        $check_cart = $this->app->shop_manager->get_cart($cart);

        if (!is_array($check_cart)) {
            $checkout_errors['cart_empty'] = 'Your cart is empty';
        } else {
            if (!isset($data['payment_gw']) and $mw_process_payment == true) {
                $data['payment_gw'] = 'none';
            } else {
                if ($mw_process_payment == true) {
                    $gw_check = $this->payment_options('payment_gw_' . $data['payment_gw']);
                    if (is_array($gw_check[0])) {
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

            if (($this->app->user_manager->session_get('shipping_country'))) {
                $shipping_country = $this->app->user_manager->session_get('shipping_country');
            }
            if (($this->app->user_manager->session_get('shipping_cost_max'))) {
                $shipping_cost_max = $this->app->user_manager->session_get('shipping_cost_max');
            }
            if (($this->app->user_manager->session_get('shipping_cost'))) {
                $shipping_cost = $this->app->user_manager->session_get('shipping_cost');
            }
            if (($this->app->user_manager->session_get('shipping_cost_above'))) {
                $shipping_cost_above = $this->app->user_manager->session_get('shipping_cost_above');
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

            $custom_order_id = $this->app->option_manager->get('custom_order_id', 'shop');
            $posted_fields = array();
            $place_order = array();
            $place_order['id'] = false;

            $return_url_after = '';
            $return_to_ref = false;
            if ($this->app->url_manager->is_ajax()) {
                $place_order['url'] = $this->app->url_manager->current(true);
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
                $this->app->user_manager->session_set('checkout_return_to_url', $_SERVER['HTTP_REFERER']);
            } elseif (isset($_SERVER['HTTP_REFERER'])) {
                $place_order['url'] = $_SERVER['HTTP_REFERER'];
                $return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
                $this->app->user_manager->session_set('checkout_return_to_url', $_SERVER['HTTP_REFERER']);
            } else {
                $place_order['url'] = $this->app->url_manager->current();
            }

            $place_order['session_id'] = $sid;
            $place_order['order_completed'] = 0;
            $items_count = 0;

            foreach ($flds_from_data as $value) {
                if (isset($data[$value]) and ($data[$value]) != false) {
                    $place_order[$value] = $data[$value];
                    $posted_fields[$value] = $data[$value];
                }
            }

            $amount = $this->app->shop_manager->cart_total();
            $tax = $this->app->cart_manager->get_tax();

            if (!empty($checkout_errors)) {
                return array('error' => $checkout_errors);
            }

            $place_order['amount'] = $amount;
            $place_order['allow_html'] = true;
            $place_order['currency'] = $this->app->option_manager->get('currency', 'payments');

            if (isset($data['shipping_gw'])) {
                $place_order['shipping_service'] = $data['shipping_gw'];
            }
            $place_order['shipping'] = $shipping_cost;
            if ($tax != 0) {
                $place_order['taxes_amount'] = $tax;
            }

            $items_count = $this->app->shop_manager->cart_sum(false);
            $place_order['items_count'] = $items_count;

            $cart_checksum = md5($sid . serialize($check_cart) . uniqid());

            $place_order['payment_verify_token'] = $cart_checksum;

            if (isset($save_custom_fields_for_order) and !empty($save_custom_fields_for_order)) {
                $place_order['custom_fields_data'] = $this->app->format->array_to_base64($save_custom_fields_for_order);
            }

            if (!isset($place_order['shipping']) or $place_order['shipping'] == false) {
                $place_order['shipping'] = 0;
            }

            $temp_order = $this->app->database_manager->save($table_orders, $place_order);
            if ($temp_order != false) {
                $place_order['id'] = $temp_order;
            } else {
                $place_order['id'] = 0;
            }

            if ($custom_order_id != false) {
                foreach ($place_order as $key => $value) {
                    $custom_order_id = str_ireplace('{' . $key . '}', $value, $custom_order_id);
                }

                $custom_order_id = str_ireplace('{YYYYMMDD}', date('Ymd'), $custom_order_id);
                $custom_order_id = str_ireplace('{date}', date('Y-m-d'), $custom_order_id);
            }

            if ($custom_order_id != false) {
                $place_order['item_name'] = 'Order id:' . ' ' . $custom_order_id;
                $place_order['order_id'] = $custom_order_id;
            } else {
                $place_order['item_name'] = 'Order id:' . ' ' . $place_order['id'];
            }

            if ($mw_process_payment == true) {
                $shop_dir = module_dir('shop');
                $shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;

                if ($data['payment_gw'] != 'none') {
                    $gw_process = modules_path() . $data['payment_gw'] . '_process.php';
                    if (!is_file($gw_process)) {
                        $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'process.php', false);
                    }

                    $mw_return_url = $this->app->url_manager->api_link('checkout') . '?mw_payment_success=1&order_id=' . $place_order['id'] . '&payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . '&order_id=' . $place_order['id'] . $return_url_after;
                    $mw_cancel_url = $this->app->url_manager->api_link('checkout') . '?mw_payment_failure=1&order_id=' . $place_order['id'] . '&payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . '&order_id=' . $place_order['id'] . $return_url_after;
                    $mw_ipn_url = $this->app->url_manager->api_link('checkout_ipn') . '?payment_gw=' . $data['payment_gw'] . '&order_id=' . $place_order['id'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . $return_url_after;

                    if (is_file($gw_process)) {
                        require_once $gw_process;
                    } else {
                        $checkout_errors['payment_gw'] = 'Payment gateway\'s process file not found.';
                    }
                } else {
                    $place_order['order_completed'] = 1;
                    $place_order['is_paid'] = 0;
                    $place_order['success'] = 'Your order has been placed successfully!';
                }
                $place_order['order_status'] = 'pending';
                if (!empty($checkout_errors)) {
                    return array('error' => $checkout_errors);
                }

                if (isset($place_order['error'])) {
                    return array('error' => $place_order['error']);
                }

                $ord = $this->app->shop_manager->place_order($place_order);
                $place_order['id'] = $ord;
            }

            if (isset($place_order) and !empty($place_order)) {
                if (!isset($place_order['success'])) {
                    $place_order['success'] = 'Your order has been placed successfully!';
                }
                $return = $place_order;
                if (isset($place_order['redirect'])) {
                    $return['redirect'] = $place_order['redirect'];
                }

                return $return;
            }
        }

        if (!empty($checkout_errors)) {
            return array('error' => $checkout_errors);
        }
    }

    public function payment_options($option_key = false)
    {
        $option_key_q = '';
        if (is_string($option_key)) {
            $option_key_q = "&limit=1&option_key={$option_key}";
        }
        $providers = $this->app->option_manager->get_all('option_group=payments' . $option_key_q);

        $payment_modules = get_modules('type=payment_gateway');
        $str = 'payment_gw_';
        $l = strlen($str);
        $enabled_providers = array();
        if (!empty($payment_modules) and !empty($providers)) {
            foreach ($payment_modules as $payment_module) {
                foreach ($providers as $value) {
                    if ($value['option_value'] == 1) {
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
                if ($value['option_value'] == 1) {
                    if (substr($value['option_key'], 0, $l) == $str) {
                        $title = substr($value['option_key'], $l);
                        $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
                        $value['gw_file'] = $title;
                        $mod_infp = $this->app->modules->get('ui=any&one=1&module=' . $title);

                        if (!empty($mod_infp)) {
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

    public function after_checkout($order_id, $suppress_output = true)
    {
        if ($suppress_output == true) {
            ob_start();
        }
        if ($order_id == false or trim($order_id) == '') {
            return array('error' => 'Invalid order ID');
        }

        $ord_data = $this->app->shop_manager->get_orders('one=1&id=' . $order_id);
        if (is_array($ord_data)) {
            $ord = $order_id;
            $notification = array();
            $notification['module'] = 'shop';
            $notification['rel_type'] = 'cart_orders';
            $notification['rel_id'] = $ord;
            $notification['title'] = 'You have new order';
            $notification['description'] = 'New order is placed from ' . $this->app->url_manager->current(1);
            $notification['content'] = 'New order in the online shop. Order id: ' . $ord;
            $this->app->notifications_manager->save($notification);
            $this->app->log_manager->save($notification);
            $this->confirm_email_send($order_id);
        }
        if ($suppress_output == true) {
            ob_end_clean();
        }
    }

    public function confirm_email_send($order_id, $to = false, $no_cache = false, $skip_enabled_check = false)
    {
        $ord_data = $this->app->shop_manager->get_order_by_id($order_id);

        if (is_array($ord_data)) {
            if ($skip_enabled_check == false) {
                $order_email_enabled = $this->app->option_manager->get('order_email_enabled', 'orders');
            } else {
                $order_email_enabled = $skip_enabled_check;
            }

            if ($order_email_enabled == true) {
                $order_email_subject = $this->app->option_manager->get('order_email_subject', 'orders');
                $order_email_content = $this->app->option_manager->get('order_email_content', 'orders');
                $order_email_cc = $this->app->option_manager->get('order_email_cc', 'orders');
                $order_email_send_when = $this->app->option_manager->get('order_email_send_when', 'orders');
                if ($order_email_send_when == 'order_paid' and !$skip_enabled_check) {
                    if (isset($ord_data['is_paid']) and $ord_data['is_paid'] == false) {
                        return;
                    }
                }

                if ($order_email_subject == false or trim($order_email_subject) == '') {
                    $order_email_subject = 'Thank you for your order!';
                }
                if ($to == false) {
                    $to = $ord_data['email'];
                }
                if ($order_email_content != false and trim($order_email_subject) != '') {
                    $cart_items = array();
                    if (!empty($ord_data)) {
                        $cart_items = $this->app->shop_manager->get_cart('order_id=' . $ord_data['id'] . '&no_session_id=' . $this->app->user_manager->session_id());
                        // $cart_items = $this->order_items($ord_data['id']);
                        $order_items_html = $this->app->format->array_to_ul($cart_items);
                        $order_email_content = str_replace('{cart_items}', $order_items_html, $order_email_content);
                        $order_email_content = str_replace('{date}', date('F jS, Y', strtotime($ord_data['created_at'])), $order_email_content);
                        foreach ($ord_data as $key => $value) {
                            if (!is_array($value) and is_string($key)) {
                                if (strtolower($key) == 'amount') {
                                    $value = number_format($value, 2);
                                }
                                $order_email_content = str_ireplace('{' . $key . '}', $value, $order_email_content);
                            }
                        }
                    }

                    $twig = new \Twig_Environment(new \Twig_Loader_String());
                    $order_email_content = $twig->render(
                        $order_email_content,
                        array('cart' => $cart_items, 'order' => $ord_data)
                    );

                    if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                        $sender = new \Microweber\Utils\MailSender();
                        $sender->send($to, $order_email_subject, $order_email_content);
                        $cc = false;
                        if (isset($order_email_cc) and (filter_var($order_email_cc, FILTER_VALIDATE_EMAIL))) {
                            $cc = $order_email_cc;
                            $sender->send($cc, $order_email_subject, $order_email_content, false, $no_cache);
                        }

                        return true;
                    }
                }
            }
        }
    }

    public function checkout_ipn($data)
    {
        if (isset($data['payment_verify_token'])) {
            $payment_verify_token = ($data['payment_verify_token']);
        }
        if (!isset($data['payment_gw'])) {
            return array('error' => 'You must provide a payment gateway parameter!');
        }

        $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);

        $hostname = $this->get_domain_from_str($_SERVER['REMOTE_ADDR']);

        $payment_verify_token = $this->app->database_manager->escape_string($payment_verify_token);
        $table = $this->tables['cart_orders'];

        $query = array();
        $query['payment_verify_token'] = $payment_verify_token;
        if (isset($data['order_id'])) {
            $query['id'] = intval($data['order_id']);
        } else {
            $query['transaction_id'] = '[null]';
        }
        $query['limit'] = 1;
        $query['table'] = $table;
        $query['no_cache'] = true;

        $ord_data = $this->app->database_manager->get($query);
        if (!isset($ord_data[0]) or !is_array($ord_data[0])) {
            return array('error' => 'Order is completed or expired.');
        } else {
            $ord_data = $ord_data[0];
            $ord = $ord_data['id'];
        }

        $cart_table = $this->tables['cart'];
        $table_orders = $this->tables['cart_orders'];

        $data['payment_gw'] = str_replace('..', '', $data['payment_gw']);
        $gw_process = modules_path() . $data['payment_gw'] . '_checkout_ipn.php';
        if (!is_file($gw_process)) {
            $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'checkout_ipn.php', false);
        }
        if (!is_file($gw_process)) {
            $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'notify.php', false);
        }

        $update_order = array();
        if (is_file($gw_process)) {
            include $gw_process;
        } else {
            return array('error' => 'The payment gateway is not found!');
        }

        if (!empty($update_order) and isset($update_order['order_completed']) and trim($update_order['order_completed']) == 1) {
            $update_order['id'] = $ord;
            $update_order['payment_gw'] = $data['payment_gw'];
            $ord = $this->app->database_manager->save($table_orders, $update_order);
            $this->confirm_email_send($ord);
            if (isset($update_order['is_paid']) and $update_order['is_paid'] == 1) {
                $this->app->shop_manager->update_quantities($ord);
            }
            if ($ord > 0) {
                $this->app->cache_manager->delete('cart/global');
                $this->app->cache_manager->delete('cart_orders/global');
                //return true;
            }
        }

        if (isset($data['return_to'])) {
            $return_to = urldecode($data['return_to']);

            $append = '?';
            if (strstr($return_to, '?')) {
                $append = '&';
            }
            $return_to = $return_to . $append . 'mw_payment_success=1';

            return $this->app->url_manager->redirect($return_to);
        }

        return;
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
}
