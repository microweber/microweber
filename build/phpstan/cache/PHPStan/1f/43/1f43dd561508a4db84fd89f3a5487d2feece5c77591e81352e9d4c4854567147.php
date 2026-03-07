<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Customer/Models/Customer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Customer\Models\Customer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-e2f71013945302e3698e17e25926af651d05e673dcd299aa5bf6cbaad08b5e43',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Customer\\Models\\Customer',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Customer/Models/Customer.php',
      ),
    ),
    'namespace' => 'Modules\\Customer\\Models',
    'name' => 'Modules\\Customer\\Models\\Customer',
    'shortName' => 'Customer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 300,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'EloquentFilter\\Filterable',
      2 => 'MicroweberPackages\\Database\\Traits\\CacheableQueryBuilderTrait',
      3 => 'Laravel\\Cashier\\Billable',
      4 => 'Illuminate\\Notifications\\Notifiable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'customers\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 103,
            'startFilePos' => 676,
            'endTokenPos' => 103,
            'endFilePos' => 686,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'factory' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'factory',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\\Modules\\Customer\\Database\\Factories\\CustomerFactory::class',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 116,
            'startFilePos' => 729,
            'endTokenPos' => 118,
            'endFilePos' => 787,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 99,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cacheTagsToClear' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'cacheTagsToClear',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'countries\', \'addresses\', \'customers\', \'users\', \'companies\']',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 127,
            'startFilePos' => 822,
            'endTokenPos' => 141,
            'endFilePos' => 882,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 93,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \'active\']',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 33,
            'startTokenPos' => 150,
            'startFilePos' => 914,
            'endTokenPos' => 159,
            'endFilePos' => 950,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'fillable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'first_name\', \'last_name\', \'phone\', \'email\', \'status\', \'customer_data\', \'user_id\', \'currency_id\', \'currency\', \'company_id\', \'stripe_id\', \'pm_type\', \'pm_last_four\', \'trial_ends_at\']',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 53,
            'startTokenPos' => 168,
            'startFilePos' => 977,
            'endTokenPos' => 215,
            'endFilePos' => 1294,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'customer_data\' => \'array\']',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 58,
            'startTokenPos' => 224,
            'startFilePos' => 1322,
            'endTokenPos' => 232,
            'endFilePos' => 1363,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'translatable' => 
      array (
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'name' => 'translatable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'first_name\', \'last_name\']',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 241,
            'startFilePos' => 1393,
            'endTokenPos' => 246,
            'endFilePos' => 1419,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 55,
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
      'modelFilter' => 
      array (
        'name' => 'modelFilter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'scopeActive' => 
      array (
        'name' => 'scopeActive',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 33,
            'endColumn' => 38,
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
        'startLine' => 77,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'scopeInactive' => 
      array (
        'name' => 'scopeInactive',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 35,
            'endColumn' => 40,
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
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'addresses' => 
      array (
        'name' => 'addresses',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 93,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'billingAddress' => 
      array (
        'name' => 'billingAddress',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'shippingAddress' => 
      array (
        'name' => 'shippingAddress',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 109,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'orders' => 
      array (
        'name' => 'orders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'user' => 
      array (
        'name' => 'user',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 124,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'scopeWhereDisplayName' => 
      array (
        'name' => 'scopeWhereDisplayName',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'displayName' => 
          array (
            'name' => 'displayName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 51,
            'endColumn' => 62,
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
        'startLine' => 129,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'scopeWherePhone' => 
      array (
        'name' => 'scopeWherePhone',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 134,
            'endLine' => 134,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'phone' => 
          array (
            'name' => 'phone',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 134,
            'endLine' => 134,
            'startColumn' => 45,
            'endColumn' => 50,
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
        'startLine' => 134,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'scopeApplyFilters' => 
      array (
        'name' => 'scopeApplyFilters',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'filters' => 
          array (
            'name' => 'filters',
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
            'startLine' => 139,
            'endLine' => 139,
            'startColumn' => 47,
            'endColumn' => 60,
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
        'startLine' => 139,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'activeOptions' => 
      array (
        'name' => 'activeOptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 179,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'cityAndCountry' => 
      array (
        'name' => 'cityAndCountry',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 206,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'getFullName' => 
      array (
        'name' => 'getFullName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 232,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'email' => 
      array (
        'name' => 'email',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 250,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'getEmail' => 
      array (
        'name' => 'getEmail',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 256,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'getPhone' => 
      array (
        'name' => 'getPhone',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 272,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'company' => 
      array (
        'name' => 'company',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 286,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'currency' => 
      array (
        'name' => 'currency',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 291,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
        'aliasName' => NULL,
      ),
      'stripeEmail' => 
      array (
        'name' => 'stripeEmail',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 295,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Customer\\Models',
        'declaringClassName' => 'Modules\\Customer\\Models\\Customer',
        'implementingClassName' => 'Modules\\Customer\\Models\\Customer',
        'currentClassName' => 'Modules\\Customer\\Models\\Customer',
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