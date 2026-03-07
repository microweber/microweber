<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/TabularDataReader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\TabularDataReader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6c114c2c7c3d404d7d9c87d49ccd4eeb04e665ce7a363a1eea761852965cd056-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\TabularDataReader',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/TabularDataReader.php',
      ),
    ),
    'namespace' => 'League\\Csv',
    'name' => 'League\\Csv\\TabularDataReader',
    'shortName' => 'TabularDataReader',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Represents a Tabular data.
 *
 * @template TValue of array
 * @template-extends IteratorAggregate<array-key, TValue>
 *
 * @method Iterator fetchColumnByName(string $name) returns a column from its name
 * @method Iterator fetchColumnByOffset(int $offset) returns a column from its offset
 * @method mixed value(int|string $column = 0) returns a given value from the first element of the tabular data.
 * @method bool each(Closure $callback) iterates over each record and passes it to a closure. Iteration is interrupted if the closure returns false
 * @method bool exists(Closure $callback) tells whether at least one record satisfies the predicate.
 * @method mixed reduce(Closure $callback, mixed $initial = null) reduces the collection to a single value, passing the result of each iteration into the subsequent iteration
 * @method Iterator getObjects(string $className, array $header = []) Returns the tabular data records as an iterator object containing instance of the defined class name.
 * @method TabularDataReader filter(Query\\Predicate|Closure $predicate) returns all the elements of this collection for which your callback function returns `true`
 * @method TabularDataReader slice(int $offset, ?int $length = null) extracts a slice of $length elements starting at position $offset from the Collection.
 * @method TabularDataReader sorted(Query\\Sort|Closure $orderBy) sorts the Collection according to the closure provided see Statement::orderBy method
 * @method TabularDataReader select(string|int ...$columnOffsetOrName) extract a selection of the tabular data records columns.
 * @method TabularDataReader selectAllExcept(string|int ...$columnOffsetOrName) specifies the names or index of one or more columns to exclude from the selection of the tabular data records columns.
 * @method TabularDataReader matchingFirstOrFail(string $expression) extract the first found fragment identifier of the tabular data or fail
 * @method TabularDataReader|null matchingFirst(string $expression) extract the first found fragment identifier of the tabular data or return null if none is found
 * @method iterable<int, TabularDataReader> matching(string $expression) extract all found fragment identifiers for the tabular data
 * @method iterable<TabularDataReader> chunkBy(int $recordsCount) Chunk the TabulaDataReader into smaller TabularDataReader instances of the given size or less.
 * @method TabularDataReader mapHeader(array $headers) Returns a new TabulaDataReader with a new set of headers.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 46,
    'endLine' => 99,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'League\\Csv\\TabularData',
      1 => 'IteratorAggregate',
      2 => 'Countable',
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
      'getIterator' => 
      array (
        'name' => 'getIterator',
        'parameters' => 
        array (
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
 * Returns the tabular data rows as an iterator object containing flat array.
 *
 * Each row is represented as a simple array containing values.
 *
 * If the tabular data has a header included as a separate row then each record
 * is combined to the header record and the header record is removed from the iteration.
 *
 * If the tabular data is inconsistent. Missing fields are filled with null values
 * while extra record fields are strip from the returned array.
 *
 * @return Iterator<array-key, TValue>
 */',
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularDataReader',
        'implementingClassName' => 'League\\Csv\\TabularDataReader',
        'currentClassName' => 'League\\Csv\\TabularDataReader',
        'aliasName' => NULL,
      ),
      'count' => 
      array (
        'name' => 'count',
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
 * Returns the number of records contained in the tabular data structure
 * excluding the header record.
 */',
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularDataReader',
        'implementingClassName' => 'League\\Csv\\TabularDataReader',
        'currentClassName' => 'League\\Csv\\TabularDataReader',
        'aliasName' => NULL,
      ),
      'fetchPairs' => 
      array (
        'name' => 'fetchPairs',
        'parameters' => 
        array (
          'offset_index' => 
          array (
            'name' => 'offset_index',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 104,
                'startFilePos' => 4361,
                'endTokenPos' => 104,
                'endFilePos' => 4361,
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
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 32,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'value_index' => 
          array (
            'name' => 'value_index',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 115,
                'startFilePos' => 4390,
                'endTokenPos' => 115,
                'endFilePos' => 4390,
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
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 62,
            'endColumn' => 88,
            'parameterIndex' => 1,
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
 * Returns the next key-value pairs from the tabular data (first
 * column is the key, second column is the value).
 *
 * By default, if no column index is provided:
 * - the first column is used to provide the keys
 * - the second column is used to provide the value
 *
 * @param string|int $offset_index The column index to serve as offset
 * @param string|int $value_index The column index to serve as value
 *
 * @throws UnableToProcessCsv if the column index is invalid or not found
 */',
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 100,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularDataReader',
        'implementingClassName' => 'League\\Csv\\TabularDataReader',
        'currentClassName' => 'League\\Csv\\TabularDataReader',
        'aliasName' => NULL,
      ),
      'fetchOne' => 
      array (
        'name' => 'fetchOne',
        'parameters' => 
        array (
          'nth_record' => 
          array (
            'name' => 'nth_record',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 98,
                'endLine' => 98,
                'startTokenPos' => 150,
                'startFilePos' => 5001,
                'endTokenPos' => 150,
                'endFilePos' => 5001,
              ),
            ),
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
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 30,
            'endColumn' => 48,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'Deprecated',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '\'use League\\Csv\\TabularDataReader::nth() instead\'',
                'attributes' => 
                array (
                  'startLine' => 97,
                  'endLine' => 97,
                  'startTokenPos' => 129,
                  'startFilePos' => 4876,
                  'endTokenPos' => 129,
                  'endFilePos' => 4924,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.9.0\'',
                'attributes' => 
                array (
                  'startLine' => 97,
                  'endLine' => 97,
                  'startTokenPos' => 134,
                  'startFilePos' => 4933,
                  'endTokenPos' => 134,
                  'endFilePos' => 4950,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated since version 9.9.0
 *
 * Returns the nth record from the tabular data.
 *
 * By default, if no index is provided the first record of the tabular data is returned
 *
 * @param int $nth_record the tabular data record offset
 *
 * @throws UnableToProcessCsv if argument is less than 0
 */',
        'startLine' => 97,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularDataReader',
        'implementingClassName' => 'League\\Csv\\TabularDataReader',
        'currentClassName' => 'League\\Csv\\TabularDataReader',
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