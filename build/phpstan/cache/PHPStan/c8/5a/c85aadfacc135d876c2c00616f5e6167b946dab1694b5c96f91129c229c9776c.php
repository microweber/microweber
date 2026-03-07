<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/widgets/src/TableWidget.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Widgets\TableWidget
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0a2092eb917384b043e34f442d5123c2b5ac075529654785079c6301e7edf3df-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Widgets\\TableWidget',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/widgets/src/TableWidget.php',
      ),
    ),
    'namespace' => 'Filament\\Widgets',
    'name' => 'Filament\\Widgets\\TableWidget',
    'shortName' => 'TableWidget',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 53,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Filament\\Widgets\\Widget',
    'implementsClassNames' => 
    array (
      0 => 'Filament\\Actions\\Contracts\\HasActions',
      1 => 'Filament\\Schemas\\Contracts\\HasSchemas',
      2 => 'Filament\\Tables\\Contracts\\HasTable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Filament\\Actions\\Concerns\\InteractsWithActions',
      1 => 'Filament\\Schemas\\Concerns\\InteractsWithSchemas',
      2 => 'Filament\\Tables\\Concerns\\InteractsWithTable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'view' => 
      array (
        'declaringClassName' => 'Filament\\Widgets\\TableWidget',
        'implementingClassName' => 'Filament\\Widgets\\TableWidget',
        'name' => 'view',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\'filament-widgets::table-widget\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 107,
            'startFilePos' => 708,
            'endTokenPos' => 107,
            'endFilePos' => 739,
          ),
        ),
        'docComment' => '/**
 * @var view-string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 62,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'heading' => 
      array (
        'declaringClassName' => 'Filament\\Widgets\\TableWidget',
        'implementingClassName' => 'Filament\\Widgets\\TableWidget',
        'name' => 'heading',
        'modifiers' => 18,
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
                  'name' => 'string',
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 123,
            'startFilePos' => 872,
            'endTokenPos' => 123,
            'endFilePos' => 875,
          ),
        ),
        'docComment' => '/**
 * @deprecated Override the `table()` method to configure the table.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 45,
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
      'getTableHeading' => 
      array (
        'name' => 'getTableHeading',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Illuminate\\Contracts\\Support\\Htmlable',
                  'isIdentifier' => false,
                ),
              ),
              2 => 
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Override the `table()` method to configure the table.
 */',
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Widgets',
        'declaringClassName' => 'Filament\\Widgets\\TableWidget',
        'implementingClassName' => 'Filament\\Widgets\\TableWidget',
        'currentClassName' => 'Filament\\Widgets\\TableWidget',
        'aliasName' => NULL,
      ),
      'makeTable' => 
      array (
        'name' => 'makeTable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Tables\\Table',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 41,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Widgets',
        'declaringClassName' => 'Filament\\Widgets\\TableWidget',
        'implementingClassName' => 'Filament\\Widgets\\TableWidget',
        'currentClassName' => 'Filament\\Widgets\\TableWidget',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Filament\\Tables\\Concerns\\InteractsWithTable' => 
        array (
          0 => 
          array (
            'alias' => 'makeBaseTable',
            'method' => 'makeTable',
            'hash' => 'filament\\tables\\concerns\\interactswithtable::maketable',
          ),
        ),
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'filament\\tables\\concerns\\interactswithtable::maketable' => 'Filament\\Tables\\Concerns\\InteractsWithTable::makeTable',
      ),
    ),
  ),
));