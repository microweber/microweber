<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LiveEdit/Http/Livewire/ItemsEditor/ModuleSettingsItemsEditorListComponent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorListComponent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-ab8121ec1a24477b461e06b95b8a431514155a2c9281a3ca0ab20586a5bb7935',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LiveEdit/Http/Livewire/ItemsEditor/ModuleSettingsItemsEditorListComponent.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor',
    'name' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
    'shortName' => 'ModuleSettingsItemsEditorListComponent',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @deprecated
 */',
    'attributes' => 
    array (
      0 => 
      array (
        'name' => 'Livewire\\Attributes\\Isolate',
        'isRepeated' => false,
        'arguments' => 
        array (
        ),
      ),
    ),
    'startLine' => 14,
    'endLine' => 78,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\AbstractModuleSettingsEditorComponent',
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
      'view' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'name' => 'view',
        'modifiers' => 1,
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
          'code' => '\'microweber-live-edit::module-items-editor-list\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 38,
            'startFilePos' => 283,
            'endTokenPos' => 38,
            'endFilePos' => 330,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 75,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'listeners' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'name' => 'listeners',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[
    //  \'onItemChanged\' => \'$refresh\',
    \'onItemChanged\' => \'handleOnItemChanged\',
    \'onItemDeleted\' => \'$refresh\',
    \'refreshComponent\' => \'$refresh\',
    \'onReorderListItems\' => \'reorderListItems\',
    \'onShowConfirmDeleteItemById\' => \'showConfirmDeleteItemById\',
    \'onConfirmDeleteSelectedItems\' => \'confirmDeleteSelectedItems\',
]',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 29,
            'startTokenPos' => 47,
            'startFilePos' => 360,
            'endTokenPos' => 93,
            'endFilePos' => 725,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'handleOnItemChanged' => 
      array (
        'name' => 'handleOnItemChanged',
        'parameters' => 
        array (
          'moduleId' => 
          array (
            'name' => 'moduleId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 52,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'isNew' => 
          array (
            'name' => 'isNew',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 59,
            'endColumn' => 64,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'itemId' => 
          array (
            'name' => 'itemId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 67,
            'endColumn' => 73,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor',
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'currentClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 72,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor',
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
        'currentClassName' => 'MicroweberPackages\\LiveEdit\\Http\\Livewire\\ItemsEditor\\ModuleSettingsItemsEditorListComponent',
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