<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Auth/Access/Gate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Auth\Access\Gate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-00d270efeeb91cdb09d9fed32150fcd40ccb1dd24af6f4f41950a1e0ba52683e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Auth/Access/Gate.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
    'name' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
    'shortName' => 'Gate',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 150,
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
      'has' => 
      array (
        'name' => 'has',
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
            'startLine' => 13,
            'endLine' => 13,
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
 * Determine if a given ability has been defined.
 *
 * @param  string  $ability
 * @return bool
 */',
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'define' => 
      array (
        'name' => 'define',
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
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 38,
            'endColumn' => 46,
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
 * Define a new ability.
 *
 * @param  string  $ability
 * @param  callable|string  $callback
 * @return $this
 */',
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'resource' => 
      array (
        'name' => 'resource',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 30,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'abilities' => 
          array (
            'name' => 'abilities',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 61,
                'startFilePos' => 676,
                'endTokenPos' => 61,
                'endFilePos' => 679,
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
                      'name' => 'array',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 45,
            'endColumn' => 68,
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
 * Define abilities for a resource.
 *
 * @param  string  $name
 * @param  string  $class
 * @param  array|null  $abilities
 * @return $this
 */',
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 70,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'policy' => 
      array (
        'name' => 'policy',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 28,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'policy' => 
          array (
            'name' => 'policy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 36,
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
 * Define a policy class for a given class type.
 *
 * @param  string  $class
 * @param  string  $policy
 * @return $this
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'before' => 
      array (
        'name' => 'before',
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
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 28,
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
 * Register a callback to run before all Gate checks.
 *
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'after' => 
      array (
        'name' => 'after',
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
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 27,
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
 * Register a callback to run after all Gate checks.
 *
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'allows' => 
      array (
        'name' => 'allows',
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
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 123,
                'startFilePos' => 1518,
                'endTokenPos' => 124,
                'endFilePos' => 1519,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 38,
            'endColumn' => 52,
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
 * Determine if all of the given abilities should be granted for the current user.
 *
 * @param  iterable|string  $ability
 * @param  array|mixed  $arguments
 * @return bool
 */',
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'denies' => 
      array (
        'name' => 'denies',
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
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 75,
                'endLine' => 75,
                'startTokenPos' => 143,
                'startFilePos' => 1783,
                'endTokenPos' => 144,
                'endFilePos' => 1784,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 38,
            'endColumn' => 52,
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
 * Determine if any of the given abilities should be denied for the current user.
 *
 * @param  iterable|string  $ability
 * @param  array|mixed  $arguments
 * @return bool
 */',
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'check' => 
      array (
        'name' => 'check',
        'parameters' => 
        array (
          'abilities' => 
          array (
            'name' => 'abilities',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 27,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 163,
                'startFilePos' => 2052,
                'endTokenPos' => 164,
                'endFilePos' => 2053,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 39,
            'endColumn' => 53,
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
 * Determine if all of the given abilities should be granted for the current user.
 *
 * @param  iterable|string  $abilities
 * @param  array|mixed  $arguments
 * @return bool
 */',
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'any' => 
      array (
        'name' => 'any',
        'parameters' => 
        array (
          'abilities' => 
          array (
            'name' => 'abilities',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 25,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 183,
                'startFilePos' => 2323,
                'endTokenPos' => 184,
                'endFilePos' => 2324,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 37,
            'endColumn' => 51,
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
 * Determine if any one of the given abilities should be granted for the current user.
 *
 * @param  iterable|string  $abilities
 * @param  array|mixed  $arguments
 * @return bool
 */',
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'authorize' => 
      array (
        'name' => 'authorize',
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
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 104,
                'endLine' => 104,
                'startTokenPos' => 203,
                'startFilePos' => 2671,
                'endTokenPos' => 204,
                'endFilePos' => 2672,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 41,
            'endColumn' => 55,
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
 * Determine if the given ability should be granted for the current user.
 *
 * @param  string  $ability
 * @param  array|mixed  $arguments
 * @return \\Illuminate\\Auth\\Access\\Response
 *
 * @throws \\Illuminate\\Auth\\Access\\AuthorizationException
 */',
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'inspect' => 
      array (
        'name' => 'inspect',
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
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 113,
                'endLine' => 113,
                'startTokenPos' => 223,
                'startFilePos' => 2917,
                'endTokenPos' => 224,
                'endFilePos' => 2918,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 39,
            'endColumn' => 53,
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
 * Inspect the user for the given ability.
 *
 * @param  string  $ability
 * @param  array|mixed  $arguments
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'raw' => 
      array (
        'name' => 'raw',
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
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 25,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 124,
                'endLine' => 124,
                'startTokenPos' => 243,
                'startFilePos' => 3213,
                'endTokenPos' => 244,
                'endFilePos' => 3214,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 35,
            'endColumn' => 49,
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
 * Get the raw result from the authorization callback.
 *
 * @param  string  $ability
 * @param  array|mixed  $arguments
 * @return mixed
 *
 * @throws \\Illuminate\\Auth\\Access\\AuthorizationException
 */',
        'startLine' => 124,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'getPolicyFor' => 
      array (
        'name' => 'getPolicyFor',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 134,
            'endLine' => 134,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Get a policy instance for a given class.
 *
 * @param  object|string  $class
 * @return mixed
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 134,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'forUser' => 
      array (
        'name' => 'forUser',
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
            'startLine' => 142,
            'endLine' => 142,
            'startColumn' => 29,
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
 * Get a guard instance for the given user.
 *
 * @param  \\Illuminate\\Contracts\\Auth\\Authenticatable|mixed  $user
 * @return static
 */',
        'startLine' => 142,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'aliasName' => NULL,
      ),
      'abilities' => 
      array (
        'name' => 'abilities',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the defined abilities.
 *
 * @return array
 */',
        'startLine' => 149,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\Access\\Gate',
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