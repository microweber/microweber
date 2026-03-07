<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Scheduling/ManagesFrequencies.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Console\Scheduling\ManagesFrequencies
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8fc0479ac50664fbb1e22077eafb3776726f5857a5e1201ea0420dca5946f4e4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Scheduling/ManagesFrequencies.php',
      ),
    ),
    'namespace' => 'Illuminate\\Console\\Scheduling',
    'name' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
    'shortName' => 'ManagesFrequencies',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 667,
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
      'cron' => 
      array (
        'name' => 'cron',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 26,
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
 * The Cron expression representing the event\'s frequency.
 *
 * @param  string  $expression
 * @return $this
 */',
        'startLine' => 16,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'between' => 
      array (
        'name' => 'between',
        'parameters' => 
        array (
          'startTime' => 
          array (
            'name' => 'startTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'endTime' => 
          array (
            'name' => 'endTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 41,
            'endColumn' => 48,
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
 * Schedule the event to run between start and end time.
 *
 * @param  string  $startTime
 * @param  string  $endTime
 * @return $this
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
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'unlessBetween' => 
      array (
        'name' => 'unlessBetween',
        'parameters' => 
        array (
          'startTime' => 
          array (
            'name' => 'startTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 35,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'endTime' => 
          array (
            'name' => 'endTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 47,
            'endColumn' => 54,
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
 * Schedule the event to not run between start and end time.
 *
 * @param  string  $startTime
 * @param  string  $endTime
 * @return $this
 */',
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'inTimeInterval' => 
      array (
        'name' => 'inTimeInterval',
        'parameters' => 
        array (
          'startTime' => 
          array (
            'name' => 'startTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 37,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'endTime' => 
          array (
            'name' => 'endTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 49,
            'endColumn' => 56,
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
 * Schedule the event to run between start and end time.
 *
 * @param  string  $startTime
 * @param  string  $endTime
 * @return \\Closure
 */',
        'startLine' => 54,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everySecond' => 
      array (
        'name' => 'everySecond',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every second.
 *
 * @return $this
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyTwoSeconds' => 
      array (
        'name' => 'everyTwoSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every two seconds.
 *
 * @return $this
 */',
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyFiveSeconds' => 
      array (
        'name' => 'everyFiveSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every five seconds.
 *
 * @return $this
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyTenSeconds' => 
      array (
        'name' => 'everyTenSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every ten seconds.
 *
 * @return $this
 */',
        'startLine' => 108,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyFifteenSeconds' => 
      array (
        'name' => 'everyFifteenSeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every fifteen seconds.
 *
 * @return $this
 */',
        'startLine' => 118,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyTwentySeconds' => 
      array (
        'name' => 'everyTwentySeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every twenty seconds.
 *
 * @return $this
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyThirtySeconds' => 
      array (
        'name' => 'everyThirtySeconds',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every thirty seconds.
 *
 * @return $this
 */',
        'startLine' => 138,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'repeatEvery' => 
      array (
        'name' => 'repeatEvery',
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
            'startLine' => 149,
            'endLine' => 149,
            'startColumn' => 36,
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
 * Schedule the event to run multiple times per minute.
 *
 * @param  int<0, 59>  $seconds
 * @return $this
 */',
        'startLine' => 149,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyMinute' => 
      array (
        'name' => 'everyMinute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every minute.
 *
 * @return $this
 */',
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyTwoMinutes' => 
      array (
        'name' => 'everyTwoMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every two minutes.
 *
 * @return $this
 */',
        'startLine' => 175,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyThreeMinutes' => 
      array (
        'name' => 'everyThreeMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every three minutes.
 *
 * @return $this
 */',
        'startLine' => 185,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyFourMinutes' => 
      array (
        'name' => 'everyFourMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every four minutes.
 *
 * @return $this
 */',
        'startLine' => 195,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyFiveMinutes' => 
      array (
        'name' => 'everyFiveMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every five minutes.
 *
 * @return $this
 */',
        'startLine' => 205,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyTenMinutes' => 
      array (
        'name' => 'everyTenMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every ten minutes.
 *
 * @return $this
 */',
        'startLine' => 215,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyFifteenMinutes' => 
      array (
        'name' => 'everyFifteenMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every fifteen minutes.
 *
 * @return $this
 */',
        'startLine' => 225,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyThirtyMinutes' => 
      array (
        'name' => 'everyThirtyMinutes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run every thirty minutes.
 *
 * @return $this
 */',
        'startLine' => 235,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'hourly' => 
      array (
        'name' => 'hourly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run hourly.
 *
 * @return $this
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
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'hourlyAt' => 
      array (
        'name' => 'hourlyAt',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 256,
            'endLine' => 256,
            'startColumn' => 30,
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
 * Schedule the event to run hourly at a given offset in the hour.
 *
 * @param  array|string|int<0, 59>|int<0, 59>[]  $offset
 * @return $this
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
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyOddHour' => 
      array (
        'name' => 'everyOddHour',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 267,
                'endLine' => 267,
                'startTokenPos' => 791,
                'startFilePos' => 5752,
                'endTokenPos' => 791,
                'endFilePos' => 5752,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 34,
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
 * Schedule the event to run every odd hour.
 *
 * @param  array|string|int  $offset
 * @return $this
 */',
        'startLine' => 267,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyTwoHours' => 
      array (
        'name' => 'everyTwoHours',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 278,
                'endLine' => 278,
                'startTokenPos' => 823,
                'startFilePos' => 6007,
                'endTokenPos' => 823,
                'endFilePos' => 6007,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 278,
            'endLine' => 278,
            'startColumn' => 35,
            'endColumn' => 45,
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
 * Schedule the event to run every two hours.
 *
 * @param  array|string|int  $offset
 * @return $this
 */',
        'startLine' => 278,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyThreeHours' => 
      array (
        'name' => 'everyThreeHours',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 289,
                'endLine' => 289,
                'startTokenPos' => 855,
                'startFilePos' => 6263,
                'endTokenPos' => 855,
                'endFilePos' => 6263,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Schedule the event to run every three hours.
 *
 * @param  array|string|int  $offset
 * @return $this
 */',
        'startLine' => 289,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everyFourHours' => 
      array (
        'name' => 'everyFourHours',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 300,
                'endLine' => 300,
                'startTokenPos' => 887,
                'startFilePos' => 6517,
                'endTokenPos' => 887,
                'endFilePos' => 6517,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 300,
            'endLine' => 300,
            'startColumn' => 36,
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
 * Schedule the event to run every four hours.
 *
 * @param  array|string|int  $offset
 * @return $this
 */',
        'startLine' => 300,
        'endLine' => 303,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'everySixHours' => 
      array (
        'name' => 'everySixHours',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 311,
                'endLine' => 311,
                'startTokenPos' => 919,
                'startFilePos' => 6769,
                'endTokenPos' => 919,
                'endFilePos' => 6769,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 311,
            'endLine' => 311,
            'startColumn' => 35,
            'endColumn' => 45,
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
 * Schedule the event to run every six hours.
 *
 * @param  array|string|int  $offset
 * @return $this
 */',
        'startLine' => 311,
        'endLine' => 314,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'daily' => 
      array (
        'name' => 'daily',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run daily.
 *
 * @return $this
 */',
        'startLine' => 321,
        'endLine' => 324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'at' => 
      array (
        'name' => 'at',
        'parameters' => 
        array (
          'time' => 
          array (
            'name' => 'time',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 24,
            'endColumn' => 28,
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
 * Schedule the command at a given time.
 *
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 332,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'dailyAt' => 
      array (
        'name' => 'dailyAt',
        'parameters' => 
        array (
          'time' => 
          array (
            'name' => 'time',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 343,
            'endLine' => 343,
            'startColumn' => 29,
            'endColumn' => 33,
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
 * Schedule the event to run daily at a given time (10:00, 19:30, etc).
 *
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 343,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'twiceDaily' => 
      array (
        'name' => 'twiceDaily',
        'parameters' => 
        array (
          'first' => 
          array (
            'name' => 'first',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 360,
                'endLine' => 360,
                'startTokenPos' => 1071,
                'startFilePos' => 7797,
                'endTokenPos' => 1071,
                'endFilePos' => 7797,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 32,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => '13',
              'attributes' => 
              array (
                'startLine' => 360,
                'endLine' => 360,
                'startTokenPos' => 1078,
                'startFilePos' => 7810,
                'endTokenPos' => 1078,
                'endFilePos' => 7811,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 44,
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
 * Schedule the event to run twice daily.
 *
 * @param  int<0, 23>  $first
 * @param  int<0, 23>  $second
 * @return $this
 */',
        'startLine' => 360,
        'endLine' => 363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'twiceDailyAt' => 
      array (
        'name' => 'twiceDailyAt',
        'parameters' => 
        array (
          'first' => 
          array (
            'name' => 'first',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 373,
                'endLine' => 373,
                'startTokenPos' => 1113,
                'startFilePos' => 8137,
                'endTokenPos' => 1113,
                'endFilePos' => 8137,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 373,
            'endLine' => 373,
            'startColumn' => 34,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => '13',
              'attributes' => 
              array (
                'startLine' => 373,
                'endLine' => 373,
                'startTokenPos' => 1120,
                'startFilePos' => 8150,
                'endTokenPos' => 1120,
                'endFilePos' => 8151,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 373,
            'endLine' => 373,
            'startColumn' => 46,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 373,
                'endLine' => 373,
                'startTokenPos' => 1127,
                'startFilePos' => 8164,
                'endTokenPos' => 1127,
                'endFilePos' => 8164,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 373,
            'endLine' => 373,
            'startColumn' => 60,
            'endColumn' => 70,
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
 * Schedule the event to run twice daily at a given offset.
 *
 * @param  int<0, 23>  $first
 * @param  int<0, 23>  $second
 * @param  int<0, 59>  $offset
 * @return $this
 */',
        'startLine' => 373,
        'endLine' => 378,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'hourBasedSchedule' => 
      array (
        'name' => 'hourBasedSchedule',
        'parameters' => 
        array (
          'minutes' => 
          array (
            'name' => 'minutes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 387,
            'endLine' => 387,
            'startColumn' => 42,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'hours' => 
          array (
            'name' => 'hours',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 387,
            'endLine' => 387,
            'startColumn' => 52,
            'endColumn' => 57,
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
 * Schedule the event to run at the given minutes and hours.
 *
 * @param  array|string|int<0, 59>  $minutes
 * @param  array|string|int<0, 23>  $hours
 * @return $this
 */',
        'startLine' => 387,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'weekdays' => 
      array (
        'name' => 'weekdays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on weekdays.
 *
 * @return $this
 */',
        'startLine' => 402,
        'endLine' => 405,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'weekends' => 
      array (
        'name' => 'weekends',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on weekends.
 *
 * @return $this
 */',
        'startLine' => 412,
        'endLine' => 415,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'mondays' => 
      array (
        'name' => 'mondays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Mondays.
 *
 * @return $this
 */',
        'startLine' => 422,
        'endLine' => 425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'tuesdays' => 
      array (
        'name' => 'tuesdays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Tuesdays.
 *
 * @return $this
 */',
        'startLine' => 432,
        'endLine' => 435,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'wednesdays' => 
      array (
        'name' => 'wednesdays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Wednesdays.
 *
 * @return $this
 */',
        'startLine' => 442,
        'endLine' => 445,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'thursdays' => 
      array (
        'name' => 'thursdays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Thursdays.
 *
 * @return $this
 */',
        'startLine' => 452,
        'endLine' => 455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'fridays' => 
      array (
        'name' => 'fridays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Fridays.
 *
 * @return $this
 */',
        'startLine' => 462,
        'endLine' => 465,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'saturdays' => 
      array (
        'name' => 'saturdays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Saturdays.
 *
 * @return $this
 */',
        'startLine' => 472,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'sundays' => 
      array (
        'name' => 'sundays',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run only on Sundays.
 *
 * @return $this
 */',
        'startLine' => 482,
        'endLine' => 485,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'weekly' => 
      array (
        'name' => 'weekly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run weekly.
 *
 * @return $this
 */',
        'startLine' => 492,
        'endLine' => 497,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'weeklyOn' => 
      array (
        'name' => 'weeklyOn',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 506,
            'endLine' => 506,
            'startColumn' => 30,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'time' => 
          array (
            'name' => 'time',
            'default' => 
            array (
              'code' => '\'0:0\'',
              'attributes' => 
              array (
                'startLine' => 506,
                'endLine' => 506,
                'startTokenPos' => 1552,
                'startFilePos' => 10994,
                'endTokenPos' => 1552,
                'endFilePos' => 10998,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 506,
            'endLine' => 506,
            'startColumn' => 42,
            'endColumn' => 54,
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
 * Schedule the event to run weekly on a given day and time.
 *
 * @param  array|mixed  $dayOfWeek
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 506,
        'endLine' => 511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'monthly' => 
      array (
        'name' => 'monthly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run monthly.
 *
 * @return $this
 */',
        'startLine' => 518,
        'endLine' => 523,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'monthlyOn' => 
      array (
        'name' => 'monthlyOn',
        'parameters' => 
        array (
          'dayOfMonth' => 
          array (
            'name' => 'dayOfMonth',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 532,
                'endLine' => 532,
                'startTokenPos' => 1634,
                'startFilePos' => 11563,
                'endTokenPos' => 1634,
                'endFilePos' => 11563,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 532,
            'endLine' => 532,
            'startColumn' => 31,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'time' => 
          array (
            'name' => 'time',
            'default' => 
            array (
              'code' => '\'0:0\'',
              'attributes' => 
              array (
                'startLine' => 532,
                'endLine' => 532,
                'startTokenPos' => 1641,
                'startFilePos' => 11574,
                'endTokenPos' => 1641,
                'endFilePos' => 11578,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 532,
            'endLine' => 532,
            'startColumn' => 48,
            'endColumn' => 60,
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
 * Schedule the event to run monthly on a given day and time.
 *
 * @param  int<1, 31>  $dayOfMonth
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 532,
        'endLine' => 537,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'twiceMonthly' => 
      array (
        'name' => 'twiceMonthly',
        'parameters' => 
        array (
          'first' => 
          array (
            'name' => 'first',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 547,
                'endLine' => 547,
                'startTokenPos' => 1681,
                'startFilePos' => 11932,
                'endTokenPos' => 1681,
                'endFilePos' => 11932,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 547,
            'endLine' => 547,
            'startColumn' => 34,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => '16',
              'attributes' => 
              array (
                'startLine' => 547,
                'endLine' => 547,
                'startTokenPos' => 1688,
                'startFilePos' => 11945,
                'endTokenPos' => 1688,
                'endFilePos' => 11946,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 547,
            'endLine' => 547,
            'startColumn' => 46,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'time' => 
          array (
            'name' => 'time',
            'default' => 
            array (
              'code' => '\'0:0\'',
              'attributes' => 
              array (
                'startLine' => 547,
                'endLine' => 547,
                'startTokenPos' => 1695,
                'startFilePos' => 11957,
                'endTokenPos' => 1695,
                'endFilePos' => 11961,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 547,
            'endLine' => 547,
            'startColumn' => 60,
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
 * Schedule the event to run twice monthly at a given time.
 *
 * @param  int<1, 31>  $first
 * @param  int<1, 31>  $second
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 547,
        'endLine' => 554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'lastDayOfMonth' => 
      array (
        'name' => 'lastDayOfMonth',
        'parameters' => 
        array (
          'time' => 
          array (
            'name' => 'time',
            'default' => 
            array (
              'code' => '\'0:0\'',
              'attributes' => 
              array (
                'startLine' => 562,
                'endLine' => 562,
                'startTokenPos' => 1746,
                'startFilePos' => 12291,
                'endTokenPos' => 1746,
                'endFilePos' => 12295,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 562,
            'endLine' => 562,
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
 * Schedule the event to run on the last day of the month.
 *
 * @param  string  $time
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
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'quarterly' => 
      array (
        'name' => 'quarterly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run quarterly.
 *
 * @return $this
 */',
        'startLine' => 574,
        'endLine' => 580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'quarterlyOn' => 
      array (
        'name' => 'quarterlyOn',
        'parameters' => 
        array (
          'dayOfQuarter' => 
          array (
            'name' => 'dayOfQuarter',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 589,
                'endLine' => 589,
                'startTokenPos' => 1850,
                'startFilePos' => 12950,
                'endTokenPos' => 1850,
                'endFilePos' => 12950,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 589,
            'endLine' => 589,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'time' => 
          array (
            'name' => 'time',
            'default' => 
            array (
              'code' => '\'0:0\'',
              'attributes' => 
              array (
                'startLine' => 589,
                'endLine' => 589,
                'startTokenPos' => 1857,
                'startFilePos' => 12961,
                'endTokenPos' => 1857,
                'endFilePos' => 12965,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 589,
            'endLine' => 589,
            'startColumn' => 52,
            'endColumn' => 64,
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
 * Schedule the event to run quarterly on a given day and time.
 *
 * @param  int  $dayOfQuarter
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 589,
        'endLine' => 595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'yearly' => 
      array (
        'name' => 'yearly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Schedule the event to run yearly.
 *
 * @return $this
 */',
        'startLine' => 602,
        'endLine' => 608,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'yearlyOn' => 
      array (
        'name' => 'yearlyOn',
        'parameters' => 
        array (
          'month' => 
          array (
            'name' => 'month',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 618,
                'endLine' => 618,
                'startTokenPos' => 1960,
                'startFilePos' => 13668,
                'endTokenPos' => 1960,
                'endFilePos' => 13668,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 618,
            'endLine' => 618,
            'startColumn' => 30,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'dayOfMonth' => 
          array (
            'name' => 'dayOfMonth',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 618,
                'endLine' => 618,
                'startTokenPos' => 1967,
                'startFilePos' => 13685,
                'endTokenPos' => 1967,
                'endFilePos' => 13685,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 618,
            'endLine' => 618,
            'startColumn' => 42,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'time' => 
          array (
            'name' => 'time',
            'default' => 
            array (
              'code' => '\'0:0\'',
              'attributes' => 
              array (
                'startLine' => 618,
                'endLine' => 618,
                'startTokenPos' => 1974,
                'startFilePos' => 13696,
                'endTokenPos' => 1974,
                'endFilePos' => 13700,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 618,
            'endLine' => 618,
            'startColumn' => 59,
            'endColumn' => 71,
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
 * Schedule the event to run yearly on a given month, day, and time.
 *
 * @param  int  $month
 * @param  int<1, 31>|string  $dayOfMonth
 * @param  string  $time
 * @return $this
 */',
        'startLine' => 618,
        'endLine' => 624,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'days' => 
      array (
        'name' => 'days',
        'parameters' => 
        array (
          'days' => 
          array (
            'name' => 'days',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 632,
            'endLine' => 632,
            'startColumn' => 26,
            'endColumn' => 30,
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
 * Set the days of the week the command should run on.
 *
 * @param  array|mixed  $days
 * @return $this
 */',
        'startLine' => 632,
        'endLine' => 637,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'timezone' => 
      array (
        'name' => 'timezone',
        'parameters' => 
        array (
          'timezone' => 
          array (
            'name' => 'timezone',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 645,
            'endLine' => 645,
            'startColumn' => 30,
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
 * Set the timezone the date should be evaluated on.
 *
 * @param  \\DateTimeZone|string  $timezone
 * @return $this
 */',
        'startLine' => 645,
        'endLine' => 650,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'aliasName' => NULL,
      ),
      'spliceIntoPosition' => 
      array (
        'name' => 'spliceIntoPosition',
        'parameters' => 
        array (
          'position' => 
          array (
            'name' => 'position',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 659,
            'endLine' => 659,
            'startColumn' => 43,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 659,
            'endLine' => 659,
            'startColumn' => 54,
            'endColumn' => 59,
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
 * Splice the given value into the given position of the expression.
 *
 * @param  int  $position
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 659,
        'endLine' => 666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Scheduling',
        'declaringClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'implementingClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
        'currentClassName' => 'Illuminate\\Console\\Scheduling\\ManagesFrequencies',
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