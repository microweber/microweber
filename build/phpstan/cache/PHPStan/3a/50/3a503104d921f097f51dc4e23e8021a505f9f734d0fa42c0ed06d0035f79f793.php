<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/support/src/helpers.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-Filament\Support\generate_search_column_expression
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-c3578fcbf914c38606e78b54618f8aff8cd0b738137eeb83e72ec82ed8f8afad',
   'data' => 
  array (
    'name' => 'generate_search_column_expression',
    'parameters' => 
    array (
      'column' => 
      array (
        'name' => 'column',
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
        'startLine' => 236,
        'endLine' => 236,
        'startColumn' => 48,
        'endColumn' => 61,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'isSearchForcedCaseInsensitive' => 
      array (
        'name' => 'isSearchForcedCaseInsensitive',
        'default' => NULL,
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
                  'name' => 'bool',
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
        'startLine' => 236,
        'endLine' => 236,
        'startColumn' => 64,
        'endColumn' => 99,
        'parameterIndex' => 1,
        'isOptional' => false,
      ),
      'databaseConnection' => 
      array (
        'name' => 'databaseConnection',
        'default' => NULL,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Connection',
            'isIdentifier' => false,
          ),
        ),
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 236,
        'endLine' => 236,
        'startColumn' => 102,
        'endColumn' => 131,
        'parameterIndex' => 2,
        'isOptional' => false,
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
              'name' => 'string',
              'isIdentifier' => true,
            ),
          ),
          1 => 
          array (
            'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
            'data' => 
            array (
              'name' => 'Illuminate\\Database\\Query\\Expression',
              'isIdentifier' => false,
            ),
          ),
        ),
      ),
    ),
    'attributes' => 
    array (
    ),
    'docComment' => '/**
 * @internal This function is only to be used internally by Filament and is subject to change at any time. Please do not use this function in your own code.
 */',
    'startLine' => 236,
    'endLine' => 307,
    'startColumn' => 5,
    'endColumn' => 5,
    'couldThrow' => false,
    'isClosure' => false,
    'isGenerator' => false,
    'isVariadic' => false,
    'isStatic' => false,
    'namespace' => 'Filament\\Support',
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Support\\generate_search_column_expression',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/support/src/helpers.php',
      ),
    ),
  ),
));