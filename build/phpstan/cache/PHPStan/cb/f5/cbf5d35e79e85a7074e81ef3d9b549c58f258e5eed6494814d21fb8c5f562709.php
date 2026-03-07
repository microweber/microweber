<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Content/Filament/Admin/ContentResource/Pages/EditContent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Content\Filament\Admin\ContentResource\Pages\EditContent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-06ff89c0a8121a104f0e0061799fc6cc2062f30dcb8c4110333dbf121a380691',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Content/Filament/Admin/ContentResource/Pages/EditContent.php',
      ),
    ),
    'namespace' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages',
    'name' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
    'shortName' => 'EditContent',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 111,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Filament\\Resources\\Pages\\EditRecord',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Modules\\Content\\Concerns\\HasEditContentForms',
      1 => 'MicroweberPackages\\Filament\\Concerns\\ModifyComponentData',
      2 => 'Filament\\Schemas\\Concerns\\InteractsWithSchemas',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'resource' => 
      array (
        'declaringClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'implementingClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'name' => 'resource',
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
          'code' => '\\Modules\\Content\\Filament\\Admin\\ContentResource::class',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 91,
            'startFilePos' => 804,
            'endTokenPos' => 93,
            'endFilePos' => 825,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 63,
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
      'handleRecordUpdate' => 
      array (
        'name' => 'handleRecordUpdate',
        'parameters' => 
        array (
          'record' => 
          array (
            'name' => 'record',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'startColumn' => 43,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 58,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Model',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages',
        'declaringClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'implementingClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'currentClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'aliasName' => NULL,
      ),
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 26,
            'endColumn' => 57,
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
        'startLine' => 63,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages',
        'declaringClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'implementingClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'currentClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'aliasName' => NULL,
      ),
      'getHeaderActions' => 
      array (
        'name' => 'getHeaderActions',
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
        'docComment' => NULL,
        'startLine' => 70,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages',
        'declaringClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'implementingClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
        'currentClassName' => 'Modules\\Content\\Filament\\Admin\\ContentResource\\Pages\\EditContent',
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