<?php

namespace Modules\Checkout\Services;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use MicroweberPackages\Utils\Mail\MailSender;
use Modules\Payment\Drivers\AbstractPaymentMethod;
use Modules\Shipping\Models\ShippingProvider;

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

        $return = [
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order,
        ];

        if (isset($orderData['redirect']) and $orderData['redirect']) {
            $return['redirect'] = $orderData['redirect'];
        }
        $orderKeys = ['order_completed', 'order_status', 'currency', 'amount', 'shipping_amount', 'items_count', 'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip', 'country', 'shipping_provider_id', 'payment_provider_id', 'taxes_amount', 'transaction_id'];

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
            $this->app->shop_manager->update_quantities($orderId);

            $order->is_paid = 1;
            $order->save();
        }
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
