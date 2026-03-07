<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Testing/Fakes/QueueFake.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Testing\Fakes\QueueFake
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5c340e04f1c0d276915679a36078b4586105525ff3683531709e88835b8719ab-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Testing/Fakes/QueueFake.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
    'name' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
    'shortName' => 'QueueFake',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @phpstan-type RawPushType array{"payload": string, "queue": string|null, "options": array<array-key, mixed>}
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 616,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Queue\\QueueManager',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Testing\\Fakes\\Fake',
      1 => 'Illuminate\\Contracts\\Queue\\Queue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\ReflectsClosures',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'queue' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'name' => 'queue',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The original queue manager.
 *
 * @var \\Illuminate\\Contracts\\Queue\\Queue
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 18,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'jobsToFake' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'name' => 'jobsToFake',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The job types that should be intercepted instead of pushed to the queue.
 *
 * @var \\Illuminate\\Support\\Collection
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'jobsToBeQueued' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'name' => 'jobsToBeQueued',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The job types that should be pushed to the queue and not intercepted.
 *
 * @var \\Illuminate\\Support\\Collection
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'jobs' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'name' => 'jobs',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 104,
            'startFilePos' => 1122,
            'endTokenPos' => 105,
            'endFilePos' => 1123,
          ),
        ),
        'docComment' => '/**
 * All of the jobs that have been pushed.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'rawPushes' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'name' => 'rawPushes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 116,
            'startFilePos' => 1261,
            'endTokenPos' => 117,
            'endFilePos' => 1262,
          ),
        ),
        'docComment' => '/**
 * All of the payloads that have been raw pushed.
 *
 * @var list<RawPushType>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'serializeAndRestore' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'name' => 'serializeAndRestore',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 130,
            'startFilePos' => 1434,
            'endTokenPos' => 130,
            'endFilePos' => 1438,
          ),
        ),
        'docComment' => '/**
 * Indicates if items should be serialized and restored when pushed to the queue.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 48,
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
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 33,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'jobsToFake' => 
          array (
            'name' => 'jobsToFake',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 71,
                'endLine' => 71,
                'startTokenPos' => 148,
                'startFilePos' => 1737,
                'endTokenPos' => 149,
                'endFilePos' => 1738,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 39,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 71,
                'endLine' => 71,
                'startTokenPos' => 156,
                'startFilePos' => 1750,
                'endTokenPos' => 156,
                'endFilePos' => 1753,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 57,
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
 * Create a new fake queue instance.
 *
 * @param  \\Illuminate\\Contracts\\Foundation\\Application  $app
 * @param  array  $jobsToFake
 * @param  \\Illuminate\\Queue\\QueueManager|null  $queue
 * @return void
 */',
        'startLine' => 71,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'except' => 
      array (
        'name' => 'except',
        'parameters' => 
        array (
          'jobsToBeQueued' => 
          array (
            'name' => 'jobsToBeQueued',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 28,
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
 * Specify the jobs that should be queued instead of faked.
 *
 * @param  array|string  $jobsToBeQueued
 * @return $this
 */',
        'startLine' => 86,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushed' => 
      array (
        'name' => 'assertPushed',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 34,
            'endColumn' => 37,
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
                'startLine' => 100,
                'endLine' => 100,
                'startTokenPos' => 261,
                'startFilePos' => 2514,
                'endTokenPos' => 261,
                'endFilePos' => 2517,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 40,
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
 * Assert if a job was pushed based on a truth-test callback.
 *
 * @param  string|\\Closure  $job
 * @param  callable|int|null  $callback
 * @return void
 */',
        'startLine' => 100,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushedTimes' => 
      array (
        'name' => 'assertPushedTimes',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 42,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'times' => 
          array (
            'name' => 'times',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 123,
                'endLine' => 123,
                'startTokenPos' => 380,
                'startFilePos' => 3133,
                'endTokenPos' => 380,
                'endFilePos' => 3133,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 48,
            'endColumn' => 57,
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
 * Assert if a job was pushed a number of times.
 *
 * @param  string  $job
 * @param  int  $times
 * @return void
 */',
        'startLine' => 123,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushedOn' => 
      array (
        'name' => 'assertPushedOn',
        'parameters' => 
        array (
          'queue' => 
          array (
            'name' => 'queue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 36,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 44,
            'endColumn' => 47,
            'parameterIndex' => 1,
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
                'startLine' => 141,
                'endLine' => 141,
                'startTokenPos' => 451,
                'startFilePos' => 3635,
                'endTokenPos' => 451,
                'endFilePos' => 3638,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 50,
            'endColumn' => 65,
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
 * Assert if a job was pushed based on a truth-test callback.
 *
 * @param  string  $queue
 * @param  string|\\Closure  $job
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 141,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushedWithChain' => 
      array (
        'name' => 'assertPushedWithChain',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 43,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expectedChain' => 
          array (
            'name' => 'expectedChain',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 164,
                'endLine' => 164,
                'startTokenPos' => 578,
                'startFilePos' => 4340,
                'endTokenPos' => 579,
                'endFilePos' => 4341,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 49,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 164,
                'endLine' => 164,
                'startTokenPos' => 586,
                'startFilePos' => 4356,
                'endTokenPos' => 586,
                'endFilePos' => 4359,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 70,
            'endColumn' => 85,
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
 * Assert if a job was pushed with chained jobs based on a truth-test callback.
 *
 * @param  string  $job
 * @param  array  $expectedChain
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 164,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushedWithoutChain' => 
      array (
        'name' => 'assertPushedWithoutChain',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 46,
            'endColumn' => 49,
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
                'startLine' => 188,
                'endLine' => 188,
                'startTokenPos' => 701,
                'startFilePos' => 5169,
                'endTokenPos' => 701,
                'endFilePos' => 5172,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 52,
            'endColumn' => 67,
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
 * Assert if a job was pushed with an empty chain based on a truth-test callback.
 *
 * @param  string  $job
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 188,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushedWithChainOfObjects' => 
      array (
        'name' => 'assertPushedWithChainOfObjects',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 206,
            'endLine' => 206,
            'startColumn' => 55,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expectedChain' => 
          array (
            'name' => 'expectedChain',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 206,
            'endLine' => 206,
            'startColumn' => 61,
            'endColumn' => 74,
            'parameterIndex' => 1,
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
            'startLine' => 206,
            'endLine' => 206,
            'startColumn' => 77,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert if a job was pushed with chained jobs based on a truth-test callback.
 *
 * @param  string  $job
 * @param  array  $expectedChain
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 206,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertPushedWithChainOfClasses' => 
      array (
        'name' => 'assertPushedWithChainOfClasses',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 55,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expectedChain' => 
          array (
            'name' => 'expectedChain',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 61,
            'endColumn' => 74,
            'parameterIndex' => 1,
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
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 77,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert if a job was pushed with chained jobs based on a truth-test callback.
 *
 * @param  string  $job
 * @param  array  $expectedChain
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 224,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertClosurePushed' => 
      array (
        'name' => 'assertClosurePushed',
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
                'startLine' => 245,
                'endLine' => 245,
                'startTokenPos' => 1003,
                'startFilePos' => 7037,
                'endTokenPos' => 1003,
                'endFilePos' => 7040,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 245,
            'endLine' => 245,
            'startColumn' => 41,
            'endColumn' => 56,
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
 * Assert if a closure was pushed based on a truth-test callback.
 *
 * @param  callable|int|null  $callback
 * @return void
 */',
        'startLine' => 245,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertClosureNotPushed' => 
      array (
        'name' => 'assertClosureNotPushed',
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
                'startLine' => 256,
                'endLine' => 256,
                'startTokenPos' => 1035,
                'startFilePos' => 7336,
                'endTokenPos' => 1035,
                'endFilePos' => 7339,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 256,
            'endLine' => 256,
            'startColumn' => 44,
            'endColumn' => 59,
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
 * Assert that a closure was not pushed based on a truth-test callback.
 *
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 256,
        'endLine' => 259,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'isChainOfObjects' => 
      array (
        'name' => 'isChainOfObjects',
        'parameters' => 
        array (
          'chain' => 
          array (
            'name' => 'chain',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 267,
            'endLine' => 267,
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
 * Determine if the given chain is entirely composed of objects.
 *
 * @param  array  $chain
 * @return bool
 */',
        'startLine' => 267,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertNotPushed' => 
      array (
        'name' => 'assertNotPushed',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 37,
            'endColumn' => 40,
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
                'startLine' => 279,
                'endLine' => 279,
                'startTokenPos' => 1117,
                'startFilePos' => 7954,
                'endTokenPos' => 1117,
                'endFilePos' => 7957,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 43,
            'endColumn' => 58,
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
 * Determine if a job was pushed based on a truth-test callback.
 *
 * @param  string|\\Closure  $job
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 279,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertCount' => 
      array (
        'name' => 'assertCount',
        'parameters' => 
        array (
          'expectedCount' => 
          array (
            'name' => 'expectedCount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 297,
            'endLine' => 297,
            'startColumn' => 33,
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
 * Assert the total count of jobs that were pushed.
 *
 * @param  int  $expectedCount
 * @return void
 */',
        'startLine' => 297,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'assertNothingPushed' => 
      array (
        'name' => 'assertNothingPushed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that no jobs were pushed.
 *
 * @return void
 */',
        'startLine' => 312,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'pushed' => 
      array (
        'name' => 'pushed',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
            'startColumn' => 28,
            'endColumn' => 31,
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
                'startLine' => 326,
                'endLine' => 326,
                'startTokenPos' => 1320,
                'startFilePos' => 9245,
                'endTokenPos' => 1320,
                'endFilePos' => 9248,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
            'startColumn' => 34,
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
 * Get all of the jobs matching a truth-test callback.
 *
 * @param  string  $job
 * @param  callable|null  $callback
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 326,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'pushedRaw' => 
      array (
        'name' => 'pushedRaw',
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
                'startLine' => 345,
                'endLine' => 345,
                'startTokenPos' => 1437,
                'startFilePos' => 9829,
                'endTokenPos' => 1437,
                'endFilePos' => 9832,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 31,
            'endColumn' => 46,
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
 * Get all of the raw pushes matching a truth-test callback.
 *
 * @param  null|\\Closure(string, ?string, array): bool  $callback
 * @return \\Illuminate\\Support\\Collection<int, RawPushType>
 */',
        'startLine' => 345,
        'endLine' => 350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'hasPushed' => 
      array (
        'name' => 'hasPushed',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 358,
            'endLine' => 358,
            'startColumn' => 31,
            'endColumn' => 34,
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
 * Determine if there are any stored jobs for a given class.
 *
 * @param  string  $job
 * @return bool
 */',
        'startLine' => 358,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'connection' => 
      array (
        'name' => 'connection',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 369,
                'endLine' => 369,
                'startTokenPos' => 1559,
                'startFilePos' => 10470,
                'endTokenPos' => 1559,
                'endFilePos' => 10473,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
            'startColumn' => 32,
            'endColumn' => 44,
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
 * Resolve a queue connection instance.
 *
 * @param  mixed  $value
 * @return \\Illuminate\\Contracts\\Queue\\Queue
 */',
        'startLine' => 369,
        'endLine' => 372,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'size' => 
      array (
        'name' => 'size',
        'parameters' => 
        array (
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 380,
                'endLine' => 380,
                'startTokenPos' => 1583,
                'startFilePos' => 10656,
                'endTokenPos' => 1583,
                'endFilePos' => 10659,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 26,
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
 * Get the size of the queue.
 *
 * @param  string|null  $queue
 * @return int
 */',
        'startLine' => 380,
        'endLine' => 385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'push' => 
      array (
        'name' => 'push',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 395,
            'endLine' => 395,
            'startColumn' => 26,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 395,
                'endLine' => 395,
                'startTokenPos' => 1650,
                'startFilePos' => 11030,
                'endTokenPos' => 1650,
                'endFilePos' => 11031,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 395,
            'endLine' => 395,
            'startColumn' => 32,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 395,
                'endLine' => 395,
                'startTokenPos' => 1657,
                'startFilePos' => 11043,
                'endTokenPos' => 1657,
                'endFilePos' => 11046,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 395,
            'endLine' => 395,
            'startColumn' => 44,
            'endColumn' => 56,
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
 * Push a new job onto the queue.
 *
 * @param  string|object  $job
 * @param  mixed  $data
 * @param  string|null  $queue
 * @return mixed
 */',
        'startLine' => 395,
        'endLine' => 412,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'shouldFakeJob' => 
      array (
        'name' => 'shouldFakeJob',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 420,
            'endLine' => 420,
            'startColumn' => 35,
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
 * Determine if a job should be faked or actually dispatched.
 *
 * @param  object  $job
 * @return bool
 */',
        'startLine' => 420,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'shouldDispatchJob' => 
      array (
        'name' => 'shouldDispatchJob',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 441,
            'endLine' => 441,
            'startColumn' => 42,
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
 * Determine if a job should be pushed to the queue instead of faked.
 *
 * @param  object  $job
 * @return bool
 */',
        'startLine' => 441,
        'endLine' => 450,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'pushRaw' => 
      array (
        'name' => 'pushRaw',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 2010,
                'startFilePos' => 12860,
                'endTokenPos' => 2010,
                'endFilePos' => 12863,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 2019,
                'startFilePos' => 12883,
                'endTokenPos' => 2020,
                'endFilePos' => 12884,
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
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 54,
            'endColumn' => 72,
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
 * Push a raw payload onto the queue.
 *
 * @param  string  $payload
 * @param  string|null  $queue
 * @param  array  $options
 * @return mixed
 */',
        'startLine' => 460,
        'endLine' => 467,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'later' => 
      array (
        'name' => 'later',
        'parameters' => 
        array (
          'delay' => 
          array (
            'name' => 'delay',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 478,
            'endLine' => 478,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 478,
            'endLine' => 478,
            'startColumn' => 35,
            'endColumn' => 38,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 478,
                'endLine' => 478,
                'startTokenPos' => 2079,
                'startFilePos' => 13349,
                'endTokenPos' => 2079,
                'endFilePos' => 13350,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 478,
            'endLine' => 478,
            'startColumn' => 41,
            'endColumn' => 50,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 478,
                'endLine' => 478,
                'startTokenPos' => 2086,
                'startFilePos' => 13362,
                'endTokenPos' => 2086,
                'endFilePos' => 13365,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 478,
            'endLine' => 478,
            'startColumn' => 53,
            'endColumn' => 65,
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
 * Push a new job onto the queue after (n) seconds.
 *
 * @param  \\DateTimeInterface|\\DateInterval|int  $delay
 * @param  string|object  $job
 * @param  mixed  $data
 * @param  string|null  $queue
 * @return mixed
 */',
        'startLine' => 478,
        'endLine' => 481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'pushOn' => 
      array (
        'name' => 'pushOn',
        'parameters' => 
        array (
          'queue' => 
          array (
            'name' => 'queue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 28,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 36,
            'endColumn' => 39,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 491,
                'endLine' => 491,
                'startTokenPos' => 2127,
                'startFilePos' => 13654,
                'endTokenPos' => 2127,
                'endFilePos' => 13655,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 42,
            'endColumn' => 51,
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
 * Push a new job onto the queue.
 *
 * @param  string  $queue
 * @param  string|object  $job
 * @param  mixed  $data
 * @return mixed
 */',
        'startLine' => 491,
        'endLine' => 494,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'laterOn' => 
      array (
        'name' => 'laterOn',
        'parameters' => 
        array (
          'queue' => 
          array (
            'name' => 'queue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 29,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'delay' => 
          array (
            'name' => 'delay',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 45,
            'endColumn' => 48,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 505,
                'endLine' => 505,
                'startTokenPos' => 2171,
                'startFilePos' => 14038,
                'endTokenPos' => 2171,
                'endFilePos' => 14039,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 51,
            'endColumn' => 60,
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
 * Push a new job onto a specific queue after (n) seconds.
 *
 * @param  string  $queue
 * @param  \\DateTimeInterface|\\DateInterval|int  $delay
 * @param  string|object  $job
 * @param  mixed  $data
 * @return mixed
 */',
        'startLine' => 505,
        'endLine' => 508,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'pop' => 
      array (
        'name' => 'pop',
        'parameters' => 
        array (
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 516,
                'endLine' => 516,
                'startTokenPos' => 2206,
                'startFilePos' => 14289,
                'endTokenPos' => 2206,
                'endFilePos' => 14292,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 516,
            'endLine' => 516,
            'startColumn' => 25,
            'endColumn' => 37,
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
 * Pop the next job off of the queue.
 *
 * @param  string|null  $queue
 * @return \\Illuminate\\Contracts\\Queue\\Job|null
 */',
        'startLine' => 516,
        'endLine' => 519,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'bulk' => 
      array (
        'name' => 'bulk',
        'parameters' => 
        array (
          'jobs' => 
          array (
            'name' => 'jobs',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 529,
            'endLine' => 529,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 529,
                'endLine' => 529,
                'startTokenPos' => 2230,
                'startFilePos' => 14539,
                'endTokenPos' => 2230,
                'endFilePos' => 14540,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 529,
            'endLine' => 529,
            'startColumn' => 33,
            'endColumn' => 42,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'queue' => 
          array (
            'name' => 'queue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 529,
                'endLine' => 529,
                'startTokenPos' => 2237,
                'startFilePos' => 14552,
                'endTokenPos' => 2237,
                'endFilePos' => 14555,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 529,
            'endLine' => 529,
            'startColumn' => 45,
            'endColumn' => 57,
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
 * Push an array of jobs onto the queue.
 *
 * @param  array  $jobs
 * @param  mixed  $data
 * @param  string|null  $queue
 * @return mixed
 */',
        'startLine' => 529,
        'endLine' => 534,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'pushedJobs' => 
      array (
        'name' => 'pushedJobs',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the jobs that have been pushed.
 *
 * @return array
 */',
        'startLine' => 541,
        'endLine' => 544,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'rawPushes' => 
      array (
        'name' => 'rawPushes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the payloads that were pushed raw.
 *
 * @return list<RawPushType>
 */',
        'startLine' => 551,
        'endLine' => 554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'serializeAndRestore' => 
      array (
        'name' => 'serializeAndRestore',
        'parameters' => 
        array (
          'serializeAndRestore' => 
          array (
            'name' => 'serializeAndRestore',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 562,
                'endLine' => 562,
                'startTokenPos' => 2328,
                'startFilePos' => 15247,
                'endTokenPos' => 2328,
                'endFilePos' => 15250,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 562,
            'endLine' => 562,
            'startColumn' => 41,
            'endColumn' => 72,
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
 * Specify if jobs should be serialized and restored when being "pushed" to the queue.
 *
 * @param  bool  $serializeAndRestore
 * @return $this
 */',
        'startLine' => 562,
        'endLine' => 567,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'serializeAndRestoreJob' => 
      array (
        'name' => 'serializeAndRestoreJob',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 575,
            'endLine' => 575,
            'startColumn' => 47,
            'endColumn' => 50,
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
 * Serialize and unserialize the job to simulate the queueing process.
 *
 * @param  mixed  $job
 * @return mixed
 */',
        'startLine' => 575,
        'endLine' => 578,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'getConnectionName' => 
      array (
        'name' => 'getConnectionName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the connection name for the queue.
 *
 * @return string
 */',
        'startLine' => 585,
        'endLine' => 588,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      'setConnectionName' => 
      array (
        'name' => 'setConnectionName',
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
            'startLine' => 596,
            'endLine' => 596,
            'startColumn' => 39,
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
 * Set the connection name for the queue.
 *
 * @param  string  $name
 * @return $this
 */',
        'startLine' => 596,
        'endLine' => 599,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 610,
            'endLine' => 610,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 610,
            'endLine' => 610,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Override the QueueManager to prevent circular dependency.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 *
 * @throws \\BadMethodCallException
 */',
        'startLine' => 610,
        'endLine' => 615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Testing\\Fakes',
        'declaringClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'implementingClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
        'currentClassName' => 'Illuminate\\Support\\Testing\\Fakes\\QueueFake',
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