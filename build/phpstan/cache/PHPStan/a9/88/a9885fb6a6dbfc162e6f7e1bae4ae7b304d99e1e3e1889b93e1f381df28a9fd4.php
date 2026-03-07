<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/laravel-permission/src/Traits/HasRoles.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\Permission\Traits\HasRoles
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2a592993c44a108bcce617ba904f42128ceebbddf464e87b2d40f6af986fce0f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\Permission\\Traits\\HasRoles',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/laravel-permission/src/Traits/HasRoles.php',
      ),
    ),
    'namespace' => 'Spatie\\Permission\\Traits',
    'name' => 'Spatie\\Permission\\Traits\\HasRoles',
    'shortName' => 'HasRoles',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 446,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Spatie\\Permission\\Traits\\HasPermissions',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'roleClass' => 
      array (
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'name' => 'roleClass',
        'modifiers' => 4,
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
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 72,
            'startFilePos' => 494,
            'endTokenPos' => 72,
            'endFilePos' => 497,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 38,
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
      'bootHasRoles' => 
      array (
        'name' => 'bootHasRoles',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'getRoleClass' => 
      array (
        'name' => 'getRoleClass',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'roles' => 
      array (
        'name' => 'roles',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A model may have multiple roles.
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
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'scopeRole' => 
      array (
        'name' => 'scopeRole',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 31,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 47,
            'endColumn' => 52,
            'parameterIndex' => 1,
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
                'startLine' => 79,
                'endLine' => 79,
                'startTokenPos' => 453,
                'startFilePos' => 2416,
                'endTokenPos' => 453,
                'endFilePos' => 2419,
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
            'startColumn' => 55,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'without' => 
          array (
            'name' => 'without',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 79,
                'endLine' => 79,
                'startTokenPos' => 460,
                'startFilePos' => 2433,
                'endTokenPos' => 460,
                'endFilePos' => 2437,
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
            'startColumn' => 70,
            'endColumn' => 85,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Scope the model query to certain roles only.
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  $roles
 * @param  string  $guard
 * @param  bool  $without
 */',
        'startLine' => 79,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'scopeWithoutRole' => 
      array (
        'name' => 'scopeWithoutRole',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
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
            'startColumn' => 38,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 54,
            'endColumn' => 59,
            'parameterIndex' => 1,
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
                'startLine' => 112,
                'endLine' => 112,
                'startTokenPos' => 726,
                'startFilePos' => 3578,
                'endTokenPos' => 726,
                'endFilePos' => 3581,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 62,
            'endColumn' => 74,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Scope the model query to only those without certain roles.
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  $roles
 * @param  string  $guard
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'collectRoles' => 
      array (
        'name' => 'collectRoles',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 122,
            'endLine' => 122,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns array of role ids
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  $roles
 */',
        'startLine' => 122,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 4,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'assignRole' => 
      array (
        'name' => 'assignRole',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 32,
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
 * Assign the given role to the model.
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  ...$roles
 * @return $this
 */',
        'startLine' => 148,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'removeRole' => 
      array (
        'name' => 'removeRole',
        'parameters' => 
        array (
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 199,
            'endLine' => 199,
            'startColumn' => 32,
            'endColumn' => 39,
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
 * Revoke the given role from the model.
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  ...$role
 * @return $this
 */',
        'startLine' => 199,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'syncRoles' => 
      array (
        'name' => 'syncRoles',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 31,
            'endColumn' => 39,
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
 * Remove all current roles and set the given ones.
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  ...$roles
 * @return $this
 */',
        'startLine' => 224,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'hasRole' => 
      array (
        'name' => 'hasRole',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 29,
            'endColumn' => 34,
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
                'startLine' => 247,
                'endLine' => 247,
                'startTokenPos' => 1509,
                'startFilePos' => 7554,
                'endTokenPos' => 1509,
                'endFilePos' => 7557,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 37,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Determine if the model has (one of) the given role(s).
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  $roles
 */',
        'startLine' => 247,
        'endLine' => 304,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'hasAnyRole' => 
      array (
        'name' => 'hasAnyRole',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 313,
            'endLine' => 313,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
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
 * Determine if the model has any of the given role(s).
 *
 * Alias to hasRole() but without Guard controls
 *
 * @param  string|int|array|Role|Collection|\\BackedEnum  $roles
 */',
        'startLine' => 313,
        'endLine' => 316,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'hasAllRoles' => 
      array (
        'name' => 'hasAllRoles',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 323,
            'endLine' => 323,
            'startColumn' => 33,
            'endColumn' => 38,
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
                'startLine' => 323,
                'endLine' => 323,
                'startTokenPos' => 2020,
                'startFilePos' => 9956,
                'endTokenPos' => 2020,
                'endFilePos' => 9959,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 323,
            'endLine' => 323,
            'startColumn' => 41,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Determine if the model has all of the given role(s).
 *
 * @param  string|array|Role|Collection|\\BackedEnum  $roles
 */',
        'startLine' => 323,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'hasExactRoles' => 
      array (
        'name' => 'hasExactRoles',
        'parameters' => 
        array (
          'roles' => 
          array (
            'name' => 'roles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 35,
            'endColumn' => 40,
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
                'startLine' => 371,
                'endLine' => 371,
                'startTokenPos' => 2342,
                'startFilePos' => 11337,
                'endTokenPos' => 2342,
                'endFilePos' => 11340,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 43,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
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
 * Determine if the model has exactly all of the given role(s).
 *
 * @param  string|array|Role|Collection|\\BackedEnum  $roles
 */',
        'startLine' => 371,
        'endLine' => 391,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'getDirectPermissions' => 
      array (
        'name' => 'getDirectPermissions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return all permissions directly coupled to the model.
 */',
        'startLine' => 396,
        'endLine' => 399,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'getRoleNames' => 
      array (
        'name' => 'getRoleNames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 401,
        'endLine' => 406,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'getStoredRole' => 
      array (
        'name' => 'getStoredRole',
        'parameters' => 
        array (
          'role' => 
          array (
            'name' => 'role',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 408,
            'endLine' => 408,
            'startColumn' => 38,
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
            'name' => 'Spatie\\Permission\\Contracts\\Role',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 408,
        'endLine' => 423,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'aliasName' => NULL,
      ),
      'convertPipeToArray' => 
      array (
        'name' => 'convertPipeToArray',
        'parameters' => 
        array (
          'pipeString' => 
          array (
            'name' => 'pipeString',
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
            'startLine' => 425,
            'endLine' => 425,
            'startColumn' => 43,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 425,
        'endLine' => 445,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Spatie\\Permission\\Traits',
        'declaringClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'implementingClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
        'currentClassName' => 'Spatie\\Permission\\Traits\\HasRoles',
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