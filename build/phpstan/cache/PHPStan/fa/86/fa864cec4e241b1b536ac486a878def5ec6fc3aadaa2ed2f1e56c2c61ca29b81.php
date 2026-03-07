<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/sanctum/src/Sanctum.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Sanctum\Sanctum
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cf72df8fd0650cf17cb83ef396841b6ee05065873eb83ce545b66aac67fa32f5-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Sanctum\\Sanctum',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/sanctum/src/Sanctum.php',
      ),
    ),
    'namespace' => 'Laravel\\Sanctum',
    'name' => 'Laravel\\Sanctum\\Sanctum',
    'shortName' => 'Sanctum',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TToken of \\Laravel\\Sanctum\\Contracts\\HasAbilities = \\Laravel\\Sanctum\\PersonalAccessToken
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 137,
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
      'personalAccessTokenModel' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'name' => 'personalAccessTokenModel',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Laravel\\Sanctum\\PersonalAccessToken\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 30,
            'startFilePos' => 329,
            'endTokenPos' => 30,
            'endFilePos' => 367,
          ),
        ),
        'docComment' => '/**
 * The personal access client model class name.
 *
 * @var class-string<TToken>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 86,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'accessTokenRetrievalCallback' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'name' => 'accessTokenRetrievalCallback',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * A callback that can get the token from the request.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 48,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'accessTokenAuthenticationCallback' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'name' => 'accessTokenAuthenticationCallback',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * A callback that can add to the validation of the access token.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 53,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'currentRequestHostPlaceholder' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'name' => 'currentRequestHostPlaceholder',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'__SANCTUM_CURRENT_REQUEST_HOST__\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 61,
            'startFilePos' => 907,
            'endTokenPos' => 61,
            'endFilePos' => 940,
          ),
        ),
        'docComment' => '/**
 * A placeholder to instruct Sanctum to include the current request host in the list of stateful domains.
 *
 * @var string;
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 86,
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
      'currentApplicationUrlWithPort' => 
      array (
        'name' => 'currentApplicationUrlWithPort',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application URL from the "APP_URL" environment variable - with port.
 *
 * @return string
 */',
        'startLine' => 45,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
        'aliasName' => NULL,
      ),
      'currentRequestHost' => 
      array (
        'name' => 'currentRequestHost',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a fixed token instructing Sanctum to include the current request host in the list of stateful domains.
 *
 * @return string
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
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
        'aliasName' => NULL,
      ),
      'actingAs' => 
      array (
        'name' => 'actingAs',
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
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'abilities' => 
          array (
            'name' => 'abilities',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 179,
                'startFilePos' => 1986,
                'endTokenPos' => 180,
                'endFilePos' => 1987,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 44,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'guard' => 
          array (
            'name' => 'guard',
            'default' => 
            array (
              'code' => '\'sanctum\'',
              'attributes' => 
              array (
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 187,
                'startFilePos' => 1999,
                'endTokenPos' => 187,
                'endFilePos' => 2007,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 61,
            'endColumn' => 78,
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
 * Set the current user for the application with the given abilities.
 *
 * @param  \\Illuminate\\Contracts\\Auth\\Authenticatable|\\Laravel\\Sanctum\\HasApiTokens  $user
 * @param  array  $abilities
 * @param  string  $guard
 * @return \\Illuminate\\Contracts\\Auth\\Authenticatable
 */',
        'startLine' => 70,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
        'aliasName' => NULL,
      ),
      'usePersonalAccessTokenModel' => 
      array (
        'name' => 'usePersonalAccessTokenModel',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 56,
            'endColumn' => 61,
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
 * Set the personal access token model name.
 *
 * @param  class-string<TToken>  $model
 * @return void
 */',
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
        'aliasName' => NULL,
      ),
      'getAccessTokenFromRequestUsing' => 
      array (
        'name' => 'getAccessTokenFromRequestUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
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
                      'name' => 'callable',
                      'isIdentifier' => true,
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 59,
            'endColumn' => 77,
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
 * Specify a callback that should be used to fetch the access token from the request.
 *
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
        'aliasName' => NULL,
      ),
      'authenticateAccessTokensUsing' => 
      array (
        'name' => 'authenticateAccessTokensUsing',
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 58,
            'endColumn' => 75,
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
 * Specify a callback that should be used to authenticate access tokens.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 123,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
        'aliasName' => NULL,
      ),
      'personalAccessTokenModel' => 
      array (
        'name' => 'personalAccessTokenModel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the token model class name.
 *
 * @return class-string<TToken>
 */',
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\Sanctum',
        'implementingClassName' => 'Laravel\\Sanctum\\Sanctum',
        'currentClassName' => 'Laravel\\Sanctum\\Sanctum',
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