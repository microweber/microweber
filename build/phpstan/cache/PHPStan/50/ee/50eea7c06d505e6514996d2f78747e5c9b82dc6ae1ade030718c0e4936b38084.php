<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Publishing/Publisher.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Publishing\Publisher
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c0ce48f4da2ade6ab79300b835dea8f9c2d63ff733d3cffe90d8716f9fb31bc7-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Publishing/Publisher.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Publishing',
    'name' => 'Nwidart\\Modules\\Publishing\\Publisher',
    'shortName' => 'Publisher',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 193,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Nwidart\\Modules\\Contracts\\PublisherInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'module' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'name' => 'module',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The name of module will used.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'repository' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'name' => 'repository',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The modules repository instance.
 *
 * @var RepositoryInterface
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'console' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'name' => 'console',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The laravel console instance.
 *
 * @var \\Illuminate\\Console\\Command
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'success' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'name' => 'success',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The success message will displayed at console.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'error' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'name' => 'error',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 75,
            'startFilePos' => 854,
            'endTokenPos' => 75,
            'endFilePos' => 855,
          ),
        ),
        'docComment' => '/**
 * The error message will displayed at console.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'showMessage' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'name' => 'showMessage',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 86,
            'startFilePos' => 999,
            'endTokenPos' => 86,
            'endFilePos' => 1002,
          ),
        ),
        'docComment' => '/**
 * Determine whether the result message will shown in the console.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'module' => 
          array (
            'name' => 'module',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Nwidart\\Modules\\Module',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 57,
            'endLine' => 57,
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
 * The constructor.
 */',
        'startLine' => 57,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'showMessage' => 
      array (
        'name' => 'showMessage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Show the result message.
 *
 * @return self
 */',
        'startLine' => 67,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'hideMessage' => 
      array (
        'name' => 'hideMessage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Hide the result message.
 *
 * @return self
 */',
        'startLine' => 79,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'getModule' => 
      array (
        'name' => 'getModule',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get module instance.
 *
 * @return \\Nwidart\\Modules\\Module
 */',
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'setRepository' => 
      array (
        'name' => 'setRepository',
        'parameters' => 
        array (
          'repository' => 
          array (
            'name' => 'repository',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Nwidart\\Modules\\Contracts\\RepositoryInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 35,
            'endColumn' => 65,
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
 * Set modules repository instance.
 *
 * @return $this
 */',
        'startLine' => 101,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'getRepository' => 
      array (
        'name' => 'getRepository',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get modules repository instance.
 *
 * @return RepositoryInterface
 */',
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'setConsole' => 
      array (
        'name' => 'setConsole',
        'parameters' => 
        array (
          'console' => 
          array (
            'name' => 'console',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Console\\Command',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 32,
            'endColumn' => 47,
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
 * Set console instance.
 *
 *
 * @return $this
 */',
        'startLine' => 124,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'getConsole' => 
      array (
        'name' => 'getConsole',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get console instance.
 *
 * @return \\Illuminate\\Console\\Command
 */',
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'getFilesystem' => 
      array (
        'name' => 'getFilesystem',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get laravel filesystem instance.
 *
 * @return \\Illuminate\\Filesystem\\Filesystem
 */',
        'startLine' => 146,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'getDestinationPath' => 
      array (
        'name' => 'getDestinationPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get destination path.
 *
 * @return string
 */',
        'startLine' => 156,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'getSourcePath' => 
      array (
        'name' => 'getSourcePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get source path.
 *
 * @return string
 */',
        'startLine' => 163,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'aliasName' => NULL,
      ),
      'publish' => 
      array (
        'name' => 'publish',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Publish something.
 */',
        'startLine' => 168,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Publishing',
        'declaringClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'implementingClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
        'currentClassName' => 'Nwidart\\Modules\\Publishing\\Publisher',
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