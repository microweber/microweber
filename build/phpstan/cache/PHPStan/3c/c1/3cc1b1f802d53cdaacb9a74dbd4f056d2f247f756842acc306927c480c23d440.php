<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Coupon.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Coupon
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-107c6d03ad1ee0ad2b3a1cfe6f5021491321abeb2c32adcc8235dc9fe687f5a3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Coupon',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Coupon.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Coupon',
    'shortName' => 'Coupon',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A coupon contains information about a percent-off or amount-off discount you
 * might want to apply to a customer. Coupons may be applied to <a href="https://stripe.com/docs/api#subscriptions">subscriptions</a>, <a href="https://stripe.com/docs/api#invoices">invoices</a>,
 * <a href="https://stripe.com/docs/api/checkout/sessions">checkout sessions</a>, <a href="https://stripe.com/docs/api#quotes">quotes</a>, and more. Coupons do not work with conventional one-off <a href="https://stripe.com/docs/api#create_charge">charges</a> or <a href="https://stripe.com/docs/api/payment_intents">payment intents</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|int $amount_off Amount (in the <code>currency</code> specified) that will be taken off the subtotal of any invoices for this customer.
 * @property null|(object{products: string[]}&StripeObject) $applies_to
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|string $currency If <code>amount_off</code> has been set, the three-letter <a href="https://stripe.com/docs/currencies">ISO code for the currency</a> of the amount to take off.
 * @property null|StripeObject $currency_options Coupons defined in each available currency option. Each key must be a three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a> and a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property string $duration One of <code>forever</code>, <code>once</code>, or <code>repeating</code>. Describes how long a customer who applies this coupon will get the discount.
 * @property null|int $duration_in_months If <code>duration</code> is <code>repeating</code>, the number of months the coupon applies. Null if coupon <code>duration</code> is <code>forever</code> or <code>once</code>.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|int $max_redemptions Maximum number of times this coupon can be redeemed, in total, across all customers, before it is no longer valid.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $name Name of the coupon displayed to customers on for instance invoices or receipts.
 * @property null|float $percent_off Percent that will be taken off the subtotal of any invoices for this customer for the duration of the coupon. For example, a coupon with percent_off of 50 will make a $ (or local equivalent)100 invoice $ (or local equivalent)50 instead.
 * @property null|int $redeem_by Date after which the coupon can no longer be redeemed.
 * @property int $times_redeemed Number of times this coupon has been applied to a customer.
 * @property bool $valid Taking account of the above properties, whether this coupon can still be applied to a customer.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 159,
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
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'coupon\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 27,
            'startFilePos' => 3382,
            'endTokenPos' => 27,
            'endFilePos' => 3389,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'DURATION_FOREVER' => 
      array (
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'name' => 'DURATION_FOREVER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'forever\'',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 41,
            'startFilePos' => 3453,
            'endTokenPos' => 41,
            'endFilePos' => 3461,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'DURATION_ONCE' => 
      array (
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'name' => 'DURATION_ONCE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'once\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 50,
            'startFilePos' => 3490,
            'endTokenPos' => 50,
            'endFilePos' => 3495,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'DURATION_REPEATING' => 
      array (
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'name' => 'DURATION_REPEATING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'repeating\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 59,
            'startFilePos' => 3529,
            'endTokenPos' => 59,
            'endFilePos' => 3539,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 43,
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
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 76,
                'startFilePos' => 5062,
                'endTokenPos' => 76,
                'endFilePos' => 5065,
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
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 83,
                'startFilePos' => 5079,
                'endTokenPos' => 83,
                'endFilePos' => 5082,
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
 * You can create coupons easily via the <a
 * href="https://dashboard.stripe.com/coupons">coupon management</a> page of the
 * Stripe dashboard. Coupon creation is also accessible via the API if you need to
 * create coupons on the fly.
 *
 * A coupon has either a <code>percent_off</code> or an <code>amount_off</code> and
 * <code>currency</code>. If you set an <code>amount_off</code>, that amount will
 * be subtracted from any invoice’s subtotal. For example, an invoice with a
 * subtotal of <currency>100</currency> will have a final total of
 * <currency>0</currency> if a coupon with an <code>amount_off</code> of
 * <amount>200</amount> is applied to it and an invoice with a subtotal of
 * <currency>300</currency> will have a final total of <currency>100</currency> if
 * a coupon with an <code>amount_off</code> of <amount>200</amount> is applied to
 * it.
 *
 * @param null|array{amount_off?: int, applies_to?: array{products?: string[]}, currency?: string, currency_options?: array<string, array{amount_off: int}>, duration?: string, duration_in_months?: int, expand?: string[], id?: string, max_redemptions?: int, metadata?: null|array<string, string>, name?: string, percent_off?: float, redeem_by?: int} $params
 * @param null|array|string $options
 *
 * @return Coupon the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 63,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'currentClassName' => 'Stripe\\Coupon',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
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
                'startLine' => 89,
                'endLine' => 89,
                'startTokenPos' => 178,
                'startFilePos' => 6008,
                'endTokenPos' => 178,
                'endFilePos' => 6011,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
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
                'startLine' => 89,
                'endLine' => 89,
                'startTokenPos' => 185,
                'startFilePos' => 6022,
                'endTokenPos' => 185,
                'endFilePos' => 6025,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
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
 * You can delete coupons via the <a
 * href="https://dashboard.stripe.com/coupons">coupon management</a> page of the
 * Stripe dashboard. However, deleting a coupon does not affect any customers who
 * have already applied the coupon; it means that new customers can’t redeem the
 * coupon. You can also delete coupons via the API.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Coupon the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 89,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'currentClassName' => 'Stripe\\Coupon',
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
                'startLine' => 110,
                'endLine' => 110,
                'startTokenPos' => 268,
                'startFilePos' => 6674,
                'endTokenPos' => 268,
                'endFilePos' => 6677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 110,
            'endLine' => 110,
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
                'startLine' => 110,
                'endLine' => 110,
                'startTokenPos' => 275,
                'startFilePos' => 6688,
                'endTokenPos' => 275,
                'endFilePos' => 6691,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 110,
            'endLine' => 110,
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
 * Returns a list of your coupons.
 *
 * @param null|array{created?: array|int, ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Coupon> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 110,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'currentClassName' => 'Stripe\\Coupon',
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
            'startLine' => 127,
            'endLine' => 127,
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
                'startLine' => 127,
                'endLine' => 127,
                'startTokenPos' => 331,
                'startFilePos' => 7192,
                'endTokenPos' => 331,
                'endFilePos' => 7195,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 127,
            'endLine' => 127,
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
 * Retrieves the coupon with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Coupon
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 127,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'currentClassName' => 'Stripe\\Coupon',
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
            'startLine' => 148,
            'endLine' => 148,
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
                'startLine' => 148,
                'endLine' => 148,
                'startTokenPos' => 394,
                'startFilePos' => 7948,
                'endTokenPos' => 394,
                'endFilePos' => 7951,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 148,
            'endLine' => 148,
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
                'startLine' => 148,
                'endLine' => 148,
                'startTokenPos' => 401,
                'startFilePos' => 7962,
                'endTokenPos' => 401,
                'endFilePos' => 7965,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 148,
            'endLine' => 148,
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
 * Updates the metadata of a coupon. Other coupon details (currency, duration,
 * amount_off) are, by design, not editable.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{currency_options?: array<string, array{amount_off: int}>, expand?: string[], metadata?: null|array<string, string>, name?: string} $params
 * @param null|array|string $opts
 *
 * @return Coupon the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 148,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Coupon',
        'implementingClassName' => 'Stripe\\Coupon',
        'currentClassName' => 'Stripe\\Coupon',
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