<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/InteractsWithAuthentication.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Dusk\Concerns\InteractsWithAuthentication
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b1562ca18511ecf338abc0e035ebeea64029ff53145e1f744435209ecca35e9d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/InteractsWithAuthentication.php',
      ),
    ),
    'namespace' => 'Laravel\\Dusk\\Concerns',
    'name' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
    'shortName' => 'InteractsWithAuthentication',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 123,
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
    ),
    'immediateMethods' => 
    array (
      'login' => 
      array (
        'name' => 'login',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Log into the application as the default user.
 *
 * @return $this
 */',
        'startLine' => 15,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'loginAs' => 
      array (
        'name' => 'loginAs',
        'parameters' => 
        array (
          'userId' => 
          array (
            'name' => 'userId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 71,
                'startFilePos' => 581,
                'endTokenPos' => 71,
                'endFilePos' => 584,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 38,
            'endColumn' => 50,
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
 * Log into the application using a given user ID or email.
 *
 * @param  object|string  $userId
 * @param  string|null  $guard
 * @return $this
 */',
        'startLine' => 27,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'logout' => 
      array (
        'name' => 'logout',
        'parameters' => 
        array (
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 40,
                'endLine' => 40,
                'startTokenPos' => 161,
                'startFilePos' => 1004,
                'endTokenPos' => 161,
                'endFilePos' => 1007,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 28,
            'endColumn' => 40,
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
 * Log out of the application.
 *
 * @param  string|null  $guard
 * @return $this
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
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'currentUserInfo' => 
      array (
        'name' => 'currentUserInfo',
        'parameters' => 
        array (
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 215,
                'startFilePos' => 1348,
                'endTokenPos' => 215,
                'endFilePos' => 1351,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 40,
            'endColumn' => 52,
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
 * Get the ID and the class name of the authenticated user.
 *
 * @param  string|null  $guard
 * @return array
 */',
        'startLine' => 51,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'assertAuthenticated' => 
      array (
        'name' => 'assertAuthenticated',
        'parameters' => 
        array (
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 285,
                'startFilePos' => 1749,
                'endTokenPos' => 285,
                'endFilePos' => 1752,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 41,
            'endColumn' => 53,
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
 * Assert that the user is authenticated.
 *
 * @param  string|null  $guard
 * @return $this
 */',
        'startLine' => 64,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'assertGuest' => 
      array (
        'name' => 'assertGuest',
        'parameters' => 
        array (
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 79,
                'endLine' => 79,
                'startTokenPos' => 343,
                'startFilePos' => 2135,
                'endTokenPos' => 343,
                'endFilePos' => 2138,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 33,
            'endColumn' => 45,
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
 * Assert that the user is not authenticated.
 *
 * @param  string|null  $guard
 * @return $this
 */',
        'startLine' => 79,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'assertAuthenticatedAs' => 
      array (
        'name' => 'assertAuthenticatedAs',
        'parameters' => 
        array (
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 406,
                'startFilePos' => 2608,
                'endTokenPos' => 406,
                'endFilePos' => 2611,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 50,
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
 * Assert that the user is authenticated as the given user.
 *
 * @param  mixed  $user
 * @param  string|null  $guard
 * @return $this
 */',
        'startLine' => 97,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'aliasName' => NULL,
      ),
      'shouldUseAbsoluteRouteForAuthentication' => 
      array (
        'name' => 'shouldUseAbsoluteRouteForAuthentication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if route() should use an absolute path.
 *
 * @return bool
 */',
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithAuthentication',
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