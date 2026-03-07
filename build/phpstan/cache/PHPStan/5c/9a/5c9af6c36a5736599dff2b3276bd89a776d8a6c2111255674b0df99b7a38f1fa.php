<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Cashier.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Cashier
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e85f609fbe78b47f0381a5bf429491d228216edb0973b547194cb9e496db0f52-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Cashier',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Cashier.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier',
    'name' => 'Laravel\\Cashier\\Cashier',
    'shortName' => 'Cashier',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 252,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'VERSION' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'16.3.0\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 79,
            'startFilePos' => 471,
            'endTokenPos' => 79,
            'endFilePos' => 478,
          ),
        ),
        'docComment' => '/**
 * The Cashier library version.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'STRIPE_VERSION' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'STRIPE_VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Stripe\\Util\\ApiVersion::CURRENT',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 90,
            'startFilePos' => 582,
            'endTokenPos' => 92,
            'endFilePos' => 606,
          ),
        ),
        'docComment' => '/**
 * The Stripe API version.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
    ),
    'immediateProperties' => 
    array (
      'apiBaseUrl' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'apiBaseUrl',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Stripe\\BaseStripeClient::DEFAULT_API_BASE',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 105,
            'startFilePos' => 724,
            'endTokenPos' => 107,
            'endFilePos' => 757,
          ),
        ),
        'docComment' => '/**
 * The base URL for the Stripe API.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 67,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'formatCurrencyUsing' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'formatCurrencyUsing',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom currency formatter.
 *
 * @var callable
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'registersRoutes' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'registersRoutes',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 129,
            'startFilePos' => 1019,
            'endTokenPos' => 129,
            'endFilePos' => 1022,
          ),
        ),
        'docComment' => '/**
 * Indicates if Cashier routes will be registered.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'deactivatePastDue' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'deactivatePastDue',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 142,
            'startFilePos' => 1179,
            'endTokenPos' => 142,
            'endFilePos' => 1182,
          ),
        ),
        'docComment' => '/**
 * Indicates if Cashier will mark past due subscriptions as inactive.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'deactivateIncomplete' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'deactivateIncomplete',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 155,
            'startFilePos' => 1344,
            'endTokenPos' => 155,
            'endFilePos' => 1347,
          ),
        ),
        'docComment' => '/**
 * Indicates if Cashier will mark incomplete subscriptions as inactive.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'calculatesTaxes' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'calculatesTaxes',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 168,
            'startFilePos' => 1509,
            'endTokenPos' => 168,
            'endFilePos' => 1513,
          ),
        ),
        'docComment' => '/**
 * Indicates if Cashier will automatically calculate taxes using Stripe Tax.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customerModel' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'customerModel',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'App\\Models\\User\'',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 181,
            'startFilePos' => 1640,
            'endTokenPos' => 181,
            'endFilePos' => 1658,
          ),
        ),
        'docComment' => '/**
 * The default customer model class name.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 55,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'subscriptionModel' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'subscriptionModel',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Laravel\\Cashier\\Subscription::class',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 194,
            'startFilePos' => 1785,
            'endTokenPos' => 196,
            'endFilePos' => 1803,
          ),
        ),
        'docComment' => '/**
 * The subscription model class name.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 59,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'subscriptionItemModel' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'name' => 'subscriptionItemModel',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Laravel\\Cashier\\SubscriptionItem::class',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 209,
            'startFilePos' => 1939,
            'endTokenPos' => 211,
            'endFilePos' => 1961,
          ),
        ),
        'docComment' => '/**
 * The subscription item model class name.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 67,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'findBillable' => 
      array (
        'name' => 'findBillable',
        'parameters' => 
        array (
          'stripeId' => 
          array (
            'name' => 'stripeId',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Stripe\\Customer',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 41,
            'endColumn' => 76,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the customer instance by its Stripe ID.
 *
 * @param  \\Stripe\\Customer|string|null  $stripeId
 * @return \\Laravel\\Cashier\\Billable|null
 */',
        'startLine' => 101,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'stripe' => 
      array (
        'name' => 'stripe',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 120,
                'endLine' => 120,
                'startTokenPos' => 340,
                'startFilePos' => 2759,
                'endTokenPos' => 341,
                'endFilePos' => 2760,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 120,
            'endLine' => 120,
            'startColumn' => 35,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the Stripe SDK client.
 *
 * @param  array  $options
 * @return \\Stripe\\StripeClient
 */',
        'startLine' => 120,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'formatCurrencyUsing' => 
      array (
        'name' => 'formatCurrencyUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 137,
            'endLine' => 137,
            'startColumn' => 48,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the custom currency formatter.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 137,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'formatAmount' => 
      array (
        'name' => 'formatAmount',
        'parameters' => 
        array (
          'amount' => 
          array (
            'name' => 'amount',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 41,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'currency' => 
          array (
            'name' => 'currency',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 151,
                'endLine' => 151,
                'startTokenPos' => 467,
                'startFilePos' => 3633,
                'endTokenPos' => 467,
                'endFilePos' => 3636,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 54,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'locale' => 
          array (
            'name' => 'locale',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 151,
                'endLine' => 151,
                'startTokenPos' => 477,
                'startFilePos' => 3657,
                'endTokenPos' => 477,
                'endFilePos' => 3660,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 80,
            'endColumn' => 101,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 151,
                'endLine' => 151,
                'startTokenPos' => 486,
                'startFilePos' => 3680,
                'endTokenPos' => 487,
                'endFilePos' => 3681,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 104,
            'endColumn' => 122,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Format the given amount into a displayable currency.
 *
 * @param  int  $amount
 * @param  string|null  $currency
 * @param  string|null  $locale
 * @param  array  $options
 * @return string
 */',
        'startLine' => 151,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'ignoreRoutes' => 
      array (
        'name' => 'ignoreRoutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configure Cashier to not register its routes.
 *
 * @return static
 */',
        'startLine' => 177,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'keepPastDueSubscriptionsActive' => 
      array (
        'name' => 'keepPastDueSubscriptionsActive',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configure Cashier to maintain past due subscriptions as active.
 *
 * @return static
 */',
        'startLine' => 189,
        'endLine' => 194,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'keepIncompleteSubscriptionsActive' => 
      array (
        'name' => 'keepIncompleteSubscriptionsActive',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configure Cashier to maintain incomplete subscriptions as active.
 *
 * @return static
 */',
        'startLine' => 201,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'calculateTaxes' => 
      array (
        'name' => 'calculateTaxes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configure Cashier to automatically calculate taxes using Stripe Tax.
 *
 * @return static
 */',
        'startLine' => 213,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'useCustomerModel' => 
      array (
        'name' => 'useCustomerModel',
        'parameters' => 
        array (
          'customerModel' => 
          array (
            'name' => 'customerModel',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 226,
            'endLine' => 226,
            'startColumn' => 45,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the customer model class name.
 *
 * @param  class-string<\\Illuminate\\Database\\Eloquent\\Model>  $customerModel
 * @return void
 */',
        'startLine' => 226,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'useSubscriptionModel' => 
      array (
        'name' => 'useSubscriptionModel',
        'parameters' => 
        array (
          'subscriptionModel' => 
          array (
            'name' => 'subscriptionModel',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 49,
            'endColumn' => 73,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the subscription model class name.
 *
 * @param  class-string<\\Illuminate\\Database\\Eloquent\\Model>  $subscriptionModel
 * @return void
 */',
        'startLine' => 237,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
        'aliasName' => NULL,
      ),
      'useSubscriptionItemModel' => 
      array (
        'name' => 'useSubscriptionItemModel',
        'parameters' => 
        array (
          'subscriptionItemModel' => 
          array (
            'name' => 'subscriptionItemModel',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 53,
            'endColumn' => 81,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the subscription item model class name.
 *
 * @param  class-string<\\Illuminate\\Database\\Eloquent\\Model>  $subscriptionItemModel
 * @return void
 */',
        'startLine' => 248,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\Cashier',
        'implementingClassName' => 'Laravel\\Cashier\\Cashier',
        'currentClassName' => 'Laravel\\Cashier\\Cashier',
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