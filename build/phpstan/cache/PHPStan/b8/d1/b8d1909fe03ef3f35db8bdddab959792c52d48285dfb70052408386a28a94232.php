<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Cache/Lock.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Cache\Lock
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ceb400855a8e2884b320ff31c0976fea4de45e8dffa2a8190e504f942829f207-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Cache\\Lock',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Cache/Lock.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Cache',
    'name' => 'Illuminate\\Contracts\\Cache\\Lock',
    'shortName' => 'Lock',
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
    'endLine' => 46,
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
                'startLine' => 13,
                'endLine' => 13,
                'startTokenPos' => 25,
                'startFilePos' => 219,
                'endTokenPos' => 25,
                'endFilePos' => 222,
              ),
            ),
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
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Cache',
        'declaringClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
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
            'startLine' => 24,
            'endLine' => 24,
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
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 44,
                'startFilePos' => 527,
                'endTokenPos' => 44,
                'endFilePos' => 530,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
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
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Cache',
        'declaringClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
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
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Cache',
        'declaringClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
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
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Cache',
        'declaringClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
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
 * Releases this lock in disregard of ownership.
 *
 * @return void
 */',
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Cache',
        'declaringClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'implementingClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
        'currentClassName' => 'Illuminate\\Contracts\\Cache\\Lock',
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