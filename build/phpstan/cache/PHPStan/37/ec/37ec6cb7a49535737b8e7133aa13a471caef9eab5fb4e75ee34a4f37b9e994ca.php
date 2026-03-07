<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/SubscriptionSchedule.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\SubscriptionSchedule
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-89a497bd19d4d014cbadd29f7c2a7a9ea14caf99a6a2a202a6036532708a3e88-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\SubscriptionSchedule',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/SubscriptionSchedule.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\SubscriptionSchedule',
    'shortName' => 'SubscriptionSchedule',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A subscription schedule allows you to create and manage the lifecycle of a subscription by predefining expected changes.
 *
 * Related guide: <a href="https://stripe.com/docs/billing/subscriptions/subscription-schedules">Subscription schedules</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|Application|string $application ID of the Connect Application that created the schedule.
 * @property (object{type: string, updated_at?: int}&StripeObject) $billing_mode The billing mode of the subscription.
 * @property null|int $canceled_at Time at which the subscription schedule was canceled. Measured in seconds since the Unix epoch.
 * @property null|int $completed_at Time at which the subscription schedule was completed. Measured in seconds since the Unix epoch.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|(object{end_date: int, start_date: int}&StripeObject) $current_phase Object representing the start and end dates for the current phase of the subscription schedule, if it is <code>active</code>.
 * @property Customer|string $customer ID of the customer who owns the subscription schedule.
 * @property (object{application_fee_percent: null|float, automatic_tax?: (object{disabled_reason: null|string, enabled: bool, liability: null|(object{account?: Account|string, type: string}&StripeObject)}&StripeObject), billing_cycle_anchor: string, billing_thresholds: null|(object{amount_gte: null|int, reset_billing_cycle_anchor: null|bool}&StripeObject), collection_method: null|string, default_payment_method: null|PaymentMethod|string, description: null|string, invoice_settings: (object{account_tax_ids: null|(string|TaxId)[], days_until_due: null|int, issuer: (object{account?: Account|string, type: string}&StripeObject)}&StripeObject), on_behalf_of: null|Account|string, transfer_data: null|(object{amount_percent: null|float, destination: Account|string}&StripeObject)}&StripeObject) $default_settings
 * @property string $end_behavior Behavior of the subscription schedule and underlying subscription when it ends. Possible values are <code>release</code> or <code>cancel</code> with the default being <code>release</code>. <code>release</code> will end the subscription schedule and keep the underlying subscription running. <code>cancel</code> will end the subscription schedule and cancel the underlying subscription.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property ((object{add_invoice_items: ((object{discounts: ((object{coupon: null|Coupon|string, discount: null|Discount|string, promotion_code: null|PromotionCode|string}&StripeObject))[], metadata: null|StripeObject, period: (object{end: (object{timestamp?: int, type: string}&StripeObject), start: (object{timestamp?: int, type: string}&StripeObject)}&StripeObject), price: Price|string, quantity: null|int, tax_rates?: null|TaxRate[]}&StripeObject))[], application_fee_percent: null|float, automatic_tax?: (object{disabled_reason: null|string, enabled: bool, liability: null|(object{account?: Account|string, type: string}&StripeObject)}&StripeObject), billing_cycle_anchor: null|string, billing_thresholds: null|(object{amount_gte: null|int, reset_billing_cycle_anchor: null|bool}&StripeObject), collection_method: null|string, currency: string, default_payment_method: null|PaymentMethod|string, default_tax_rates?: null|TaxRate[], description: null|string, discounts: ((object{coupon: null|Coupon|string, discount: null|Discount|string, promotion_code: null|PromotionCode|string}&StripeObject))[], end_date: int, invoice_settings: null|(object{account_tax_ids: null|(string|TaxId)[], days_until_due: null|int, issuer: null|(object{account?: Account|string, type: string}&StripeObject)}&StripeObject), items: ((object{billing_thresholds: null|(object{usage_gte: null|int}&StripeObject), discounts: ((object{coupon: null|Coupon|string, discount: null|Discount|string, promotion_code: null|PromotionCode|string}&StripeObject))[], metadata: null|StripeObject, plan: Plan|string, price: Price|string, quantity?: int, tax_rates?: null|TaxRate[]}&StripeObject))[], metadata: null|StripeObject, on_behalf_of: null|Account|string, proration_behavior: string, start_date: int, transfer_data: null|(object{amount_percent: null|float, destination: Account|string}&StripeObject), trial_end: null|int}&StripeObject))[] $phases Configuration for the subscription schedule\'s phases.
 * @property null|int $released_at Time at which the subscription schedule was released. Measured in seconds since the Unix epoch.
 * @property null|string $released_subscription ID of the subscription once managed by the subscription schedule (if it is released).
 * @property string $status The present status of the subscription schedule. Possible values are <code>not_started</code>, <code>active</code>, <code>completed</code>, <code>released</code>, and <code>canceled</code>. You can read more about the different states in our <a href="https://stripe.com/docs/billing/subscriptions/subscription-schedules">behavior guide</a>.
 * @property null|string|Subscription $subscription ID of the subscription managed by the subscription schedule.
 * @property null|string|TestHelpers\\TestClock $test_clock ID of the test clock this subscription schedule belongs to.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 166,
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
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription_schedule\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 27,
            'startFilePos' => 5957,
            'endTokenPos' => 27,
            'endFilePos' => 5979,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'END_BEHAVIOR_CANCEL' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'END_BEHAVIOR_CANCEL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'cancel\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 41,
            'startFilePos' => 6046,
            'endTokenPos' => 41,
            'endFilePos' => 6053,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'END_BEHAVIOR_NONE' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'END_BEHAVIOR_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'none\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 50,
            'startFilePos' => 6086,
            'endTokenPos' => 50,
            'endFilePos' => 6091,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'END_BEHAVIOR_RELEASE' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'END_BEHAVIOR_RELEASE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'release\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 59,
            'startFilePos' => 6127,
            'endTokenPos' => 59,
            'endFilePos' => 6135,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'END_BEHAVIOR_RENEW' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'END_BEHAVIOR_RENEW',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'renew\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 68,
            'startFilePos' => 6169,
            'endTokenPos' => 68,
            'endFilePos' => 6175,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_ACTIVE' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'STATUS_ACTIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'active\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 77,
            'startFilePos' => 6205,
            'endTokenPos' => 77,
            'endFilePos' => 6212,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'STATUS_CANCELED' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'STATUS_CANCELED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'canceled\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 86,
            'startFilePos' => 6243,
            'endTokenPos' => 86,
            'endFilePos' => 6252,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_COMPLETED' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'STATUS_COMPLETED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'completed\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 95,
            'startFilePos' => 6284,
            'endTokenPos' => 95,
            'endFilePos' => 6294,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'STATUS_NOT_STARTED' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'STATUS_NOT_STARTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'not_started\'',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 104,
            'startFilePos' => 6328,
            'endTokenPos' => 104,
            'endFilePos' => 6340,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'STATUS_RELEASED' => 
      array (
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'name' => 'STATUS_RELEASED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'released\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 113,
            'startFilePos' => 6371,
            'endTokenPos' => 113,
            'endFilePos' => 6380,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
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
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 130,
                'startFilePos' => 9342,
                'endTokenPos' => 130,
                'endFilePos' => 9345,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
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
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 137,
                'startFilePos' => 9359,
                'endTokenPos' => 137,
                'endFilePos' => 9362,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
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
 * Creates a new subscription schedule object. Each customer can have up to 500
 * active or scheduled subscriptions.
 *
 * @param null|array{billing_mode?: array{type: string}, customer?: string, default_settings?: array{application_fee_percent?: float, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_cycle_anchor?: string, billing_thresholds?: null|array{amount_gte?: int, reset_billing_cycle_anchor?: bool}, collection_method?: string, default_payment_method?: string, description?: null|string, invoice_settings?: array{account_tax_ids?: null|string[], days_until_due?: int, issuer?: array{account?: string, type: string}}, on_behalf_of?: null|string, transfer_data?: null|array{amount_percent?: float, destination: string}}, end_behavior?: string, expand?: string[], from_subscription?: string, metadata?: null|array<string, string>, phases?: (array{add_invoice_items?: (array{discounts?: array{coupon?: string, discount?: string, promotion_code?: string}[], metadata?: array<string, string>, period?: array{end: array{timestamp?: int, type: string}, start: array{timestamp?: int, type: string}}, price?: string, price_data?: array{currency: string, product: string, tax_behavior?: string, unit_amount?: int, unit_amount_decimal?: string}, quantity?: int, tax_rates?: null|string[]})[], application_fee_percent?: float, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_cycle_anchor?: string, billing_thresholds?: null|array{amount_gte?: int, reset_billing_cycle_anchor?: bool}, collection_method?: string, currency?: string, default_payment_method?: string, default_tax_rates?: null|string[], description?: null|string, discounts?: null|array{coupon?: string, discount?: string, promotion_code?: string}[], duration?: array{interval: string, interval_count?: int}, end_date?: int, invoice_settings?: array{account_tax_ids?: null|string[], days_until_due?: int, issuer?: array{account?: string, type: string}}, items: (array{billing_thresholds?: null|array{usage_gte: int}, discounts?: null|array{coupon?: string, discount?: string, promotion_code?: string}[], metadata?: array<string, string>, plan?: string, price?: string, price_data?: array{currency: string, product: string, recurring: array{interval: string, interval_count?: int}, tax_behavior?: string, unit_amount?: int, unit_amount_decimal?: string}, quantity?: int, tax_rates?: null|string[]})[], iterations?: int, metadata?: array<string, string>, on_behalf_of?: string, proration_behavior?: string, transfer_data?: array{amount_percent?: float, destination: string}, trial?: bool, trial_end?: int})[], start_date?: array|int|string} $params
 * @param null|array|string $options
 *
 * @return SubscriptionSchedule the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 60,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'currentClassName' => 'Stripe\\SubscriptionSchedule',
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
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 234,
                'startFilePos' => 10231,
                'endTokenPos' => 234,
                'endFilePos' => 10234,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
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
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 241,
                'startFilePos' => 10245,
                'endTokenPos' => 241,
                'endFilePos' => 10248,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
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
 * Retrieves the list of your subscription schedules.
 *
 * @param null|array{canceled_at?: array|int, completed_at?: array|int, created?: array|int, customer?: string, ending_before?: string, expand?: string[], limit?: int, released_at?: array|int, scheduled?: bool, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<SubscriptionSchedule> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 82,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'currentClassName' => 'Stripe\\SubscriptionSchedule',
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
            'startLine' => 101,
            'endLine' => 101,
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
                'startLine' => 101,
                'endLine' => 101,
                'startTokenPos' => 297,
                'startFilePos' => 10920,
                'endTokenPos' => 297,
                'endFilePos' => 10923,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 101,
            'endLine' => 101,
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
 * Retrieves the details of an existing subscription schedule. You only need to
 * supply the unique subscription schedule identifier that was returned upon
 * subscription schedule creation.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return SubscriptionSchedule
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 101,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'currentClassName' => 'Stripe\\SubscriptionSchedule',
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
            'startLine' => 121,
            'endLine' => 121,
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
                'startLine' => 121,
                'endLine' => 121,
                'startTokenPos' => 360,
                'startFilePos' => 14005,
                'endTokenPos' => 360,
                'endFilePos' => 14008,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 121,
            'endLine' => 121,
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
                'startLine' => 121,
                'endLine' => 121,
                'startTokenPos' => 367,
                'startFilePos' => 14019,
                'endTokenPos' => 367,
                'endFilePos' => 14022,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 121,
            'endLine' => 121,
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
 * Updates an existing subscription schedule.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{default_settings?: array{application_fee_percent?: float, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_cycle_anchor?: string, billing_thresholds?: null|array{amount_gte?: int, reset_billing_cycle_anchor?: bool}, collection_method?: string, default_payment_method?: string, description?: null|string, invoice_settings?: array{account_tax_ids?: null|string[], days_until_due?: int, issuer?: array{account?: string, type: string}}, on_behalf_of?: null|string, transfer_data?: null|array{amount_percent?: float, destination: string}}, end_behavior?: string, expand?: string[], metadata?: null|array<string, string>, phases?: (array{add_invoice_items?: (array{discounts?: array{coupon?: string, discount?: string, promotion_code?: string}[], metadata?: array<string, string>, period?: array{end: array{timestamp?: int, type: string}, start: array{timestamp?: int, type: string}}, price?: string, price_data?: array{currency: string, product: string, tax_behavior?: string, unit_amount?: int, unit_amount_decimal?: string}, quantity?: int, tax_rates?: null|string[]})[], application_fee_percent?: float, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, billing_cycle_anchor?: string, billing_thresholds?: null|array{amount_gte?: int, reset_billing_cycle_anchor?: bool}, collection_method?: string, currency?: string, default_payment_method?: string, default_tax_rates?: null|string[], description?: null|string, discounts?: null|array{coupon?: string, discount?: string, promotion_code?: string}[], duration?: array{interval: string, interval_count?: int}, end_date?: array|int|string, invoice_settings?: array{account_tax_ids?: null|string[], days_until_due?: int, issuer?: array{account?: string, type: string}}, items: (array{billing_thresholds?: null|array{usage_gte: int}, discounts?: null|array{coupon?: string, discount?: string, promotion_code?: string}[], metadata?: array<string, string>, plan?: string, price?: string, price_data?: array{currency: string, product: string, recurring: array{interval: string, interval_count?: int}, tax_behavior?: string, unit_amount?: int, unit_amount_decimal?: string}, quantity?: int, tax_rates?: null|string[]})[], iterations?: int, metadata?: array<string, string>, on_behalf_of?: string, proration_behavior?: string, start_date?: array|int|string, transfer_data?: array{amount_percent?: float, destination: string}, trial?: bool, trial_end?: array|int|string})[], proration_behavior?: string} $params
 * @param null|array|string $opts
 *
 * @return SubscriptionSchedule the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 121,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'currentClassName' => 'Stripe\\SubscriptionSchedule',
        'aliasName' => NULL,
      ),
      'cancel' => 
      array (
        'name' => 'cancel',
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
                'startLine' => 141,
                'endLine' => 141,
                'startTokenPos' => 463,
                'startFilePos' => 14617,
                'endTokenPos' => 463,
                'endFilePos' => 14620,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
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
                'startLine' => 141,
                'endLine' => 141,
                'startTokenPos' => 470,
                'startFilePos' => 14631,
                'endTokenPos' => 470,
                'endFilePos' => 14634,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
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
 * @return SubscriptionSchedule the canceled subscription schedule
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 141,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'currentClassName' => 'Stripe\\SubscriptionSchedule',
        'aliasName' => NULL,
      ),
      'release' => 
      array (
        'name' => 'release',
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
                'startLine' => 158,
                'endLine' => 158,
                'startTokenPos' => 547,
                'startFilePos' => 15122,
                'endTokenPos' => 547,
                'endFilePos' => 15125,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
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
                'startLine' => 158,
                'endLine' => 158,
                'startTokenPos' => 554,
                'startFilePos' => 15136,
                'endTokenPos' => 554,
                'endFilePos' => 15139,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
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
 * @return SubscriptionSchedule the released subscription schedule
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 158,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\SubscriptionSchedule',
        'implementingClassName' => 'Stripe\\SubscriptionSchedule',
        'currentClassName' => 'Stripe\\SubscriptionSchedule',
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