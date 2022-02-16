<?php

namespace MicroweberPackages\Checkout;

use Carbon\Carbon;
use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use MicroweberPackages\Checkout\Http\Controllers\CheckoutController;

//use MicroweberPackages\Invoice\Address;
//use MicroweberPackages\Invoice\Invoice;
use MicroweberPackages\Order\Events\OrderWasPaid;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Order\Notifications\NewOrder;
use MicroweberPackages\Utils\Mail\MailSender;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class CheckoutManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
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

        /*$tables['cart_taxes'] = 'cart_taxes';*/

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
            if (isset($update_order['payment_gw']) and isset($update_order['id'])) {
                $gw_return = normalize_path(modules_path() . $update_order['payment_gw'] . DS . 'return.php', false);
                if (is_file($gw_return)) {
                    include $gw_return;

                    if ($update_order != $update_order_orig) {

                        if (isset($update_order['is_paid'])) {
                            if (intval($update_order['is_paid']) == 1) {
                                $_REQUEST['mw_payment_success'] = true;
                                $_REQUEST['mw_payment_failure'] = null;
                            } else {
                                $_REQUEST['mw_payment_success'] = null;
                                $_REQUEST['mw_payment_failure'] = true;
                                //    mw()->cart_manager->recover_cart(session()->getId(), $update_order['id']);

                            }
                        }

                        $should_mark_as_paid = false;


                        $this->_verify_request_params($update_order);


                        if (!isset($update_order_orig['is_paid']) or (isset($update_order_orig['is_paid']) and intval($update_order_orig['is_paid']) == 0)) {
                            if (isset($update_order['is_paid']) and intval($update_order['is_paid']) == 1) {
                                $should_mark_as_paid = true;
                                unset($update_order['is_paid']);
                            }
                        }

                        $this->app->order_manager->save($update_order);


                        if ($should_mark_as_paid) {
                            $this->app->checkout_manager->mark_order_as_paid($update_order['id']);
                        }


                        if (isset($update_order['id'])) {
                            $this->after_checkout($update_order['id']);
                        }


                    }
                }
            }

            if (isset($_REQUEST['mw_payment_success'])) {
                $mw_process_payment = false;
                $mw_process_payment_success = true;
                $exec_return = true;
            } elseif (isset($_REQUEST['mw_payment_failure'])) {

                if (isset($_REQUEST['recart']) and $_REQUEST['recart'] != false and isset($_REQUEST['order_id'])) {

                    mw()->cart_manager->recover_cart($_REQUEST['recart'], $_REQUEST['order_id']);
                }

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
            } else {

                if(isset($update_order) and isset($update_order['id'])){
                    if ($mw_process_payment_success == true) {
                        return redirect(route('checkout.finish', $update_order['id']))->with('success',_e('Your payment is complete',true));
                    } elseif ($mw_process_payment_failed == true) {
                        return redirect(route('checkout.finish', $update_order['id']))->with('success',_e('Your payment was not complete',true));

                    } else {
                        return redirect('/');
                    }
                }


            }
        }

        $additional_fields = false;
        if (isset($data['for']) and isset($data['for_id'])) {
            $additional_fields = $this->app->fields_manager->get([
                'rel_type' => $data['for'],
                'rel_id' => $data['for_id'],
                'return_full' => true,
            ]);
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

   /*
    *  OLD VALIDATION ON MODAL
    *      $validator = app()->make(CheckoutController::class);

        if (!empty($data)) {
            $request = new Request();
            $request->merge($data);
            $is_valid = $validator->validate($request);
        } else {
            $is_valid['errors'] = 'Data not entered.';
        }

        if (is_object($is_valid)) {
            return $is_valid;
        }

        if (isset($is_valid['errors'])) {
            return $is_valid;
        }*/

        $checkout_errors = array();
        $check_cart = $this->app->shop_manager->get_cart($cart);

        if (!is_array($check_cart)) {
            $checkout_errors['cart_empty'] = 'Your cart is empty';
        } else {

            if (!is_admin()) {
                $shop_require_terms = $this->app->option_manager->get('shop_require_terms', 'website');
                if ($shop_require_terms) {
                    $user_id_or_email = $this->app->user_manager->id();
                    if (!$user_id_or_email) {
                        if (isset($data['email'])) {
                            $user_id_or_email = $data['email'];
                        }
                    }

                    if (!$user_id_or_email) {
                        $checkout_errors['cart_needs_email'] = _e('You must provide email address', true);
                    } else {
                        $terms_and_conditions_name = 'terms_shop';

                        $check_term = $this->app->user_manager->terms_check($terms_and_conditions_name, $user_id_or_email);
                        if (!$check_term) {
                            if (isset($data['terms']) and $data['terms']) {
                                $this->app->user_manager->terms_accept($terms_and_conditions_name, $user_id_or_email);
                            } else {
                                return array(
                                    'error' => _e('You must agree to terms and conditions', true),
                                    'form_data_required' => 'terms',
                                    'form_data_module' => 'users/terms'
                                );

                            }
                        }
                    }
                }
            }


            if (!isset($data['payment_gw']) and $mw_process_payment == true) {
                $data['payment_gw'] = 'none';
            } else {
                if ($mw_process_payment == true) {
                    $gw_check = $this->payment_options('payment_gw_' . $data['payment_gw']);
                    dd($gw_check);
                    if (isset($gw_check[0]) && is_array($gw_check[0])) {
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

            $discount_value = false;
            $discount_type = false;

            $coupon_id = false;
            $coupon_code = false;
            $shipping_cost = 0;

            /*  if (($this->app->user_manager->session_get('shipping_country'))) {
                  $shipping_country = $this->app->user_manager->session_get('shipping_country');
              }
              if (($this->app->user_manager->session_get('shipping_cost_max'))) {
                  $shipping_cost_max = $this->app->user_manager->session_get('shipping_cost_max');
              }
              if (($this->app->user_manager->session_get('shipping_cost_above'))) {
                  $shipping_cost_above = $this->app->user_manager->session_get('shipping_cost_above');
              }*/


//
//            if ($this->app->user_manager->session_get('shipping_cost')) {
//                $shipping_cost = $this->app->user_manager->session_get('shipping_cost');
//            }
//
////
////
////
////            $shipping_gw_from_session = $this->app->user_manager->session_get('shipping_provider');
////            if(!isset($data['shipping_gw']) and $shipping_gw_from_session){
////                $data['shipping_gw'] = $shipping_gw_from_session;
////            }
////            if(isset($data['shipping_gw']) and $data['shipping_gw']){
////                try {
////                    $shipping_cost = $this->app->shipping_manager->driver($data['shipping_gw'])->cost();
////
////                } catch (\InvalidArgumentException $e) {
////                    $shipping_cost = 0;
////                    unset($data['shipping_gw']);
////                }
////             }

            $shipping_cost = $this->getShippingCost($data);

            if (($this->app->user_manager->session_get('discount_value'))) {
                $discount_value = $this->app->user_manager->session_get('discount_value');
            }
            if (($this->app->user_manager->session_get('discount_type'))) {
                $discount_type = $this->app->user_manager->session_get('discount_type');
            }
            if (($this->app->user_manager->session_get('coupon_id'))) {
                $coupon_id = $this->app->user_manager->session_get('coupon_id');
            }
            if (($this->app->user_manager->session_get('coupon_code'))) {
                $coupon_code = $this->app->user_manager->session_get('coupon_code');
            }


            //post any of those on the form
            $flds_from_data = array('first_name', 'last_name', 'email', 'country', 'city', 'state', 'zip', 'address', 'address2', 'payment_email', 'payment_name', 'payment_country', 'payment_address', 'payment_city', 'payment_state', 'payment_zip', 'phone', 'promo_code', 'payment_gw', 'other_info');

            if (!isset($data['email']) or $data['email'] == '') {
                $data['email'] = user_name(user_id(), 'email');
            }

            if (get_option('shop_require_email', 'website') == 1) {
                if (!isset($data['email']) or $data['email'] == '') {
                    $checkout_errors['email'] = 'Email is required';
                }
            }

            if (!isset($data['first_name']) or $data['first_name'] == '') {
                $data['first_name'] = user_name(user_id(), 'first');
            }

            if (get_option('shop_require_first_name', 'website') == 1) {
                if (!isset($data['first_name']) or $data['first_name'] == '') {
                    $checkout_errors['first_name'] = 'First name is required';
                }
            }

            if (get_option('shop_require_last_name', 'website') == 1) {
                if (!isset($data['last_name']) or $data['last_name'] == '') {
                    // $checkout_errors['last_name'] = 'Last name is required';
                    $data['last_name'] = user_name(user_id(), 'last');
                }
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
            $set_return_url_for_order_finish = false;


//            if ($this->app->url_manager->is_ajax()) {
//                $set_return_url_for_order_finish = $this->app->url_manager->current(true);
//            } elseif (isset($_SERVER['HTTP_REFERER'])) {
//                $set_return_url_for_order_finish = $_SERVER['HTTP_REFERER'];
//            }
           // $set_return_url_for_order_finish = route('checkout.finish',0);


//            if ($set_return_url_for_order_finish) {
//                $urlarray = explode('?', $set_return_url_for_order_finish);
//                if (isset($urlarray[0]) and isset($urlarray[1])) {
//                    parse_str($urlarray[1], $ref_params);
//                    if (isset($ref_params['mw_payment_failure'])) {
//                        unset($ref_params['mw_payment_failure']);
//                    }
//                    if (isset($ref_params['mw_payment_success'])) {
//                        unset($ref_params['mw_payment_success']);
//                    }
//                    $rebuild = http_build_query($ref_params, '', '&amp;');
//                    if ($rebuild) {
//                        $set_return_url_for_order_finish = $urlarray[0] . '?' . $rebuild;
//                    } else {
//                        $set_return_url_for_order_finish = $urlarray[0];
//                    }
//                }
//                $place_order['url'] = $set_return_url_for_order_finish;
//                $return_url_after = '&return_to=' . urlencode($set_return_url_for_order_finish);
//                $this->app->user_manager->session_set('checkout_return_to_url', $set_return_url_for_order_finish);
//            } else {
//                $place_order['url'] = $this->app->url_manager->current();
//            }

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

            $amount = number_format($amount, 2);

            $place_order['amount'] = $amount;
            $place_order['allow_html'] = true;
            $place_order['currency'] = $this->app->option_manager->get('currency', 'payments');
            if (!$place_order['currency']) {
                $place_order['currency'] = 'USD';
            }

            if (isset($data['shipping_gw'])) {
                $place_order['shipping_service'] = $data['shipping_gw'];
            }
            $place_order['shipping'] = $shipping_cost;
            if ($tax != 0) {
                $place_order['taxes_amount'] = $tax;
            }

            $items_count = $this->app->shop_manager->cart_sum(false);
            $place_order['items_count'] = $items_count;

            $cart_checksum = md5($sid . serialize($check_cart) . uniqid(time()));

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


            // Discount details save
            $place_order['promo_code'] = $coupon_code;
            $place_order['coupon_id'] = $coupon_id;
            $place_order['discount_type'] = $discount_type;
            $place_order['discount_value'] = $discount_value;


            // convert currency to payment provider currency
            $currencies_list_paypal = mw()->shop_manager->currency_get_for_paypal();
            $currencyCode = strtoupper($place_order['currency']);
            $amount = $place_order['amount'];

            if (!isset($place_order['payment_amount'])) {
                $place_order['payment_amount'] = $amount;
            }
            $place_order['payment_shipping'] = $place_order['shipping'];


            $payment_currency = get_option('payment_currency', 'payments');
            $payment_currency_rate = get_option('payment_currency_rate', 'payments');

            if (!isset($place_order['payment_currency'])) {
                $place_order['payment_currency'] = $place_order['currency'];
            }

            if ($payment_currency and $payment_currency != $currencyCode) {

                if (!in_array(strtoupper($place_order['currency']), $currencies_list_paypal)) {

                    $currencyCode = $payment_currency;


                    if ($payment_currency_rate != false) {
                        $payment_currency_rate = str_replace(',', '.', $payment_currency_rate);
                        $payment_currency_rate = floatval($payment_currency_rate);

                    }
                    if ($payment_currency_rate != 0.00) {

                        $amount = $amount * $payment_currency_rate;
                        $place_order['payment_amount'] = $amount;

                    }


                    if ($place_order['payment_shipping']) {
                        $place_order['payment_shipping'] = $place_order['payment_shipping'] * $payment_currency_rate;

                    }


                }
            }


            $place_order['payment_currency'] = $currencyCode;


            // end of convert for curency


            if ($mw_process_payment == true) {
                $shop_dir = module_dir('shop');
                $shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;

                if ($data['payment_gw'] != 'none') {
                    $place_order['payment_gw'] = $data['payment_gw'];
                    $gw_process = modules_path() . $data['payment_gw'] . '_process.php';
                    if (!is_file($gw_process)) {
                        $gw_process = normalize_path(modules_path() . $data['payment_gw'] . DS . 'process.php', false);
                    }

                    $encrypter = new \Illuminate\Encryption\Encrypter(md5(\Illuminate\Support\Facades\Config::get('app.key') . $place_order['payment_verify_token']), \Illuminate\Support\Facades\Config::get('app.cipher'));

                    $vkey_data = array();
                    // $vkey_data['payment_amount'] = $place_order['payment_amount'];
                    // $vkey_data['payment_currency'] = $place_order['payment_currency'];
                    $vkey_data['payment_verify_token'] = $place_order['payment_verify_token'];
                    //   $vkey_data['id'] = $place_order['id'];
// dd($vkey_data);
                    //  $enc_key_hash = md5($encrypter->encrypt(json_encode($vkey_data)));

                    //  $enc_key_hash = md5(\Config::get('app.key').json_encode($vkey_data));
                    $enc_key_hash = md5(json_encode($vkey_data));
                    $enc_key_hash = $encrypter->encrypt($enc_key_hash);

                    $mw_return_url = $this->app->url_manager->api_link('checkout') . '?mw_payment_success=1&order_id=' . $place_order['id'] . '&payment_gw=' . $place_order['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . '&_vkey_url=' . $enc_key_hash . $return_url_after;
                    $vkey_data_temp = $vkey_data;
                    // $vkey_data_temp['url'] = $mw_return_url;
                    //$mw_return_url .= '&_vkey_url=' . $enc_key_hash;


                    $mw_cancel_url = $this->app->url_manager->api_link('checkout') . '?mw_payment_failure=1&order_id=' . $place_order['id'] . '&payment_gw=' . $place_order['payment_gw'] . '&_vkey_url=' . $enc_key_hash . '&recart=' . $sid . $return_url_after;
                    $vkey_data_temp = $vkey_data;
                    // $vkey_data_temp['url'] = $mw_cancel_url;
                    // $mw_cancel_url .= '&_vkey_url=' . $enc_key_hash;


                    $mw_ipn_url = $this->app->url_manager->api_link('checkout_ipn') . '?payment_gw=' . $place_order['payment_gw'] . '&order_id=' . $place_order['id'] . '&payment_verify_token=' . $place_order['payment_verify_token'] . '&_vkey_url=' . $enc_key_hash . $return_url_after;
                    $vkey_data_temp = $vkey_data;
                    //$vkey_data_temp['url'] = $mw_ipn_url;
                    //$mw_ipn_url .= '&_vkey_url=' . $enc_key_hash;


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


                $place_order['order_status'] = 'new';

                if (!empty($checkout_errors)) {
                    return array('error' => $checkout_errors);
                }

                if (isset($place_order['error'])) {
                    return array('error' => $place_order['error']);
                }


                /*
                     $invoicePrefix = 'INV';
                     $nextInvoiceNumber = Invoice::getNextInvoiceNumber($invoicePrefix);
                     $invoiceDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                     $dueDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime('+6 days', strtotime(date('Y-m-d')))));

                     $invoiceTotal = ($place_order['amount'] * 100);

                     $invoice = Invoice::create([
                         'invoice_date' => $invoiceDate,
                         'due_date' => $dueDate,
                         'invoice_number' => $invoicePrefix . '-' . $nextInvoiceNumber,
                         'reference_number' => '',
                         'customer_id' => $findCustomer->id,
                         'company_id' => 0,
                         'invoice_template_id' => 1,
                         'status' => Invoice::STATUS_DRAFT,
                         'paid_status' => Invoice::STATUS_UNPAID,
                         'sub_total' => $invoiceTotal,
                         'discount' =>'',
                         'discount_type' => $place_order['discount_type'],
                         'discount_val' => ($place_order['discount_value'] * 100),
                         'total' => $invoiceTotal,
                         'due_amount' => $invoiceTotal,
                         'tax_per_item' => '',
                         'discount_per_item' => '',
                         'tax' => '',
                         'notes' => '',
                         'unique_hash' => str_random(60)
                     ]);

                     foreach ($check_cart as $cartItem) {
                         $invoice->items()->create([
                             'name'=>$cartItem['title'],
                             'description'=>$cartItem['description'],
                             'price'=>($cartItem['price'] * 100),
                             'quantity'=>$cartItem['qty'],
                         ]);
                     }*/

                $ord = $this->app->shop_manager->place_order($place_order);
                $place_order['id'] = $ord;

                if (isset($place_order['is_paid']) and $place_order['is_paid']) {
                    $this->app->event_manager->trigger('mw.cart.checkout.order_paid', $place_order);
                }


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

    public function getUserInfo()
    {
        return $this->checkout_get_user_info();
    }


    public function checkout_get_user_info()
    {

        $ready = [];
        $logged_user_data = [];
        $shipping_address_from_profile = [];
        $logged_user_data = [];


        $selected_country_from_session = session_get('shipping_country');
        $checkout_session = session_get('checkout');
        $checkout_session2 = session_get('checkout_v2');

        if (!$checkout_session) {
            $checkout_session = [];
        }
        if (!$checkout_session2) {
            $checkout_session2 = [];
        }
        $checkout_session = array_merge($checkout_session,$checkout_session2);
        $user_fields_from_profile = ['email', 'last_name', 'first_name', 'phone', 'username', 'middle_name'];
        $shipping_fields_keys = ['address', 'city', 'state', 'zip', 'other_info', 'country', 'shipping_gw', 'payment_gw'];

        $all_field_keys = array_merge($user_fields_from_profile, $shipping_fields_keys);


        if (is_logged()) {
            $shipping_address_from_profile = app()->user_manager->get_shipping_address();
        }
        if ($checkout_session) {
            foreach ($all_field_keys as $field_key) {
                if (!empty($checkout_session) and !isset($ready[$field_key])) {
                    foreach ($checkout_session as $k => $v) {
                        if ($field_key == $k and $v) {
                            $ready[$k] = $v;
                        }
                    }
                }
            }
            if (!isset($ready['country']) and $selected_country_from_session) {
                $ready['country'] = $selected_country_from_session;

            }
        }

        if ($shipping_address_from_profile) {
            foreach ($all_field_keys as $field_key) {
                if (!empty($shipping_address_from_profile) and !isset($ready[$field_key])) {
                    foreach ($shipping_address_from_profile as $k => $v) {
                        if ($field_key == $k and $v) {
                            $ready[$k] = $v;
                        }

                    }
                }
            }
        }


        if ($shipping_address_from_profile) {
            $logged_user_data = get_user();
            if ($logged_user_data) {
                foreach ($all_field_keys as $field_key) {
                    if (!empty($logged_user_data) and !isset($ready[$field_key])) {
                        foreach ($logged_user_data as $k => $v) {
                            if ($field_key == $k and $v) {
                                $ready[$k] = $v;
                            }

                        }
                    }
                }
            }

        }
        return $ready;
    }

    public function payment_options($option_key = false)
    {
        $option_key_q = '';
        if (is_string($option_key)) {
            $option_key_q = "&limit=1&option_key={$option_key}";
        }
        $providers = $this->app->option_manager->get_all('option_group=payments' . $option_key_q);
      //  $providers = $this->app->option_repository->getByParams('option_group=payments' . $option_key_q);

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
                        $mod_infp = $this->app->module_manager->get('ui=any&one=1&module=' . $title);

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

    public function after_checkout($orderId)
    {
        if ($orderId == false or trim($orderId) == '') {
            return array('error' => _e('Invalid order ID'));
        }

        $order = Order::find($orderId);
        if (!$order) {
            return array('error' => _e('Order not found'));
        }
        // $this->confirm_email_send($orderId);
    }


    public function mark_order_as_paid($orderId)
    {

        $order = Order::find($orderId);
        if (!$order) {
            return;
        }

        $update_order_event_data = $order->toArray();

        if (!isset($update_order_event_data['is_paid']) or (isset($update_order_event_data['is_paid']) and intval($update_order_event_data['is_paid']) == 0)) {
            event($event = new OrderWasPaid($order, $update_order_event_data));
            $this->app->event_manager->trigger('mw.cart.checkout.order_paid', $update_order_event_data);
            $this->app->shop_manager->update_quantities($orderId);
            $order->is_paid = 1;
            $order->save();
        }


    }


    public function confirm_email_send($order_id, $to = false, $no_cache = true, $skip_enabled_check = false)
    {
        $ord_data = $this->app->shop_manager->get_order_by_id($order_id);

        if (is_array($ord_data)) {

            if ($skip_enabled_check == false) {
                $order_email_enabled = $this->app->option_manager->get('order_email_enabled', 'orders');
            } else {
                $order_email_enabled = $skip_enabled_check;
            }

            $send_to_client = true;
            $send_to_admins = true;
            $send_to_client_option = $this->app->option_manager->get('send_email_on_new_order', 'orders');
            if (!empty($send_to_client_option)) {
                if ($send_to_client_option == 'admins') {
                    $send_to_admins = true;
                    $send_to_client = false;
                }
                if ($send_to_client_option == 'client') {
                    $send_to_admins = false;
                    $send_to_client = true;
                }
            }

            if ($order_email_enabled) {

                //  $order_email_subject = $this->app->option_manager->get('order_email_subject', 'orders');
                // $order_email_content = $this->app->option_manager->get('order_email_content', 'orders');

                $mail_template = false;
                $mail_template_binds = $this->app->event_manager->trigger('mw.cart.confirm_email_send', $order_id);
                if (is_array($mail_template_binds)) {
                    foreach ($mail_template_binds as $bind) {
                        if (is_array($bind) && isset($bind['mail_template'])) {
                            $mail_template = $bind['mail_template'];
                        }
                    }
                }

                if (!$mail_template) {
                    return;
                }

                $order_email_cc_string = $mail_template['copy_to'];
                $order_email_subject = $mail_template['subject'];
                $order_email_content = $mail_template['message'];

                $order_email_cc = array();
                if (!empty($order_email_cc_string) && strpos($order_email_cc_string, ',')) {
                    $order_email_cc = explode(',', $order_email_cc_string);
                } else {
                    $order_email_cc[] = $order_email_cc_string;
                }

                if (empty($order_email_cc)) {
                    $admins = get_users('is_admin=1');
                    foreach ($admins as $admin) {
                        if (isset($admin['email']) && !empty($admin['email']) && filter_var($admin['email'], FILTER_VALIDATE_EMAIL)) {
                            $order_email_cc[] = $admin['email'];
                        }
                    }
                }

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

                        $cart_items_info = array();
                        $order_items_html = '';
                        if (!empty($cart_items)) {
                            foreach ($cart_items as $cart_item) {
                                $arr = array();
                                if (isset($cart_item['item_image']) and $cart_item['item_image']) {

                                    $arr['item_image'] = $cart_item['item_image'];
                                    $arr['item_image'] = '<img src="' . $arr['item_image'] . '" width="100" />';
                                }
                                if (isset($cart_item['link'])) {
                                    $arr['link'] = $cart_item['link'];
                                }
                                if (isset($cart_item['title'])) {
                                    $arr['title'] = $cart_item['title'];
                                }
                                if (isset($cart_item['custom_fields'])) {
                                    $arr['custom_fields'] = $cart_item['custom_fields'];
                                }
                                $cart_items_info[] = $arr;
                            }
                            $order_items_html = $this->app->format->array_to_table($cart_items_info);

                        }
                        $order_email_content = str_replace('{{cart_items}}', $order_items_html, $order_email_content);
                        $order_email_content = str_replace('{{date}}', date('F jS, Y', strtotime($ord_data['created_at'])), $order_email_content);
                        foreach ($ord_data as $key => $value) {
                            if (!is_array($value) and is_string($key)) {
                                if (strtolower($key) == 'amount') {
                                    $value = number_format($value, 2);
                                    $order_email_content = str_ireplace('{{' . $key . '}}', $value, $order_email_content);
                                    continue;
                                }
                            }
                        }
                    }

                    if (get_option('bank_transfer_send_email_instructions', 'payments') == 'y') {
                        $order_email_content .= _e("Follow payment instructions", true);
                        $order_email_content .= '<br />' . get_option('bank_transfer_instructions', 'payments');
                    }

                    $loader = new ArrayLoader([
                        'checkout_mail.html' => $order_email_content,
                    ]);
                    $twig = new Environment($loader);
                    $order_email_content = $twig->render(
                        'checkout_mail.html', [
                            'cart' => $cart_items,
                            'order' => $ord_data,
                            'order_id' => $ord_data['id'],
                            'transaction_id' => $ord_data['transaction_id'],
                            'currency' => $ord_data['currency'],
                            'order_status' => $ord_data['order_status'],
                            'first_name' => $ord_data['first_name'],
                            'last_name' => $ord_data['last_name'],
                            'email' => $ord_data['email'],
                            'phone' => $ord_data['phone'],
                            'address' => $ord_data['address'],
                            'zip' => $ord_data['zip'],
                            'state' => $ord_data['state'],
                            'city' => $ord_data['city'],
                            'country' => $ord_data['country']
                        ]
                    );

                    $sender = new MailSender();

                    // Send only to client
                    if ($send_to_client && !$send_to_admins && filter_var($to, FILTER_VALIDATE_EMAIL)) {
                        $sender->send($to, $order_email_subject, $order_email_content);
                        // echo 'Send only to client.';
                    }

                    // Send only to admins
                    if (!$send_to_client && $send_to_admins && is_array($order_email_cc)) {
                        // echo 'Send only to admins.';
                        foreach ($order_email_cc as $admin_email) {
                            $sender->send($admin_email, $order_email_subject, $order_email_content, false, $no_cache);
                        }
                    }

                    // Send to admins and client
                    if ($send_to_client && $send_to_admins) {
                        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                            $sender->send($to, $order_email_subject, $order_email_content);
                            // echo 'Send to client.';
                        }
                        if (is_array($order_email_cc)) {
                            // echo 'Send to admins.';
                            foreach ($order_email_cc as $admin_email) {
                                $sender->send($admin_email, $order_email_subject, $order_email_content, false, $no_cache);
                            }
                        }
                    }

                    return true;
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

        $should_mark_as_paid = false;

        $client_ip = user_ip();

        $hostname = $this->get_domain_from_str($client_ip);


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

            // $this->_verify_request_params($update_order);

        } else {
            return array('error' => 'The payment gateway is not found!');
        }
        $update_order_event_data = [];


        if (is_array($update_order)) {
            $update_order_event_data = array_merge($ord_data, $update_order);
        }


        if (!empty($update_order_event_data) and isset($update_order_event_data['order_completed']) and $update_order_event_data['order_completed'] == 1) {
            $this->after_checkout($ord);

            if (!isset($ord_data['is_paid']) or (isset($ord_data['is_paid']) and intval($ord_data['is_paid']) == 0)) {
                if (isset($update_order_event_data['is_paid']) and intval($update_order_event_data['is_paid']) == 1) {
                    $should_mark_as_paid = true;
                }
            }

            if ($should_mark_as_paid) {
                $this->app->checkout_manager->mark_order_as_paid($ord);
            }


            //            $update_order_event_data['id'] = $ord;
//            $update_order_event_data['payment_gw'] = $data['payment_gw'];
//            $ord = $this->app->database_manager->save($table_orders, $update_order_event_data);
//
//
//            if (isset($update_order_event_data['is_paid']) and $update_order_event_data['is_paid']) {
//                $this->app->event_manager->trigger('mw.cart.checkout.order_paid', $update_order_event_data);
//            }
//
//            if (isset($update_order_event_data['is_paid']) and $update_order_event_data['is_paid'] == 1) {
//                $this->app->shop_manager->update_quantities($ord);
//
//
//            }
//            if ($ord > 0) {
//                $this->app->cache_manager->delete('cart');
//                $this->app->cache_manager->delete('cart_orders');
//                //return true;
//            }
//
//            $this->confirm_email_send($ord);

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

    private function _verify_request_params($data)
    {

        $error = true;

        if (!isset($data['payment_verify_token'])) {
            $error = true;
        }
        if (isset($data['order_id'])) {
            $data['id'] = $data['order_id'];
        }

        if (!isset($data['payment_amount'])) {
            $error = true;
        }


        if (!isset($data['payment_currency'])) {
            $error = true;
        }
        if (!isset($data['id'])) {
            $error = true;
        }



        $vkey = false;

        if (isset($_REQUEST['_vkey_url'])) {
            $vkey = $_REQUEST['_vkey_url'];
        }


//        $url = url_current();
//        $param = '_vkey_url';
//        $pieces = parse_url($url);
//        $query = [];
//        if ($pieces['query']) {
//            parse_str($pieces['query'], $query);
//            $data[$param] = $query[$param];
//            unset($query[$param]);
//            $pieces['query'] = http_build_query($query);
//        }
//        if (!isset($data['_vkey_url'])) {
//            $error = true;
//        } else {
//            $vkey = $data['_vkey_url'];
//        }


        if (!$vkey) {
            $error = true;
        }
        $order_data = false;
        if(!$error and isset($data['id'])){
        $order_data = get_order_by_id($data['id']);
        }

        if ($order_data and $vkey) {

            $vkey_data = array();
            //  $vkey_data['payment_amount'] = $order_data['payment_amount'];
            // $vkey_data['payment_currency'] = $order_data['payment_currency'];
            $vkey_data['payment_verify_token'] = $order_data['payment_verify_token'];
            //  $vkey_data['id'] = $order_data['id'];
//dd($order_data);
            $enc_key_hash = md5(json_encode($vkey_data));
            //   $enc_key_hash = md5(\Config::get('app.key').json_encode($vkey_data));

            // dd(2222,$vkey,$enc_key_hash,$data,$order_data);

            // $vkey = urldecode($vkey);

            $encrypter = new \Illuminate\Encryption\Encrypter(md5(\Config::get('app.key') . $order_data['payment_verify_token']), \Config::get('app.cipher'));

            $decrypt_data = $encrypter->decrypt($vkey);

            //    dd($enc_key_hash,$decrypt_data);

            //  $enc_key_hash = $encrypter->encrypt(json_encode($vkey_data));

            //dd($vkey, $enc_key_hash,$order_data,$vkey_data);
            if ($enc_key_hash === $decrypt_data) {
                $error = false;

            }

            // $url_verify = $this->_build_url($pieces);
            // $decrypt_data = @json_decode($encrypter->decrypt($vkey), true);

//            if (!$decrypt_data) {
//                $error = true;
//            } else {
//
//                $decrypt_url = $decrypt_data['url'];
//                $decrypt_payment_amount = $decrypt_data['payment_amount'];
//                $decrypt_payment_currency = $decrypt_data['payment_currency'];
//
//                $url_verify = urldecode($url_verify);
//                $decrypt_url = urldecode($decrypt_url);
//
//                if (md5($url_verify) !== md5($decrypt_url)) {
//                    $error = true;
//                }
//
//                if (md5(floatval($decrypt_payment_amount)) !== md5(floatval($data['payment_amount']))) {
//                    $error = true;
//                }
//                if (md5(strtoupper($decrypt_payment_currency)) !== md5(strtoupper($data['payment_currency']))) {
//                    $error = true;
//                }
//            }
        }


        if ($error) {

            abort(403, 'Unauthorized action.');
        }

    }

    public function getShippingModules()
    {
        return $this->app->shipping_manager->getShippingModules();

    }

    public function getShippingCost($data = [])
    {

        if (!is_array($data)) {
            $data = [];
        }
        $shipping_cost = 0;

        if ($this->app->user_manager->session_get('shipping_cost')) {
            $shipping_cost = $this->app->user_manager->session_get('shipping_cost');
        }


        $shipping_gw_from_session = $this->app->user_manager->session_get('shipping_provider');
        if (!isset($data['shipping_gw']) and $shipping_gw_from_session) {
            $data['shipping_gw'] = $shipping_gw_from_session;
        } else {
            $data['shipping_gw'] = 'default';

        }
        if (isset($data['shipping_gw']) and $data['shipping_gw']) {
            // $shipping_cost = $this->app->shipping_manager->driver($data['shipping_gw'])->cost();

            try {
                $shipping_cost = $this->app->shipping_manager->driver($data['shipping_gw'])->cost();

            } catch (\InvalidArgumentException $e) {
                $shipping_cost = 0;
                unset($data['shipping_gw']);
            }
        }
        return $shipping_cost;

    }

    private function _build_url(array $elements)
    {
        $e = $elements;
        return
            (isset($e['host']) ? (
                (isset($e['scheme']) ? "$e[scheme]://" : '//') .
                (isset($e['user']) ? $e['user'] . (isset($e['pass']) ? ":$e[pass]" : '') . '@' : '') .
                $e['host'] .
                (isset($e['port']) ? ":$e[port]" : '')
            ) : '') .
            (isset($e['path']) ? $e['path'] : '/') .
            (isset($e['query']) ? '?' . (is_array($e['query']) ? http_build_query($e['query'], '', '&') : $e['query']) : '') .
            (isset($e['fragment']) ? "#$e[fragment]" : '');
    }
}
