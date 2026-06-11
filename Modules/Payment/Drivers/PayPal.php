<?php

namespace Modules\Payment\Drivers;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Log;
use Modules\Payment\Enums\PaymentStatus;
use Omnipay\Omnipay;

/**
 * PayPal Express Checkout Driver
 *
 * Implements PayPal Express Checkout using Omnipay with support for:
 * - Express Checkout (redirect to PayPal)
 * - Webhook notifications for automatic payment updates
 * - Order capture and verification
 * - Test/Sandbox mode support
 */
class PayPal extends AbstractPaymentMethod
{
    public string $provider = 'paypal';

    /** @var \Omnipay\PayPal\ExpressGateway|\Omnipay\PayPal\RestGateway */
    protected $gateway;

    /**
     * Get PayPal logo URL
     *
     * @return string
     */
    public function logo(): string
    {
        return asset('modules/payment/img/paypal.png');
    }

    /**
     * Get payment method title
     *
     * @return string
     */
    public function title(): string
    {
        return 'PayPal';
    }

    /**
     * Initialize PayPal gateway
     *
     * @return \Omnipay\Common\GatewayInterface|null
     */
    protected function getGateway(): ?\Omnipay\Common\GatewayInterface
    {
        if (!$this->gateway) {
            $model = $this->getModel();
            if (!$model || !$model->settings) {
                return null;
            }

            $settings = $model->settings;

            // Check for REST API credentials first (preferred)
            if (!empty($settings['client_id']) && !empty($settings['client_secret'])) {
                $this->gateway = Omnipay::create('PayPal_Rest');
                $this->gateway->setClientId($settings['client_id']);
                $this->gateway->setSecret($settings['client_secret']);
            } else {
                // Fall back to Classic API credentials
                $this->gateway = Omnipay::create('PayPal_Express');
                $this->gateway->setUsername($settings['paypal_api_username'] ?? '');
                $this->gateway->setPassword($settings['paypal_api_password'] ?? '');
                $this->gateway->setSignature($settings['paypal_api_signature'] ?? '');
            }

            $this->gateway->setTestMode($settings['test_mode'] ?? true);
        }

        return $this->gateway;
    }

    /**
     * Process payment - create PayPal order and return redirect URL
     *
     * @param array $data Payment data including amount, currency, returnUrl, cancelUrl
     * @return array Response with success flag, transactionId, redirectUrl
     */
    public function process($data = []): array
    {
        try {
            $gateway = $this->getGateway();
            if (!$gateway) {
                return [
                    'success' => false,
                    'message' => 'PayPal is not configured properly',
                ];
            }

            $orderReferenceId = $data['order_reference_id'] ?? uniqid('ORDER-');
            $amount = $data['order']['amount'] ?? $data['amount'] ?? 0;
            $currency = strtoupper($data['order']['currency'] ?? $data['currency'] ?? 'USD');
            $returnUrl = $data['returnUrl'] ?? '';
            $cancelUrl = $data['cancelUrl'] ?? '';
            $notifyUrl = $data['notifyUrl'] ?? '';
            $email = $data['email'] ?? ($data['order']['email'] ?? '');

            // Build purchase data
            $purchaseData = [
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => $currency,
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
                'description' => $data['description'] ?? 'Order Payment',
                'transactionId' => $orderReferenceId,
            ];

            // Add notify URL if provided (for IPN)
            if ($notifyUrl) {
                $purchaseData['notifyUrl'] = $notifyUrl;
            }

            // Add customer email if provided
            if (!empty($email)) {
                $purchaseData['email'] = $email;
            }

            // Add items if provided
            if (!empty($data['items'])) {
                $purchaseData['items'] = $this->formatItems($data['items']);
            }

            Log::info('PayPal: Initiating payment', [
                'order_reference_id' => $orderReferenceId,
                'amount' => $amount,
                'currency' => $currency,
            ]);

            $response = $gateway->purchase($purchaseData)->send();

            if ($response->isRedirect()) {
                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference() ?? $orderReferenceId,
                    'redirectUrl' => $response->getRedirectUrl(),
                    'providerResponse' => $response->getData(),
                ];
            }

            Log::error('PayPal payment initiation failed', [
                'message' => $response->getMessage(),
                'data' => $response->getData(),
            ]);

            return [
                'success' => false,
                'message' => $response->getMessage() ?? 'Payment initialization failed',
                'providerResponse' => $response->getData(),
            ];

        } catch (\Exception $e) {
            Log::error('PayPal payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify and complete payment after PayPal redirect
     *
     * @param array $data Payment data including token (transaction reference), PayerID, amount, currency
     * @return array Response with success flag and payment details
     */
    public function verifyPayment(array $data): array
    {
        try {
            $gateway = $this->getGateway();
            if (!$gateway) {
                return [
                    'success' => false,
                    'message' => 'PayPal is not configured properly',
                ];
            }

            $token = $data['token'] ?? null;
            $payerId = $data['PayerID'] ?? ($data['payer_id'] ?? null);
            $amount = $data['amount'] ?? 0;
            $currency = strtoupper($data['currency'] ?? 'USD');

            if (!$token) {
                return [
                    'success' => false,
                    'message' => 'Payment token is required',
                ];
            }

            Log::info('PayPal: Completing payment', [
                'token' => $token,
            ]);

            $completeData = [
                'transactionReference' => $token,
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => $currency,
            ];

            // Add payer ID if provided
            if ($payerId) {
                $completeData['payerId'] = $payerId;
            }

            $response = $gateway->completePurchase($completeData)->send();

            if ($response->isSuccessful()) {
                $responseData = $response->getData();

                Log::info('PayPal: Payment completed successfully', [
                    'transaction_id' => $response->getTransactionReference(),
                ]);

                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference(),
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => 'completed',
                    'providerResponse' => $responseData,
                ];
            }

            Log::error('PayPal payment completion failed', [
                'message' => $response->getMessage(),
                'data' => $response->getData(),
            ]);

            return [
                'success' => false,
                'message' => $response->getMessage() ?? 'Payment completion failed',
                'providerResponse' => $response->getData(),
            ];

        } catch (\Exception $e) {
            Log::error('PayPal payment verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format items for PayPal order
     *
     * @param array $items Cart items
     * @return array Formatted items for Omnipay
     */
    protected function formatItems(array $items): array
    {
        return array_map(function ($item) {
            return [
                'name' => $item['name'] ?? '',
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'price' => number_format($item['price'] ?? 0, 2, '.', ''),
            ];
        }, $items);
    }

    /**
     * Format customer data for PayPal
     *
     * @param array $customer Customer data
     * @return array Formatted customer data
     */
    protected function formatCustomerData(array $customer): array
    {
        return [
            'firstName' => $customer['first_name'] ?? '',
            'lastName' => $customer['last_name'] ?? '',
            'email' => $customer['email'] ?? '',
            'phone' => $customer['phone'] ?? '',
            'billingAddress1' => $customer['address'] ?? '',
            'billingCity' => $customer['city'] ?? '',
            'billingState' => $customer['state'] ?? '',
            'billingCountry' => $customer['country'] ?? '',
            'billingPostcode' => $customer['zip'] ?? '',
        ];
    }

    /**
     * Get settings form for Filament admin
     *
     * @return array
     */
    public function getSettingsForm(): array
    {
        return [
            Section::make('PayPal Configuration')
                ->description('Configure PayPal Express Checkout settings')
                ->schema([

                    Section::make('REST API Credentials (Recommended)')
                        ->description('Enter your PayPal REST API credentials from the Developer Dashboard')
                        ->schema([
                            TextInput::make('settings.client_id')
                                ->label('Client ID')
                                ->helperText('Your PayPal app Client ID (REST API)')
                                ->placeholder('Enter PayPal Client ID'),

                            TextInput::make('settings.client_secret')
                                ->label('Client Secret')
                                ->password()
                                ->helperText('Your PayPal app Secret (REST API)')
                                ->placeholder('Enter PayPal Client Secret'),
                        ])->collapsible(),

                    Section::make('Classic API Credentials (Legacy)')
                        ->description('Alternatively, use Classic API credentials')
                        ->schema([
                            TextInput::make('settings.paypal_api_username')
                                ->label('API Username')
                                ->helperText('Your PayPal Classic API username')
                                ->placeholder('Enter API Username'),

                            TextInput::make('settings.paypal_api_password')
                                ->label('API Password')
                                ->password()
                                ->helperText('Your PayPal Classic API password')
                                ->placeholder('Enter API Password'),

                            TextInput::make('settings.paypal_api_signature')
                                ->label('API Signature')
                                ->helperText('Your PayPal Classic API signature')
                                ->placeholder('Enter API Signature'),
                        ])->collapsible(),

                    Toggle::make('settings.test_mode')
                        ->label('Test Mode (Sandbox)')
                        ->helperText('Enable to use PayPal Sandbox environment')
                        ->default(true),

                    Select::make('settings.payment_intent')
                        ->label('Payment Intent')
                        ->options([
                            'CAPTURE' => 'Capture Immediately',
                            'AUTHORIZE' => 'Authorize Only (Manual Capture)',
                        ])
                        ->default('CAPTURE')
                        ->helperText('Choose when to capture the payment')
                        ->visible(false),

                    TextInput::make('settings.webhook_id')
                        ->label('Webhook ID')
                        ->required()
                        ->helperText('REQUIRED: Your PayPal Webhook ID from Developer Dashboard > Webhooks. Webhooks will be rejected without this for security.')
                        ->placeholder('Enter webhook ID from PayPal Dashboard'),
                ]),
        ];
    }

    /**
     * Get payment form (for checkout)
     *
     * @return array
     */
    public function getForm(): array
    {
        return [
            Section::make('')
                ->schema([
                    \Filament\Forms\Components\Placeholder::make('paypal_info')
                        ->content('You will be redirected to PayPal to complete your purchase securely.')
                        ->helperText('Click the button below to proceed to PayPal checkout.'),
                ]),
        ];
    }

    /**
     * Get webhook ID from settings
     *
     * @return string|null
     */
    public function getWebhookId(): ?string
    {
        $model = $this->getModel();
        return $model->settings['webhook_id'] ?? null;
    }
}
