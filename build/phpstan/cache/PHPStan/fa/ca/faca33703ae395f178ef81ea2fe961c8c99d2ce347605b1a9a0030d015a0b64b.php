<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/SetupIntent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\SetupIntent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e7c5ad18029f7687dd58425738bb42d89a3817c0d31e8ec6d6fa36833f3afdfa-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\SetupIntent',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/SetupIntent.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\SetupIntent',
    'shortName' => 'SetupIntent',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A SetupIntent guides you through the process of setting up and saving a customer\'s payment credentials for future payments.
 * For example, you can use a SetupIntent to set up and save your customer\'s card without immediately collecting a payment.
 * Later, you can use <a href="https://stripe.com/docs/api#payment_intents">PaymentIntents</a> to drive the payment flow.
 *
 * Create a SetupIntent when you\'re ready to collect your customer\'s payment credentials.
 * Don\'t maintain long-lived, unconfirmed SetupIntents because they might not be valid.
 * The SetupIntent transitions through multiple <a href="https://docs.stripe.com/payments/intents#intent-statuses">statuses</a> as it guides
 * you through the setup process.
 *
 * Successful SetupIntents result in payment credentials that are optimized for future payments.
 * For example, cardholders in <a href="https://stripe.com/guides/strong-customer-authentication">certain regions</a> might need to be run through
 * <a href="https://docs.stripe.com/strong-customer-authentication">Strong Customer Authentication</a> during payment method collection
 * to streamline later <a href="https://docs.stripe.com/payments/setup-intents">off-session payments</a>.
 * If you use the SetupIntent with a <a href="https://stripe.com/docs/api#setup_intent_object-customer">Customer</a>,
 * it automatically attaches the resulting payment method to that Customer after successful setup.
 * We recommend using SetupIntents or <a href="https://stripe.com/docs/api#payment_intent_object-setup_future_usage">setup_future_usage</a> on
 * PaymentIntents to save payment methods to prevent saving invalid or unoptimized payment methods.
 *
 * By using SetupIntents, you can reduce friction for your customers, even as regulations change over time.
 *
 * Related guide: <a href="https://docs.stripe.com/payments/setup-intents">Setup Intents API</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|Application|string $application ID of the Connect application that created the SetupIntent.
 * @property null|bool $attach_to_self <p>If present, the SetupIntent\'s payment method will be attached to the in-context Stripe Account.</p><p>It can only be used for this Stripe Account’s own money movement flows like InboundTransfer and OutboundTransfers. It cannot be set to true when setting up a PaymentMethod for a Customer, and defaults to false when attaching a PaymentMethod to a Customer.</p>
 * @property null|(object{allow_redirects?: string, enabled: null|bool}&StripeObject) $automatic_payment_methods Settings for dynamic payment methods compatible with this Setup Intent
 * @property null|string $cancellation_reason Reason for cancellation of this SetupIntent, one of <code>abandoned</code>, <code>requested_by_customer</code>, or <code>duplicate</code>.
 * @property null|string $client_secret <p>The client secret of this SetupIntent. Used for client-side retrieval using a publishable key.</p><p>The client secret can be used to complete payment setup from your frontend. It should not be stored, logged, or exposed to anyone other than the customer. Make sure that you have TLS enabled on any page that includes the client secret.</p>
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|Customer|string $customer <p>ID of the Customer this SetupIntent belongs to, if one exists.</p><p>If present, the SetupIntent\'s payment method will be attached to the Customer on successful setup. Payment methods attached to other Customers cannot be used with this SetupIntent.</p>
 * @property null|string $description An arbitrary string attached to the object. Often useful for displaying to users.
 * @property null|string[] $flow_directions <p>Indicates the directions of money movement for which this payment method is intended to be used.</p><p>Include <code>inbound</code> if you intend to use the payment method as the origin to pull funds from. Include <code>outbound</code> if you intend to use the payment method as the destination to send funds to. You can include both if you intend to use the payment method for both purposes.</p>
 * @property null|(object{advice_code?: string, charge?: string, code?: string, decline_code?: string, doc_url?: string, message?: string, network_advice_code?: string, network_decline_code?: string, param?: string, payment_intent?: PaymentIntent, payment_method?: PaymentMethod, payment_method_type?: string, request_log_url?: string, setup_intent?: SetupIntent, source?: Account|BankAccount|Card|Source, type: string}&StripeObject) $last_setup_error The error encountered in the previous SetupIntent confirmation.
 * @property null|SetupAttempt|string $latest_attempt The most recent SetupAttempt for this SetupIntent.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|Mandate|string $mandate ID of the multi use Mandate generated by the SetupIntent.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|(object{cashapp_handle_redirect_or_display_qr_code?: (object{hosted_instructions_url: string, mobile_auth_url: string, qr_code: (object{expires_at: int, image_url_png: string, image_url_svg: string}&StripeObject)}&StripeObject), redirect_to_url?: (object{return_url: null|string, url: null|string}&StripeObject), type: string, use_stripe_sdk?: StripeObject, verify_with_microdeposits?: (object{arrival_date: int, hosted_verification_url: string, microdeposit_type: null|string}&StripeObject)}&StripeObject) $next_action If present, this property tells you what actions you need to take in order for your customer to continue payment setup.
 * @property null|Account|string $on_behalf_of The account (if any) for which the setup is intended.
 * @property null|PaymentMethod|string $payment_method ID of the payment method used with this SetupIntent. If the payment method is <code>card_present</code> and isn\'t a digital wallet, then the <a href="https://docs.stripe.com/api/setup_attempts/object#setup_attempt_object-payment_method_details-card_present-generated_card">generated_card</a> associated with the <code>latest_attempt</code> is attached to the Customer instead.
 * @property null|(object{id: string, parent: null|string}&StripeObject) $payment_method_configuration_details Information about the <a href="https://stripe.com/docs/api/payment_method_configurations">payment method configuration</a> used for this Setup Intent.
 * @property null|(object{acss_debit?: (object{currency: null|string, mandate_options?: (object{custom_mandate_url?: string, default_for?: string[], interval_description: null|string, payment_schedule: null|string, transaction_type: null|string}&StripeObject), verification_method?: string}&StripeObject), amazon_pay?: (object{}&StripeObject), bacs_debit?: (object{mandate_options?: (object{reference_prefix?: string}&StripeObject)}&StripeObject), card?: (object{mandate_options: null|(object{amount: int, amount_type: string, currency: string, description: null|string, end_date: null|int, interval: string, interval_count: null|int, reference: string, start_date: int, supported_types: null|string[]}&StripeObject), network: null|string, request_three_d_secure: null|string}&StripeObject), card_present?: (object{}&StripeObject), klarna?: (object{currency: null|string, preferred_locale: null|string}&StripeObject), link?: (object{persistent_token: null|string}&StripeObject), paypal?: (object{billing_agreement_id: null|string}&StripeObject), sepa_debit?: (object{mandate_options?: (object{reference_prefix?: string}&StripeObject)}&StripeObject), us_bank_account?: (object{financial_connections?: (object{filters?: (object{account_subcategories?: string[]}&StripeObject), permissions?: string[], prefetch: null|string[], return_url?: string}&StripeObject), mandate_options?: (object{collection_method?: string}&StripeObject), verification_method?: string}&StripeObject)}&StripeObject) $payment_method_options Payment method-specific configuration for this SetupIntent.
 * @property string[] $payment_method_types The list of payment method types (e.g. card) that this SetupIntent is allowed to set up. A list of valid payment method types can be found <a href="https://docs.stripe.com/api/payment_methods/object#payment_method_object-type">here</a>.
 * @property null|Mandate|string $single_use_mandate ID of the single_use Mandate generated by the SetupIntent.
 * @property string $status <a href="https://stripe.com/docs/payments/intents#intent-statuses">Status</a> of this SetupIntent, one of <code>requires_payment_method</code>, <code>requires_confirmation</code>, <code>requires_action</code>, <code>processing</code>, <code>canceled</code>, or <code>succeeded</code>.
 * @property string $usage <p>Indicates how the payment method is intended to be used in the future.</p><p>Use <code>on_session</code> if you intend to only reuse the payment method when the customer is in your checkout flow. Use <code>off_session</code> if your customer may or may not be in your checkout flow. If not provided, this value defaults to <code>off_session</code>.</p>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 56,
    'endLine' => 215,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\Update',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'setup_intent\'',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 27,
            'startFilePos' => 9683,
            'endTokenPos' => 27,
            'endFilePos' => 9696,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'CANCELLATION_REASON_ABANDONED' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'CANCELLATION_REASON_ABANDONED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'abandoned\'',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 41,
            'startFilePos' => 9773,
            'endTokenPos' => 41,
            'endFilePos' => 9783,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'CANCELLATION_REASON_DUPLICATE' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'CANCELLATION_REASON_DUPLICATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'duplicate\'',
          'attributes' => 
          array (
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 50,
            'startFilePos' => 9828,
            'endTokenPos' => 50,
            'endFilePos' => 9838,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'CANCELLATION_REASON_REQUESTED_BY_CUSTOMER' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'CANCELLATION_REASON_REQUESTED_BY_CUSTOMER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'requested_by_customer\'',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 59,
            'startFilePos' => 9895,
            'endTokenPos' => 59,
            'endFilePos' => 9917,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 78,
      ),
      'STATUS_CANCELED' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'STATUS_CANCELED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'canceled\'',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 68,
            'startFilePos' => 9949,
            'endTokenPos' => 68,
            'endFilePos' => 9958,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_PROCESSING' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'STATUS_PROCESSING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'processing\'',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 67,
            'startTokenPos' => 77,
            'startFilePos' => 9991,
            'endTokenPos' => 77,
            'endFilePos' => 10002,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'STATUS_REQUIRES_ACTION' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'STATUS_REQUIRES_ACTION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'requires_action\'',
          'attributes' => 
          array (
            'startLine' => 68,
            'endLine' => 68,
            'startTokenPos' => 86,
            'startFilePos' => 10040,
            'endTokenPos' => 86,
            'endFilePos' => 10056,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'STATUS_REQUIRES_CONFIRMATION' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'STATUS_REQUIRES_CONFIRMATION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'requires_confirmation\'',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 95,
            'startFilePos' => 10100,
            'endTokenPos' => 95,
            'endFilePos' => 10122,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 65,
      ),
      'STATUS_REQUIRES_PAYMENT_METHOD' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'STATUS_REQUIRES_PAYMENT_METHOD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'requires_payment_method\'',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 104,
            'startFilePos' => 10168,
            'endTokenPos' => 104,
            'endFilePos' => 10192,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 69,
      ),
      'STATUS_SUCCEEDED' => 
      array (
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'name' => 'STATUS_SUCCEEDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'succeeded\'',
          'attributes' => 
          array (
            'startLine' => 71,
            'endLine' => 71,
            'startTokenPos' => 113,
            'startFilePos' => 10224,
            'endTokenPos' => 113,
            'endFilePos' => 10234,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 71,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 87,
                'endLine' => 87,
                'startTokenPos' => 130,
                'startFilePos' => 15176,
                'endTokenPos' => 130,
                'endFilePos' => 15179,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 87,
                'endLine' => 87,
                'startTokenPos' => 137,
                'startFilePos' => 15193,
                'endTokenPos' => 137,
                'endFilePos' => 15196,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 51,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a SetupIntent object.
 *
 * After you create the SetupIntent, attach a payment method and <a
 * href="/docs/api/setup_intents/confirm">confirm</a> it to collect any required
 * permissions to charge the payment method later.
 *
 * @param null|array{attach_to_self?: bool, automatic_payment_methods?: array{allow_redirects?: string, enabled: bool}, confirm?: bool, confirmation_token?: string, customer?: string, description?: string, expand?: string[], flow_directions?: string[], mandate_data?: null|array{customer_acceptance: array{accepted_at?: int, offline?: array{}, online?: array{ip_address: string, user_agent: string}, type: string}}, metadata?: array<string, string>, on_behalf_of?: string, payment_method?: string, payment_method_configuration?: string, payment_method_data?: array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, cashapp?: array{}, crypto?: array{}, customer_balance?: array{}, eps?: array{bank?: string}, fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, grabpay?: array{}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, paynow?: array{}, paypal?: array{}, pix?: array{}, promptpay?: array{}, radar_options?: array{session?: string}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, sofort?: array{country: string}, swish?: array{}, twint?: array{}, type: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}}, payment_method_options?: array{acss_debit?: array{currency?: string, mandate_options?: array{custom_mandate_url?: null|string, default_for?: string[], interval_description?: string, payment_schedule?: string, transaction_type?: string}, verification_method?: string}, amazon_pay?: array{}, bacs_debit?: array{mandate_options?: array{reference_prefix?: null|string}}, card?: array{mandate_options?: array{amount: int, amount_type: string, currency: string, description?: string, end_date?: int, interval: string, interval_count?: int, reference: string, start_date: int, supported_types?: string[]}, moto?: bool, network?: string, request_three_d_secure?: string, three_d_secure?: array{ares_trans_status?: string, cryptogram?: string, electronic_commerce_indicator?: string, network_options?: array{cartes_bancaires?: array{cb_avalgo: string, cb_exemption?: string, cb_score?: int}}, requestor_challenge_indicator?: string, transaction_id?: string, version?: string}}, card_present?: array{}, klarna?: array{currency?: string, on_demand?: array{average_amount?: int, maximum_amount?: int, minimum_amount?: int, purchase_interval?: string, purchase_interval_count?: int}, preferred_locale?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing: array{amount: int, date: string}, reference: string}[]}, link?: array{persistent_token?: string}, paypal?: array{billing_agreement_id?: string}, sepa_debit?: array{mandate_options?: array{reference_prefix?: null|string}}, us_bank_account?: array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[], return_url?: string}, mandate_options?: array{collection_method?: null|string}, networks?: array{requested?: string[]}, verification_method?: string}}, payment_method_types?: string[], return_url?: string, single_use?: array{amount: int, currency: string}, usage?: string, use_stripe_sdk?: bool} $params
 * @param null|array|string $options
 *
 * @return SetupIntent the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 87,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 234,
                'startFilePos' => 15991,
                'endTokenPos' => 234,
                'endFilePos' => 15994,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 241,
                'startFilePos' => 16005,
                'endTokenPos' => 241,
                'endFilePos' => 16008,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 48,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a list of SetupIntents.
 *
 * @param null|array{attach_to_self?: bool, created?: array|int, customer?: string, ending_before?: string, expand?: string[], limit?: int, payment_method?: string, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<SetupIntent> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 109,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
      'retrieve' => 
      array (
        'name' => 'retrieve',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 133,
            'endLine' => 133,
            'startColumn' => 37,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 133,
                'endLine' => 133,
                'startTokenPos' => 297,
                'startFilePos' => 16909,
                'endTokenPos' => 297,
                'endFilePos' => 16912,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 133,
            'endLine' => 133,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves the details of a SetupIntent that has previously been created.
 *
 * Client-side retrieval using a publishable key is allowed when the
 * <code>client_secret</code> is provided in the query string.
 *
 * When retrieved with a publishable key, only a subset of properties will be
 * returned. Please refer to the <a href="#setup_intent_object">SetupIntent</a>
 * object reference for more details.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return SetupIntent
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 133,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 35,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 153,
                'endLine' => 153,
                'startTokenPos' => 360,
                'startFilePos' => 21446,
                'endTokenPos' => 360,
                'endFilePos' => 21449,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 40,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 153,
                'endLine' => 153,
                'startTokenPos' => 367,
                'startFilePos' => 21460,
                'endTokenPos' => 367,
                'endFilePos' => 21463,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 56,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Updates a SetupIntent object.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{attach_to_self?: bool, customer?: string, description?: string, expand?: string[], flow_directions?: string[], metadata?: null|array<string, string>, payment_method?: string, payment_method_configuration?: string, payment_method_data?: array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, cashapp?: array{}, crypto?: array{}, customer_balance?: array{}, eps?: array{bank?: string}, fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, grabpay?: array{}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, paynow?: array{}, paypal?: array{}, pix?: array{}, promptpay?: array{}, radar_options?: array{session?: string}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, sofort?: array{country: string}, swish?: array{}, twint?: array{}, type: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}}, payment_method_options?: array{acss_debit?: array{currency?: string, mandate_options?: array{custom_mandate_url?: null|string, default_for?: string[], interval_description?: string, payment_schedule?: string, transaction_type?: string}, verification_method?: string}, amazon_pay?: array{}, bacs_debit?: array{mandate_options?: array{reference_prefix?: null|string}}, card?: array{mandate_options?: array{amount: int, amount_type: string, currency: string, description?: string, end_date?: int, interval: string, interval_count?: int, reference: string, start_date: int, supported_types?: string[]}, moto?: bool, network?: string, request_three_d_secure?: string, three_d_secure?: array{ares_trans_status?: string, cryptogram?: string, electronic_commerce_indicator?: string, network_options?: array{cartes_bancaires?: array{cb_avalgo: string, cb_exemption?: string, cb_score?: int}}, requestor_challenge_indicator?: string, transaction_id?: string, version?: string}}, card_present?: array{}, klarna?: array{currency?: string, on_demand?: array{average_amount?: int, maximum_amount?: int, minimum_amount?: int, purchase_interval?: string, purchase_interval_count?: int}, preferred_locale?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing: array{amount: int, date: string}, reference: string}[]}, link?: array{persistent_token?: string}, paypal?: array{billing_agreement_id?: string}, sepa_debit?: array{mandate_options?: array{reference_prefix?: null|string}}, us_bank_account?: array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[], return_url?: string}, mandate_options?: array{collection_method?: null|string}, networks?: array{requested?: string[]}, verification_method?: string}}, payment_method_types?: string[]} $params
 * @param null|array|string $opts
 *
 * @return SetupIntent the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 153,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
      'cancel' => 
      array (
        'name' => 'cancel',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 173,
                'endLine' => 173,
                'startTokenPos' => 463,
                'startFilePos' => 22040,
                'endTokenPos' => 463,
                'endFilePos' => 22043,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 173,
            'endLine' => 173,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 173,
                'endLine' => 173,
                'startTokenPos' => 470,
                'startFilePos' => 22054,
                'endTokenPos' => 470,
                'endFilePos' => 22057,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 173,
            'endLine' => 173,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return SetupIntent the canceled setup intent
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 173,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
      'confirm' => 
      array (
        'name' => 'confirm',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 190,
                'endLine' => 190,
                'startTokenPos' => 547,
                'startFilePos' => 22528,
                'endTokenPos' => 547,
                'endFilePos' => 22531,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 190,
            'endLine' => 190,
            'startColumn' => 29,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 190,
                'endLine' => 190,
                'startTokenPos' => 554,
                'startFilePos' => 22542,
                'endTokenPos' => 554,
                'endFilePos' => 22545,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 190,
            'endLine' => 190,
            'startColumn' => 45,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return SetupIntent the confirmed setup intent
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 190,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
      'verifyMicrodeposits' => 
      array (
        'name' => 'verifyMicrodeposits',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 207,
                'endLine' => 207,
                'startTokenPos' => 631,
                'startFilePos' => 23028,
                'endTokenPos' => 631,
                'endFilePos' => 23031,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 207,
            'endLine' => 207,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 207,
                'endLine' => 207,
                'startTokenPos' => 638,
                'startFilePos' => 23042,
                'endTokenPos' => 638,
                'endFilePos' => 23045,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 207,
            'endLine' => 207,
            'startColumn' => 57,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return SetupIntent the verified setup intent
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 207,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SetupIntent',
        'implementingClassName' => 'Stripe\\SetupIntent',
        'currentClassName' => 'Stripe\\SetupIntent',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));