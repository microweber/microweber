<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Hashing/ArgonHasher.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Hashing\ArgonHasher
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-744950cbd3cec619147c5d410e2b0086c2eaa809d620c5d2e8752763b7913f99-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Hashing\\ArgonHasher',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Hashing/ArgonHasher.php',
      ),
    ),
    'namespace' => 'Illuminate\\Hashing',
    'name' => 'Illuminate\\Hashing\\ArgonHasher',
    'shortName' => 'ArgonHasher',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 251,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Hashing\\AbstractHasher',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Hashing\\Hasher',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'memory' => 
      array (
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'name' => 'memory',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '1024',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 48,
            'startFilePos' => 302,
            'endTokenPos' => 48,
            'endFilePos' => 305,
          ),
        ),
        'docComment' => '/**
 * The default memory cost factor.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'time' => 
      array (
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'name' => 'time',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 59,
            'startFilePos' => 407,
            'endTokenPos' => 59,
            'endFilePos' => 407,
          ),
        ),
        'docComment' => '/**
 * The default time cost factor.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'threads' => 
      array (
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'name' => 'threads',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 70,
            'startFilePos' => 510,
            'endTokenPos' => 70,
            'endFilePos' => 510,
          ),
        ),
        'docComment' => '/**
 * The default threads factor.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'verifyAlgorithm' => 
      array (
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'name' => 'verifyAlgorithm',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 81,
            'startFilePos' => 643,
            'endTokenPos' => 81,
            'endFilePos' => 647,
          ),
        ),
        'docComment' => '/**
 * Indicates whether to perform an algorithm check.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
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
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 45,
                'endLine' => 45,
                'startTokenPos' => 98,
                'startFilePos' => 811,
                'endTokenPos' => 99,
                'endFilePos' => 812,
              ),
            ),
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
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 33,
            'endColumn' => 51,
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
 * Create a new hasher instance.
 *
 * @param  array  $options
 * @return void
 */',
        'startLine' => 45,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 62,
            'endLine' => 62,
            'startColumn' => 26,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 62,
                'endLine' => 62,
                'startTokenPos' => 195,
                'startFilePos' => 1322,
                'endTokenPos' => 196,
                'endFilePos' => 1323,
              ),
            ),
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
            'startLine' => 62,
            'endLine' => 62,
            'startColumn' => 57,
            'endColumn' => 75,
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
 * Hash the given value.
 *
 * @param  string  $value
 * @param  array  $options
 * @return string
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 62,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'algorithm' => 
      array (
        'name' => 'algorithm',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the algorithm that should be used for hashing.
 *
 * @return int
 */',
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'check' => 
      array (
        'name' => 'check',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 27,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'hashedValue' => 
          array (
            'name' => 'hashedValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 58,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 335,
                'startFilePos' => 2232,
                'endTokenPos' => 336,
                'endFilePos' => 2233,
              ),
            ),
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 72,
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
 * Check the given plain value against a hash.
 *
 * @param  string  $value
 * @param  string  $hashedValue
 * @param  array  $options
 * @return bool
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 97,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'needsRehash' => 
      array (
        'name' => 'needsRehash',
        'parameters' => 
        array (
          'hashedValue' => 
          array (
            'name' => 'hashedValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 117,
                'endLine' => 117,
                'startTokenPos' => 438,
                'startFilePos' => 2852,
                'endTokenPos' => 439,
                'endFilePos' => 2853,
              ),
            ),
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
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 47,
            'endColumn' => 65,
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
 * Check if the given hash has been hashed using the given options.
 *
 * @param  string  $hashedValue
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 117,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'verifyConfiguration' => 
      array (
        'name' => 'verifyConfiguration',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 41,
            'endColumn' => 46,
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
 * Verifies that the configuration is less than or equal to what is configured.
 *
 * @internal
 */',
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'isUsingCorrectAlgorithm' => 
      array (
        'name' => 'isUsingCorrectAlgorithm',
        'parameters' => 
        array (
          'hashedValue' => 
          array (
            'name' => 'hashedValue',
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
            'startColumn' => 48,
            'endColumn' => 59,
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
 * Verify the hashed value\'s algorithm.
 *
 * @param  string  $hashedValue
 * @return bool
 */',
        'startLine' => 142,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'isUsingValidOptions' => 
      array (
        'name' => 'isUsingValidOptions',
        'parameters' => 
        array (
          'hashedValue' => 
          array (
            'name' => 'hashedValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 44,
            'endColumn' => 55,
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
 * Verify the hashed value\'s options.
 *
 * @param  string  $hashedValue
 * @return bool
 */',
        'startLine' => 153,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'setMemory' => 
      array (
        'name' => 'setMemory',
        'parameters' => 
        array (
          'memory' => 
          array (
            'name' => 'memory',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 182,
            'endLine' => 182,
            'startColumn' => 31,
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
 * Set the default password memory factor.
 *
 * @param  int  $memory
 * @return $this
 */',
        'startLine' => 182,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'setTime' => 
      array (
        'name' => 'setTime',
        'parameters' => 
        array (
          'time' => 
          array (
            'name' => 'time',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 29,
            'endColumn' => 37,
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
 * Set the default password timing factor.
 *
 * @param  int  $time
 * @return $this
 */',
        'startLine' => 195,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'setThreads' => 
      array (
        'name' => 'setThreads',
        'parameters' => 
        array (
          'threads' => 
          array (
            'name' => 'threads',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 208,
            'endLine' => 208,
            'startColumn' => 32,
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
 * Set the default password threads factor.
 *
 * @param  int  $threads
 * @return $this
 */',
        'startLine' => 208,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'memory' => 
      array (
        'name' => 'memory',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 221,
            'endLine' => 221,
            'startColumn' => 31,
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
 * Extract the memory cost value from the options array.
 *
 * @param  array  $options
 * @return int
 */',
        'startLine' => 221,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'time' => 
      array (
        'name' => 'time',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 232,
            'endLine' => 232,
            'startColumn' => 29,
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
 * Extract the time cost value from the options array.
 *
 * @param  array  $options
 * @return int
 */',
        'startLine' => 232,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'aliasName' => NULL,
      ),
      'threads' => 
      array (
        'name' => 'threads',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 243,
            'endLine' => 243,
            'startColumn' => 32,
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
 * Extract the thread\'s value from the options array.
 *
 * @param  array  $options
 * @return int
 */',
        'startLine' => 243,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Hashing',
        'declaringClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'implementingClassName' => 'Illuminate\\Hashing\\ArgonHasher',
        'currentClassName' => 'Illuminate\\Hashing\\ArgonHasher',
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