<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/InvoicePayment.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\InvoicePayment
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4f998c31d8849304ecbcacd5baf8a1f21859b0b95cc9a070e0e2025c8ca670c0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\InvoicePayment',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/InvoicePayment.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\InvoicePayment',
    'shortName' => 'InvoicePayment',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Invoice Payments represent payments made against invoices. Invoice Payments can
 * be accessed in two ways:
 * 1. By expanding the <code>payments</code> field on the <a href="https://stripe.com/docs/api#invoice">Invoice</a> resource.
 * 2. By using the Invoice Payment retrieve and list endpoints.
 *
 * Invoice Payments include the mapping between payment objects, such as Payment Intent, and Invoices.
 * This resource and its endpoints allows you to easily track if a payment is associated with a specific invoice and
 * monitor the allocation details of the payments.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|int $amount_paid Amount that was actually paid for this invoice, in cents (or local equivalent). This field is null until the payment is <code>paid</code>. This amount can be less than the <code>amount_requested</code> if the PaymentIntent’s <code>amount_received</code> is not sufficient to pay all of the invoices that it is attached to.
 * @property int $amount_requested Amount intended to be paid toward this invoice, in cents (or local equivalent)
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property Invoice|string $invoice The invoice that was paid.
 * @property bool $is_default Stripe automatically creates a default InvoicePayment when the invoice is finalized, and keeps it synchronized with the invoice’s <code>amount_remaining</code>. The PaymentIntent associated with the default payment can’t be edited or canceled directly.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property (object{charge?: Charge|string, payment_intent?: PaymentIntent|string, type: string}&StripeObject) $payment
 * @property string $status The status of the payment, one of <code>open</code>, <code>paid</code>, or <code>canceled</code>.
 * @property (object{canceled_at: null|int, paid_at: null|int}&StripeObject) $status_transitions
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 71,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\InvoicePayment',
        'implementingClassName' => 'Stripe\\InvoicePayment',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invoice_payment\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 27,
            'startFilePos' => 2546,
            'endTokenPos' => 27,
            'endFilePos' => 2562,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 44,
                'startFilePos' => 3226,
                'endTokenPos' => 44,
                'endFilePos' => 3229,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
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
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 51,
                'startFilePos' => 3240,
                'endTokenPos' => 51,
                'endFilePos' => 3243,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
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
 * When retrieving an invoice, there is an includable payments property containing
 * the first handful of those items. There is also a URL where you can retrieve the
 * full (paginated) list of payments.
 *
 * @param null|array{ending_before?: string, expand?: string[], invoice?: string, limit?: int, payment?: array{payment_intent?: string, type: string}, starting_after?: string, status?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<InvoicePayment> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 46,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\InvoicePayment',
        'implementingClassName' => 'Stripe\\InvoicePayment',
        'currentClassName' => 'Stripe\\InvoicePayment',
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
            'startLine' => 63,
            'endLine' => 63,
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
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 107,
                'startFilePos' => 3761,
                'endTokenPos' => 107,
                'endFilePos' => 3764,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
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
 * Retrieves the invoice payment with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return InvoicePayment
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 63,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\InvoicePayment',
        'implementingClassName' => 'Stripe\\InvoicePayment',
        'currentClassName' => 'Stripe\\InvoicePayment',
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