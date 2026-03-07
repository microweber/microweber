<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Restore/Formats/Helpers/SpreadsheetHelper.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Restore\Formats\Helpers\SpreadsheetHelper
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-a235f09f48e85ded2217d3f28c7a4f3b4df337e3cb193bd39bc04eb660fac002',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Restore/Formats/Helpers/SpreadsheetHelper.php',
      ),
    ),
    'namespace' => 'Modules\\Restore\\Formats\\Helpers',
    'name' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
 * @filesource 	PhpSpreadsheet <https://github.com/PHPOffice/PhpSpreadsheet>
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
    'endLine' => 777,
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
            'startFilePos' => 1527,
            'endTokenPos' => 166,
            'endFilePos' => 1768,
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
            'startFilePos' => 1858,
            'endTokenPos' => 291,
            'endFilePos' => 2505,
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
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
            'startFilePos' => 2632,
            'endTokenPos' => 324,
            'endFilePos' => 2667,
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
                'startFilePos' => 2893,
                'endTokenPos' => 339,
                'endFilePos' => 2896,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startFilePos' => 4121,
                'endTokenPos' => 513,
                'endFilePos' => 4121,
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
                'startFilePos' => 4131,
                'endTokenPos' => 518,
                'endFilePos' => 4134,
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
                'startFilePos' => 4153,
                'endTokenPos' => 523,
                'endFilePos' => 4157,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startFilePos' => 5933,
                'endTokenPos' => 812,
                'endFilePos' => 5936,
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
                'startFilePos' => 5951,
                'endTokenPos' => 817,
                'endFilePos' => 5955,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startFilePos' => 7474,
                'endTokenPos' => 1049,
                'endFilePos' => 7474,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startFilePos' => 7975,
                'endTokenPos' => 1110,
                'endFilePos' => 7975,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startFilePos' => 8772,
                'endTokenPos' => 1151,
                'endFilePos' => 8775,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startTokenPos' => 1921,
                'startFilePos' => 13363,
                'endTokenPos' => 1921,
                'endFilePos' => 13366,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startTokenPos' => 1978,
                'startFilePos' => 13668,
                'endTokenPos' => 1978,
                'endFilePos' => 13674,
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
                'startTokenPos' => 1983,
                'startFilePos' => 13685,
                'endTokenPos' => 1983,
                'endFilePos' => 13690,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startTokenPos' => 2169,
                'startFilePos' => 14892,
                'endTokenPos' => 2169,
                'endFilePos' => 14898,
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
                'startTokenPos' => 2174,
                'startFilePos' => 14909,
                'endTokenPos' => 2174,
                'endFilePos' => 14914,
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
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startTokenPos' => 2330,
                'startFilePos' => 16180,
                'endTokenPos' => 2330,
                'endFilePos' => 16183,
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
                'startTokenPos' => 2335,
                'startFilePos' => 16195,
                'endTokenPos' => 2336,
                'endFilePos' => 16196,
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
                'startTokenPos' => 2343,
                'startFilePos' => 16218,
                'endTokenPos' => 2343,
                'endFilePos' => 16221,
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
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 543,
                'endLine' => 543,
                'startTokenPos' => 2713,
                'startFilePos' => 18560,
                'endTokenPos' => 2713,
                'endFilePos' => 18563,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 543,
            'endLine' => 543,
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
                'startLine' => 543,
                'endLine' => 543,
                'startTokenPos' => 2720,
                'startFilePos' => 18581,
                'endTokenPos' => 2721,
                'endFilePos' => 18582,
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
            'startLine' => 543,
            'endLine' => 543,
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
                'startLine' => 543,
                'endLine' => 543,
                'startTokenPos' => 2728,
                'startFilePos' => 18604,
                'endTokenPos' => 2728,
                'endFilePos' => 18607,
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
            'startLine' => 543,
            'endLine' => 543,
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
        'startLine' => 543,
        'endLine' => 575,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 582,
                'endLine' => 582,
                'startTokenPos' => 3017,
                'startFilePos' => 20111,
                'endTokenPos' => 3017,
                'endFilePos' => 20114,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 582,
            'endLine' => 582,
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
        'startLine' => 582,
        'endLine' => 589,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 596,
                'endLine' => 596,
                'startTokenPos' => 3085,
                'startFilePos' => 20573,
                'endTokenPos' => 3085,
                'endFilePos' => 20576,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 596,
            'endLine' => 596,
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
        'startLine' => 596,
        'endLine' => 603,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 610,
                'endLine' => 610,
                'startTokenPos' => 3153,
                'startFilePos' => 21006,
                'endTokenPos' => 3153,
                'endFilePos' => 21009,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 610,
            'endLine' => 610,
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
        'startLine' => 610,
        'endLine' => 617,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 624,
                'endLine' => 624,
                'startTokenPos' => 3221,
                'startFilePos' => 21427,
                'endTokenPos' => 3221,
                'endFilePos' => 21430,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 624,
            'endLine' => 624,
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
        'startLine' => 624,
        'endLine' => 631,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
        'startLine' => 637,
        'endLine' => 642,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 650,
                'endLine' => 650,
                'startTokenPos' => 3347,
                'startFilePos' => 22240,
                'endTokenPos' => 3347,
                'endFilePos' => 22243,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
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
                'startLine' => 650,
                'endLine' => 650,
                'startTokenPos' => 3352,
                'startFilePos' => 22253,
                'endTokenPos' => 3352,
                'endFilePos' => 22256,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
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
        'startLine' => 650,
        'endLine' => 659,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
            'startLine' => 667,
            'endLine' => 667,
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
                'startLine' => 667,
                'endLine' => 667,
                'startTokenPos' => 3434,
                'startFilePos' => 22807,
                'endTokenPos' => 3434,
                'endFilePos' => 22810,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 667,
            'endLine' => 667,
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
        'startLine' => 667,
        'endLine' => 675,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 684,
                'endLine' => 684,
                'startTokenPos' => 3508,
                'startFilePos' => 23398,
                'endTokenPos' => 3508,
                'endFilePos' => 23401,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 684,
            'endLine' => 684,
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
                'startLine' => 684,
                'endLine' => 684,
                'startTokenPos' => 3513,
                'startFilePos' => 23417,
                'endTokenPos' => 3513,
                'endFilePos' => 23420,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 684,
            'endLine' => 684,
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
                'startLine' => 684,
                'endLine' => 684,
                'startTokenPos' => 3518,
                'startFilePos' => 23430,
                'endTokenPos' => 3518,
                'endFilePos' => 23433,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 684,
            'endLine' => 684,
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
        'startLine' => 684,
        'endLine' => 695,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
            'startLine' => 706,
            'endLine' => 706,
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
        'startLine' => 706,
        'endLine' => 715,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
            'startLine' => 726,
            'endLine' => 726,
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
        'startLine' => 726,
        'endLine' => 734,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 741,
                'endLine' => 741,
                'startTokenPos' => 3889,
                'startFilePos' => 25178,
                'endTokenPos' => 3889,
                'endFilePos' => 25181,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 741,
            'endLine' => 741,
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
        'startLine' => 741,
        'endLine' => 753,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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
                'startLine' => 760,
                'endLine' => 760,
                'startTokenPos' => 3967,
                'startFilePos' => 25738,
                'endTokenPos' => 3967,
                'endFilePos' => 25741,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 760,
            'endLine' => 760,
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
        'startLine' => 760,
        'endLine' => 776,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Modules\\Restore\\Formats\\Helpers',
        'declaringClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'implementingClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
        'currentClassName' => 'Modules\\Restore\\Formats\\Helpers\\SpreadsheetHelper',
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