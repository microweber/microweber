<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Controllers/TwoFactorAuthenticationController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2fd9e3b5429baa5fb75b7a89e32cd61f90dc7a4d5117b729a59180b544b76477-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Http/Controllers/TwoFactorAuthenticationController.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
    'name' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
    'shortName' => 'TwoFactorAuthenticationController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 41,
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
    ),
    'immediateMethods' => 
    array (
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 27,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'enable' => 
          array (
            'name' => 'enable',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 45,
            'endColumn' => 81,
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
 * Enable two factor authentication for the user.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication  $enable
 * @return \\Laravel\\Fortify\\Contracts\\TwoFactorEnabledResponse
 */',
        'startLine' => 21,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 29,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'disable' => 
          array (
            'name' => 'disable',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Fortify\\Actions\\DisableTwoFactorAuthentication',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 47,
            'endColumn' => 85,
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
 * Disable two factor authentication for the user.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Laravel\\Fortify\\Actions\\DisableTwoFactorAuthentication  $disable
 * @return \\Laravel\\Fortify\\Contracts\\TwoFactorDisabledResponse
 */',
        'startLine' => 35,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
        'implementingClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
        'currentClassName' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController',
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