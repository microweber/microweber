<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/View/Concerns/ManagesFragments.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\View\Concerns\ManagesFragments
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3aa45ff28b496717744482d52ad4ed5a1e540ef49b0dc75e2eb6844db918a801-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/View/Concerns/ManagesFragments.php',
      ),
    ),
    'namespace' => 'Illuminate\\View\\Concerns',
    'name' => 'Illuminate\\View\\Concerns\\ManagesFragments',
    'shortName' => 'ManagesFragments',
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
    'endLine' => 88,
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
      'fragments' => 
      array (
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'name' => 'fragments',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 26,
            'startFilePos' => 216,
            'endTokenPos' => 27,
            'endFilePos' => 217,
          ),
        ),
        'docComment' => '/**
 * All of the captured, rendered fragments.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fragmentStack' => 
      array (
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'name' => 'fragmentStack',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 38,
            'startFilePos' => 343,
            'endTokenPos' => 39,
            'endFilePos' => 344,
          ),
        ),
        'docComment' => '/**
 * The stack of in-progress fragment renders.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 34,
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
      'startFragment' => 
      array (
        'name' => 'startFragment',
        'parameters' => 
        array (
          'fragment' => 
          array (
            'name' => 'fragment',
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
            'startColumn' => 35,
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
 * Start injecting content into a fragment.
 *
 * @param  string  $fragment
 * @return void
 */',
        'startLine' => 29,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'aliasName' => NULL,
      ),
      'stopFragment' => 
      array (
        'name' => 'stopFragment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stop injecting content into a fragment.
 *
 * @return string
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 43,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'aliasName' => NULL,
      ),
      'getFragment' => 
      array (
        'name' => 'getFragment',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 33,
            'endColumn' => 37,
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
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 170,
                'startFilePos' => 1297,
                'endTokenPos' => 170,
                'endFilePos' => 1300,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 40,
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
 * Get the contents of a fragment.
 *
 * @param  string  $name
 * @param  string|null  $default
 * @return mixed
 */',
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'aliasName' => NULL,
      ),
      'getFragments' => 
      array (
        'name' => 'getFragments',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the entire array of rendered fragments.
 *
 * @return array
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'aliasName' => NULL,
      ),
      'flushFragments' => 
      array (
        'name' => 'flushFragments',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush all of the fragments.
 *
 * @return void
 */',
        'startLine' => 83,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\View\\Concerns',
        'declaringClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'implementingClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
        'currentClassName' => 'Illuminate\\View\\Concerns\\ManagesFragments',
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