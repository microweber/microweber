<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Teamcard/Filament/TeamcardTableList.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Teamcard\Filament\TeamcardTableList
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-1c9e55c658edee0c517e770763f11f30140d7059a81332e007635240e4914b5b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Teamcard/Filament/TeamcardTableList.php',
      ),
    ),
    'namespace' => 'Modules\\Teamcard\\Filament',
    'name' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
    'shortName' => 'TeamcardTableList',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Team Card Table List Component
 *
 * Manages the display and manipulation of team member cards in the admin panel
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 49,
    'endLine' => 308,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\LiveEdit\\Filament\\Admin\\Tables\\LiveEditModuleTable',
    'implementsClassNames' => 
    array (
      0 => 'Filament\\Forms\\Contracts\\HasForms',
      1 => 'Filament\\Tables\\Contracts\\HasTable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Filament\\Tables\\Concerns\\InteractsWithTable',
      1 => 'Filament\\Forms\\Concerns\\InteractsWithForms',
      2 => 'Filament\\Actions\\Concerns\\InteractsWithActions',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'rel_id' => 
      array (
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'name' => 'rel_id',
        'modifiers' => 1,
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
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 197,
            'startFilePos' => 1399,
            'endTokenPos' => 197,
            'endFilePos' => 1402,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'rel_type' => 
      array (
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'name' => 'rel_type',
        'modifiers' => 1,
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
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 210,
            'startFilePos' => 1440,
            'endTokenPos' => 210,
            'endFilePos' => 1443,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 40,
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
      'editFormArray' => 
      array (
        'name' => 'editFormArray',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define the form fields for creating/editing team cards
 */',
        'startLine' => 62,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'aliasName' => NULL,
      ),
      'table' => 
      array (
        'name' => 'table',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Filament\\Tables\\Table',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 27,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
        'docComment' => '/**
 * Configure the data table
 */',
        'startLine' => 111,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'aliasName' => NULL,
      ),
      'getTeamCardQuery' => 
      array (
        'name' => 'getTeamCardQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the base query for team cards
 */',
        'startLine' => 254,
        'endLine' => 259,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'aliasName' => NULL,
      ),
      'initializeDefaultTeamCards' => 
      array (
        'name' => 'initializeDefaultTeamCards',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 264,
            'endLine' => 264,
            'startColumn' => 51,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initialize default team cards if none exist
 */',
        'startLine' => 264,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'aliasName' => NULL,
      ),
      'getDefaultContent' => 
      array (
        'name' => 'getDefaultContent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get default content from JSON file
 */',
        'startLine' => 283,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'aliasName' => NULL,
      ),
      'createDefaultTeamCard' => 
      array (
        'name' => 'createDefaultTeamCard',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
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
            'startLine' => 292,
            'endLine' => 292,
            'startColumn' => 46,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a default team card
 */',
        'startLine' => 292,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Contracts\\View\\View',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the component
 */',
        'startLine' => 304,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Teamcard\\Filament',
        'declaringClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'implementingClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
        'currentClassName' => 'Modules\\Teamcard\\Filament\\TeamcardTableList',
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