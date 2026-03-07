<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Process/Updater.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Process\Updater
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-fbf39347332d20b04af87e67cde6343260ed76ca4add5545d7d885b28897cc57-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Process\\Updater',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Process/Updater.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Process',
    'name' => 'Nwidart\\Modules\\Process\\Updater',
    'shortName' => 'Updater',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 80,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Nwidart\\Modules\\Process\\Runner',
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
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          'module' => 
          array (
            'name' => 'module',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 14,
            'endLine' => 14,
            'startColumn' => 28,
            'endColumn' => 34,
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
 * Update the dependencies for the specified module by given the module name.
 *
 * @param  string  $module
 */',
        'startLine' => 14,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Process',
        'declaringClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'implementingClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'currentClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'aliasName' => NULL,
      ),
      'isComposerSilenced' => 
      array (
        'name' => 'isComposerSilenced',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if composer should output anything.
 *
 * @return string
 */',
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Process',
        'declaringClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'implementingClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'currentClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'aliasName' => NULL,
      ),
      'installRequires' => 
      array (
        'name' => 'installRequires',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 38,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 35,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Process',
        'declaringClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'implementingClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'currentClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'aliasName' => NULL,
      ),
      'installDevRequires' => 
      array (
        'name' => 'installDevRequires',
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
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 41,
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
        'docComment' => NULL,
        'startLine' => 49,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Process',
        'declaringClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'implementingClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'currentClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'aliasName' => NULL,
      ),
      'copyScriptsToMainComposerJson' => 
      array (
        'name' => 'copyScriptsToMainComposerJson',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 52,
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
        'docComment' => NULL,
        'startLine' => 63,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Process',
        'declaringClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'implementingClassName' => 'Nwidart\\Modules\\Process\\Updater',
        'currentClassName' => 'Nwidart\\Modules\\Process\\Updater',
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