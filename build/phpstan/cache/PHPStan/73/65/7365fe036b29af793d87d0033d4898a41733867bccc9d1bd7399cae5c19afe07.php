<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/Actions/ModelShowCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Commands\Actions\ModelShowCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-aeeb2da0d8d1af8b8ebe749b2cbaf99af97f8706d756570826556199ca87966f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/Actions/ModelShowCommand.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
    'name' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
    'shortName' => 'ModelShowCommand',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
      0 => 
      array (
        'name' => 'Symfony\\Component\\Console\\Attribute\\AsCommand',
        'isRepeated' => false,
        'arguments' => 
        array (
          0 => 
          array (
            'code' => '\'module:model-show\'',
            'attributes' => 
            array (
              'startLine' => 17,
              'endLine' => 17,
              'startTokenPos' => 64,
              'startFilePos' => 504,
              'endTokenPos' => 64,
              'endFilePos' => 522,
            ),
          ),
          1 => 
          array (
            'code' => '\'Show information about an Eloquent model in modules\'',
            'attributes' => 
            array (
              'startLine' => 17,
              'endLine' => 17,
              'startTokenPos' => 67,
              'startFilePos' => 525,
              'endTokenPos' => 67,
              'endFilePos' => 577,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 17,
    'endLine' => 111,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Console\\ShowModelCommand',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Console\\PromptsForMissingInput',
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
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'name' => 'name',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'module:model-show\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 93,
            'startFilePos' => 762,
            'endTokenPos' => 93,
            'endFilePos' => 780,
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
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Show information about an Eloquent model in modules\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 104,
            'startFilePos' => 895,
            'endTokenPos' => 104,
            'endFilePos' => 947,
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
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 83,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'signature' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'module:model-show {model : The model to show}
                {--database= : The database connection to use}
                {--json : Output the model as JSON}\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 41,
            'startTokenPos' => 115,
            'startFilePos' => 1058,
            'endTokenPos' => 115,
            'endFilePos' => 1219,
          ),
        ),
        'docComment' => '/**
 * The console command signature.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 53,
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
      'qualifyModel' => 
      array (
        'name' => 'qualifyModel',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Qualify the given model class base name.
 *
 * @see \\Illuminate\\Console\\GeneratorCommand
 */',
        'startLine' => 48,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'aliasName' => NULL,
      ),
      'formatModuleNamespace' => 
      array (
        'name' => 'formatModuleNamespace',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 69,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'aliasName' => NULL,
      ),
      'findModels' => 
      array (
        'name' => 'findModels',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 32,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 80,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'aliasName' => NULL,
      ),
      'promptForMissingArguments' => 
      array (
        'name' => 'promptForMissingArguments',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Console\\Input\\InputInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 50,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'output' => 
          array (
            'name' => 'output',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Console\\Output\\OutputInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 73,
            'endColumn' => 95,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 93,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
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