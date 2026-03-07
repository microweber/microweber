<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Export/Formats/CsvExport.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Export\Formats\CsvExport
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-5041c275e18091fbaa05f63daf78820e2ca06bd514d5ccf9a2d356286419fc56',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Export\\Formats\\CsvExport',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Export/Formats/CsvExport.php',
      ),
    ),
    'namespace' => 'Modules\\Export\\Formats',
    'name' => 'Modules\\Export\\Formats\\CsvExport',
    'shortName' => 'CsvExport',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 4,
    'endLine' => 61,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Export\\Formats\\DefaultExport',
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
        'declaringClassName' => 'Modules\\Export\\Formats\\CsvExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\CsvExport',
        'name' => 'type',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'csv\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 24,
            'startFilePos' => 147,
            'endTokenPos' => 24,
            'endFilePos' => 151,
          ),
        ),
        'docComment' => '/**
 * The type of export
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
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
        'startLine' => 13,
        'endLine' => 48,
        'startColumn' => 2,
        'endColumn' => 2,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\CsvExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\CsvExport',
        'currentClassName' => 'Modules\\Export\\Formats\\CsvExport',
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
            'startLine' => 50,
            'endLine' => 50,
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
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 197,
                'startFilePos' => 1018,
                'endTokenPos' => 197,
                'endFilePos' => 1020,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
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
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 204,
                'startFilePos' => 1036,
                'endTokenPos' => 204,
                'endFilePos' => 1038,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
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
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 211,
                'startFilePos' => 1056,
                'endTokenPos' => 211,
                'endFilePos' => 1059,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
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
        'startLine' => 50,
        'endLine' => 60,
        'startColumn' => 2,
        'endColumn' => 2,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Export\\Formats',
        'declaringClassName' => 'Modules\\Export\\Formats\\CsvExport',
        'implementingClassName' => 'Modules\\Export\\Formats\\CsvExport',
        'currentClassName' => 'Modules\\Export\\Formats\\CsvExport',
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