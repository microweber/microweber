<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Category/Models/Category.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Category\Models\Category
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-b4e5bbeb8b6a98909e9bc7ce98009affb6e99c94cb75c6cc1347620be3b5f0cb',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Category\\Models\\Category',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Category/Models/Category.php',
      ),
    ),
    'namespace' => 'Modules\\Category\\Models',
    'name' => 'Modules\\Category\\Models\\Category',
    'shortName' => 'Category',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 179,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Modules\\ContentField\\Concerns\\HasContentFieldTrait',
      1 => 'MicroweberPackages\\Database\\Traits\\CacheableQueryBuilderTrait',
      2 => 'EloquentFilter\\Filterable',
      3 => 'MicroweberPackages\\Core\\Models\\HasSearchableTrait',
      4 => 'Modules\\ContentData\\Traits\\ContentDataTrait',
      5 => 'MicroweberPackages\\Database\\Traits\\HasCreatedByFieldsTrait',
      6 => 'MicroweberPackages\\Database\\Traits\\MaxPositionTrait',
      7 => 'Modules\\Media\\Traits\\MediaTrait',
      8 => 'MicroweberPackages\\Database\\Traits\\HasSlugTrait',
      9 => 'MicroweberPackages\\Multilanguage\\Models\\Traits\\HasMultilanguageTrait',
      10 => 'Modules\\Category\\Traits\\SetParentIdToCategoryTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'categories\'',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 150,
            'startFilePos' => 1168,
            'endTokenPos' => 150,
            'endFilePos' => 1179,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'data_type\' => \'category\', \'rel_type\' => \\Modules\\Content\\Models\\Content::class, \'is_active\' => \'1\', \'is_deleted\' => \'0\', \'is_hidden\' => \'0\', \'parent_id\' => \'0\']',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 51,
            'startTokenPos' => 163,
            'startFilePos' => 1332,
            'endTokenPos' => 209,
            'endFilePos' => 1548,
          ),
        ),
        'docComment' => '/**
 * The model\'s default values for attributes.
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'fillable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[
    "id",
    "rel_type",
    "rel_id",
    "data_type",
    "parent_id",
    "title",
    "content",
    "description",
    // "category-parent-selector",
    "position",
    //  "thumbnail",
    "url",
    "users_can_create_content",
    "category_subtype",
    "category_subtype_settings",
    "category_meta_title",
    "category_meta_description",
    "is_hidden",
    "is_active",
    "is_deleted",
    "is_hidden",
    "category_meta_keywords",
]',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 77,
            'startTokenPos' => 220,
            'startFilePos' => 1629,
            'endTokenPos' => 285,
            'endFilePos' => 2174,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'casts',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'category_subtype_settings\' => \'array\', \'position\' => \'integer\', \'parent_id\' => \'integer\', \'is_active\' => \'integer\', \'is_hidden\' => \'integer\', \'is_deleted\' => \'integer\']',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 86,
            'startTokenPos' => 294,
            'startFilePos' => 2198,
            'endTokenPos' => 338,
            'endFilePos' => 2422,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'searchable' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'searchable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '["id", "rel_type", "rel_id", "data_type", "parent_id", "title", "content", "description", "position", "url", "is_hidden", "is_deleted", "users_can_create_content", "users_can_create_content_allowed_usergroups", "category_subtype", "category_meta_title", "category_meta_description", "category_meta_keywords"]',
          'attributes' => 
          array (
            'startLine' => 88,
            'endLine' => 107,
            'startTokenPos' => 347,
            'startFilePos' => 2454,
            'endTokenPos' => 402,
            'endFilePos' => 2911,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cacheTagsToClear' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'cacheTagsToClear',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'content\', \'content_fields_drafts\', \'menu\', \'content_fields\', \'content_data\', \'categories\']',
          'attributes' => 
          array (
            'startLine' => 109,
            'endLine' => 109,
            'startTokenPos' => 411,
            'startFilePos' => 2946,
            'endTokenPos' => 428,
            'endFilePos' => 3037,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 124,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'translatable' => 
      array (
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'name' => 'translatable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'title\', \'url\', \'description\', \'content\', \'category_meta_keywords\', \'category_meta_description\', \'category_meta_title\']',
          'attributes' => 
          array (
            'startLine' => 111,
            'endLine' => 111,
            'startTokenPos' => 437,
            'startFilePos' => 3068,
            'endTokenPos' => 457,
            'endFilePos' => 3187,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 148,
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
      'modelFilter' => 
      array (
        'name' => 'modelFilter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 114,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Models',
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'currentClassName' => 'Modules\\Category\\Models\\Category',
        'aliasName' => NULL,
      ),
      'items' => 
      array (
        'name' => 'items',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Models',
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'currentClassName' => 'Modules\\Category\\Models\\Category',
        'aliasName' => NULL,
      ),
      'children' => 
      array (
        'name' => 'children',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 124,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Models',
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'currentClassName' => 'Modules\\Category\\Models\\Category',
        'aliasName' => NULL,
      ),
      'parent' => 
      array (
        'name' => 'parent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 129,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Models',
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'currentClassName' => 'Modules\\Category\\Models\\Category',
        'aliasName' => NULL,
      ),
      'link' => 
      array (
        'name' => 'link',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 135,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Category\\Models',
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'currentClassName' => 'Modules\\Category\\Models\\Category',
        'aliasName' => NULL,
      ),
      'hasActiveProductInSubcategories' => 
      array (
        'name' => 'hasActiveProductInSubcategories',
        'parameters' => 
        array (
          'category' => 
          array (
            'name' => 'category',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 60,
            'endColumn' => 68,
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
        'startLine' => 154,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Category\\Models',
        'declaringClassName' => 'Modules\\Category\\Models\\Category',
        'implementingClassName' => 'Modules\\Category\\Models\\Category',
        'currentClassName' => 'Modules\\Category\\Models\\Category',
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