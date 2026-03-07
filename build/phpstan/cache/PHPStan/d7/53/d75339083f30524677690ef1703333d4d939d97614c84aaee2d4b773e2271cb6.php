<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Category/Helpers/PlainTextCategoriesSave.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Category\Helpers\PlainTextCategoriesSave
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-bc1eca16a654d18b66bd9d1cf2e47d7a3d65caa472526c6005b9dae230d1c823',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Category/Helpers/PlainTextCategoriesSave.php',
      ),
    ),
    'namespace' => 'Modules\\Category\\Helpers',
    'name' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
    'shortName' => 'PlainTextCategoriesSave',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 83,
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
    ),
    'immediateMethods' => 
    array (
      'saveCategories' => 
      array (
        'name' => 'saveCategories',
        'parameters' => 
        array (
          'categoriesToSave' => 
          array (
            'name' => 'categoriesToSave',
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 36,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'mainCategoryId' => 
          array (
            'name' => 'mainCategoryId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 61,
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
        'docComment' => '/**
* Example input:
*  $categoriesToSave = [];
   $categoriesToSave[] = \'Properties > Locations > City > Sofia > Dragalevci\';
   $categoriesToSave[] = \'Properties > Locations > City > Sofia > Mladost\';
   $categoriesToSave[] = \'Properties > Locations > City > Sofia > Nadejda\';
* @param array $categoriesToSave
* @param $mainCategoryId
* @return void
*/',
        'startLine' => 21,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Helpers',
        'declaringClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'implementingClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'currentClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'aliasName' => NULL,
      ),
      '_addCategory' => 
      array (
        'name' => '_addCategory',
        'parameters' => 
        array (
          'title' => 
          array (
            'name' => 'title',
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
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parentId' => 
          array (
            'name' => 'parentId',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 30,
                'endLine' => 30,
                'startTokenPos' => 97,
                'startFilePos' => 922,
                'endTokenPos' => 97,
                'endFilePos' => 922,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 43,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Category\\Helpers',
        'declaringClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'implementingClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'currentClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'aliasName' => NULL,
      ),
      '_addCategoryRecursive' => 
      array (
        'name' => '_addCategoryRecursive',
        'parameters' => 
        array (
          'array' => 
          array (
            'name' => 'array',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 44,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parentId' => 
          array (
            'name' => 'parentId',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 54,
                'endLine' => 54,
                'startTokenPos' => 246,
                'startFilePos' => 1589,
                'endTokenPos' => 246,
                'endFilePos' => 1589,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 54,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Category\\Helpers',
        'declaringClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'implementingClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'currentClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'aliasName' => NULL,
      ),
      'parseGoogleTaxonomy' => 
      array (
        'name' => 'parseGoogleTaxonomy',
        'parameters' => 
        array (
          'content' => 
          array (
            'name' => 'content',
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
            'startColumn' => 42,
            'endColumn' => 49,
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
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\Category\\Helpers',
        'declaringClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'implementingClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
        'currentClassName' => 'Modules\\Category\\Helpers\\PlainTextCategoriesSave',
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