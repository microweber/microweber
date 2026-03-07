<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/Zip/ZipStream.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Utils\Zip\ZipStream
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-0e481a286a7ef0d86746957677aca995200c5d9482e36db641172700253439ac',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/Zip/ZipStream.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Utils\\Zip',
    'name' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
    'shortName' => 'ZipStream',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class to create zip file, aimed at large files, or even large target zip file.
 * This class will stream the generated zip file directly to the HTTP client as the content is added.
 *
 * If you need the Zip file data on the server, for storage in a database of the server file system, look at
 *  the Zip class at http://www.phpclasses.org/browse/package/6110.html
 *
 * License: GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 *
 * Inspired on CreateZipFile by Rochak Chauhan  www.rochakchauhan.com (http://www.phpclasses.org/browse/package/2322.html)
 * and
 * http://www.pkware.com/documents/casestudies/APPNOTE.TXT Zip file specification.
 *
 * @author A. Grandt <php@grandt.com>
 * @copyright 2009-2013 A. Grandt
 * @license GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 *
 * @link http://www.phpclasses.org/package/6116
 * @link https://github.com/Grandt/PHPZip
 *
 * @version 1.38
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 621,
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
      'VERSION' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1.38',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 31,
            'startFilePos' => 1121,
            'endTokenPos' => 31,
            'endFilePos' => 1124,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 25,
      ),
      'ZIP_LOCAL_FILE_HEADER' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'ZIP_LOCAL_FILE_HEADER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"PK\\x03\\x04"',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 40,
            'startFilePos' => 1162,
            'endTokenPos' => 40,
            'endFilePos' => 1179,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'ZIP_CENTRAL_FILE_HEADER' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'ZIP_CENTRAL_FILE_HEADER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"PK\\x01\\x02"',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 51,
            'startFilePos' => 1249,
            'endTokenPos' => 51,
            'endFilePos' => 1266,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'ZIP_END_OF_CENTRAL_DIRECTORY' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'ZIP_END_OF_CENTRAL_DIRECTORY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"PK\\x05\\x06\\x00\\x00\\x00\\x00"',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 62,
            'startFilePos' => 1343,
            'endTokenPos' => 62,
            'endFilePos' => 1376,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 76,
      ),
      'EXT_FILE_ATTR_DIR' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'EXT_FILE_ATTR_DIR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x10\\x00\\xffA"',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 73,
            'startFilePos' => 1444,
            'endTokenPos' => 73,
            'endFilePos' => 1461,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'EXT_FILE_ATTR_FILE' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'EXT_FILE_ATTR_FILE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x00\\x00\\xff\\x81"',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 82,
            'startFilePos' => 1495,
            'endTokenPos' => 82,
            'endFilePos' => 1512,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'ATTR_VERSION_TO_EXTRACT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'ATTR_VERSION_TO_EXTRACT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x14\\x00"',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 91,
            'startFilePos' => 1552,
            'endTokenPos' => 91,
            'endFilePos' => 1561,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'ATTR_MADE_BY_VERSION' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'ATTR_MADE_BY_VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x1e\\x03"',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 102,
            'startFilePos' => 1626,
            'endTokenPos' => 102,
            'endFilePos' => 1635,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
    ),
    'immediateProperties' => 
    array (
      'zipMemoryThreshold' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'zipMemoryThreshold',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '1048576',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 113,
            'startFilePos' => 1692,
            'endTokenPos' => 113,
            'endFilePos' => 1698,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'zipComment' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'zipComment',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 124,
            'startFilePos' => 1796,
            'endTokenPos' => 124,
            'endFilePos' => 1799,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cdRec' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'cdRec',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 133,
            'startFilePos' => 1823,
            'endTokenPos' => 135,
            'endFilePos' => 1829,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'offset' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'offset',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 146,
            'startFilePos' => 1875,
            'endTokenPos' => 146,
            'endFilePos' => 1875,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isFinalized' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'isFinalized',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 155,
            'startFilePos' => 1905,
            'endTokenPos' => 155,
            'endFilePos' => 1909,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'addExtraField' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'addExtraField',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 164,
            'startFilePos' => 1941,
            'endTokenPos' => 164,
            'endFilePos' => 1944,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamChunkSize' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamChunkSize',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '16384',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 173,
            'startFilePos' => 1979,
            'endTokenPos' => 173,
            'endFilePos' => 1983,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamFilePath' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamFilePath',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 184,
            'startFilePos' => 2026,
            'endTokenPos' => 184,
            'endFilePos' => 2029,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamTimeStamp' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamTimeStamp',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 193,
            'startFilePos' => 2063,
            'endTokenPos' => 193,
            'endFilePos' => 2066,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamComment' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamComment',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 202,
            'startFilePos' => 2098,
            'endTokenPos' => 202,
            'endFilePos' => 2101,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamFile' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamFile',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 211,
            'startFilePos' => 2130,
            'endTokenPos' => 211,
            'endFilePos' => 2133,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamData' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamData',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 220,
            'startFilePos' => 2162,
            'endTokenPos' => 220,
            'endFilePos' => 2165,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamFileLength' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'name' => 'streamFileLength',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 229,
            'startFilePos' => 2200,
            'endTokenPos' => 229,
            'endFilePos' => 2200,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 34,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'archiveName' => 
          array (
            'name' => 'archiveName',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 244,
                'startFilePos' => 2455,
                'endTokenPos' => 244,
                'endFilePos' => 2456,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'contentType' => 
          array (
            'name' => 'contentType',
            'default' => 
            array (
              'code' => '\'application/zip\'',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 251,
                'startFilePos' => 2474,
                'endTokenPos' => 251,
                'endFilePos' => 2490,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 52,
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
 * Constructor.
 *
 * @param string $archiveName Name to send to the HTTP client.
 * @param string $contentType Content mime type. Optional, defaults to "application/zip".
 */',
        'startLine' => 66,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      '__destruct' => 
      array (
        'name' => '__destruct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 93,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'setExtraField' => 
      array (
        'name' => 'setExtraField',
        'parameters' => 
        array (
          'setExtraField' => 
          array (
            'name' => 'setExtraField',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 502,
                'startFilePos' => 4421,
                'endTokenPos' => 502,
                'endFilePos' => 4424,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 35,
            'endColumn' => 55,
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
 * Extra fields on the Zip directory records are Unix time codes needed for compatibility on the default Mac zip archive tool.
 * These are enabled as default, as they do no harm elsewhere and only add 26 bytes per file added.
 *
 * @param bool $setExtraField TRUE (default) will enable adding of extra fields, anything else will disable it.
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
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'setComment' => 
      array (
        'name' => 'setComment',
        'parameters' => 
        array (
          'newComment' => 
          array (
            'name' => 'newComment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 118,
                'endLine' => 118,
                'startTokenPos' => 536,
                'startFilePos' => 4695,
                'endTokenPos' => 536,
                'endFilePos' => 4698,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 32,
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
 * Set Zip archive comment.
 *
 * @param string $newComment New comment. null to clear.
 *
 * @return bool $success
 */',
        'startLine' => 118,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'addDirectory' => 
      array (
        'name' => 'addDirectory',
        'parameters' => 
        array (
          'directoryPath' => 
          array (
            'name' => 'directoryPath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 138,
                'endLine' => 138,
                'startTokenPos' => 589,
                'startFilePos' => 5463,
                'endTokenPos' => 589,
                'endFilePos' => 5463,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 50,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'fileComment' => 
          array (
            'name' => 'fileComment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 138,
                'endLine' => 138,
                'startTokenPos' => 596,
                'startFilePos' => 5481,
                'endTokenPos' => 596,
                'endFilePos' => 5484,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 66,
            'endColumn' => 84,
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
 * Add an empty directory entry to the zip archive.
 * Basically this is only used if an empty directory is added.
 *
 * @param string $directoryPath Directory Path and name to be added to the archive.
 * @param int    $timestamp     (Optional) Timestamp for the added directory, if omitted or set to 0, the current time will be used.
 * @param string $fileComment   (Optional) Comment to be added to the archive for this directory. To use fileComment, timestamp must be given.
 *
 * @return bool $success
 */',
        'startLine' => 138,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'addFile' => 
      array (
        'name' => 'addFile',
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
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 29,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'filePath' => 
          array (
            'name' => 'filePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 36,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 167,
                'endLine' => 167,
                'startTokenPos' => 730,
                'startFilePos' => 6652,
                'endTokenPos' => 730,
                'endFilePos' => 6652,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 47,
            'endColumn' => 60,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'fileComment' => 
          array (
            'name' => 'fileComment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 167,
                'endLine' => 167,
                'startTokenPos' => 737,
                'startFilePos' => 6670,
                'endTokenPos' => 737,
                'endFilePos' => 6673,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 63,
            'endColumn' => 81,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'compress' => 
          array (
            'name' => 'compress',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 167,
                'endLine' => 167,
                'startTokenPos' => 744,
                'startFilePos' => 6688,
                'endTokenPos' => 744,
                'endFilePos' => 6691,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 84,
            'endColumn' => 99,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a file to the archive at the specified location and file name.
 *
 * @param string $data        File data.
 * @param string $filePath    Filepath and name to be used in the archive.
 * @param int    $timestamp   (Optional) Timestamp for the added file, if omitted or set to 0, the current time will be used.
 * @param string $fileComment (Optional) Comment to be added to the archive for this file. To use fileComment, timestamp must be given.
 * @param bool   $compress    (Optional) Compress file, if set to FALSE the file will only be stored. Default TRUE.
 *
 * @return bool $success
 */',
        'startLine' => 167,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'addDirectoryContent' => 
      array (
        'name' => 'addDirectoryContent',
        'parameters' => 
        array (
          'realPath' => 
          array (
            'name' => 'realPath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'zipPath' => 
          array (
            'name' => 'zipPath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 52,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'recursive' => 
          array (
            'name' => 'recursive',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 1049,
                'startFilePos' => 9138,
                'endTokenPos' => 1049,
                'endFilePos' => 9141,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 62,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'followSymlinks' => 
          array (
            'name' => 'followSymlinks',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 1056,
                'startFilePos' => 9162,
                'endTokenPos' => 1056,
                'endFilePos' => 9165,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 81,
            'endColumn' => 102,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'addedFiles' => 
          array (
            'name' => 'addedFiles',
            'default' => 
            array (
              'code' => 'array()',
              'attributes' => 
              array (
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 1064,
                'startFilePos' => 9183,
                'endTokenPos' => 1066,
                'endFilePos' => 9189,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 105,
            'endColumn' => 126,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the content to a directory.
 *
 * @author Adam Schmalhofer <Adam.Schmalhofer@gmx.de>
 * @author A. Grandt
 *
 * @param string $realPath       Path on the file system.
 * @param string $zipPath        Filepath and name to be used in the archive.
 * @param bool   $recursive      Add content recursively, default is TRUE.
 * @param bool   $followSymlinks Follow and add symbolic links, if they are accessible, default is TRUE.
 * @param array  &$addedFiles    Reference to the added files, this is used to prevent duplicates, efault is an empty array.
 *                               If you start the function by parsing an array, the array will be populated with the realPath
 *                               and zipPath kay/value pairs added to the archive by the function.
 */',
        'startLine' => 222,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'addLargeFile' => 
      array (
        'name' => 'addLargeFile',
        'parameters' => 
        array (
          'dataFile' => 
          array (
            'name' => 'dataFile',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 34,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'filePath' => 
          array (
            'name' => 'filePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 45,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 263,
                'endLine' => 263,
                'startTokenPos' => 1336,
                'startFilePos' => 10942,
                'endTokenPos' => 1336,
                'endFilePos' => 10942,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 56,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'fileComment' => 
          array (
            'name' => 'fileComment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 263,
                'endLine' => 263,
                'startTokenPos' => 1343,
                'startFilePos' => 10960,
                'endTokenPos' => 1343,
                'endFilePos' => 10963,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 72,
            'endColumn' => 90,
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
 * Add a file to the archive at the specified location and file name.
 *
 * @param string $dataFile    File name/path.
 * @param string $filePath    Filepath and name to be used in the archive.
 * @param int    $timestamp   (Optional) Timestamp for the added file, if omitted or set to 0, the current time will be used.
 * @param string $fileComment (Optional) Comment to be added to the archive for this file. To use fileComment, timestamp must be given.
 *
 * @return bool $success
 */',
        'startLine' => 263,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'openStream' => 
      array (
        'name' => 'openStream',
        'parameters' => 
        array (
          'filePath' => 
          array (
            'name' => 'filePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 293,
                'endLine' => 293,
                'startTokenPos' => 1509,
                'startFilePos' => 12097,
                'endTokenPos' => 1509,
                'endFilePos' => 12097,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 43,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'fileComment' => 
          array (
            'name' => 'fileComment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 293,
                'endLine' => 293,
                'startTokenPos' => 1516,
                'startFilePos' => 12115,
                'endTokenPos' => 1516,
                'endFilePos' => 12118,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 59,
            'endColumn' => 77,
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
 * Create a stream to be used for large entries.
 *
 * @param string $filePath    Filepath and name to be used in the archive.
 * @param int    $timestamp   (Optional) Timestamp for the added file, if omitted or set to 0, the current time will be used.
 * @param string $fileComment (Optional) Comment to be added to the archive for this file. To use fileComment, timestamp must be given.
 *
 * @return bool $success
 */',
        'startLine' => 293,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'addStreamData' => 
      array (
        'name' => 'addStreamData',
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
            'startLine' => 324,
            'endLine' => 324,
            'startColumn' => 35,
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
 * Add data to the open stream.
 *
 * @param string $data
 *
 * @return $length bytes added or FALSE if the archive is finalized or there are no open stream.
 */',
        'startLine' => 324,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'closeStream' => 
      array (
        'name' => 'closeStream',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Close the current stream.
 *
 * @return bool $success
 */',
        'startLine' => 344,
        'endLine' => 367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'processFile' => 
      array (
        'name' => 'processFile',
        'parameters' => 
        array (
          'dataFile' => 
          array (
            'name' => 'dataFile',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
            'startColumn' => 34,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'filePath' => 
          array (
            'name' => 'filePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
            'startColumn' => 45,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 369,
                'endLine' => 369,
                'startTokenPos' => 1938,
                'startFilePos' => 14252,
                'endTokenPos' => 1938,
                'endFilePos' => 14252,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
            'startColumn' => 56,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'fileComment' => 
          array (
            'name' => 'fileComment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 369,
                'endLine' => 369,
                'startTokenPos' => 1945,
                'startFilePos' => 14270,
                'endTokenPos' => 1945,
                'endFilePos' => 14273,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
            'startColumn' => 72,
            'endColumn' => 90,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 369,
        'endLine' => 415,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'finalize' => 
      array (
        'name' => 'finalize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Close the archive.
 * A closed archive can no longer have new files added to it.
 *
 * @return bool $success
 */',
        'startLine' => 423,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'getDosTime' => 
      array (
        'name' => 'getDosTime',
        'parameters' => 
        array (
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 463,
                'endLine' => 463,
                'startTokenPos' => 2557,
                'startFilePos' => 16848,
                'endTokenPos' => 2557,
                'endFilePos' => 16848,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 463,
            'endLine' => 463,
            'startColumn' => 33,
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
 * Calculate the 2 byte dostime used in the zip entries.
 *
 * @param int $timestamp
 *
 * @return 2-byte encoded DOS Date
 */',
        'startLine' => 463,
        'endLine' => 476,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'buildZipEntry' => 
      array (
        'name' => 'buildZipEntry',
        'parameters' => 
        array (
          'filePath' => 
          array (
            'name' => 'filePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 36,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fileComment' => 
          array (
            'name' => 'fileComment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'gpFlags' => 
          array (
            'name' => 'gpFlags',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 61,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'gzType' => 
          array (
            'name' => 'gzType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 71,
            'endColumn' => 77,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 80,
            'endColumn' => 89,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'fileCRC32' => 
          array (
            'name' => 'fileCRC32',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 92,
            'endColumn' => 101,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
          'gzLength' => 
          array (
            'name' => 'gzLength',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 104,
            'endColumn' => 112,
            'parameterIndex' => 6,
            'isOptional' => false,
          ),
          'dataLength' => 
          array (
            'name' => 'dataLength',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 115,
            'endColumn' => 125,
            'parameterIndex' => 7,
            'isOptional' => false,
          ),
          'extFileAttr' => 
          array (
            'name' => 'extFileAttr',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
            'startColumn' => 128,
            'endColumn' => 139,
            'parameterIndex' => 8,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the Zip file structures.
 *
 * @param string $filePath
 * @param string $fileComment
 * @param string $gpFlags
 * @param string $gzType
 * @param int    $timestamp
 * @param string $fileCRC32
 * @param int    $gzLength
 * @param int    $dataLength
 * @param int    $extFileAttr Use self::EXT_FILE_ATTR_FILE for files, self::EXT_FILE_ATTR_DIR for Directories.
 */',
        'startLine' => 491,
        'endLine' => 555,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'pathJoin' => 
      array (
        'name' => 'pathJoin',
        'parameters' => 
        array (
          'dir' => 
          array (
            'name' => 'dir',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 563,
            'endLine' => 563,
            'startColumn' => 37,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 563,
            'endLine' => 563,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Join $file to $dir path, and clean up any excess slashes.
 *
 * @param string $dir
 * @param string $file
 */',
        'startLine' => 563,
        'endLine' => 570,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'aliasName' => NULL,
      ),
      'getRelativePath' => 
      array (
        'name' => 'getRelativePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 581,
            'endLine' => 581,
            'startColumn' => 44,
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
 * Clean up a path, removing any unnecessary elements such as /./, // or redundant ../ segments.
 * If the path starts with a "/", it is deemed an absolute path and any /../ in the beginning is stripped off.
 * The returned path will not end in a "/".
 *
 * @param string $path The path to clean up
 *
 * @return string the clean path
 */',
        'startLine' => 581,
        'endLine' => 620,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\ZipStream',
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