<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/passport/src/Client.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Passport\Client
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2f92dcee17ea279cd968d116dd34b4f4f665e70558752a00c6ee335afbe1a2d6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Passport\\Client',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/passport/src/Client.php',
      ),
    ),
    'namespace' => 'Laravel\\Passport',
    'name' => 'Laravel\\Passport\\Client',
    'shortName' => 'Client',
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
    'endLine' => 242,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'Laravel\\Passport\\ResolvesInheritedScopes',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'oauth_clients\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 92,
            'startFilePos' => 876,
            'endTokenPos' => 92,
            'endFilePos' => 890,
          ),
        ),
        'docComment' => '/**
 * The database table used by the model.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'guarded' => 
      array (
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'name' => 'guarded',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 103,
            'startFilePos' => 1017,
            'endTokenPos' => 103,
            'endFilePos' => 1021,
          ),
        ),
        'docComment' => '/**
 * The guarded attributes on the model.
 *
 * @var array<string>|bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hidden' => 
      array (
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'secret\']',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 44,
            'startTokenPos' => 114,
            'startFilePos' => 1157,
            'endTokenPos' => 119,
            'endFilePos' => 1181,
          ),
        ),
        'docComment' => '/**
 * The attributes excluded from the model\'s JSON form.
 *
 * @var array<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'grant_types\' => \'array\', \'scopes\' => \'array\', \'redirect_uris\' => \'array\', \'personal_access_client\' => \'bool\', \'password_client\' => \'bool\', \'revoked\' => \'bool\']',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 58,
            'startTokenPos' => 130,
            'startFilePos' => 1324,
            'endTokenPos' => 174,
            'endFilePos' => 1539,
          ),
        ),
        'docComment' => '/**
 * The attributes that should be cast to native types.
 *
 * @var array<string, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'plainSecret' => 
      array (
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'name' => 'plainSecret',
        'modifiers' => 1,
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
                  'name' => 'string',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 188,
            'startFilePos' => 1721,
            'endTokenPos' => 188,
            'endFilePos' => 1724,
          ),
        ),
        'docComment' => '/**
 * The temporary plain-text client secret.
 *
 * This is only available during the request that created the client.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 39,
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
      'initializeHasUniqueStringIds' => 
      array (
        'name' => 'initializeHasUniqueStringIds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initialize the trait.
 */',
        'startLine' => 70,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'user' => 
      array (
        'name' => 'user',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the user that the client belongs to.
 *
 * @deprecated Use owner()
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<\\Illuminate\\Foundation\\Auth\\User, $this>
 */',
        'startLine' => 82,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'owner' => 
      array (
        'name' => 'owner',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the owner of the registered client.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<\\Illuminate\\Foundation\\Auth\\User, $this>
 */',
        'startLine' => 96,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'authCodes' => 
      array (
        'name' => 'authCodes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the authentication codes for the client.
 *
 * @deprecated Will be removed in a future Laravel version.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasMany<\\Laravel\\Passport\\AuthCode, $this>
 */',
        'startLine' => 108,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'tokens' => 
      array (
        'name' => 'tokens',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the tokens that belong to the client.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasMany<\\Laravel\\Passport\\Token, $this>
 */',
        'startLine' => 118,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'secret' => 
      array (
        'name' => 'secret',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Casts\\Attribute',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Interact with the client\'s secret.
 */',
        'startLine' => 126,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'plainSecret' => 
      array (
        'name' => 'plainSecret',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Casts\\Attribute',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Interact with the client\'s plain secret.
 */',
        'startLine' => 140,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'redirectUris' => 
      array (
        'name' => 'redirectUris',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Casts\\Attribute',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Interact with the client\'s redirect URIs.
 */',
        'startLine' => 150,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'grantTypes' => 
      array (
        'name' => 'grantTypes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Casts\\Attribute',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Interact with the client\'s grant types.
 */',
        'startLine' => 164,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'firstParty' => 
      array (
        'name' => 'firstParty',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the client is a "first party" client.
 */',
        'startLine' => 182,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'skipsAuthorization' => 
      array (
        'name' => 'skipsAuthorization',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Auth\\Authenticatable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 40,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scopes' => 
          array (
            'name' => 'scopes',
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
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 63,
            'endColumn' => 75,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the client should skip the authorization prompt.
 *
 * @param  \\Laravel\\Passport\\Scope[]  $scopes
 */',
        'startLine' => 196,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'hasGrantType' => 
      array (
        'name' => 'hasGrantType',
        'parameters' => 
        array (
          'grantType' => 
          array (
            'name' => 'grantType',
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
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 34,
            'endColumn' => 50,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the client has the given grant type.
 */',
        'startLine' => 204,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'hasScope' => 
      array (
        'name' => 'hasScope',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
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
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 30,
            'endColumn' => 42,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine whether the client has the given scope.
 */',
        'startLine' => 212,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'confidential' => 
      array (
        'name' => 'confidential',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the client is a confidential client.
 */',
        'startLine' => 220,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'getConnectionName' => 
      array (
        'name' => 'getConnectionName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'string',
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current connection name for the model.
 */',
        'startLine' => 228,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new factory instance for the model.
 *
 * @return \\Laravel\\Passport\\Database\\Factories\\ClientFactory
 */',
        'startLine' => 238,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Laravel\\Passport',
        'declaringClassName' => 'Laravel\\Passport\\Client',
        'implementingClassName' => 'Laravel\\Passport\\Client',
        'currentClassName' => 'Laravel\\Passport\\Client',
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