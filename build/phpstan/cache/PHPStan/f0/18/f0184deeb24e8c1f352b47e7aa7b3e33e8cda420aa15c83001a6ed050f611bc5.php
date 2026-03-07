<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Billing/Models/BillingUser.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Billing\Models\BillingUser
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-4ef3f7ffc768041ef74ace5e355d7b2c6be78ed236cc7713017f6c959e8933f1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Billing\\Models\\BillingUser',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Billing/Models/BillingUser.php',
      ),
    ),
    'namespace' => 'Modules\\Billing\\Models',
    'name' => 'Modules\\Billing\\Models\\BillingUser',
    'shortName' => 'BillingUser',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 55,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'App\\Models\\User',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'users\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 28,
            'startFilePos' => 121,
            'endTokenPos' => 28,
            'endFilePos' => 127,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 31,
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
      'getActiveSubscription' => 
      array (
        'name' => 'getActiveSubscription',
        'parameters' => 
        array (
          'sku' => 
          array (
            'name' => 'sku',
            'default' => 
            array (
              'code' => '\'hosting\'',
              'attributes' => 
              array (
                'startLine' => 18,
                'endLine' => 18,
                'startTokenPos' => 53,
                'startFilePos' => 353,
                'endTokenPos' => 53,
                'endFilePos' => 361,
              ),
            ),
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
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 43,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => true,
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
        'docComment' => NULL,
        'startLine' => 18,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'currentClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'aliasName' => NULL,
      ),
      'getSubscriptionName' => 
      array (
        'name' => 'getSubscriptionName',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 23,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'currentClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'aliasName' => NULL,
      ),
      'hasActiveSubscription' => 
      array (
        'name' => 'hasActiveSubscription',
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
        'docComment' => NULL,
        'startLine' => 29,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'currentClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'aliasName' => NULL,
      ),
      'subscriptionManual' => 
      array (
        'name' => 'subscriptionManual',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'currentClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'aliasName' => NULL,
      ),
      'subscriptions' => 
      array (
        'name' => 'subscriptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'currentClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'aliasName' => NULL,
      ),
      'subscriptionCustomer' => 
      array (
        'name' => 'subscriptionCustomer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 51,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'implementingClassName' => 'Modules\\Billing\\Models\\BillingUser',
        'currentClassName' => 'Modules\\Billing\\Models\\BillingUser',
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