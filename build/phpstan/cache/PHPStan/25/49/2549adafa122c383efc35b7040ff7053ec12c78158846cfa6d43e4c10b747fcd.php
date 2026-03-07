<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Export/Formats/ZipBatchExport.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Export\Formats\ZipBatchExport
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-32aecaaeff1c0b2918957b6a89a50ec85f045eff6a12aac15b454f7ac2f0c24b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Export/Formats/ZipBatchExport.php',
      ),
    ),
    'namespace' => 'Modules\\Export\\Formats',
    'name' => 'Modules\\Export\\Formats\\ZipBatchExport',
    'shortName' => 'ZipBatchExport',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 377,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Export\\Formats\\DefaultExport',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Modules\\Export\\Traits\\ExportGetSet',
      1 => 'Modules\\Export\\Traits\\ExportFileNameGetSet',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'type' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'name' => 'type',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'zip\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 60,
            'startFilePos' => 434,
            'endTokenPos' => 60,
            'endFilePos' => 438,
          ),
        ),
        'docComment' => '/**
 * The type of export
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'files' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'name' => 'files',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 71,
            'startFilePos' => 516,
            'endTokenPos' => 73,
            'endFilePos' => 522,
          ),
        ),
        'docComment' => '/**
 * Files in zip
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_cacheGroupName' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'name' => '_cacheGroupName',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'BackupExporting\'',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 84,
            'startFilePos' => 640,
            'endTokenPos' => 84,
            'endFilePos' => 656,
          ),
        ),
        'docComment' => '/**
 * The name of cache group for backup file.
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'logger' => 
      array (
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'name' => 'logger',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
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
            'startLine' => 42,
            'endLine' => 42,
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
        'docComment' => '/**
 * Set logger
 * @param class $logger
 */',
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_getZipFileName' => 
      array (
        'name' => '_getZipFileName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      'addFile' => 
      array (
        'name' => 'addFile',
        'parameters' => 
        array (
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
            'startLine' => 72,
            'endLine' => 72,
            'startColumn' => 29,
            'endColumn' => 33,
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
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      'start' => 
      array (
        'name' => 'start',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 77,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      'getExportLog' => 
      array (
        'name' => 'getExportLog',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 179,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_getTempalteFilesPaths' => 
      array (
        'name' => '_getTempalteFilesPaths',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 201,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_getUserFilesTemplatesPaths' => 
      array (
        'name' => '_getUserFilesTemplatesPaths',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 242,
        'endLine' => 274,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_getUserFilesModulesPaths' => 
      array (
        'name' => '_getUserFilesModulesPaths',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 276,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_getUserFilesPaths' => 
      array (
        'name' => '_getUserFilesPaths',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 307,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_getDirContents' => 
      array (
        'name' => '_getDirContents',
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
            'startLine' => 351,
            'endLine' => 351,
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
        'startLine' => 351,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'aliasName' => NULL,
      ),
      '_finishUp' => 
      array (
        'name' => '_finishUp',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Clear all cache
 */',
        'startLine' => 371,
        'endLine' => 375,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
        'currentClassName' => 'Modules\\Export\\Formats\\ZipBatchExport',
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