<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/Zip/Zip.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Utils\Zip\Zip
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-94fd6b90820e69fa069cc5c98f1db6be77d11aa619e21c2cebd96bfee27c7147',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/Zip/Zip.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Utils\\Zip',
    'name' => 'MicroweberPackages\\Utils\\Zip\\Zip',
    'shortName' => 'Zip',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class to create and manage a Zip file.
 *
 * Inspired by CreateZipFile by Rochak Chauhan  www.rochakchauhan.com (http://www.phpclasses.org/browse/package/2322.html)
 * and
 * http://www.pkware.com/documents/casestudies/APPNOTE.TXT Zip file specification.
 *
 * License: GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 *
 * @author A. Grandt <php@grandt.com>
 * @copyright 2009-2013 A. Grandt
 * @license GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 *
 * @link http://www.phpclasses.org/package/6110
 * @link https://github.com/Grandt/PHPZip
 *
 * @version 1.38
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 44,
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
      'VERSION' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1.38',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 33,
            'startFilePos' => 1458,
            'endTokenPos' => 33,
            'endFilePos' => 1461,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 25,
      ),
      'ZIP_LOCAL_FILE_HEADER' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'ZIP_LOCAL_FILE_HEADER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"PK\\x03\\x04"',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 42,
            'startFilePos' => 1499,
            'endTokenPos' => 42,
            'endFilePos' => 1516,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'ZIP_CENTRAL_FILE_HEADER' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'ZIP_CENTRAL_FILE_HEADER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"PK\\x01\\x02"',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 53,
            'startFilePos' => 1586,
            'endTokenPos' => 53,
            'endFilePos' => 1603,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'ZIP_END_OF_CENTRAL_DIRECTORY' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'ZIP_END_OF_CENTRAL_DIRECTORY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"PK\\x05\\x06\\x00\\x00\\x00\\x00"',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 64,
            'startFilePos' => 1680,
            'endTokenPos' => 64,
            'endFilePos' => 1713,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 76,
      ),
      'EXT_FILE_ATTR_DIR' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'EXT_FILE_ATTR_DIR',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x10\\x00\\xffA"',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 75,
            'startFilePos' => 1781,
            'endTokenPos' => 75,
            'endFilePos' => 1798,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'EXT_FILE_ATTR_FILE' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'EXT_FILE_ATTR_FILE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x00\\x00\\xff\\x81"',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 84,
            'startFilePos' => 1832,
            'endTokenPos' => 84,
            'endFilePos' => 1849,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'ATTR_VERSION_TO_EXTRACT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'ATTR_VERSION_TO_EXTRACT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x14\\x00"',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 93,
            'startFilePos' => 1889,
            'endTokenPos' => 93,
            'endFilePos' => 1898,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'ATTR_MADE_BY_VERSION' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'ATTR_MADE_BY_VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"\\x1e\\x03"',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 104,
            'startFilePos' => 1963,
            'endTokenPos' => 104,
            'endFilePos' => 1972,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
    ),
    'immediateProperties' => 
    array (
      'zipMemoryThreshold' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'zipMemoryThreshold',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '1048576',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 115,
            'startFilePos' => 2029,
            'endTokenPos' => 115,
            'endFilePos' => 2035,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'zipData' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'zipData',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 126,
            'startFilePos' => 2130,
            'endTokenPos' => 126,
            'endFilePos' => 2133,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'zipFile' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'zipFile',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 135,
            'startFilePos' => 2159,
            'endTokenPos' => 135,
            'endFilePos' => 2162,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'zipComment' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'zipComment',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 144,
            'startFilePos' => 2191,
            'endTokenPos' => 144,
            'endFilePos' => 2194,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'cdRec',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 153,
            'startFilePos' => 2218,
            'endTokenPos' => 155,
            'endFilePos' => 2224,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'offset',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 166,
            'startFilePos' => 2270,
            'endTokenPos' => 166,
            'endFilePos' => 2270,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'isFinalized',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 175,
            'startFilePos' => 2300,
            'endTokenPos' => 175,
            'endFilePos' => 2304,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'addExtraField',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 184,
            'startFilePos' => 2336,
            'endTokenPos' => 184,
            'endFilePos' => 2339,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamChunkSize',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '65536',
          'attributes' => 
          array (
            'startLine' => 68,
            'endLine' => 68,
            'startTokenPos' => 193,
            'startFilePos' => 2374,
            'endTokenPos' => 193,
            'endFilePos' => 2378,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamFilePath',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 202,
            'startFilePos' => 2411,
            'endTokenPos' => 202,
            'endFilePos' => 2414,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamTimeStamp',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 211,
            'startFilePos' => 2448,
            'endTokenPos' => 211,
            'endFilePos' => 2451,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamComment',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 71,
            'endLine' => 71,
            'startTokenPos' => 220,
            'startFilePos' => 2483,
            'endTokenPos' => 220,
            'endFilePos' => 2486,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 71,
        'endLine' => 71,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamFile',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 229,
            'startFilePos' => 2515,
            'endTokenPos' => 229,
            'endFilePos' => 2518,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamData',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 73,
            'endLine' => 73,
            'startTokenPos' => 238,
            'startFilePos' => 2547,
            'endTokenPos' => 238,
            'endFilePos' => 2550,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 73,
        'endLine' => 73,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'name' => 'streamFileLength',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 74,
            'endLine' => 74,
            'startTokenPos' => 247,
            'startFilePos' => 2585,
            'endTokenPos' => 247,
            'endFilePos' => 2585,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 74,
        'endLine' => 74,
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
          'useZipFile' => 
          array (
            'name' => 'useZipFile',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 81,
                'endLine' => 81,
                'startTokenPos' => 262,
                'startFilePos' => 2756,
                'endTokenPos' => 262,
                'endFilePos' => 2760,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 33,
            'endColumn' => 51,
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
 * Constructor.
 *
 * @param bool $useZipFile Write temp zip data to tempFile? Default FALSE
 */',
        'startLine' => 81,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
        'startLine' => 90,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
                'startLine' => 104,
                'endLine' => 104,
                'startTokenPos' => 361,
                'startFilePos' => 3494,
                'endTokenPos' => 361,
                'endFilePos' => 3497,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
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
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
                'startLine' => 116,
                'endLine' => 116,
                'startTokenPos' => 395,
                'startFilePos' => 3768,
                'endTokenPos' => 395,
                'endFilePos' => 3771,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
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
 * @param string $newComment New comment. NULL to clear.
 *
 * @return bool $success
 */',
        'startLine' => 116,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'setZipFile' => 
      array (
        'name' => 'setZipFile',
        'parameters' => 
        array (
          'fileName' => 
          array (
            'name' => 'fileName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 32,
            'endColumn' => 40,
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
 * Set zip file to write zip data to.
 * This will cause all present and future data written to this class to be written to this file.
 * This can be used at any time, even after the Zip Archive have been finalized. Any previous file will be closed.
 * Warning: If the given file already exists, it will be overwritten.
 *
 * @param string $fileName
 *
 * @return bool $success
 */',
        'startLine' => 136,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 168,
            'endLine' => 168,
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
                'startLine' => 168,
                'endLine' => 168,
                'startTokenPos' => 601,
                'startFilePos' => 5536,
                'endTokenPos' => 601,
                'endFilePos' => 5536,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 168,
            'endLine' => 168,
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
                'startLine' => 168,
                'endLine' => 168,
                'startTokenPos' => 608,
                'startFilePos' => 5554,
                'endTokenPos' => 608,
                'endFilePos' => 5557,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 168,
            'endLine' => 168,
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
        'startLine' => 168,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 196,
            'endLine' => 196,
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
            'startLine' => 196,
            'endLine' => 196,
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
                'startLine' => 196,
                'endLine' => 196,
                'startTokenPos' => 742,
                'startFilePos' => 6724,
                'endTokenPos' => 742,
                'endFilePos' => 6724,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
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
                'startLine' => 196,
                'endLine' => 196,
                'startTokenPos' => 749,
                'startFilePos' => 6742,
                'endTokenPos' => 749,
                'endFilePos' => 6745,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
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
                'startLine' => 196,
                'endLine' => 196,
                'startTokenPos' => 756,
                'startFilePos' => 6760,
                'endTokenPos' => 756,
                'endFilePos' => 6763,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
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
        'startLine' => 196,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 255,
            'endLine' => 255,
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
            'startLine' => 255,
            'endLine' => 255,
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
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1105,
                'startFilePos' => 9367,
                'endTokenPos' => 1105,
                'endFilePos' => 9370,
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
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1112,
                'startFilePos' => 9391,
                'endTokenPos' => 1112,
                'endFilePos' => 9394,
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
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1120,
                'startFilePos' => 9412,
                'endTokenPos' => 1122,
                'endFilePos' => 9418,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 255,
            'endLine' => 255,
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
        'startLine' => 255,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 296,
            'endLine' => 296,
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
            'startLine' => 296,
            'endLine' => 296,
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
                'startLine' => 296,
                'endLine' => 296,
                'startTokenPos' => 1392,
                'startFilePos' => 11171,
                'endTokenPos' => 1392,
                'endFilePos' => 11171,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 296,
            'endLine' => 296,
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
                'startLine' => 296,
                'endLine' => 296,
                'startTokenPos' => 1399,
                'startFilePos' => 11189,
                'endTokenPos' => 1399,
                'endFilePos' => 11192,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 296,
            'endLine' => 296,
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
        'startLine' => 296,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 326,
            'endLine' => 326,
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
                'startLine' => 326,
                'endLine' => 326,
                'startTokenPos' => 1565,
                'startFilePos' => 12326,
                'endTokenPos' => 1565,
                'endFilePos' => 12326,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
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
                'startLine' => 326,
                'endLine' => 326,
                'startTokenPos' => 1572,
                'startFilePos' => 12344,
                'endTokenPos' => 1572,
                'endFilePos' => 12347,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
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
        'startLine' => 326,
        'endLine' => 350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 358,
            'endLine' => 358,
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
        'startLine' => 358,
        'endLine' => 371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
        'startLine' => 378,
        'endLine' => 401,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 403,
            'endLine' => 403,
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
            'startLine' => 403,
            'endLine' => 403,
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
                'startLine' => 403,
                'endLine' => 403,
                'startTokenPos' => 2001,
                'startFilePos' => 14502,
                'endTokenPos' => 2001,
                'endFilePos' => 14502,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 403,
            'endLine' => 403,
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
                'startLine' => 403,
                'endLine' => 403,
                'startTokenPos' => 2008,
                'startFilePos' => 14520,
                'endTokenPos' => 2008,
                'endFilePos' => 14523,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 403,
            'endLine' => 403,
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
        'startLine' => 403,
        'endLine' => 453,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
        'startLine' => 461,
        'endLine' => 489,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'getZipFile' => 
      array (
        'name' => 'getZipFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the handle ressource for the archive zip file.
 * If the zip haven\'t been finalized yet, this will cause it to become finalized.
 *
 * @return zip file handle
 */',
        'startLine' => 497,
        'endLine' => 508,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'getZipData' => 
      array (
        'name' => 'getZipData',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the zip file contents
 * If the zip haven\'t been finalized yet, this will cause it to become finalized.
 *
 * @return zip data
 */',
        'startLine' => 516,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'sendZip' => 
      array (
        'name' => 'sendZip',
        'parameters' => 
        array (
          'fileName' => 
          array (
            'name' => 'fileName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 539,
            'endLine' => 539,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'contentType' => 
          array (
            'name' => 'contentType',
            'default' => 
            array (
              'code' => '\'application/zip\'',
              'attributes' => 
              array (
                'startLine' => 539,
                'endLine' => 539,
                'startTokenPos' => 2787,
                'startFilePos' => 18207,
                'endTokenPos' => 2787,
                'endFilePos' => 18223,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 539,
            'endLine' => 539,
            'startColumn' => 40,
            'endColumn' => 71,
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
 * Send the archive as a zip download.
 *
 * @param string $fileName    The name of the Zip archive, ie. "archive.zip".
 * @param string $contentType Content mime type. Optional, defaults to "application/zip".
 *
 * @return bool $success
 */',
        'startLine' => 539,
        'endLine' => 578,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'getArchiveSize' => 
      array (
        'name' => 'getArchiveSize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the current size of the archive.
 *
 * @return $size Size of the archive
 */',
        'startLine' => 585,
        'endLine' => 593,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
                'startLine' => 602,
                'endLine' => 602,
                'startTokenPos' => 3148,
                'startFilePos' => 20475,
                'endTokenPos' => 3148,
                'endFilePos' => 20475,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 602,
            'endLine' => 602,
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
        'startLine' => 602,
        'endLine' => 615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
            'startLine' => 630,
            'endLine' => 630,
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
        'startLine' => 630,
        'endLine' => 692,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'zipwrite' => 
      array (
        'name' => 'zipwrite',
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
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 31,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 694,
        'endLine' => 702,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'aliasName' => NULL,
      ),
      'zipflush' => 
      array (
        'name' => 'zipflush',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 704,
        'endLine' => 711,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 719,
            'endLine' => 719,
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
            'startLine' => 719,
            'endLine' => 719,
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
        'startLine' => 719,
        'endLine' => 726,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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
            'startLine' => 737,
            'endLine' => 737,
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
        'startLine' => 737,
        'endLine' => 776,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Utils\\Zip',
        'declaringClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'implementingClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
        'currentClassName' => 'MicroweberPackages\\Utils\\Zip\\Zip',
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