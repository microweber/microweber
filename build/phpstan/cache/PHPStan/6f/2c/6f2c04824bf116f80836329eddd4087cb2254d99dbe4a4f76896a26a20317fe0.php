<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/Database/MigrateRollbackCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Nwidart\Modules\Commands\Database\MigrateRollbackCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5855c8544fd032bcf3035dd2649accb18b0f7f5284e02f3b692b835e7bcad8cf-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../nwidart/laravel-modules/src/Commands/Database/MigrateRollbackCommand.php',
      ),
    ),
    'namespace' => 'Nwidart\\Modules\\Commands\\Database',
    'name' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
    'shortName' => 'MigrateRollbackCommand',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 74,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Nwidart\\Modules\\Commands\\BaseCommand',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Nwidart\\Modules\\Traits\\MigrationLoaderTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'name' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'name' => 'name',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'module:migrate-rollback\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 50,
            'startFilePos' => 414,
            'endTokenPos' => 50,
            'endFilePos' => 438,
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
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 48,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Rollback the modules migrations.\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 61,
            'startFilePos' => 553,
            'endTokenPos' => 61,
            'endFilePos' => 586,
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
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 64,
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
      'executeAction' => 
      array (
        'name' => 'executeAction',
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
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 35,
            'endColumn' => 39,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Commands\\Database',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'aliasName' => NULL,
      ),
      'getInfo' => 
      array (
        'name' => 'getInfo',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Nwidart\\Modules\\Commands\\Database',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'aliasName' => NULL,
      ),
      'getOptions' => 
      array (
        'name' => 'getOptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the console command options.
 *
 * @return array
 */',
        'startLine' => 64,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Nwidart\\Modules\\Commands\\Database',
        'declaringClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'implementingClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
        'currentClassName' => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
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