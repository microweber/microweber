<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Updater/Services/UpdaterHelper.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Updater\Services\UpdaterHelper
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-81f246306e7033406a3ad8ba40c12d0da1d6b27de8d81f0f24a7d3b6483afe23',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Updater/Services/UpdaterHelper.php',
      ),
    ),
    'namespace' => 'Modules\\Updater\\Services',
    'name' => 'Modules\\Updater\\Services\\UpdaterHelper',
    'shortName' => 'UpdaterHelper',
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
    'endLine' => 182,
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
      'getLatestVersion' => 
      array (
        'name' => 'getLatestVersion',
        'parameters' => 
        array (
          'selectedBranch' => 
          array (
            'name' => 'selectedBranch',
            'default' => 
            array (
              'code' => '\'master\'',
              'attributes' => 
              array (
                'startLine' => 10,
                'endLine' => 10,
                'startTokenPos' => 25,
                'startFilePos' => 190,
                'endTokenPos' => 25,
                'endFilePos' => 197,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 10,
            'endLine' => 10,
            'startColumn' => 38,
            'endColumn' => 63,
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
 * Get the latest version from the update server
 */',
        'startLine' => 10,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Updater\\Services',
        'declaringClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'implementingClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'currentClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'aliasName' => NULL,
      ),
      'getCanUpdateMessages' => 
      array (
        'name' => 'getCanUpdateMessages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get messages about why the system can\'t be updated
 */',
        'startLine' => 28,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Updater\\Services',
        'declaringClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'implementingClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'currentClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'aliasName' => NULL,
      ),
      'copyStandaloneUpdater' => 
      array (
        'name' => 'copyStandaloneUpdater',
        'parameters' => 
        array (
          'updateCacheDir' => 
          array (
            'name' => 'updateCacheDir',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 43,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'skipUi' => 
          array (
            'name' => 'skipUi',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 91,
                'endLine' => 91,
                'startTokenPos' => 608,
                'startFilePos' => 2956,
                'endTokenPos' => 608,
                'endFilePos' => 2960,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 59,
            'endColumn' => 71,
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
 * Copy the standalone updater files to the public directory
 */',
        'startLine' => 91,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Updater\\Services',
        'declaringClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'implementingClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'currentClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'aliasName' => NULL,
      ),
      'generateStandaloneUpdaterFile' => 
      array (
        'name' => 'generateStandaloneUpdaterFile',
        'parameters' => 
        array (
          'stubsPath' => 
          array (
            'name' => 'stubsPath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 51,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'skipUi' => 
          array (
            'name' => 'skipUi',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 116,
                'endLine' => 116,
                'startTokenPos' => 730,
                'startFilePos' => 3787,
                'endTokenPos' => 730,
                'endFilePos' => 3791,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 63,
            'endColumn' => 77,
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
 * Generate the standalone updater file content
 */',
        'startLine' => 116,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Updater\\Services',
        'declaringClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'implementingClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
        'currentClassName' => 'Modules\\Updater\\Services\\UpdaterHelper',
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