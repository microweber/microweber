<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Auth/Access/AuthorizationException.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Auth\Access\AuthorizationException
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0a8bec85a4c37d2d03f6c750a8919f1c1f75e00808b437a2bd12be71e5153c4c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Auth/Access/AuthorizationException.php',
      ),
    ),
    'namespace' => 'Illuminate\\Auth\\Access',
    'name' => 'Illuminate\\Auth\\Access\\AuthorizationException',
    'shortName' => 'AuthorizationException',
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
    'endLine' => 114,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Exception',
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
      'response' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'name' => 'response',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The response from the gate.
 *
 * @var \\Illuminate\\Auth\\Access\\Response
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'status' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'name' => 'status',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The HTTP response status code.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 22,
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
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 53,
                'startFilePos' => 604,
                'endTokenPos' => 53,
                'endFilePos' => 607,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 33,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'code' => 
          array (
            'name' => 'code',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 60,
                'startFilePos' => 618,
                'endTokenPos' => 60,
                'endFilePos' => 621,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 50,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'previous' => 
          array (
            'name' => 'previous',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 70,
                'startFilePos' => 647,
                'endTokenPos' => 70,
                'endFilePos' => 650,
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
                      'name' => 'Throwable',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 64,
            'endColumn' => 90,
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
 * Create a new authorization exception instance.
 *
 * @param  string|null  $message
 * @param  mixed  $code
 * @param  \\Throwable|null  $previous
 * @return void
 */',
        'startLine' => 32,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'response' => 
      array (
        'name' => 'response',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the response from the gate.
 *
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'setResponse' => 
      array (
        'name' => 'setResponse',
        'parameters' => 
        array (
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 33,
            'endColumn' => 41,
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
 * Set the response from the gate.
 *
 * @param  \\Illuminate\\Auth\\Access\\Response  $response
 * @return $this
 */',
        'startLine' => 55,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'withStatus' => 
      array (
        'name' => 'withStatus',
        'parameters' => 
        array (
          'status' => 
          array (
            'name' => 'status',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
            'startColumn' => 32,
            'endColumn' => 38,
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
 * Set the HTTP response status code.
 *
 * @param  int|null  $status
 * @return $this
 */',
        'startLine' => 68,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'asNotFound' => 
      array (
        'name' => 'asNotFound',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the HTTP response status code to 404.
 *
 * @return $this
 */',
        'startLine' => 80,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'hasStatus' => 
      array (
        'name' => 'hasStatus',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the HTTP status code has been set.
 *
 * @return bool
 */',
        'startLine' => 90,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'status' => 
      array (
        'name' => 'status',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the HTTP status code.
 *
 * @return int|null
 */',
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'aliasName' => NULL,
      ),
      'toResponse' => 
      array (
        'name' => 'toResponse',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a deny response object from this exception.
 *
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 110,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
        'currentClassName' => 'Illuminate\\Auth\\Access\\AuthorizationException',
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