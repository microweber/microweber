<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Auth/Access/Response.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Auth\Access\Response
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-bec455ee08d2b7d28b9e7fed8dcadd19ea09dbb94a17054404ef49af93e25610-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Auth\\Access\\Response',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Auth/Access/Response.php',
      ),
    ),
    'namespace' => 'Illuminate\\Auth\\Access',
    'name' => 'Illuminate\\Auth\\Access\\Response',
    'shortName' => 'Response',
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
    'endLine' => 216,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\Arrayable',
      1 => 'Stringable',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'allowed' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'name' => 'allowed',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates whether the response was allowed.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'message' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'name' => 'message',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The response message.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'code' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'name' => 'code',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The response code.
 *
 * @var mixed
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'status' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
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
        'startLine' => 36,
        'endLine' => 36,
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
          'allowed' => 
          array (
            'name' => 'allowed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 33,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 73,
                'startFilePos' => 786,
                'endTokenPos' => 73,
                'endFilePos' => 787,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 43,
            'endColumn' => 55,
            'parameterIndex' => 1,
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
                'startLine' => 46,
                'endLine' => 46,
                'startTokenPos' => 80,
                'startFilePos' => 798,
                'endTokenPos' => 80,
                'endFilePos' => 801,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 58,
            'endColumn' => 69,
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
 * Create a new response.
 *
 * @param  bool  $allowed
 * @param  string|null  $message
 * @param  mixed  $code
 * @return void
 */',
        'startLine' => 46,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'allow' => 
      array (
        'name' => 'allow',
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
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 128,
                'startFilePos' => 1134,
                'endTokenPos' => 128,
                'endFilePos' => 1137,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 34,
            'endColumn' => 48,
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
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 135,
                'startFilePos' => 1148,
                'endTokenPos' => 135,
                'endFilePos' => 1151,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 51,
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
 * Create a new "allow" Response.
 *
 * @param  string|null  $message
 * @param  mixed  $code
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'deny' => 
      array (
        'name' => 'deny',
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
                'startLine' => 72,
                'endLine' => 72,
                'startTokenPos' => 172,
                'startFilePos' => 1433,
                'endTokenPos' => 172,
                'endFilePos' => 1436,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
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
                'startLine' => 72,
                'endLine' => 72,
                'startTokenPos' => 179,
                'startFilePos' => 1447,
                'endTokenPos' => 179,
                'endFilePos' => 1450,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
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
 * Create a new "deny" Response.
 *
 * @param  string|null  $message
 * @param  mixed  $code
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'denyWithStatus' => 
      array (
        'name' => 'denyWithStatus',
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
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 85,
                'endLine' => 85,
                'startTokenPos' => 219,
                'startFilePos' => 1804,
                'endTokenPos' => 219,
                'endFilePos' => 1807,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 52,
            'endColumn' => 66,
            'parameterIndex' => 1,
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
                'startLine' => 85,
                'endLine' => 85,
                'startTokenPos' => 226,
                'startFilePos' => 1818,
                'endTokenPos' => 226,
                'endFilePos' => 1821,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 69,
            'endColumn' => 80,
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
 * Create a new "deny" Response with a HTTP status code.
 *
 * @param  int  $status
 * @param  string|null  $message
 * @param  mixed  $code
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'denyAsNotFound' => 
      array (
        'name' => 'denyAsNotFound',
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
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 265,
                'startFilePos' => 2158,
                'endTokenPos' => 265,
                'endFilePos' => 2161,
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
            'startColumn' => 43,
            'endColumn' => 57,
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
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 272,
                'startFilePos' => 2172,
                'endTokenPos' => 272,
                'endFilePos' => 2175,
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
            'startColumn' => 60,
            'endColumn' => 71,
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
 * Create a new "deny" Response with a 404 HTTP status code.
 *
 * @param  string|null  $message
 * @param  mixed  $code
 * @return \\Illuminate\\Auth\\Access\\Response
 */',
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'allowed' => 
      array (
        'name' => 'allowed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the response was allowed.
 *
 * @return bool
 */',
        'startLine' => 107,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'denied' => 
      array (
        'name' => 'denied',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the response was denied.
 *
 * @return bool
 */',
        'startLine' => 117,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'message' => 
      array (
        'name' => 'message',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the response message.
 *
 * @return string|null
 */',
        'startLine' => 127,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'code' => 
      array (
        'name' => 'code',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the response code / reason.
 *
 * @return mixed
 */',
        'startLine' => 137,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'authorize' => 
      array (
        'name' => 'authorize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Throw authorization exception if response was denied.
 *
 * @return \\Illuminate\\Auth\\Access\\Response
 *
 * @throws \\Illuminate\\Auth\\Access\\AuthorizationException
 */',
        'startLine' => 149,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
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
            'startLine' => 166,
            'endLine' => 166,
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
 * @param  null|int  $status
 * @return $this
 */',
        'startLine' => 166,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
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
        'startLine' => 178,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
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
        'startLine' => 188,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert the response to an array.
 *
 * @return array
 */',
        'startLine' => 198,
        'endLine' => 205,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the string representation of the message.
 *
 * @return string
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
        'namespace' => 'Illuminate\\Auth\\Access',
        'declaringClassName' => 'Illuminate\\Auth\\Access\\Response',
        'implementingClassName' => 'Illuminate\\Auth\\Access\\Response',
        'currentClassName' => 'Illuminate\\Auth\\Access\\Response',
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