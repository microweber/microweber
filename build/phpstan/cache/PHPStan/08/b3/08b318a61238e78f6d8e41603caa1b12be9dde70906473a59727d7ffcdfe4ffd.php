<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Shipping/Drivers/PickupFromAddress.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Shipping\Drivers\PickupFromAddress
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-3fa2b89f7dda8d7f27825e732fb29bea2f01bb55c45863916e3e6b023e19ce6d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Shipping/Drivers/PickupFromAddress.php',
      ),
    ),
    'namespace' => 'Modules\\Shipping\\Drivers',
    'name' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
    'shortName' => 'PickupFromAddress',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 72,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Shipping\\Drivers\\AbstractShippingMethod',
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
      'provider' => 
      array (
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'name' => 'provider',
        'modifiers' => 1,
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
          'code' => '\'pickup_from_address\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 30,
            'startFilePos' => 153,
            'endTokenPos' => 30,
            'endFilePos' => 173,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 52,
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
      'title' => 
      array (
        'name' => 'title',
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
        'startLine' => 12,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'aliasName' => NULL,
      ),
      'getShippingCost' => 
      array (
        'name' => 'getShippingCost',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 17,
                'endLine' => 17,
                'startTokenPos' => 63,
                'startFilePos' => 308,
                'endTokenPos' => 64,
                'endFilePos' => 309,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 37,
            'endColumn' => 46,
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
                  'name' => 'float',
                  'isIdentifier' => true,
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
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'aliasName' => NULL,
      ),
      'getForm' => 
      array (
        'name' => 'getForm',
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
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'aliasName' => NULL,
      ),
      'getSettingsForm' => 
      array (
        'name' => 'getSettingsForm',
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
        'docComment' => NULL,
        'startLine' => 41,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\PickupFromAddress',
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