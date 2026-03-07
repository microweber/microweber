<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/SetupCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Commands\SetupCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2abcd1e1a5c54fbaad658ff274a04c228e26eae7ef9bd1b3e708e9bcf52cf3b2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/SetupCommand.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Commands',
    'name' => 'Nwidart\\Modules\\Commands\\SetupCommand',
    'shortName' => 'SetupCommand',
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
    'endLine' => 74,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
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
      'name' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'name' => 'name',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'module:setup\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 30,
            'startFilePos' => 211,
            'endTokenPos' => 30,
            'endFilePos' => 224,
          ),
        ),
        'docComment' => '/**
 * The console command name.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Setting up modules folders for first use.\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 41,
            'startFilePos' => 339,
            'endTokenPos' => 41,
            'endFilePos' => 381,
          ),
        ),
        'docComment' => '/**
 * The console command description.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 73,
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
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Execute the console command.
 */',
        'startLine' => 26,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Commands',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'aliasName' => NULL,
      ),
      'generateModulesFolder' => 
      array (
        'name' => 'generateModulesFolder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the modules folder.
 */',
        'startLine' => 36,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Commands',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'aliasName' => NULL,
      ),
      'generateAssetsFolder' => 
      array (
        'name' => 'generateAssetsFolder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the assets folder.
 */',
        'startLine' => 48,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Commands',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'aliasName' => NULL,
      ),
      'generateDirectory' => 
      array (
        'name' => 'generateDirectory',
        'parameters' => 
        array (
          'dir' => 
          array (
            'name' => 'dir',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 42,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'success' => 
          array (
            'name' => 'success',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 48,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'error' => 
          array (
            'name' => 'error',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 58,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate the specified directory by given $dir.
 */',
        'startLine' => 60,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Commands',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\SetupCommand',
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