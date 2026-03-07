<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/ByteSequence.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\ByteSequence
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-852d94f114f0767d486929a1368caa25db94a2dcdf16363289a18e5f8d9e2927-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\ByteSequence',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/ByteSequence.php',
      ),
    ),
    'namespace' => 'League\\Csv',
    'name' => 'League\\Csv\\ByteSequence',
    'shortName' => 'ByteSequence',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Defines constants for common BOM sequences.
 *
 * @deprecated since version 9.16.0
 * @see Bom
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 34,
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
      'BOM_UTF8' => 
      array (
        'declaringClassName' => 'League\\Csv\\ByteSequence',
        'implementingClassName' => 'League\\Csv\\ByteSequence',
        'name' => 'BOM_UTF8',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"﻿"',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 46,
            'startFilePos' => 536,
            'endTokenPos' => 46,
            'endFilePos' => 549,
          ),
        ),
        'docComment' => NULL,
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
                'code' => '\'use League\\Csv\\Bom:Utf8 instead\'',
                'attributes' => 
                array (
                  'startLine' => 24,
                  'endLine' => 24,
                  'startTokenPos' => 28,
                  'startFilePos' => 444,
                  'endTokenPos' => 28,
                  'endFilePos' => 476,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.16.0\'',
                'attributes' => 
                array (
                  'startLine' => 24,
                  'endLine' => 24,
                  'startTokenPos' => 34,
                  'startFilePos' => 486,
                  'endTokenPos' => 34,
                  'endFilePos' => 504,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 24,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'BOM_UTF16_BE' => 
      array (
        'declaringClassName' => 'League\\Csv\\ByteSequence',
        'implementingClassName' => 'League\\Csv\\ByteSequence',
        'name' => 'BOM_UTF16_BE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\xfe\\xff"',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 73,
            'startFilePos' => 677,
            'endTokenPos' => 73,
            'endFilePos' => 686,
          ),
        ),
        'docComment' => NULL,
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
                'code' => '\'use League\\Csv\\Bom:Utf16be instead\'',
                'attributes' => 
                array (
                  'startLine' => 26,
                  'endLine' => 26,
                  'startTokenPos' => 55,
                  'startFilePos' => 578,
                  'endTokenPos' => 55,
                  'endFilePos' => 613,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.16.0\'',
                'attributes' => 
                array (
                  'startLine' => 26,
                  'endLine' => 26,
                  'startTokenPos' => 61,
                  'startFilePos' => 623,
                  'endTokenPos' => 61,
                  'endFilePos' => 641,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 26,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'BOM_UTF16_LE' => 
      array (
        'declaringClassName' => 'League\\Csv\\ByteSequence',
        'implementingClassName' => 'League\\Csv\\ByteSequence',
        'name' => 'BOM_UTF16_LE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\xff\\xfe"',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 100,
            'startFilePos' => 814,
            'endTokenPos' => 100,
            'endFilePos' => 823,
          ),
        ),
        'docComment' => NULL,
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
                'code' => '\'use League\\Csv\\Bom:Utf16Le instead\'',
                'attributes' => 
                array (
                  'startLine' => 28,
                  'endLine' => 28,
                  'startTokenPos' => 82,
                  'startFilePos' => 715,
                  'endTokenPos' => 82,
                  'endFilePos' => 750,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.16.0\'',
                'attributes' => 
                array (
                  'startLine' => 28,
                  'endLine' => 28,
                  'startTokenPos' => 88,
                  'startFilePos' => 760,
                  'endTokenPos' => 88,
                  'endFilePos' => 778,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 28,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'BOM_UTF32_BE' => 
      array (
        'declaringClassName' => 'League\\Csv\\ByteSequence',
        'implementingClassName' => 'League\\Csv\\ByteSequence',
        'name' => 'BOM_UTF32_BE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x00\\x00\\xfe\\xff"',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 127,
            'startFilePos' => 951,
            'endTokenPos' => 127,
            'endFilePos' => 968,
          ),
        ),
        'docComment' => NULL,
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
                'code' => '\'use League\\Csv\\Bom:Utf32Be instead\'',
                'attributes' => 
                array (
                  'startLine' => 30,
                  'endLine' => 30,
                  'startTokenPos' => 109,
                  'startFilePos' => 852,
                  'endTokenPos' => 109,
                  'endFilePos' => 887,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.16.0\'',
                'attributes' => 
                array (
                  'startLine' => 30,
                  'endLine' => 30,
                  'startTokenPos' => 115,
                  'startFilePos' => 897,
                  'endTokenPos' => 115,
                  'endFilePos' => 915,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 30,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'BOM_UTF32_LE' => 
      array (
        'declaringClassName' => 'League\\Csv\\ByteSequence',
        'implementingClassName' => 'League\\Csv\\ByteSequence',
        'name' => 'BOM_UTF32_LE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\xff\\xfe\\x00\\x00"',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 154,
            'startFilePos' => 1096,
            'endTokenPos' => 154,
            'endFilePos' => 1113,
          ),
        ),
        'docComment' => NULL,
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
                'code' => '\'use League\\Csv\\Bom:Utf32Le instead\'',
                'attributes' => 
                array (
                  'startLine' => 32,
                  'endLine' => 32,
                  'startTokenPos' => 136,
                  'startFilePos' => 997,
                  'endTokenPos' => 136,
                  'endFilePos' => 1032,
                ),
              ),
              'since' => 
              array (
                'code' => '\'league/csv:9.16.0\'',
                'attributes' => 
                array (
                  'startLine' => 32,
                  'endLine' => 32,
                  'startTokenPos' => 142,
                  'startFilePos' => 1042,
                  'endTokenPos' => 142,
                  'endFilePos' => 1060,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 32,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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