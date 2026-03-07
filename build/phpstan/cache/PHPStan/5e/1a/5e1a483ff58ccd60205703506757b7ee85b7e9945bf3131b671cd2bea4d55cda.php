<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/AbstractCsv.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\AbstractCsv
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-31d73689e7ba09d6bb758e0ecba3eec9894ad3cf6ae836746af88b709ad69cf8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\AbstractCsv',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/AbstractCsv.php',
      ),
    ),
    'namespace' => 'League\\Csv',
    'name' => 'League\\Csv\\AbstractCsv',
    'shortName' => 'AbstractCsv',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * An abstract class to enable CSV document loading.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 47,
    'endLine' => 675,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'League\\Csv\\ByteSequence',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'STREAM_FILTER_MODE' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'STREAM_FILTER_MODE',
        'modifiers' => 2,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\STREAM_FILTER_READ',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 194,
            'startFilePos' => 1028,
            'endTokenPos' => 194,
            'endFilePos' => 1045,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 60,
      ),
    ),
    'immediateProperties' => 
    array (
      'stream_filters' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'stream_filters',
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
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 207,
            'startFilePos' => 1153,
            'endTokenPos' => 208,
            'endFilePos' => 1154,
          ),
        ),
        'docComment' => '/** @var array<string, bool> collection of stream filters. */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'input_bom' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'input_bom',
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
                  'name' => 'League\\Csv\\Bom',
                  'isIdentifier' => false,
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
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 220,
            'startFilePos' => 1189,
            'endTokenPos' => 220,
            'endFilePos' => 1192,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'output_bom' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'output_bom',
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
                  'name' => 'League\\Csv\\Bom',
                  'isIdentifier' => false,
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
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 232,
            'startFilePos' => 1228,
            'endTokenPos' => 232,
            'endFilePos' => 1231,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'delimiter' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'delimiter',
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
          'code' => '\',\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 243,
            'startFilePos' => 1268,
            'endTokenPos' => 243,
            'endFilePos' => 1270,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'enclosure' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'enclosure',
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
          'code' => '\'"\'',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 254,
            'startFilePos' => 1307,
            'endTokenPos' => 254,
            'endFilePos' => 1309,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'escape' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'escape',
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
          'code' => '\'\\\\\'',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 265,
            'startFilePos' => 1343,
            'endTokenPos' => 265,
            'endFilePos' => 1346,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'is_input_bom_included' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'is_input_bom_included',
        'modifiers' => 2,
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
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 276,
            'startFilePos' => 1393,
            'endTokenPos' => 276,
            'endFilePos' => 1397,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 50,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'formatters' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'formatters',
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
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 289,
            'startFilePos' => 1538,
            'endTokenPos' => 290,
            'endFilePos' => 1539,
          ),
        ),
        'docComment' => '/** @var array<Closure(array): array> collection of Closure to format the record before reading. */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'document' => 
      array (
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'name' => 'document',
        'modifiers' => 130,
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
                  'name' => 'SplFileObject',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'League\\Csv\\Stream',
                  'isIdentifier' => false,
                ),
              ),
            ),
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 36,
        'endColumn' => 84,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'document' => 
          array (
            'name' => 'document',
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
                      'name' => 'SplFileObject',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'League\\Csv\\Stream',
                      'isIdentifier' => false,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 36,
            'endColumn' => 84,
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
 * @final This method should not be overwritten in child classes
 */',
        'startLine' => 65,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
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
        'docComment' => '/**
 * Reset dynamic object properties to improve performance.
 */',
        'startLine' => 74,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      '__clone' => 
      array (
        'name' => '__clone',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws UnavailableStream
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
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'fromString' => 
      array (
        'name' => 'fromString',
        'parameters' => 
        array (
          'content' => 
          array (
            'name' => 'content',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 89,
                'endLine' => 89,
                'startTokenPos' => 411,
                'startFilePos' => 2287,
                'endTokenPos' => 411,
                'endFilePos' => 2288,
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
                      'name' => 'Stringable',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 39,
            'endColumn' => 69,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a new instance from a string.
 */',
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'mode' => 
          array (
            'name' => 'mode',
            'default' => 
            array (
              'code' => '\'r+\'',
              'attributes' => 
              array (
                'startLine' => 103,
                'endLine' => 103,
                'startTokenPos' => 455,
                'startFilePos' => 2867,
                'endTokenPos' => 455,
                'endFilePos' => 2870,
              ),
            ),
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
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 44,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'context' => 
          array (
            'name' => 'context',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 103,
                'endLine' => 103,
                'startTokenPos' => 462,
                'startFilePos' => 2884,
                'endTokenPos' => 462,
                'endFilePos' => 2887,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 65,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a new instance from a file path.
 *
 * @param SplFileInfo|SplFileObject|resource|string $filename an SPL file object, a resource stream or a file path
 * @param non-empty-string $mode the file path open mode used with a file path or a SplFileInfo object
 * @param resource|null $context the resource context used with a file pathor a SplFileInfo object
 *
 * @throws UnavailableStream
 */',
        'startLine' => 103,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getDelimiter' => 
      array (
        'name' => 'getDelimiter',
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
 * Returns the current field delimiter.
 */',
        'startLine' => 115,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getEnclosure' => 
      array (
        'name' => 'getEnclosure',
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
 * Returns the current field enclosure.
 */',
        'startLine' => 123,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getPathname' => 
      array (
        'name' => 'getPathname',
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
 * Returns the pathname of the underlying document.
 */',
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getEscape' => 
      array (
        'name' => 'getEscape',
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
 * Returns the current field escape character.
 */',
        'startLine' => 139,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getOutputBOM' => 
      array (
        'name' => 'getOutputBOM',
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
 * Returns the BOM sequence in use on Output methods.
 */',
        'startLine' => 147,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getInputBOM' => 
      array (
        'name' => 'getInputBOM',
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
 * Returns the BOM sequence of the given CSV.
 */',
        'startLine' => 155,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'supportsStreamFilterOnRead' => 
      array (
        'name' => 'supportsStreamFilterOnRead',
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
 * Tells whether the stream filter read capabilities can be used.
 */',
        'startLine' => 168,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'supportsStreamFilterOnWrite' => 
      array (
        'name' => 'supportsStreamFilterOnWrite',
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
 * Tells whether the stream filter write capabilities can be used.
 */',
        'startLine' => 182,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'hasStreamFilter' => 
      array (
        'name' => 'hasStreamFilter',
        'parameters' => 
        array (
          'filtername' => 
          array (
            'name' => 'filtername',
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
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 37,
            'endColumn' => 54,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Tells whether the specified stream filter is attached to the current stream.
 */',
        'startLine' => 196,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'isInputBOMIncluded' => 
      array (
        'name' => 'isInputBOMIncluded',
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
 * Tells whether the BOM can be stripped if presents.
 */',
        'startLine' => 204,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'chunk' => 
      array (
        'name' => 'chunk',
        'parameters' => 
        array (
          'length' => 
          array (
            'name' => 'length',
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
            'startLine' => 214,
            'endLine' => 214,
            'startColumn' => 27,
            'endColumn' => 37,
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
            'name' => 'Generator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the CSV document as a Generator of string chunk.
 *
 * @throws Exception if the number of bytes is less than 1
 */',
        'startLine' => 214,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => true,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'toString' => 
      array (
        'name' => 'toString',
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
 * Retrieves the CSV content.
 *
 * @throws Exception If the string representation cannot be returned
 */',
        'startLine' => 238,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'download' => 
      array (
        'name' => 'download',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1213,
                'startFilePos' => 6941,
                'endTokenPos' => 1213,
                'endFilePos' => 6944,
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
            'startLine' => 255,
            'endLine' => 255,
            'startColumn' => 30,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Outputs all data on the CSV file.
 *
 * Returns the number of characters read from the handle and passed through to the output.
 *
 * @throws InvalidArgumentException|Exception
 */',
        'startLine' => 255,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'setDelimiter' => 
      array (
        'name' => 'setDelimiter',
        'parameters' => 
        array (
          'delimiter' => 
          array (
            'name' => 'delimiter',
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
            'startLine' => 287,
            'endLine' => 287,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the field delimiter.
 *
 * @throws InvalidArgument If the Csv control character is not one character only.
 */',
        'startLine' => 287,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'setEnclosure' => 
      array (
        'name' => 'setEnclosure',
        'parameters' => 
        array (
          'enclosure' => 
          array (
            'name' => 'enclosure',
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
            'startLine' => 306,
            'endLine' => 306,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the field enclosure.
 *
 * @throws InvalidArgument If the Csv control character is not one character only.
 */',
        'startLine' => 306,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'setEscape' => 
      array (
        'name' => 'setEscape',
        'parameters' => 
        array (
          'escape' => 
          array (
            'name' => 'escape',
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
            'startLine' => 325,
            'endLine' => 325,
            'startColumn' => 31,
            'endColumn' => 44,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the field escape character.
 *
 * @throws InvalidArgument If the Csv control character is not one character only.
 */',
        'startLine' => 325,
        'endLine' => 339,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'addFormatter' => 
      array (
        'name' => 'addFormatter',
        'parameters' => 
        array (
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
            'startLine' => 346,
            'endLine' => 346,
            'startColumn' => 34,
            'endColumn' => 52,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds a record formatter.
 *
 * @param callable(array): array $formatter
 */',
        'startLine' => 346,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'skipInputBOM' => 
      array (
        'name' => 'skipInputBOM',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Enables BOM Stripping.
 */',
        'startLine' => 356,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'includeInputBOM' => 
      array (
        'name' => 'includeInputBOM',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Disables skipping Input BOM.
 */',
        'startLine' => 366,
        'endLine' => 371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'setOutputBOM' => 
      array (
        'name' => 'setOutputBOM',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
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
                      'name' => 'League\\Csv\\Bom',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
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
            'startLine' => 378,
            'endLine' => 378,
            'startColumn' => 34,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the BOM sequence to prepend the CSV on output.
 *
 * @throws InvalidArgument if the given non-empty string is not a valid BOM sequence
 */',
        'startLine' => 378,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'appendStreamFilterOnRead' => 
      array (
        'name' => 'appendStreamFilterOnRead',
        'parameters' => 
        array (
          'filtername' => 
          array (
            'name' => 'filtername',
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
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 46,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 400,
                'endLine' => 400,
                'startTokenPos' => 1966,
                'startFilePos' => 10979,
                'endTokenPos' => 1966,
                'endFilePos' => 10982,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 66,
            'endColumn' => 85,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Append a stream filter.
 *
 * @throws InvalidArgument If the stream filter API can not be appended
 * @throws UnavailableFeature If the stream filter API can not be used
 */',
        'startLine' => 400,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'appendStreamFilterOnWrite' => 
      array (
        'name' => 'appendStreamFilterOnWrite',
        'parameters' => 
        array (
          'filtername' => 
          array (
            'name' => 'filtername',
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
            'startLine' => 418,
            'endLine' => 418,
            'startColumn' => 47,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 418,
                'endLine' => 418,
                'startTokenPos' => 2069,
                'startFilePos' => 11648,
                'endTokenPos' => 2069,
                'endFilePos' => 11651,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 418,
            'endLine' => 418,
            'startColumn' => 67,
            'endColumn' => 86,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Append a stream filter.
 *
 * @throws InvalidArgument If the stream filter API can not be appended
 * @throws UnavailableFeature If the stream filter API can not be used
 */',
        'startLine' => 418,
        'endLine' => 428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'prependStreamFilterOnWrite' => 
      array (
        'name' => 'prependStreamFilterOnWrite',
        'parameters' => 
        array (
          'filtername' => 
          array (
            'name' => 'filtername',
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
            'startLine' => 436,
            'endLine' => 436,
            'startColumn' => 48,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 436,
                'endLine' => 436,
                'startTokenPos' => 2172,
                'startFilePos' => 12320,
                'endTokenPos' => 2172,
                'endFilePos' => 12323,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 436,
            'endLine' => 436,
            'startColumn' => 68,
            'endColumn' => 87,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepend a stream filter.
 *
 * @throws InvalidArgument If the stream filter API can not be appended
 * @throws UnavailableFeature If the stream filter API can not be used
 */',
        'startLine' => 436,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'prependStreamFilterOnRead' => 
      array (
        'name' => 'prependStreamFilterOnRead',
        'parameters' => 
        array (
          'filtername' => 
          array (
            'name' => 'filtername',
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
            'startLine' => 454,
            'endLine' => 454,
            'startColumn' => 47,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 454,
                'endLine' => 454,
                'startTokenPos' => 2275,
                'startFilePos' => 12991,
                'endTokenPos' => 2275,
                'endFilePos' => 12994,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 454,
            'endLine' => 454,
            'startColumn' => 67,
            'endColumn' => 86,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepend a stream filter.
 *
 * @throws InvalidArgument If the stream filter API can not be appended
 * @throws UnavailableFeature If the stream filter API can not be used
 */',
        'startLine' => 454,
        'endLine' => 464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getStreamFilterMode' => 
      array (
        'name' => 'getStreamFilterMode',
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
                'code' => '\'use League\\Csv\\AbstractCsv::supportsStreamFilterOnRead() or League\\Csv\\AbstractCsv::supportsStreamFilterOnWrite() instead\'',
                'attributes' => 
                array (
                  'startLine' => 476,
                  'endLine' => 476,
                  'startTokenPos' => 2366,
                  'startFilePos' => 13728,
                  'endTokenPos' => 2366,
                  'endFilePos' => 13850,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.7.0\'',
                'attributes' => 
                array (
                  'startLine' => 476,
                  'endLine' => 476,
                  'startTokenPos' => 2371,
                  'startFilePos' => 13859,
                  'endTokenPos' => 2371,
                  'endFilePos' => 13876,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated since version 9.7.0
 * @see AbstractCsv::supportsStreamFilterOnRead
 * @see AbstractCsv::supportsStreamFilterOnWrite
 * @codeCoverageIgnore
 *
 * Returns the stream filter mode.
 */',
        'startLine' => 476,
        'endLine' => 480,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'supportsStreamFilter' => 
      array (
        'name' => 'supportsStreamFilter',
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
          0 => 
          array (
            'name' => 'Deprecated',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '\'use League\\Csv\\AbstractCsv::supportsStreamFilterOnRead() or League\\Csv\\AbstractCsv::supportsStreamFilterOnWrite() instead\'',
                'attributes' => 
                array (
                  'startLine' => 492,
                  'endLine' => 492,
                  'startTokenPos' => 2404,
                  'startFilePos' => 14363,
                  'endTokenPos' => 2404,
                  'endFilePos' => 14485,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.7.0\'',
                'attributes' => 
                array (
                  'startLine' => 492,
                  'endLine' => 492,
                  'startTokenPos' => 2409,
                  'startFilePos' => 14494,
                  'endTokenPos' => 2409,
                  'endFilePos' => 14511,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated since version 9.7.0
 * @see AbstractCsv::supportsStreamFilterOnRead
 * @see AbstractCsv::supportsStreamFilterOnWrite
 * @codeCoverageIgnore
 *
 * Tells whether the stream filter capabilities can be used.
 */',
        'startLine' => 492,
        'endLine' => 496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'getContent' => 
      array (
        'name' => 'getContent',
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
                'code' => '\'use League\\Csv\\AbstractCsv::toString() instead\'',
                'attributes' => 
                array (
                  'startLine' => 507,
                  'endLine' => 507,
                  'startTokenPos' => 2446,
                  'startFilePos' => 14904,
                  'endTokenPos' => 2446,
                  'endFilePos' => 14951,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.7.0\'',
                'attributes' => 
                array (
                  'startLine' => 507,
                  'endLine' => 507,
                  'startTokenPos' => 2451,
                  'startFilePos' => 14960,
                  'endTokenPos' => 2451,
                  'endFilePos' => 14977,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * Retrieves the CSV content.
 *
 * DEPRECATION WARNING! This method will be removed in the next major point release
 *
 * @deprecated since version 9.7.0
 * @see AbstractCsv::toString
 * @codeCoverageIgnore
 */',
        'startLine' => 507,
        'endLine' => 511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
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
                'code' => '\'use League\\Csv\\AbstractCsv::toString() instead\'',
                'attributes' => 
                array (
                  'startLine' => 522,
                  'endLine' => 522,
                  'startTokenPos' => 2486,
                  'startFilePos' => 15346,
                  'endTokenPos' => 2486,
                  'endFilePos' => 15393,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.1.0\'',
                'attributes' => 
                array (
                  'startLine' => 522,
                  'endLine' => 522,
                  'startTokenPos' => 2491,
                  'startFilePos' => 15402,
                  'endTokenPos' => 2491,
                  'endFilePos' => 15419,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @deprecated since version 9.1.0
 * @see AbstractCsv::toString
 * @codeCoverageIgnore
 *
 * Retrieves the CSV content
 */',
        'startLine' => 522,
        'endLine' => 526,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'sendHeaders' => 
      array (
        'name' => 'sendHeaders',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
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
            'startLine' => 544,
            'endLine' => 544,
            'startColumn' => 36,
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
          0 => 
          array (
            'name' => 'Deprecated',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '\'the method no longer affect the outcome of the class, use League\\Csv\\HttpHeaders::forFileDownload instead\'',
                'attributes' => 
                array (
                  'startLine' => 543,
                  'endLine' => 543,
                  'startTokenPos' => 2526,
                  'startFilePos' => 16045,
                  'endTokenPos' => 2526,
                  'endFilePos' => 16151,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.17.0\'',
                'attributes' => 
                array (
                  'startLine' => 543,
                  'endLine' => 543,
                  'startTokenPos' => 2531,
                  'startFilePos' => 16160,
                  'endTokenPos' => 2531,
                  'endFilePos' => 16178,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @throws Exception if the submitted header is invalid according to RFC 6266
 *
 * @see HttpHeaders::forFileDownload()
 * @codeCoverageIgnore
 *
 * Send the CSV headers.
 *
 * Adapted from Symfony\\Component\\HttpFoundation\\ResponseHeaderBag::makeDisposition
 *
 * @deprecated since version 9.17.0
 * @see https://tools.ietf.org/html/rfc6266#section-4.3
 */',
        'startLine' => 543,
        'endLine' => 568,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'output' => 
      array (
        'name' => 'output',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 584,
                'endLine' => 584,
                'startTokenPos' => 2761,
                'startFilePos' => 17744,
                'endTokenPos' => 2761,
                'endFilePos' => 17747,
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
            'startLine' => 584,
            'endLine' => 584,
            'startColumn' => 28,
            'endColumn' => 51,
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
                'code' => '\'use League\\Csv\\AbstractCsv::download() instead\'',
                'attributes' => 
                array (
                  'startLine' => 583,
                  'endLine' => 583,
                  'startTokenPos' => 2739,
                  'startFilePos' => 17619,
                  'endTokenPos' => 2739,
                  'endFilePos' => 17666,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.18.0\'',
                'attributes' => 
                array (
                  'startLine' => 583,
                  'endLine' => 583,
                  'startTokenPos' => 2744,
                  'startFilePos' => 17675,
                  'endTokenPos' => 2744,
                  'endFilePos' => 17693,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 *
 * @codeCoverageIgnore
 * @deprecated since version 9.18.0
 * @see AbstractCsv::download()
 *
 * Outputs all data on the CSV file.
 *
 * Returns the number of characters read from the handle and passed through to the output.
 *
 * @throws Exception
 */',
        'startLine' => 583,
        'endLine' => 591,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'addStreamFilter' => 
      array (
        'name' => 'addStreamFilter',
        'parameters' => 
        array (
          'filtername' => 
          array (
            'name' => 'filtername',
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
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 37,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 606,
                'endLine' => 606,
                'startTokenPos' => 2847,
                'startFilePos' => 18680,
                'endTokenPos' => 2847,
                'endFilePos' => 18683,
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
                      'name' => 'array',
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
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 57,
            'endColumn' => 77,
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
            'name' => 'static',
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
                'code' => '\'use League\\Csv\\AbstractCsv::appendStreamFilterOnRead() or League\\Csv\\AbstractCsv::prependStreamFilterOnRead() instead\'',
                'attributes' => 
                array (
                  'startLine' => 605,
                  'endLine' => 605,
                  'startTokenPos' => 2820,
                  'startFilePos' => 18458,
                  'endTokenPos' => 2820,
                  'endFilePos' => 18576,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.18.0\'',
                'attributes' => 
                array (
                  'startLine' => 605,
                  'endLine' => 605,
                  'startTokenPos' => 2825,
                  'startFilePos' => 18585,
                  'endTokenPos' => 2825,
                  'endFilePos' => 18603,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 * @codeCoverageIgnore
 * @deprecated since version 9.22.0
 * @see AbstractCsv::appendStreamFilterOnRead()
 * @see AbstractCsv::appendStreamFilterOnWrite()
 *
 * Append a stream filter.
 *
 * @throws InvalidArgument If the stream filter API can not be appended
 * @throws UnavailableFeature If the stream filter API can not be used
 */',
        'startLine' => 605,
        'endLine' => 613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'createFromFileObject' => 
      array (
        'name' => 'createFromFileObject',
        'parameters' => 
        array (
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'SplFileObject',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 623,
            'endLine' => 623,
            'startColumn' => 49,
            'endColumn' => 67,
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
            'name' => 'static',
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
                'code' => '\'use League\\Csv\\AbstractCsv::from() instead\'',
                'attributes' => 
                array (
                  'startLine' => 622,
                  'endLine' => 622,
                  'startTokenPos' => 2906,
                  'startFilePos' => 19184,
                  'endTokenPos' => 2906,
                  'endFilePos' => 19227,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.27.0\'',
                'attributes' => 
                array (
                  'startLine' => 622,
                  'endLine' => 622,
                  'startTokenPos' => 2911,
                  'startFilePos' => 19236,
                  'endTokenPos' => 2911,
                  'endFilePos' => 19254,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 * @codeCoverageIgnore
 * @deprecated since version 9.27.0
 *
 * Returns a new instance from a SplFileObject.
 */',
        'startLine' => 622,
        'endLine' => 626,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'createFromStream' => 
      array (
        'name' => 'createFromStream',
        'parameters' => 
        array (
          'stream' => 
          array (
            'name' => 'stream',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 638,
            'endLine' => 638,
            'startColumn' => 45,
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
            'name' => 'static',
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
                'code' => '\'use League\\Csv\\AbstractCsv::from() instead\'',
                'attributes' => 
                array (
                  'startLine' => 637,
                  'endLine' => 637,
                  'startTokenPos' => 2952,
                  'startFilePos' => 19682,
                  'endTokenPos' => 2952,
                  'endFilePos' => 19725,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.27.0\'',
                'attributes' => 
                array (
                  'startLine' => 637,
                  'endLine' => 637,
                  'startTokenPos' => 2957,
                  'startFilePos' => 19734,
                  'endTokenPos' => 2957,
                  'endFilePos' => 19752,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 * @codeCoverageIgnore
 * @deprecated since version 9.27.0
 *
 * Returns a new instance from a PHP resource stream.
 *
 * @param resource $stream
 */',
        'startLine' => 637,
        'endLine' => 643,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'createFromString' => 
      array (
        'name' => 'createFromString',
        'parameters' => 
        array (
          'content' => 
          array (
            'name' => 'content',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 653,
                'endLine' => 653,
                'startTokenPos' => 3050,
                'startFilePos' => 20422,
                'endTokenPos' => 3050,
                'endFilePos' => 20423,
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
                      'name' => 'Stringable',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 653,
            'endLine' => 653,
            'startColumn' => 45,
            'endColumn' => 75,
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
            'name' => 'static',
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
                'code' => '\'use League\\Csv\\AbstractCsv::fromString() instead\'',
                'attributes' => 
                array (
                  'startLine' => 652,
                  'endLine' => 652,
                  'startTokenPos' => 3025,
                  'startFilePos' => 20269,
                  'endTokenPos' => 3025,
                  'endFilePos' => 20318,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.27.0\'',
                'attributes' => 
                array (
                  'startLine' => 652,
                  'endLine' => 652,
                  'startTokenPos' => 3030,
                  'startFilePos' => 20327,
                  'endTokenPos' => 3030,
                  'endFilePos' => 20345,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 * @codeCoverageIgnore
 * @deprecated since version 9.27.0
 *
 * Returns a new instance from a string.
 */',
        'startLine' => 652,
        'endLine' => 656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
        'aliasName' => NULL,
      ),
      'createFromPath' => 
      array (
        'name' => 'createFromPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 671,
            'endLine' => 671,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'open_mode' => 
          array (
            'name' => 'open_mode',
            'default' => 
            array (
              'code' => '\'r+\'',
              'attributes' => 
              array (
                'startLine' => 671,
                'endLine' => 671,
                'startTokenPos' => 3105,
                'startFilePos' => 21039,
                'endTokenPos' => 3105,
                'endFilePos' => 21042,
              ),
            ),
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
            'startLine' => 671,
            'endLine' => 671,
            'startColumn' => 57,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'context' => 
          array (
            'name' => 'context',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 671,
                'endLine' => 671,
                'startTokenPos' => 3112,
                'startFilePos' => 21056,
                'endTokenPos' => 3112,
                'endFilePos' => 21059,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 671,
            'endLine' => 671,
            'startColumn' => 83,
            'endColumn' => 97,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
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
                'code' => '\'use League\\Csv\\AbstractCsv::from() instead\'',
                'attributes' => 
                array (
                  'startLine' => 670,
                  'endLine' => 670,
                  'startTokenPos' => 3077,
                  'startFilePos' => 20889,
                  'endTokenPos' => 3077,
                  'endFilePos' => 20932,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.27.0\'',
                'attributes' => 
                array (
                  'startLine' => 670,
                  'endLine' => 670,
                  'startTokenPos' => 3082,
                  'startFilePos' => 20941,
                  'endTokenPos' => 3082,
                  'endFilePos' => 20959,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * DEPRECATION WARNING! This method will be removed in the next major point release.
 * @codeCoverageIgnore
 * @deprecated since version 9.27.0
 *
 * Returns a new instance from a file path.
 *
 * @param non-empty-string $open_mode
 * @param resource|null $context the resource context
 *
 * @throws UnavailableStream
 */',
        'startLine' => 670,
        'endLine' => 674,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'League\\Csv',
        'declaringClassName' => 'League\\Csv\\AbstractCsv',
        'implementingClassName' => 'League\\Csv\\AbstractCsv',
        'currentClassName' => 'League\\Csv\\AbstractCsv',
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