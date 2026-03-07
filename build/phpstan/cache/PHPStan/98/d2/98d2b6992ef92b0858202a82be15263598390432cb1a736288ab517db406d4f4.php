<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Order/Filament/Admin/Resources/OrderResource/RelationManagers/PaymentsRelationManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers\PaymentsRelationManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-af1b27cf2883ed0807adf491956c75c4e7f24da9f33473c570dac2801dc95bc1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Order/Filament/Admin/Resources/OrderResource/RelationManagers/PaymentsRelationManager.php',
      ),
    ),
    'namespace' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers',
    'name' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
    'shortName' => 'PaymentsRelationManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 100,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Filament\\Resources\\RelationManagers\\RelationManager',
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
      'relationship' => 
      array (
        'declaringClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'implementingClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'name' => 'relationship',
        'modifiers' => 18,
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
          'code' => '\'payments\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 97,
            'startFilePos' => 668,
            'endTokenPos' => 97,
            'endFilePos' => 677,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 55,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'recordTitleAttribute' => 
      array (
        'declaringClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'implementingClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'name' => 'recordTitleAttribute',
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
          'code' => '\'transaction_id\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 111,
            'startFilePos' => 734,
            'endTokenPos' => 111,
            'endFilePos' => 749,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 70,
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
      'form' => 
      array (
        'name' => 'form',
        'parameters' => 
        array (
          'schema' => 
          array (
            'name' => 'schema',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Filament\\Schemas\\Schema',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 26,
            'endColumn' => 39,
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
            'name' => 'Filament\\Schemas\\Schema',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers',
        'declaringClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'implementingClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'currentClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
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
            'startLine' => 59,
            'endLine' => 59,
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
        'docComment' => NULL,
        'startLine' => 59,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers',
        'declaringClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'implementingClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
        'currentClassName' => 'Modules\\Order\\Filament\\Admin\\Resources\\OrderResource\\RelationManagers\\PaymentsRelationManager',
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