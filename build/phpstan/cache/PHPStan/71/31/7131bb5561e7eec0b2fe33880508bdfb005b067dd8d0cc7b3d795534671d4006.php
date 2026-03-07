<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/sanctum/src/PersonalAccessToken.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Sanctum\PersonalAccessToken
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5f4e3cf12f9b900c207fc113967a558b2c1c602f6da7f930c2a3ca991730afae-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/sanctum/src/PersonalAccessToken.php',
      ),
    ),
    'namespace' => 'Laravel\\Sanctum',
    'name' => 'Laravel\\Sanctum\\PersonalAccessToken',
    'shortName' => 'PersonalAccessToken',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 93,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'Laravel\\Sanctum\\Contracts\\HasAbilities',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'casts' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'abilities\' => \'json\', \'last_used_at\' => \'datetime\', \'expires_at\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 19,
            'startTokenPos' => 39,
            'startFilePos' => 325,
            'endTokenPos' => 62,
            'endFilePos' => 436,
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
        'startLine' => 15,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'token\', \'abilities\', \'expires_at\']',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 31,
            'startTokenPos' => 73,
            'startFilePos' => 568,
            'endTokenPos' => 87,
            'endFilePos' => 650,
          ),
        ),
        'docComment' => '/**
 * The attributes that are mass assignable.
 *
 * @var array<int, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hidden' => 
      array (
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'token\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 40,
            'startTokenPos' => 98,
            'startFilePos' => 795,
            'endTokenPos' => 103,
            'endFilePos' => 818,
          ),
        ),
        'docComment' => '/**
 * The attributes that should be hidden for serialization.
 *
 * @var array<int, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'tokenable' => 
      array (
        'name' => 'tokenable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the tokenable model that the access token belongs to.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo
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
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'currentClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'aliasName' => NULL,
      ),
      'findToken' => 
      array (
        'name' => 'findToken',
        'parameters' => 
        array (
          'token' => 
          array (
            'name' => 'token',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 38,
            'endColumn' => 43,
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
 * Find the token instance matching the given token.
 *
 * @param  string  $token
 * @return static|null
 */',
        'startLine' => 58,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'currentClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'aliasName' => NULL,
      ),
      'can' => 
      array (
        'name' => 'can',
        'parameters' => 
        array (
          'ability' => 
          array (
            'name' => 'ability',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 25,
            'endColumn' => 32,
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
 * Determine if the token has a given ability.
 *
 * @param  string  $ability
 * @return bool
 */',
        'startLine' => 77,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'currentClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'aliasName' => NULL,
      ),
      'cant' => 
      array (
        'name' => 'cant',
        'parameters' => 
        array (
          'ability' => 
          array (
            'name' => 'ability',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 26,
            'endColumn' => 33,
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
 * Determine if the token is missing a given ability.
 *
 * @param  string  $ability
 * @return bool
 */',
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Sanctum',
        'declaringClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'implementingClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
        'currentClassName' => 'Laravel\\Sanctum\\PersonalAccessToken',
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