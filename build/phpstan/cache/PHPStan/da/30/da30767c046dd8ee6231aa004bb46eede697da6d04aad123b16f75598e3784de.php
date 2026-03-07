<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/actions/src/Imports/Models/Import.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Actions\Imports\Models\Import
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b621cbc8d3d136a72623dc63d0bbc97b8e41efc85af7fe70090da3a66ecd264f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Actions\\Imports\\Models\\Import',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/actions/src/Imports/Models/Import.php',
      ),
    ),
    'namespace' => 'Filament\\Actions\\Imports\\Models',
    'name' => 'Filament\\Actions\\Imports\\Models\\Import',
    'shortName' => 'Import',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property CarbonInterface | null $completed_at
 * @property string $file_name
 * @property string $file_path
 * @property class-string<Importer> $importer
 * @property int $processed_rows
 * @property int $total_rows
 * @property int $successful_rows
 * @property-read Collection<FailedImportRow> $failedRows
 * @property-read Authenticatable $user
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 105,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Prunable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'guarded' => 
      array (
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'name' => 'guarded',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 127,
            'startFilePos' => 1154,
            'endTokenPos' => 128,
            'endFilePos' => 1155,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hasPolymorphicUserRelationship' => 
      array (
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'name' => 'hasPolymorphicUserRelationship',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 141,
            'startFilePos' => 1219,
            'endTokenPos' => 141,
            'endFilePos' => 1223,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 66,
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
      'casts' => 
      array (
        'name' => 'casts',
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
 * @return array<string, string>
 */',
        'startLine' => 33,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'aliasName' => NULL,
      ),
      'failedRows' => 
      array (
        'name' => 'failedRows',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'aliasName' => NULL,
      ),
      'user' => 
      array (
        'name' => 'user',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 52,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'aliasName' => NULL,
      ),
      'getImporter' => 
      array (
        'name' => 'getImporter',
        'parameters' => 
        array (
          'columnMap' => 
          array (
            'name' => 'columnMap',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 9,
            'endColumn' => 24,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 9,
            'endColumn' => 22,
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
            'name' => 'Filament\\Actions\\Imports\\Importer',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, string>  $columnMap
 * @param  array<string, mixed>  $options
 */',
        'startLine' => 80,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'aliasName' => NULL,
      ),
      'getFailedRowsCount' => 
      array (
        'name' => 'getFailedRowsCount',
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
        'docComment' => NULL,
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'aliasName' => NULL,
      ),
      'polymorphicUserRelationship' => 
      array (
        'name' => 'polymorphicUserRelationship',
        'parameters' => 
        array (
          'condition' => 
          array (
            'name' => 'condition',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 96,
                'endLine' => 96,
                'startTokenPos' => 420,
                'startFilePos' => 2756,
                'endTokenPos' => 420,
                'endFilePos' => 2759,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 56,
            'endColumn' => 77,
            'parameterIndex' => 0,
            'isOptional' => true,
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
        'startLine' => 96,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'aliasName' => NULL,
      ),
      'hasPolymorphicUserRelationship' => 
      array (
        'name' => 'hasPolymorphicUserRelationship',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Filament\\Actions\\Imports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'implementingClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
        'currentClassName' => 'Filament\\Actions\\Imports\\Models\\Import',
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