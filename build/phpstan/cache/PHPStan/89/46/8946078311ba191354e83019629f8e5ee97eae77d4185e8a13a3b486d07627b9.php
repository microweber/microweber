<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Foundation/MaintenanceMode.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Foundation\MaintenanceMode
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9cfa4744f819a10c17f1d34fc870259a193571501f4f374a9eb70d7110f6939a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Foundation/MaintenanceMode.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Foundation',
    'name' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
    'shortName' => 'MaintenanceMode',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 35,
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
      'activate' => 
      array (
        'name' => 'activate',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
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
            'startLine' => 13,
            'endLine' => 13,
            'startColumn' => 30,
            'endColumn' => 43,
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
 * Take the application down for maintenance.
 *
 * @param  array  $payload
 * @return void
 */',
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Foundation',
        'declaringClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'implementingClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'currentClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'aliasName' => NULL,
      ),
      'deactivate' => 
      array (
        'name' => 'deactivate',
        'parameters' => 
        array (
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
 * Take the application out of maintenance.
 *
 * @return void
 */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Foundation',
        'declaringClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'implementingClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'currentClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'aliasName' => NULL,
      ),
      'active' => 
      array (
        'name' => 'active',
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
 * Determine if the application is currently down for maintenance.
 *
 * @return bool
 */',
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Foundation',
        'declaringClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'implementingClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'currentClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'aliasName' => NULL,
      ),
      'data' => 
      array (
        'name' => 'data',
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
 * Get the data array which was provided when the application was placed into maintenance.
 *
 * @return array
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Foundation',
        'declaringClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'implementingClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
        'currentClassName' => 'Illuminate\\Contracts\\Foundation\\MaintenanceMode',
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