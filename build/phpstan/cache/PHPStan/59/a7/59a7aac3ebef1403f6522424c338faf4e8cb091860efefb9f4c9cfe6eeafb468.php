<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/SubscriptionBuilder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\SubscriptionBuilder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-fa20ae67b07e21ad6f02d6283b778f604f2a852ac56f1b2e5eb921fa4f679e5f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/SubscriptionBuilder.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier',
    'name' => 'Laravel\\Cashier\\SubscriptionBuilder',
    'shortName' => 'SubscriptionBuilder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 556,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
      1 => 'Illuminate\\Support\\Traits\\Conditionable',
      2 => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
      3 => 'Laravel\\Cashier\\Concerns\\HandlesTaxes',
      4 => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
      5 => 'Laravel\\Cashier\\Concerns\\InteractsWithStripe',
      6 => 'Laravel\\Cashier\\Concerns\\Prorates',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'owner' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'owner',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The model that is subscribing.
 *
 * @var \\Laravel\\Cashier\\Billable|\\Illuminate\\Database\\Eloquent\\Model
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'type' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'type',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The type of the subscription.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'items' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'items',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 158,
            'startFilePos' => 1245,
            'endTokenPos' => 159,
            'endFilePos' => 1246,
          ),
        ),
        'docComment' => '/**
 * The prices the customer is being subscribed to.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'trialExpires' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'trialExpires',
        'modifiers' => 2,
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
                  'name' => 'Carbon\\CarbonInterface',
                  'isIdentifier' => false,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 173,
            'startFilePos' => 1409,
            'endTokenPos' => 173,
            'endFilePos' => 1412,
          ),
        ),
        'docComment' => '/**
 * The date and time the trial will expire.
 *
 * @var \\Carbon\\CarbonInterface|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 52,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'skipTrial' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'skipTrial',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 186,
            'startFilePos' => 1544,
            'endTokenPos' => 186,
            'endFilePos' => 1548,
          ),
        ),
        'docComment' => '/**
 * Indicates that the trial should end immediately.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'billingCycleAnchor' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'billingCycleAnchor',
        'modifiers' => 2,
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
                  'name' => 'int',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 200,
            'startFilePos' => 1700,
            'endTokenPos' => 200,
            'endFilePos' => 1703,
          ),
        ),
        'docComment' => '/**
 * The date on which the billing cycle should be anchored.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'billingThresholds' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'billingThresholds',
        'modifiers' => 2,
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
                  'name' => 'array',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 214,
            'startFilePos' => 1847,
            'endTokenPos' => 214,
            'endFilePos' => 1850,
          ),
        ),
        'docComment' => '/**
 * The billing thresholds for the subscription.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'metadata' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'name' => 'metadata',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 227,
            'startFilePos' => 1977,
            'endTokenPos' => 228,
            'endFilePos' => 1978,
          ),
        ),
        'docComment' => '/**
 * The metadata to apply to the subscription.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 35,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'owner' => 
          array (
            'name' => 'owner',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 41,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'prices' => 
          array (
            'name' => 'prices',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 96,
                'endLine' => 96,
                'startTokenPos' => 255,
                'startFilePos' => 2259,
                'endTokenPos' => 256,
                'endFilePos' => 2260,
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
                      'name' => 'array',
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
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 55,
            'endColumn' => 79,
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
 * Create a new subscription builder instance.
 *
 * @param  mixed  $owner
 * @param  string  $type
 * @param  string|string[]|array[]  $prices
 * @return void
 */',
        'startLine' => 96,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'price' => 
      array (
        'name' => 'price',
        'parameters' => 
        array (
          'price' => 
          array (
            'name' => 'price',
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
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
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 27,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'quantity' => 
          array (
            'name' => 'quantity',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 113,
                'endLine' => 113,
                'startTokenPos' => 327,
                'startFilePos' => 2654,
                'endTokenPos' => 327,
                'endFilePos' => 2654,
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
                      'name' => 'int',
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
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 48,
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
 * Set a price on the subscription builder.
 *
 * @param  string|array  $price
 * @param  int|null  $quantity
 * @return $this
 */',
        'startLine' => 113,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'meteredPrice' => 
      array (
        'name' => 'meteredPrice',
        'parameters' => 
        array (
          'price' => 
          array (
            'name' => 'price',
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
            'startLine' => 142,
            'endLine' => 142,
            'startColumn' => 34,
            'endColumn' => 46,
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
 * Set a metered price on the subscription builder.
 *
 * @param  string  $price
 * @return $this
 */',
        'startLine' => 142,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'quantity' => 
      array (
        'name' => 'quantity',
        'parameters' => 
        array (
          'quantity' => 
          array (
            'name' => 'quantity',
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
                      'name' => 'int',
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
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 30,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'price' => 
          array (
            'name' => 'price',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 154,
                'endLine' => 154,
                'startTokenPos' => 530,
                'startFilePos' => 3660,
                'endTokenPos' => 530,
                'endFilePos' => 3663,
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
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 46,
            'endColumn' => 66,
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
 * Specify the quantity of a subscription item.
 *
 * @param  int|null  $quantity
 * @param  string|null  $price
 * @return $this
 */',
        'startLine' => 154,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'trialDays' => 
      array (
        'name' => 'trialDays',
        'parameters' => 
        array (
          'trialDays' => 
          array (
            'name' => 'trialDays',
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
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 31,
            'endColumn' => 44,
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
 * Specify the number of days of the trial.
 *
 * @param  int  $trialDays
 * @return $this
 */',
        'startLine' => 177,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'trialUntil' => 
      array (
        'name' => 'trialUntil',
        'parameters' => 
        array (
          'trialUntil' => 
          array (
            'name' => 'trialUntil',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Carbon\\CarbonInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 190,
            'endLine' => 190,
            'startColumn' => 32,
            'endColumn' => 58,
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
 * Specify the ending date of the trial.
 *
 * @param  \\Carbon\\Carbon|\\Carbon\\CarbonInterface  $trialUntil
 * @return $this
 */',
        'startLine' => 190,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'skipTrial' => 
      array (
        'name' => 'skipTrial',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Force the trial to end immediately.
 *
 * @return $this
 */',
        'startLine' => 202,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'anchorBillingCycleOn' => 
      array (
        'name' => 'anchorBillingCycleOn',
        'parameters' => 
        array (
          'date' => 
          array (
            'name' => 'date',
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
                      'name' => 'DateTimeInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
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
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 42,
            'endColumn' => 68,
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
 * Change the billing cycle anchor on a subscription creation.
 *
 * @param  \\DateTimeInterface|int  $date
 * @return $this
 */',
        'startLine' => 215,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'withBillingThresholds' => 
      array (
        'name' => 'withBillingThresholds',
        'parameters' => 
        array (
          'thresholds' => 
          array (
            'name' => 'thresholds',
            'default' => NULL,
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
            'startLine' => 232,
            'endLine' => 232,
            'startColumn' => 43,
            'endColumn' => 59,
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
 * Set billing thresholds for the subscription.
 *
 * @param  array{amount_gte?: int|null, reset_billing_cycle_anchor?: bool|null}  $thresholds
 * @return $this
 */',
        'startLine' => 232,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'withMetadata' => 
      array (
        'name' => 'withMetadata',
        'parameters' => 
        array (
          'metadata' => 
          array (
            'name' => 'metadata',
            'default' => NULL,
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
            'startLine' => 245,
            'endLine' => 245,
            'startColumn' => 34,
            'endColumn' => 48,
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
 * The metadata to apply to a new subscription.
 *
 * @param  array  $metadata
 * @return $this
 */',
        'startLine' => 245,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'customerOptions' => 
          array (
            'name' => 'customerOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 261,
                'endLine' => 261,
                'startTokenPos' => 869,
                'startFilePos' => 6240,
                'endTokenPos' => 870,
                'endFilePos' => 6241,
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
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 25,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'subscriptionOptions' => 
          array (
            'name' => 'subscriptionOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 261,
                'endLine' => 261,
                'startTokenPos' => 879,
                'startFilePos' => 6273,
                'endTokenPos' => 880,
                'endFilePos' => 6274,
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
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 54,
            'endColumn' => 84,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Laravel\\Cashier\\Subscription',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a new Stripe subscription to the Stripe model.
 *
 * @param  array  $customerOptions
 * @param  array  $subscriptionOptions
 * @return \\Laravel\\Cashier\\Subscription
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\IncompletePayment
 */',
        'startLine' => 261,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'paymentMethod' => 
          array (
            'name' => 'paymentMethod',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 277,
                'endLine' => 277,
                'startTokenPos' => 918,
                'startFilePos' => 6774,
                'endTokenPos' => 918,
                'endFilePos' => 6777,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 28,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'customerOptions' => 
          array (
            'name' => 'customerOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 277,
                'endLine' => 277,
                'startTokenPos' => 927,
                'startFilePos' => 6805,
                'endTokenPos' => 928,
                'endFilePos' => 6806,
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
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 51,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'subscriptionOptions' => 
          array (
            'name' => 'subscriptionOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 277,
                'endLine' => 277,
                'startTokenPos' => 937,
                'startFilePos' => 6838,
                'endTokenPos' => 938,
                'endFilePos' => 6839,
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
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 80,
            'endColumn' => 110,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Laravel\\Cashier\\Subscription',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new Stripe subscription.
 *
 * @param  \\Stripe\\PaymentMethod|string|null  $paymentMethod
 * @param  array  $customerOptions
 * @param  array  $subscriptionOptions
 * @return \\Laravel\\Cashier\\Subscription
 *
 * @throws \\Exception
 * @throws \\Laravel\\Cashier\\Exceptions\\IncompletePayment
 */',
        'startLine' => 277,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'createAndSendInvoice' => 
      array (
        'name' => 'createAndSendInvoice',
        'parameters' => 
        array (
          'customerOptions' => 
          array (
            'name' => 'customerOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 308,
                'endLine' => 308,
                'startTokenPos' => 1073,
                'startFilePos' => 7876,
                'endTokenPos' => 1074,
                'endFilePos' => 7877,
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
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 42,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'subscriptionOptions' => 
          array (
            'name' => 'subscriptionOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 308,
                'endLine' => 308,
                'startTokenPos' => 1083,
                'startFilePos' => 7909,
                'endTokenPos' => 1084,
                'endFilePos' => 7910,
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
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 71,
            'endColumn' => 101,
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
 * Create a new Stripe subscription and send an invoice to the customer.
 *
 * @param  array  $customerOptions
 * @param  array  $subscriptionOptions
 * @return \\Laravel\\Cashier\\Subscription
 *
 * @throws \\Exception
 * @throws \\Laravel\\Cashier\\Exceptions\\IncompletePayment
 */',
        'startLine' => 308,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'createSubscription' => 
      array (
        'name' => 'createSubscription',
        'parameters' => 
        array (
          'stripeSubscription' => 
          array (
            'name' => 'stripeSubscription',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Stripe\\Subscription',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 323,
            'endLine' => 323,
            'startColumn' => 43,
            'endColumn' => 80,
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
 * Create the Eloquent Subscription.
 *
 * @param  \\Stripe\\Subscription  $stripeSubscription
 * @return \\Laravel\\Cashier\\Subscription
 */',
        'startLine' => 323,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'checkout' => 
      array (
        'name' => 'checkout',
        'parameters' => 
        array (
          'sessionOptions' => 
          array (
            'name' => 'sessionOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 375,
                'endLine' => 375,
                'startTokenPos' => 1527,
                'startFilePos' => 10370,
                'endTokenPos' => 1528,
                'endFilePos' => 10371,
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
            'startLine' => 375,
            'endLine' => 375,
            'startColumn' => 30,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'customerOptions' => 
          array (
            'name' => 'customerOptions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 375,
                'endLine' => 375,
                'startTokenPos' => 1537,
                'startFilePos' => 10399,
                'endTokenPos' => 1538,
                'endFilePos' => 10400,
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
            'startLine' => 375,
            'endLine' => 375,
            'startColumn' => 58,
            'endColumn' => 84,
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
 * Begin a new Checkout Session.
 *
 * @param  array  $sessionOptions
 * @param  array  $customerOptions
 * @return \\Laravel\\Cashier\\Checkout
 */',
        'startLine' => 375,
        'endLine' => 412,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'getStripeCustomer' => 
      array (
        'name' => 'getStripeCustomer',
        'parameters' => 
        array (
          'paymentMethod' => 
          array (
            'name' => 'paymentMethod',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1859,
                'startFilePos' => 12531,
                'endTokenPos' => 1859,
                'endFilePos' => 12534,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
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
                'startLine' => 421,
                'endLine' => 421,
                'startTokenPos' => 1868,
                'startFilePos' => 12554,
                'endTokenPos' => 1869,
                'endFilePos' => 12555,
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
            'startLine' => 421,
            'endLine' => 421,
            'startColumn' => 65,
            'endColumn' => 83,
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
 * Get the Stripe customer instance for the current user and payment method.
 *
 * @param  \\Stripe\\PaymentMethod|string|null  $paymentMethod
 * @param  array  $options
 * @return \\Stripe\\Customer
 */',
        'startLine' => 421,
        'endLine' => 430,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'buildPayload' => 
      array (
        'name' => 'buildPayload',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the payload for subscription creation.
 *
 * @return array
 */',
        'startLine' => 437,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'getTrialEndForPayload' => 
      array (
        'name' => 'getTrialEndForPayload',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'int',
                  'isIdentifier' => true,
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the trial ending date for the Stripe payload.
 *
 * @return int|string|null
 */',
        'startLine' => 482,
        'endLine' => 493,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'getTaxRatesForPayload' => 
      array (
        'name' => 'getTaxRatesForPayload',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'array',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the tax rates for the Stripe payload.
 *
 * @return array|null
 */',
        'startLine' => 500,
        'endLine' => 507,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'getPriceTaxRatesForPayload' => 
      array (
        'name' => 'getPriceTaxRatesForPayload',
        'parameters' => 
        array (
          'price' => 
          array (
            'name' => 'price',
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
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
            'startLine' => 515,
            'endLine' => 515,
            'startColumn' => 51,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'array',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the price tax rates for the Stripe payload.
 *
 * @param  string|array  $price
 * @return array|null
 */',
        'startLine' => 515,
        'endLine' => 522,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'validateCouponForSubscriptionApplication' => 
      array (
        'name' => 'validateCouponForSubscriptionApplication',
        'parameters' => 
        array (
          'couponId' => 
          array (
            'name' => 'couponId',
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
            'startLine' => 533,
            'endLine' => 533,
            'startColumn' => 65,
            'endColumn' => 80,
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
 * Validate that a coupon can be applied to a subscription.
 *
 * @param  string  $couponId
 * @return void
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\InvalidCoupon
 * @throws \\Stripe\\Exception\\ApiErrorException
 */',
        'startLine' => 533,
        'endLine' => 545,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'aliasName' => NULL,
      ),
      'getItems' => 
      array (
        'name' => 'getItems',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the items set on the subscription builder.
 *
 * @return array
 */',
        'startLine' => 552,
        'endLine' => 555,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionBuilder',
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