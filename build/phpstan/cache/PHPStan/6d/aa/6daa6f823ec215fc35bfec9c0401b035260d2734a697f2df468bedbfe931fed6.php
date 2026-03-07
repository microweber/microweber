<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Charge.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Charge
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d707906cdccb89ad144d3321869abb938f719f6bb415555195ba9be0079c2be9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Charge',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Charge.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Charge',
    'shortName' => 'Charge',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The <code>Charge</code> object represents a single attempt to move money into your Stripe account.
 * PaymentIntent confirmation is the most common way to create Charges, but transferring
 * money to a different Stripe account through Connect also creates Charges.
 * Some legacy payment flows create Charges directly, which is not recommended for new integrations.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property int $amount Amount intended to be collected by this payment. A positive integer representing how much to charge in the <a href="https://stripe.com/docs/currencies#zero-decimal">smallest currency unit</a> (e.g., 100 cents to charge $1.00 or 100 to charge ¥100, a zero-decimal currency). The minimum amount is $0.50 US or <a href="https://stripe.com/docs/currencies#minimum-and-maximum-charge-amounts">equivalent in charge currency</a>. The amount value supports up to eight digits (e.g., a value of 99999999 for a USD charge of $999,999.99).
 * @property int $amount_captured Amount in cents (or local equivalent) captured (can be less than the amount attribute on the charge if a partial capture was made).
 * @property int $amount_refunded Amount in cents (or local equivalent) refunded (can be less than the amount attribute on the charge if a partial refund was issued).
 * @property null|Application|string $application ID of the Connect application that created the charge.
 * @property null|ApplicationFee|string $application_fee The application fee (if any) for the charge. <a href="https://stripe.com/docs/connect/direct-charges#collect-fees">See the Connect documentation</a> for details.
 * @property null|int $application_fee_amount The amount of the application fee (if any) requested for the charge. <a href="https://stripe.com/docs/connect/direct-charges#collect-fees">See the Connect documentation</a> for details.
 * @property null|string $authorization_code Authorization code on the charge.
 * @property null|BalanceTransaction|string $balance_transaction ID of the balance transaction that describes the impact of this charge on your account balance (not including refunds or disputes).
 * @property (object{address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, phone: null|string, tax_id: null|string}&StripeObject) $billing_details
 * @property null|string $calculated_statement_descriptor The full statement descriptor that is passed to card networks, and that is displayed on your customers\' credit card and bank statements. Allows you to see what the statement descriptor looks like after the static and dynamic portions are combined. This value only exists for card payments.
 * @property bool $captured If the charge was created without capturing, this Boolean represents whether it is still uncaptured or has since been captured.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property null|Customer|string $customer ID of the customer this charge is for if one exists.
 * @property null|string $description An arbitrary string attached to the object. Often useful for displaying to users.
 * @property bool $disputed Whether the charge has been disputed.
 * @property null|BalanceTransaction|string $failure_balance_transaction ID of the balance transaction that describes the reversal of the balance on your account due to payment failure.
 * @property null|string $failure_code Error code explaining reason for charge failure if available (see <a href="https://stripe.com/docs/error-codes">the errors section</a> for a list of codes).
 * @property null|string $failure_message Message to user further explaining reason for charge failure if available.
 * @property null|(object{stripe_report?: string, user_report?: string}&StripeObject) $fraud_details Information on fraud assessments for the charge.
 * @property null|(object{customer_reference?: string, line_items: ((object{discount_amount: null|int, product_code: string, product_description: string, quantity: null|int, tax_amount: null|int, unit_cost: null|int}&StripeObject))[], merchant_reference: string, shipping_address_zip?: string, shipping_amount?: int, shipping_from_zip?: string}&StripeObject) $level3
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|Account|string $on_behalf_of The account (if any) the charge was made on behalf of without triggering an automatic transfer. See the <a href="https://stripe.com/docs/connect/separate-charges-and-transfers">Connect documentation</a> for details.
 * @property null|(object{advice_code: null|string, network_advice_code: null|string, network_decline_code: null|string, network_status: null|string, reason: null|string, risk_level?: string, risk_score?: int, rule?: (object{action: string, id: string, predicate: string}&StripeObject)|string, seller_message: null|string, type: string}&StripeObject) $outcome Details about whether the payment was accepted, and why. See <a href="https://stripe.com/docs/declines">understanding declines</a> for details.
 * @property bool $paid <code>true</code> if the charge succeeded, or was successfully authorized for later capture.
 * @property null|PaymentIntent|string $payment_intent ID of the PaymentIntent associated with this charge, if one exists.
 * @property null|string $payment_method ID of the payment method used in this charge.
 * @property null|(object{ach_credit_transfer?: (object{account_number: null|string, bank_name: null|string, routing_number: null|string, swift_code: null|string}&StripeObject), ach_debit?: (object{account_holder_type: null|string, bank_name: null|string, country: null|string, fingerprint: null|string, last4: null|string, routing_number: null|string}&StripeObject), acss_debit?: (object{bank_name: null|string, fingerprint: null|string, institution_number: null|string, last4: null|string, mandate?: string, transit_number: null|string}&StripeObject), affirm?: (object{location?: string, reader?: string, transaction_id: null|string}&StripeObject), afterpay_clearpay?: (object{order_id: null|string, reference: null|string}&StripeObject), alipay?: (object{buyer_id?: string, fingerprint: null|string, transaction_id: null|string}&StripeObject), alma?: (object{installments?: (object{count: int}&StripeObject), transaction_id: null|string}&StripeObject), amazon_pay?: (object{funding?: (object{card?: (object{brand: null|string, country: null|string, exp_month: null|int, exp_year: null|int, funding: null|string, last4: null|string}&StripeObject), type: null|string}&StripeObject), transaction_id: null|string}&StripeObject), au_becs_debit?: (object{bsb_number: null|string, fingerprint: null|string, last4: null|string, mandate?: string}&StripeObject), bacs_debit?: (object{fingerprint: null|string, last4: null|string, mandate: null|string, sort_code: null|string}&StripeObject), bancontact?: (object{bank_code: null|string, bank_name: null|string, bic: null|string, generated_sepa_debit: null|PaymentMethod|string, generated_sepa_debit_mandate: null|Mandate|string, iban_last4: null|string, preferred_language: null|string, verified_name: null|string}&StripeObject), billie?: (object{transaction_id: null|string}&StripeObject), blik?: (object{buyer_id: null|string}&StripeObject), boleto?: (object{tax_id: string}&StripeObject), card?: (object{amount_authorized: null|int, authorization_code: null|string, brand: null|string, capture_before?: int, checks: null|(object{address_line1_check: null|string, address_postal_code_check: null|string, cvc_check: null|string}&StripeObject), country: null|string, description?: null|string, exp_month: int, exp_year: int, extended_authorization?: (object{status: string}&StripeObject), fingerprint?: null|string, funding: null|string, iin?: null|string, incremental_authorization?: (object{status: string}&StripeObject), installments: null|(object{plan: null|(object{count: null|int, interval: null|string, type: string}&StripeObject)}&StripeObject), issuer?: null|string, last4: null|string, mandate: null|string, moto?: null|bool, multicapture?: (object{status: string}&StripeObject), network: null|string, network_token?: null|(object{used: bool}&StripeObject), network_transaction_id: null|string, overcapture?: (object{maximum_amount_capturable: int, status: string}&StripeObject), regulated_status: null|string, three_d_secure: null|(object{authentication_flow: null|string, electronic_commerce_indicator: null|string, exemption_indicator: null|string, exemption_indicator_applied?: bool, result: null|string, result_reason: null|string, transaction_id: null|string, version: null|string}&StripeObject), wallet: null|(object{amex_express_checkout?: (object{}&StripeObject), apple_pay?: (object{}&StripeObject), dynamic_last4: null|string, google_pay?: (object{}&StripeObject), link?: (object{}&StripeObject), masterpass?: (object{billing_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, shipping_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject)}&StripeObject), samsung_pay?: (object{}&StripeObject), type: string, visa_checkout?: (object{billing_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), email: null|string, name: null|string, shipping_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject)}&StripeObject)}&StripeObject)}&StripeObject), card_present?: (object{amount_authorized: null|int, brand: null|string, brand_product: null|string, capture_before?: int, cardholder_name: null|string, country: null|string, description?: null|string, emv_auth_data: null|string, exp_month: int, exp_year: int, fingerprint: null|string, funding: null|string, generated_card: null|string, iin?: null|string, incremental_authorization_supported: bool, issuer?: null|string, last4: null|string, network: null|string, network_transaction_id: null|string, offline: null|(object{stored_at: null|int, type: null|string}&StripeObject), overcapture_supported: bool, preferred_locales: null|string[], read_method: null|string, receipt: null|(object{account_type?: string, application_cryptogram: null|string, application_preferred_name: null|string, authorization_code: null|string, authorization_response_code: null|string, cardholder_verification_method: null|string, dedicated_file_name: null|string, terminal_verification_results: null|string, transaction_status_information: null|string}&StripeObject), wallet?: (object{type: string}&StripeObject)}&StripeObject), cashapp?: (object{buyer_id: null|string, cashtag: null|string, transaction_id: null|string}&StripeObject), crypto?: (object{buyer_address?: string, network?: string, token_currency?: string, transaction_hash?: string}&StripeObject), customer_balance?: (object{}&StripeObject), eps?: (object{bank: null|string, verified_name: null|string}&StripeObject), fpx?: (object{account_holder_type: null|string, bank: string, transaction_id: null|string}&StripeObject), giropay?: (object{bank_code: null|string, bank_name: null|string, bic: null|string, verified_name: null|string}&StripeObject), grabpay?: (object{transaction_id: null|string}&StripeObject), ideal?: (object{bank: null|string, bic: null|string, generated_sepa_debit: null|PaymentMethod|string, generated_sepa_debit_mandate: null|Mandate|string, iban_last4: null|string, verified_name: null|string}&StripeObject), interac_present?: (object{brand: null|string, cardholder_name: null|string, country: null|string, description?: null|string, emv_auth_data: null|string, exp_month: int, exp_year: int, fingerprint: null|string, funding: null|string, generated_card: null|string, iin?: null|string, issuer?: null|string, last4: null|string, network: null|string, network_transaction_id: null|string, preferred_locales: null|string[], read_method: null|string, receipt: null|(object{account_type?: string, application_cryptogram: null|string, application_preferred_name: null|string, authorization_code: null|string, authorization_response_code: null|string, cardholder_verification_method: null|string, dedicated_file_name: null|string, terminal_verification_results: null|string, transaction_status_information: null|string}&StripeObject)}&StripeObject), kakao_pay?: (object{buyer_id: null|string, transaction_id: null|string}&StripeObject), klarna?: (object{payer_details: null|(object{address: null|(object{country: null|string}&StripeObject)}&StripeObject), payment_method_category: null|string, preferred_locale: null|string}&StripeObject), konbini?: (object{store: null|(object{chain: null|string}&StripeObject)}&StripeObject), kr_card?: (object{brand: null|string, buyer_id: null|string, last4: null|string, transaction_id: null|string}&StripeObject), link?: (object{country: null|string}&StripeObject), mobilepay?: (object{card: null|(object{brand: null|string, country: null|string, exp_month: null|int, exp_year: null|int, last4: null|string}&StripeObject)}&StripeObject), multibanco?: (object{entity: null|string, reference: null|string}&StripeObject), naver_pay?: (object{buyer_id: null|string, transaction_id: null|string}&StripeObject), nz_bank_account?: (object{account_holder_name: null|string, bank_code: string, bank_name: string, branch_code: string, last4: string, suffix: null|string}&StripeObject), oxxo?: (object{number: null|string}&StripeObject), p24?: (object{bank: null|string, reference: null|string, verified_name: null|string}&StripeObject), pay_by_bank?: (object{}&StripeObject), payco?: (object{buyer_id: null|string, transaction_id: null|string}&StripeObject), paynow?: (object{location?: string, reader?: string, reference: null|string}&StripeObject), paypal?: (object{country: null|string, payer_email: null|string, payer_id: null|string, payer_name: null|string, seller_protection: null|(object{dispute_categories: null|string[], status: string}&StripeObject), transaction_id: null|string}&StripeObject), pix?: (object{bank_transaction_id?: null|string}&StripeObject), promptpay?: (object{reference: null|string}&StripeObject), revolut_pay?: (object{funding?: (object{card?: (object{brand: null|string, country: null|string, exp_month: null|int, exp_year: null|int, funding: null|string, last4: null|string}&StripeObject), type: null|string}&StripeObject), transaction_id: null|string}&StripeObject), samsung_pay?: (object{buyer_id: null|string, transaction_id: null|string}&StripeObject), satispay?: (object{transaction_id: null|string}&StripeObject), sepa_credit_transfer?: (object{bank_name: null|string, bic: null|string, iban: null|string}&StripeObject), sepa_debit?: (object{bank_code: null|string, branch_code: null|string, country: null|string, fingerprint: null|string, last4: null|string, mandate: null|string}&StripeObject), sofort?: (object{bank_code: null|string, bank_name: null|string, bic: null|string, country: null|string, generated_sepa_debit: null|PaymentMethod|string, generated_sepa_debit_mandate: null|Mandate|string, iban_last4: null|string, preferred_language: null|string, verified_name: null|string}&StripeObject), stripe_account?: (object{}&StripeObject), swish?: (object{fingerprint: null|string, payment_reference: null|string, verified_phone_last4: null|string}&StripeObject), twint?: (object{}&StripeObject), type: string, us_bank_account?: (object{account_holder_type: null|string, account_type: null|string, bank_name: null|string, fingerprint: null|string, last4: null|string, mandate?: Mandate|string, payment_reference: null|string, routing_number: null|string}&StripeObject), wechat?: (object{}&StripeObject), wechat_pay?: (object{fingerprint: null|string, location?: string, reader?: string, transaction_id: null|string}&StripeObject), zip?: (object{}&StripeObject)}&StripeObject) $payment_method_details Details about the payment method at the time of the transaction.
 * @property null|(object{presentment_amount: int, presentment_currency: string}&StripeObject) $presentment_details
 * @property null|(object{session?: string}&StripeObject) $radar_options Options to configure Radar. See <a href="https://stripe.com/docs/radar/radar-session">Radar Session</a> for more information.
 * @property null|string $receipt_email This is the email address that the receipt for this charge was sent to.
 * @property null|string $receipt_number This is the transaction number that appears on email receipts sent for this charge. This attribute will be <code>null</code> until a receipt has been sent.
 * @property null|string $receipt_url This is the URL to view the receipt for this charge. The receipt is kept up-to-date to the latest state of the charge, including any refunds. If the charge is for an Invoice, the receipt will be stylized as an Invoice receipt.
 * @property bool $refunded Whether the charge has been fully refunded. If the charge is only partially refunded, this attribute will still be false.
 * @property null|Collection<Refund> $refunds A list of refunds that have been applied to the charge.
 * @property null|Review|string $review ID of the review associated with this charge if one exists.
 * @property null|(object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), carrier?: null|string, name?: string, phone?: null|string, tracking_number?: null|string}&StripeObject) $shipping Shipping information for the charge.
 * @property null|Account|BankAccount|Card|Source $source This is a legacy field that will be removed in the future. It contains the Source, Card, or BankAccount object used for the charge. For details about the payment method used for this charge, refer to <code>payment_method</code> or <code>payment_method_details</code> instead.
 * @property null|string|Transfer $source_transfer The transfer ID which created this charge. Only present if the charge came from another Stripe account. <a href="https://docs.stripe.com/connect/destination-charges">See the Connect documentation</a> for details.
 * @property null|string $statement_descriptor <p>For a non-card charge, text that appears on the customer\'s statement as the statement descriptor. This value overrides the account\'s default statement descriptor. For information about requirements, including the 22-character limit, see <a href="https://docs.stripe.com/get-started/account/statement-descriptors">the Statement Descriptor docs</a>.</p><p>For a card charge, this value is ignored unless you don\'t specify a <code>statement_descriptor_suffix</code>, in which case this value is used as the suffix.</p>
 * @property null|string $statement_descriptor_suffix Provides information about a card charge. Concatenated to the account\'s <a href="https://docs.stripe.com/get-started/account/statement-descriptors#static">statement descriptor prefix</a> to form the complete statement descriptor that appears on the customer\'s statement. If the account has no prefix value, the suffix is concatenated to the account\'s statement descriptor.
 * @property string $status The status of the payment is either <code>succeeded</code>, <code>pending</code>, or <code>failed</code>.
 * @property null|string|Transfer $transfer ID of the transfer to the <code>destination</code> account (only applicable if the charge was created using the <code>destination</code> parameter).
 * @property null|(object{amount: null|int, destination: Account|string}&StripeObject) $transfer_data An optional dictionary including the account to automatically transfer to as part of a destination charge. <a href="https://stripe.com/docs/connect/destination-charges">See the Connect documentation</a> for details.
 * @property null|string $transfer_group A string that identifies this transaction as part of a group. See the <a href="https://stripe.com/docs/connect/separate-charges-and-transfers#transfer-options">Connect documentation</a> for details.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 62,
    'endLine' => 277,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\NestedResource',
      1 => 'Stripe\\ApiOperations\\Update',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'charge\'',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 27,
            'startFilePos' => 21158,
            'endTokenPos' => 27,
            'endFilePos' => 21165,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'STATUS_FAILED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'STATUS_FAILED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'failed\'',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 46,
            'startFilePos' => 21264,
            'endTokenPos' => 46,
            'endFilePos' => 21271,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 55,
            'startFilePos' => 21301,
            'endTokenPos' => 55,
            'endFilePos' => 21309,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'STATUS_SUCCEEDED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
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
            'startTokenPos' => 64,
            'startFilePos' => 21341,
            'endTokenPos' => 64,
            'endFilePos' => 21351,
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
      'DECLINED_AUTHENTICATION_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_AUTHENTICATION_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'authentication_required\'',
          'attributes' => 
          array (
            'startLine' => 168,
            'endLine' => 168,
            'startTokenPos' => 410,
            'startFilePos' => 25984,
            'endTokenPos' => 410,
            'endFilePos' => 26008,
          ),
        ),
        'docComment' => '/**
 * Possible string representations of decline codes.
 * These strings are applicable to the decline_code property of the \\Stripe\\Exception\\CardException exception.
 *
 * @see https://stripe.com/docs/declines/codes
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 71,
      ),
      'DECLINED_APPROVE_WITH_ID' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_APPROVE_WITH_ID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'approve_with_id\'',
          'attributes' => 
          array (
            'startLine' => 169,
            'endLine' => 169,
            'startTokenPos' => 419,
            'startFilePos' => 26048,
            'endTokenPos' => 419,
            'endFilePos' => 26064,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 169,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'DECLINED_CALL_ISSUER' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_CALL_ISSUER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'call_issuer\'',
          'attributes' => 
          array (
            'startLine' => 170,
            'endLine' => 170,
            'startTokenPos' => 428,
            'startFilePos' => 26100,
            'endTokenPos' => 428,
            'endFilePos' => 26112,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 170,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'DECLINED_CARD_NOT_SUPPORTED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_CARD_NOT_SUPPORTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card_not_supported\'',
          'attributes' => 
          array (
            'startLine' => 171,
            'endLine' => 171,
            'startTokenPos' => 437,
            'startFilePos' => 26155,
            'endTokenPos' => 437,
            'endFilePos' => 26174,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 171,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'DECLINED_CARD_VELOCITY_EXCEEDED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_CARD_VELOCITY_EXCEEDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card_velocity_exceeded\'',
          'attributes' => 
          array (
            'startLine' => 172,
            'endLine' => 172,
            'startTokenPos' => 446,
            'startFilePos' => 26221,
            'endTokenPos' => 446,
            'endFilePos' => 26244,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 172,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 69,
      ),
      'DECLINED_CURRENCY_NOT_SUPPORTED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_CURRENCY_NOT_SUPPORTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'currency_not_supported\'',
          'attributes' => 
          array (
            'startLine' => 173,
            'endLine' => 173,
            'startTokenPos' => 455,
            'startFilePos' => 26291,
            'endTokenPos' => 455,
            'endFilePos' => 26314,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 173,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 69,
      ),
      'DECLINED_DO_NOT_HONOR' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_DO_NOT_HONOR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'do_not_honor\'',
          'attributes' => 
          array (
            'startLine' => 174,
            'endLine' => 174,
            'startTokenPos' => 464,
            'startFilePos' => 26351,
            'endTokenPos' => 464,
            'endFilePos' => 26364,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 174,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'DECLINED_DO_NOT_TRY_AGAIN' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_DO_NOT_TRY_AGAIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'do_not_try_again\'',
          'attributes' => 
          array (
            'startLine' => 175,
            'endLine' => 175,
            'startTokenPos' => 473,
            'startFilePos' => 26405,
            'endTokenPos' => 473,
            'endFilePos' => 26422,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 175,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'DECLINED_DUPLICATED_TRANSACTION' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_DUPLICATED_TRANSACTION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'duplicate_transaction\'',
          'attributes' => 
          array (
            'startLine' => 176,
            'endLine' => 176,
            'startTokenPos' => 482,
            'startFilePos' => 26469,
            'endTokenPos' => 482,
            'endFilePos' => 26491,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 176,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 68,
      ),
      'DECLINED_EXPIRED_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_EXPIRED_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'expired_card\'',
          'attributes' => 
          array (
            'startLine' => 177,
            'endLine' => 177,
            'startTokenPos' => 491,
            'startFilePos' => 26528,
            'endTokenPos' => 491,
            'endFilePos' => 26541,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 177,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'DECLINED_FRAUDULENT' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_FRAUDULENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'fraudulent\'',
          'attributes' => 
          array (
            'startLine' => 178,
            'endLine' => 178,
            'startTokenPos' => 500,
            'startFilePos' => 26576,
            'endTokenPos' => 500,
            'endFilePos' => 26587,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 178,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'DECLINED_GENERIC_DECLINE' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_GENERIC_DECLINE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'generic_decline\'',
          'attributes' => 
          array (
            'startLine' => 179,
            'endLine' => 179,
            'startTokenPos' => 509,
            'startFilePos' => 26627,
            'endTokenPos' => 509,
            'endFilePos' => 26643,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 179,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'DECLINED_INCORRECT_NUMBER' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INCORRECT_NUMBER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'incorrect_number\'',
          'attributes' => 
          array (
            'startLine' => 180,
            'endLine' => 180,
            'startTokenPos' => 518,
            'startFilePos' => 26684,
            'endTokenPos' => 518,
            'endFilePos' => 26701,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 180,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'DECLINED_INCORRECT_CVC' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INCORRECT_CVC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'incorrect_cvc\'',
          'attributes' => 
          array (
            'startLine' => 181,
            'endLine' => 181,
            'startTokenPos' => 527,
            'startFilePos' => 26739,
            'endTokenPos' => 527,
            'endFilePos' => 26753,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 181,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'DECLINED_INCORRECT_PIN' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INCORRECT_PIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'incorrect_pin\'',
          'attributes' => 
          array (
            'startLine' => 182,
            'endLine' => 182,
            'startTokenPos' => 536,
            'startFilePos' => 26791,
            'endTokenPos' => 536,
            'endFilePos' => 26805,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 182,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'DECLINED_INCORRECT_ZIP' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INCORRECT_ZIP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'incorrect_zip\'',
          'attributes' => 
          array (
            'startLine' => 183,
            'endLine' => 183,
            'startTokenPos' => 545,
            'startFilePos' => 26843,
            'endTokenPos' => 545,
            'endFilePos' => 26857,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 183,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'DECLINED_INSUFFICIENT_FUNDS' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INSUFFICIENT_FUNDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'insufficient_funds\'',
          'attributes' => 
          array (
            'startLine' => 184,
            'endLine' => 184,
            'startTokenPos' => 554,
            'startFilePos' => 26900,
            'endTokenPos' => 554,
            'endFilePos' => 26919,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 184,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'DECLINED_INVALID_ACCOUNT' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INVALID_ACCOUNT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invalid_account\'',
          'attributes' => 
          array (
            'startLine' => 185,
            'endLine' => 185,
            'startTokenPos' => 563,
            'startFilePos' => 26959,
            'endTokenPos' => 563,
            'endFilePos' => 26975,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 185,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'DECLINED_INVALID_AMOUNT' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INVALID_AMOUNT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invalid_amount\'',
          'attributes' => 
          array (
            'startLine' => 186,
            'endLine' => 186,
            'startTokenPos' => 572,
            'startFilePos' => 27014,
            'endTokenPos' => 572,
            'endFilePos' => 27029,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 186,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'DECLINED_INVALID_CVC' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INVALID_CVC',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invalid_cvc\'',
          'attributes' => 
          array (
            'startLine' => 187,
            'endLine' => 187,
            'startTokenPos' => 581,
            'startFilePos' => 27065,
            'endTokenPos' => 581,
            'endFilePos' => 27077,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 187,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'DECLINED_INVALID_EXPIRY_YEAR' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INVALID_EXPIRY_YEAR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invalid_expiry_year\'',
          'attributes' => 
          array (
            'startLine' => 188,
            'endLine' => 188,
            'startTokenPos' => 590,
            'startFilePos' => 27121,
            'endTokenPos' => 590,
            'endFilePos' => 27141,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 188,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 63,
      ),
      'DECLINED_INVALID_NUMBER' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INVALID_NUMBER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invalid_number\'',
          'attributes' => 
          array (
            'startLine' => 189,
            'endLine' => 189,
            'startTokenPos' => 599,
            'startFilePos' => 27180,
            'endTokenPos' => 599,
            'endFilePos' => 27195,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 189,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'DECLINED_INVALID_PIN' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_INVALID_PIN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invalid_pin\'',
          'attributes' => 
          array (
            'startLine' => 190,
            'endLine' => 190,
            'startTokenPos' => 608,
            'startFilePos' => 27231,
            'endTokenPos' => 608,
            'endFilePos' => 27243,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 190,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'DECLINED_ISSUER_NOT_AVAILABLE' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_ISSUER_NOT_AVAILABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'issuer_not_available\'',
          'attributes' => 
          array (
            'startLine' => 191,
            'endLine' => 191,
            'startTokenPos' => 617,
            'startFilePos' => 27288,
            'endTokenPos' => 617,
            'endFilePos' => 27309,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 191,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 65,
      ),
      'DECLINED_LOST_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_LOST_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'lost_card\'',
          'attributes' => 
          array (
            'startLine' => 192,
            'endLine' => 192,
            'startTokenPos' => 626,
            'startFilePos' => 27343,
            'endTokenPos' => 626,
            'endFilePos' => 27353,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 192,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'DECLINED_MERCHANT_BLACKLIST' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_MERCHANT_BLACKLIST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'merchant_blacklist\'',
          'attributes' => 
          array (
            'startLine' => 193,
            'endLine' => 193,
            'startTokenPos' => 635,
            'startFilePos' => 27396,
            'endTokenPos' => 635,
            'endFilePos' => 27415,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 193,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'DECLINED_NEW_ACCOUNT_INFORMATION_AVAILABLE' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_NEW_ACCOUNT_INFORMATION_AVAILABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'new_account_information_available\'',
          'attributes' => 
          array (
            'startLine' => 194,
            'endLine' => 194,
            'startTokenPos' => 644,
            'startFilePos' => 27473,
            'endTokenPos' => 644,
            'endFilePos' => 27507,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 194,
        'endLine' => 194,
        'startColumn' => 5,
        'endColumn' => 91,
      ),
      'DECLINED_NO_ACTION_TAKEN' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_NO_ACTION_TAKEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'no_action_taken\'',
          'attributes' => 
          array (
            'startLine' => 195,
            'endLine' => 195,
            'startTokenPos' => 653,
            'startFilePos' => 27547,
            'endTokenPos' => 653,
            'endFilePos' => 27563,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 195,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'DECLINED_NOT_PERMITTED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_NOT_PERMITTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'not_permitted\'',
          'attributes' => 
          array (
            'startLine' => 196,
            'endLine' => 196,
            'startTokenPos' => 662,
            'startFilePos' => 27601,
            'endTokenPos' => 662,
            'endFilePos' => 27615,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 196,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'DECLINED_OFFLINE_PIN_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_OFFLINE_PIN_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'offline_pin_required\'',
          'attributes' => 
          array (
            'startLine' => 197,
            'endLine' => 197,
            'startTokenPos' => 671,
            'startFilePos' => 27660,
            'endTokenPos' => 671,
            'endFilePos' => 27681,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 197,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 65,
      ),
      'DECLINED_ONLINE_OR_OFFLINE_PIN_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_ONLINE_OR_OFFLINE_PIN_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'online_or_offline_pin_required\'',
          'attributes' => 
          array (
            'startLine' => 198,
            'endLine' => 198,
            'startTokenPos' => 680,
            'startFilePos' => 27736,
            'endTokenPos' => 680,
            'endFilePos' => 27767,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 198,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 85,
      ),
      'DECLINED_PICKUP_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_PICKUP_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pickup_card\'',
          'attributes' => 
          array (
            'startLine' => 199,
            'endLine' => 199,
            'startTokenPos' => 689,
            'startFilePos' => 27803,
            'endTokenPos' => 689,
            'endFilePos' => 27815,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 199,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'DECLINED_PIN_TRY_EXCEEDED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_PIN_TRY_EXCEEDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pin_try_exceeded\'',
          'attributes' => 
          array (
            'startLine' => 200,
            'endLine' => 200,
            'startTokenPos' => 698,
            'startFilePos' => 27856,
            'endTokenPos' => 698,
            'endFilePos' => 27873,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 200,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'DECLINED_PROCESSING_ERROR' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_PROCESSING_ERROR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'processing_error\'',
          'attributes' => 
          array (
            'startLine' => 201,
            'endLine' => 201,
            'startTokenPos' => 707,
            'startFilePos' => 27914,
            'endTokenPos' => 707,
            'endFilePos' => 27931,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 201,
        'endLine' => 201,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'DECLINED_REENTER_TRANSACTION' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_REENTER_TRANSACTION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'reenter_transaction\'',
          'attributes' => 
          array (
            'startLine' => 202,
            'endLine' => 202,
            'startTokenPos' => 716,
            'startFilePos' => 27975,
            'endTokenPos' => 716,
            'endFilePos' => 27995,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 202,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 63,
      ),
      'DECLINED_RESTRICTED_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_RESTRICTED_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'restricted_card\'',
          'attributes' => 
          array (
            'startLine' => 203,
            'endLine' => 203,
            'startTokenPos' => 725,
            'startFilePos' => 28035,
            'endTokenPos' => 725,
            'endFilePos' => 28051,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 203,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'DECLINED_REVOCATION_OF_ALL_AUTHORIZATIONS' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_REVOCATION_OF_ALL_AUTHORIZATIONS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'revocation_of_all_authorizations\'',
          'attributes' => 
          array (
            'startLine' => 204,
            'endLine' => 204,
            'startTokenPos' => 734,
            'startFilePos' => 28108,
            'endTokenPos' => 734,
            'endFilePos' => 28141,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 204,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 89,
      ),
      'DECLINED_REVOCATION_OF_AUTHORIZATION' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_REVOCATION_OF_AUTHORIZATION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'revocation_of_authorization\'',
          'attributes' => 
          array (
            'startLine' => 205,
            'endLine' => 205,
            'startTokenPos' => 743,
            'startFilePos' => 28193,
            'endTokenPos' => 743,
            'endFilePos' => 28221,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 205,
        'endLine' => 205,
        'startColumn' => 5,
        'endColumn' => 79,
      ),
      'DECLINED_SECURITY_VIOLATION' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_SECURITY_VIOLATION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'security_violation\'',
          'attributes' => 
          array (
            'startLine' => 206,
            'endLine' => 206,
            'startTokenPos' => 752,
            'startFilePos' => 28264,
            'endTokenPos' => 752,
            'endFilePos' => 28283,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 206,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'DECLINED_SERVICE_NOT_ALLOWED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_SERVICE_NOT_ALLOWED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'service_not_allowed\'',
          'attributes' => 
          array (
            'startLine' => 207,
            'endLine' => 207,
            'startTokenPos' => 761,
            'startFilePos' => 28327,
            'endTokenPos' => 761,
            'endFilePos' => 28347,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 207,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 63,
      ),
      'DECLINED_STOLEN_CARD' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_STOLEN_CARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'stolen_card\'',
          'attributes' => 
          array (
            'startLine' => 208,
            'endLine' => 208,
            'startTokenPos' => 770,
            'startFilePos' => 28383,
            'endTokenPos' => 770,
            'endFilePos' => 28395,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 208,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'DECLINED_STOP_PAYMENT_ORDER' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_STOP_PAYMENT_ORDER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'stop_payment_order\'',
          'attributes' => 
          array (
            'startLine' => 209,
            'endLine' => 209,
            'startTokenPos' => 779,
            'startFilePos' => 28438,
            'endTokenPos' => 779,
            'endFilePos' => 28457,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 209,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'DECLINED_TESTMODE_DECLINE' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_TESTMODE_DECLINE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'testmode_decline\'',
          'attributes' => 
          array (
            'startLine' => 210,
            'endLine' => 210,
            'startTokenPos' => 788,
            'startFilePos' => 28498,
            'endTokenPos' => 788,
            'endFilePos' => 28515,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 210,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'DECLINED_TRANSACTION_NOT_ALLOWED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_TRANSACTION_NOT_ALLOWED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'transaction_not_allowed\'',
          'attributes' => 
          array (
            'startLine' => 211,
            'endLine' => 211,
            'startTokenPos' => 797,
            'startFilePos' => 28563,
            'endTokenPos' => 797,
            'endFilePos' => 28587,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 211,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 71,
      ),
      'DECLINED_TRY_AGAIN_LATER' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_TRY_AGAIN_LATER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'try_again_later\'',
          'attributes' => 
          array (
            'startLine' => 212,
            'endLine' => 212,
            'startTokenPos' => 806,
            'startFilePos' => 28627,
            'endTokenPos' => 806,
            'endFilePos' => 28643,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 212,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'DECLINED_WITHDRAWAL_COUNT_LIMIT_EXCEEDED' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'DECLINED_WITHDRAWAL_COUNT_LIMIT_EXCEEDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'withdrawal_count_limit_exceeded\'',
          'attributes' => 
          array (
            'startLine' => 213,
            'endLine' => 213,
            'startTokenPos' => 815,
            'startFilePos' => 28699,
            'endTokenPos' => 815,
            'endFilePos' => 28731,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 213,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 87,
      ),
      'PATH_REFUNDS' => 
      array (
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'name' => 'PATH_REFUNDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/refunds\'',
          'attributes' => 
          array (
            'startLine' => 247,
            'endLine' => 247,
            'startTokenPos' => 964,
            'startFilePos' => 29659,
            'endTokenPos' => 964,
            'endFilePos' => 29668,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 247,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 36,
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
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 81,
                'startFilePos' => 22606,
                'endTokenPos' => 81,
                'endFilePos' => 22609,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
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
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 88,
                'startFilePos' => 22623,
                'endTokenPos' => 88,
                'endFilePos' => 22626,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
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
 * This method is no longer recommended—use the <a
 * href="/docs/api/payment_intents">Payment Intents API</a> to initiate a new
 * payment instead. Confirmation of the PaymentIntent creates the
 * <code>Charge</code> object used to request payment.
 *
 * @param null|array{amount?: int, application_fee?: int, application_fee_amount?: int, capture?: bool, currency?: string, customer?: string, description?: string, destination?: array{account: string, amount?: int}, expand?: string[], metadata?: null|array<string, string>, on_behalf_of?: string, radar_options?: array{session?: string}, receipt_email?: string, shipping?: array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, source?: string, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_data?: array{amount?: int, destination: string}, transfer_group?: string} $params
 * @param null|array|string $options
 *
 * @return Charge the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 86,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
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
                'startTokenPos' => 185,
                'startFilePos' => 23536,
                'endTokenPos' => 185,
                'endFilePos' => 23539,
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
                'startTokenPos' => 192,
                'startFilePos' => 23550,
                'endTokenPos' => 192,
                'endFilePos' => 23553,
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
 * Returns a list of charges you’ve previously created. The charges are returned in
 * sorted order, with the most recent charges appearing first.
 *
 * @param null|array{created?: array|int, customer?: string, ending_before?: string, expand?: string[], limit?: int, payment_intent?: string, starting_after?: string, transfer_group?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Charge> of ApiResources
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
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
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
            'startLine' => 129,
            'endLine' => 129,
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
                'startLine' => 129,
                'endLine' => 129,
                'startTokenPos' => 248,
                'startFilePos' => 24310,
                'endTokenPos' => 248,
                'endFilePos' => 24313,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
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
 * Retrieves the details of a charge that has previously been created. Supply the
 * unique charge ID that was returned from your previous request, and Stripe will
 * return the corresponding charge information. The same information is returned
 * when creating or refunding the charge.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Charge
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 129,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
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
            'startLine' => 150,
            'endLine' => 150,
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
                'startLine' => 150,
                'endLine' => 150,
                'startTokenPos' => 311,
                'startFilePos' => 25354,
                'endTokenPos' => 311,
                'endFilePos' => 25357,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 150,
            'endLine' => 150,
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
                'startLine' => 150,
                'endLine' => 150,
                'startTokenPos' => 318,
                'startFilePos' => 25368,
                'endTokenPos' => 318,
                'endFilePos' => 25371,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 150,
            'endLine' => 150,
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
 * Updates the specified charge by setting the values of the parameters passed. Any
 * parameters not provided will be left unchanged.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{customer?: string, description?: string, expand?: string[], fraud_details?: array{user_report: null|string}, metadata?: null|array<string, string>, receipt_email?: string, shipping?: array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, transfer_group?: string} $params
 * @param null|array|string $opts
 *
 * @return Charge the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 150,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
        'aliasName' => NULL,
      ),
      'capture' => 
      array (
        'name' => 'capture',
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
                'startLine' => 223,
                'endLine' => 223,
                'startTokenPos' => 830,
                'startFilePos' => 28980,
                'endTokenPos' => 830,
                'endFilePos' => 28983,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 223,
            'endLine' => 223,
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
                'startLine' => 223,
                'endLine' => 223,
                'startTokenPos' => 837,
                'startFilePos' => 28994,
                'endTokenPos' => 837,
                'endFilePos' => 28997,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 223,
            'endLine' => 223,
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
 * @return Charge the captured charge
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 223,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
        'aliasName' => NULL,
      ),
      'search' => 
      array (
        'name' => 'search',
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
                'startTokenPos' => 916,
                'startFilePos' => 29483,
                'endTokenPos' => 916,
                'endFilePos' => 29486,
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
            'startColumn' => 35,
            'endColumn' => 48,
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
                'startTokenPos' => 923,
                'startFilePos' => 29497,
                'endTokenPos' => 923,
                'endFilePos' => 29500,
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
            'startColumn' => 51,
            'endColumn' => 62,
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
 * @return SearchResult<Charge> the charge search results
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 240,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
        'aliasName' => NULL,
      ),
      'allRefunds' => 
      array (
        'name' => 'allRefunds',
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
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 39,
            'endColumn' => 41,
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
                'startLine' => 258,
                'endLine' => 258,
                'startTokenPos' => 984,
                'startFilePos' => 30023,
                'endTokenPos' => 984,
                'endFilePos' => 30026,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 44,
            'endColumn' => 57,
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
                'startLine' => 258,
                'endLine' => 258,
                'startTokenPos' => 991,
                'startFilePos' => 30037,
                'endTokenPos' => 991,
                'endFilePos' => 30040,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 60,
            'endColumn' => 71,
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
 * @param string $id the ID of the charge on which to retrieve the refunds
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<Refund> the list of refunds
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 258,
        'endLine' => 261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
        'aliasName' => NULL,
      ),
      'retrieveRefund' => 
      array (
        'name' => 'retrieveRefund',
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
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 43,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'refundId' => 
          array (
            'name' => 'refundId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 48,
            'endColumn' => 56,
            'parameterIndex' => 1,
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
                'startLine' => 273,
                'endLine' => 273,
                'startTokenPos' => 1039,
                'startFilePos' => 30534,
                'endTokenPos' => 1039,
                'endFilePos' => 30537,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 59,
            'endColumn' => 72,
            'parameterIndex' => 2,
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
                'startLine' => 273,
                'endLine' => 273,
                'startTokenPos' => 1046,
                'startFilePos' => 30548,
                'endTokenPos' => 1046,
                'endFilePos' => 30551,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 75,
            'endColumn' => 86,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string $id the ID of the charge to which the refund belongs
 * @param string $refundId the ID of the refund to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Refund
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 273,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Charge',
        'implementingClassName' => 'Stripe\\Charge',
        'currentClassName' => 'Stripe\\Charge',
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