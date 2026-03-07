<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/actions/src/Exports/Models/Export.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Actions\Exports\Models\Export
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d52086d35451927e210bdbd84ca4346a73b51221d3457449033a15a172414c26-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Actions\\Exports\\Models\\Export',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/actions/src/Exports/Models/Export.php',
      ),
    ),
    'namespace' => 'Filament\\Actions\\Exports\\Models',
    'name' => 'Filament\\Actions\\Exports\\Models\\Export',
    'shortName' => 'Export',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property CarbonInterface | null $completed_at
 * @property string $file_disk
 * @property string $file_name
 * @property class-string<Exporter> $exporter
 * @property int $processed_rows
 * @property int $total_rows
 * @property int $successful_rows
 * @property-read Authenticatable $user
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 119,
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
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'name' => 'guarded',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 127,
            'startFilePos' => 1087,
            'endTokenPos' => 128,
            'endFilePos' => 1088,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
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
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
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
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 141,
            'startFilePos' => 1152,
            'endTokenPos' => 141,
            'endFilePos' => 1156,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
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
        'startLine' => 32,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
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
        'startLine' => 46,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'aliasName' => NULL,
      ),
      'getExporter' => 
      array (
        'name' => 'getExporter',
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
            'startLine' => 75,
            'endLine' => 75,
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
            'startLine' => 76,
            'endLine' => 76,
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
            'name' => 'Filament\\Actions\\Exports\\Exporter',
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
        'startLine' => 74,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
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
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
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
                'startLine' => 90,
                'endLine' => 90,
                'startTokenPos' => 388,
                'startFilePos' => 2567,
                'endTokenPos' => 388,
                'endFilePos' => 2570,
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
            'startLine' => 90,
            'endLine' => 90,
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
        'startLine' => 90,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
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
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'aliasName' => NULL,
      ),
      'getFileDisk' => 
      array (
        'name' => 'getFileDisk',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Contracts\\Filesystem\\Filesystem',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'aliasName' => NULL,
      ),
      'getFileDirectory' => 
      array (
        'name' => 'getFileDirectory',
        'parameters' => 
        array (
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
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'aliasName' => NULL,
      ),
      'deleteFileDirectory' => 
      array (
        'name' => 'deleteFileDirectory',
        'parameters' => 
        array (
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
        'startLine' => 110,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Actions\\Exports\\Models',
        'declaringClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'implementingClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
        'currentClassName' => 'Filament\\Actions\\Exports\\Models\\Export',
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