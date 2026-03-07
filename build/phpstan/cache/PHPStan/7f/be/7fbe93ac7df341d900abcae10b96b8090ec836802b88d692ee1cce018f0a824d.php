<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Shipping/Drivers/FlatRate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Shipping\Drivers\FlatRate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-b29832072576ee32a770ccfe8671315606175e55dd9edfb7d2af3f2aaff4d153',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Shipping/Drivers/FlatRate.php',
      ),
    ),
    'namespace' => 'Modules\\Shipping\\Drivers',
    'name' => 'Modules\\Shipping\\Drivers\\FlatRate',
    'shortName' => 'FlatRate',
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
    'endLine' => 78,
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
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
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
          'code' => '\'flat_rate\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 30,
            'startFilePos' => 144,
            'endTokenPos' => 30,
            'endFilePos' => 154,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 42,
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
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
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
                'startFilePos' => 279,
                'endTokenPos' => 64,
                'endFilePos' => 280,
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
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
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
        'startLine' => 27,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
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
        'startLine' => 43,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Shipping\\Drivers',
        'declaringClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'implementingClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
        'currentClassName' => 'Modules\\Shipping\\Drivers\\FlatRate',
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