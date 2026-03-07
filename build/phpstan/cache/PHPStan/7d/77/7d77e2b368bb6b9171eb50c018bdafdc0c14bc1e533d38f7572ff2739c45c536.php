<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Concerns/InteractsWithSignals.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Console\Concerns\InteractsWithSignals
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b5cbab30713dd9b48178b8ad13186fb62f9d9fbdf0407a8827b9c84a5773cb28-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Concerns/InteractsWithSignals.php',
      ),
    ),
    'namespace' => 'Illuminate\\Console\\Concerns',
    'name' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
    'shortName' => 'InteractsWithSignals',
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
    'endLine' => 53,
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
      'signals' => 
      array (
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'name' => 'signals',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The signal registrar instance.
 *
 * @var \\Illuminate\\Console\\Signals|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 23,
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
      'trap' => 
      array (
        'name' => 'trap',
        'parameters' => 
        array (
          'signals' => 
          array (
            'name' => 'signals',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 26,
            'endColumn' => 33,
            'parameterIndex' => 0,
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
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 36,
            'endColumn' => 44,
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
 * Define a callback to be run when the given signal(s) occurs.
 *
 * @template TSignals of iterable<array-key, int>|int
 *
 * @param  (\\Closure():(TSignals))|TSignals  $signals
 * @param  callable(int $signal): void  $callback
 * @return void
 */',
        'startLine' => 26,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'aliasName' => NULL,
      ),
      'untrap' => 
      array (
        'name' => 'untrap',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Untrap signal handlers set within the command\'s handler.
 *
 * @return void
 *
 * @internal
 */',
        'startLine' => 45,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\InteractsWithSignals',
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