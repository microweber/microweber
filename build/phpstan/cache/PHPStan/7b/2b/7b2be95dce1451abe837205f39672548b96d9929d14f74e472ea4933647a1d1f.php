<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Billing/Services/SubscriptionManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Billing\Services\SubscriptionManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-736ade220d4a5baecd9c07348111f0950d8c20196ab5453fa4cada6eaa0315d8',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Billing/Services/SubscriptionManager.php',
      ),
    ),
    'namespace' => 'Modules\\Billing\\Services',
    'name' => 'Modules\\Billing\\Services\\SubscriptionManager',
    'shortName' => 'SubscriptionManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 264,
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
    ),
    'immediateMethods' => 
    array (
      'handleCart' => 
      array (
        'name' => 'handleCart',
        'parameters' => 
        array (
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 15,
            'endLine' => 15,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 15,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'createOrder' => 
      array (
        'name' => 'createOrder',
        'parameters' => 
        array (
          'subscriptionCustomer' => 
          array (
            'name' => 'subscriptionCustomer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Billing\\Models\\SubscriptionCustomer',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 34,
            'endColumn' => 75,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 78,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'isPaid' => 
          array (
            'name' => 'isPaid',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 171,
                'startFilePos' => 885,
                'endTokenPos' => 171,
                'endFilePos' => 889,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 85,
            'endColumn' => 99,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 32,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'attachCartItemsToOrder' => 
      array (
        'name' => 'attachCartItemsToOrder',
        'parameters' => 
        array (
          'order' => 
          array (
            'name' => 'order',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 68,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'prepareCheckoutData' => 
      array (
        'name' => 'prepareCheckoutData',
        'parameters' => 
        array (
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 42,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'order' => 
          array (
            'name' => 'order',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 49,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'subscriptionCustomer' => 
          array (
            'name' => 'subscriptionCustomer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 57,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'subscription\'',
              'attributes' => 
              array (
                'startLine' => 81,
                'endLine' => 81,
                'startTokenPos' => 544,
                'startFilePos' => 2625,
                'endTokenPos' => 544,
                'endFilePos' => 2638,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 80,
            'endColumn' => 101,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 81,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'triggerCheckoutEvent' => 
      array (
        'name' => 'triggerCheckoutEvent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 108,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'getSubscriptionCustomer' => 
      array (
        'name' => 'getSubscriptionCustomer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 118,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'subscribeToPlan' => 
      array (
        'name' => 'subscribeToPlan',
        'parameters' => 
        array (
          'sku' => 
          array (
            'name' => 'sku',
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
            'startColumn' => 37,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'referer' => 
          array (
            'name' => 'referer',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 148,
                'endLine' => 148,
                'startTokenPos' => 982,
                'startFilePos' => 4704,
                'endTokenPos' => 982,
                'endFilePos' => 4707,
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
            'startColumn' => 43,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 148,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'findSwapablePlan' => 
      array (
        'name' => 'findSwapablePlan',
        'parameters' => 
        array (
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 39,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subscriptionCustomer' => 
          array (
            'name' => 'subscriptionCustomer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 46,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 179,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'swapSubscription' => 
      array (
        'name' => 'swapSubscription',
        'parameters' => 
        array (
          'subscriptionCustomer' => 
          array (
            'name' => 'subscriptionCustomer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Billing\\Models\\SubscriptionCustomer',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 38,
            'endColumn' => 79,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 82,
            'endColumn' => 86,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'newPlan' => 
          array (
            'name' => 'newPlan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 89,
            'endColumn' => 96,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 205,
        'endLine' => 232,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'newSubscription' => 
      array (
        'name' => 'newSubscription',
        'parameters' => 
        array (
          'subscriptionCustomer' => 
          array (
            'name' => 'subscriptionCustomer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Billing\\Models\\SubscriptionCustomer',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 234,
            'endLine' => 234,
            'startColumn' => 37,
            'endColumn' => 78,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 234,
            'endLine' => 234,
            'startColumn' => 81,
            'endColumn' => 85,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Laravel\\Cashier\\Checkout',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 234,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'aliasName' => NULL,
      ),
      'newPurchase' => 
      array (
        'name' => 'newPurchase',
        'parameters' => 
        array (
          'subscriptionCustomer' => 
          array (
            'name' => 'subscriptionCustomer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Billing\\Models\\SubscriptionCustomer',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 33,
            'endColumn' => 74,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 77,
            'endColumn' => 81,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Laravel\\Cashier\\Checkout',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 249,
        'endLine' => 263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Services',
        'declaringClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'implementingClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
        'currentClassName' => 'Modules\\Billing\\Services\\SubscriptionManager',
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