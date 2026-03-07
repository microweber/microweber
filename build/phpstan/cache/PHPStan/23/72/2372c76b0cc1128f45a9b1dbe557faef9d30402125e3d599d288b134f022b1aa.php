<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/PaymentMethod.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\PaymentMethod
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-fb21ec882ff5924b149b853c5ff87488ac3a5886c93d3dc9ed549532ff7f806b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\PaymentMethod',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/PaymentMethod.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\PaymentMethod',
    'shortName' => 'PaymentMethod',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * PaymentMethod objects represent your customer\'s payment instruments.
 * You can use them with <a href="https://stripe.com/docs/payments/payment-intents">PaymentIntents</a> to collect payments or save them to
 * Customer objects to store instrument details for future payments.
 *
 * Related guides: <a href="https://stripe.com/docs/payments/payment-methods">Payment Methods</a> and <a href="https://stripe.com/docs/payments/more-payment-scenarios">More Payment Scenarios</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|(object{bank_name: null|string, fingerprint: null|string, institution_number: null|string, last4: null|string, transit_number: null|string}&StripeObject) $acss_debit
 * @property null|(object{}&StripeObject) $affirm
 * @property null|(object{}&StripeObject) $afterpay_clearpay
 * @property null|(object{}&StripeObject) $alipay
 * @property null|string $allow_redisplay This field indicates whether this payment method can be shown again to its customer in a checkout flow. Stripe products such as Checkout and Elements use this field to determine whether a payment method can be shown as a saved payment method in a checkout flow. The field defaults to “unspecified”.
 * @property null|(object{}&StripeObject) $alma
 * @property null|(object{}&StripeObject) $amazon_pay
 * @property null|(object{bsb_number: null|string, fingerprint: null|string, last4: null|string}&StripeObject) $au_becs_debit
 * @property null|(object{fingerprint: null|string, last4: null|string, sort_code: null|string}&StripeObject) $bacs_debit
 * @property null|(object{}&StripeObject) $bancontact
 * @property null|(object{}&StripeObject) $billie
 * @property (object{address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, phone: null|string, tax_id: null|string}&StripeObject) $billing_details
 * @property null|(object{}&StripeObject) $blik
 * @property null|(object{tax_id: string}&StripeObject) $boleto
 * @property null|(object{brand: string, checks: null|(object{address_line1_check: null|string, address_postal_code_check: null|string, cvc_check: null|string}&StripeObject), country: null|string, description?: null|string, display_brand: null|string, exp_month: int, exp_year: int, fingerprint?: null|string, funding: string, generated_from: null|(object{charge: null|string, payment_method_details: null|(object{card_present?: (object{amount_authorized: null|int, brand: null|string, brand_product: null|string, capture_before?: int, cardholder_name: null|string, country: null|string, description?: null|string, emv_auth_data: null|string, exp_month: int, exp_year: int, fingerprint: null|string, funding: null|string, generated_card: null|string, iin?: null|string, incremental_authorization_supported: bool, issuer?: null|string, last4: null|string, network: null|string, network_transaction_id: null|string, offline: null|(object{stored_at: null|int, type: null|string}&StripeObject), overcapture_supported: bool, preferred_locales: null|string[], read_method: null|string, receipt: null|(object{account_type?: string, application_cryptogram: null|string, application_preferred_name: null|string, authorization_code: null|string, authorization_response_code: null|string, cardholder_verification_method: null|string, dedicated_file_name: null|string, terminal_verification_results: null|string, transaction_status_information: null|string}&StripeObject), wallet?: (object{type: string}&StripeObject)}&StripeObject), type: string}&StripeObject), setup_attempt: null|SetupAttempt|string}&StripeObject), iin?: null|string, issuer?: null|string, last4: string, networks: null|(object{available: string[], preferred: null|string}&StripeObject), regulated_status: null|string, three_d_secure_usage: null|(object{supported: bool}&StripeObject), wallet: null|(object{amex_express_checkout?: (object{}&StripeObject), apple_pay?: (object{}&StripeObject), dynamic_last4: null|string, google_pay?: (object{}&StripeObject), link?: (object{}&StripeObject), masterpass?: (object{billing_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, shipping_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject)}&StripeObject), samsung_pay?: (object{}&StripeObject), type: string, visa_checkout?: (object{billing_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, shipping_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject)}&StripeObject)}&StripeObject)}&StripeObject) $card
 * @property null|(object{brand: null|string, brand_product: null|string, cardholder_name: null|string, country: null|string, description?: null|string, exp_month: int, exp_year: int, fingerprint: null|string, funding: null|string, iin?: null|string, issuer?: null|string, last4: null|string, networks: null|(object{available: string[], preferred: null|string}&StripeObject), offline: null|(object{stored_at: null|int, type: null|string}&StripeObject), preferred_locales: null|string[], read_method: null|string, wallet?: (object{type: string}&StripeObject)}&StripeObject) $card_present
 * @property null|(object{buyer_id: null|string, cashtag: null|string}&StripeObject) $cashapp
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|(object{}&StripeObject) $crypto
 * @property null|Customer|string $customer The ID of the Customer to which this PaymentMethod is saved. This will not be set when the PaymentMethod has not been saved to a Customer.
 * @property null|(object{}&StripeObject) $customer_balance
 * @property null|(object{bank: null|string}&StripeObject) $eps
 * @property null|(object{account_holder_type: null|string, bank: string}&StripeObject) $fpx
 * @property null|(object{}&StripeObject) $giropay
 * @property null|(object{}&StripeObject) $grabpay
 * @property null|(object{bank: null|string, bic: null|string}&StripeObject) $ideal
 * @property null|(object{brand: null|string, cardholder_name: null|string, country: null|string, description?: null|string, exp_month: int, exp_year: int, fingerprint: null|string, funding: null|string, iin?: null|string, issuer?: null|string, last4: null|string, networks: null|(object{available: string[], preferred: null|string}&StripeObject), preferred_locales: null|string[], read_method: null|string}&StripeObject) $interac_present
 * @property null|(object{}&StripeObject) $kakao_pay
 * @property null|(object{dob?: null|(object{day: null|int, month: null|int, year: null|int}&StripeObject)}&StripeObject) $klarna
 * @property null|(object{}&StripeObject) $konbini
 * @property null|(object{brand: null|string, last4: null|string}&StripeObject) $kr_card
 * @property null|(object{email: null|string, persistent_token?: string}&StripeObject) $link
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|(object{}&StripeObject) $mobilepay
 * @property null|(object{}&StripeObject) $multibanco
 * @property null|(object{buyer_id: null|string, funding: string}&StripeObject) $naver_pay
 * @property null|(object{account_holder_name: null|string, bank_code: string, bank_name: string, branch_code: string, last4: string, suffix: null|string}&StripeObject) $nz_bank_account
 * @property null|(object{}&StripeObject) $oxxo
 * @property null|(object{bank: null|string}&StripeObject) $p24
 * @property null|(object{}&StripeObject) $pay_by_bank
 * @property null|(object{}&StripeObject) $payco
 * @property null|(object{}&StripeObject) $paynow
 * @property null|(object{country: null|string, payer_email: null|string, payer_id: null|string}&StripeObject) $paypal
 * @property null|(object{}&StripeObject) $pix
 * @property null|(object{}&StripeObject) $promptpay
 * @property null|(object{session?: string}&StripeObject) $radar_options Options to configure Radar. See <a href="https://stripe.com/docs/radar/radar-session">Radar Session</a> for more information.
 * @property null|(object{}&StripeObject) $revolut_pay
 * @property null|(object{}&StripeObject) $samsung_pay
 * @property null|(object{}&StripeObject) $satispay
 * @property null|(object{bank_code: null|string, branch_code: null|string, country: null|string, fingerprint: null|string, generated_from: null|(object{charge: null|Charge|string, setup_attempt: null|SetupAttempt|string}&StripeObject), last4: null|string}&StripeObject) $sepa_debit
 * @property null|(object{country: null|string}&StripeObject) $sofort
 * @property null|(object{}&StripeObject) $swish
 * @property null|(object{}&StripeObject) $twint
 * @property string $type The type of the PaymentMethod. An additional hash is included on the PaymentMethod with a name matching this value. It contains additional information specific to the PaymentMethod type.
 * @property null|(object{account_holder_type: null|string, account_type: null|string, bank_name: null|string, financial_connections_account: null|string, fingerprint: null|string, last4: null|string, networks: null|(object{preferred: null|string, supported: string[]}&StripeObject), routing_number: null|string, status_details: null|(object{blocked?: (object{network_code: null|string, reason: null|string}&StripeObject)}&StripeObject)}&StripeObject) $us_bank_account
 * @property null|(object{}&StripeObject) $wechat_pay
 * @property null|(object{}&StripeObject) $zip
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 75,
    'endLine' => 265,
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
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'payment_method\'',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 27,
            'startFilePos' => 10474,
            'endTokenPos' => 27,
            'endFilePos' => 10489,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'ALLOW_REDISPLAY_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'ALLOW_REDISPLAY_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 81,
            'endLine' => 81,
            'startTokenPos' => 41,
            'startFilePos' => 10559,
            'endTokenPos' => 41,
            'endFilePos' => 10566,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'ALLOW_REDISPLAY_LIMITED' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'ALLOW_REDISPLAY_LIMITED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'limited\'',
          'attributes' => 
          array (
            'startLine' => 82,
            'endLine' => 82,
            'startTokenPos' => 50,
            'startFilePos' => 10605,
            'endTokenPos' => 50,
            'endFilePos' => 10613,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'ALLOW_REDISPLAY_UNSPECIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'ALLOW_REDISPLAY_UNSPECIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unspecified\'',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 59,
            'startFilePos' => 10656,
            'endTokenPos' => 59,
            'endFilePos' => 10668,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'TYPE_ACSS_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_ACSS_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'acss_debit\'',
          'attributes' => 
          array (
            'startLine' => 85,
            'endLine' => 85,
            'startTokenPos' => 68,
            'startFilePos' => 10700,
            'endTokenPos' => 68,
            'endFilePos' => 10711,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_AFFIRM' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_AFFIRM',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'affirm\'',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 77,
            'startFilePos' => 10738,
            'endTokenPos' => 77,
            'endFilePos' => 10745,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AFTERPAY_CLEARPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_AFTERPAY_CLEARPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'afterpay_clearpay\'',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 87,
            'startTokenPos' => 86,
            'startFilePos' => 10783,
            'endTokenPos' => 86,
            'endFilePos' => 10801,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'TYPE_ALIPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_ALIPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'alipay\'',
          'attributes' => 
          array (
            'startLine' => 88,
            'endLine' => 88,
            'startTokenPos' => 95,
            'startFilePos' => 10828,
            'endTokenPos' => 95,
            'endFilePos' => 10835,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_ALMA' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_ALMA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'alma\'',
          'attributes' => 
          array (
            'startLine' => 89,
            'endLine' => 89,
            'startTokenPos' => 104,
            'startFilePos' => 10860,
            'endTokenPos' => 104,
            'endFilePos' => 10865,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_AMAZON_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_AMAZON_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'amazon_pay\'',
          'attributes' => 
          array (
            'startLine' => 90,
            'endLine' => 90,
            'startTokenPos' => 113,
            'startFilePos' => 10896,
            'endTokenPos' => 113,
            'endFilePos' => 10907,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_AU_BECS_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_AU_BECS_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'au_becs_debit\'',
          'attributes' => 
          array (
            'startLine' => 91,
            'endLine' => 91,
            'startTokenPos' => 122,
            'startFilePos' => 10941,
            'endTokenPos' => 122,
            'endFilePos' => 10955,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TYPE_BACS_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_BACS_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bacs_debit\'',
          'attributes' => 
          array (
            'startLine' => 92,
            'endLine' => 92,
            'startTokenPos' => 131,
            'startFilePos' => 10986,
            'endTokenPos' => 131,
            'endFilePos' => 10997,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_BANCONTACT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_BANCONTACT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bancontact\'',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 140,
            'startFilePos' => 11028,
            'endTokenPos' => 140,
            'endFilePos' => 11039,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_BILLIE' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_BILLIE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'billie\'',
          'attributes' => 
          array (
            'startLine' => 94,
            'endLine' => 94,
            'startTokenPos' => 149,
            'startFilePos' => 11066,
            'endTokenPos' => 149,
            'endFilePos' => 11073,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_BLIK' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_BLIK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'blik\'',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 95,
            'startTokenPos' => 158,
            'startFilePos' => 11098,
            'endTokenPos' => 158,
            'endFilePos' => 11103,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_BOLETO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_BOLETO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'boleto\'',
          'attributes' => 
          array (
            'startLine' => 96,
            'endLine' => 96,
            'startTokenPos' => 167,
            'startFilePos' => 11130,
            'endTokenPos' => 167,
            'endFilePos' => 11137,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card\'',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 176,
            'startFilePos' => 11162,
            'endTokenPos' => 176,
            'endFilePos' => 11167,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_CARD_PRESENT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_CARD_PRESENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card_present\'',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 185,
            'startFilePos' => 11200,
            'endTokenPos' => 185,
            'endFilePos' => 11213,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'TYPE_CASHAPP' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_CASHAPP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cashapp\'',
          'attributes' => 
          array (
            'startLine' => 99,
            'endLine' => 99,
            'startTokenPos' => 194,
            'startFilePos' => 11241,
            'endTokenPos' => 194,
            'endFilePos' => 11249,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_CRYPTO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_CRYPTO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'crypto\'',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 203,
            'startFilePos' => 11276,
            'endTokenPos' => 203,
            'endFilePos' => 11283,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_CUSTOMER_BALANCE' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_CUSTOMER_BALANCE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'customer_balance\'',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 212,
            'startFilePos' => 11320,
            'endTokenPos' => 212,
            'endFilePos' => 11337,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'TYPE_EPS' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_EPS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'eps\'',
          'attributes' => 
          array (
            'startLine' => 102,
            'endLine' => 102,
            'startTokenPos' => 221,
            'startFilePos' => 11361,
            'endTokenPos' => 221,
            'endFilePos' => 11365,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'TYPE_FPX' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_FPX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'fpx\'',
          'attributes' => 
          array (
            'startLine' => 103,
            'endLine' => 103,
            'startTokenPos' => 230,
            'startFilePos' => 11389,
            'endTokenPos' => 230,
            'endFilePos' => 11393,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 103,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'TYPE_GIROPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_GIROPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'giropay\'',
          'attributes' => 
          array (
            'startLine' => 104,
            'endLine' => 104,
            'startTokenPos' => 239,
            'startFilePos' => 11421,
            'endTokenPos' => 239,
            'endFilePos' => 11429,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_GRABPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_GRABPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'grabpay\'',
          'attributes' => 
          array (
            'startLine' => 105,
            'endLine' => 105,
            'startTokenPos' => 248,
            'startFilePos' => 11457,
            'endTokenPos' => 248,
            'endFilePos' => 11465,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_IDEAL' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_IDEAL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ideal\'',
          'attributes' => 
          array (
            'startLine' => 106,
            'endLine' => 106,
            'startTokenPos' => 257,
            'startFilePos' => 11491,
            'endTokenPos' => 257,
            'endFilePos' => 11497,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_INTERAC_PRESENT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_INTERAC_PRESENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'interac_present\'',
          'attributes' => 
          array (
            'startLine' => 107,
            'endLine' => 107,
            'startTokenPos' => 266,
            'startFilePos' => 11533,
            'endTokenPos' => 266,
            'endFilePos' => 11549,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 107,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'TYPE_KAKAO_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_KAKAO_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'kakao_pay\'',
          'attributes' => 
          array (
            'startLine' => 108,
            'endLine' => 108,
            'startTokenPos' => 275,
            'startFilePos' => 11579,
            'endTokenPos' => 275,
            'endFilePos' => 11589,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_KLARNA' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_KLARNA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'klarna\'',
          'attributes' => 
          array (
            'startLine' => 109,
            'endLine' => 109,
            'startTokenPos' => 284,
            'startFilePos' => 11616,
            'endTokenPos' => 284,
            'endFilePos' => 11623,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_KONBINI' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_KONBINI',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'konbini\'',
          'attributes' => 
          array (
            'startLine' => 110,
            'endLine' => 110,
            'startTokenPos' => 293,
            'startFilePos' => 11651,
            'endTokenPos' => 293,
            'endFilePos' => 11659,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 110,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_KR_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_KR_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'kr_card\'',
          'attributes' => 
          array (
            'startLine' => 111,
            'endLine' => 111,
            'startTokenPos' => 302,
            'startFilePos' => 11687,
            'endTokenPos' => 302,
            'endFilePos' => 11695,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_LINK' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_LINK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'link\'',
          'attributes' => 
          array (
            'startLine' => 112,
            'endLine' => 112,
            'startTokenPos' => 311,
            'startFilePos' => 11720,
            'endTokenPos' => 311,
            'endFilePos' => 11725,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_MOBILEPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_MOBILEPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mobilepay\'',
          'attributes' => 
          array (
            'startLine' => 113,
            'endLine' => 113,
            'startTokenPos' => 320,
            'startFilePos' => 11755,
            'endTokenPos' => 320,
            'endFilePos' => 11765,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_MULTIBANCO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_MULTIBANCO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'multibanco\'',
          'attributes' => 
          array (
            'startLine' => 114,
            'endLine' => 114,
            'startTokenPos' => 329,
            'startFilePos' => 11796,
            'endTokenPos' => 329,
            'endFilePos' => 11807,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 114,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_NAVER_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_NAVER_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'naver_pay\'',
          'attributes' => 
          array (
            'startLine' => 115,
            'endLine' => 115,
            'startTokenPos' => 338,
            'startFilePos' => 11837,
            'endTokenPos' => 338,
            'endFilePos' => 11847,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 115,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_NZ_BANK_ACCOUNT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_NZ_BANK_ACCOUNT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'nz_bank_account\'',
          'attributes' => 
          array (
            'startLine' => 116,
            'endLine' => 116,
            'startTokenPos' => 347,
            'startFilePos' => 11883,
            'endTokenPos' => 347,
            'endFilePos' => 11899,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 116,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'TYPE_OXXO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_OXXO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'oxxo\'',
          'attributes' => 
          array (
            'startLine' => 117,
            'endLine' => 117,
            'startTokenPos' => 356,
            'startFilePos' => 11924,
            'endTokenPos' => 356,
            'endFilePos' => 11929,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 117,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_P24' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_P24',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'p24\'',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 118,
            'startTokenPos' => 365,
            'startFilePos' => 11953,
            'endTokenPos' => 365,
            'endFilePos' => 11957,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'TYPE_PAYCO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_PAYCO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'payco\'',
          'attributes' => 
          array (
            'startLine' => 119,
            'endLine' => 119,
            'startTokenPos' => 374,
            'startFilePos' => 11983,
            'endTokenPos' => 374,
            'endFilePos' => 11989,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_PAYNOW' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_PAYNOW',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'paynow\'',
          'attributes' => 
          array (
            'startLine' => 120,
            'endLine' => 120,
            'startTokenPos' => 383,
            'startFilePos' => 12016,
            'endTokenPos' => 383,
            'endFilePos' => 12023,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 120,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_PAYPAL' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_PAYPAL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'paypal\'',
          'attributes' => 
          array (
            'startLine' => 121,
            'endLine' => 121,
            'startTokenPos' => 392,
            'startFilePos' => 12050,
            'endTokenPos' => 392,
            'endFilePos' => 12057,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 121,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_PAY_BY_BANK' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_PAY_BY_BANK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pay_by_bank\'',
          'attributes' => 
          array (
            'startLine' => 122,
            'endLine' => 122,
            'startTokenPos' => 401,
            'startFilePos' => 12089,
            'endTokenPos' => 401,
            'endFilePos' => 12101,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 122,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'TYPE_PIX' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_PIX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pix\'',
          'attributes' => 
          array (
            'startLine' => 123,
            'endLine' => 123,
            'startTokenPos' => 410,
            'startFilePos' => 12125,
            'endTokenPos' => 410,
            'endFilePos' => 12129,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 123,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'TYPE_PROMPTPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_PROMPTPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'promptpay\'',
          'attributes' => 
          array (
            'startLine' => 124,
            'endLine' => 124,
            'startTokenPos' => 419,
            'startFilePos' => 12159,
            'endTokenPos' => 419,
            'endFilePos' => 12169,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 124,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_REVOLUT_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_REVOLUT_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'revolut_pay\'',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 428,
            'startFilePos' => 12201,
            'endTokenPos' => 428,
            'endFilePos' => 12213,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'TYPE_SAMSUNG_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_SAMSUNG_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'samsung_pay\'',
          'attributes' => 
          array (
            'startLine' => 126,
            'endLine' => 126,
            'startTokenPos' => 437,
            'startFilePos' => 12245,
            'endTokenPos' => 437,
            'endFilePos' => 12257,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'TYPE_SATISPAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_SATISPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'satispay\'',
          'attributes' => 
          array (
            'startLine' => 127,
            'endLine' => 127,
            'startTokenPos' => 446,
            'startFilePos' => 12286,
            'endTokenPos' => 446,
            'endFilePos' => 12295,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 127,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'TYPE_SEPA_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_SEPA_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sepa_debit\'',
          'attributes' => 
          array (
            'startLine' => 128,
            'endLine' => 128,
            'startTokenPos' => 455,
            'startFilePos' => 12326,
            'endTokenPos' => 455,
            'endFilePos' => 12337,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 128,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_SOFORT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_SOFORT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sofort\'',
          'attributes' => 
          array (
            'startLine' => 129,
            'endLine' => 129,
            'startTokenPos' => 464,
            'startFilePos' => 12364,
            'endTokenPos' => 464,
            'endFilePos' => 12371,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_SWISH' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_SWISH',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'swish\'',
          'attributes' => 
          array (
            'startLine' => 130,
            'endLine' => 130,
            'startTokenPos' => 473,
            'startFilePos' => 12397,
            'endTokenPos' => 473,
            'endFilePos' => 12403,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 130,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_TWINT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_TWINT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'twint\'',
          'attributes' => 
          array (
            'startLine' => 131,
            'endLine' => 131,
            'startTokenPos' => 482,
            'startFilePos' => 12429,
            'endTokenPos' => 482,
            'endFilePos' => 12435,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 131,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_US_BANK_ACCOUNT' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_US_BANK_ACCOUNT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'us_bank_account\'',
          'attributes' => 
          array (
            'startLine' => 132,
            'endLine' => 132,
            'startTokenPos' => 491,
            'startFilePos' => 12471,
            'endTokenPos' => 491,
            'endFilePos' => 12487,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 132,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'TYPE_WECHAT_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_WECHAT_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'wechat_pay\'',
          'attributes' => 
          array (
            'startLine' => 133,
            'endLine' => 133,
            'startTokenPos' => 500,
            'startFilePos' => 12518,
            'endTokenPos' => 500,
            'endFilePos' => 12529,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 133,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_ZIP' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'name' => 'TYPE_ZIP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'zip\'',
          'attributes' => 
          array (
            'startLine' => 134,
            'endLine' => 134,
            'startTokenPos' => 509,
            'startFilePos' => 12553,
            'endTokenPos' => 509,
            'endFilePos' => 12557,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 134,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 27,
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
                'startLine' => 154,
                'endLine' => 154,
                'startTokenPos' => 526,
                'startFilePos' => 15474,
                'endTokenPos' => 526,
                'endFilePos' => 15477,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
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
                'startLine' => 154,
                'endLine' => 154,
                'startTokenPos' => 533,
                'startFilePos' => 15491,
                'endTokenPos' => 533,
                'endFilePos' => 15494,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
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
 * Creates a PaymentMethod object. Read the <a
 * href="/docs/stripe-js/reference#stripe-create-payment-method">Stripe.js
 * reference</a> to learn how to create PaymentMethods via Stripe.js.
 *
 * Instead of creating a PaymentMethod directly, we recommend using the <a
 * href="/docs/payments/accept-a-payment">PaymentIntents</a> API to accept a
 * payment immediately or the <a
 * href="/docs/payments/save-and-reuse">SetupIntent</a> API to collect payment
 * method details ahead of a future payment.
 *
 * @param null|array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, card?: array{cvc?: string, exp_month?: int, exp_year?: int, networks?: array{preferred?: string}, number?: string, token?: string}, cashapp?: array{}, crypto?: array{}, customer?: string, customer_balance?: array{}, eps?: array{bank?: string}, expand?: string[], fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, grabpay?: array{}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, payment_method?: string, paynow?: array{}, paypal?: array{}, pix?: array{}, promptpay?: array{}, radar_options?: array{session?: string}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, sofort?: array{country: string}, swish?: array{}, twint?: array{}, type?: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}} $params
 * @param null|array|string $options
 *
 * @return PaymentMethod the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 154,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'currentClassName' => 'Stripe\\PaymentMethod',
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
                'startLine' => 179,
                'endLine' => 179,
                'startTokenPos' => 630,
                'startFilePos' => 16476,
                'endTokenPos' => 630,
                'endFilePos' => 16479,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 179,
            'endLine' => 179,
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
                'startLine' => 179,
                'endLine' => 179,
                'startTokenPos' => 637,
                'startFilePos' => 16490,
                'endTokenPos' => 637,
                'endFilePos' => 16493,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 179,
            'endLine' => 179,
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
 * Returns a list of PaymentMethods for Treasury flows. If you want to list the
 * PaymentMethods attached to a Customer for payments, you should use the <a
 * href="/docs/api/payment_methods/customer_list">List a Customer’s
 * PaymentMethods</a> API instead.
 *
 * @param null|array{customer?: string, ending_before?: string, expand?: string[], limit?: int, starting_after?: string, type?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<PaymentMethod> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 179,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'currentClassName' => 'Stripe\\PaymentMethod',
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
            'startLine' => 199,
            'endLine' => 199,
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
                'startLine' => 199,
                'endLine' => 199,
                'startTokenPos' => 693,
                'startFilePos' => 17203,
                'endTokenPos' => 693,
                'endFilePos' => 17206,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 199,
            'endLine' => 199,
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
 * Retrieves a PaymentMethod object attached to the StripeAccount. To retrieve a
 * payment method attached to a Customer, you should use <a
 * href="/docs/api/payment_methods/customer">Retrieve a Customer’s
 * PaymentMethods</a>.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return PaymentMethod
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 199,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'currentClassName' => 'Stripe\\PaymentMethod',
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
            'startLine' => 220,
            'endLine' => 220,
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
                'startLine' => 220,
                'endLine' => 220,
                'startTokenPos' => 756,
                'startFilePos' => 18331,
                'endTokenPos' => 756,
                'endFilePos' => 18334,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 220,
            'endLine' => 220,
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
                'startLine' => 220,
                'endLine' => 220,
                'startTokenPos' => 763,
                'startFilePos' => 18345,
                'endTokenPos' => 763,
                'endFilePos' => 18348,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 220,
            'endLine' => 220,
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
 * Updates a PaymentMethod object. A PaymentMethod must be attached to a customer
 * to be updated.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{allow_redisplay?: string, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, card?: array{exp_month?: int, exp_year?: int, networks?: array{preferred?: null|string}}, expand?: string[], link?: array{}, metadata?: null|array<string, string>, pay_by_bank?: array{}, us_bank_account?: array{account_holder_type?: string, account_type?: string}} $params
 * @param null|array|string $opts
 *
 * @return PaymentMethod the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 220,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'currentClassName' => 'Stripe\\PaymentMethod',
        'aliasName' => NULL,
      ),
      'attach' => 
      array (
        'name' => 'attach',
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
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 859,
                'startFilePos' => 18929,
                'endTokenPos' => 859,
                'endFilePos' => 18932,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
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
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 866,
                'startFilePos' => 18943,
                'endTokenPos' => 866,
                'endFilePos' => 18946,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
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
 * @return PaymentMethod the attached payment method
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 240,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'currentClassName' => 'Stripe\\PaymentMethod',
        'aliasName' => NULL,
      ),
      'detach' => 
      array (
        'name' => 'detach',
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
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 943,
                'startFilePos' => 19419,
                'endTokenPos' => 943,
                'endFilePos' => 19422,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
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
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 950,
                'startFilePos' => 19433,
                'endTokenPos' => 950,
                'endFilePos' => 19436,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
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
 * @return PaymentMethod the detached payment method
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 257,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentMethod',
        'implementingClassName' => 'Stripe\\PaymentMethod',
        'currentClassName' => 'Stripe\\PaymentMethod',
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