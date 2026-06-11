<?php

namespace Modules\Payment\Drivers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Modules\Payment\Enums\PaymentStatus;
use Omnipay\Omnipay;
use Stripe\StripeClient;

class Stripe extends AbstractPaymentMethod
{
public string $provider = 'stripe';
private $gateway;
private ?StripeClient $stripeClient = null;

public function logo(): string
{
return asset('modules/payment/img/stripe.png');
}

public function __construct()
{
$this->gateway = Omnipay::create('Stripe\Checkout');

// Set API keys if available
$model = $this->getModel();
if ($model && isset($model->settings['secret_key'])) {
$this->gateway->setApiKey($model->settings['secret_key']);
$this->stripeClient = new StripeClient($model->settings['secret_key']);
}
}

/**
* Get the Stripe client instance
*
* @return StripeClient|null
*/
public function getStripeClient(): ?StripeClient
{
if (!$this->stripeClient) {
$model = $this->getModel();
if ($model && isset($model->settings['secret_key'])) {
$this->stripeClient = new StripeClient($model->settings['secret_key']);
}
}
return $this->stripeClient;
}

public function title(): string
{
return 'Stripe';
}

public function getSettingsForm(): array
{
return [
Select::make('settings.payment_method')
->label('Payment Method')
->options([
'checkout' => 'Stripe Checkout (Hosted)',
'payment_intent' => 'Payment Intents API (Embedded)',
])
->default('checkout')
->helperText('Choose how customers will complete payments'),

TextInput::make('settings.publishable_key')
->label('Publishable Key')
->required()
->helperText('Your Stripe publishable key from the Stripe dashboard'),

TextInput::make('settings.secret_key')
->label('Secret Key')
->required()
->password()
->helperText('Your Stripe secret key from the Stripe dashboard'),

TextInput::make('settings.webhook_secret')
->label('Webhook Secret')
->required()
->password()
->helperText('REQUIRED: Your Stripe webhook signing secret (whsec_...) from Stripe Dashboard > Webhooks. Webhooks will be rejected without this.'),

Toggle::make('settings.collect_phone_number')
->label('Collect Phone Number')
->helperText('Enable phone number collection during checkout')
->default(false),

Toggle::make('settings.automatic_capture')
->label('Automatic Capture')
->helperText('Automatically capture payments when authorized')
->default(true),
];
}

public function getForm(): array
{
// No form fields needed as we're using Stripe Checkout or Payment Intents
return [];
}

/**
* Get payment method configuration
*
* @return string
*/
public function getPaymentMethod(): string
{
$model = $this->getModel();
return $model->settings['payment_method'] ?? 'checkout';
}

public function process($data = []): array
{
try {
$model = $this->getModel();
if (!$model || !$model->settings) {
throw new \Exception('Stripe is not configured properly');
}

$paymentMethod = $this->getPaymentMethod();

if ($paymentMethod === 'payment_intent') {
return $this->processPaymentIntent($data);
}

return $this->processCheckout($data);

} catch (\Exception $e) {
return [
'success' => false,
'message' => $e->getMessage(),
];
}
}

/**
* Process payment using Stripe Checkout (hosted page)
*
* @param array $data
* @return array
*/
protected function processCheckout(array $data): array
{
$model = $this->getModel();

// Set API key
$this->gateway->setApiKey($model->settings['secret_key']);

$stripe = $this->getStripeClient();
if (!$stripe) {
throw new \Exception('Stripe client not initialized');
}

$sessionData = [
'customer_email' => $data['email'] ?? null,

'line_items' => [
[
'price_data' => [
'unit_amount' => (int) round($data['amount'] * 100), // in cents
'product_data' => ['name' => $data['order_reference_id'] ?? 'Order'],
'currency' => strtolower($data['currency'] ?? 'usd'),
],
'quantity' => 1,
],
],
'metadata' => [
'order_reference_id' => $data['order_reference_id'] ?? null,
],
'client_reference_id' => $data['order_reference_id'] ?? null,
'mode' => 'payment',
'success_url' => $data['returnUrl'],
'cancel_url' => $data['cancelUrl'],
];

// Add phone number collection if enabled in settings
if (isset($model->settings['collect_phone_number']) && $model->settings['collect_phone_number']) {
$sessionData['phone_number_collection'] = [
'enabled' => true
];
}

$response = $stripe->checkout->sessions->create($sessionData);

if (isset($response['success']) and $response['success'] == false) {
throw new \Exception($response['message']);
}

if (isset($response['url']) and $response['url']) {
return [
'success' => true,
'transactionId' => $response['id'],
'redirectUrl' => $response['url'],
'clientSecret' => $response['client_secret'] ?? null,
'providerResponse' => $response,
];
} else {
throw new \Exception('Failed to create checkout session');
}
}

/**
* Process payment using Payment Intents API (embedded)
*
* @param array $data
* @return array
*/
protected function processPaymentIntent(array $data): array
{
$stripe = $this->getStripeClient();
if (!$stripe) {
throw new \Exception('Stripe client not initialized');
}

$amount = (int) round($data['amount'] * 100); // Convert to cents
$currency = strtolower($data['currency'] ?? 'usd');
$automaticCapture = $this->getAutomaticCapture();

// Create a PaymentIntent
$paymentIntentData = [
'amount' => $amount,
'currency' => $currency,
'metadata' => [
'order_reference_id' => $data['order_reference_id'] ?? null,
'customer_email' => $data['email'] ?? null,
],
];

// Set capture method based on configuration
if (!$automaticCapture) {
$paymentIntentData['capture_method'] = 'manual';
}

// Add customer email if provided
if (!empty($data['email'])) {
// Try to find or create a customer
$customer = $this->getOrCreateCustomer($data['email']);
if ($customer) {
$paymentIntentData['customer'] = $customer['id'];
}
$paymentIntentData['receipt_email'] = $data['email'];
}

// Add payment method if provided
if (!empty($data['payment_method_id'])) {
$paymentIntentData['payment_method'] = $data['payment_method_id'];
$paymentIntentData['confirm'] = true;
}

$response = $stripe->paymentIntents->create($paymentIntentData);

if (!isset($response['id'])) {
throw new \Exception('Failed to create payment intent');
}

return [
'success' => true,
'transactionId' => $response['id'],
'clientSecret' => $response['client_secret'],
'status' => $response['status'],
'requires_action' => in_array($response['status'], ['requires_action', 'requires_confirmation']),
'providerResponse' => $response,
];
}

/**
* Get or create a Stripe customer
*
* @param string $email
* @return array|null
*/
protected function getOrCreateCustomer(string $email): ?array
{
$stripe = $this->getStripeClient();
if (!$stripe) {
return null;
}

try {
// Search for existing customer
$customers = $stripe->customers->search([
'query' => "email:'$email'",
]);

if (!empty($customers['data'])) {
return $customers['data'][0];
}

// Create new customer
return $stripe->customers->create([
'email' => $email,
]);
} catch (\Exception $e) {
// Log error but don't fail the payment
return null;
}
}

/**
* Get automatic capture setting
*
* @return bool
*/
protected function getAutomaticCapture(): bool
{
$model = $this->getModel();
return $model->settings['automatic_capture'] ?? true;
}

public function verifyPayment(array $data): array
{
        try {
            $model = $this->getModel();
            if (!$model || !$model->settings) {
                return [
                    'success' => false,
                    'message' => 'Stripe is not configured properly',
                ];
            }

            $this->gateway->setApiKey($model->settings['secret_key']);

            // Retrieve the checkout session
            $response = $this->gateway->fetchTransaction([
                'transactionReference' => $data['order']['transaction_id']
            ])->send();

            if ($response->isSuccessful()) {

                $session = $response->getData();
                if ($session['payment_status'] === 'paid') {
                    return [
                        'success' => true,
                        'transactionId' => $session['id'],
                        'amount' => $session['amount_total'] / 100, // convert back to whole number
                        'currency' => $session['currency'],
                        'status' => 'completed', // or 'pending'
                        'providerResponse' => $session,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $response->getMessage() ?? 'Payment verification failed',
                'providerResponse' => $response->getData(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
