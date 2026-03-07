<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LaravelTemplates/Models/SystemTemplates.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\LaravelTemplates\Models\SystemTemplates
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-fb4c9bc63a154e12bd694954bed44acf71879a04c02d63b4d20b761b5de91938',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LaravelTemplates/Models/SystemTemplates.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\LaravelTemplates\\Models',
    'name' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
    'shortName' => 'SystemTemplates',
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
        'declaringClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'implementingClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'system_templates\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 30,
            'startFilePos' => 250,
            'endTokenPos' => 30,
            'endFilePos' => 267,
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
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'implementingClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
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
            'startFilePos' => 386,
            'endTokenPos' => 70,
            'endFilePos' => 553,
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
        'declaringClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'implementingClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
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
            'startFilePos' => 664,
            'endTokenPos' => 90,
            'endFilePos' => 701,
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
        'declaringClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'implementingClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
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
            'startFilePos' => 823,
            'endTokenPos' => 101,
            'endFilePos' => 826,
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
      'getActiveTemplates' => 
      array (
        'name' => 'getActiveTemplates',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get active templates.
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
        'namespace' => 'MicroweberPackages\\LaravelTemplates\\Models',
        'declaringClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'implementingClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'currentClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
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
 * Get template by name.
 *
 * @param string $name
 * @return \\MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates|null
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
        'namespace' => 'MicroweberPackages\\LaravelTemplates\\Models',
        'declaringClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'implementingClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
        'currentClassName' => 'MicroweberPackages\\LaravelTemplates\\Models\\SystemTemplates',
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