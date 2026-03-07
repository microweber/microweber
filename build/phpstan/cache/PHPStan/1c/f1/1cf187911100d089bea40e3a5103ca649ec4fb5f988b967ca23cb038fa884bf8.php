<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Checkout/Session.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Checkout\Session
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7ea28c19c9ae58dd7e45860cdafbb57a6a6cf092cc0d22e1127719619c901356-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Checkout\\Session',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Checkout/Session.php',
      ),
    ),
    'namespace' => 'Stripe\\Checkout',
    'name' => 'Stripe\\Checkout\\Session',
    'shortName' => 'Session',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A Checkout Session represents your customer\'s session as they pay for
 * one-time purchases or subscriptions through <a href="https://stripe.com/docs/payments/checkout">Checkout</a>
 * or <a href="https://stripe.com/docs/payments/payment-links">Payment Links</a>. We recommend creating a
 * new Session each time your customer attempts to pay.
 *
 * Once payment is successful, the Checkout Session will contain a reference
 * to the <a href="https://stripe.com/docs/api/customers">Customer</a>, and either the successful
 * <a href="https://stripe.com/docs/api/payment_intents">PaymentIntent</a> or an active
 * <a href="https://stripe.com/docs/api/subscriptions">Subscription</a>.
 *
 * You can create a Checkout Session on your server and redirect to its URL
 * to begin Checkout.
 *
 * Related guide: <a href="https://stripe.com/docs/checkout/quickstart">Checkout quickstart</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|(object{enabled: bool}&\\Stripe\\StripeObject) $adaptive_pricing Settings for price localization with <a href="https://docs.stripe.com/payments/checkout/adaptive-pricing">Adaptive Pricing</a>.
 * @property null|(object{recovery: null|(object{allow_promotion_codes: bool, enabled: bool, expires_at: null|int, url: null|string}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $after_expiration When set, provides configuration for actions to take if this Checkout Session expires.
 * @property null|bool $allow_promotion_codes Enables user redeemable promotion codes.
 * @property null|int $amount_subtotal Total of all items before discounts or taxes are applied.
 * @property null|int $amount_total Total of all items after discounts and taxes are applied.
 * @property (object{enabled: bool, liability: null|(object{account?: string|\\Stripe\\Account, type: string}&\\Stripe\\StripeObject), provider: null|string, status: null|string}&\\Stripe\\StripeObject) $automatic_tax
 * @property null|string $billing_address_collection Describes whether Checkout should collect the customer\'s billing address. Defaults to <code>auto</code>.
 * @property null|string $cancel_url If set, Checkout displays a back button and customers will be directed to this URL if they decide to cancel payment and return to your website.
 * @property null|string $client_reference_id A unique string to reference the Checkout Session. This can be a customer ID, a cart ID, or similar, and can be used to reconcile the Session with your internal systems.
 * @property null|string $client_secret The client secret of your Checkout Session. Applies to Checkout Sessions with <code>ui_mode: embedded</code> or <code>ui_mode: custom</code>. For <code>ui_mode: embedded</code>, the client secret is to be used when initializing Stripe.js embedded checkout. For <code>ui_mode: custom</code>, use the client secret with <a href="https://stripe.com/docs/js/custom_checkout/init">initCheckout</a> on your front end.
 * @property null|(object{shipping_details: null|(object{address: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&\\Stripe\\StripeObject), name: string}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $collected_information Information about the customer collected within the Checkout Session.
 * @property null|(object{promotions: null|string, terms_of_service: null|string}&\\Stripe\\StripeObject) $consent Results of <code>consent_collection</code> for this session.
 * @property null|(object{payment_method_reuse_agreement: null|(object{position: string}&\\Stripe\\StripeObject), promotions: null|string, terms_of_service: null|string}&\\Stripe\\StripeObject) $consent_collection When set, provides configuration for the Checkout Session to gather active consent from customers.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property null|(object{amount_subtotal: int, amount_total: int, fx_rate: string, source_currency: string}&\\Stripe\\StripeObject) $currency_conversion Currency conversion details for <a href="https://docs.stripe.com/payments/checkout/adaptive-pricing">Adaptive Pricing</a> sessions created before 2025-03-31.
 * @property ((object{dropdown?: (object{default_value: null|string, options: (object{label: string, value: string}&\\Stripe\\StripeObject)[], value: null|string}&\\Stripe\\StripeObject), key: string, label: (object{custom: null|string, type: string}&\\Stripe\\StripeObject), numeric?: (object{default_value: null|string, maximum_length: null|int, minimum_length: null|int, value: null|string}&\\Stripe\\StripeObject), optional: bool, text?: (object{default_value: null|string, maximum_length: null|int, minimum_length: null|int, value: null|string}&\\Stripe\\StripeObject), type: string}&\\Stripe\\StripeObject))[] $custom_fields Collect additional information from your customer using custom fields. Up to 3 fields are supported.
 * @property (object{after_submit: null|(object{message: string}&\\Stripe\\StripeObject), shipping_address: null|(object{message: string}&\\Stripe\\StripeObject), submit: null|(object{message: string}&\\Stripe\\StripeObject), terms_of_service_acceptance: null|(object{message: string}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $custom_text
 * @property null|string|\\Stripe\\Customer $customer The ID of the customer for this Session. For Checkout Sessions in <code>subscription</code> mode or Checkout Sessions with <code>customer_creation</code> set as <code>always</code> in <code>payment</code> mode, Checkout will create a new customer object based on information provided during the payment flow unless an existing customer was provided when the Session was created.
 * @property null|string $customer_creation Configure whether a Checkout Session creates a Customer when the Checkout Session completes.
 * @property null|(object{address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&\\Stripe\\StripeObject), email: null|string, name: null|string, phone: null|string, tax_exempt: null|string, tax_ids: null|((object{type: string, value: null|string}&\\Stripe\\StripeObject))[]}&\\Stripe\\StripeObject) $customer_details The customer details including the customer\'s tax exempt status and the customer\'s tax IDs. Customer\'s address details are not present on Sessions in <code>setup</code> mode.
 * @property null|string $customer_email If provided, this value will be used when the Customer object is created. If not provided, customers will be asked to enter their email address. Use this parameter to prefill customer data if you already have an email on file. To access information about the customer once the payment flow is complete, use the <code>customer</code> attribute.
 * @property null|((object{coupon: null|string|\\Stripe\\Coupon, promotion_code: null|string|\\Stripe\\PromotionCode}&\\Stripe\\StripeObject))[] $discounts List of coupons and promotion codes attached to the Checkout Session.
 * @property int $expires_at The timestamp at which the Checkout Session will expire.
 * @property null|string|\\Stripe\\Invoice $invoice ID of the invoice created by the Checkout Session, if it exists.
 * @property null|(object{enabled: bool, invoice_data: (object{account_tax_ids: null|(string|\\Stripe\\TaxId)[], custom_fields: null|(object{name: string, value: string}&\\Stripe\\StripeObject)[], description: null|string, footer: null|string, issuer: null|(object{account?: string|\\Stripe\\Account, type: string}&\\Stripe\\StripeObject), metadata: null|\\Stripe\\StripeObject, rendering_options: null|(object{amount_tax_display: null|string, template: null|string}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $invoice_creation Details on the state of invoice creation for the Checkout Session.
 * @property null|\\Stripe\\Collection<\\Stripe\\LineItem> $line_items The line items purchased by the customer.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|string $locale The IETF language tag of the locale Checkout is displayed in. If blank or <code>auto</code>, the browser\'s locale is used.
 * @property null|\\Stripe\\StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property string $mode The mode of the Checkout Session.
 * @property null|((object{adjustable_quantity: null|(object{enabled: bool, maximum: null|int, minimum: null|int}&\\Stripe\\StripeObject), price: string, quantity: int}&\\Stripe\\StripeObject))[] $optional_items The optional items presented to the customer at checkout.
 * @property null|string $origin_context Where the user is coming from. This informs the optimizations that are applied to the session.
 * @property null|string|\\Stripe\\PaymentIntent $payment_intent The ID of the PaymentIntent for Checkout Sessions in <code>payment</code> mode. You can\'t confirm or cancel the PaymentIntent for a Checkout Session. To cancel, <a href="https://stripe.com/docs/api/checkout/sessions/expire">expire the Checkout Session</a> instead.
 * @property null|string|\\Stripe\\PaymentLink $payment_link The ID of the Payment Link that created this Session.
 * @property null|string $payment_method_collection Configure whether a Checkout Session should collect a payment method. Defaults to <code>always</code>.
 * @property null|(object{id: string, parent: null|string}&\\Stripe\\StripeObject) $payment_method_configuration_details Information about the payment method configuration used for this Checkout session if using dynamic payment methods.
 * @property null|(object{acss_debit?: (object{currency?: string, mandate_options?: (object{custom_mandate_url?: string, default_for?: string[], interval_description: null|string, payment_schedule: null|string, transaction_type: null|string}&\\Stripe\\StripeObject), setup_future_usage?: string, target_date?: string, verification_method?: string}&\\Stripe\\StripeObject), affirm?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), afterpay_clearpay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), alipay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), amazon_pay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), au_becs_debit?: (object{setup_future_usage?: string, target_date?: string}&\\Stripe\\StripeObject), bacs_debit?: (object{mandate_options?: (object{reference_prefix?: string}&\\Stripe\\StripeObject), setup_future_usage?: string, target_date?: string}&\\Stripe\\StripeObject), bancontact?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), boleto?: (object{expires_after_days: int, setup_future_usage?: string}&\\Stripe\\StripeObject), card?: (object{installments?: (object{enabled?: bool}&\\Stripe\\StripeObject), request_extended_authorization?: string, request_incremental_authorization?: string, request_multicapture?: string, request_overcapture?: string, request_three_d_secure: string, restrictions?: (object{brands_blocked?: string[]}&\\Stripe\\StripeObject), setup_future_usage?: string, statement_descriptor_suffix_kana?: string, statement_descriptor_suffix_kanji?: string}&\\Stripe\\StripeObject), cashapp?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), customer_balance?: (object{bank_transfer?: (object{eu_bank_transfer?: (object{country: string}&\\Stripe\\StripeObject), requested_address_types?: string[], type: null|string}&\\Stripe\\StripeObject), funding_type: null|string, setup_future_usage?: string}&\\Stripe\\StripeObject), eps?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), fpx?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), giropay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), grabpay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), ideal?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), kakao_pay?: (object{capture_method?: string, setup_future_usage?: string}&\\Stripe\\StripeObject), klarna?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), konbini?: (object{expires_after_days: null|int, setup_future_usage?: string}&\\Stripe\\StripeObject), kr_card?: (object{capture_method?: string, setup_future_usage?: string}&\\Stripe\\StripeObject), link?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), mobilepay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), multibanco?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), naver_pay?: (object{capture_method?: string, setup_future_usage?: string}&\\Stripe\\StripeObject), oxxo?: (object{expires_after_days: int, setup_future_usage?: string}&\\Stripe\\StripeObject), p24?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), payco?: (object{capture_method?: string}&\\Stripe\\StripeObject), paynow?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), paypal?: (object{capture_method?: string, preferred_locale: null|string, reference: null|string, setup_future_usage?: string}&\\Stripe\\StripeObject), pix?: (object{amount_includes_iof?: string, expires_after_seconds: null|int, setup_future_usage?: string}&\\Stripe\\StripeObject), revolut_pay?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), samsung_pay?: (object{capture_method?: string}&\\Stripe\\StripeObject), sepa_debit?: (object{mandate_options?: (object{reference_prefix?: string}&\\Stripe\\StripeObject), setup_future_usage?: string, target_date?: string}&\\Stripe\\StripeObject), sofort?: (object{setup_future_usage?: string}&\\Stripe\\StripeObject), swish?: (object{reference: null|string}&\\Stripe\\StripeObject), us_bank_account?: (object{financial_connections?: (object{filters?: (object{account_subcategories?: string[]}&\\Stripe\\StripeObject), permissions?: string[], prefetch: null|string[], return_url?: string}&\\Stripe\\StripeObject), setup_future_usage?: string, target_date?: string, verification_method?: string}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $payment_method_options Payment-method-specific configuration for the PaymentIntent or SetupIntent of this CheckoutSession.
 * @property string[] $payment_method_types A list of the types of payment methods (e.g. card) this Checkout Session is allowed to accept.
 * @property string $payment_status The payment status of the Checkout Session, one of <code>paid</code>, <code>unpaid</code>, or <code>no_payment_required</code>. You can use this value to decide when to fulfill your customer\'s order.
 * @property null|(object{update_shipping_details: null|string}&\\Stripe\\StripeObject) $permissions <p>This property is used to set up permissions for various actions (e.g., update) on the CheckoutSession object.</p><p>For specific permissions, please refer to their dedicated subsections, such as <code>permissions.update_shipping_details</code>.</p>
 * @property null|(object{enabled: bool}&\\Stripe\\StripeObject) $phone_number_collection
 * @property null|(object{presentment_amount: int, presentment_currency: string}&\\Stripe\\StripeObject) $presentment_details
 * @property null|string $recovered_from The ID of the original expired Checkout Session that triggered the recovery flow.
 * @property null|string $redirect_on_completion This parameter applies to <code>ui_mode: embedded</code>. Learn more about the <a href="https://stripe.com/docs/payments/checkout/custom-success-page?payment-ui=embedded-form">redirect behavior</a> of embedded sessions. Defaults to <code>always</code>.
 * @property null|string $return_url Applies to Checkout Sessions with <code>ui_mode: embedded</code> or <code>ui_mode: custom</code>. The URL to redirect your customer back to after they authenticate or cancel their payment on the payment method\'s app or site.
 * @property null|(object{allow_redisplay_filters: null|string[], payment_method_remove: null|string, payment_method_save: null|string}&\\Stripe\\StripeObject) $saved_payment_method_options Controls saved payment method settings for the session. Only available in <code>payment</code> and <code>subscription</code> mode.
 * @property null|string|\\Stripe\\SetupIntent $setup_intent The ID of the SetupIntent for Checkout Sessions in <code>setup</code> mode. You can\'t confirm or cancel the SetupIntent for a Checkout Session. To cancel, <a href="https://stripe.com/docs/api/checkout/sessions/expire">expire the Checkout Session</a> instead.
 * @property null|(object{allowed_countries: string[]}&\\Stripe\\StripeObject) $shipping_address_collection When set, provides configuration for Checkout to collect a shipping address from a customer.
 * @property null|(object{amount_subtotal: int, amount_tax: int, amount_total: int, shipping_rate: null|string|\\Stripe\\ShippingRate, taxes?: ((object{amount: int, rate: \\Stripe\\TaxRate, taxability_reason: null|string, taxable_amount: null|int}&\\Stripe\\StripeObject))[]}&\\Stripe\\StripeObject) $shipping_cost The details of the customer cost of shipping, including the customer chosen ShippingRate.
 * @property ((object{shipping_amount: int, shipping_rate: string|\\Stripe\\ShippingRate}&\\Stripe\\StripeObject))[] $shipping_options The shipping rate options applied to this Session.
 * @property null|string $status The status of the Checkout Session, one of <code>open</code>, <code>complete</code>, or <code>expired</code>.
 * @property null|string $submit_type Describes the type of transaction being performed by Checkout in order to customize relevant text on the page, such as the submit button. <code>submit_type</code> can only be specified on Checkout Sessions in <code>payment</code> mode. If blank or <code>auto</code>, <code>pay</code> is used.
 * @property null|string|\\Stripe\\Subscription $subscription The ID of the <a href="https://stripe.com/docs/api/subscriptions">Subscription</a> for Checkout Sessions in <code>subscription</code> mode.
 * @property null|string $success_url The URL the customer will be directed to after the payment or subscription creation is successful.
 * @property null|(object{enabled: bool, required: string}&\\Stripe\\StripeObject) $tax_id_collection
 * @property null|(object{amount_discount: int, amount_shipping: null|int, amount_tax: int, breakdown?: (object{discounts: (object{amount: int, discount: \\Stripe\\Discount}&\\Stripe\\StripeObject)[], taxes: ((object{amount: int, rate: \\Stripe\\TaxRate, taxability_reason: null|string, taxable_amount: null|int}&\\Stripe\\StripeObject))[]}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $total_details Tax and discount details for the computed total amount.
 * @property null|string $ui_mode The UI mode of the Session. Defaults to <code>hosted</code>.
 * @property null|string $url The URL to the Checkout Session. Applies to Checkout Sessions with <code>ui_mode: hosted</code>. Redirect customers to this URL to take them to Checkout. If you’re using <a href="https://stripe.com/docs/payments/checkout/custom-domains">Custom Domains</a>, the URL will use your subdomain. Otherwise, it’ll use <code>checkout.stripe.com.</code> This value is only present when the session is active.
 * @property null|(object{link?: (object{display?: string}&\\Stripe\\StripeObject)}&\\Stripe\\StripeObject) $wallet_options Wallet-specific configuration for this Checkout Session.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 86,
    'endLine' => 249,
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
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'checkout.session\'',
          'attributes' => 
          array (
            'startLine' => 88,
            'endLine' => 88,
            'startTokenPos' => 27,
            'startFilePos' => 19808,
            'endTokenPos' => 27,
            'endFilePos' => 19825,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'BILLING_ADDRESS_COLLECTION_AUTO' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'BILLING_ADDRESS_COLLECTION_AUTO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'auto\'',
          'attributes' => 
          array (
            'startLine' => 92,
            'endLine' => 92,
            'startTokenPos' => 41,
            'startFilePos' => 19912,
            'endTokenPos' => 41,
            'endFilePos' => 19917,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'BILLING_ADDRESS_COLLECTION_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'BILLING_ADDRESS_COLLECTION_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'required\'',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 50,
            'startFilePos' => 19968,
            'endTokenPos' => 50,
            'endFilePos' => 19977,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 59,
      ),
      'CUSTOMER_CREATION_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'CUSTOMER_CREATION_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 95,
            'startTokenPos' => 59,
            'startFilePos' => 20018,
            'endTokenPos' => 59,
            'endFilePos' => 20025,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'CUSTOMER_CREATION_IF_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'CUSTOMER_CREATION_IF_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'if_required\'',
          'attributes' => 
          array (
            'startLine' => 96,
            'endLine' => 96,
            'startTokenPos' => 68,
            'startFilePos' => 20070,
            'endTokenPos' => 68,
            'endFilePos' => 20082,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'MODE_PAYMENT' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'MODE_PAYMENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'payment\'',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 77,
            'startFilePos' => 20111,
            'endTokenPos' => 77,
            'endFilePos' => 20119,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'MODE_SETUP' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'MODE_SETUP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'setup\'',
          'attributes' => 
          array (
            'startLine' => 99,
            'endLine' => 99,
            'startTokenPos' => 86,
            'startFilePos' => 20145,
            'endTokenPos' => 86,
            'endFilePos' => 20151,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'MODE_SUBSCRIPTION' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'MODE_SUBSCRIPTION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription\'',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 95,
            'startFilePos' => 20184,
            'endTokenPos' => 95,
            'endFilePos' => 20197,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'ORIGIN_CONTEXT_MOBILE_APP' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'ORIGIN_CONTEXT_MOBILE_APP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mobile_app\'',
          'attributes' => 
          array (
            'startLine' => 102,
            'endLine' => 102,
            'startTokenPos' => 104,
            'startFilePos' => 20239,
            'endTokenPos' => 104,
            'endFilePos' => 20250,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'ORIGIN_CONTEXT_WEB' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'ORIGIN_CONTEXT_WEB',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'web\'',
          'attributes' => 
          array (
            'startLine' => 103,
            'endLine' => 103,
            'startTokenPos' => 113,
            'startFilePos' => 20284,
            'endTokenPos' => 113,
            'endFilePos' => 20288,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 103,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'PAYMENT_METHOD_COLLECTION_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'PAYMENT_METHOD_COLLECTION_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 105,
            'endLine' => 105,
            'startTokenPos' => 122,
            'startFilePos' => 20337,
            'endTokenPos' => 122,
            'endFilePos' => 20344,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'PAYMENT_METHOD_COLLECTION_IF_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'PAYMENT_METHOD_COLLECTION_IF_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'if_required\'',
          'attributes' => 
          array (
            'startLine' => 106,
            'endLine' => 106,
            'startTokenPos' => 131,
            'startFilePos' => 20397,
            'endTokenPos' => 131,
            'endFilePos' => 20409,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
      'PAYMENT_STATUS_NO_PAYMENT_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'PAYMENT_STATUS_NO_PAYMENT_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'no_payment_required\'',
          'attributes' => 
          array (
            'startLine' => 108,
            'endLine' => 108,
            'startTokenPos' => 140,
            'startFilePos' => 20460,
            'endTokenPos' => 140,
            'endFilePos' => 20480,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 69,
      ),
      'PAYMENT_STATUS_PAID' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'PAYMENT_STATUS_PAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'paid\'',
          'attributes' => 
          array (
            'startLine' => 109,
            'endLine' => 109,
            'startTokenPos' => 149,
            'startFilePos' => 20515,
            'endTokenPos' => 149,
            'endFilePos' => 20520,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'PAYMENT_STATUS_UNPAID' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'PAYMENT_STATUS_UNPAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unpaid\'',
          'attributes' => 
          array (
            'startLine' => 110,
            'endLine' => 110,
            'startTokenPos' => 158,
            'startFilePos' => 20557,
            'endTokenPos' => 158,
            'endFilePos' => 20564,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 110,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'REDIRECT_ON_COMPLETION_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'REDIRECT_ON_COMPLETION_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 112,
            'endLine' => 112,
            'startTokenPos' => 167,
            'startFilePos' => 20610,
            'endTokenPos' => 167,
            'endFilePos' => 20617,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'REDIRECT_ON_COMPLETION_IF_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'REDIRECT_ON_COMPLETION_IF_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'if_required\'',
          'attributes' => 
          array (
            'startLine' => 113,
            'endLine' => 113,
            'startTokenPos' => 176,
            'startFilePos' => 20667,
            'endTokenPos' => 176,
            'endFilePos' => 20679,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'REDIRECT_ON_COMPLETION_NEVER' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'REDIRECT_ON_COMPLETION_NEVER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'never\'',
          'attributes' => 
          array (
            'startLine' => 114,
            'endLine' => 114,
            'startTokenPos' => 185,
            'startFilePos' => 20723,
            'endTokenPos' => 185,
            'endFilePos' => 20729,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 114,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'STATUS_COMPLETE' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'STATUS_COMPLETE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'complete\'',
          'attributes' => 
          array (
            'startLine' => 116,
            'endLine' => 116,
            'startTokenPos' => 194,
            'startFilePos' => 20761,
            'endTokenPos' => 194,
            'endFilePos' => 20770,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 116,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_EXPIRED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'STATUS_EXPIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'expired\'',
          'attributes' => 
          array (
            'startLine' => 117,
            'endLine' => 117,
            'startTokenPos' => 203,
            'startFilePos' => 20800,
            'endTokenPos' => 203,
            'endFilePos' => 20808,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 117,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'STATUS_OPEN' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'STATUS_OPEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'open\'',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 118,
            'startTokenPos' => 212,
            'startFilePos' => 20835,
            'endTokenPos' => 212,
            'endFilePos' => 20840,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'SUBMIT_TYPE_AUTO' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'SUBMIT_TYPE_AUTO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'auto\'',
          'attributes' => 
          array (
            'startLine' => 120,
            'endLine' => 120,
            'startTokenPos' => 221,
            'startFilePos' => 20873,
            'endTokenPos' => 221,
            'endFilePos' => 20878,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 120,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'SUBMIT_TYPE_BOOK' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'SUBMIT_TYPE_BOOK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'book\'',
          'attributes' => 
          array (
            'startLine' => 121,
            'endLine' => 121,
            'startTokenPos' => 230,
            'startFilePos' => 20910,
            'endTokenPos' => 230,
            'endFilePos' => 20915,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 121,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'SUBMIT_TYPE_DONATE' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'SUBMIT_TYPE_DONATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'donate\'',
          'attributes' => 
          array (
            'startLine' => 122,
            'endLine' => 122,
            'startTokenPos' => 239,
            'startFilePos' => 20949,
            'endTokenPos' => 239,
            'endFilePos' => 20956,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 122,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'SUBMIT_TYPE_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'SUBMIT_TYPE_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pay\'',
          'attributes' => 
          array (
            'startLine' => 123,
            'endLine' => 123,
            'startTokenPos' => 248,
            'startFilePos' => 20987,
            'endTokenPos' => 248,
            'endFilePos' => 20991,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 123,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'SUBMIT_TYPE_SUBSCRIBE' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'SUBMIT_TYPE_SUBSCRIBE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscribe\'',
          'attributes' => 
          array (
            'startLine' => 124,
            'endLine' => 124,
            'startTokenPos' => 257,
            'startFilePos' => 21028,
            'endTokenPos' => 257,
            'endFilePos' => 21038,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 124,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'UI_MODE_CUSTOM' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'UI_MODE_CUSTOM',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'custom\'',
          'attributes' => 
          array (
            'startLine' => 126,
            'endLine' => 126,
            'startTokenPos' => 266,
            'startFilePos' => 21069,
            'endTokenPos' => 266,
            'endFilePos' => 21076,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'UI_MODE_EMBEDDED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'UI_MODE_EMBEDDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'embedded\'',
          'attributes' => 
          array (
            'startLine' => 127,
            'endLine' => 127,
            'startTokenPos' => 275,
            'startFilePos' => 21108,
            'endTokenPos' => 275,
            'endFilePos' => 21117,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 127,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'UI_MODE_HOSTED' => 
      array (
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'name' => 'UI_MODE_HOSTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'hosted\'',
          'attributes' => 
          array (
            'startLine' => 128,
            'endLine' => 128,
            'startTokenPos' => 284,
            'startFilePos' => 21147,
            'endTokenPos' => 284,
            'endFilePos' => 21154,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 128,
        'endLine' => 128,
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
                'startLine' => 140,
                'endLine' => 140,
                'startTokenPos' => 301,
                'startFilePos' => 29609,
                'endTokenPos' => 301,
                'endFilePos' => 29612,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
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
                'startLine' => 140,
                'endLine' => 140,
                'startTokenPos' => 308,
                'startFilePos' => 29626,
                'endTokenPos' => 308,
                'endFilePos' => 29629,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
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
 * Creates a Checkout Session object.
 *
 * @param null|array{adaptive_pricing?: array{enabled?: bool}, after_expiration?: array{recovery?: array{allow_promotion_codes?: bool, enabled: bool}}, allow_promotion_codes?: bool, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_address_collection?: string, cancel_url?: string, client_reference_id?: string, consent_collection?: array{payment_method_reuse_agreement?: array{position: string}, promotions?: string, terms_of_service?: string}, currency?: string, custom_fields?: array{dropdown?: array{default_value?: string, options: array{label: string, value: string}[]}, key: string, label: array{custom: string, type: string}, numeric?: array{default_value?: string, maximum_length?: int, minimum_length?: int}, optional?: bool, text?: array{default_value?: string, maximum_length?: int, minimum_length?: int}, type: string}[], custom_text?: array{after_submit?: null|array{message: string}, shipping_address?: null|array{message: string}, submit?: null|array{message: string}, terms_of_service_acceptance?: null|array{message: string}}, customer?: string, customer_creation?: string, customer_email?: string, customer_update?: array{address?: string, name?: string, shipping?: string}, discounts?: array{coupon?: string, promotion_code?: string}[], expand?: string[], expires_at?: int, invoice_creation?: array{enabled: bool, invoice_data?: array{account_tax_ids?: null|string[], custom_fields?: null|array{name: string, value: string}[], description?: string, footer?: string, issuer?: array{account?: string, type: string}, metadata?: array<string, string>, rendering_options?: null|array{amount_tax_display?: null|string, template?: string}}}, line_items?: array{adjustable_quantity?: array{enabled: bool, maximum?: int, minimum?: int}, dynamic_tax_rates?: string[], price?: string, price_data?: array{currency: string, product?: string, product_data?: array{description?: string, images?: string[], metadata?: array<string, string>, name: string, tax_code?: string}, recurring?: array{interval: string, interval_count?: int}, tax_behavior?: string, unit_amount?: int, unit_amount_decimal?: string}, quantity?: int, tax_rates?: string[]}[], locale?: string, metadata?: array<string, string>, mode?: string, optional_items?: array{adjustable_quantity?: array{enabled: bool, maximum?: int, minimum?: int}, price: string, quantity: int}[], origin_context?: string, payment_intent_data?: array{application_fee_amount?: int, capture_method?: string, description?: string, metadata?: array<string, string>, on_behalf_of?: string, receipt_email?: string, setup_future_usage?: string, shipping?: array{address: array{city?: string, country?: string, line1: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_data?: array{amount?: int, destination: string}, transfer_group?: string}, payment_method_collection?: string, payment_method_configuration?: string, payment_method_data?: array{allow_redisplay?: string}, payment_method_options?: array{acss_debit?: array{currency?: string, mandate_options?: array{custom_mandate_url?: null|string, default_for?: string[], interval_description?: string, payment_schedule?: string, transaction_type?: string}, setup_future_usage?: string, target_date?: string, verification_method?: string}, affirm?: array{setup_future_usage?: string}, afterpay_clearpay?: array{setup_future_usage?: string}, alipay?: array{setup_future_usage?: string}, amazon_pay?: array{setup_future_usage?: string}, au_becs_debit?: array{setup_future_usage?: string, target_date?: string}, bacs_debit?: array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: string, target_date?: string}, bancontact?: array{setup_future_usage?: string}, boleto?: array{expires_after_days?: int, setup_future_usage?: string}, card?: array{installments?: array{enabled?: bool}, request_extended_authorization?: string, request_incremental_authorization?: string, request_multicapture?: string, request_overcapture?: string, request_three_d_secure?: string, restrictions?: array{brands_blocked?: string[]}, setup_future_usage?: string, statement_descriptor_suffix_kana?: string, statement_descriptor_suffix_kanji?: string}, cashapp?: array{setup_future_usage?: string}, customer_balance?: array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, requested_address_types?: string[], type: string}, funding_type?: string, setup_future_usage?: string}, eps?: array{setup_future_usage?: string}, fpx?: array{setup_future_usage?: string}, giropay?: array{setup_future_usage?: string}, grabpay?: array{setup_future_usage?: string}, ideal?: array{setup_future_usage?: string}, kakao_pay?: array{capture_method?: string, setup_future_usage?: string}, klarna?: array{setup_future_usage?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing: array{amount: int, date: string}, reference: string}[]}, konbini?: array{expires_after_days?: int, setup_future_usage?: string}, kr_card?: array{capture_method?: string, setup_future_usage?: string}, link?: array{setup_future_usage?: string}, mobilepay?: array{setup_future_usage?: string}, multibanco?: array{setup_future_usage?: string}, naver_pay?: array{capture_method?: string, setup_future_usage?: string}, oxxo?: array{expires_after_days?: int, setup_future_usage?: string}, p24?: array{setup_future_usage?: string, tos_shown_and_accepted?: bool}, pay_by_bank?: array{}, payco?: array{capture_method?: string}, paynow?: array{setup_future_usage?: string}, paypal?: array{capture_method?: null|string, preferred_locale?: string, reference?: string, risk_correlation_id?: string, setup_future_usage?: null|string}, pix?: array{amount_includes_iof?: string, expires_after_seconds?: int, setup_future_usage?: string}, revolut_pay?: array{setup_future_usage?: string}, samsung_pay?: array{capture_method?: string}, sepa_debit?: array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: string, target_date?: string}, sofort?: array{setup_future_usage?: string}, swish?: array{reference?: string}, us_bank_account?: array{financial_connections?: array{permissions?: string[], prefetch?: string[]}, setup_future_usage?: string, target_date?: string, verification_method?: string}, wechat_pay?: array{app_id?: string, client: string, setup_future_usage?: string}}, payment_method_types?: string[], permissions?: array{update_shipping_details?: string}, phone_number_collection?: array{enabled: bool}, redirect_on_completion?: string, return_url?: string, saved_payment_method_options?: array{allow_redisplay_filters?: string[], payment_method_remove?: string, payment_method_save?: string}, setup_intent_data?: array{description?: string, metadata?: array<string, string>, on_behalf_of?: string}, shipping_address_collection?: array{allowed_countries: string[]}, shipping_options?: array{shipping_rate?: string, shipping_rate_data?: array{delivery_estimate?: array{maximum?: array{unit: string, value: int}, minimum?: array{unit: string, value: int}}, display_name: string, fixed_amount?: array{amount: int, currency: string, currency_options?: array<string, array{amount: int, tax_behavior?: string}>}, metadata?: array<string, string>, tax_behavior?: string, tax_code?: string, type?: string}}[], submit_type?: string, subscription_data?: array{application_fee_percent?: float, billing_cycle_anchor?: int, billing_mode?: array{type: string}, default_tax_rates?: string[], description?: string, invoice_settings?: array{issuer?: array{account?: string, type: string}}, metadata?: array<string, string>, on_behalf_of?: string, proration_behavior?: string, transfer_data?: array{amount_percent?: float, destination: string}, trial_end?: int, trial_period_days?: int, trial_settings?: array{end_behavior: array{missing_payment_method: string}}}, success_url?: string, tax_id_collection?: array{enabled: bool, required?: string}, ui_mode?: string, wallet_options?: array{link?: array{display?: string}}} $params
 * @param null|array|string $options
 *
 * @return Session the created resource
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 140,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Checkout',
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'currentClassName' => 'Stripe\\Checkout\\Session',
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
                'startLine' => 162,
                'endLine' => 162,
                'startTokenPos' => 405,
                'startFilePos' => 30530,
                'endTokenPos' => 405,
                'endFilePos' => 30533,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 162,
            'endLine' => 162,
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
                'startLine' => 162,
                'endLine' => 162,
                'startTokenPos' => 412,
                'startFilePos' => 30544,
                'endTokenPos' => 412,
                'endFilePos' => 30547,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 162,
            'endLine' => 162,
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
 * Returns a list of Checkout Sessions.
 *
 * @param null|array{created?: array|int, customer?: string, customer_details?: array{email: string}, ending_before?: string, expand?: string[], limit?: int, payment_intent?: string, payment_link?: string, starting_after?: string, status?: string, subscription?: string} $params
 * @param null|array|string $opts
 *
 * @return \\Stripe\\Collection<Session> of ApiResources
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 162,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Checkout',
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'currentClassName' => 'Stripe\\Checkout\\Session',
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
            'startLine' => 179,
            'endLine' => 179,
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
                'startLine' => 179,
                'endLine' => 179,
                'startTokenPos' => 468,
                'startFilePos' => 31062,
                'endTokenPos' => 468,
                'endFilePos' => 31065,
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
 * Retrieves a Checkout Session object.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Session
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 179,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Checkout',
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'currentClassName' => 'Stripe\\Checkout\\Session',
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
            'startLine' => 202,
            'endLine' => 202,
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
                'startLine' => 202,
                'endLine' => 202,
                'startTokenPos' => 531,
                'startFilePos' => 32414,
                'endTokenPos' => 531,
                'endFilePos' => 32417,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 202,
            'endLine' => 202,
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
                'startLine' => 202,
                'endLine' => 202,
                'startTokenPos' => 538,
                'startFilePos' => 32428,
                'endTokenPos' => 538,
                'endFilePos' => 32431,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 202,
            'endLine' => 202,
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
 * Updates a Checkout Session object.
 *
 * Related guide: <a href="/payments/checkout/dynamic-updates">Dynamically update
 * Checkout</a>
 *
 * @param string $id the ID of the resource to update
 * @param null|array{collected_information?: array{shipping_details?: array{address: array{city?: string, country: string, line1: string, line2?: string, postal_code?: string, state?: string}, name: string}}, expand?: string[], metadata?: null|array<string, string>, shipping_options?: null|array{shipping_rate?: string, shipping_rate_data?: array{delivery_estimate?: array{maximum?: array{unit: string, value: int}, minimum?: array{unit: string, value: int}}, display_name: string, fixed_amount?: array{amount: int, currency: string, currency_options?: array<string, array{amount: int, tax_behavior?: string}>}, metadata?: array<string, string>, tax_behavior?: string, tax_code?: string, type?: string}}[]} $params
 * @param null|array|string $opts
 *
 * @return Session the updated resource
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 202,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Checkout',
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'currentClassName' => 'Stripe\\Checkout\\Session',
        'aliasName' => NULL,
      ),
      'expire' => 
      array (
        'name' => 'expire',
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
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 634,
                'startFilePos' => 33014,
                'endTokenPos' => 634,
                'endFilePos' => 33017,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
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
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 641,
                'startFilePos' => 33028,
                'endTokenPos' => 641,
                'endFilePos' => 33031,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
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
 * @return Session the expired session
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 222,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Checkout',
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'currentClassName' => 'Stripe\\Checkout\\Session',
        'aliasName' => NULL,
      ),
      'allLineItems' => 
      array (
        'name' => 'allLineItems',
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
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 41,
            'endColumn' => 43,
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
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 723,
                'startFilePos' => 33569,
                'endTokenPos' => 723,
                'endFilePos' => 33572,
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
            'startColumn' => 46,
            'endColumn' => 59,
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
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 730,
                'startFilePos' => 33583,
                'endTokenPos' => 730,
                'endFilePos' => 33586,
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
            'startColumn' => 62,
            'endColumn' => 73,
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
 * @return \\Stripe\\Collection<\\Stripe\\LineItem> list of line items
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 240,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Checkout',
        'declaringClassName' => 'Stripe\\Checkout\\Session',
        'implementingClassName' => 'Stripe\\Checkout\\Session',
        'currentClassName' => 'Stripe\\Checkout\\Session',
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