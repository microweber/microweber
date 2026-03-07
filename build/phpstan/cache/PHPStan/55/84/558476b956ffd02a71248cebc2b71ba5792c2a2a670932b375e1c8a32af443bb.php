<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/TabularDataWriter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\TabularDataWriter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4dd484ed5c83c3bcb332488a3d1bd9136cbffd046b47895498a2083073325b6c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\TabularDataWriter',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/TabularDataWriter.php',
      ),
    ),
    'namespace' => 'League\\Csv',
    'name' => 'League\\Csv\\TabularDataWriter',
    'shortName' => 'TabularDataWriter',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A class to insert records into a CSV Document.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 47,
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
      'insertAll' => 
      array (
        'name' => 'insertAll',
        'parameters' => 
        array (
          'records' => 
          array (
            'name' => 'records',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 31,
            'endColumn' => 47,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds multiple records to the CSV document.
 *
 * @see TabularDataWriter::insertOne
 *
 * @param iterable<array<null|int|float|string|Stringable>> $records
 *
 * @throws CannotInsertRecord If the record can not be inserted
 * @throws Exception If the record can not be inserted
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularDataWriter',
        'implementingClassName' => 'League\\Csv\\TabularDataWriter',
        'currentClassName' => 'League\\Csv\\TabularDataWriter',
        'aliasName' => NULL,
      ),
      'insertOne' => 
      array (
        'name' => 'insertOne',
        'parameters' => 
        array (
          'record' => 
          array (
            'name' => 'record',
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
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 31,
            'endColumn' => 43,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds a single record to a CSV document.
 *
 * A record is an array that can contain scalar type values, NULL values
 * or objects implementing the __toString method.
 *
 * @param array<mixed> $record
 *
 * @throws CannotInsertRecord If the record can not be inserted
 * @throws Exception If the record can not be inserted
 */',
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\TabularDataWriter',
        'implementingClassName' => 'League\\Csv\\TabularDataWriter',
        'currentClassName' => 'League\\Csv\\TabularDataWriter',
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