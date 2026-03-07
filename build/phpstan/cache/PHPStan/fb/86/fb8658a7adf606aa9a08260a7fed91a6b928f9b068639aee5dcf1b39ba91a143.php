<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Http\Middleware\TrimStrings
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b38cf8e3534b5977d8864161bcb0d43ca5fe4994bccae4f9514d73b519812fca-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
    'name' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
    'shortName' => 'TrimStrings',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 119,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest',
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
      'except' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'name' => 'except',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'current_password\', \'password\', \'password_confirmation\']',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 20,
            'startTokenPos' => 40,
            'startFilePos' => 301,
            'endTokenPos' => 51,
            'endFilePos' => 388,
          ),
        ),
        'docComment' => '/**
 * The attributes that should not be trimmed.
 *
 * @var array<int, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'neverTrim' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'name' => 'neverTrim',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 64,
            'startFilePos' => 534,
            'endTokenPos' => 65,
            'endFilePos' => 535,
          ),
        ),
        'docComment' => '/**
 * The globally ignored attributes that should not be trimmed.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'skipCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'name' => 'skipCallbacks',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 78,
            'startFilePos' => 663,
            'endTokenPos' => 79,
            'endFilePos' => 664,
          ),
        ),
        'docComment' => '/**
 * All of the registered skip callbacks.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 41,
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
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'next' => 
          array (
            'name' => 'next',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 38,
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
        'docComment' => '/**
 * Handle an incoming request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Closure  $next
 * @return mixed
 */',
        'startLine' => 43,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'aliasName' => NULL,
      ),
      'transform' => 
      array (
        'name' => 'transform',
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
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 34,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 40,
            'endColumn' => 45,
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
 * Transform the given value.
 *
 * @param  string  $key
 * @param  mixed  $value
 * @return mixed
 */',
        'startLine' => 61,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'aliasName' => NULL,
      ),
      'shouldSkip' => 
      array (
        'name' => 'shouldSkip',
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
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 35,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'except' => 
          array (
            'name' => 'except',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 41,
            'endColumn' => 47,
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
 * Determine if the given key should be skipped.
 *
 * @param  string  $key
 * @param  array  $except
 * @return bool
 */',
        'startLine' => 79,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'aliasName' => NULL,
      ),
      'except' => 
      array (
        'name' => 'except',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 90,
            'endLine' => 90,
            'startColumn' => 35,
            'endColumn' => 45,
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
 * Indicate that the given attributes should never be trimmed.
 *
 * @param  array|string  $attributes
 * @return void
 */',
        'startLine' => 90,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'aliasName' => NULL,
      ),
      'skipWhen' => 
      array (
        'name' => 'skipWhen',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 37,
            'endColumn' => 53,
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
 * Register a callback that instructs the middleware to be skipped.
 *
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 103,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'aliasName' => NULL,
      ),
      'flushState' => 
      array (
        'name' => 'flushState',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the middleware\'s global state.
 *
 * @return void
 */',
        'startLine' => 113,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\TrimStrings',
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