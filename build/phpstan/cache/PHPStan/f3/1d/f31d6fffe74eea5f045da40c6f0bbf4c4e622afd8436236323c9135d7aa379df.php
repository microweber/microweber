<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Routing\Middleware\ThrottleRequests
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-aa52dc3cec14e1f256a36a5ad23d1e5bbb25d7a5c6c31196dbf03613f7aa291c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php',
      ),
    ),
    'namespace' => 'Illuminate\\Routing\\Middleware',
    'name' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
    'shortName' => 'ThrottleRequests',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 344,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\InteractsWithTime',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'limiter' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'name' => 'limiter',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The rate limiter instance.
 *
 * @var \\Illuminate\\Cache\\RateLimiter
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'shouldHashKeys' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'name' => 'shouldHashKeys',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 85,
            'startFilePos' => 779,
            'endTokenPos' => 85,
            'endFilePos' => 782,
          ),
        ),
        'docComment' => '/**
 * Indicates if the rate limiter keys should be hashed.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 44,
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
          'limiter' => 
          array (
            'name' => 'limiter',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Cache\\RateLimiter',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 33,
            'endColumn' => 52,
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
 * Create a new request throttler.
 *
 * @param  \\Illuminate\\Cache\\RateLimiter  $limiter
 * @return void
 */',
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'using' => 
      array (
        'name' => 'using',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 34,
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
        'docComment' => '/**
 * Specify the named rate limiter to use for the middleware.
 *
 * @param  string  $name
 * @return string
 */',
        'startLine' => 51,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'with' => 
      array (
        'name' => 'with',
        'parameters' => 
        array (
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => 
            array (
              'code' => '60',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 156,
                'startFilePos' => 1554,
                'endTokenPos' => 156,
                'endFilePos' => 1555,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'decayMinutes' => 
          array (
            'name' => 'decayMinutes',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 163,
                'startFilePos' => 1574,
                'endTokenPos' => 163,
                'endFilePos' => 1574,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 52,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 170,
                'startFilePos' => 1587,
                'endTokenPos' => 170,
                'endFilePos' => 1588,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 71,
            'endColumn' => 82,
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
 * Specify the rate limiter configuration for the middleware.
 *
 * @param  int  $maxAttempts
 * @param  int  $decayMinutes
 * @param  string  $prefix
 * @return string
 *
 * @named-arguments-supported
 */',
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
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
            'startLine' => 84,
            'endLine' => 84,
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
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => 
            array (
              'code' => '60',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 216,
                'startFilePos' => 2193,
                'endTokenPos' => 216,
                'endFilePos' => 2194,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 53,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'decayMinutes' => 
          array (
            'name' => 'decayMinutes',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 223,
                'startFilePos' => 2213,
                'endTokenPos' => 223,
                'endFilePos' => 2213,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 72,
            'endColumn' => 88,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 230,
                'startFilePos' => 2226,
                'endTokenPos' => 230,
                'endFilePos' => 2227,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 91,
            'endColumn' => 102,
            'parameterIndex' => 4,
            'isOptional' => true,
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
 * @param  int|string  $maxAttempts
 * @param  float|int  $decayMinutes
 * @param  string  $prefix
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 *
 * @throws \\Illuminate\\Http\\Exceptions\\ThrottleRequestsException
 * @throws \\Illuminate\\Routing\\Exceptions\\MissingRateLimiterException
 */',
        'startLine' => 84,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'handleRequestUsingNamedLimiter' => 
      array (
        'name' => 'handleRequestUsingNamedLimiter',
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
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 55,
            'endColumn' => 62,
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
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 65,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'limiterName' => 
          array (
            'name' => 'limiterName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 80,
            'endColumn' => 91,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'limiter' => 
          array (
            'name' => 'limiter',
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
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 94,
            'endColumn' => 109,
            'parameterIndex' => 3,
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
 * @param  string  $limiterName
 * @param  \\Closure  $limiter
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 *
 * @throws \\Illuminate\\Http\\Exceptions\\ThrottleRequestsException
 */',
        'startLine' => 117,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'handleRequest' => 
      array (
        'name' => 'handleRequest',
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
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 38,
            'endColumn' => 45,
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
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 48,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'limits' => 
          array (
            'name' => 'limits',
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
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 63,
            'endColumn' => 75,
            'parameterIndex' => 2,
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
 * @param  array  $limits
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 *
 * @throws \\Illuminate\\Http\\Exceptions\\ThrottleRequestsException
 */',
        'startLine' => 151,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'resolveMaxAttempts' => 
      array (
        'name' => 'resolveMaxAttempts',
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
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 43,
            'endColumn' => 50,
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
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 53,
            'endColumn' => 64,
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
 * Resolve the number of attempts if the user is authenticated or not.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  int|string  $maxAttempts
 * @return int
 *
 * @throws \\Illuminate\\Routing\\Exceptions\\MissingRateLimiterException
 */',
        'startLine' => 183,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'resolveRequestSignature' => 
      array (
        'name' => 'resolveRequestSignature',
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
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 48,
            'endColumn' => 55,
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
 * Resolve request signature.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return string
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 213,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'buildException' => 
      array (
        'name' => 'buildException',
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
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 39,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 49,
            'endColumn' => 52,
            'parameterIndex' => 1,
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
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 55,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'responseCallback' => 
          array (
            'name' => 'responseCallback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 233,
                'endLine' => 233,
                'startTokenPos' => 1025,
                'startFilePos' => 7381,
                'endTokenPos' => 1025,
                'endFilePos' => 7384,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 69,
            'endColumn' => 92,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a \'too many attempts\' exception.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  string  $key
 * @param  int  $maxAttempts
 * @param  callable|null  $responseCallback
 * @return \\Illuminate\\Http\\Exceptions\\ThrottleRequestsException|\\Illuminate\\Http\\Exceptions\\HttpResponseException
 */',
        'startLine' => 233,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'getTimeUntilNextRetry' => 
      array (
        'name' => 'getTimeUntilNextRetry',
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
            'startLine' => 254,
            'endLine' => 254,
            'startColumn' => 46,
            'endColumn' => 49,
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
 * Get the number of seconds until the next retry.
 *
 * @param  string  $key
 * @return int
 */',
        'startLine' => 254,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'addHeaders' => 
      array (
        'name' => 'addHeaders',
        'parameters' => 
        array (
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HttpFoundation\\Response',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 268,
            'endLine' => 268,
            'startColumn' => 35,
            'endColumn' => 52,
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
            'startLine' => 268,
            'endLine' => 268,
            'startColumn' => 55,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'remainingAttempts' => 
          array (
            'name' => 'remainingAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 268,
            'endLine' => 268,
            'startColumn' => 69,
            'endColumn' => 86,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'retryAfter' => 
          array (
            'name' => 'retryAfter',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 268,
                'endLine' => 268,
                'startTokenPos' => 1163,
                'startFilePos' => 8526,
                'endTokenPos' => 1163,
                'endFilePos' => 8529,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 268,
            'endLine' => 268,
            'startColumn' => 89,
            'endColumn' => 106,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the limit header information to the given response.
 *
 * @param  \\Symfony\\Component\\HttpFoundation\\Response  $response
 * @param  int  $maxAttempts
 * @param  int  $remainingAttempts
 * @param  int|null  $retryAfter
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 268,
        'endLine' => 275,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'getHeaders' => 
      array (
        'name' => 'getHeaders',
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
            'startLine' => 286,
            'endLine' => 286,
            'startColumn' => 35,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'remainingAttempts' => 
          array (
            'name' => 'remainingAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 287,
            'endLine' => 287,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'retryAfter' => 
          array (
            'name' => 'retryAfter',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 288,
                'endLine' => 288,
                'startTokenPos' => 1219,
                'startFilePos' => 9071,
                'endTokenPos' => 1219,
                'endFilePos' => 9074,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 288,
            'endLine' => 288,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'response' => 
          array (
            'name' => 'response',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 289,
                'endLine' => 289,
                'startTokenPos' => 1229,
                'startFilePos' => 9107,
                'endTokenPos' => 1229,
                'endFilePos' => 9110,
              ),
            ),
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
                      'name' => 'Symfony\\Component\\HttpFoundation\\Response',
                      'isIdentifier' => false,
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
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the limit headers information.
 *
 * @param  int  $maxAttempts
 * @param  int  $remainingAttempts
 * @param  int|null  $retryAfter
 * @param  \\Symfony\\Component\\HttpFoundation\\Response|null  $response
 * @return array
 */',
        'startLine' => 286,
        'endLine' => 308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'calculateRemainingAttempts' => 
      array (
        'name' => 'calculateRemainingAttempts',
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 51,
            'endColumn' => 54,
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 57,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'retryAfter' => 
          array (
            'name' => 'retryAfter',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 318,
                'endLine' => 318,
                'startTokenPos' => 1373,
                'startFilePos' => 9976,
                'endTokenPos' => 1373,
                'endFilePos' => 9979,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 71,
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
 * Calculate the number of remaining attempts.
 *
 * @param  string  $key
 * @param  int  $maxAttempts
 * @param  int|null  $retryAfter
 * @return int
 */',
        'startLine' => 318,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'formatIdentifier' => 
      array (
        'name' => 'formatIdentifier',
        'parameters' => 
        array (
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
            'startLine' => 329,
            'endLine' => 329,
            'startColumn' => 39,
            'endColumn' => 44,
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
 * Format the given identifier based on the configured hashing settings.
 *
 * @param  string  $value
 * @return string
 */',
        'startLine' => 329,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'aliasName' => NULL,
      ),
      'shouldHashKeys' => 
      array (
        'name' => 'shouldHashKeys',
        'parameters' => 
        array (
          'shouldHashKeys' => 
          array (
            'name' => 'shouldHashKeys',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 340,
                'endLine' => 340,
                'startTokenPos' => 1455,
                'startFilePos' => 10563,
                'endTokenPos' => 1455,
                'endFilePos' => 10566,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 43,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Specify whether rate limiter keys should be hashed.
 *
 * @param  bool  $shouldHashKeys
 * @return void
 */',
        'startLine' => 340,
        'endLine' => 343,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Routing\\Middleware',
        'declaringClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'implementingClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
        'currentClassName' => 'Illuminate\\Routing\\Middleware\\ThrottleRequests',
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