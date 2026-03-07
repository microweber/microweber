<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cache/Lock.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Cache\Lock
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8fc55247df449c7a7ae1b01a598baa1707ceb1f9ecf45c44f9a750170d09ceb2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Cache\\Lock',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cache/Lock.php',
      ),
    ),
    'namespace' => 'Illuminate\\Cache',
    'name' => 'Illuminate\\Cache\\Lock',
    'shortName' => 'Lock',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 183,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Cache\\Lock',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\InteractsWithTime',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'name' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'name' => 'name',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The name of the lock.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'seconds' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'name' => 'seconds',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The number of seconds the lock should be maintained.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'owner' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'name' => 'owner',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The scope identifier of this lock.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sleepMilliseconds' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'name' => 'sleepMilliseconds',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '250',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 82,
            'startFilePos' => 812,
            'endTokenPos' => 82,
            'endFilePos' => 814,
          ),
        ),
        'docComment' => '/**
 * The number of milliseconds to wait before re-attempting to acquire a lock while blocking.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
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
      '__construct' => 
      array (
        'name' => '__construct',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => NULL,
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
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'owner' => 
          array (
            'name' => 'owner',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 103,
                'startFilePos' => 1047,
                'endTokenPos' => 103,
                'endFilePos' => 1050,
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
            'startColumn' => 50,
            'endColumn' => 62,
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
 * Create a new lock instance.
 *
 * @param  string  $name
 * @param  int  $seconds
 * @param  string|null  $owner
 * @return void
 */',
        'startLine' => 51,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'acquire' => 
      array (
        'name' => 'acquire',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Attempt to acquire the lock.
 *
 * @return bool
 */',
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'release' => 
      array (
        'name' => 'release',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Release the lock.
 *
 * @return bool
 */',
        'startLine' => 74,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'getCurrentOwner' => 
      array (
        'name' => 'getCurrentOwner',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the owner value written into the driver for this lock.
 *
 * @return string
 */',
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 66,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 89,
                'endLine' => 89,
                'startTokenPos' => 212,
                'startFilePos' => 1791,
                'endTokenPos' => 212,
                'endFilePos' => 1794,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 25,
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
 * Attempt to acquire the lock.
 *
 * @param  callable|null  $callback
 * @return mixed
 */',
        'startLine' => 89,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'block' => 
      array (
        'name' => 'block',
        'parameters' => 
        array (
          'seconds' => 
          array (
            'name' => 'seconds',
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
            'startColumn' => 27,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 113,
                'endLine' => 113,
                'startTokenPos' => 293,
                'startFilePos' => 2357,
                'endTokenPos' => 293,
                'endFilePos' => 2360,
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
            'startColumn' => 37,
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
 * Attempt to acquire the lock for the given number of seconds.
 *
 * @param  int  $seconds
 * @param  callable|null  $callback
 * @return mixed
 *
 * @throws \\Illuminate\\Contracts\\Cache\\LockTimeoutException
 */',
        'startLine' => 113,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'owner' => 
      array (
        'name' => 'owner',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the current owner of the lock.
 *
 * @return string
 */',
        'startLine' => 145,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'isOwnedByCurrentProcess' => 
      array (
        'name' => 'isOwnedByCurrentProcess',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines whether this lock is allowed to release the lock in the driver.
 *
 * @return bool
 */',
        'startLine' => 155,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'isOwnedBy' => 
      array (
        'name' => 'isOwnedBy',
        'parameters' => 
        array (
          'owner' => 
          array (
            'name' => 'owner',
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
            'startColumn' => 31,
            'endColumn' => 36,
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
 * Determine whether this lock is owned by the given identifier.
 *
 * @param  string|null  $owner
 * @return bool
 */',
        'startLine' => 166,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
        'aliasName' => NULL,
      ),
      'betweenBlockedAttemptsSleepFor' => 
      array (
        'name' => 'betweenBlockedAttemptsSleepFor',
        'parameters' => 
        array (
          'milliseconds' => 
          array (
            'name' => 'milliseconds',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 52,
            'endColumn' => 64,
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
 * Specify the number of milliseconds to sleep in between blocked lock acquisition attempts.
 *
 * @param  int  $milliseconds
 * @return $this
 */',
        'startLine' => 177,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Cache\\Lock',
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