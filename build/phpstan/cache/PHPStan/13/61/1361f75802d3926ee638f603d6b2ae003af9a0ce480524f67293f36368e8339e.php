<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Source.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Source
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a57a02e3124f4d8f24f2312c37f568a377f64f541a002365f6248a9a6fbde6d8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Source',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Source.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Source',
    'shortName' => 'Source',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * <code>Source</code> objects allow you to accept a variety of payment methods. They
 * represent a customer\'s payment instrument, and can be used with the Stripe API
 * just like a <code>Card</code> object: once chargeable, they can be charged, or can be
 * attached to customers.
 *
 * Stripe doesn\'t recommend using the deprecated <a href="https://stripe.com/docs/api/sources">Sources API</a>.
 * We recommend that you adopt the <a href="https://stripe.com/docs/api/payment_methods">PaymentMethods API</a>.
 * This newer API provides access to our latest features and payment method types.
 *
 * Related guides: <a href="https://stripe.com/docs/sources">Sources API</a> and <a href="https://stripe.com/docs/sources/customers">Sources &amp; Customers</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|(object{account_number?: null|string, bank_name?: null|string, fingerprint?: null|string, refund_account_holder_name?: null|string, refund_account_holder_type?: null|string, refund_routing_number?: null|string, routing_number?: null|string, swift_code?: null|string}&StripeObject) $ach_credit_transfer
 * @property null|(object{bank_name?: null|string, country?: null|string, fingerprint?: null|string, last4?: null|string, routing_number?: null|string, type?: null|string}&StripeObject) $ach_debit
 * @property null|(object{bank_address_city?: null|string, bank_address_line_1?: null|string, bank_address_line_2?: null|string, bank_address_postal_code?: null|string, bank_name?: null|string, category?: null|string, country?: null|string, fingerprint?: null|string, last4?: null|string, routing_number?: null|string}&StripeObject) $acss_debit
 * @property null|(object{data_string?: null|string, native_url?: null|string, statement_descriptor?: null|string}&StripeObject) $alipay
 * @property null|string $allow_redisplay This field indicates whether this payment method can be shown again to its customer in a checkout flow. Stripe products such as Checkout and Elements use this field to determine whether a payment method can be shown as a saved payment method in a checkout flow. The field defaults to “unspecified”.
 * @property null|int $amount A positive integer in the smallest currency unit (that is, 100 cents for $1.00, or 1 for ¥1, Japanese Yen being a zero-decimal currency) representing the total amount associated with the source. This is the amount for which the source will be chargeable once ready. Required for <code>single_use</code> sources.
 * @property null|(object{bsb_number?: null|string, fingerprint?: null|string, last4?: null|string}&StripeObject) $au_becs_debit
 * @property null|(object{bank_code?: null|string, bank_name?: null|string, bic?: null|string, iban_last4?: null|string, preferred_language?: null|string, statement_descriptor?: null|string}&StripeObject) $bancontact
 * @property null|(object{address_line1_check?: null|string, address_zip_check?: null|string, brand?: null|string, country?: null|string, cvc_check?: null|string, description?: string, dynamic_last4?: null|string, exp_month?: null|int, exp_year?: null|int, fingerprint?: string, funding?: null|string, iin?: string, issuer?: string, last4?: null|string, name?: null|string, three_d_secure?: string, tokenization_method?: null|string}&StripeObject) $card
 * @property null|(object{application_cryptogram?: string, application_preferred_name?: string, authorization_code?: null|string, authorization_response_code?: string, brand?: null|string, country?: null|string, cvm_type?: string, data_type?: null|string, dedicated_file_name?: string, description?: string, emv_auth_data?: string, evidence_customer_signature?: null|string, evidence_transaction_certificate?: null|string, exp_month?: null|int, exp_year?: null|int, fingerprint?: string, funding?: null|string, iin?: string, issuer?: string, last4?: null|string, pos_device_id?: null|string, pos_entry_mode?: string, read_method?: null|string, reader?: null|string, terminal_verification_results?: string, transaction_status_information?: string}&StripeObject) $card_present
 * @property string $client_secret The client secret of the source. Used for client-side retrieval using a publishable key.
 * @property null|(object{attempts_remaining: int, status: string}&StripeObject) $code_verification
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|string $currency Three-letter <a href="https://stripe.com/docs/currencies">ISO code for the currency</a> associated with the source. This is the currency for which the source will be chargeable once ready. Required for <code>single_use</code> sources.
 * @property null|string $customer The ID of the customer to which this source is attached. This will not be present when the source has not been attached to a customer.
 * @property null|(object{reference?: null|string, statement_descriptor?: null|string}&StripeObject) $eps
 * @property string $flow The authentication <code>flow</code> of the source. <code>flow</code> is one of <code>redirect</code>, <code>receiver</code>, <code>code_verification</code>, <code>none</code>.
 * @property null|(object{bank_code?: null|string, bank_name?: null|string, bic?: null|string, statement_descriptor?: null|string}&StripeObject) $giropay
 * @property null|(object{bank?: null|string, bic?: null|string, iban_last4?: null|string, statement_descriptor?: null|string}&StripeObject) $ideal
 * @property null|(object{background_image_url?: string, client_token?: null|string, first_name?: string, last_name?: string, locale?: string, logo_url?: string, page_title?: string, pay_later_asset_urls_descriptive?: string, pay_later_asset_urls_standard?: string, pay_later_name?: string, pay_later_redirect_url?: string, pay_now_asset_urls_descriptive?: string, pay_now_asset_urls_standard?: string, pay_now_name?: string, pay_now_redirect_url?: string, pay_over_time_asset_urls_descriptive?: string, pay_over_time_asset_urls_standard?: string, pay_over_time_name?: string, pay_over_time_redirect_url?: string, payment_method_categories?: string, purchase_country?: string, purchase_type?: string, redirect_url?: string, shipping_delay?: int, shipping_first_name?: string, shipping_last_name?: string}&StripeObject) $klarna
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|(object{entity?: null|string, reference?: null|string, refund_account_holder_address_city?: null|string, refund_account_holder_address_country?: null|string, refund_account_holder_address_line1?: null|string, refund_account_holder_address_line2?: null|string, refund_account_holder_address_postal_code?: null|string, refund_account_holder_address_state?: null|string, refund_account_holder_name?: null|string, refund_iban?: null|string}&StripeObject) $multibanco
 * @property null|(object{address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, phone: null|string, verified_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), verified_email: null|string, verified_name: null|string, verified_phone: null|string}&StripeObject) $owner Information about the owner of the payment instrument that may be used or required by particular source types.
 * @property null|(object{reference?: null|string}&StripeObject) $p24
 * @property null|(object{address: null|string, amount_charged: int, amount_received: int, amount_returned: int, refund_attributes_method: string, refund_attributes_status: string}&StripeObject) $receiver
 * @property null|(object{failure_reason: null|string, return_url: string, status: string, url: string}&StripeObject) $redirect
 * @property null|(object{bank_name?: null|string, bic?: null|string, iban?: null|string, refund_account_holder_address_city?: null|string, refund_account_holder_address_country?: null|string, refund_account_holder_address_line1?: null|string, refund_account_holder_address_line2?: null|string, refund_account_holder_address_postal_code?: null|string, refund_account_holder_address_state?: null|string, refund_account_holder_name?: null|string, refund_iban?: null|string}&StripeObject) $sepa_credit_transfer
 * @property null|(object{bank_code?: null|string, branch_code?: null|string, country?: null|string, fingerprint?: null|string, last4?: null|string, mandate_reference?: null|string, mandate_url?: null|string}&StripeObject) $sepa_debit
 * @property null|(object{bank_code?: null|string, bank_name?: null|string, bic?: null|string, country?: null|string, iban_last4?: null|string, preferred_language?: null|string, statement_descriptor?: null|string}&StripeObject) $sofort
 * @property null|(object{amount: int, currency: string, email?: string, items: null|((object{amount: null|int, currency: null|string, description: null|string, parent: null|string, quantity?: int, type: null|string}&StripeObject))[], shipping?: (object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), carrier?: null|string, name?: string, phone?: null|string, tracking_number?: null|string}&StripeObject)}&StripeObject) $source_order
 * @property null|string $statement_descriptor Extra information about a source. This will appear on your customer\'s statement every time you charge the source.
 * @property string $status The status of the source, one of <code>canceled</code>, <code>chargeable</code>, <code>consumed</code>, <code>failed</code>, or <code>pending</code>. Only <code>chargeable</code> sources can be used to create a charge.
 * @property null|(object{address_line1_check?: null|string, address_zip_check?: null|string, authenticated?: null|bool, brand?: null|string, card?: null|string, country?: null|string, customer?: null|string, cvc_check?: null|string, description?: string, dynamic_last4?: null|string, exp_month?: null|int, exp_year?: null|int, fingerprint?: string, funding?: null|string, iin?: string, issuer?: string, last4?: null|string, name?: null|string, three_d_secure?: string, tokenization_method?: null|string}&StripeObject) $three_d_secure
 * @property string $type The <code>type</code> of the source. The <code>type</code> is a payment method, one of <code>ach_credit_transfer</code>, <code>ach_debit</code>, <code>alipay</code>, <code>bancontact</code>, <code>card</code>, <code>card_present</code>, <code>eps</code>, <code>giropay</code>, <code>ideal</code>, <code>multibanco</code>, <code>klarna</code>, <code>p24</code>, <code>sepa_debit</code>, <code>sofort</code>, <code>three_d_secure</code>, or <code>wechat</code>. An additional hash is included on the source with a name matching this value. It contains additional information specific to the <a href="https://stripe.com/docs/sources">payment method</a> used.
 * @property null|string $usage Either <code>reusable</code> or <code>single_use</code>. Whether this source should be reusable or not. Some source types may or may not be reusable by construction, while others may leave the option at creation. If an incompatible value is passed, an error will be returned.
 * @property null|(object{prepay_id?: string, qr_code_url?: null|string, statement_descriptor?: string}&StripeObject) $wechat
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 59,
    'endLine' => 251,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\Update',
      1 => 'Stripe\\ApiOperations\\NestedResource',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'source\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 27,
            'startFilePos' => 12052,
            'endTokenPos' => 27,
            'endFilePos' => 12059,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'ALLOW_REDISPLAY_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'ALLOW_REDISPLAY_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 41,
            'startFilePos' => 12129,
            'endTokenPos' => 41,
            'endFilePos' => 12136,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'ALLOW_REDISPLAY_LIMITED' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'ALLOW_REDISPLAY_LIMITED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'limited\'',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 50,
            'startFilePos' => 12175,
            'endTokenPos' => 50,
            'endFilePos' => 12183,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'ALLOW_REDISPLAY_UNSPECIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'ALLOW_REDISPLAY_UNSPECIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unspecified\'',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 67,
            'startTokenPos' => 59,
            'startFilePos' => 12226,
            'endTokenPos' => 59,
            'endFilePos' => 12238,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'FLOW_CODE_VERIFICATION' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'FLOW_CODE_VERIFICATION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'code_verification\'',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 68,
            'startFilePos' => 12277,
            'endTokenPos' => 68,
            'endFilePos' => 12295,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'FLOW_NONE' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'FLOW_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'none\'',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 77,
            'startFilePos' => 12320,
            'endTokenPos' => 77,
            'endFilePos' => 12325,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'FLOW_RECEIVER' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'FLOW_RECEIVER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'receiver\'',
          'attributes' => 
          array (
            'startLine' => 71,
            'endLine' => 71,
            'startTokenPos' => 86,
            'startFilePos' => 12354,
            'endTokenPos' => 86,
            'endFilePos' => 12363,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 71,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'FLOW_REDIRECT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'FLOW_REDIRECT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'redirect\'',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 95,
            'startFilePos' => 12392,
            'endTokenPos' => 95,
            'endFilePos' => 12401,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'STATUS_CANCELED' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'STATUS_CANCELED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'canceled\'',
          'attributes' => 
          array (
            'startLine' => 74,
            'endLine' => 74,
            'startTokenPos' => 104,
            'startFilePos' => 12433,
            'endTokenPos' => 104,
            'endFilePos' => 12442,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 74,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_CHARGEABLE' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'STATUS_CHARGEABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'chargeable\'',
          'attributes' => 
          array (
            'startLine' => 75,
            'endLine' => 75,
            'startTokenPos' => 113,
            'startFilePos' => 12475,
            'endTokenPos' => 113,
            'endFilePos' => 12486,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'STATUS_CONSUMED' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'STATUS_CONSUMED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'consumed\'',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 76,
            'startTokenPos' => 122,
            'startFilePos' => 12517,
            'endTokenPos' => 122,
            'endFilePos' => 12526,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_FAILED' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'STATUS_FAILED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'failed\'',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 131,
            'startFilePos' => 12555,
            'endTokenPos' => 131,
            'endFilePos' => 12562,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 78,
            'startTokenPos' => 140,
            'startFilePos' => 12592,
            'endTokenPos' => 140,
            'endFilePos' => 12600,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'TYPE_ACH_CREDIT_TRANSFER' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_ACH_CREDIT_TRANSFER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ach_credit_transfer\'',
          'attributes' => 
          array (
            'startLine' => 80,
            'endLine' => 80,
            'startTokenPos' => 149,
            'startFilePos' => 12641,
            'endTokenPos' => 149,
            'endFilePos' => 12661,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 80,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 59,
      ),
      'TYPE_ACH_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_ACH_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ach_debit\'',
          'attributes' => 
          array (
            'startLine' => 81,
            'endLine' => 81,
            'startTokenPos' => 158,
            'startFilePos' => 12691,
            'endTokenPos' => 158,
            'endFilePos' => 12701,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_ACSS_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_ACSS_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'acss_debit\'',
          'attributes' => 
          array (
            'startLine' => 82,
            'endLine' => 82,
            'startTokenPos' => 167,
            'startFilePos' => 12732,
            'endTokenPos' => 167,
            'endFilePos' => 12743,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_ALIPAY' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_ALIPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'alipay\'',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 176,
            'startFilePos' => 12770,
            'endTokenPos' => 176,
            'endFilePos' => 12777,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_AU_BECS_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_AU_BECS_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'au_becs_debit\'',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 185,
            'startFilePos' => 12811,
            'endTokenPos' => 185,
            'endFilePos' => 12825,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TYPE_BANCONTACT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_BANCONTACT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bancontact\'',
          'attributes' => 
          array (
            'startLine' => 85,
            'endLine' => 85,
            'startTokenPos' => 194,
            'startFilePos' => 12856,
            'endTokenPos' => 194,
            'endFilePos' => 12867,
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
      'TYPE_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card\'',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 203,
            'startFilePos' => 12892,
            'endTokenPos' => 203,
            'endFilePos' => 12897,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_CARD_PRESENT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_CARD_PRESENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card_present\'',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 87,
            'startTokenPos' => 212,
            'startFilePos' => 12930,
            'endTokenPos' => 212,
            'endFilePos' => 12943,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'TYPE_EPS' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_EPS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'eps\'',
          'attributes' => 
          array (
            'startLine' => 88,
            'endLine' => 88,
            'startTokenPos' => 221,
            'startFilePos' => 12967,
            'endTokenPos' => 221,
            'endFilePos' => 12971,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'TYPE_GIROPAY' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_GIROPAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'giropay\'',
          'attributes' => 
          array (
            'startLine' => 89,
            'endLine' => 89,
            'startTokenPos' => 230,
            'startFilePos' => 12999,
            'endTokenPos' => 230,
            'endFilePos' => 13007,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_IDEAL' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_IDEAL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'ideal\'',
          'attributes' => 
          array (
            'startLine' => 90,
            'endLine' => 90,
            'startTokenPos' => 239,
            'startFilePos' => 13033,
            'endTokenPos' => 239,
            'endFilePos' => 13039,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TYPE_KLARNA' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_KLARNA',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'klarna\'',
          'attributes' => 
          array (
            'startLine' => 91,
            'endLine' => 91,
            'startTokenPos' => 248,
            'startFilePos' => 13066,
            'endTokenPos' => 248,
            'endFilePos' => 13073,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_MULTIBANCO' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_MULTIBANCO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'multibanco\'',
          'attributes' => 
          array (
            'startLine' => 92,
            'endLine' => 92,
            'startTokenPos' => 257,
            'startFilePos' => 13104,
            'endTokenPos' => 257,
            'endFilePos' => 13115,
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
      'TYPE_P24' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_P24',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'p24\'',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 266,
            'startFilePos' => 13139,
            'endTokenPos' => 266,
            'endFilePos' => 13143,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'TYPE_SEPA_CREDIT_TRANSFER' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_SEPA_CREDIT_TRANSFER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sepa_credit_transfer\'',
          'attributes' => 
          array (
            'startLine' => 94,
            'endLine' => 94,
            'startTokenPos' => 275,
            'startFilePos' => 13184,
            'endTokenPos' => 275,
            'endFilePos' => 13205,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'TYPE_SEPA_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_SEPA_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sepa_debit\'',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 95,
            'startTokenPos' => 284,
            'startFilePos' => 13236,
            'endTokenPos' => 284,
            'endFilePos' => 13247,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'TYPE_SOFORT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_SOFORT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sofort\'',
          'attributes' => 
          array (
            'startLine' => 96,
            'endLine' => 96,
            'startTokenPos' => 293,
            'startFilePos' => 13274,
            'endTokenPos' => 293,
            'endFilePos' => 13281,
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
      'TYPE_THREE_D_SECURE' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_THREE_D_SECURE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'three_d_secure\'',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 302,
            'startFilePos' => 13316,
            'endTokenPos' => 302,
            'endFilePos' => 13331,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'TYPE_WECHAT' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'TYPE_WECHAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'wechat\'',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 311,
            'startFilePos' => 13358,
            'endTokenPos' => 311,
            'endFilePos' => 13365,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'USAGE_REUSABLE' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'USAGE_REUSABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'reusable\'',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 320,
            'startFilePos' => 13396,
            'endTokenPos' => 320,
            'endFilePos' => 13405,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'USAGE_SINGLE_USE' => 
      array (
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'name' => 'USAGE_SINGLE_USE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'single_use\'',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 329,
            'startFilePos' => 13437,
            'endTokenPos' => 329,
            'endFilePos' => 13448,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 42,
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
                'startLine' => 113,
                'endLine' => 113,
                'startTokenPos' => 346,
                'startFilePos' => 14903,
                'endTokenPos' => 346,
                'endFilePos' => 14906,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 113,
            'endLine' => 113,
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
                'startLine' => 113,
                'endLine' => 113,
                'startTokenPos' => 353,
                'startFilePos' => 14920,
                'endTokenPos' => 353,
                'endFilePos' => 14923,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 113,
            'endLine' => 113,
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
 * Creates a new source object.
 *
 * @param null|array{amount?: int, currency?: string, customer?: string, expand?: string[], flow?: string, mandate?: array{acceptance?: array{date?: int, ip?: string, offline?: array{contact_email: string}, online?: array{date?: int, ip?: string, user_agent?: string}, status: string, type?: string, user_agent?: string}, amount?: null|int, currency?: string, interval?: string, notification_method?: string}, metadata?: array<string, string>, original_source?: string, owner?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: string, name?: string, phone?: string}, receiver?: array{refund_attributes_method?: string}, redirect?: array{return_url: string}, source_order?: array{items?: array{amount?: int, currency?: string, description?: string, parent?: string, quantity?: int, type?: string}[], shipping?: array{address: array{city?: string, country?: string, line1: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name?: string, phone?: string, tracking_number?: string}}, statement_descriptor?: string, token?: string, type?: string, usage?: string} $params
 * @param null|array|string $options
 *
 * @return Source the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 113,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'currentClassName' => 'Stripe\\Source',
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
            'startLine' => 137,
            'endLine' => 137,
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
                'startLine' => 137,
                'endLine' => 137,
                'startTokenPos' => 453,
                'startFilePos' => 15762,
                'endTokenPos' => 453,
                'endFilePos' => 15765,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 137,
            'endLine' => 137,
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
 * Retrieves an existing source object. Supply the unique source ID from a source
 * creation request and Stripe will return the corresponding up-to-date source
 * object information.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Source
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 137,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'currentClassName' => 'Stripe\\Source',
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
            'startLine' => 163,
            'endLine' => 163,
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
                'startLine' => 163,
                'endLine' => 163,
                'startTokenPos' => 516,
                'startFilePos' => 17600,
                'endTokenPos' => 516,
                'endFilePos' => 17603,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
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
                'startLine' => 163,
                'endLine' => 163,
                'startTokenPos' => 523,
                'startFilePos' => 17614,
                'endTokenPos' => 523,
                'endFilePos' => 17617,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
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
 * Updates the specified source by setting the values of the parameters passed. Any
 * parameters not provided will be left unchanged.
 *
 * This request accepts the <code>metadata</code> and <code>owner</code> as
 * arguments. It is also possible to update type specific information for selected
 * payment methods. Please refer to our <a href="/docs/sources">payment method
 * guides</a> for more detail.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{amount?: int, expand?: string[], mandate?: array{acceptance?: array{date?: int, ip?: string, offline?: array{contact_email: string}, online?: array{date?: int, ip?: string, user_agent?: string}, status: string, type?: string, user_agent?: string}, amount?: null|int, currency?: string, interval?: string, notification_method?: string}, metadata?: null|array<string, string>, owner?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: string, name?: string, phone?: string}, source_order?: array{items?: array{amount?: int, currency?: string, description?: string, parent?: string, quantity?: int, type?: string}[], shipping?: array{address: array{city?: string, country?: string, line1: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name?: string, phone?: string, tracking_number?: string}}} $params
 * @param null|array|string $opts
 *
 * @return Source the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 163,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'currentClassName' => 'Stripe\\Source',
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
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 624,
                'startFilePos' => 18316,
                'endTokenPos' => 624,
                'endFilePos' => 18319,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
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
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 631,
                'startFilePos' => 18330,
                'endTokenPos' => 631,
                'endFilePos' => 18333,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
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
 * @return Source the detached source
 *
 * @throws Exception\\UnexpectedValueException if the source is not attached to a customer
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 186,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'currentClassName' => 'Stripe\\Source',
        'aliasName' => NULL,
      ),
      'allSourceTransactions' => 
      array (
        'name' => 'allSourceTransactions',
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
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 50,
            'endColumn' => 52,
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
                'startLine' => 225,
                'endLine' => 225,
                'startTokenPos' => 868,
                'startFilePos' => 19630,
                'endTokenPos' => 868,
                'endFilePos' => 19633,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 55,
            'endColumn' => 68,
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
                'startLine' => 225,
                'endLine' => 225,
                'startTokenPos' => 875,
                'startFilePos' => 19644,
                'endTokenPos' => 875,
                'endFilePos' => 19647,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 71,
            'endColumn' => 82,
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
 * @param string $id
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<SourceTransaction> list of source transactions
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 225,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'currentClassName' => 'Stripe\\Source',
        'aliasName' => NULL,
      ),
      'verify' => 
      array (
        'name' => 'verify',
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
                'startLine' => 243,
                'endLine' => 243,
                'startTokenPos' => 967,
                'startFilePos' => 20196,
                'endTokenPos' => 967,
                'endFilePos' => 20199,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 243,
            'endLine' => 243,
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
                'startLine' => 243,
                'endLine' => 243,
                'startTokenPos' => 974,
                'startFilePos' => 20210,
                'endTokenPos' => 974,
                'endFilePos' => 20213,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 243,
            'endLine' => 243,
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
 * @return Source the verified source
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 243,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Source',
        'implementingClassName' => 'Stripe\\Source',
        'currentClassName' => 'Stripe\\Source',
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