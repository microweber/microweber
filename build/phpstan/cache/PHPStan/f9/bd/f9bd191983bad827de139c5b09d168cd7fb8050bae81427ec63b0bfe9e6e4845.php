<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/AttemptToAuthenticate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Actions\AttemptToAuthenticate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-404f5c7400b02b4a65edfb54978d509265333569120d8994d4ea4bb8a85eace3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/AttemptToAuthenticate.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Actions',
    'name' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
    'shortName' => 'AttemptToAuthenticate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 119,
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
      'guard' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'name' => 'guard',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The guard implementation.
 *
 * @var \\Illuminate\\Contracts\\Auth\\StatefulGuard
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'limiter' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'name' => 'limiter',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The login rate limiter instance.
 *
 * @var \\Laravel\\Fortify\\LoginRateLimiter
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
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'guard' => 
          array (
            'name' => 'guard',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Auth\\StatefulGuard',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 33,
            'endColumn' => 52,
            'parameterIndex' => 0,
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
                'name' => 'Laravel\\Fortify\\LoginRateLimiter',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 55,
            'endColumn' => 79,
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
 * Create a new controller instance.
 *
 * @param  \\Illuminate\\Contracts\\Auth\\StatefulGuard  $guard
 * @param  \\Laravel\\Fortify\\LoginRateLimiter  $limiter
 * @return void
 */',
        'startLine' => 34,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
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
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'next' => 
          array (
            'name' => 'next',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 38,
            'endColumn' => 42,
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
 * Handle the incoming request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  callable  $next
 * @return mixed
 *
 * @throws ValidationException
 */',
        'startLine' => 49,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'aliasName' => NULL,
      ),
      'handleUsingCustomCallback' => 
      array (
        'name' => 'handleUsingCustomCallback',
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
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 50,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'next' => 
          array (
            'name' => 'next',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 60,
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
 * Attempt to authenticate using a custom callback.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  callable  $next
 * @return mixed
 *
 * @throws ValidationException
 */',
        'startLine' => 74,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'aliasName' => NULL,
      ),
      'throwFailedAuthenticationException' => 
      array (
        'name' => 'throwFailedAuthenticationException',
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 59,
            'endColumn' => 66,
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
 * Throw a failed authentication validation exception.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return void
 *
 * @throws \\Illuminate\\Validation\\ValidationException
 */',
        'startLine' => 97,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'aliasName' => NULL,
      ),
      'fireFailedEvent' => 
      array (
        'name' => 'fireFailedEvent',
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 40,
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
 * Fire the failed authentication attempt event with the given arguments.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return void
 */',
        'startLine' => 112,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\AttemptToAuthenticate',
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