<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Backup/Formats/CsvBackup.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Backup\Formats\CsvBackup
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-09d3d63ea64e2e6004f1515545789ac8c656e02ffcd2a60911d4e01f4065a0bb',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Backup\\Formats\\CsvBackup',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Backup/Formats/CsvBackup.php',
      ),
    ),
    'namespace' => 'Modules\\Backup\\Formats',
    'name' => 'Modules\\Backup\\Formats\\CsvBackup',
    'shortName' => 'CsvBackup',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 54,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Backup\\Formats\\DefaultBackup',
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
      'type' => 
      array (
        'declaringClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'name' => 'type',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'csv\'',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 24,
            'startFilePos' => 148,
            'endTokenPos' => 24,
            'endFilePos' => 152,
          ),
        ),
        'docComment' => '/**
 * The type of export
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 2,
        'endColumn' => 22,
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
        'startLine' => 14,
        'endLine' => 41,
        'startColumn' => 2,
        'endColumn' => 2,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'aliasName' => NULL,
      ),
      'array2csv' => 
      array (
        'name' => 'array2csv',
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
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'delimiter' => 
          array (
            'name' => 'delimiter',
            'default' => 
            array (
              'code' => '\',\'',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 193,
                'startFilePos' => 821,
                'endTokenPos' => 193,
                'endFilePos' => 823,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 35,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'enclosure' => 
          array (
            'name' => 'enclosure',
            'default' => 
            array (
              'code' => '\'"\'',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 200,
                'startFilePos' => 839,
                'endTokenPos' => 200,
                'endFilePos' => 841,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 53,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'escape_char' => 
          array (
            'name' => 'escape_char',
            'default' => 
            array (
              'code' => '"\\\\"',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 207,
                'startFilePos' => 859,
                'endTokenPos' => 207,
                'endFilePos' => 862,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 71,
            'endColumn' => 89,
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
        'startLine' => 43,
        'endLine' => 53,
        'startColumn' => 2,
        'endColumn' => 2,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Backup\\Formats',
        'declaringClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'implementingClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
        'currentClassName' => 'Modules\\Backup\\Formats\\CsvBackup',
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