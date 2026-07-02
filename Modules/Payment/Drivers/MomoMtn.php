<?php

namespace Modules\Payment\Drivers;

use Modules\Payment\Drivers\AbstractPaymentMethod;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Notifications\Notification;
use Omnipay\Omnipay;
use Omnipay\MoMoMtn\Gateway;

class MomoMtn extends AbstractPaymentMethod
{
    public string $provider = 'momomtn';

    public function title(): string
    {
        return 'MoMo MTN Africa';
    }

    public function description(): string
    {
        return 'Accept payments via MTN Mobile Money using Omnipay';
    }

    private function getGateway()
    {
        $model = $this->getModel();

        $gateway = Omnipay::create('MoMoMtn');

        if ($model && $model->settings) {
            $gateway->initialize([
                'apiUserId' => $model->settings['api_user_id'] ?? '',
                'apiKey' => $model->settings['api_key'] ?? '',
                'subscriptionKey' => $model->settings['subscription_key'] ?? '',
                'targetEnvironment' => $model->settings['target_environment'] ?? 'sandbox',
                'callbackHost' => $model->settings['callback_host'] ?? 'webhook.site',
                'testMode' => ($model->settings['target_environment'] ?? 'sandbox') === 'sandbox'
            ]);
        }

        return $gateway;
    }

    public function getSettingsForm(): array
    {
        return [
            \Filament\Schemas\Components\Section::make('MoMo MTN Configuration')
                ->description('Configure your MoMo MTN payment gateway settings. For sandbox testing, you can generate API credentials automatically.')
                ->schema([
                    Forms\Components\TextInput::make('settings.subscription_key')
                        ->label('Collection Subscription Key')
                        ->required()
                        ->helperText('Your Collection API subscription key (Primary or Secondary) from MoMo developer profile.'),

                    Forms\Components\Select::make('settings.target_environment')
                        ->label('Target Environment')
                        ->options([
                            'sandbox' => 'Sandbox (Testing)',
                            'production' => 'Production (Live)'
                        ])
                        ->default('sandbox')
                        ->required()
                        ->helperText('Select sandbox for testing or production for live transactions.')
                        ->live(),

                    \Filament\Schemas\Components\Actions::make([
                        \Filament\Actions\Action::make('generateCredentials')
                            ->label('Generate Sandbox Credentials')
                            ->icon('heroicon-o-key')
                            ->color('success')
                            ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('settings.target_environment') === 'sandbox')
                            ->action(function (\Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, $state) {
                                $subscriptionKey = $get('settings.subscription_key');

                                if (empty($subscriptionKey)) {
                                    throw new \Exception('Please enter your subscription key first');
                                }

                                $credentials = $this->createSandboxCredentials($subscriptionKey);

                                if ($credentials) {
                                    $set('settings.api_user_id', $credentials['api_user_id']);
                                    $set('settings.api_key', $credentials['api_key']);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Sandbox Credentials Generated Successfully!')
                                        ->body('API User ID and API Key have been automatically generated and filled in.')
                                        ->success()
                                        ->send();
                                } else {
                                    throw new \Exception('Failed to generate credentials. Check your subscription key and try again.');
                                }
                            })
                            ->modalHeading('Generate Sandbox Credentials')
                            ->modalDescription('This will create new API User ID and API Key for sandbox testing. Make sure your subscription key is correct.')
                            ->modalSubmitActionLabel('Generate')
                            ->requiresConfirmation(),

                        \Filament\Actions\Action::make('manualCredentials')
                            ->label('Manual Credential Setup')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->color('info')
                            ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('settings.target_environment') === 'sandbox')
                            ->action(function () {
                                \Filament\Notifications\Notification::make()
                                    ->title('Manual Credential Creation')
                                    ->body('Use the curl commands shown below to create API credentials manually, then paste them into the form.')
                                    ->info()
                                    ->persistent()
                                    ->send();
                            })
                    ])
                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('settings.target_environment') === 'sandbox'),

                    Forms\Components\TextInput::make('settings.api_user_id')
                        ->label('API User ID')
                        ->required()
                        ->helperText('Your MoMo API User ID (UUID format). For sandbox, use the generate button above.'),

                    Forms\Components\TextInput::make('settings.api_key')
                        ->label('API Key')
                      //  ->required()
                        ->helperText('Your MoMo API key. For sandbox, use the generate button above.'),

                    Forms\Components\TextInput::make('settings.callback_host')
                        ->label('Callback Host')
                        ->default('webhook.site')
                        ->helperText('Callback host for API user creation. Default: webhook.site'),

                    Forms\Components\Placeholder::make('sandbox_info')
                        ->content('
                            **Sandbox Setup Instructions:**

                            **Subscribe to Sandbox Provisioning API**
                            1. Go to momodeveloper.mtn.com → Products
                            2. Subscribe to "Sandbox Provisioning API" (separate from Collection API)
                            3. Use same subscription key and click "Generate Sandbox Credentials"


                            **Test Phone Numbers (Sandbox):**
                            - 56733123453: SUCCESS
                            - 46733123450: FAILED
                            - 46733123451: REJECTED
                            - 46733123452: TIMEOUT
                            - 46733123454: PENDING
                        ')
                        ->columnSpanFull()
                ]),
        ];
    }

    public function getForm(): array
    {

        $gateway = $this->getGateway();
        return [
            \Filament\Schemas\Components\Section::make()
                ->schema(function (\Filament\Schemas\Components\Section $component, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, mixed $state = null) use ($gateway) {
                    return [
                        Forms\Components\TextInput::make('payer_phone')
                            ->label('Mobile Money Phone Number')
                            ->required()
                            ->tel()
                            ->helperText('Enter your mobile money phone number (e.g., 256XXXXXXXXX).')
                            ->placeholder('256XXXXXXXXX'),




                        Forms\Components\Placeholder::make('')
                            ->visible(function () use ($gateway) {
                                return $gateway instanceof Gateway && $gateway->getTestMode();
                            })
                            ->content('For sandbox testing, use: 56733123453 (Success), 46733123450 (Failed), 46733123451 (Rejected), 46733123452 (Timeout), 46733123454 (Pending)'),



                        Forms\Components\Placeholder::make('')
                            ->content('You will receive a payment request on your mobile phone to complete the transaction. For sandbox testing, all transactions use EUR currency.')

                    ];
                })
        ];
    }

    public function process($data = []): array
    {
        $model = $this->getModel();
        if (!$model || !$model->settings) {
            return [
                'success' => false,
                'error' => 'MoMo MTN is not configured properly',
            ];
        }

        // Validate required configuration fields
        $requiredSettings = ['subscription_key', 'api_user_id', 'api_key', 'target_environment'];
        foreach ($requiredSettings as $field) {
            if (empty($model->settings[$field])) {
                return [
                    'success' => false,
                    'error' => "Missing required configuration: {$field}",
                ];
            }
        }

        // Validate required data - use the dedicated payer_phone field
        if (empty($data['payer_phone'])) {
            return [
                'success' => false,
                'error' => 'Mobile Money phone number is required for MoMo payment'
            ];
        }

        // Validate amount
        if (!isset($data['amount']) || empty($data['amount'])) {
            return [
                'success' => false,
                'error' => 'Payment amount is required for MoMo payment'
            ];
        }

        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            return [
                'success' => false,
                'error' => 'Payment amount must be a positive number'
            ];
        }

        try {
            $gateway = $this->getGateway();

            // Prepare payment data
            $paymentData = [
                'amount' => number_format($data['amount'], 2, '.', ''),
                'currency' => 'EUR', // MoMo sandbox requires EUR
                'payerPhone' => $data['payer_phone'],
                'payerMessage' => $data['customer_name'] ?? 'Payment',
                'payeeNote' => 'Order #' . ($data['id'] ?? 'N/A'),
                'externalId' => (string) Str::uuid()
            ];

            // Process payment using Omnipay
            $response = $gateway->purchase($paymentData)->send();

            if ($response->isSuccessful()) {
                return [
                    'success' => true,
                    'transactionId' => $response->getTransactionReference(),
                    'message' => $response->getMessage(),
                    'providerResponse' => [
                        'referenceId' => $response->getTransactionReference(),
                        'statusCode' => $response->getCode()
                    ],
                ];
            }

            // Handle failure
            return [
                'success' => false,
                'error' => $response->getMessage(),
                'providerResponse' => [
                    'statusCode' => $response->getCode(),
                    'response' => $response->getData()
                ],
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function createSandboxCredentials($subscriptionKey): array
    {
        try {
            $gateway = Omnipay::create('MoMoMtn');
            $gateway->initialize([
                'subscriptionKey' => $subscriptionKey,
                'targetEnvironment' => 'sandbox',
                'callbackHost' => 'webhook.site'
            ]);

            // Create API User
            $userResponse = $gateway->createApiUser([
                'subscriptionKey' => $subscriptionKey,
                'callbackHost' => 'webhook.site'
            ])->send();

            if (!$userResponse->isSuccessful()) {
                return [
                    'success' => false,
                    'error' => 'Failed to create API user: ' . $userResponse->getMessage()
                ];
            }

            $apiUserId = $userResponse->getApiUserId();

            // Create API Key
            $keyResponse = $gateway->createApiKey([
                'apiUserId' => $apiUserId,
                'subscriptionKey' => $subscriptionKey
            ])->send();

            if (!$keyResponse->isSuccessful()) {
                return [
                    'success' => false,
                    'error' => 'Failed to create API key: ' . $keyResponse->getMessage()
                ];
            }

            return [
                'success' => true,
                'api_user_id' => $apiUserId,
                'api_key' => $keyResponse->getApiKey()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

