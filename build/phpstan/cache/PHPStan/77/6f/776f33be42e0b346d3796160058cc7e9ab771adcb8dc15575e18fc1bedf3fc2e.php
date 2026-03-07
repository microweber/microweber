<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cache/RateLimiting/Limit.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Cache\RateLimiting\Limit
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e138a2ffd30f8cba0316e768c21be63d809513d3d9d2702eaa997af24483621d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cache/RateLimiting/Limit.php',
      ),
    ),
    'namespace' => 'Illuminate\\Cache\\RateLimiting',
    'name' => 'Illuminate\\Cache\\RateLimiting\\Limit',
    'shortName' => 'Limit',
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
    'endLine' => 157,
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
      'key' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'name' => 'key',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The rate limit signature key.
 *
 * @var mixed
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'maxAttempts' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'name' => 'maxAttempts',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The maximum number of attempts allowed within the given number of seconds.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'decaySeconds' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'name' => 'decaySeconds',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The number of seconds until the rate limit is reset.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'responseCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'name' => 'responseCallback',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The response generator callback.
 *
 * @var callable
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 29,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 53,
                'startFilePos' => 759,
                'endTokenPos' => 53,
                'endFilePos' => 760,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => 
            array (
              'code' => '60',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 62,
                'startFilePos' => 782,
                'endTokenPos' => 62,
                'endFilePos' => 783,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
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
            'startColumn' => 44,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'decaySeconds' => 
          array (
            'name' => 'decaySeconds',
            'default' => 
            array (
              'code' => '60',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 71,
                'startFilePos' => 806,
                'endTokenPos' => 71,
                'endFilePos' => 807,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
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
            'startColumn' => 67,
            'endColumn' => 88,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new limit instance.
 *
 * @param  mixed  $key
 * @param  int  $maxAttempts
 * @param  int  $decaySeconds
 * @return void
 */',
        'startLine' => 43,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'perSecond' => 
      array (
        'name' => 'perSecond',
        'parameters' => 
        array (
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decaySeconds' => 
          array (
            'name' => 'decaySeconds',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 57,
                'endLine' => 57,
                'startTokenPos' => 122,
                'startFilePos' => 1149,
                'endTokenPos' => 122,
                'endFilePos' => 1149,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 52,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new rate limit.
 *
 * @param  int  $maxAttempts
 * @param  int  $decaySeconds
 * @return static
 */',
        'startLine' => 57,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'perMinute' => 
      array (
        'name' => 'perMinute',
        'parameters' => 
        array (
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 38,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decayMinutes' => 
          array (
            'name' => 'decayMinutes',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 69,
                'endLine' => 69,
                'startTokenPos' => 162,
                'startFilePos' => 1436,
                'endTokenPos' => 162,
                'endFilePos' => 1436,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 52,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new rate limit.
 *
 * @param  int  $maxAttempts
 * @param  int  $decayMinutes
 * @return static
 */',
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'perMinutes' => 
      array (
        'name' => 'perMinutes',
        'parameters' => 
        array (
          'decayMinutes' => 
          array (
            'name' => 'decayMinutes',
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
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
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
            'startColumn' => 54,
            'endColumn' => 65,
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
 * Create a new rate limit using minutes as decay time.
 *
 * @param  int  $decayMinutes
 * @param  int  $maxAttempts
 * @return static
 */',
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'perHour' => 
      array (
        'name' => 'perHour',
        'parameters' => 
        array (
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 36,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decayHours' => 
          array (
            'name' => 'decayHours',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 246,
                'startFilePos' => 2065,
                'endTokenPos' => 246,
                'endFilePos' => 2065,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 50,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new rate limit using hours as decay time.
 *
 * @param  int  $maxAttempts
 * @param  int  $decayHours
 * @return static
 */',
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'perDay' => 
      array (
        'name' => 'perDay',
        'parameters' => 
        array (
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
            'startColumn' => 35,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decayDays' => 
          array (
            'name' => 'decayDays',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 105,
                'endLine' => 105,
                'startTokenPos' => 294,
                'startFilePos' => 2376,
                'endTokenPos' => 294,
                'endFilePos' => 2376,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
            'startColumn' => 49,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new rate limit using days as decay time.
 *
 * @param  int  $maxAttempts
 * @param  int  $decayDays
 * @return static
 */',
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'none' => 
      array (
        'name' => 'none',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new unlimited rate limit.
 *
 * @return static
 */',
        'startLine' => 115,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'by' => 
      array (
        'name' => 'by',
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
            'startLine' => 126,
            'endLine' => 126,
            'startColumn' => 24,
            'endColumn' => 27,
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
 * Set the key of the rate limit.
 *
 * @param  mixed  $key
 * @return $this
 */',
        'startLine' => 126,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'response' => 
      array (
        'name' => 'response',
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
                'name' => 'callable',
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
            'startColumn' => 30,
            'endColumn' => 47,
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
 * Set the callback that should generate the response when the limit is exceeded.
 *
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 139,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'aliasName' => NULL,
      ),
      'fallbackKey' => 
      array (
        'name' => 'fallbackKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a potential fallback key for the limit.
 *
 * @return string
 */',
        'startLine' => 151,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache\\RateLimiting',
        'declaringClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'implementingClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
        'currentClassName' => 'Illuminate\\Cache\\RateLimiting\\Limit',
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