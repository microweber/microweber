<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Concerns/InteractsWithFlashData.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Http\Concerns\InteractsWithFlashData
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-005433ff166ac754454aa664ad0e271b86a2bee386fd69f0c42221a963e20237-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Concerns/InteractsWithFlashData.php',
      ),
    ),
    'namespace' => 'Illuminate\\Http\\Concerns',
    'name' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
    'shortName' => 'InteractsWithFlashData',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 68,
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
      'old' => 
      array (
        'name' => 'old',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 16,
                'endLine' => 16,
                'startTokenPos' => 30,
                'startFilePos' => 350,
                'endTokenPos' => 30,
                'endFilePos' => 353,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 25,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 16,
                'endLine' => 16,
                'startTokenPos' => 37,
                'startFilePos' => 367,
                'endTokenPos' => 37,
                'endFilePos' => 370,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
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
 * Retrieve an old input item.
 *
 * @param  string|null  $key
 * @param  \\Illuminate\\Database\\Eloquent\\Model|string|array|null  $default
 * @return string|array|null
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
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'aliasName' => NULL,
      ),
      'flash' => 
      array (
        'name' => 'flash',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flash the input for the current request to the session.
 *
 * @return void
 */',
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'aliasName' => NULL,
      ),
      'flashOnly' => 
      array (
        'name' => 'flashOnly',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 31,
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
 * Flash only some of the input to the session.
 *
 * @param  array|mixed  $keys
 * @return void
 */',
        'startLine' => 39,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'aliasName' => NULL,
      ),
      'flashExcept' => 
      array (
        'name' => 'flashExcept',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 33,
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
 * Flash only some of the input to the session.
 *
 * @param  array|mixed  $keys
 * @return void
 */',
        'startLine' => 52,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'aliasName' => NULL,
      ),
      'flush' => 
      array (
        'name' => 'flush',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush all of the old input from the session.
 *
 * @return void
 */',
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Concerns',
        'declaringClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'implementingClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
        'currentClassName' => 'Illuminate\\Http\\Concerns\\InteractsWithFlashData',
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