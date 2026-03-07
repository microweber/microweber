<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Backup/Formats/ZipBatchBackup.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Backup\Formats\ZipBatchBackup
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-df69a354525c15f185b4265d514d0c9c3236f33113c75eeadb0051c621895946',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Backup/Formats/ZipBatchBackup.php',
      ),
    ),
    'namespace' => 'Modules\\Backup\\Formats',
    'name' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
    'shortName' => 'ZipBatchBackup',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 727,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Backup\\Formats\\DefaultBackup',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Modules\\Backup\\Traits\\BackupGetSet',
      1 => 'Modules\\Backup\\Traits\\BackupFileNameGetSet',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'type' => 
      array (
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'name' => 'type',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'zip\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 50,
            'startFilePos' => 344,
            'endTokenPos' => 50,
            'endFilePos' => 348,
          ),
        ),
        'docComment' => '/**
 * The type of export
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
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
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'name' => 'files',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 61,
            'startFilePos' => 426,
            'endTokenPos' => 63,
            'endFilePos' => 432,
          ),
        ),
        'docComment' => '/**
 * Files in zip
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
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
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'name' => '_cacheGroupName',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'BackupExporting\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 74,
            'startFilePos' => 550,
            'endTokenPos' => 74,
            'endFilePos' => 566,
          ),
        ),
        'docComment' => '/**
 * The name of cache group for backup file.
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
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
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'name' => 'logger',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
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
            'startLine' => 38,
            'endLine' => 38,
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
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 43,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
            'startLine' => 68,
            'endLine' => 68,
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
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 73,
        'endLine' => 445,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 447,
        'endLine' => 484,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 487,
        'endLine' => 526,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 528,
        'endLine' => 560,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 562,
        'endLine' => 637,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 639,
        'endLine' => 694,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
            'startLine' => 696,
            'endLine' => 696,
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
        'startLine' => 696,
        'endLine' => 716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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
        'startLine' => 721,
        'endLine' => 726,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\ZipBatchBackup',
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