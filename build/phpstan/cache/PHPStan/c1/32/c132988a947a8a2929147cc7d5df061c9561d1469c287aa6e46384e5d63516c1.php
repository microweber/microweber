<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cache/CacheLock.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Cache\CacheLock
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e3d856a1eb59b410fa5f0fc944ec88a5f35fc2687f39d56218f24789ecc449b9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Cache\\CacheLock',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cache/CacheLock.php',
      ),
    ),
    'namespace' => 'Illuminate\\Cache',
    'name' => 'Illuminate\\Cache\\CacheLock',
    'shortName' => 'CacheLock',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 85,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Cache\\Lock',
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
      'store' => 
      array (
        'declaringClassName' => 'Illuminate\\Cache\\CacheLock',
        'implementingClassName' => 'Illuminate\\Cache\\CacheLock',
        'name' => 'store',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The cache store implementation.
 *
 * @var \\Illuminate\\Contracts\\Cache\\Store
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 21,
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
          'store' => 
          array (
            'name' => 'store',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 1,
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
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 48,
            'endColumn' => 55,
            'parameterIndex' => 2,
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
                'startLine' => 23,
                'endLine' => 23,
                'startTokenPos' => 45,
                'startFilePos' => 492,
                'endTokenPos' => 45,
                'endFilePos' => 495,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 58,
            'endColumn' => 70,
            'parameterIndex' => 3,
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
 * @param  \\Illuminate\\Contracts\\Cache\\Store  $store
 * @param  string  $name
 * @param  int  $seconds
 * @param  string|null  $owner
 * @return void
 */',
        'startLine' => 23,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\CacheLock',
        'implementingClassName' => 'Illuminate\\Cache\\CacheLock',
        'currentClassName' => 'Illuminate\\Cache\\CacheLock',
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
        'startLine' => 35,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\CacheLock',
        'implementingClassName' => 'Illuminate\\Cache\\CacheLock',
        'currentClassName' => 'Illuminate\\Cache\\CacheLock',
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
        'startLine' => 57,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\CacheLock',
        'implementingClassName' => 'Illuminate\\Cache\\CacheLock',
        'currentClassName' => 'Illuminate\\Cache\\CacheLock',
        'aliasName' => NULL,
      ),
      'forceRelease' => 
      array (
        'name' => 'forceRelease',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Releases this lock regardless of ownership.
 *
 * @return void
 */',
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\CacheLock',
        'implementingClassName' => 'Illuminate\\Cache\\CacheLock',
        'currentClassName' => 'Illuminate\\Cache\\CacheLock',
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
 * @return mixed
 */',
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Cache',
        'declaringClassName' => 'Illuminate\\Cache\\CacheLock',
        'implementingClassName' => 'Illuminate\\Cache\\CacheLock',
        'currentClassName' => 'Illuminate\\Cache\\CacheLock',
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