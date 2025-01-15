<?php

namespace Modules\Checkout\Services;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use MicroweberPackages\Utils\Mail\MailSender;
use Modules\Payment\Drivers\AbstractPaymentMethod;
use Modules\Shipping\Models\ShippingProvider;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class CheckoutService
{
    /**
     * @var \MicroweberPackages\App\LaravelApplication
     */
    protected $app;

    public function __construct($app = null)
    {
        $this->app = $app ?: app();
    }

    /**
     * Add item to cart
     */
    public function addItem($product, $quantity)
    {
        return $this->app->shop_manager->add_to_cart([
            'product_id' => $product->id,
            'qty' => $quantity
        ]);
    }

    /**
     * Process checkout
     */
    public function checkout(array $data)
    {
        // Unify and validate parameters
        $data = $this->unifyParams($data);

        // Validate cart
        $cart = $this->validateCart();
        if (isset($cart['error'])) {
            return $cart;
        }

        // Validate checkout data
        $validationResult = $this->validateCheckoutData($data);
        if (isset($validationResult['error'])) {
            return $validationResult;
        }

        // Process payment
        $orderData = $this->prepareOrderData($data);

        if (isset($data['payment_provider_id'])) {
            $gatewayResponse = [];
            $gw_check = app()->payment_method_manager->getProviderById($data['payment_provider_id']);

            if ($gw_check) {
                /* @var AbstractPaymentMethod $gatewayResponse */
                $gatewayResponse = $this->processPayment($data['payment_provider_id'], $orderData);
                $orderData['payment_provider_id'] = $data['payment_provider_id'];
            } else {
                $orderData['error'] = 'No such payment gateway is activated';
            }

            if (isset($gatewayResponse['success']) and $gatewayResponse['success']) {
                $orderData['order_completed'] = 1;
                $orderData['is_paid'] = 0;
                $orderData['success'] = 'Your order has been placed successfully!';
            }
            if (isset($gatewayResponse['success']) and $gatewayResponse['success'] == false) {
                $orderData['order_completed'] = 0;
                $orderData['is_paid'] = 0;
                $orderData['error'] = $gatewayResponse['error'] ?? 'An error occurred while processing the payment';
            }
            if (isset($gatewayResponse['redirectUrl']) and $gatewayResponse['redirectUrl']) {
                $orderData['redirect'] = $gatewayResponse['redirectUrl'];
            }
            if (isset($gatewayResponse['transactionId']) and $gatewayResponse['transactionId']) {
                $orderData['transaction_id'] = $gatewayResponse['transactionId'];
            }
        } else {
            $orderData['order_completed'] = 1;
            $orderData['success'] = 'Your order has been placed successfully!';
        }

        if (isset($orderData['error'])) {
            return array('error' => $orderData['error']);
        }
        // Place order
        $order = $this->app->order_manager->place_order($orderData);

        if (isset($orderData['is_paid']) && $orderData['is_paid']) {
            $this->markOrderAsPaid($order);
        }

        $this->updateQuantities($order);
        $this->confirmEmailSend($order);
        $return = [
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order,
            'id' => $order,
        ];

        if (isset($orderData['redirect']) and $orderData['redirect']) {
            $return['redirect'] = $orderData['redirect'];
        }

        $orderKeys = [
            'order_completed',
            'order_status',
            'currency',
            'amount',
            'shipping_amount',
            'items_count',
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'zip',
            'country',
            'shipping_provider_id',
            'payment_provider_id',
            'taxes_amount',
            'transaction_id'
        ];

        foreach ($orderKeys as $key) {
            if (isset($orderData[$key]) and $orderData[$key]) {
                $return[$key] = $orderData[$key];
            }
        }


        return $return;
    }

    public function setUserInfo($key, $value): void
    {
        $checkout_session = session_get('checkout') ?: [];
        $checkout_session[$key] = $value;
        session_set('checkout', $checkout_session);
    }

    /**
     * Get user checkout information
     */
    public function getUserInfo($key = false)
    {
        $ready = session_get('checkout') ?: [];

        $user_fields = ['email', 'last_name', 'first_name', 'phone', 'username', 'middle_name'];
        $shipping_fields = ['address', 'city', 'state', 'zip', 'other_info', 'country', 'shipping_provider_id', 'payment_provider_id'];
        $all_fields = array_merge($user_fields, $shipping_fields);

        // Get shipping address from profile if logged in
        if (is_logged()) {
            $shipping_address = $this->app->user_manager->get_shipping_address();
            foreach ($all_fields as $field) {
                if (!isset($ready[$field])) {
                    if (!empty($shipping_address[$field])) {
                        $ready[$field] = $shipping_address[$field];
                    }
                }
            }
        }

        // Merge with session data
        foreach ($all_fields as $field) {
            if (!isset($ready[$field])) {
                if (isset($checkout_session[$field])) {
                    $ready[$field] = $checkout_session[$field];
                }
            }
        }

        if (is_logged()) {
            $user_fields = get_user();
            foreach ($all_fields as $field) {
                if (isset($user_fields[$field])) {
                    if (!isset($ready[$field])) {
                        $ready[$field] = $user_fields[$field];
                    }
                }
            }
        }

        if ($key) {
            return $ready[$key] ?? null;
        }

        return $ready;
    }

    public function confirmEmailSend($order_id, $to = false, $no_cache = true, $skip_enabled_check = false)
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

    /**
     * Mark order as paid
     */
    public function markOrderAsPaid($orderId)
    {
        $order = Order::where('id', $orderId)->first();
        if (!$order) {
            return;
        }

        if (!$order->is_paid) {
            event(new OrderWasPaid($order));
            $this->app->event_manager->trigger('mw.cart.checkout.order_paid', $order->toArray());

            $order->is_paid = 1;
            $order->save();
        }
    }

    public function updateQuantities($orderId)
    {
        $this->app->shop_manager->update_quantities($orderId);

    }

    /**
     * Get shipping cost
     */
    public function getShippingCost(array $data = [])
    {
        $shipping_cost = 0;

        $shipping_provider_id = checkout_get_user_info('shipping_provider_id');

        if ($shipping_provider_id) {

            $provider = app()->shipping_method_manager->getProviderById($shipping_provider_id);

            if ($provider) {
                try {
                    $shipping_cost = app()->shipping_method_manager->getShippingCost($shipping_provider_id, $data);

                } catch (\InvalidArgumentException $e) {

                }
            }
        }

        return $shipping_cost;
    }

    /**
     * Unify parameters
     */
    protected function unifyParams($params)
    {
        $unified = $params;

        $mappings = [
            'postal_code' => 'zip',
            'payment_gw' => 'payment_provider',
            'payment_method' => 'payment_provider',
            'payment_method_id' => 'payment_provider_id',
            'shipping_gw' => 'shipping_provider',
            'shipping_method' => 'shipping_provider',
            'shipping_method_id' => 'shipping_provider_id'
        ];

        foreach ($mappings as $old => $new) {
            if (isset($unified[$old])) {
                $unified[$new] = $unified[$old];
                unset($unified[$old]);
            }
        }

        return $unified;
    }

    /**
     * Validate cart
     */
    protected function validateCart()
    {
        $cart = $this->app->shop_manager->get_cart([
            'session_id' => $this->app->user_manager->session_id(),
            'order_completed' => 0,
            'for_checkout' => true
        ]);

        if (!is_array($cart)) {
            return ['error' => 'Your cart is empty'];
        }

        return $cart;
    }

    /**
     * Validate checkout data
     */
    protected function validateCheckoutData($data)
    {
        $errors = [];

        // Validate required fields
        if (get_option('shop_require_email', 'website') == 1) {
            if (empty($data['email'])) {
                $errors['email'] = 'Email is required';
            }
        }

        if (get_option('shop_require_first_name', 'website') == 1) {
            if (empty($data['first_name'])) {
                $errors['first_name'] = 'First name is required';
            }
        }

        // Validate terms acceptance
        if (get_option('shop_require_terms', 'website') == 1) {
            $user_id_or_email = $this->app->user_manager->id() ?: ($data['email'] ?? null);

            if (!$user_id_or_email) {
                $errors['email'] = 'You must provide email address';
            } else {
                $terms_accepted = $this->app->user_manager->terms_check('terms_shop', $user_id_or_email);
                if (!$terms_accepted && empty($data['terms'])) {
                    $errors['terms'] = 'You must agree to terms and conditions';
                }
            }
        }

        return !empty($errors) ? ['error' => $errors] : true;
    }

    /**
     * Prepare order data
     */
    protected function prepareOrderData($data)
    {
        $order_reference_id = 'ORD-' . crc32(uniqid(time()));
        //check if exists
        $check = Order::where('order_reference_id', $order_reference_id)->first();
        if ($check) {
            $order_reference_id = 'ORD-' . crc32(uniqid(time()) . rand());
        }

        $orderData = [
            'session_id' => $this->app->user_manager->session_id(),
            'order_status' => 'new',
            'order_completed' => 0,
            'is_paid' => 0,
            'currency' => $this->app->option_manager->get('currency', 'payments') ?: 'USD',
            'amount' => $this->app->shop_manager->cart_total(),
            'shipping_amount' => $this->getShippingCost($data),
            'items_count' => $this->app->shop_manager->cart_sum(false),
            'order_reference_id' => $order_reference_id,
            'payment_verify_token' => md5(uniqid(time())),
        ];

        // Add customer fields
        $fields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip', 'country'];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $orderData[$field] = $data[$field];
            }
        }

        if (isset($data['shipping_provider_id'])) {
            $orderData['shipping_provider_id'] = $data['shipping_provider_id'];
        }

        if (isset($data['shipping_provider'])) {
            $orderData['shipping_provider'] = $data['shipping_provider'];
        }

        if (get_option('enable_taxes', 'shop') == 1) {
            $orderData['taxes_amount'] = $this->app->cart_manager->get_tax();
        }

        return $orderData;
    }

    /**
     * Process payment
     */
    protected function processPayment($providerId, &$orderData)
    {
        $provider = $this->app->payment_method_manager->getProviderById($providerId);
        if (!$provider) {
            return ['error' => 'Invalid payment provider'];
        }

        $orderData['payment_provider'] = $provider['provider'];
        $orderData['payment_provider_id'] = $providerId;

        // Generate payment URLs
        $encrypter = new \Illuminate\Encryption\Encrypter(
            md5(config('app.key') . $orderData['payment_verify_token']),
            config('app.cipher')
        );

        $enc_key_hash = $encrypter->encrypt(md5($orderData['payment_verify_token']));

        $orderData['returnUrl'] = route('checkout.payment.return', [
            'mw_payment_success' => 1,
            'order_reference_id' => $orderData['order_reference_id'],
            'payment_verify_token' => $orderData['payment_verify_token'],
            '_vkey_url' => $enc_key_hash
        ]);

        $orderData['cancelUrl'] = route('checkout.payment.cancel', [
            'mw_payment_failure' => 1,
            'order_reference_id' => $orderData['order_reference_id'],
            'payment_verify_token' => $enc_key_hash,
            'recart' => $orderData['session_id']
        ]);

        $orderData['notifyUrl'] = route('checkout.payment.notify', [
            'order_reference_id' => $orderData['order_reference_id'],
            'payment_verify_token' => $orderData['payment_verify_token'],
            '_vkey_url' => $enc_key_hash
        ]);

        return $this->app->payment_method_manager->process($providerId, $orderData);
    }
}
