<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Order/Models/Order.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Order\Models\Order
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-b5e0bf6d8932863f3dbab34e3583cc87b8af6bb5a642741eeb28b73031e3187e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Order\\Models\\Order',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Order/Models/Order.php',
      ),
    ),
    'namespace' => 'Modules\\Order\\Models',
    'name' => 'Modules\\Order\\Models\\Order',
    'shortName' => 'Order',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 243,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Notifications\\Notifiable',
      1 => 'EloquentFilter\\Filterable',
      2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      3 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
      4 => 'Modules\\Cart\\Traits\\HasCartItems',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'name' => 'table',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'cart_orders\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 134,
            'startFilePos' => 1009,
            'endTokenPos' => 134,
            'endFilePos' => 1021,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'name' => 'fillable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'email\', \'first_name\', \'last_name\', \'country\', \'city\', \'state\', \'zip\', \'address\', \'address2\', \'other_info\', \'phone\', \'custom_fields_data\', \'order_status\', \'customer_id\', \'payment_provider_id\', \'payment_provider\', \'shipping_provider_id\', \'shipping_provider\', \'order_reference_id\']',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 65,
            'startTokenPos' => 143,
            'startFilePos' => 1047,
            'endTokenPos' => 202,
            'endFilePos' => 1486,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'searchable' => 
      array (
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'name' => 'searchable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'is_completed\', \'order_reference_id\', \'email\', \'first_name\', \'last_name\', \'country\', \'city\', \'state\', \'zip\', \'address\', \'address2\', \'other_info\', \'phone\', \'custom_fields_data\', \'order_status\', \'customer_id\']',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 85,
            'startTokenPos' => 211,
            'startFilePos' => 1518,
            'endTokenPos' => 261,
            'endFilePos' => 1861,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 85,
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
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[
    // \'order_status\' => OrderStatus::class,
    \'payment_data\' => \'array\',
    \'custom_fields_data\' => \'array\',
]',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 91,
            'startTokenPos' => 270,
            'startFilePos' => 1888,
            'endTokenPos' => 288,
            'endFilePos' => 2018,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 91,
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
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 94,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
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
        'startLine' => 130,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'payments' => 
      array (
        'name' => 'payments',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'customer' => 
      array (
        'name' => 'customer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 141,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'shippingMethodName' => 
      array (
        'name' => 'shippingMethodName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 146,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'addressText' => 
      array (
        'name' => 'addressText',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 158,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'paymentMethodName' => 
      array (
        'name' => 'paymentMethodName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 167,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'customerName' => 
      array (
        'name' => 'customerName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 173,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'thumbnail' => 
      array (
        'name' => 'thumbnail',
        'parameters' => 
        array (
          'width' => 
          array (
            'name' => 'width',
            'default' => 
            array (
              'code' => '100',
              'attributes' => 
              array (
                'startLine' => 191,
                'endLine' => 191,
                'startTokenPos' => 729,
                'startFilePos' => 4567,
                'endTokenPos' => 729,
                'endFilePos' => 4569,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 191,
            'endLine' => 191,
            'startColumn' => 31,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'height' => 
          array (
            'name' => 'height',
            'default' => 
            array (
              'code' => '100',
              'attributes' => 
              array (
                'startLine' => 191,
                'endLine' => 191,
                'startTokenPos' => 736,
                'startFilePos' => 4582,
                'endTokenPos' => 736,
                'endFilePos' => 4584,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 191,
            'endLine' => 191,
            'startColumn' => 45,
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
        'startLine' => 191,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'cartProducts' => 
      array (
        'name' => 'cartProducts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 201,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
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
        'startLine' => 220,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'getPaymentStatuses' => 
      array (
        'name' => 'getPaymentStatuses',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 226,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
        'aliasName' => NULL,
      ),
      'getOrderStatuses' => 
      array (
        'name' => 'getOrderStatuses',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 235,
        'endLine' => 242,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Order\\Models',
        'declaringClassName' => 'Modules\\Order\\Models\\Order',
        'implementingClassName' => 'Modules\\Order\\Models\\Order',
        'currentClassName' => 'Modules\\Order\\Models\\Order',
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