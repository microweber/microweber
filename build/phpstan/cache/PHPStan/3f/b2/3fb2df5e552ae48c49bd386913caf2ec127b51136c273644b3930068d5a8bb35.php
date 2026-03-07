<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Process.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Process
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-743ba558dbb199d9a5a5b1396e1e98e149ed477a689991a405fa98eba4dce198-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Process',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Process.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Process',
    'shortName' => 'Process',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Process\\PendingProcess command(array|string $command)
 * @method static \\Illuminate\\Process\\PendingProcess path(string $path)
 * @method static \\Illuminate\\Process\\PendingProcess timeout(int $timeout)
 * @method static \\Illuminate\\Process\\PendingProcess idleTimeout(int $timeout)
 * @method static \\Illuminate\\Process\\PendingProcess forever()
 * @method static \\Illuminate\\Process\\PendingProcess env(array $environment)
 * @method static \\Illuminate\\Process\\PendingProcess input(\\Traversable|resource|string|int|float|bool|null $input)
 * @method static \\Illuminate\\Process\\PendingProcess quietly()
 * @method static \\Illuminate\\Process\\PendingProcess tty(bool $tty = true)
 * @method static \\Illuminate\\Process\\PendingProcess options(array $options)
 * @method static \\Illuminate\\Contracts\\Process\\ProcessResult run(array|string|null $command = null, callable|null $output = null)
 * @method static \\Illuminate\\Process\\InvokedProcess start(array|string|null $command = null, callable|null $output = null)
 * @method static bool supportsTty()
 * @method static \\Illuminate\\Process\\PendingProcess withFakeHandlers(array $fakeHandlers)
 * @method static \\Illuminate\\Process\\PendingProcess|mixed when(\\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \\Illuminate\\Process\\PendingProcess|mixed unless(\\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \\Illuminate\\Process\\FakeProcessResult result(array|string $output = \'\', array|string $errorOutput = \'\', int $exitCode = 0)
 * @method static \\Illuminate\\Process\\FakeProcessDescription describe()
 * @method static \\Illuminate\\Process\\FakeProcessSequence sequence(array $processes = [])
 * @method static bool isRecording()
 * @method static \\Illuminate\\Process\\Factory recordIfRecording(\\Illuminate\\Process\\PendingProcess $process, \\Illuminate\\Contracts\\Process\\ProcessResult $result)
 * @method static \\Illuminate\\Process\\Factory record(\\Illuminate\\Process\\PendingProcess $process, \\Illuminate\\Contracts\\Process\\ProcessResult $result)
 * @method static \\Illuminate\\Process\\Factory preventStrayProcesses(bool $prevent = true)
 * @method static bool preventingStrayProcesses()
 * @method static \\Illuminate\\Process\\Factory assertRan(\\Closure|string $callback)
 * @method static \\Illuminate\\Process\\Factory assertRanTimes(\\Closure|string $callback, int $times = 1)
 * @method static \\Illuminate\\Process\\Factory assertNotRan(\\Closure|string $callback)
 * @method static \\Illuminate\\Process\\Factory assertDidntRun(\\Closure|string $callback)
 * @method static \\Illuminate\\Process\\Factory assertNothingRan()
 * @method static \\Illuminate\\Process\\Pool pool(callable $callback)
 * @method static \\Illuminate\\Contracts\\Process\\ProcessResult pipe(callable|array $callback, callable|null $output = null)
 * @method static \\Illuminate\\Process\\ProcessPoolResults concurrently(callable $callback, callable|null $output = null)
 * @method static \\Illuminate\\Process\\PendingProcess newPendingProcess()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 *
 * @see \\Illuminate\\Process\\PendingProcess
 * @see \\Illuminate\\Process\\Factory
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 51,
    'endLine' => 75,
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
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Process',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Process',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Process',
        'aliasName' => NULL,
      ),
      'fake' => 
      array (
        'name' => 'fake',
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
                'startLine' => 69,
                'endLine' => 69,
                'startTokenPos' => 72,
                'startFilePos' => 4018,
                'endTokenPos' => 72,
                'endFilePos' => 4021,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
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
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 33,
            'endColumn' => 67,
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
 * Indicate that the process factory should fake processes.
 *
 * @param  \\Closure|array|null  $callback
 * @return \\Illuminate\\Process\\Factory
 */',
        'startLine' => 69,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Process',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Process',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Process',
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