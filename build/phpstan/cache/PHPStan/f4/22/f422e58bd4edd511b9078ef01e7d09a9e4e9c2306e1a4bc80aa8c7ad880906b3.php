<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Billing/Services/StripeService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Billing\Services\StripeService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-8904f083845b138e89e22b76d9c3cc107a84925b1348cfa8ef20251e214f97c2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Billing\\Services\\StripeService',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Billing/Services/StripeService.php',
      ),
    ),
    'namespace' => 'Modules\\Billing\\Services',
    'name' => 'Modules\\Billing\\Services\\StripeService',
    'shortName' => 'StripeService',
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
    'endLine' => 236,
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
    ),
    'immediateProperties' => 
    array (
      'stripe' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'name' => 'stripe',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var StripeClient
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'paymentProivderId' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'name' => 'paymentProivderId',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 76,
            'startFilePos' => 532,
            'endTokenPos' => 76,
            'endFilePos' => 532,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'paymentProivderType' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'name' => 'paymentProivderType',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'stripe\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 85,
            'startFilePos' => 569,
            'endTokenPos' => 85,
            'endFilePos' => 576,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 43,
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
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 25,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'getProducts' => 
      array (
        'name' => 'getProducts',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 34,
                'endLine' => 34,
                'startTokenPos' => 157,
                'startFilePos' => 967,
                'endTokenPos' => 158,
                'endFilePos' => 968,
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 33,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
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
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'getPrices' => 
      array (
        'name' => 'getPrices',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 191,
                'startFilePos' => 1084,
                'endTokenPos' => 192,
                'endFilePos' => 1085,
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
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 31,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'getInvoices' => 
      array (
        'name' => 'getInvoices',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 44,
                'endLine' => 44,
                'startTokenPos' => 225,
                'startFilePos' => 1201,
                'endTokenPos' => 226,
                'endFilePos' => 1202,
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 33,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'getPaymentProivderId' => 
      array (
        'name' => 'getPaymentProivderId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 49,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'getPaymentProivderType' => 
      array (
        'name' => 'getPaymentProivderType',
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
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'testConnection' => 
      array (
        'name' => 'testConnection',
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
        'startLine' => 59,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'syncProducts' => 
      array (
        'name' => 'syncProducts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 69,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
        'aliasName' => NULL,
      ),
      'syncCustomers' => 
      array (
        'name' => 'syncCustomers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Fetch all customers from Stripe and sync to local Customer model.
 *
 * @return int Number of customers synced
 */',
        'startLine' => 136,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\StripeService',
        'implementingClassName' => 'Modules\\Billing\\Services\\StripeService',
        'currentClassName' => 'Modules\\Billing\\Services\\StripeService',
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