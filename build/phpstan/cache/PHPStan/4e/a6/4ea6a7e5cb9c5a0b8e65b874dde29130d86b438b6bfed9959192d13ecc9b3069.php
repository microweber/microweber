<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Controllers/TwoFactorAuthenticatedSessionController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2d25f01ca1002663fb51d107633d41f25545f3f48ac09e819f5d0cfc4ec154d0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Controllers/TwoFactorAuthenticatedSessionController.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
    'name' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
    'shortName' => 'TwoFactorAuthenticatedSessionController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 76,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Routing\\Controller',
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
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
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
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 21,
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
            'startLine' => 30,
            'endLine' => 30,
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
 * Create a new controller instance.
 *
 * @param  \\Illuminate\\Contracts\\Auth\\StatefulGuard  $guard
 * @return void
 */',
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 28,
            'endColumn' => 57,
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
            'name' => 'Laravel\\Fortify\\Contracts\\TwoFactorChallengeViewResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Show the two factor authentication challenge view.
 *
 * @param  \\Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest  $request
 * @return \\Laravel\\Fortify\\Contracts\\TwoFactorChallengeViewResponse
 */',
        'startLine' => 41,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'aliasName' => NULL,
      ),
      'store' => 
      array (
        'name' => 'store',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 27,
            'endColumn' => 56,
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
 * Attempt to authenticate a new session using the two factor authentication code.
 *
 * @param  \\Laravel\\Fortify\\Http\\Requests\\TwoFactorLoginRequest  $request
 * @return mixed
 */',
        'startLine' => 56,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController',
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