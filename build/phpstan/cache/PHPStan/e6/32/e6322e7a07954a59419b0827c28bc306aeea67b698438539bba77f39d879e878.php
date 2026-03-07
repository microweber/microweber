<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Queue/InteractsWithQueue.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Queue\InteractsWithQueue
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5164fb3810965d6950d48e24220ba9478f291a085c9dcc4f586e3abb9c6c2a8d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Queue\\InteractsWithQueue',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Queue/InteractsWithQueue.php',
      ),
    ),
    'namespace' => 'Illuminate\\Queue',
    'name' => 'Illuminate\\Queue\\InteractsWithQueue',
    'shortName' => 'InteractsWithQueue',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 282,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
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
      'job' => 
      array (
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'name' => 'job',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The underlying queue job instance.
 *
 * @var \\Illuminate\\Contracts\\Queue\\Job|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 16,
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
      'attempts' => 
      array (
        'name' => 'attempts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of times the job has been attempted.
 *
 * @return int
 */',
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete the job from the queue.
 *
 * @return void
 */',
        'startLine' => 40,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'fail' => 
      array (
        'name' => 'fail',
        'parameters' => 
        array (
          'exception' => 
          array (
            'name' => 'exception',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 53,
                'endLine' => 53,
                'startTokenPos' => 157,
                'startFilePos' => 1051,
                'endTokenPos' => 157,
                'endFilePos' => 1054,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 26,
            'endColumn' => 42,
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
 * Fail the job from the queue.
 *
 * @param  \\Throwable|string|null  $exception
 * @return void
 */',
        'startLine' => 53,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'release' => 
      array (
        'name' => 'release',
        'parameters' => 
        array (
          'delay' => 
          array (
            'name' => 'delay',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 74,
                'endLine' => 74,
                'startTokenPos' => 262,
                'startFilePos' => 1694,
                'endTokenPos' => 262,
                'endFilePos' => 1694,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 29,
            'endColumn' => 38,
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
 * Release the job back into the queue after (n) seconds.
 *
 * @param  \\DateTimeInterface|\\DateInterval|int  $delay
 * @return void
 */',
        'startLine' => 74,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'withFakeQueueInteractions' => 
      array (
        'name' => 'withFakeQueueInteractions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that queue interactions like fail, delete, and release should be faked.
 *
 * @return $this
 */',
        'startLine' => 90,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertDeleted' => 
      array (
        'name' => 'assertDeleted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the job was deleted from the queue.
 *
 * @return $this
 */',
        'startLine' => 102,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertNotDeleted' => 
      array (
        'name' => 'assertNotDeleted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the job was not deleted from the queue.
 *
 * @return $this
 */',
        'startLine' => 119,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertFailed' => 
      array (
        'name' => 'assertFailed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the job was manually failed.
 *
 * @return $this
 */',
        'startLine' => 136,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertFailedWith' => 
      array (
        'name' => 'assertFailedWith',
        'parameters' => 
        array (
          'exception' => 
          array (
            'name' => 'exception',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 38,
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
 * Assert that the job was manually failed with a specific exception.
 *
 * @param  \\Throwable|string  $exception
 * @return $this
 */',
        'startLine' => 154,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertNotFailed' => 
      array (
        'name' => 'assertNotFailed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the job was not manually failed.
 *
 * @return $this
 */',
        'startLine' => 199,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertReleased' => 
      array (
        'name' => 'assertReleased',
        'parameters' => 
        array (
          'delay' => 
          array (
            'name' => 'delay',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 217,
                'endLine' => 217,
                'startTokenPos' => 805,
                'startFilePos' => 5482,
                'endTokenPos' => 805,
                'endFilePos' => 5485,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 217,
            'endLine' => 217,
            'startColumn' => 36,
            'endColumn' => 48,
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
 * Assert that the job was released back onto the queue.
 *
 * @param  \\DateTimeInterface|\\DateInterval|int  $delay
 * @return $this
 */',
        'startLine' => 217,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'assertNotReleased' => 
      array (
        'name' => 'assertNotReleased',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the job was not released back onto the queue.
 *
 * @return $this
 */',
        'startLine' => 246,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'ensureQueueInteractionsHaveBeenFaked' => 
      array (
        'name' => 'ensureQueueInteractionsHaveBeenFaked',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure that queue interactions have been faked.
 *
 * @return void
 */',
        'startLine' => 263,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'aliasName' => NULL,
      ),
      'setJob' => 
      array (
        'name' => 'setJob',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Queue\\Job',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 28,
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
 * Set the base queue job instance.
 *
 * @param  \\Illuminate\\Contracts\\Queue\\Job  $job
 * @return $this
 */',
        'startLine' => 276,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'implementingClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
        'currentClassName' => 'Illuminate\\Queue\\InteractsWithQueue',
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