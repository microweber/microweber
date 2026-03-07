<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Restore/DatabaseWriter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Restore\DatabaseWriter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-d7fc3687290087d9a6c976b8c8677fbe02542fd8ffd2cb897fce8ec74cd1a4db',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Restore\\DatabaseWriter',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Restore/DatabaseWriter.php',
      ),
    ),
    'namespace' => 'Modules\\Restore',
    'name' => 'Modules\\Restore\\DatabaseWriter',
    'shortName' => 'DatabaseWriter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Microweber - Backup Module Database Writer
 * @namespace MicroweberPackages\\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 541,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Modules\\Restore\\Traits\\DatabaseMediaWriter',
      1 => 'Modules\\Restore\\Traits\\DatabaseModuleWriter',
      2 => 'Modules\\Restore\\Traits\\DatabaseMenusWriter',
      3 => 'Modules\\Restore\\Traits\\DatabaseRelationWriter',
      4 => 'Modules\\Restore\\Traits\\DatabaseCustomFieldsWriter',
      5 => 'Modules\\Restore\\Traits\\DatabaseContentWriter',
      6 => 'Modules\\Restore\\Traits\\DatabaseContentFieldsWriter',
      7 => 'Modules\\Restore\\Traits\\DatabaseContentDataWriter',
      8 => 'Modules\\Restore\\Traits\\DatabaseCategoriesWriter',
      9 => 'Modules\\Restore\\Traits\\DatabaseCategoryItemsWriter',
      10 => 'Modules\\Restore\\Traits\\DatabaseTaggingTaggedWriter',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'overwriteById' => 
      array (
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'name' => 'overwriteById',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 153,
            'startFilePos' => 1389,
            'endTokenPos' => 153,
            'endFilePos' => 1393,
          ),
        ),
        'docComment' => '/**
 * Overwrite by id
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'deleteOldContent' => 
      array (
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'name' => 'deleteOldContent',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 164,
            'startFilePos' => 1487,
            'endTokenPos' => 164,
            'endFilePos' => 1491,
          ),
        ),
        'docComment' => '/**
 * Delete old content
 * @var bool
 */',
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
      'deleteOldCssFiles' => 
      array (
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'name' => 'deleteOldCssFiles',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 175,
            'startFilePos' => 1561,
            'endTokenPos' => 175,
            'endFilePos' => 1565,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'content' => 
      array (
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'name' => 'content',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 184,
            'startFilePos' => 1592,
            'endTokenPos' => 185,
            'endFilePos' => 1593,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'logger' => 
      array (
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'name' => 'logger',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 19,
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
      'setContent' => 
      array (
        'name' => 'setContent',
        'parameters' => 
        array (
          'content' => 
          array (
            'name' => 'content',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 32,
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
        'docComment' => NULL,
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'setOverwriteById' => 
      array (
        'name' => 'setOverwriteById',
        'parameters' => 
        array (
          'overwrite' => 
          array (
            'name' => 'overwrite',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 38,
            'endColumn' => 47,
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
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'setDeleteOldContent' => 
      array (
        'name' => 'setDeleteOldContent',
        'parameters' => 
        array (
          'delete' => 
          array (
            'name' => 'delete',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 41,
            'endColumn' => 47,
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
        'startLine' => 79,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'setDeleteOldCssFiles' => 
      array (
        'name' => 'setDeleteOldCssFiles',
        'parameters' => 
        array (
          'delete' => 
          array (
            'name' => 'delete',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 42,
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
        'docComment' => NULL,
        'startLine' => 84,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'setLogger' => 
      array (
        'name' => 'setLogger',
        'parameters' => 
        array (
          'logger' => 
          array (
            'name' => 'logger',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 31,
            'endColumn' => 37,
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
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_unsetItemFields' => 
      array (
        'name' => '_unsetItemFields',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 39,
            'endColumn' => 43,
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
 * Unset item fields
 * @param array $item
 * @return array
 */',
        'startLine' => 99,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_saveItemDatabase' => 
      array (
        'name' => '_saveItemDatabase',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 40,
            'endColumn' => 44,
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
        'startLine' => 108,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_getItemFriendlyName' => 
      array (
        'name' => '_getItemFriendlyName',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 43,
            'endColumn' => 47,
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
        'startLine' => 259,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_saveItem' => 
      array (
        'name' => '_saveItem',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 32,
            'endColumn' => 36,
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
 * Save item in database
 * @param string $table
 * @param array $item
 */',
        'startLine' => 276,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'runWriter' => 
      array (
        'name' => 'runWriter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run database writer.
 * @return string[]
 */',
        'startLine' => 298,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'runWriterWithBatch' => 
      array (
        'name' => 'runWriterWithBatch',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 346,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'getRestoreLog' => 
      array (
        'name' => 'getRestoreLog',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 435,
        'endLine' => 465,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_deleteOldCssFiles' => 
      array (
        'name' => '_deleteOldCssFiles',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 467,
        'endLine' => 479,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_deleteOldContent' => 
      array (
        'name' => '_deleteOldContent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 481,
        'endLine' => 501,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      '_finishUp' => 
      array (
        'name' => '_finishUp',
        'parameters' => 
        array (
          'callFrom' => 
          array (
            'name' => 'callFrom',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 506,
                'endLine' => 506,
                'startTokenPos' => 3303,
                'startFilePos' => 16159,
                'endTokenPos' => 3303,
                'endFilePos' => 16160,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 506,
            'endLine' => 506,
            'startColumn' => 32,
            'endColumn' => 45,
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
 * Clear all cache on framework
 */',
        'startLine' => 506,
        'endLine' => 523,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'log' => 
      array (
        'name' => 'log',
        'parameters' => 
        array (
          'msg' => 
          array (
            'name' => 'msg',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 526,
            'endLine' => 526,
            'startColumn' => 25,
            'endColumn' => 28,
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
        'startLine' => 526,
        'endLine' => 533,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
        'aliasName' => NULL,
      ),
      'clearLog' => 
      array (
        'name' => 'clearLog',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 535,
        'endLine' => 540,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Restore',
        'declaringClassName' => 'Modules\\Restore\\DatabaseWriter',
        'implementingClassName' => 'Modules\\Restore\\DatabaseWriter',
        'currentClassName' => 'Modules\\Restore\\DatabaseWriter',
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