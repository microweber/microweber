<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Bus.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Bus
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6ca43cfae6d1c8f80bbe449170edb50366e8786a1c2cc5a93beba8a4b03fd80b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Bus',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Bus.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Bus',
    'shortName' => 'Bus',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static mixed dispatch(mixed $command)
 * @method static mixed dispatchSync(mixed $command, mixed $handler = null)
 * @method static mixed dispatchNow(mixed $command, mixed $handler = null)
 * @method static \\Illuminate\\Bus\\Batch|null findBatch(string $batchId)
 * @method static \\Illuminate\\Bus\\PendingBatch batch(\\Illuminate\\Support\\Collection|array|mixed $jobs)
 * @method static \\Illuminate\\Foundation\\Bus\\PendingChain chain(\\Illuminate\\Support\\Collection|array $jobs)
 * @method static bool hasCommandHandler(mixed $command)
 * @method static bool|mixed getCommandHandler(mixed $command)
 * @method static mixed dispatchToQueue(mixed $command)
 * @method static void dispatchAfterResponse(mixed $command, mixed $handler = null)
 * @method static \\Illuminate\\Bus\\Dispatcher pipeThrough(array $pipes)
 * @method static \\Illuminate\\Bus\\Dispatcher map(array $map)
 * @method static \\Illuminate\\Support\\Testing\\Fakes\\BusFake except(array|string $jobsToDispatch)
 * @method static void assertDispatched(string|\\Closure $command, callable|int|null $callback = null)
 * @method static void assertDispatchedTimes(string|\\Closure $command, int $times = 1)
 * @method static void assertNotDispatched(string|\\Closure $command, callable|null $callback = null)
 * @method static void assertNothingDispatched()
 * @method static void assertDispatchedSync(string|\\Closure $command, callable|int|null $callback = null)
 * @method static void assertDispatchedSyncTimes(string|\\Closure $command, int $times = 1)
 * @method static void assertNotDispatchedSync(string|\\Closure $command, callable|null $callback = null)
 * @method static void assertDispatchedAfterResponse(string|\\Closure $command, callable|int|null $callback = null)
 * @method static void assertDispatchedAfterResponseTimes(string|\\Closure $command, int $times = 1)
 * @method static void assertNotDispatchedAfterResponse(string|\\Closure $command, callable|null $callback = null)
 * @method static void assertChained(array $expectedChain)
 * @method static void assertNothingChained()
 * @method static void assertDispatchedWithoutChain(string|\\Closure $command, callable|null $callback = null)
 * @method static \\Illuminate\\Support\\Testing\\Fakes\\ChainedBatchTruthTest chainedBatch(\\Closure $callback)
 * @method static void assertBatched(callable $callback)
 * @method static void assertBatchCount(int $count)
 * @method static void assertNothingBatched()
 * @method static void assertNothingPlaced()
 * @method static \\Illuminate\\Support\\Collection dispatched(string $command, callable|null $callback = null)
 * @method static \\Illuminate\\Support\\Collection dispatchedSync(string $command, callable|null $callback = null)
 * @method static \\Illuminate\\Support\\Collection dispatchedAfterResponse(string $command, callable|null $callback = null)
 * @method static \\Illuminate\\Support\\Collection batched(callable $callback)
 * @method static bool hasDispatched(string $command)
 * @method static bool hasDispatchedSync(string $command)
 * @method static bool hasDispatchedAfterResponse(string $command)
 * @method static \\Illuminate\\Bus\\Batch dispatchFakeBatch(string $name = \'\')
 * @method static \\Illuminate\\Bus\\Batch recordPendingBatch(\\Illuminate\\Bus\\PendingBatch $pendingBatch)
 * @method static \\Illuminate\\Support\\Testing\\Fakes\\BusFake serializeAndRestore(bool $serializeAndRestore = true)
 * @method static array dispatchedBatches()
 *
 * @see \\Illuminate\\Bus\\Dispatcher
 * @see \\Illuminate\\Support\\Testing\\Fakes\\BusFake
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 57,
    'endLine' => 100,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Facades\\Facade',
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
      'fake' => 
      array (
        'name' => 'fake',
        'parameters' => 
        array (
          'jobsToFake' => 
          array (
            'name' => 'jobsToFake',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 57,
                'startFilePos' => 4048,
                'endTokenPos' => 58,
                'endFilePos' => 4049,
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
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'batchRepository' => 
          array (
            'name' => 'batchRepository',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 68,
                'startFilePos' => 4088,
                'endTokenPos' => 68,
                'endFilePos' => 4091,
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
                      'name' => 'Illuminate\\Bus\\BatchRepository',
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
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 51,
            'endColumn' => 90,
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
 * Replace the bound instance with a fake.
 *
 * @param  array|string  $jobsToFake
 * @param  \\Illuminate\\Bus\\BatchRepository|null  $batchRepository
 * @return \\Illuminate\\Support\\Testing\\Fakes\\BusFake
 */',
        'startLine' => 66,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'aliasName' => NULL,
      ),
      'dispatchChain' => 
      array (
        'name' => 'dispatchChain',
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
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 42,
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
 * Dispatch the given chain of jobs.
 *
 * @param  array|mixed  $jobs
 * @return \\Illuminate\\Foundation\\Bus\\PendingDispatch
 */',
        'startLine' => 83,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'aliasName' => NULL,
      ),
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 */',
        'startLine' => 96,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Bus',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Bus',
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