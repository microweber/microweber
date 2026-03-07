<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Http\Controllers\AuthenticatedSessionController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8ac5523f39438f70e1c4b971846c5d2026cba2a2fa55db5b84ba5bbe226c87ec-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Controllers/AuthenticatedSessionController.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
    'name' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
    'shortName' => 'AuthenticatedSessionController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 111,
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
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
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
        'startLine' => 28,
        'endLine' => 28,
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
            'startLine' => 36,
            'endLine' => 36,
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
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
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
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 28,
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
            'name' => 'Laravel\\Fortify\\Contracts\\LoginViewResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Show the login view.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Laravel\\Fortify\\Contracts\\LoginViewResponse
 */',
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
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
                'name' => 'Laravel\\Fortify\\Http\\Requests\\LoginRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 27,
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
 * Attempt to authenticate a new session.
 *
 * @param  \\Laravel\\Fortify\\Http\\Requests\\LoginRequest  $request
 * @return mixed
 */',
        'startLine' => 58,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'aliasName' => NULL,
      ),
      'loginPipeline' => 
      array (
        'name' => 'loginPipeline',
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
                'name' => 'Laravel\\Fortify\\Http\\Requests\\LoginRequest',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 38,
            'endColumn' => 58,
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
 * Get the authentication pipeline instance.
 *
 * @param  \\Laravel\\Fortify\\Http\\Requests\\LoginRequest  $request
 * @return \\Illuminate\\Pipeline\\Pipeline
 */',
        'startLine' => 71,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'aliasName' => NULL,
      ),
      'destroy' => 
      array (
        'name' => 'destroy',
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
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 29,
            'endColumn' => 44,
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
            'name' => 'Laravel\\Fortify\\Contracts\\LogoutResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Destroy an authenticated session.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Laravel\\Fortify\\Contracts\\LogoutResponse
 */',
        'startLine' => 100,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\AuthenticatedSessionController',
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