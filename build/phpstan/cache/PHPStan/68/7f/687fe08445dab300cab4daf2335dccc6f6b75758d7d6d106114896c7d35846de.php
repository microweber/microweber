<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/TabularData.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\TabularData
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2dbd92e6e5d98eaf76c3f554d0dc03744e2d6108af45a0f08cac6e425b57ce42-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\TabularData',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/TabularData.php',
      ),
    ),
    'namespace' => 'League\\Csv',
    'name' => 'League\\Csv\\TabularData',
    'shortName' => 'TabularData',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method array nth(int $nth) returns the nth record from the tabular data.
 * @method object|null nthAsObject(int $nth, string $className, array $header = []) returns the nth record from the tabular data as an instance of the defined class name.
 * @method array first() returns the first record from the tabular data.
 * @method object|null firstAsObject(string $className, array $header = []) returns the last record from the tabular data as an instance of the defined class name.
 * @method array last() returns the first record from the tabular data.
 * @method object|null lastAsObject(string $className, array $header = []) returns the last record from the tabular data as an instance of the defined class name.
 * @method Iterator map(callable $callback) Run a map over each container record.
 * @method Iterator getRecordsAsObject(string $className, array $header = []) Returns the tabular data records as an iterator object containing instance of the defined class name.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 70,
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
      'getHeader' => 
      array (
        'name' => 'getHeader',
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
 * Returns the header associated with the tabular data.
 *
 * The header must contain unique string or be an empty array
 * if no header is specified.
 *
 * @return array<string>
 */',
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularData',
        'implementingClassName' => 'League\\Csv\\TabularData',
        'currentClassName' => 'League\\Csv\\TabularData',
        'aliasName' => NULL,
      ),
      'getRecords' => 
      array (
        'name' => 'getRecords',
        'parameters' => 
        array (
          'header' => 
          array (
            'name' => 'header',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 56,
                'endLine' => 56,
                'startTokenPos' => 58,
                'startFilePos' => 2328,
                'endTokenPos' => 59,
                'endFilePos' => 2329,
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 32,
            'endColumn' => 49,
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
            'name' => 'Iterator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the tabular data records as an iterator object.
 *
 * Each record is represented as a simple array containing strings or null values.
 *
 * If the tabular data has a header record then each record is combined
 * to the header record and the header record is removed from the iterator.
 *
 * If the tabular data is inconsistent. Missing record fields are
 * filled with null values while extra record fields are strip from
 * the returned object.
 *
 * @param array<int, string> $header an optional header mapper to use instead of the tabular data header
 *
 * @return Iterator<array-key, array<array-key, mixed>>
 */',
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 61,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularData',
        'implementingClassName' => 'League\\Csv\\TabularData',
        'currentClassName' => 'League\\Csv\\TabularData',
        'aliasName' => NULL,
      ),
      'fetchColumn' => 
      array (
        'name' => 'fetchColumn',
        'parameters' => 
        array (
          'index' => 
          array (
            'name' => 'index',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 69,
                'endLine' => 69,
                'startTokenPos' => 82,
                'startFilePos' => 2747,
                'endTokenPos' => 82,
                'endFilePos' => 2747,
              ),
            ),
            'type' => 
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
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
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
            'startColumn' => 33,
            'endColumn' => 53,
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
            'name' => 'Iterator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a single column from the next record of the tabular data.
 *
 * By default, if no value is supplied the first column is fetched
 *
 * @param string|int $index CSV column index
 *
 * @throws UnableToProcessCsv if the column index is invalid or not found
 *
 * @return Iterator<int, mixed>
 */',
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularData',
        'implementingClassName' => 'League\\Csv\\TabularData',
        'currentClassName' => 'League\\Csv\\TabularData',
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