<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/ShippingRate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\ShippingRate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0869c8cf38f20444cb92e0e933c31c9be6daf0702480ab0e8ec05790dc12e74d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\ShippingRate',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/ShippingRate.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\ShippingRate',
    'shortName' => 'ShippingRate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Shipping rates describe the price of shipping presented to your customers and
 * applied to a purchase. For more information, see <a href="https://stripe.com/docs/payments/during-payment/charge-shipping">Charge for shipping</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property bool $active Whether the shipping rate can be used for new purchases. Defaults to <code>true</code>.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|(object{maximum: null|(object{unit: string, value: int}&StripeObject), minimum: null|(object{unit: string, value: int}&StripeObject)}&StripeObject) $delivery_estimate The estimated range for how long shipping will take, meant to be displayable to the customer. This will appear on CheckoutSessions.
 * @property null|string $display_name The name of the shipping rate, meant to be displayable to the customer. This will appear on CheckoutSessions.
 * @property null|(object{amount: int, currency: string, currency_options?: StripeObject}&StripeObject) $fixed_amount
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $tax_behavior Specifies whether the rate is considered inclusive of taxes or exclusive of taxes. One of <code>inclusive</code>, <code>exclusive</code>, or <code>unspecified</code>.
 * @property null|string|TaxCode $tax_code A <a href="https://stripe.com/docs/tax/tax-categories">tax code</a> ID. The Shipping tax code is <code>txcd_92010001</code>.
 * @property string $type The type of calculation to use on the shipping rate.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 116,
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
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'shipping_rate\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 27,
            'startFilePos' => 2204,
            'endTokenPos' => 27,
            'endFilePos' => 2218,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 40,
      ),
      'TAX_BEHAVIOR_EXCLUSIVE' => 
      array (
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'name' => 'TAX_BEHAVIOR_EXCLUSIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'exclusive\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 41,
            'startFilePos' => 2288,
            'endTokenPos' => 41,
            'endFilePos' => 2298,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TAX_BEHAVIOR_INCLUSIVE' => 
      array (
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'name' => 'TAX_BEHAVIOR_INCLUSIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'inclusive\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 50,
            'startFilePos' => 2336,
            'endTokenPos' => 50,
            'endFilePos' => 2346,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TAX_BEHAVIOR_UNSPECIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'name' => 'TAX_BEHAVIOR_UNSPECIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unspecified\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 59,
            'startFilePos' => 2386,
            'endTokenPos' => 59,
            'endFilePos' => 2398,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'TYPE_FIXED_AMOUNT' => 
      array (
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'name' => 'TYPE_FIXED_AMOUNT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'fixed_amount\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 68,
            'startFilePos' => 2432,
            'endTokenPos' => 68,
            'endFilePos' => 2445,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 45,
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
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 85,
                'startFilePos' => 3135,
                'endTokenPos' => 85,
                'endFilePos' => 3138,
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
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 92,
                'startFilePos' => 3152,
                'endTokenPos' => 92,
                'endFilePos' => 3155,
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
 * Creates a new shipping rate object.
 *
 * @param null|array{delivery_estimate?: array{maximum?: array{unit: string, value: int}, minimum?: array{unit: string, value: int}}, display_name: string, expand?: string[], fixed_amount?: array{amount: int, currency: string, currency_options?: array<string, array{amount: int, tax_behavior?: string}>}, metadata?: array<string, string>, tax_behavior?: string, tax_code?: string, type?: string} $params
 * @param null|array|string $options
 *
 * @return ShippingRate the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 46,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'currentClassName' => 'Stripe\\ShippingRate',
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
                'startLine' => 68,
                'endLine' => 68,
                'startTokenPos' => 189,
                'startFilePos' => 3925,
                'endTokenPos' => 189,
                'endFilePos' => 3928,
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
                'startLine' => 68,
                'endLine' => 68,
                'startTokenPos' => 196,
                'startFilePos' => 3939,
                'endTokenPos' => 196,
                'endFilePos' => 3942,
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
 * Returns a list of your shipping rates.
 *
 * @param null|array{active?: bool, created?: array|int, currency?: string, ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<ShippingRate> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 68,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'currentClassName' => 'Stripe\\ShippingRate',
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
            'startLine' => 85,
            'endLine' => 85,
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
                'startLine' => 85,
                'endLine' => 85,
                'startTokenPos' => 252,
                'startFilePos' => 4461,
                'endTokenPos' => 252,
                'endFilePos' => 4464,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
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
 * Returns the shipping rate object with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return ShippingRate
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 85,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'currentClassName' => 'Stripe\\ShippingRate',
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
            'startLine' => 105,
            'endLine' => 105,
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
                'startLine' => 105,
                'endLine' => 105,
                'startTokenPos' => 315,
                'startFilePos' => 5205,
                'endTokenPos' => 315,
                'endFilePos' => 5208,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
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
                'startLine' => 105,
                'endLine' => 105,
                'startTokenPos' => 322,
                'startFilePos' => 5219,
                'endTokenPos' => 322,
                'endFilePos' => 5222,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
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
 * Updates an existing shipping rate object.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{active?: bool, expand?: string[], fixed_amount?: array{currency_options?: array<string, array{amount?: int, tax_behavior?: string}>}, metadata?: null|array<string, string>, tax_behavior?: string} $params
 * @param null|array|string $opts
 *
 * @return ShippingRate the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 105,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ShippingRate',
        'implementingClassName' => 'Stripe\\ShippingRate',
        'currentClassName' => 'Stripe\\ShippingRate',
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