<?php

namespace Modules\Checkout\Repositories;

use MicroweberPackages\Utils\Mail\MailSender;
use Modules\Checkout\Services\CheckoutService;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\Payment\Drivers\AbstractPaymentMethod;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

//use MicroweberPackages\Invoice\Address;
//use MicroweberPackages\Invoice\Invoice;

class CheckoutManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public $tables = array();
    public CheckoutService $checkoutService;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }


        $this->checkoutService = new CheckoutService();


    }


    public function checkout($data)
    {

        return $this->checkoutService->checkout($data);

    }


    public function getUserInfo($key = false)
    {
        return $this->checkoutService->getUserInfo($key);
    }

    public function setUserInfo($key, $value)
    {
        return $this->checkoutService->setUserInfo($key, $value);
    }


    public function payment_options($option_key = false)
    {
        if ($option_key) {
            return throw new \Exception('Not implemented payment_options $option_key');
            // return app()->payment_method_manager->getProvider($option_key);
        }
        return app()->payment_method_manager->getProviders();


//        $option_key_q = '';
//        if (is_string($option_key)) {
//            $option_key_q = "&limit=1&option_key={$option_key}";
//        }
//        $providers = $this->app->option_manager->get_all('group=payments' . $option_key_q);
//      //  $providers = $this->app->option_repository->getByParams('group=payments' . $option_key_q);
//
//        $payment_modules = get_modules('type=payment_gateway');
//        $str = 'payment_provider_';
//        $l = strlen($str);
//        $enabled_providers = array();
//        if (!empty($payment_modules) and !empty($providers)) {
//            foreach ($payment_modules as $payment_module) {
//                foreach ($providers as $value) {
//                    if ($value['option_value'] == 1) {
//                        if (substr($value['option_key'], 0, $l) == $str) {
//                            $title = substr($value['option_key'], $l);
//                            $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
//                            $value['gw_file'] = $title;
//
//                            if (isset($payment_module['module']) and $value['gw_file'] == $payment_module['module']) {
//                                $payment_module['gw_file'] = $title;
//                                $enabled_providers[] = $payment_module;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        if (!empty($enabled_providers)) {
//            return $enabled_providers;
//        }
//
//        // the rest is for comaptibily and will be removed in the near future
//        $str = 'payment_provider_';
//        $l = strlen($str);
//        if (is_array($providers)) {
//            $valid = array();
//            foreach ($providers as $value) {
//                if ($value['option_value'] == 1) {
//                    if (substr($value['option_key'], 0, $l) == $str) {
//                        $title = substr($value['option_key'], $l);
//                        $string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
//                        $value['gw_file'] = $title;
//                        $mod_infp = $this->app->module_manager->get('ui=any&one=1&module=' . $title);
//
//                        if (!empty($mod_infp)) {
//                            $value = $mod_infp;
//                            $title = sanitize_path($title);
//
//                            $value['gw_file'] = $title;
//                            $valid[] = $value;
//                        }
//                    }
//                }
//            }
//
//            return $valid;
//        }
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
        return $this->checkoutService->markOrderAsPaid($orderId);


    }


    public function confirm_email_send($order_id, $to = false, $no_cache = true, $skip_enabled_check = false)
    {
        return $this->checkoutService->confirmEmailSend($order_id, $to, $no_cache, $skip_enabled_check);
    }

    /*
        public function checkout_ipn($data)
        {
            if (isset($data['payment_verify_token'])) {
                $payment_verify_token = ($data['payment_verify_token']);
            }
            if (!isset($data['payment_provider'])) {
                return array('error' => 'You must provide a payment gateway parameter!');
            }


            $data['payment_provider'] = sanitize_path($data['payment_provider']);

            $should_mark_as_paid = false;

            $client_ip = user_ip();

            $hostname = $this->get_domain_from_str($client_ip);


            $payment_verify_token = $this->app->database_manager->escape_string($payment_verify_token);
            $table = 'cart_orders';

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

            $cart_table = 'cart';
            $table_orders = 'cart_orders';

            $data['payment_provider'] = sanitize_path($data['payment_provider']);
            $gw_process = modules_path() . $data['payment_provider'] . '_checkout_ipn.php';
            if (!is_file($gw_process)) {
                $gw_process = normalize_path(modules_path() . $data['payment_provider'] . DS . 'checkout_ipn.php', false);
            }
            if (!is_file($gw_process)) {
                $gw_process = normalize_path(modules_path() . $data['payment_provider'] . DS . 'notify.php', false);
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
    //            $update_order_event_data['payment_provider'] = $data['payment_provider'];
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
        }*/

    public function getShippingModules()
    {
        return $this->app->shipping_method_manager->getProviders();

    }

    public function getShippingCost($data = [])
    {
        return $this->checkoutService->getShippingCost($data);

    }


}
