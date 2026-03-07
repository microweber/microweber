<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Container/ContextualBindingBuilder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Container\ContextualBindingBuilder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b2ab8d24f04224ab8b4259ae1b803f7b6a1fbc45c3020e9a84e05b6f666a1795-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Container/ContextualBindingBuilder.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Container',
    'name' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
    'shortName' => 'ContextualBindingBuilder',
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
    'endLine' => 39,
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
      'needs' => 
      array (
        'name' => 'needs',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 13,
            'endLine' => 13,
            'startColumn' => 27,
            'endColumn' => 35,
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
 * Define the abstract target that depends on the context.
 *
 * @param  string  $abstract
 * @return $this
 */',
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Container',
        'declaringClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'implementingClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'currentClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'aliasName' => NULL,
      ),
      'give' => 
      array (
        'name' => 'give',
        'parameters' => 
        array (
          'implementation' => 
          array (
            'name' => 'implementation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 26,
            'endColumn' => 40,
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
 * Define the implementation for the contextual binding.
 *
 * @param  \\Closure|string|array  $implementation
 * @return void
 */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Container',
        'declaringClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'implementingClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'currentClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'aliasName' => NULL,
      ),
      'giveTagged' => 
      array (
        'name' => 'giveTagged',
        'parameters' => 
        array (
          'tag' => 
          array (
            'name' => 'tag',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 32,
            'endColumn' => 35,
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
 * Define tagged services to be used as the implementation for the contextual binding.
 *
 * @param  string  $tag
 * @return void
 */',
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Container',
        'declaringClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'implementingClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'currentClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'aliasName' => NULL,
      ),
      'giveConfig' => 
      array (
        'name' => 'giveConfig',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 64,
                'startFilePos' => 881,
                'endTokenPos' => 64,
                'endFilePos' => 884,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 38,
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
 * Specify the configuration item to bind as a primitive.
 *
 * @param  string  $key
 * @param  mixed  $default
 * @return void
 */',
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Container',
        'declaringClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'implementingClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
        'currentClassName' => 'Illuminate\\Contracts\\Container\\ContextualBindingBuilder',
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