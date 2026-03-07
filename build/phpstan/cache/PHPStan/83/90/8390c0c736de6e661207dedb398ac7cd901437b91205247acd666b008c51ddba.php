<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/sanctum/src/HasApiTokens.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Sanctum\HasApiTokens
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7400600b832dc377ac5f51d051a917775f6efc0d2176a1de7bd7826499ae6509-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Sanctum\\HasApiTokens',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/sanctum/src/HasApiTokens.php',
      ),
    ),
    'namespace' => 'Laravel\\Sanctum',
    'name' => 'Laravel\\Sanctum\\HasApiTokens',
    'shortName' => 'HasApiTokens',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TToken of \\Laravel\\Sanctum\\Contracts\\HasAbilities = \\Laravel\\Sanctum\\PersonalAccessToken
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 111,
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
      'accessToken' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'name' => 'accessToken',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The access token the user is using for the current request.
 *
 * @var TToken
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 27,
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
      'tokens' => 
      array (
        'name' => 'tokens',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the access tokens that belong to model.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphMany<TToken, $this>
 */',
        'startLine' => 25,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'aliasName' => NULL,
      ),
      'tokenCan' => 
      array (
        'name' => 'tokenCan',
        'parameters' => 
        array (
          'ability' => 
          array (
            'name' => 'ability',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
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
            'startColumn' => 30,
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
 * Determine if the current API token has a given scope.
 *
 * @param  string  $ability
 * @return bool
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
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'aliasName' => NULL,
      ),
      'tokenCant' => 
      array (
        'name' => 'tokenCant',
        'parameters' => 
        array (
          'ability' => 
          array (
            'name' => 'ability',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
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
            'startColumn' => 31,
            'endColumn' => 45,
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
 * Determine if the current API token does not have a given scope.
 *
 * @param  string  $ability
 * @return bool
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
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'aliasName' => NULL,
      ),
      'createToken' => 
      array (
        'name' => 'createToken',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'abilities' => 
          array (
            'name' => 'abilities',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 144,
                'startFilePos' => 1455,
                'endTokenPos' => 146,
                'endFilePos' => 1459,
              ),
            ),
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
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 47,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'expiresAt' => 
          array (
            'name' => 'expiresAt',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 156,
                'startFilePos' => 1494,
                'endTokenPos' => 156,
                'endFilePos' => 1497,
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
                      'name' => 'DateTimeInterface',
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
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 73,
            'endColumn' => 108,
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
 * Create a new personal access token for the user.
 *
 * @param  string  $name
 * @param  array  $abilities
 * @param  \\DateTimeInterface|null  $expiresAt
 * @return \\Laravel\\Sanctum\\NewAccessToken
 */',
        'startLine' => 60,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'aliasName' => NULL,
      ),
      'generateTokenString' => 
      array (
        'name' => 'generateTokenString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the token string.
 *
 * @return string
 */',
        'startLine' => 79,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'aliasName' => NULL,
      ),
      'currentAccessToken' => 
      array (
        'name' => 'currentAccessToken',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the access token currently associated with the user.
 *
 * @return TToken
 */',
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'aliasName' => NULL,
      ),
      'withAccessToken' => 
      array (
        'name' => 'withAccessToken',
        'parameters' => 
        array (
          'accessToken' => 
          array (
            'name' => 'accessToken',
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
            'startColumn' => 37,
            'endColumn' => 48,
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
 * Set the current access token for the user.
 *
 * @param  TToken  $accessToken
 * @return $this
 */',
        'startLine' => 105,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'implementingClassName' => 'Laravel\\Sanctum\\HasApiTokens',
        'currentClassName' => 'Laravel\\Sanctum\\HasApiTokens',
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