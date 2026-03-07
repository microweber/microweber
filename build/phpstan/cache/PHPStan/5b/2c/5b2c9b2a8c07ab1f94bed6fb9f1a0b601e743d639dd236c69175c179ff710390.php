<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/Actions/ModelPruneCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Commands\Actions\ModelPruneCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b94cce7bd433358f324085fb2b41c7e0a032af163cb3cdd4b62aa1d08362b2aa-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/Actions/ModelPruneCommand.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
    'name' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
    'shortName' => 'ModelPruneCommand',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 121,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Console\\PruneCommand',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Console\\PromptsForMissingInput',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ALL' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'name' => 'ALL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'All\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 81,
            'startFilePos' => 575,
            'endTokenPos' => 81,
            'endFilePos' => 579,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
    ),
    'immediateProperties' => 
    array (
      'name' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'name' => 'name',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'module:prune\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 90,
            'startFilePos' => 605,
            'endTokenPos' => 90,
            'endFilePos' => 618,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'signature' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'module:prune {module*}
                                {--all : Check all Modules}
                                {--model=* : Class names of the models to be pruned}
                                {--except=* : Class names of the models to be excluded from pruning}
                                {--path=* : Absolute path(s) to directories where models are located}
                                {--chunk=1000 : The number of models to retrieve per chunk of models to be deleted}
                                {--pretend : Display the number of prunable records found instead of deleting them}\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 34,
            'startTokenPos' => 101,
            'startFilePos' => 724,
            'endTokenPos' => 101,
            'endFilePos' => 1327,
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
        'startLine' => 28,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 117,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Prune models by module that are no longer needed\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 112,
            'startFilePos' => 1442,
            'endTokenPos' => 112,
            'endFilePos' => 1491,
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
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 80,
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
            'startLine' => 43,
            'endLine' => 43,
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
            'startLine' => 43,
            'endLine' => 43,
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
        'startLine' => 43,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'aliasName' => NULL,
      ),
      'models' => 
      array (
        'name' => 'models',
        'parameters' => 
        array (
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
        'docComment' => '/**
 * Determine the models that should be pruned.
 */',
        'startLine' => 71,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Commands\\Actions',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
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