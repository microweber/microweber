<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Category/Repositories/CategoryRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Category\Repositories\CategoryRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-820f8a1b5ed9f882489c85739573990ac17b442645368be39422b8125a194800',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Category/Repositories/CategoryRepository.php',
      ),
    ),
    'namespace' => 'Modules\\Category\\Repositories',
    'name' => 'Modules\\Category\\Repositories\\CategoryRepository',
    'shortName' => 'CategoryRepository',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 373,
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
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'name' => 'model',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Modules\\Category\\Models\\Category::class',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 50,
            'startFilePos' => 421,
            'endTokenPos' => 52,
            'endFilePos' => 435,
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
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 36,
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
      'getByUrl' => 
      array (
        'name' => 'getByUrl',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 30,
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
        'startLine' => 22,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getByColumnNameAndColumnValue' => 
      array (
        'name' => 'getByColumnNameAndColumnValue',
        'parameters' => 
        array (
          'columnName' => 
          array (
            'name' => 'columnName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 51,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columnValue' => 
          array (
            'name' => 'columnValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 64,
            'endColumn' => 75,
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
        'startLine' => 36,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getMedia' => 
      array (
        'name' => 'getMedia',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 30,
            'endColumn' => 32,
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
 * Find category media by category id.
 *
 * @param mixed $id
 *
 * @return array
 */',
        'startLine' => 70,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'tree' => 
      array (
        'name' => 'tree',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 87,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getChildsTree' => 
      array (
        'name' => 'getChildsTree',
        'parameters' => 
        array (
          'categoryId' => 
          array (
            'name' => 'categoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 110,
            'endLine' => 110,
            'startColumn' => 35,
            'endColumn' => 45,
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
        'startLine' => 110,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getSubCategories' => 
      array (
        'name' => 'getSubCategories',
        'parameters' => 
        array (
          'categoryId' => 
          array (
            'name' => 'categoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 38,
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
        'docComment' => '/**
 *
 * @param mixed $categoryId
 *
 * @return boolean|array
 */',
        'startLine' => 148,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'hasProductsInStock' => 
      array (
        'name' => 'hasProductsInStock',
        'parameters' => 
        array (
          'categoryId' => 
          array (
            'name' => 'categoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 187,
            'endLine' => 187,
            'startColumn' => 40,
            'endColumn' => 50,
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
 * Check if category has products in stock.
 *
 * @param mixed $categoryId
 *
 * @return boolean
 */',
        'startLine' => 187,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      '_checkProductsInStockRecursive' => 
      array (
        'name' => '_checkProductsInStockRecursive',
        'parameters' => 
        array (
          'categories' => 
          array (
            'name' => 'categories',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 53,
            'endColumn' => 63,
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
        'startLine' => 205,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getItemsCountAll' => 
      array (
        'name' => 'getItemsCountAll',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 220,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getItemsInStockCountAll' => 
      array (
        'name' => 'getItemsInStockCountAll',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 238,
        'endLine' => 259,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getItemsCount' => 
      array (
        'name' => 'getItemsCount',
        'parameters' => 
        array (
          'categoryId' => 
          array (
            'name' => 'categoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 35,
            'endColumn' => 45,
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
        'startLine' => 261,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getProductsInStockCount' => 
      array (
        'name' => 'getProductsInStockCount',
        'parameters' => 
        array (
          'categoryId' => 
          array (
            'name' => 'categoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 45,
            'endColumn' => 55,
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
        'startLine' => 273,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getItems' => 
      array (
        'name' => 'getItems',
        'parameters' => 
        array (
          'categoryId' => 
          array (
            'name' => 'categoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
            'startColumn' => 30,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relType' => 
          array (
            'name' => 'relType',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 284,
                'endLine' => 284,
                'startTokenPos' => 1579,
                'startFilePos' => 7966,
                'endTokenPos' => 1579,
                'endFilePos' => 7970,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'relId' => 
          array (
            'name' => 'relId',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 284,
                'endLine' => 284,
                'startTokenPos' => 1586,
                'startFilePos' => 7982,
                'endTokenPos' => 1586,
                'endFilePos' => 7986,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
            'startColumn' => 61,
            'endColumn' => 74,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 284,
        'endLine' => 314,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'getCategoryItemsCountQueryBuilder' => 
      array (
        'name' => 'getCategoryItemsCountQueryBuilder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 316,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
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
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 26,
            'endColumn' => 30,
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
        'startLine' => 334,
        'endLine' => 371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Repositories',
        'declaringClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'implementingClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
        'currentClassName' => 'Modules\\Category\\Repositories\\CategoryRepository',
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