<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/Writer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\Writer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-01b655612b4bed1c348557b7f490093473fec6e47f8fa2f607d9d00197835608-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\Writer',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/Writer.php',
      ),
    ),
    'namespace' => 'League\\Csv',
    'name' => 'League\\Csv\\Writer',
    'shortName' => 'Writer',
    'isInterface' => false,
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
    'startLine' => 29,
    'endLine' => 352,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'League\\Csv\\AbstractCsv',
    'implementsClassNames' => 
    array (
      0 => 'League\\Csv\\TabularDataWriter',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ENCLOSE_ALL' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'ENCLOSE_ALL',
        'modifiers' => 2,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 86,
            'startFilePos' => 614,
            'endTokenPos' => 86,
            'endFilePos' => 614,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'ENCLOSE_NECESSARY' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'ENCLOSE_NECESSARY',
        'modifiers' => 2,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 97,
            'startFilePos' => 657,
            'endTokenPos' => 97,
            'endFilePos' => 657,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'ENCLOSE_NONE' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'ENCLOSE_NONE',
        'modifiers' => 2,
        'type' => NULL,
        'value' => 
        array (
          'code' => '-1',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 108,
            'startFilePos' => 695,
            'endTokenPos' => 109,
            'endFilePos' => 696,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'STREAM_FILTER_MODE' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'STREAM_FILTER_MODE',
        'modifiers' => 2,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\STREAM_FILTER_WRITE',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 120,
            'startFilePos' => 741,
            'endTokenPos' => 120,
            'endFilePos' => 759,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
    ),
    'immediateProperties' => 
    array (
      'validators' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'validators',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 133,
            'startFilePos' => 901,
            'endTokenPos' => 134,
            'endFilePos' => 902,
          ),
        ),
        'docComment' => '/** @var array<Closure(array): bool> callable collection to validate the record before insertion. */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'newline' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'newline',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '"\\n"',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 145,
            'startFilePos' => 937,
            'endTokenPos' => 145,
            'endFilePos' => 940,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'flush_counter' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'flush_counter',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 156,
            'startFilePos' => 978,
            'endTokenPos' => 156,
            'endFilePos' => 978,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'flush_threshold' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'flush_threshold',
        'modifiers' => 2,
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
                  'name' => 'int',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 168,
            'startFilePos' => 1019,
            'endTokenPos' => 168,
            'endFilePos' => 1022,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'enclose_all' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'enclose_all',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'self::ENCLOSE_NECESSARY',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 179,
            'startFilePos' => 1058,
            'endTokenPos' => 181,
            'endFilePos' => 1080,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 57,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'enclosure_replace' => 
      array (
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'name' => 'enclosure_replace',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[[], []]',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 194,
            'startFilePos' => 1179,
            'endTokenPos' => 201,
            'endFilePos' => 1186,
          ),
        ),
        'docComment' => '/** @var array{0:array<string>,1:array<string>} */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 50,
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
      'resetProperties' => 
      array (
        'name' => 'resetProperties',
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
        'startLine' => 45,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'insertRecord' => 
      array (
        'name' => 'insertRecord',
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
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
        'startLine' => 55,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'getEndOfLine' => 
      array (
        'name' => 'getEndOfLine',
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
        'docComment' => '/**
 * Returns the current end of line sequence characters.
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'getFlushThreshold' => 
      array (
        'name' => 'getFlushThreshold',
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
                  'name' => 'int',
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
        'docComment' => '/**
 * Returns the flush threshold.
 */',
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'encloseAll' => 
      array (
        'name' => 'encloseAll',
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
        'docComment' => '/**
 * Tells whether new entries will all be enclosed on writing.
 */',
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'encloseNecessary' => 
      array (
        'name' => 'encloseNecessary',
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
        'docComment' => '/**
 * Tells whether new entries will be selectively enclosed on writing
 * if the field content requires encoding.
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'encloseNone' => 
      array (
        'name' => 'encloseNone',
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
        'docComment' => '/**
 * Tells whether new entries will never be enclosed on writing.
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
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
                      'name' => 'League\\Csv\\TabularDataProvider',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'League\\Csv\\TabularData',
                      'isIdentifier' => false,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'iterable',
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
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 31,
            'endColumn' => 79,
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
 * @see Writer::insertOne
 *
 * @throws CannotInsertRecord
 * @throws Exception
 */',
        'startLine' => 118,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
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
            'startLine' => 148,
            'endLine' => 148,
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
 * @throws CannotInsertRecord If the record can not be inserted
 * @throws Exception If the record can not be inserted
 */',
        'startLine' => 148,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'validateRecord' => 
      array (
        'name' => 'validateRecord',
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
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 39,
            'endColumn' => 51,
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
        'docComment' => '/**
 * Validates a record.
 *
 * @throws CannotInsertRecord If the validation failed
 */',
        'startLine' => 176,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'addValidator' => 
      array (
        'name' => 'addValidator',
        'parameters' => 
        array (
          'validator' => 
          array (
            'name' => 'validator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 34,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'validator_name' => 
          array (
            'name' => 'validator_name',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 55,
            'endColumn' => 76,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds a record validator.
 *
 * @param callable(array): bool $validator
 */',
        'startLine' => 188,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'setEndOfLine' => 
      array (
        'name' => 'setEndOfLine',
        'parameters' => 
        array (
          'endOfLine' => 
          array (
            'name' => 'endOfLine',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 34,
            'endColumn' => 50,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the end of line sequence.
 */',
        'startLine' => 198,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'setFlushThreshold' => 
      array (
        'name' => 'setFlushThreshold',
        'parameters' => 
        array (
          'threshold' => 
          array (
            'name' => 'threshold',
            'default' => NULL,
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
                      'name' => 'int',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 210,
            'endLine' => 210,
            'startColumn' => 39,
            'endColumn' => 53,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the flush threshold.
 *
 * @throws InvalidArgument if the threshold is an integer less than 1
 */',
        'startLine' => 210,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'necessaryEnclosure' => 
      array (
        'name' => 'necessaryEnclosure',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 225,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'forceEnclosure' => 
      array (
        'name' => 'forceEnclosure',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 233,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'noEnclosure' => 
      array (
        'name' => 'noEnclosure',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 241,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'formatRecord' => 
      array (
        'name' => 'formatRecord',
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
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'formatter' => 
          array (
            'name' => 'formatter',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 52,
            'endColumn' => 70,
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
                'code' => '\'no longer affecting the class behaviour\'',
                'attributes' => 
                array (
                  'startLine' => 262,
                  'endLine' => 262,
                  'startTokenPos' => 1281,
                  'startFilePos' => 7261,
                  'endTokenPos' => 1281,
                  'endFilePos' => 7301,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.8.0\'',
                'attributes' => 
                array (
                  'startLine' => 262,
                  'endLine' => 262,
                  'startTokenPos' => 1286,
                  'startFilePos' => 7310,
                  'endTokenPos' => 1286,
                  'endFilePos' => 7327,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated since version 9.8.0
 * @codeCoverageIgnore
 *
 * Format a record.
 *
 * The returned array must contain
 *   - scalar types values,
 *   - NULL values,
 *   - or objects implementing the __toString() method.
 */',
        'startLine' => 262,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'addRecord' => 
      array (
        'name' => 'addRecord',
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
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
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
                'code' => '\'no longer affecting the class behaviour\'',
                'attributes' => 
                array (
                  'startLine' => 278,
                  'endLine' => 278,
                  'startTokenPos' => 1328,
                  'startFilePos' => 7802,
                  'endTokenPos' => 1328,
                  'endFilePos' => 7842,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.9.0\'',
                'attributes' => 
                array (
                  'startLine' => 278,
                  'endLine' => 278,
                  'startTokenPos' => 1333,
                  'startFilePos' => 7851,
                  'endTokenPos' => 1333,
                  'endFilePos' => 7868,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated Since version 9.9.0
 * @codeCoverageIgnore
 *
 * Adds a single record to a CSV Document using PHP algorithm.
 *
 * @see https://php.net/manual/en/function.fputcsv.php
 */',
        'startLine' => 278,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'consolidate' => 
      array (
        'name' => 'consolidate',
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
          0 => 
          array (
            'name' => 'Deprecated',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '\'no longer affecting the class behaviour\'',
                'attributes' => 
                array (
                  'startLine' => 292,
                  'endLine' => 292,
                  'startTokenPos' => 1396,
                  'startFilePos' => 8310,
                  'endTokenPos' => 1396,
                  'endFilePos' => 8350,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.9.0\'',
                'attributes' => 
                array (
                  'startLine' => 292,
                  'endLine' => 292,
                  'startTokenPos' => 1401,
                  'startFilePos' => 8359,
                  'endTokenPos' => 1401,
                  'endFilePos' => 8376,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated Since version 9.9.0
 * @codeCoverageIgnore
 *
 * Applies post insertion actions.
 */',
        'startLine' => 292,
        'endLine' => 306,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'getNewline' => 
      array (
        'name' => 'getNewline',
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
          0 => 
          array (
            'name' => 'Deprecated',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '\'use League\\Csv\\Writer::getEndOfLine()\'',
                'attributes' => 
                array (
                  'startLine' => 317,
                  'endLine' => 317,
                  'startTokenPos' => 1499,
                  'startFilePos' => 9022,
                  'endTokenPos' => 1499,
                  'endFilePos' => 9060,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.10.0\'',
                'attributes' => 
                array (
                  'startLine' => 317,
                  'endLine' => 317,
                  'startTokenPos' => 1504,
                  'startFilePos' => 9069,
                  'endTokenPos' => 1504,
                  'endFilePos' => 9087,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @see Writer::getEndOfLine()
 * @deprecated Since version 9.10.0
 * @codeCoverageIgnore
 *
 * Returns the current newline sequence characters.
 */',
        'startLine' => 317,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'setNewline' => 
      array (
        'name' => 'setNewline',
        'parameters' => 
        array (
          'newline' => 
          array (
            'name' => 'newline',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 32,
            'endColumn' => 46,
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
            'name' => 'self',
            'isIdentifier' => false,
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
                'code' => '\'use League\\Csv\\Writer::setEndOfLine()\'',
                'attributes' => 
                array (
                  'startLine' => 332,
                  'endLine' => 332,
                  'startTokenPos' => 1539,
                  'startFilePos' => 9463,
                  'endTokenPos' => 1539,
                  'endFilePos' => 9501,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.10.0\'',
                'attributes' => 
                array (
                  'startLine' => 332,
                  'endLine' => 332,
                  'startTokenPos' => 1544,
                  'startFilePos' => 9510,
                  'endTokenPos' => 1544,
                  'endFilePos' => 9528,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @see Writer::setEndOfLine()
 * @deprecated Since version 9.10.0
 * @codeCoverageIgnore
 *
 * Sets the newline sequence.
 */',
        'startLine' => 332,
        'endLine' => 336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
        'aliasName' => NULL,
      ),
      'relaxEnclosure' => 
      array (
        'name' => 'relaxEnclosure',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
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
                'code' => '\'use League\\Csv\\Writer::necessaryEnclosure()\'',
                'attributes' => 
                array (
                  'startLine' => 347,
                  'endLine' => 347,
                  'startTokenPos' => 1583,
                  'startFilePos' => 9967,
                  'endTokenPos' => 1583,
                  'endFilePos' => 10011,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.22.0\'',
                'attributes' => 
                array (
                  'startLine' => 347,
                  'endLine' => 347,
                  'startTokenPos' => 1588,
                  'startFilePos' => 10020,
                  'endTokenPos' => 1588,
                  'endFilePos' => 10038,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @see Writer::necessaryEnclosure()
 * @deprecated Since version 9.22.0
 * @codeCoverageIgnore
 *
 * Sets the enclosure threshold to only enclose necessary fields.
 */',
        'startLine' => 347,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\Writer',
        'implementingClassName' => 'League\\Csv\\Writer',
        'currentClassName' => 'League\\Csv\\Writer',
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