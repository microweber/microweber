<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Export/Formats/Helpers/SpreadsheetHelper.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Export\Formats\Helpers\SpreadsheetHelper
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-d83f3acfb8ee65372fe366e5aba7681d6af66c61da86237f33f8f6c2f76f102a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Export/Formats/Helpers/SpreadsheetHelper.php',
      ),
    ),
    'namespace' => 'Modules\\Export\\Formats\\Helpers',
    'name' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
    'shortName' => 'SpreadsheetHelper',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * PhpSpreadsheet Helper
 *
 * @author      Nick Tsai <myintaer@gmail.com>
 * @version     1.3.6
 * @filesource  PhpSpreadsheet <https://github.com/PHPOffice/PhpSpreadsheet>
 * @see         https://github.com/yidas/phpspreadsheet-helper
 * @example
 *  \\yidas\\phpSpreadsheet\\Helper::newExcel()
 *      ->addRow([\'ID\', \'Name\', \'Email\'])
 *      ->addRows([
 *          [\'1\', \'Nick\',\'myintaer@gmail.com\'],
 *          [\'2\', \'Eric\',\'eric@.....\'],
 *      ])
 *      ->output(\'My Excel\');
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 776,
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
      '_objSpreadsheet' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_objSpreadsheet',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var object Cached PhpSpreadsheet object
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_objSheet' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_objSheet',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var object Cached PhpSpreadsheet Sheet object
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_offsetRow' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_offsetRow',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var int Current row offset for the actived sheet
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_offsetCol' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_offsetCol',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var int Current column offset for the actived sheet
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_keyCoordinateMap' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_keyCoordinateMap',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var array Map of coordinates by keys
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_keyColumnMap' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_keyColumnMap',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var array Map of column alpha by keys
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_keyRowMap' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_keyRowMap',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var array Map of row number by keys
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_keyRangeMap' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_keyRangeMap',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var int Map of ranges by keys
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_readerExtensions' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_readerExtensions',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Excel5\' => \'.xls\', \'Excel2003XML\' => \'.xls\', \'Excel2007\' => \'.xlsx\', \'OOCalc\' => \'.ods\', \'SYLK\' => \'.slk\', \'Gnumeric\' => \'.gnumeric\', \'CSV\' => \'.csv\', \'HTML\' => \'.html\']',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 70,
            'startTokenPos' => 108,
            'startFilePos' => 1526,
            'endTokenPos' => 166,
            'endFilePos' => 1767,
          ),
        ),
        'docComment' => '/**
 * Extension list for reader
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_writerTypeInfo' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_writerTypeInfo',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Ods\' => [\'extension\' => \'.ods\', \'contentType\' => \'application/vnd.oasis.opendocument.spreadsheet\'], \'Xls\' => [\'extension\' => \'.xls\', \'contentType\' => \'application/vnd.ms-excel\'], \'Xlsx\' => [\'extension\' => \'.xlsx\', \'contentType\' => \'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet\'], \'Html\' => [\'extension\' => \'.html\', \'contentType\' => \'text/html\'], \'Csv\' => [\'extension\' => \'.csv\', \'contentType\' => \'text/csv\']]',
          'attributes' => 
          array (
            'startLine' => 74,
            'endLine' => 95,
            'startTokenPos' => 179,
            'startFilePos' => 1857,
            'endTokenPos' => 291,
            'endFilePos' => 2504,
          ),
        ),
        'docComment' => '/**
 * Extension list for writer
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 74,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_invalidCharacters' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'name' => '_invalidCharacters',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'*\', \':\', \'/\', \'\\\\\', \'?\', \'[\', \']\']',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 304,
            'startFilePos' => 2631,
            'endTokenPos' => 324,
            'endFilePos' => 2666,
          ),
        ),
        'docComment' => '/**
 * Invalid characters in sheet title.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 78,
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
      'newSpreadsheet' => 
      array (
        'name' => 'newSpreadsheet',
        'parameters' => 
        array (
          'phpSpreadsheet' => 
          array (
            'name' => 'phpSpreadsheet',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 108,
                'endLine' => 108,
                'startTokenPos' => 339,
                'startFilePos' => 2892,
                'endTokenPos' => 339,
                'endFilePos' => 2895,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 43,
            'endColumn' => 62,
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
 * New or load an PhpSpreadsheet object
 *
 * @param object|string $phpSpreadsheet PhpSpreadsheet object or filepath
 * @return self
 */',
        'startLine' => 108,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getSpreadsheet' => 
      array (
        'name' => 'getSpreadsheet',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get PhpSpreadsheet object from cache
 *
 * @return object PhpSpreadsheet object
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'resetSheet' => 
      array (
        'name' => 'resetSheet',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reset cached PhpSpreadsheet sheet object and helper data
 *
 * @return self
 */',
        'startLine' => 138,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'setSheet' => 
      array (
        'name' => 'setSheet',
        'parameters' => 
        array (
          'sheet' => 
          array (
            'name' => 'sheet',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 153,
                'endLine' => 153,
                'startTokenPos' => 513,
                'startFilePos' => 4120,
                'endTokenPos' => 513,
                'endFilePos' => 4120,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 37,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'title' => 
          array (
            'name' => 'title',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 153,
                'endLine' => 153,
                'startTokenPos' => 518,
                'startFilePos' => 4130,
                'endTokenPos' => 518,
                'endFilePos' => 4133,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 47,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'normalizeTitle' => 
          array (
            'name' => 'normalizeTitle',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 153,
                'endLine' => 153,
                'startTokenPos' => 523,
                'startFilePos' => 4152,
                'endTokenPos' => 523,
                'endFilePos' => 4156,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
            'startColumn' => 60,
            'endColumn' => 80,
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
 * Set an active PhpSpreadsheet Sheet
 *
 * @param object|int $sheet PhpSpreadsheet sheet object or index number
 * @param string $title Sheet title
 * @param bool $normalizeTitle Auto-normalize title rule
 * @return self
 */',
        'startLine' => 153,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getSheet' => 
      array (
        'name' => 'getSheet',
        'parameters' => 
        array (
          'identity' => 
          array (
            'name' => 'identity',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 201,
                'endLine' => 201,
                'startTokenPos' => 812,
                'startFilePos' => 5932,
                'endTokenPos' => 812,
                'endFilePos' => 5935,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 201,
            'endLine' => 201,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'autoCreate' => 
          array (
            'name' => 'autoCreate',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 201,
                'endLine' => 201,
                'startTokenPos' => 817,
                'startFilePos' => 5950,
                'endTokenPos' => 817,
                'endFilePos' => 5954,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 201,
            'endLine' => 201,
            'startColumn' => 53,
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
 * Get PhpSpreadsheet Sheet object from cache
 *
 * @param int|string $identity Sheet index or name
 * @param bool $autoCreate
 * @return object PhpSpreadsheet Sheet object
 */',
        'startLine' => 201,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getSheetCount' => 
      array (
        'name' => 'getSheetCount',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get sheet count
 *
 * @return int Count of sheets
 */',
        'startLine' => 236,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getActiveSheetIndex' => 
      array (
        'name' => 'getActiveSheetIndex',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get active sheet index
 *
 * @return int Index of active sheet
 */',
        'startLine' => 245,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'setRowOffset' => 
      array (
        'name' => 'setRowOffset',
        'parameters' => 
        array (
          'var' => 
          array (
            'name' => 'var',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1049,
                'startFilePos' => 7473,
                'endTokenPos' => 1049,
                'endFilePos' => 7473,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 255,
            'endLine' => 255,
            'startColumn' => 41,
            'endColumn' => 46,
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
 * Set the offset of rows for the actived PhpSpreadsheet Sheet
 *
 * @param int $var The offset number
 * @return self
 */',
        'startLine' => 255,
        'endLine' => 260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getRowOffset' => 
      array (
        'name' => 'getRowOffset',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the offset of rows for the actived PhpSpreadsheet Sheet
 *
 * @return int The offset number
 */',
        'startLine' => 266,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'setColumnOffset' => 
      array (
        'name' => 'setColumnOffset',
        'parameters' => 
        array (
          'var' => 
          array (
            'name' => 'var',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 276,
                'endLine' => 276,
                'startTokenPos' => 1110,
                'startFilePos' => 7974,
                'endTokenPos' => 1110,
                'endFilePos' => 7974,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 44,
            'endColumn' => 49,
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
 * Set the offset of columns for the actived PhpSpreadsheet Sheet
 *
 * @param int $var The offset number
 * @return self
 */',
        'startLine' => 276,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'addRow' => 
      array (
        'name' => 'addRow',
        'parameters' => 
        array (
          'rowData' => 
          array (
            'name' => 'rowData',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rowAttributes' => 
          array (
            'name' => 'rowAttributes',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 298,
                'endLine' => 298,
                'startTokenPos' => 1151,
                'startFilePos' => 8771,
                'endTokenPos' => 1151,
                'endFilePos' => 8774,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 45,
            'endColumn' => 63,
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
 * Add a row to the actived sheet of PhpSpreadsheet
 *
 * @param array $rowData
 *  @param mixed|array Cell value | Cell attributes
 *   Cell attributes key-value:
 *   @param string|int \'key\' Cell key for index
 *   @param mixed \'value\' Cell value
 *   @param int \'col\' Column span for mergence
 *   @param int \'row\' Row span for mergence
 *   @param int \'skip\' Column skip counter
 *   @param int \'width\' Column width pixels
 *   @param array \'style\' Array containing style information for applyFromArray()
 * @param array Row attributes refers to cell attributes
 * @return self
 */',
        'startLine' => 298,
        'endLine' => 404,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'addRows' => 
      array (
        'name' => 'addRows',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 412,
            'endLine' => 412,
            'startColumn' => 36,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rowAttributes' => 
          array (
            'name' => 'rowAttributes',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 412,
                'endLine' => 412,
                'startTokenPos' => 1923,
                'startFilePos' => 13428,
                'endTokenPos' => 1923,
                'endFilePos' => 13431,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 412,
            'endLine' => 412,
            'startColumn' => 43,
            'endColumn' => 61,
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
 * Add rows to the actived sheet of PhpSpreadsheet
 *
 * @param array array of rowData for addRow()
 * @param array Row attributes refers to cell attributes
 * @return self
 */',
        'startLine' => 412,
        'endLine' => 418,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
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
              'code' => '\'excel\'',
              'attributes' => 
              array (
                'startLine' => 425,
                'endLine' => 425,
                'startTokenPos' => 1980,
                'startFilePos' => 13733,
                'endTokenPos' => 1980,
                'endFilePos' => 13739,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 425,
            'endLine' => 425,
            'startColumn' => 35,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'format' => 
          array (
            'name' => 'format',
            'default' => 
            array (
              'code' => '\'Xlsx\'',
              'attributes' => 
              array (
                'startLine' => 425,
                'endLine' => 425,
                'startTokenPos' => 1985,
                'startFilePos' => 13750,
                'endTokenPos' => 1985,
                'endFilePos' => 13755,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 425,
            'endLine' => 425,
            'startColumn' => 54,
            'endColumn' => 67,
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
 * Output file to browser
 *
 * @param string $filename
 * @param string $format
 */',
        'startLine' => 425,
        'endLine' => 448,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => 
            array (
              'code' => '\'excel\'',
              'attributes' => 
              array (
                'startLine' => 456,
                'endLine' => 456,
                'startTokenPos' => 2171,
                'startFilePos' => 14957,
                'endTokenPos' => 2171,
                'endFilePos' => 14963,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 456,
            'endLine' => 456,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'format' => 
          array (
            'name' => 'format',
            'default' => 
            array (
              'code' => '\'Xlsx\'',
              'attributes' => 
              array (
                'startLine' => 456,
                'endLine' => 456,
                'startTokenPos' => 2176,
                'startFilePos' => 14974,
                'endTokenPos' => 2176,
                'endFilePos' => 14979,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 456,
            'endLine' => 456,
            'startColumn' => 52,
            'endColumn' => 65,
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
 * Save as file
 *
 * @param string $filename Support file path
 * @param string $format
 * @return string Filepath
 */',
        'startLine' => 456,
        'endLine' => 474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getRow' => 
      array (
        'name' => 'getRow',
        'parameters' => 
        array (
          'toString' => 
          array (
            'name' => 'toString',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 488,
                'endLine' => 488,
                'startTokenPos' => 2332,
                'startFilePos' => 16245,
                'endTokenPos' => 2332,
                'endFilePos' => 16248,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 488,
                'endLine' => 488,
                'startTokenPos' => 2337,
                'startFilePos' => 16260,
                'endTokenPos' => 2338,
                'endFilePos' => 16261,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 51,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 488,
                'endLine' => 488,
                'startTokenPos' => 2345,
                'startFilePos' => 16283,
                'endTokenPos' => 2345,
                'endFilePos' => 16286,
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
                      'name' => 'callable',
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
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 64,
            'endColumn' => 86,
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
 * Get data of a row from the actived sheet of PhpSpreadsheet
 *
 * @param bool $toString All values from sheet to be string type
 * @param bool $options [
 *  row (int) Ended row number
 *  column (int) Ended column number
 *  timestamp (bool) Excel datetime to Unixtime
 *  timestampFormat (string) Format for date() when usgin timestamp
 *  ]
 * @param callable $callback($cellValue, int $columnIndex, int $rowIndex)
 * @return array Data of Spreadsheet
 */',
        'startLine' => 488,
        'endLine' => 528,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getRows' => 
      array (
        'name' => 'getRows',
        'parameters' => 
        array (
          'toString' => 
          array (
            'name' => 'toString',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 542,
                'endLine' => 542,
                'startTokenPos' => 2702,
                'startFilePos' => 18533,
                'endTokenPos' => 2702,
                'endFilePos' => 18536,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 36,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 542,
                'endLine' => 542,
                'startTokenPos' => 2709,
                'startFilePos' => 18554,
                'endTokenPos' => 2710,
                'endFilePos' => 18555,
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 52,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 542,
                'endLine' => 542,
                'startTokenPos' => 2717,
                'startFilePos' => 18577,
                'endTokenPos' => 2717,
                'endFilePos' => 18580,
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
                      'name' => 'callable',
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
            'startLine' => 542,
            'endLine' => 542,
            'startColumn' => 71,
            'endColumn' => 93,
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
 * Get rows from the actived sheet of PhpSpreadsheet
 *
 * @param bool $toString All values from sheet to be string type
 * @param bool $options [
 *  row (int) Ended row number
 *  column (int) Ended column number
 *  timestamp (bool) Excel datetime to Unixtime
 *  timestampFormat (string) Format for date() when usgin timestamp
 *  ]
 * @param callable $callback($cellValue, int $columnIndex, int $rowIndex)
 * @return array Data of Spreadsheet
 */',
        'startLine' => 542,
        'endLine' => 574,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getCoordinateMap' => 
      array (
        'name' => 'getCoordinateMap',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 581,
                'endLine' => 581,
                'startTokenPos' => 3006,
                'startFilePos' => 20084,
                'endTokenPos' => 3006,
                'endFilePos' => 20087,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 581,
            'endLine' => 581,
            'startColumn' => 45,
            'endColumn' => 53,
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
 * Get Coordinate Map by key or all from the actived sheet
 *
 * @param string|int $key Key set by addRow()
 * @return string|array Coordinate string | Key-Coordinate array
 */',
        'startLine' => 581,
        'endLine' => 588,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getColumnMap' => 
      array (
        'name' => 'getColumnMap',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 595,
                'endLine' => 595,
                'startTokenPos' => 3074,
                'startFilePos' => 20546,
                'endTokenPos' => 3074,
                'endFilePos' => 20549,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 595,
            'endLine' => 595,
            'startColumn' => 41,
            'endColumn' => 49,
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
 * Get Column Alpha Map by key or all from the actived sheet
 *
 * @param string|int $key Key set by addRow()
 * @return string|array Column alpha string | Key-Coordinate array
 */',
        'startLine' => 595,
        'endLine' => 602,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getRowMap' => 
      array (
        'name' => 'getRowMap',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 609,
                'endLine' => 609,
                'startTokenPos' => 3142,
                'startFilePos' => 20979,
                'endTokenPos' => 3142,
                'endFilePos' => 20982,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 609,
            'endLine' => 609,
            'startColumn' => 38,
            'endColumn' => 46,
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
 * Get Row Number Map by key or all from the actived sheet
 *
 * @param string|int $key Key set by addRow()
 * @return int|array Row number | Key-Coordinate array
 */',
        'startLine' => 609,
        'endLine' => 616,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getRangeMap' => 
      array (
        'name' => 'getRangeMap',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 623,
                'endLine' => 623,
                'startTokenPos' => 3210,
                'startFilePos' => 21400,
                'endTokenPos' => 3210,
                'endFilePos' => 21403,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 623,
            'endLine' => 623,
            'startColumn' => 40,
            'endColumn' => 48,
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
 * Get Range Map by key or all from the actived sheet
 *
 * @param string|int $key Key set by addRow()
 * @return string|array Range string | Key-Range array
 */',
        'startLine' => 623,
        'endLine' => 630,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'getRangeAll' => 
      array (
        'name' => 'getRangeAll',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Range of all actived cells from the actived sheet
 *
 * @return string Range string
 */',
        'startLine' => 636,
        'endLine' => 641,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'setWrapText' => 
      array (
        'name' => 'setWrapText',
        'parameters' => 
        array (
          'range' => 
          array (
            'name' => 'range',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 649,
                'endLine' => 649,
                'startTokenPos' => 3336,
                'startFilePos' => 22213,
                'endTokenPos' => 3336,
                'endFilePos' => 22216,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 649,
            'endLine' => 649,
            'startColumn' => 40,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 649,
                'endLine' => 649,
                'startTokenPos' => 3341,
                'startFilePos' => 22226,
                'endTokenPos' => 3341,
                'endFilePos' => 22229,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 649,
            'endLine' => 649,
            'startColumn' => 53,
            'endColumn' => 63,
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
 * Set WrapText for all actived cells or set by giving range to the actived sheet
 *
 * @param string $range Cells range format
 * @param bool $value PhpSpreadsheet setWrapText() argument
 * @return self
 */',
        'startLine' => 649,
        'endLine' => 658,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'setStyle' => 
      array (
        'name' => 'setStyle',
        'parameters' => 
        array (
          'styleArray' => 
          array (
            'name' => 'styleArray',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'range' => 
          array (
            'name' => 'range',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 666,
                'endLine' => 666,
                'startTokenPos' => 3423,
                'startFilePos' => 22780,
                'endTokenPos' => 3423,
                'endFilePos' => 22783,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 50,
            'endColumn' => 60,
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
 * Set Style for all actived cells or set by giving range to the actived sheet
 *
 * @param array Array containing style information for applyFromArray()
 * @param string $range Cells range format
 * @return self
 */',
        'startLine' => 666,
        'endLine' => 674,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'setAutoSize' => 
      array (
        'name' => 'setAutoSize',
        'parameters' => 
        array (
          'colAlphaStart' => 
          array (
            'name' => 'colAlphaStart',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 683,
                'endLine' => 683,
                'startTokenPos' => 3497,
                'startFilePos' => 23371,
                'endTokenPos' => 3497,
                'endFilePos' => 23374,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 683,
            'endLine' => 683,
            'startColumn' => 40,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'colAlphaEnd' => 
          array (
            'name' => 'colAlphaEnd',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 683,
                'endLine' => 683,
                'startTokenPos' => 3502,
                'startFilePos' => 23390,
                'endTokenPos' => 3502,
                'endFilePos' => 23393,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 683,
            'endLine' => 683,
            'startColumn' => 61,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 683,
                'endLine' => 683,
                'startTokenPos' => 3507,
                'startFilePos' => 23403,
                'endTokenPos' => 3507,
                'endFilePos' => 23406,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 683,
            'endLine' => 683,
            'startColumn' => 80,
            'endColumn' => 90,
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
 * Set AutoSize for all actived cells or set by giving column range to the actived sheet
 *
 * @param string $colAlphaStart Column Alpah of start
 * @param string $colAlphaEnd Column Alpah of end
 * @param bool $value PhpSpreadsheet AutoSize() argument
 * @return self
 */',
        'startLine' => 683,
        'endLine' => 694,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'num2alpha' => 
      array (
        'name' => 'num2alpha',
        'parameters' => 
        array (
          'n' => 
          array (
            'name' => 'n',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 705,
            'endLine' => 705,
            'startColumn' => 38,
            'endColumn' => 39,
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
 * Number to Alpha
 *
 * Optimizing from \\PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate::stringFromColumnIndex()
 *
 * @example
 *  1 => A, 27 => AA
 * @param int $n column number
 * @return string Excel column alpha
 */',
        'startLine' => 705,
        'endLine' => 714,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'alpha2num' => 
      array (
        'name' => 'alpha2num',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 725,
            'endLine' => 725,
            'startColumn' => 38,
            'endColumn' => 39,
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
 * Alpha to Number
 *
 * Optimizing from \\PhpOffice\\PhpSpreadsheet\\Cell\\Coordinate::columnIndexFromString()
 *
 * @example
 *  A => 1, AA => 27
 * @param int $n Excel column alpha
 * @return string column number
 */',
        'startLine' => 725,
        'endLine' => 733,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'validExcelObj' => 
      array (
        'name' => 'validExcelObj',
        'parameters' => 
        array (
          'excelObj' => 
          array (
            'name' => 'excelObj',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 740,
                'endLine' => 740,
                'startTokenPos' => 3878,
                'startFilePos' => 25151,
                'endTokenPos' => 3878,
                'endFilePos' => 25154,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 740,
            'endLine' => 740,
            'startColumn' => 43,
            'endColumn' => 56,
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
 * Validate and return the selected PhpSpreadsheet Object
 *
 * @param object $excelObj PhpSpreadsheet Object
 * @return object Cached object or given object
 */',
        'startLine' => 740,
        'endLine' => 752,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'aliasName' => NULL,
      ),
      'validSheetObj' => 
      array (
        'name' => 'validSheetObj',
        'parameters' => 
        array (
          'sheetObj' => 
          array (
            'name' => 'sheetObj',
            'default' => 
            array (
              'code' => 'NULL',
              'attributes' => 
              array (
                'startLine' => 759,
                'endLine' => 759,
                'startTokenPos' => 3956,
                'startFilePos' => 25711,
                'endTokenPos' => 3956,
                'endFilePos' => 25714,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 759,
            'endLine' => 759,
            'startColumn' => 43,
            'endColumn' => 56,
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
 * Validate and return the selected PhpSpreadsheet Sheet Object
 *
 * @param object $excelObj PhpSpreadsheet Sheet Object
 * @return object Cached object or given object
 */',
        'startLine' => 759,
        'endLine' => 775,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Modules\\Export\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Export\\Formats\\Helpers\\SpreadsheetHelper',
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