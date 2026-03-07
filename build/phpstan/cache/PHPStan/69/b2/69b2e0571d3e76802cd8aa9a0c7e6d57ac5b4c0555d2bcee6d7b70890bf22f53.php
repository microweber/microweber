<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LiveEdit/Traits/HasLiveEditMenus.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\LiveEdit\Traits\HasLiveEditMenus
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-ff857c68e3e3ac41f422d8845aaa824db4394078c423e0250a6de5977411935e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/LiveEdit/Traits/HasLiveEditMenus.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\LiveEdit\\Traits',
    'name' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
    'shortName' => 'HasLiveEditMenus',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 112,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
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
      'menus' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'name' => 'menus',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * @var array $menus An array of menu instances.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 24,
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
      'getMenu' => 
      array (
        'name' => 'getMenu',
        'parameters' => 
        array (
          'menu' => 
          array (
            'name' => 'menu',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 29,
            'endColumn' => 40,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the menu items for a specific menu sorted to according to their order number.
 *
 * @param string $menu The name of the menu.
 * @return array The menu items.
 */',
        'startLine' => 22,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\LiveEdit\\Traits',
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'currentClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'aliasName' => NULL,
      ),
      'getMenuInstance' => 
      array (
        'name' => 'getMenuInstance',
        'parameters' => 
        array (
          'menu' => 
          array (
            'name' => 'menu',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 37,
            'endColumn' => 41,
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
 * Get the instance of a specific menu.
 *
 * @param string $menu The name of the menu.
 * @return mixed The instance of the menu.
 */',
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\LiveEdit\\Traits',
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'currentClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'aliasName' => NULL,
      ),
      'getOrCreateMenuInstance' => 
      array (
        'name' => 'getOrCreateMenuInstance',
        'parameters' => 
        array (
          'menuName' => 
          array (
            'name' => 'menuName',
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
            'startColumn' => 45,
            'endColumn' => 53,
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
 * Get the instance of a specific menu.
 *
 * @param string $menu The name of the menu.
 * @return mixed The instance of the menu.
 */',
        'startLine' => 50,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\LiveEdit\\Traits',
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'currentClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'aliasName' => NULL,
      ),
      'reorderMenuItems' => 
      array (
        'name' => 'reorderMenuItems',
        'parameters' => 
        array (
          'menu' => 
          array (
            'name' => 'menu',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Knp\\Menu\\ItemInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 38,
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
 * Reorder the menu items according to their order number.
 *
 * @param ItemInterface $menu The menu items to be reordered.
 */',
        'startLine' => 64,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\LiveEdit\\Traits',
        'declaringClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'implementingClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
        'currentClassName' => 'MicroweberPackages\\LiveEdit\\Traits\\HasLiveEditMenus',
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