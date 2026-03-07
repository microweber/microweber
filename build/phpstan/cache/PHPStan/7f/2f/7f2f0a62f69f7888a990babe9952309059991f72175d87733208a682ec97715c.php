<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LaravelModules/Models/SystemModules.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\LaravelModules\Models\SystemModules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-4f2438a2b62793d03017c72a95b12fa7c1ff86ebf05ebca2c563cf2611436ad0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LaravelModules/Models/SystemModules.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\LaravelModules\\Models',
    'name' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
    'shortName' => 'SystemModules',
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
    'endLine' => 69,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
      'table' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'implementingClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'system_modules\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 30,
            'startFilePos' => 246,
            'endTokenPos' => 30,
            'endFilePos' => 261,
          ),
        ),
        'docComment' => '/**
 * The table associated with the model.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'implementingClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'alias\', \'description\', \'path\', \'version\', \'type\', \'priority\', \'sort\', \'status\']',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 31,
            'startTokenPos' => 41,
            'startFilePos' => 380,
            'endTokenPos' => 70,
            'endFilePos' => 547,
          ),
        ),
        'docComment' => '/**
 * The attributes that are mass assignable.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'implementingClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'status\' => \'boolean\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 40,
            'startTokenPos' => 81,
            'startFilePos' => 658,
            'endTokenPos' => 90,
            'endFilePos' => 695,
          ),
        ),
        'docComment' => '/**
 * The attributes that should be cast.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timestamps' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'implementingClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'name' => 'timestamps',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 101,
            'startFilePos' => 817,
            'endTokenPos' => 101,
            'endFilePos' => 820,
          ),
        ),
        'docComment' => '/**
 * Indicates if the model should be timestamped.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 30,
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
      'getActiveModules' => 
      array (
        'name' => 'getActiveModules',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get active modules.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Collection
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\LaravelModules\\Models',
        'declaringClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'implementingClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'currentClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'aliasName' => NULL,
      ),
      'getByName' => 
      array (
        'name' => 'getByName',
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
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 38,
            'endColumn' => 42,
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
 * Get module by name.
 *
 * @param string $name
 * @return \\MicroweberPackages\\LaravelModules\\Models\\SystemModules|null
 */',
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\LaravelModules\\Models',
        'declaringClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'implementingClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
        'currentClassName' => 'MicroweberPackages\\LaravelModules\\Models\\SystemModules',
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