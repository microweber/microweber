<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Price.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Price
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8205b517462bcc208a8725b38df4fc763b3f684d9056b8f31cc4f0c80bad510a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Price',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Price.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Price',
    'shortName' => 'Price',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Prices define the unit cost, currency, and (optional) billing cycle for both recurring and one-time purchases of products.
 * <a href="https://stripe.com/docs/api#products">Products</a> help you track inventory or provisioning, and prices help you track payment terms. Different physical goods or levels of service should be represented by products, and pricing options should be represented by prices. This approach lets you change prices without having to change your provisioning scheme.
 *
 * For example, you might have a single &quot;gold&quot; product that has prices for $10/month, $100/year, and €9 once.
 *
 * Related guides: <a href="https://stripe.com/docs/billing/subscriptions/set-up-subscription">Set up a subscription</a>, <a href="https://stripe.com/docs/billing/invoices/create">create an invoice</a>, and more about <a href="https://stripe.com/docs/products-prices/overview">products and prices</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property bool $active Whether the price can be used for new purchases.
 * @property string $billing_scheme Describes how to compute the price per period. Either <code>per_unit</code> or <code>tiered</code>. <code>per_unit</code> indicates that the fixed amount (specified in <code>unit_amount</code> or <code>unit_amount_decimal</code>) will be charged per unit in <code>quantity</code> (for prices with <code>usage_type=licensed</code>), or per unit of total usage (for prices with <code>usage_type=metered</code>). <code>tiered</code> indicates that the unit pricing will be computed using a tiering strategy as defined using the <code>tiers</code> and <code>tiers_mode</code> attributes.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property null|StripeObject $currency_options Prices defined in each available currency option. Each key must be a three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a> and a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property null|(object{maximum: null|int, minimum: null|int, preset: null|int}&StripeObject) $custom_unit_amount When set, provides configuration for the amount to be adjusted by the customer during Checkout Sessions and Payment Links.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|string $lookup_key A lookup key used to retrieve prices dynamically from a static string. This may be up to 200 characters.
 * @property StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $nickname A brief description of the price, hidden from customers.
 * @property Product|string $product The ID of the product this price is associated with.
 * @property null|(object{interval: string, interval_count: int, meter: null|string, trial_period_days: null|int, usage_type: string}&StripeObject) $recurring The recurring components of a price such as <code>interval</code> and <code>usage_type</code>.
 * @property null|string $tax_behavior Only required if a <a href="https://stripe.com/docs/tax/products-prices-tax-categories-tax-behavior#setting-a-default-tax-behavior-(recommended)">default tax behavior</a> was not provided in the Stripe Tax settings. Specifies whether the price is considered inclusive of taxes or exclusive of taxes. One of <code>inclusive</code>, <code>exclusive</code>, or <code>unspecified</code>. Once specified as either <code>inclusive</code> or <code>exclusive</code>, it cannot be changed.
 * @property null|((object{flat_amount: null|int, flat_amount_decimal: null|string, unit_amount: null|int, unit_amount_decimal: null|string, up_to: null|int}&StripeObject))[] $tiers Each element represents a pricing tier. This parameter requires <code>billing_scheme</code> to be set to <code>tiered</code>. See also the documentation for <code>billing_scheme</code>.
 * @property null|string $tiers_mode Defines if the tiering price should be <code>graduated</code> or <code>volume</code> based. In <code>volume</code>-based tiering, the maximum quantity within a period determines the per unit price. In <code>graduated</code> tiering, pricing can change as the quantity grows.
 * @property null|(object{divide_by: int, round: string}&StripeObject) $transform_quantity Apply a transformation to the reported usage or set quantity before computing the amount billed. Cannot be combined with <code>tiers</code>.
 * @property string $type One of <code>one_time</code> or <code>recurring</code> depending on whether the price is for a one-time purchase or a recurring (subscription) purchase.
 * @property null|int $unit_amount The unit amount in cents (or local equivalent) to be charged, represented as a whole integer if possible. Only set if <code>billing_scheme=per_unit</code>.
 * @property null|string $unit_amount_decimal The unit amount in cents (or local equivalent) to be charged, represented as a decimal string with at most 12 decimal places. Only set if <code>billing_scheme=per_unit</code>.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 37,
    'endLine' => 156,
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
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'price\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 27,
            'startFilePos' => 5779,
            'endTokenPos' => 27,
            'endFilePos' => 5785,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 32,
      ),
      'BILLING_SCHEME_PER_UNIT' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'BILLING_SCHEME_PER_UNIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'per_unit\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 41,
            'startFilePos' => 5856,
            'endTokenPos' => 41,
            'endFilePos' => 5865,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'BILLING_SCHEME_TIERED' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'BILLING_SCHEME_TIERED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tiered\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 50,
            'startFilePos' => 5902,
            'endTokenPos' => 50,
            'endFilePos' => 5909,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'TAX_BEHAVIOR_EXCLUSIVE' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TAX_BEHAVIOR_EXCLUSIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'exclusive\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 59,
            'startFilePos' => 5948,
            'endTokenPos' => 59,
            'endFilePos' => 5958,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TAX_BEHAVIOR_INCLUSIVE' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TAX_BEHAVIOR_INCLUSIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'inclusive\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 68,
            'startFilePos' => 5996,
            'endTokenPos' => 68,
            'endFilePos' => 6006,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TAX_BEHAVIOR_UNSPECIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TAX_BEHAVIOR_UNSPECIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unspecified\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 77,
            'startFilePos' => 6046,
            'endTokenPos' => 77,
            'endFilePos' => 6058,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'TIERS_MODE_GRADUATED' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TIERS_MODE_GRADUATED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'graduated\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 86,
            'startFilePos' => 6095,
            'endTokenPos' => 86,
            'endFilePos' => 6105,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'TIERS_MODE_VOLUME' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TIERS_MODE_VOLUME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'volume\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 95,
            'startFilePos' => 6138,
            'endTokenPos' => 95,
            'endFilePos' => 6145,
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
      'TYPE_ONE_TIME' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TYPE_ONE_TIME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'one_time\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 104,
            'startFilePos' => 6175,
            'endTokenPos' => 104,
            'endFilePos' => 6184,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'TYPE_RECURRING' => 
      array (
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'name' => 'TYPE_RECURRING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'recurring\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 113,
            'startFilePos' => 6214,
            'endTokenPos' => 113,
            'endFilePos' => 6224,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 39,
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
                'startLine' => 68,
                'endLine' => 68,
                'startTokenPos' => 130,
                'startFilePos' => 7905,
                'endTokenPos' => 130,
                'endFilePos' => 7908,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
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
                'startLine' => 68,
                'endLine' => 68,
                'startTokenPos' => 137,
                'startFilePos' => 7922,
                'endTokenPos' => 137,
                'endFilePos' => 7925,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
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
 * Creates a new <a href="https://docs.stripe.com/api/prices">Price</a> for an
 * existing <a href="https://docs.stripe.com/api/products">Product</a>. The Price
 * can be recurring or one-time.
 *
 * @param null|array{active?: bool, billing_scheme?: string, currency: string, currency_options?: array<string, array{custom_unit_amount?: array{enabled: bool, maximum?: int, minimum?: int, preset?: int}, tax_behavior?: string, tiers?: (array{flat_amount?: int, flat_amount_decimal?: string, unit_amount?: int, unit_amount_decimal?: string, up_to: array|int|string})[], unit_amount?: int, unit_amount_decimal?: string}>, custom_unit_amount?: array{enabled: bool, maximum?: int, minimum?: int, preset?: int}, expand?: string[], lookup_key?: string, metadata?: array<string, string>, nickname?: string, product?: string, product_data?: array{active?: bool, id?: string, metadata?: array<string, string>, name: string, statement_descriptor?: string, tax_code?: string, unit_label?: string}, recurring?: array{interval: string, interval_count?: int, meter?: string, trial_period_days?: int, usage_type?: string}, tax_behavior?: string, tiers?: (array{flat_amount?: int, flat_amount_decimal?: string, unit_amount?: int, unit_amount_decimal?: string, up_to: array|int|string})[], tiers_mode?: string, transfer_lookup_key?: bool, transform_quantity?: array{divide_by: int, round: string}, unit_amount?: int, unit_amount_decimal?: string} $params
 * @param null|array|string $options
 *
 * @return Price the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 68,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'currentClassName' => 'Stripe\\Price',
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
                'startLine' => 92,
                'endLine' => 92,
                'startTokenPos' => 234,
                'startFilePos' => 8991,
                'endTokenPos' => 234,
                'endFilePos' => 8994,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
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
                'startLine' => 92,
                'endLine' => 92,
                'startTokenPos' => 241,
                'startFilePos' => 9005,
                'endTokenPos' => 241,
                'endFilePos' => 9008,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
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
 * Returns a list of your active prices, excluding <a
 * href="/docs/products-prices/pricing-models#inline-pricing">inline prices</a>.
 * For the list of inactive prices, set <code>active</code> to false.
 *
 * @param null|array{active?: bool, created?: array|int, currency?: string, ending_before?: string, expand?: string[], limit?: int, lookup_keys?: string[], product?: string, recurring?: array{interval?: string, meter?: string, usage_type?: string}, starting_after?: string, type?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Price> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 92,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'currentClassName' => 'Stripe\\Price',
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
            'startLine' => 109,
            'endLine' => 109,
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
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 297,
                'startFilePos' => 9507,
                'endTokenPos' => 297,
                'endFilePos' => 9510,
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
 * Retrieves the price with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Price
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 109,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'currentClassName' => 'Stripe\\Price',
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
            'startLine' => 130,
            'endLine' => 130,
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
                'startLine' => 130,
                'endLine' => 130,
                'startTokenPos' => 360,
                'startFilePos' => 10648,
                'endTokenPos' => 360,
                'endFilePos' => 10651,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
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
                'startLine' => 130,
                'endLine' => 130,
                'startTokenPos' => 367,
                'startFilePos' => 10662,
                'endTokenPos' => 367,
                'endFilePos' => 10665,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
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
 * Updates the specified price by setting the values of the parameters passed. Any
 * parameters not provided are left unchanged.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{active?: bool, currency_options?: null|array<string, array{custom_unit_amount?: array{enabled: bool, maximum?: int, minimum?: int, preset?: int}, tax_behavior?: string, tiers?: (array{flat_amount?: int, flat_amount_decimal?: string, unit_amount?: int, unit_amount_decimal?: string, up_to: array|int|string})[], unit_amount?: int, unit_amount_decimal?: string}>, expand?: string[], lookup_key?: string, metadata?: null|array<string, string>, nickname?: string, tax_behavior?: string, transfer_lookup_key?: bool} $params
 * @param null|array|string $opts
 *
 * @return Price the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 130,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'currentClassName' => 'Stripe\\Price',
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
                'startLine' => 150,
                'endLine' => 150,
                'startTokenPos' => 465,
                'startFilePos' => 11256,
                'endTokenPos' => 465,
                'endFilePos' => 11259,
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
                'startLine' => 150,
                'endLine' => 150,
                'startTokenPos' => 472,
                'startFilePos' => 11270,
                'endTokenPos' => 472,
                'endFilePos' => 11273,
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
 * @return SearchResult<Price> the price search results
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 150,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Price',
        'implementingClassName' => 'Stripe\\Price',
        'currentClassName' => 'Stripe\\Price',
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