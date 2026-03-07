<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/PrepareAuthenticatedSession.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Actions\PrepareAuthenticatedSession
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ca7ed9ae7c9879e675577ffe711302c480add3491a3e37728e43f1dfcf88f637-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/PrepareAuthenticatedSession.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Actions',
    'name' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
    'shortName' => 'PrepareAuthenticatedSession',
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
    'endLine' => 44,
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
      'limiter' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
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
        'startLine' => 14,
        'endLine' => 14,
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
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 33,
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
 * Create a new class instance.
 *
 * @param  \\Laravel\\Fortify\\LoginRateLimiter  $limiter
 * @return void
 */',
        'startLine' => 22,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
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
            'startLine' => 34,
            'endLine' => 34,
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
            'startLine' => 34,
            'endLine' => 34,
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
 */',
        'startLine' => 34,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\PrepareAuthenticatedSession',
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