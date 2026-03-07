<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Shipping/Drivers/ShippingToCountry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Shipping\Drivers\ShippingToCountry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-5305b8681a9322ebfa69de1b7a4f8ad9c080ff5b923acf2c58a082b13e9e9cc2',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Shipping/Drivers/ShippingToCountry.php',
      ),
    ),
    'namespace' => 'Modules\\Shipping\\Drivers',
    'name' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
    'shortName' => 'ShippingToCountry',
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
    'endLine' => 274,
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
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
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
          'code' => '\'shipping_to_country\'',
          'attributes' => 
          array (
            'startLine' => 9,
            'endLine' => 9,
            'startTokenPos' => 30,
            'startFilePos' => 152,
            'endTokenPos' => 30,
            'endFilePos' => 172,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 9,
        'endLine' => 9,
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
        'startLine' => 11,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
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
                'startLine' => 16,
                'endLine' => 16,
                'startTokenPos' => 63,
                'startFilePos' => 307,
                'endTokenPos' => 64,
                'endFilePos' => 308,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
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
        'startLine' => 16,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
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
        'startLine' => 162,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
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
        'startLine' => 197,
        'endLine' => 273,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\ShippingToCountry',
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