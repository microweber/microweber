<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Session/SessionManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Session\SessionManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-995e795c804f7858eb709d65e855eaa0b568dacae01206605a4a7192426b850c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Session\\SessionManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Session/SessionManager.php',
      ),
    ),
    'namespace' => 'Illuminate\\Session',
    'name' => 'Illuminate\\Session\\SessionManager',
    'shortName' => 'SessionManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @mixin \\Illuminate\\Session\\Store
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 289,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Manager',
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
      'callCustomCreator' => 
      array (
        'name' => 'callCustomCreator',
        'parameters' => 
        array (
          'driver' => 
          array (
            'name' => 'driver',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 42,
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
 * Call a custom driver creator.
 *
 * @param  string  $driver
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 18,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createNullDriver' => 
      array (
        'name' => 'createNullDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the "null" session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createArrayDriver' => 
      array (
        'name' => 'createArrayDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the "array" session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 38,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createCookieDriver' => 
      array (
        'name' => 'createCookieDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the "cookie" session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 50,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createFileDriver' => 
      array (
        'name' => 'createFileDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the file session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createNativeDriver' => 
      array (
        'name' => 'createNativeDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the file session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 74,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createDatabaseDriver' => 
      array (
        'name' => 'createDatabaseDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the database session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 88,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'getDatabaseConnection' => 
      array (
        'name' => 'getDatabaseConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the database connection for the database driver.
 *
 * @return \\Illuminate\\Database\\Connection
 */',
        'startLine' => 104,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createApcDriver' => 
      array (
        'name' => 'createApcDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the APC session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 116,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createMemcachedDriver' => 
      array (
        'name' => 'createMemcachedDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the Memcached session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 126,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createRedisDriver' => 
      array (
        'name' => 'createRedisDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the Redis session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 136,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createDynamodbDriver' => 
      array (
        'name' => 'createDynamodbDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the DynamoDB session driver.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 152,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createCacheBased' => 
      array (
        'name' => 'createCacheBased',
        'parameters' => 
        array (
          'driver' => 
          array (
            'name' => 'driver',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 41,
            'endColumn' => 47,
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
 * Create an instance of a cache driven driver.
 *
 * @param  string  $driver
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 163,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'createCacheHandler' => 
      array (
        'name' => 'createCacheHandler',
        'parameters' => 
        array (
          'driver' => 
          array (
            'name' => 'driver',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 174,
            'endLine' => 174,
            'startColumn' => 43,
            'endColumn' => 49,
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
 * Create the cache based session handler instance.
 *
 * @param  string  $driver
 * @return \\Illuminate\\Session\\CacheBasedSessionHandler
 */',
        'startLine' => 174,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'buildSession' => 
      array (
        'name' => 'buildSession',
        'parameters' => 
        array (
          'handler' => 
          array (
            'name' => 'handler',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 190,
            'endLine' => 190,
            'startColumn' => 37,
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
 * Build the session instance.
 *
 * @param  \\SessionHandlerInterface  $handler
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 190,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'buildEncryptedSession' => 
      array (
        'name' => 'buildEncryptedSession',
        'parameters' => 
        array (
          'handler' => 
          array (
            'name' => 'handler',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 208,
            'endLine' => 208,
            'startColumn' => 46,
            'endColumn' => 53,
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
 * Build the encrypted session instance.
 *
 * @param  \\SessionHandlerInterface  $handler
 * @return \\Illuminate\\Session\\EncryptedStore
 */',
        'startLine' => 208,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'shouldBlock' => 
      array (
        'name' => 'shouldBlock',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if requests for the same session should wait for each to finish before executing.
 *
 * @return bool
 */',
        'startLine' => 224,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'blockDriver' => 
      array (
        'name' => 'blockDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the cache store / driver that should be used to acquire session locks.
 *
 * @return string|null
 */',
        'startLine' => 234,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'defaultRouteBlockLockSeconds' => 
      array (
        'name' => 'defaultRouteBlockLockSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the maximum number of seconds the session lock should be held for.
 *
 * @return int
 */',
        'startLine' => 244,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'defaultRouteBlockWaitSeconds' => 
      array (
        'name' => 'defaultRouteBlockWaitSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the maximum number of seconds to wait while attempting to acquire a route block session lock.
 *
 * @return int
 */',
        'startLine' => 254,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'getSessionConfig' => 
      array (
        'name' => 'getSessionConfig',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the session configuration.
 *
 * @return array
 */',
        'startLine' => 264,
        'endLine' => 267,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'getDefaultDriver' => 
      array (
        'name' => 'getDefaultDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default session driver name.
 *
 * @return string
 */',
        'startLine' => 274,
        'endLine' => 277,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
        'aliasName' => NULL,
      ),
      'setDefaultDriver' => 
      array (
        'name' => 'setDefaultDriver',
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
            'startLine' => 285,
            'endLine' => 285,
            'startColumn' => 38,
            'endColumn' => 42,
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
 * Set the default session driver name.
 *
 * @param  string  $name
 * @return void
 */',
        'startLine' => 285,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Session',
        'declaringClassName' => 'Illuminate\\Session\\SessionManager',
        'implementingClassName' => 'Illuminate\\Session\\SessionManager',
        'currentClassName' => 'Illuminate\\Session\\SessionManager',
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