<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Testing/Concerns/InteractsWithDatabase.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d4cd7cab11e076a1920c9fd460374c8a47233d13713eee1e78f15e0ef0dd95d5-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Testing/Concerns/InteractsWithDatabase.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
    'name' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
    'shortName' => 'InteractsWithDatabase',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 337,
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
      'assertDatabaseHas' => 
      array (
        'name' => 'assertDatabaseHas',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 89,
                'startFilePos' => 987,
                'endTokenPos' => 90,
                'endFilePos' => 988,
              ),
            ),
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
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 97,
                'startFilePos' => 1005,
                'endTokenPos' => 97,
                'endFilePos' => 1008,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 68,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that a given where condition exists in the database.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  array<string, mixed>  $data
 * @param  string|null  $connection
 * @return $this
 */',
        'startLine' => 27,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertDatabaseMissing' => 
      array (
        'name' => 'assertDatabaseMissing',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 46,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 202,
                'startFilePos' => 1746,
                'endTokenPos' => 203,
                'endFilePos' => 1747,
              ),
            ),
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 54,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 210,
                'startFilePos' => 1764,
                'endTokenPos' => 210,
                'endFilePos' => 1767,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 72,
            'endColumn' => 89,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that a given where condition does not exist in the database.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  array<string, mixed>  $data
 * @param  string|null  $connection
 * @return $this
 */',
        'startLine' => 51,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertDatabaseCount' => 
      array (
        'name' => 'assertDatabaseCount',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 44,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 52,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 77,
                'endLine' => 77,
                'startTokenPos' => 330,
                'startFilePos' => 2525,
                'endTokenPos' => 330,
                'endFilePos' => 2528,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 64,
            'endColumn' => 81,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert the count of table entries.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  int  $count
 * @param  string|null  $connection
 * @return $this
 */',
        'startLine' => 77,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertDatabaseEmpty' => 
      array (
        'name' => 'assertDatabaseEmpty',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 44,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 391,
                'startFilePos' => 3029,
                'endTokenPos' => 391,
                'endFilePos' => 3032,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 52,
            'endColumn' => 69,
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
 * Assert that the given table has no entries.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  string|null  $connection
 * @return $this
 */',
        'startLine' => 93,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertSoftDeleted' => 
      array (
        'name' => 'assertSoftDeleted',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 111,
                'endLine' => 111,
                'startTokenPos' => 454,
                'startFilePos' => 3619,
                'endTokenPos' => 455,
                'endFilePos' => 3620,
              ),
            ),
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
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 111,
                'endLine' => 111,
                'startTokenPos' => 462,
                'startFilePos' => 3637,
                'endTokenPos' => 462,
                'endFilePos' => 3640,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 68,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'deletedAtColumn' => 
          array (
            'name' => 'deletedAtColumn',
            'default' => 
            array (
              'code' => '\'deleted_at\'',
              'attributes' => 
              array (
                'startLine' => 111,
                'endLine' => 111,
                'startTokenPos' => 469,
                'startFilePos' => 3662,
                'endTokenPos' => 469,
                'endFilePos' => 3673,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 88,
            'endColumn' => 118,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert the given record has been "soft deleted".
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  array<string, mixed>  $data
 * @param  string|null  $connection
 * @param  string|null  $deletedAtColumn
 * @return $this
 */',
        'startLine' => 111,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertNotSoftDeleted' => 
      array (
        'name' => 'assertNotSoftDeleted',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 613,
                'startFilePos' => 4732,
                'endTokenPos' => 614,
                'endFilePos' => 4733,
              ),
            ),
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
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 53,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 621,
                'startFilePos' => 4750,
                'endTokenPos' => 621,
                'endFilePos' => 4753,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 71,
            'endColumn' => 88,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'deletedAtColumn' => 
          array (
            'name' => 'deletedAtColumn',
            'default' => 
            array (
              'code' => '\'deleted_at\'',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 628,
                'startFilePos' => 4775,
                'endTokenPos' => 628,
                'endFilePos' => 4786,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 91,
            'endColumn' => 121,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert the given record has not been "soft deleted".
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  array<string, mixed>  $data
 * @param  string|null  $connection
 * @param  string|null  $deletedAtColumn
 * @return $this
 */',
        'startLine' => 143,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertModelExists' => 
      array (
        'name' => 'assertModelExists',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
            'startColumn' => 42,
            'endColumn' => 47,
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
 * Assert the given model exists in the database.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $model
 * @return $this
 */',
        'startLine' => 172,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'assertModelMissing' => 
      array (
        'name' => 'assertModelMissing',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 43,
            'endColumn' => 48,
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
 * Assert the given model does not exist in the database.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $model
 * @return $this
 */',
        'startLine' => 183,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'expectsDatabaseQueryCount' => 
      array (
        'name' => 'expectsDatabaseQueryCount',
        'parameters' => 
        array (
          'expected' => 
          array (
            'name' => 'expected',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 47,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 195,
                'endLine' => 195,
                'startTokenPos' => 820,
                'startFilePos' => 6370,
                'endTokenPos' => 820,
                'endFilePos' => 6373,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 58,
            'endColumn' => 75,
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
 * Specify the number of database queries that should occur throughout the test.
 *
 * @param  int  $expected
 * @param  string|null  $connection
 * @return $this
 */',
        'startLine' => 195,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'isSoftDeletableModel' => 
      array (
        'name' => 'isSoftDeletableModel',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 45,
            'endColumn' => 50,
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
 * Determine if the argument is a soft deletable model.
 *
 * @param  mixed  $model
 * @return bool
 */',
        'startLine' => 224,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'castAsJson' => 
      array (
        'name' => 'castAsJson',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 32,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 237,
                'endLine' => 237,
                'startTokenPos' => 1044,
                'startFilePos' => 7800,
                'endTokenPos' => 1044,
                'endFilePos' => 7803,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 40,
            'endColumn' => 57,
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
 * Cast a JSON string to a database compatible type.
 *
 * @param  array|object|string  $value
 * @param  string|null  $connection
 * @return \\Illuminate\\Contracts\\Database\\Query\\Expression
 */',
        'startLine' => 237,
        'endLine' => 252,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'getConnection' => 
      array (
        'name' => 'getConnection',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 261,
                'endLine' => 261,
                'startTokenPos' => 1167,
                'startFilePos' => 8518,
                'endTokenPos' => 1167,
                'endFilePos' => 8521,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 38,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 261,
                'endLine' => 261,
                'startTokenPos' => 1174,
                'startFilePos' => 8533,
                'endTokenPos' => 1174,
                'endFilePos' => 8536,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 58,
            'endColumn' => 70,
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
 * Get the database connection.
 *
 * @param  string|null  $connection
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @return \\Illuminate\\Database\\Connection
 */',
        'startLine' => 261,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'getTable' => 
      array (
        'name' => 'getTable',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Get the table name from the given model or string.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @return string
 */',
        'startLine' => 276,
        'endLine' => 283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'getTableConnection' => 
      array (
        'name' => 'getTableConnection',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 291,
            'endLine' => 291,
            'startColumn' => 43,
            'endColumn' => 48,
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
 * Get the table connection specified in the given model.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @return string|null
 */',
        'startLine' => 291,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'getDeletedAtColumn' => 
      array (
        'name' => 'getDeletedAtColumn',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 307,
            'endLine' => 307,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'defaultColumnName' => 
          array (
            'name' => 'defaultColumnName',
            'default' => 
            array (
              'code' => '\'deleted_at\'',
              'attributes' => 
              array (
                'startLine' => 307,
                'endLine' => 307,
                'startTokenPos' => 1355,
                'startFilePos' => 9969,
                'endTokenPos' => 1355,
                'endFilePos' => 9980,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 307,
            'endLine' => 307,
            'startColumn' => 51,
            'endColumn' => 83,
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
 * Get the table column name used for soft deletes.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @param  string  $defaultColumnName
 * @return string
 */',
        'startLine' => 307,
        'endLine' => 310,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'newModelFor' => 
      array (
        'name' => 'newModelFor',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 36,
            'endColumn' => 41,
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
 * Get the model entity from the given model or string.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|class-string<\\Illuminate\\Database\\Eloquent\\Model>|string  $table
 * @return \\Illuminate\\Database\\Eloquent\\Model|null
 */',
        'startLine' => 318,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'aliasName' => NULL,
      ),
      'seed' => 
      array (
        'name' => 'seed',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => 
            array (
              'code' => '\'Database\\Seeders\\DatabaseSeeder\'',
              'attributes' => 
              array (
                'startLine' => 329,
                'endLine' => 329,
                'startTokenPos' => 1433,
                'startFilePos' => 10674,
                'endTokenPos' => 1433,
                'endFilePos' => 10708,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 329,
            'endLine' => 329,
            'startColumn' => 26,
            'endColumn' => 69,
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
 * Seed a given database connection.
 *
 * @param  list<string>|class-string<\\Illuminate\\Database\\Seeder>|string  $class
 * @return $this
 */',
        'startLine' => 329,
        'endLine' => 336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Testing\\Concerns',
        'declaringClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'implementingClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
        'currentClassName' => 'Illuminate\\Foundation\\Testing\\Concerns\\InteractsWithDatabase',
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