<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Menu/Repositories/MenuRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Menu\Repositories\MenuRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-2ea7809e7b6b4512c34935647bff207f3417bb6341e5e8c6118416f03a205d3d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Menu/Repositories/MenuRepository.php',
      ),
    ),
    'namespace' => 'Modules\\Menu\\Repositories',
    'name' => 'Modules\\Menu\\Repositories\\MenuRepository',
    'shortName' => 'MenuRepository',
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
    'endLine' => 137,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\Repository\\Repositories\\AbstractRepository',
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
      'model' => 
      array (
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'name' => 'model',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Modules\\Menu\\Models\\Menu::class',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 40,
            'startFilePos' => 343,
            'endTokenPos' => 42,
            'endFilePos' => 353,
          ),
        ),
        'docComment' => '/**
 * Specify Models class name
 *
 * @return string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_getAllMenus' => 
      array (
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'name' => '_getAllMenus',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 53,
            'startFilePos' => 391,
            'endTokenPos' => 54,
            'endFilePos' => 392,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 37,
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
      'clearCache' => 
      array (
        'name' => 'clearCache',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Menu\\Repositories',
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'currentClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'aliasName' => NULL,
      ),
      'getAllMenus' => 
      array (
        'name' => 'getAllMenus',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 27,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Menu\\Repositories',
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'currentClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'aliasName' => NULL,
      ),
      'getMenusByParentIdAndItemType' => 
      array (
        'name' => 'getMenusByParentIdAndItemType',
        'parameters' => 
        array (
          'parentId' => 
          array (
            'name' => 'parentId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 51,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'itemType' => 
          array (
            'name' => 'itemType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 62,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 48,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Menu\\Repositories',
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'currentClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'aliasName' => NULL,
      ),
      'getMenusByParentId' => 
      array (
        'name' => 'getMenusByParentId',
        'parameters' => 
        array (
          'parentId' => 
          array (
            'name' => 'parentId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 40,
            'endColumn' => 48,
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
        'startLine' => 66,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Menu\\Repositories',
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'currentClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'aliasName' => NULL,
      ),
      'getMenus' => 
      array (
        'name' => 'getMenus',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 30,
            'endColumn' => 36,
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
        'startLine' => 93,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Menu\\Repositories',
        'declaringClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'implementingClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
        'currentClassName' => 'Modules\\Menu\\Repositories\\MenuRepository',
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