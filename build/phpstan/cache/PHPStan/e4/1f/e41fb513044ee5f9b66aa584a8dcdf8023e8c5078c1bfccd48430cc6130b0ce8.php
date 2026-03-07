<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Product/Models/Product.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Product\Models\Product
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-4d44daaa39e7749fa8dc0c92d53bc6324a1383386b3db4d5992e3a1c2fe9c1af',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Product\\Models\\Product',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Product/Models/Product.php',
      ),
    ),
    'namespace' => 'Modules\\Product\\Models',
    'name' => 'Modules\\Product\\Models\\Product',
    'shortName' => 'Product',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 123,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Content\\Models\\Content',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Modules\\Product\\Traits\\CustomFieldPriceTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'content\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 97,
            'startFilePos' => 944,
            'endTokenPos' => 97,
            'endFilePos' => 952,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'appends' => 
      array (
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'name' => 'appends',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'price\', \'qty\', \'sku\', \'content_data\']',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 106,
            'startFilePos' => 981,
            'endTokenPos' => 117,
            'endFilePos' => 1019,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 65,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'name' => 'fillable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '["subtype", "subtype_value", "content_type", "parent", "layout_file", "active_site_template", "title", "url", "content_meta_title", "content", "description", "content_body", "content_meta_keywords", "original_link", "require_login", "created_by", "is_home", "is_shop", "is_active", "updated_at", "created_at"]',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 63,
            'startTokenPos' => 128,
            'startFilePos' => 1083,
            'endTokenPos' => 193,
            'endFilePos' => 1566,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customFields' => 
      array (
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'name' => 'customFields',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[[\'type\' => \'price\', \'name\' => \'Price\', \'value\' => [0]]]',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 72,
            'startTokenPos' => 204,
            'startFilePos' => 1605,
            'endTokenPos' => 232,
            'endFilePos' => 1720,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'contentDataDefault' => 
      array (
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'name' => 'contentDataDefault',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'qty\' => \'nolimit\', \'sku\' => \'\', \'barcode\' => \'\', \'track_quantity\' => \'\', \'max_quantity_per_order\' => \'\', \'sell_oos\' => \'\', \'physical_product\' => \'\', \'free_shipping\' => \'\', \'shipping_fixed_cost\' => \'\', \'weight_type\' => \'kg\', \'params_in_checkout\' => 0, \'has_special_price\' => 0, \'weight\' => \'\', \'width\' => \'\', \'height\' => \'\', \'depth\' => \'\']',
          'attributes' => 
          array (
            'startLine' => 74,
            'endLine' => 91,
            'startTokenPos' => 243,
            'startFilePos' => 1764,
            'endTokenPos' => 356,
            'endFilePos' => 2237,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 74,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sortable' => 
      array (
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'name' => 'sortable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'id\' => [\'title\' => \'Product\']]',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 97,
            'startTokenPos' => 365,
            'startFilePos' => 2264,
            'endTokenPos' => 381,
            'endFilePos' => 2331,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 97,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 99,
                'endLine' => 99,
                'startTokenPos' => 396,
                'startFilePos' => 2387,
                'endTokenPos' => 397,
                'endFilePos' => 2388,
              ),
            ),
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
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 33,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 99,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Product\\Models',
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'currentClassName' => 'Modules\\Product\\Models\\Product',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The "booted" method of the model.
 *
 * @return void
 */',
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Modules\\Product\\Models',
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'currentClassName' => 'Modules\\Product\\Models\\Product',
        'aliasName' => NULL,
      ),
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
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Product\\Models',
        'declaringClassName' => 'Modules\\Product\\Models\\Product',
        'implementingClassName' => 'Modules\\Product\\Models\\Product',
        'currentClassName' => 'Modules\\Product\\Models\\Product',
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