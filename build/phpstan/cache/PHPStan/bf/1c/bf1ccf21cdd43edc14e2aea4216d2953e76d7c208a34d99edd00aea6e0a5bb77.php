<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/PaymentLink.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\PaymentLink
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-796eeb356586bcadf50247f5154ac2cf91a5e3217ce94bb98d13798f1eeb612c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\PaymentLink',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/PaymentLink.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\PaymentLink',
    'shortName' => 'PaymentLink',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A payment link is a shareable URL that will take your customers to a hosted payment page. A payment link can be shared and used multiple times.
 *
 * When a customer opens a payment link it will open a new <a href="https://stripe.com/docs/api/checkout/sessions">checkout session</a> to render the payment page. You can use <a href="https://stripe.com/docs/api/events/types#event_types-checkout.session.completed">checkout session events</a> to track payments through payment links.
 *
 * Related guide: <a href="https://stripe.com/docs/payment-links">Payment Links API</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property bool $active Whether the payment link\'s <code>url</code> is active. If <code>false</code>, customers visiting the URL will be shown a page saying that the link has been deactivated.
 * @property (object{hosted_confirmation?: (object{custom_message: null|string}&StripeObject), redirect?: (object{url: string}&StripeObject), type: string}&StripeObject) $after_completion
 * @property bool $allow_promotion_codes Whether user redeemable promotion codes are enabled.
 * @property null|Application|string $application The ID of the Connect application that created the Payment Link.
 * @property null|int $application_fee_amount The amount of the application fee (if any) that will be requested to be applied to the payment and transferred to the application owner\'s Stripe account.
 * @property null|float $application_fee_percent This represents the percentage of the subscription invoice total that will be transferred to the application owner\'s Stripe account.
 * @property (object{enabled: bool, liability: null|(object{account?: Account|string, type: string}&StripeObject)}&StripeObject) $automatic_tax
 * @property string $billing_address_collection Configuration for collecting the customer\'s billing address. Defaults to <code>auto</code>.
 * @property null|(object{payment_method_reuse_agreement: null|(object{position: string}&StripeObject), promotions: null|string, terms_of_service: null|string}&StripeObject) $consent_collection When set, provides configuration to gather active consent from customers.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property ((object{dropdown?: (object{default_value: null|string, options: (object{label: string, value: string}&StripeObject)[]}&StripeObject), key: string, label: (object{custom: null|string, type: string}&StripeObject), numeric?: (object{default_value: null|string, maximum_length: null|int, minimum_length: null|int}&StripeObject), optional: bool, text?: (object{default_value: null|string, maximum_length: null|int, minimum_length: null|int}&StripeObject), type: string}&StripeObject))[] $custom_fields Collect additional information from your customer using custom fields. Up to 3 fields are supported.
 * @property (object{after_submit: null|(object{message: string}&StripeObject), shipping_address: null|(object{message: string}&StripeObject), submit: null|(object{message: string}&StripeObject), terms_of_service_acceptance: null|(object{message: string}&StripeObject)}&StripeObject) $custom_text
 * @property string $customer_creation Configuration for Customer creation during checkout.
 * @property null|string $inactive_message The custom message to be displayed to a customer when a payment link is no longer active.
 * @property null|(object{enabled: bool, invoice_data: null|(object{account_tax_ids: null|(string|TaxId)[], custom_fields: null|(object{name: string, value: string}&StripeObject)[], description: null|string, footer: null|string, issuer: null|(object{account?: Account|string, type: string}&StripeObject), metadata: null|StripeObject, rendering_options: null|(object{amount_tax_display: null|string, template: null|string}&StripeObject)}&StripeObject)}&StripeObject) $invoice_creation Configuration for creating invoice for payment mode payment links.
 * @property null|Collection<LineItem> $line_items The line items representing what is being sold.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|Account|string $on_behalf_of The account on behalf of which to charge. See the <a href="https://support.stripe.com/questions/sending-invoices-on-behalf-of-connected-accounts">Connect documentation</a> for details.
 * @property null|((object{adjustable_quantity: null|(object{enabled: bool, maximum: null|int, minimum: null|int}&StripeObject), price: string, quantity: int}&StripeObject))[] $optional_items The optional items presented to the customer at checkout.
 * @property null|(object{capture_method: null|string, description: null|string, metadata: StripeObject, setup_future_usage: null|string, statement_descriptor: null|string, statement_descriptor_suffix: null|string, transfer_group: null|string}&StripeObject) $payment_intent_data Indicates the parameters to be passed to PaymentIntent creation during checkout.
 * @property string $payment_method_collection Configuration for collecting a payment method during checkout. Defaults to <code>always</code>.
 * @property null|string[] $payment_method_types The list of payment method types that customers can use. When <code>null</code>, Stripe will dynamically show relevant payment methods you\'ve enabled in your <a href="https://dashboard.stripe.com/settings/payment_methods">payment method settings</a>.
 * @property (object{enabled: bool}&StripeObject) $phone_number_collection
 * @property null|(object{completed_sessions: (object{count: int, limit: int}&StripeObject)}&StripeObject) $restrictions Settings that restrict the usage of a payment link.
 * @property null|(object{allowed_countries: string[]}&StripeObject) $shipping_address_collection Configuration for collecting the customer\'s shipping address.
 * @property ((object{shipping_amount: int, shipping_rate: ShippingRate|string}&StripeObject))[] $shipping_options The shipping rate options applied to the session.
 * @property string $submit_type Indicates the type of transaction being performed which customizes relevant text on the page, such as the submit button.
 * @property null|(object{description: null|string, invoice_settings: (object{issuer: (object{account?: Account|string, type: string}&StripeObject)}&StripeObject), metadata: StripeObject, trial_period_days: null|int, trial_settings: null|(object{end_behavior: (object{missing_payment_method: string}&StripeObject)}&StripeObject)}&StripeObject) $subscription_data When creating a subscription, the specified configuration data will be used. There must be at least one line item with a recurring price to use <code>subscription_data</code>.
 * @property (object{enabled: bool, required: string}&StripeObject) $tax_id_collection
 * @property null|(object{amount: null|int, destination: Account|string}&StripeObject) $transfer_data The account (if any) the payments will be attributed to for tax reporting, and where funds from each payment will be transferred to.
 * @property string $url The public URL that can be shared with customers.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 49,
    'endLine' => 169,
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
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'payment_link\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 27,
            'startFilePos' => 7766,
            'endTokenPos' => 27,
            'endFilePos' => 7779,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'BILLING_ADDRESS_COLLECTION_AUTO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'BILLING_ADDRESS_COLLECTION_AUTO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'auto\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 41,
            'startFilePos' => 7858,
            'endTokenPos' => 41,
            'endFilePos' => 7863,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'BILLING_ADDRESS_COLLECTION_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'BILLING_ADDRESS_COLLECTION_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'required\'',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 50,
            'startFilePos' => 7914,
            'endTokenPos' => 50,
            'endFilePos' => 7923,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 59,
      ),
      'CUSTOMER_CREATION_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'CUSTOMER_CREATION_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 59,
            'startFilePos' => 7964,
            'endTokenPos' => 59,
            'endFilePos' => 7971,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'CUSTOMER_CREATION_IF_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'CUSTOMER_CREATION_IF_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'if_required\'',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 68,
            'startFilePos' => 8016,
            'endTokenPos' => 68,
            'endFilePos' => 8028,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'PAYMENT_METHOD_COLLECTION_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'PAYMENT_METHOD_COLLECTION_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 77,
            'startFilePos' => 8077,
            'endTokenPos' => 77,
            'endFilePos' => 8084,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'PAYMENT_METHOD_COLLECTION_IF_REQUIRED' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'PAYMENT_METHOD_COLLECTION_IF_REQUIRED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'if_required\'',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 86,
            'startFilePos' => 8137,
            'endTokenPos' => 86,
            'endFilePos' => 8149,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
      'SUBMIT_TYPE_AUTO' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'SUBMIT_TYPE_AUTO',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'auto\'',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 95,
            'startFilePos' => 8182,
            'endTokenPos' => 95,
            'endFilePos' => 8187,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'SUBMIT_TYPE_BOOK' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'SUBMIT_TYPE_BOOK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'book\'',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 104,
            'startFilePos' => 8219,
            'endTokenPos' => 104,
            'endFilePos' => 8224,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'SUBMIT_TYPE_DONATE' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'SUBMIT_TYPE_DONATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'donate\'',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 113,
            'startFilePos' => 8258,
            'endTokenPos' => 113,
            'endFilePos' => 8265,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'SUBMIT_TYPE_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'SUBMIT_TYPE_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pay\'',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 67,
            'startTokenPos' => 122,
            'startFilePos' => 8296,
            'endTokenPos' => 122,
            'endFilePos' => 8300,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'SUBMIT_TYPE_SUBSCRIBE' => 
      array (
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'name' => 'SUBMIT_TYPE_SUBSCRIBE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscribe\'',
          'attributes' => 
          array (
            'startLine' => 68,
            'endLine' => 68,
            'startTokenPos' => 131,
            'startFilePos' => 8337,
            'endTokenPos' => 131,
            'endFilePos' => 8347,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 46,
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
                'startLine' => 80,
                'endLine' => 80,
                'startTokenPos' => 148,
                'startFilePos' => 11725,
                'endTokenPos' => 148,
                'endFilePos' => 11728,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
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
                'startLine' => 80,
                'endLine' => 80,
                'startTokenPos' => 155,
                'startFilePos' => 11742,
                'endTokenPos' => 155,
                'endFilePos' => 11745,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
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
 * Creates a payment link.
 *
 * @param null|array{after_completion?: array{hosted_confirmation?: array{custom_message?: string}, redirect?: array{url: string}, type: string}, allow_promotion_codes?: bool, application_fee_amount?: int, application_fee_percent?: float, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_address_collection?: string, consent_collection?: array{payment_method_reuse_agreement?: array{position: string}, promotions?: string, terms_of_service?: string}, currency?: string, custom_fields?: array{dropdown?: array{default_value?: string, options: array{label: string, value: string}[]}, key: string, label: array{custom: string, type: string}, numeric?: array{default_value?: string, maximum_length?: int, minimum_length?: int}, optional?: bool, text?: array{default_value?: string, maximum_length?: int, minimum_length?: int}, type: string}[], custom_text?: array{after_submit?: null|array{message: string}, shipping_address?: null|array{message: string}, submit?: null|array{message: string}, terms_of_service_acceptance?: null|array{message: string}}, customer_creation?: string, expand?: string[], inactive_message?: string, invoice_creation?: array{enabled: bool, invoice_data?: array{account_tax_ids?: null|string[], custom_fields?: null|array{name: string, value: string}[], description?: string, footer?: string, issuer?: array{account?: string, type: string}, metadata?: null|array<string, string>, rendering_options?: null|array{amount_tax_display?: null|string, template?: string}}}, line_items: array{adjustable_quantity?: array{enabled: bool, maximum?: int, minimum?: int}, price?: string, price_data?: array{currency: string, product?: string, product_data?: array{description?: string, images?: string[], metadata?: array<string, string>, name: string, tax_code?: string}, recurring?: array{interval: string, interval_count?: int}, tax_behavior?: string, unit_amount?: int, unit_amount_decimal?: string}, quantity: int}[], metadata?: array<string, string>, on_behalf_of?: string, optional_items?: array{adjustable_quantity?: array{enabled: bool, maximum?: int, minimum?: int}, price: string, quantity: int}[], payment_intent_data?: array{capture_method?: string, description?: string, metadata?: array<string, string>, setup_future_usage?: string, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_group?: string}, payment_method_collection?: string, payment_method_types?: string[], phone_number_collection?: array{enabled: bool}, restrictions?: array{completed_sessions: array{limit: int}}, shipping_address_collection?: array{allowed_countries: string[]}, shipping_options?: array{shipping_rate?: string}[], submit_type?: string, subscription_data?: array{description?: string, invoice_settings?: array{issuer?: array{account?: string, type: string}}, metadata?: array<string, string>, trial_period_days?: int, trial_settings?: array{end_behavior: array{missing_payment_method: string}}}, tax_id_collection?: array{enabled: bool, required?: string}, transfer_data?: array{amount?: int, destination: string}} $params
 * @param null|array|string $options
 *
 * @return PaymentLink the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 80,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'currentClassName' => 'Stripe\\PaymentLink',
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
                'startLine' => 102,
                'endLine' => 102,
                'startTokenPos' => 252,
                'startFilePos' => 12473,
                'endTokenPos' => 252,
                'endFilePos' => 12476,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 102,
            'endLine' => 102,
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
                'startLine' => 102,
                'endLine' => 102,
                'startTokenPos' => 259,
                'startFilePos' => 12487,
                'endTokenPos' => 259,
                'endFilePos' => 12490,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 102,
            'endLine' => 102,
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
 * Returns a list of your payment links.
 *
 * @param null|array{active?: bool, ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<PaymentLink> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 102,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'currentClassName' => 'Stripe\\PaymentLink',
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
            'startLine' => 119,
            'endLine' => 119,
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
                'startLine' => 119,
                'endLine' => 119,
                'startTokenPos' => 315,
                'startFilePos' => 12981,
                'endTokenPos' => 315,
                'endFilePos' => 12984,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
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
 * Retrieve a payment link.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return PaymentLink
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 119,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'currentClassName' => 'Stripe\\PaymentLink',
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
            'startLine' => 139,
            'endLine' => 139,
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
                'startLine' => 139,
                'endLine' => 139,
                'startTokenPos' => 378,
                'startFilePos' => 15792,
                'endTokenPos' => 378,
                'endFilePos' => 15795,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
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
                'startLine' => 139,
                'endLine' => 139,
                'startTokenPos' => 385,
                'startFilePos' => 15806,
                'endTokenPos' => 385,
                'endFilePos' => 15809,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
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
 * Updates a payment link.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{active?: bool, after_completion?: array{hosted_confirmation?: array{custom_message?: string}, redirect?: array{url: string}, type: string}, allow_promotion_codes?: bool, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_address_collection?: string, custom_fields?: null|array{dropdown?: array{default_value?: string, options: array{label: string, value: string}[]}, key: string, label: array{custom: string, type: string}, numeric?: array{default_value?: string, maximum_length?: int, minimum_length?: int}, optional?: bool, text?: array{default_value?: string, maximum_length?: int, minimum_length?: int}, type: string}[], custom_text?: array{after_submit?: null|array{message: string}, shipping_address?: null|array{message: string}, submit?: null|array{message: string}, terms_of_service_acceptance?: null|array{message: string}}, customer_creation?: string, expand?: string[], inactive_message?: null|string, invoice_creation?: array{enabled: bool, invoice_data?: array{account_tax_ids?: null|string[], custom_fields?: null|array{name: string, value: string}[], description?: string, footer?: string, issuer?: array{account?: string, type: string}, metadata?: null|array<string, string>, rendering_options?: null|array{amount_tax_display?: null|string, template?: string}}}, line_items?: array{adjustable_quantity?: array{enabled: bool, maximum?: int, minimum?: int}, id: string, quantity?: int}[], metadata?: array<string, string>, payment_intent_data?: array{description?: null|string, metadata?: null|array<string, string>, statement_descriptor?: null|string, statement_descriptor_suffix?: null|string, transfer_group?: null|string}, payment_method_collection?: string, payment_method_types?: null|string[], phone_number_collection?: array{enabled: bool}, restrictions?: null|array{completed_sessions: array{limit: int}}, shipping_address_collection?: null|array{allowed_countries: string[]}, submit_type?: string, subscription_data?: array{invoice_settings?: array{issuer?: array{account?: string, type: string}}, metadata?: null|array<string, string>, trial_period_days?: null|int, trial_settings?: null|array{end_behavior: array{missing_payment_method: string}}}, tax_id_collection?: array{enabled: bool, required?: string}} $params
 * @param null|array|string $opts
 *
 * @return PaymentLink the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 139,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'currentClassName' => 'Stripe\\PaymentLink',
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
            'startLine' => 160,
            'endLine' => 160,
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
                'startLine' => 160,
                'endLine' => 160,
                'startTokenPos' => 486,
                'startFilePos' => 16431,
                'endTokenPos' => 486,
                'endFilePos' => 16434,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
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
                'startLine' => 160,
                'endLine' => 160,
                'startTokenPos' => 493,
                'startFilePos' => 16445,
                'endTokenPos' => 493,
                'endFilePos' => 16448,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
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
 * @return Collection<LineItem> list of line items
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 160,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\PaymentLink',
        'implementingClassName' => 'Stripe\\PaymentLink',
        'currentClassName' => 'Stripe\\PaymentLink',
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