<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Plan.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Plan
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f460dc8474670613ac520409c1fa98eff7bb2b9f38eb09c45e330c55033f4acd-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Plan',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Plan.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Plan',
    'shortName' => 'Plan',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * You can now model subscriptions more flexibly using the <a href="https://stripe.com/docs/api#prices">Prices API</a>. It replaces the Plans API and is backwards compatible to simplify your migration.
 *
 * Plans define the base price, currency, and billing cycle for recurring purchases of products.
 * <a href="https://stripe.com/docs/api#products">Products</a> help you track inventory or provisioning, and plans help you track pricing. Different physical goods or levels of service should be represented by products, and pricing options should be represented by plans. This approach lets you change prices without having to change your provisioning scheme.
 *
 * For example, you might have a single &quot;gold&quot; product that has plans for $10/month, $100/year, €9/month, and €90/year.
 *
 * Related guides: <a href="https://stripe.com/docs/billing/subscriptions/set-up-subscription">Set up a subscription</a> and more about <a href="https://stripe.com/docs/products-prices/overview">products and prices</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property bool $active Whether the plan can be used for new purchases.
 * @property null|int $amount The unit amount in cents (or local equivalent) to be charged, represented as a whole integer if possible. Only set if <code>billing_scheme=per_unit</code>.
 * @property null|string $amount_decimal The unit amount in cents (or local equivalent) to be charged, represented as a decimal string with at most 12 decimal places. Only set if <code>billing_scheme=per_unit</code>.
 * @property string $billing_scheme Describes how to compute the price per period. Either <code>per_unit</code> or <code>tiered</code>. <code>per_unit</code> indicates that the fixed amount (specified in <code>amount</code>) will be charged per unit in <code>quantity</code> (for plans with <code>usage_type=licensed</code>), or per unit of total usage (for plans with <code>usage_type=metered</code>). <code>tiered</code> indicates that the unit pricing will be computed using a tiering strategy as defined using the <code>tiers</code> and <code>tiers_mode</code> attributes.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property string $interval The frequency at which a subscription is billed. One of <code>day</code>, <code>week</code>, <code>month</code> or <code>year</code>.
 * @property int $interval_count The number of intervals (specified in the <code>interval</code> attribute) between subscription billings. For example, <code>interval=month</code> and <code>interval_count=3</code> bills every 3 months.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $meter The meter tracking the usage of a metered price
 * @property null|string $nickname A brief description of the plan, hidden from customers.
 * @property null|Product|string $product The product whose pricing this plan determines.
 * @property null|((object{flat_amount: null|int, flat_amount_decimal: null|string, unit_amount: null|int, unit_amount_decimal: null|string, up_to: null|int}&StripeObject))[] $tiers Each element represents a pricing tier. This parameter requires <code>billing_scheme</code> to be set to <code>tiered</code>. See also the documentation for <code>billing_scheme</code>.
 * @property null|string $tiers_mode Defines if the tiering price should be <code>graduated</code> or <code>volume</code> based. In <code>volume</code>-based tiering, the maximum quantity within a period determines the per unit price. In <code>graduated</code> tiering, pricing can change as the quantity grows.
 * @property null|(object{divide_by: int, round: string}&StripeObject) $transform_usage Apply a transformation to the reported usage or set quantity before computing the amount billed. Cannot be combined with <code>tiers</code>.
 * @property null|int $trial_period_days Default number of trial days when subscribing a customer to this plan using <a href="https://stripe.com/docs/api#create_subscription-trial_from_plan"><code>trial_from_plan=true</code></a>.
 * @property string $usage_type Configures how the quantity per period should be determined. Can be either <code>metered</code> or <code>licensed</code>. <code>licensed</code> automatically bills the <code>quantity</code> set when adding it to a subscription. <code>metered</code> aggregates the total usage based on usage records. Defaults to <code>licensed</code>.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 38,
    'endLine' => 164,
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
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'plan\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 27,
            'startFilePos' => 5279,
            'endTokenPos' => 27,
            'endFilePos' => 5284,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'BILLING_SCHEME_PER_UNIT' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'BILLING_SCHEME_PER_UNIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'per_unit\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 41,
            'startFilePos' => 5355,
            'endTokenPos' => 41,
            'endFilePos' => 5364,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'BILLING_SCHEME_TIERED' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'BILLING_SCHEME_TIERED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tiered\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 50,
            'startFilePos' => 5401,
            'endTokenPos' => 50,
            'endFilePos' => 5408,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'INTERVAL_DAY' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'INTERVAL_DAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'day\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 59,
            'startFilePos' => 5437,
            'endTokenPos' => 59,
            'endFilePos' => 5441,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'INTERVAL_MONTH' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'INTERVAL_MONTH',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'month\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 68,
            'startFilePos' => 5471,
            'endTokenPos' => 68,
            'endFilePos' => 5477,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'INTERVAL_WEEK' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'INTERVAL_WEEK',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'week\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 77,
            'startFilePos' => 5506,
            'endTokenPos' => 77,
            'endFilePos' => 5511,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'INTERVAL_YEAR' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'INTERVAL_YEAR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'year\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 86,
            'startFilePos' => 5540,
            'endTokenPos' => 86,
            'endFilePos' => 5545,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TIERS_MODE_GRADUATED' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'TIERS_MODE_GRADUATED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'graduated\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 95,
            'startFilePos' => 5582,
            'endTokenPos' => 95,
            'endFilePos' => 5592,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'TIERS_MODE_VOLUME' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'TIERS_MODE_VOLUME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'volume\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 104,
            'startFilePos' => 5625,
            'endTokenPos' => 104,
            'endFilePos' => 5632,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'USAGE_TYPE_LICENSED' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'USAGE_TYPE_LICENSED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'licensed\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 113,
            'startFilePos' => 5668,
            'endTokenPos' => 113,
            'endFilePos' => 5677,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'USAGE_TYPE_METERED' => 
      array (
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'name' => 'USAGE_TYPE_METERED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'metered\'',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 122,
            'startFilePos' => 5711,
            'endTokenPos' => 122,
            'endFilePos' => 5719,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
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
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 139,
                'startFilePos' => 6708,
                'endTokenPos' => 139,
                'endFilePos' => 6711,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
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
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 146,
                'startFilePos' => 6725,
                'endTokenPos' => 146,
                'endFilePos' => 6728,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
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
 * You can now model subscriptions more flexibly using the <a href="#prices">Prices
 * API</a>. It replaces the Plans API and is backwards compatible to simplify your
 * migration.
 *
 * @param null|array{active?: bool, amount?: int, amount_decimal?: string, billing_scheme?: string, currency: string, expand?: string[], id?: string, interval: string, interval_count?: int, metadata?: null|array<string, string>, meter?: string, nickname?: string, product?: array|string, tiers?: (array{flat_amount?: int, flat_amount_decimal?: string, unit_amount?: int, unit_amount_decimal?: string, up_to: array|int|string})[], tiers_mode?: string, transform_usage?: array{divide_by: int, round: string}, trial_period_days?: int, usage_type?: string} $params
 * @param null|array|string $options
 *
 * @return Plan the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 70,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'currentClassName' => 'Stripe\\Plan',
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
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 241,
                'startFilePos' => 7406,
                'endTokenPos' => 241,
                'endFilePos' => 7409,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
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
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 248,
                'startFilePos' => 7420,
                'endTokenPos' => 248,
                'endFilePos' => 7423,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
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
 * Deleting plans means new subscribers can’t be added. Existing subscribers aren’t
 * affected.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Plan the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 93,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'currentClassName' => 'Stripe\\Plan',
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
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 331,
                'startFilePos' => 8101,
                'endTokenPos' => 331,
                'endFilePos' => 8104,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
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
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 338,
                'startFilePos' => 8115,
                'endTokenPos' => 338,
                'endFilePos' => 8118,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
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
 * Returns a list of your plans.
 *
 * @param null|array{active?: bool, created?: array|int, ending_before?: string, expand?: string[], limit?: int, product?: string, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Plan> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 114,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'currentClassName' => 'Stripe\\Plan',
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
            'startLine' => 131,
            'endLine' => 131,
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
                'startLine' => 131,
                'endLine' => 131,
                'startTokenPos' => 394,
                'startFilePos' => 8615,
                'endTokenPos' => 394,
                'endFilePos' => 8618,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
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
 * Retrieves the plan with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Plan
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 131,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'currentClassName' => 'Stripe\\Plan',
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
                'startTokenPos' => 457,
                'startFilePos' => 9465,
                'endTokenPos' => 457,
                'endFilePos' => 9468,
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
                'startTokenPos' => 464,
                'startFilePos' => 9479,
                'endTokenPos' => 464,
                'endFilePos' => 9482,
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
 * Updates the specified plan by setting the values of the parameters passed. Any
 * parameters not provided are left unchanged. By design, you cannot change a
 * plan’s ID, amount, currency, or billing cycle.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{active?: bool, expand?: string[], metadata?: null|array<string, string>, nickname?: string, product?: string, trial_period_days?: int} $params
 * @param null|array|string $opts
 *
 * @return Plan the updated resource
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
        'declaringClassName' => 'Stripe\\Plan',
        'implementingClassName' => 'Stripe\\Plan',
        'currentClassName' => 'Stripe\\Plan',
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