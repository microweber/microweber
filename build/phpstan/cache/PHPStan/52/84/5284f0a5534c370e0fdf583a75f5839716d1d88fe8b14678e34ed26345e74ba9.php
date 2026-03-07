<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/View/Concerns/ManagesLoops.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\View\Concerns\ManagesLoops
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-635c06ba226d82360e7894b0625c1b78d0497fb8174e8322b51c5c51ea1806b1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/View/Concerns/ManagesLoops.php',
      ),
    ),
    'namespace' => 'Illuminate\\View\\Concerns',
    'name' => 'Illuminate\\View\\Concerns\\ManagesLoops',
    'shortName' => 'ManagesLoops',
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
    'endLine' => 96,
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
      'loopsStack' => 
      array (
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'name' => 'loopsStack',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 31,
            'startFilePos' => 241,
            'endTokenPos' => 32,
            'endFilePos' => 242,
          ),
        ),
        'docComment' => '/**
 * The stack of in-progress loops.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 31,
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
      'addLoop' => 
      array (
        'name' => 'addLoop',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
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
 * Add new loop to the stack.
 *
 * @param  \\Countable|array  $data
 * @return void
 */',
        'startLine' => 23,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'aliasName' => NULL,
      ),
      'incrementLoopIndices' => 
      array (
        'name' => 'incrementLoopIndices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Increment the top loop\'s indices.
 *
 * @return void
 */',
        'startLine' => 50,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'aliasName' => NULL,
      ),
      'popLoop' => 
      array (
        'name' => 'popLoop',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Pop a loop from the top of the loop stack.
 *
 * @return void
 */',
        'startLine' => 70,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'aliasName' => NULL,
      ),
      'getLastLoop' => 
      array (
        'name' => 'getLastLoop',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an instance of the last loop in the stack.
 *
 * @return \\stdClass|null
 */',
        'startLine' => 80,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'aliasName' => NULL,
      ),
      'getLoopStack' => 
      array (
        'name' => 'getLoopStack',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the entire loop stack.
 *
 * @return array
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesLoops',
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