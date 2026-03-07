<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/RedirectIfTwoFactorAuthenticatable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1d3e734a157bbb8c659c776da3c5bbae1b80d0ec989dfe3161aa0712dd4de641-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/RedirectIfTwoFactorAuthenticatable.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Actions',
    'name' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
    'shortName' => 'RedirectIfTwoFactorAuthenticatable',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 157,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Laravel\\Fortify\\Contracts\\RedirectsIfTwoFactorAuthenticatable',
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
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
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
        'startLine' => 21,
        'endLine' => 21,
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
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
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
        'startLine' => 28,
        'endLine' => 28,
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
            'startLine' => 37,
            'endLine' => 37,
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
            'startLine' => 37,
            'endLine' => 37,
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
        'startLine' => 37,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
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
            'startLine' => 50,
            'endLine' => 50,
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
            'startLine' => 50,
            'endLine' => 50,
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
        'startLine' => 50,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'aliasName' => NULL,
      ),
      'validateCredentials' => 
      array (
        'name' => 'validateCredentials',
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
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 44,
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
 * Attempt to validate the incoming credentials.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return mixed
 */',
        'startLine' => 78,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
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
            'startLine' => 113,
            'endLine' => 113,
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
        'startLine' => 113,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
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
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 40,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'user' => 
          array (
            'name' => 'user',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 129,
                'endLine' => 129,
                'startTokenPos' => 589,
                'startFilePos' => 4126,
                'endTokenPos' => 589,
                'endFilePos' => 4129,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 50,
            'endColumn' => 61,
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
 * Fire the failed authentication attempt event with the given arguments.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Illuminate\\Contracts\\Auth\\Authenticatable|null  $user
 * @return void
 */',
        'startLine' => 129,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'aliasName' => NULL,
      ),
      'twoFactorChallengeResponse' => 
      array (
        'name' => 'twoFactorChallengeResponse',
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
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 51,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 61,
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
 * Get the two factor authentication enabled response.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  mixed  $user
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 144,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\RedirectIfTwoFactorAuthenticatable',
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