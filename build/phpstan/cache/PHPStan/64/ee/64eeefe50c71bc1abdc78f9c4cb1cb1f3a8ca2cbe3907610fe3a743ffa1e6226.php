<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/System/ClassLoader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Utils\System\ClassLoader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-bccfef5b8d6b3359ca3bf128ff2b6fac9f84e18df1c919d5cf08eef1ce992237',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/System/ClassLoader.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Utils\\System',
    'name' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
    'shortName' => 'ClassLoader',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 104,
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
      'directories' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'name' => 'directories',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 23,
            'startFilePos' => 183,
            'endTokenPos' => 24,
            'endFilePos' => 184,
          ),
        ),
        'docComment' => '/**
 * The registered directories.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'registered' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'name' => 'registered',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 37,
            'startFilePos' => 318,
            'endTokenPos' => 37,
            'endFilePos' => 322,
          ),
        ),
        'docComment' => '/**
 * Indicates if a ClassLoader has been registered.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 41,
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
      'load' => 
      array (
        'name' => 'load',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 33,
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
 * Load the given class file.
 *
 * @param  string  $class
 * @return bool
 */',
        'startLine' => 27,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\System',
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'currentClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'aliasName' => NULL,
      ),
      'normalizeClass' => 
      array (
        'name' => 'normalizeClass',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 43,
            'endColumn' => 48,
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
 * Get the normal file name for a class.
 *
 * @param  string  $class
 * @return string
 */',
        'startLine' => 48,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\System',
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'currentClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'aliasName' => NULL,
      ),
      'register' => 
      array (
        'name' => 'register',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the given class loader on the auto-loader stack.
 *
 * @return void
 */',
        'startLine' => 62,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\System',
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'currentClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'aliasName' => NULL,
      ),
      'addDirectories' => 
      array (
        'name' => 'addDirectories',
        'parameters' => 
        array (
          'directories' => 
          array (
            'name' => 'directories',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 43,
            'endColumn' => 54,
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
 * Add directories to the class loader.
 *
 * @param  string|array  $directories
 * @return void
 */',
        'startLine' => 75,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\System',
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'currentClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'aliasName' => NULL,
      ),
      'removeDirectories' => 
      array (
        'name' => 'removeDirectories',
        'parameters' => 
        array (
          'directories' => 
          array (
            'name' => 'directories',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 291,
                'startFilePos' => 1904,
                'endTokenPos' => 291,
                'endFilePos' => 1907,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 46,
            'endColumn' => 64,
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
 * Remove directories from the class loader.
 *
 * @param  string|array  $directories
 * @return void
 */',
        'startLine' => 86,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\System',
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'currentClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'aliasName' => NULL,
      ),
      'getDirectories' => 
      array (
        'name' => 'getDirectories',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets all the directories registered with the loader.
 *
 * @return array
 */',
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\System',
        'declaringClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'implementingClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
        'currentClassName' => 'MicroweberPackages\\Utils\\System\\ClassLoader',
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