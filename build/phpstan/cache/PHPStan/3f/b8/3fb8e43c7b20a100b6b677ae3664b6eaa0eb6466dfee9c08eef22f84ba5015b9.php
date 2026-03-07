<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/NamespacedItemResolver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\NamespacedItemResolver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c7ddddf870eb3b480a99a01587f05aa2a7a6a18e20d986621d9aacf79e3ecbe1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\NamespacedItemResolver',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/NamespacedItemResolver.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support',
    'name' => 'Illuminate\\Support\\NamespacedItemResolver',
    'shortName' => 'NamespacedItemResolver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 112,
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
      'parsed' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'implementingClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'name' => 'parsed',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 21,
            'startFilePos' => 170,
            'endTokenPos' => 22,
            'endFilePos' => 171,
          ),
        ),
        'docComment' => '/**
 * A cache of the parsed items.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 27,
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
      'parseKey' => 
      array (
        'name' => 'parseKey',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 20,
            'endLine' => 20,
            'startColumn' => 30,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse a key into namespace, group, and item.
 *
 * @param  string  $key
 * @return array
 */',
        'startLine' => 20,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support',
        'declaringClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'implementingClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'currentClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'aliasName' => NULL,
      ),
      'parseBasicSegments' => 
      array (
        'name' => 'parseBasicSegments',
        'parameters' => 
        array (
          'segments' => 
          array (
            'name' => 'segments',
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
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 43,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse an array of basic segments.
 *
 * @param  array  $segments
 * @return array
 */',
        'startLine' => 52,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support',
        'declaringClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'implementingClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'currentClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'aliasName' => NULL,
      ),
      'parseNamespacedSegments' => 
      array (
        'name' => 'parseNamespacedSegments',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 48,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse an array of namespaced segments.
 *
 * @param  string  $key
 * @return array
 */',
        'startLine' => 75,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support',
        'declaringClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'implementingClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'currentClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'aliasName' => NULL,
      ),
      'setParsedKey' => 
      array (
        'name' => 'setParsedKey',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 34,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parsed' => 
          array (
            'name' => 'parsed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the parsed value of a key.
 *
 * @param  string  $key
 * @param  array  $parsed
 * @return void
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support',
        'declaringClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'implementingClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'currentClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'aliasName' => NULL,
      ),
      'flushParsedKeys' => 
      array (
        'name' => 'flushParsedKeys',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the cache of parsed keys.
 *
 * @return void
 */',
        'startLine' => 108,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support',
        'declaringClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'implementingClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
        'currentClassName' => 'Illuminate\\Support\\NamespacedItemResolver',
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