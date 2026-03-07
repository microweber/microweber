<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/SubscriptionItem.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\SubscriptionItem
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-79b836fce9cfdeff1e377f685c7b560f1688f4a759f5dac8f719b0ebc894ad8c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\SubscriptionItem',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/SubscriptionItem.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier',
    'name' => 'Laravel\\Cashier\\SubscriptionItem',
    'shortName' => 'SubscriptionItem',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property \\Laravel\\Cashier\\Subscription|null $subscription
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 403,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
      3 => 'Laravel\\Cashier\\Concerns\\Prorates',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'guarded' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'name' => 'guarded',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 97,
            'startFilePos' => 822,
            'endTokenPos' => 98,
            'endFilePos' => 823,
          ),
        ),
        'docComment' => '/**
 * The attributes that are not mass assignable.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'quantity\' => \'integer\', \'meter_id\' => \'string\', \'meter_event_name\' => \'string\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 42,
            'startTokenPos' => 109,
            'startFilePos' => 950,
            'endTokenPos' => 132,
            'endFilePos' => 1061,
          ),
        ),
        'docComment' => '/**
 * The attributes that should be cast to native types.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'subscription' => 
      array (
        'name' => 'subscription',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the subscription that the item belongs to.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo
 */',
        'startLine' => 49,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'incrementQuantity' => 
      array (
        'name' => 'incrementQuantity',
        'parameters' => 
        array (
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 196,
                'startFilePos' => 1639,
                'endTokenPos' => 196,
                'endFilePos' => 1639,
              ),
            ),
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
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 39,
            'endColumn' => 52,
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
 * Increment the quantity of the subscription item.
 *
 * @param  int  $count
 * @return $this
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\SubscriptionUpdateFailure
 */',
        'startLine' => 64,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'incrementAndInvoice' => 
      array (
        'name' => 'incrementAndInvoice',
        'parameters' => 
        array (
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 80,
                'endLine' => 80,
                'startTokenPos' => 236,
                'startFilePos' => 2078,
                'endTokenPos' => 236,
                'endFilePos' => 2078,
              ),
            ),
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
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 41,
            'endColumn' => 54,
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
 *  Increment the quantity of the subscription item, and invoice immediately.
 *
 * @param  int  $count
 * @return $this
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\IncompletePayment
 * @throws \\Laravel\\Cashier\\Exceptions\\SubscriptionUpdateFailure
 */',
        'startLine' => 80,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'decrementQuantity' => 
      array (
        'name' => 'decrementQuantity',
        'parameters' => 
        array (
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 277,
                'startFilePos' => 2446,
                'endTokenPos' => 277,
                'endFilePos' => 2446,
              ),
            ),
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 39,
            'endColumn' => 52,
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
 * Decrement the quantity of the subscription item.
 *
 * @param  int  $count
 * @return $this
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\SubscriptionUpdateFailure
 */',
        'startLine' => 97,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'updateQuantity' => 
      array (
        'name' => 'updateQuantity',
        'parameters' => 
        array (
          'quantity' => 
          array (
            'name' => 'quantity',
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 36,
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
 * Update the quantity of the subscription item.
 *
 * @param  int  $quantity
 * @return $this
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\SubscriptionUpdateFailure
 */',
        'startLine' => 112,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'swap' => 
      array (
        'name' => 'swap',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 26,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 152,
                'endLine' => 152,
                'startTokenPos' => 510,
                'startFilePos' => 3974,
                'endTokenPos' => 511,
                'endFilePos' => 3975,
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 41,
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
 * Swap the subscription item to a new Stripe price.
 *
 * @param  string  $price
 * @param  array  $options
 * @return $this
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\SubscriptionUpdateFailure
 */',
        'startLine' => 152,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'swapAndInvoice' => 
      array (
        'name' => 'swapAndInvoice',
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
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 215,
                'endLine' => 215,
                'startTokenPos' => 896,
                'startFilePos' => 6208,
                'endTokenPos' => 897,
                'endFilePos' => 6209,
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
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 51,
            'endColumn' => 69,
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
 * Swap the subscription item to a new Stripe price, and invoice immediately.
 *
 * @param  string  $price
 * @param  array  $options
 * @return $this
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\IncompletePayment
 * @throws \\Laravel\\Cashier\\Exceptions\\SubscriptionUpdateFailure
 */',
        'startLine' => 215,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'reportUsage' => 
      array (
        'name' => 'reportUsage',
        'parameters' => 
        array (
          'quantity' => 
          array (
            'name' => 'quantity',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 229,
                'endLine' => 229,
                'startTokenPos' => 938,
                'startFilePos' => 6548,
                'endTokenPos' => 938,
                'endFilePos' => 6548,
              ),
            ),
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
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 229,
                'endLine' => 229,
                'startTokenPos' => 951,
                'startFilePos' => 6591,
                'endTokenPos' => 951,
                'endFilePos' => 6594,
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
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 52,
            'endColumn' => 95,
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
 * Report usage for a metered product.
 *
 * @param  int  $quantity
 * @param  \\DateTimeInterface|int|null  $timestamp
 * @return \\Stripe\\V2\\Billing\\MeterEvent
 */',
        'startLine' => 229,
        'endLine' => 272,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'usageRecords' => 
      array (
        'name' => 'usageRecords',
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
                'startLine' => 280,
                'endLine' => 280,
                'startTokenPos' => 1314,
                'startFilePos' => 8522,
                'endTokenPos' => 1315,
                'endFilePos' => 8523,
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
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 34,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the usage records for a metered product.
 *
 * @param  array  $options
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 280,
        'endLine' => 308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'currentPeriodStart' => 
      array (
        'name' => 'currentPeriodStart',
        'parameters' => 
        array (
          'timezone' => 
          array (
            'name' => 'timezone',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 316,
                'endLine' => 316,
                'startTokenPos' => 1539,
                'startFilePos' => 9835,
                'endTokenPos' => 1539,
                'endFilePos' => 9838,
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
            'startLine' => 316,
            'endLine' => 316,
            'startColumn' => 40,
            'endColumn' => 63,
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
 * Get the current period start date for this subscription item.
 *
 * @param  string|null  $timezone
 * @return \\Illuminate\\Support\\Carbon|null
 */',
        'startLine' => 316,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'currentPeriodEnd' => 
      array (
        'name' => 'currentPeriodEnd',
        'parameters' => 
        array (
          'timezone' => 
          array (
            'name' => 'timezone',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 335,
                'endLine' => 335,
                'startTokenPos' => 1626,
                'startFilePos' => 10375,
                'endTokenPos' => 1626,
                'endFilePos' => 10378,
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 38,
            'endColumn' => 61,
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
 * Get the current period end date for this subscription item.
 *
 * @param  string|null  $timezone
 * @return \\Illuminate\\Support\\Carbon|null
 */',
        'startLine' => 335,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'onTrial' => 
      array (
        'name' => 'onTrial',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the subscription item is currently within its trial period.
 *
 * @return bool
 */',
        'startLine' => 353,
        'endLine' => 356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'onGracePeriod' => 
      array (
        'name' => 'onGracePeriod',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the subscription item is on a grace period after cancellation.
 *
 * @return bool
 */',
        'startLine' => 363,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'updateStripeSubscriptionItem' => 
      array (
        'name' => 'updateStripeSubscriptionItem',
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
                'startLine' => 374,
                'endLine' => 374,
                'startTokenPos' => 1768,
                'startFilePos' => 11373,
                'endTokenPos' => 1769,
                'endFilePos' => 11374,
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
            'startLine' => 374,
            'endLine' => 374,
            'startColumn' => 50,
            'endColumn' => 68,
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
 * Update the underlying Stripe subscription item information for the model.
 *
 * @param  array  $options
 * @return \\Stripe\\SubscriptionItem
 */',
        'startLine' => 374,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'asStripeSubscriptionItem' => 
      array (
        'name' => 'asStripeSubscriptionItem',
        'parameters' => 
        array (
          'expand' => 
          array (
            'name' => 'expand',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 387,
                'endLine' => 387,
                'startTokenPos' => 1817,
                'startFilePos' => 11740,
                'endTokenPos' => 1818,
                'endFilePos' => 11741,
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
            'startLine' => 387,
            'endLine' => 387,
            'startColumn' => 46,
            'endColumn' => 63,
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
 * Get the subscription as a Stripe subscription item object.
 *
 * @param  array  $expand
 * @return \\Stripe\\SubscriptionItem
 */',
        'startLine' => 387,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new factory instance for the model.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Factories\\Factory
 */',
        'startLine' => 399,
        'endLine' => 402,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Laravel\\Cashier',
        'declaringClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'implementingClassName' => 'Laravel\\Cashier\\SubscriptionItem',
        'currentClassName' => 'Laravel\\Cashier\\SubscriptionItem',
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