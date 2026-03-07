<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Address/Models/Address.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Address\Models\Address
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-8248e48bc83dffd9f4cd0e657e0b71a2974f9028042f588c8a396bae2f524bba',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Address\\Models\\Address',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Address/Models/Address.php',
      ),
    ),
    'namespace' => 'Modules\\Address\\Models',
    'name' => 'Modules\\Address\\Models\\Address',
    'shortName' => 'Address',
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
    'endLine' => 62,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'MicroweberPackages\\Database\\Traits\\CacheableQueryBuilderTrait',
    ),
    'immediateConstants' => 
    array (
      'BILLING_TYPE' => 
      array (
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'name' => 'BILLING_TYPE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'billing\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 75,
            'startFilePos' => 409,
            'endTokenPos' => 75,
            'endFilePos' => 417,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'SHIPPING_TYPE' => 
      array (
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'name' => 'SHIPPING_TYPE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'shipping\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 84,
            'startFilePos' => 446,
            'endTokenPos' => 84,
            'endFilePos' => 455,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'OTHER_TYPE' => 
      array (
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'name' => 'OTHER_TYPE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'other\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 93,
            'startFilePos' => 481,
            'endTokenPos' => 93,
            'endFilePos' => 487,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'name' => 'table',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'addresses\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 43,
            'startFilePos' => 275,
            'endTokenPos' => 43,
            'endFilePos' => 285,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cacheTagsToClear' => 
      array (
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'name' => 'cacheTagsToClear',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'addresses\', \'customers\', \'users\', \'countries\', \'companies\']',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 52,
            'startFilePos' => 320,
            'endTokenPos' => 66,
            'endFilePos' => 380,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 93,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'address_street_1\', \'address_street_2\', \'city\', \'state\', \'country\', \'country_id\', \'zip\', \'phone\', \'type\', \'customer_id\', \'rel_type\', \'rel_id\']',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 36,
            'startTokenPos' => 102,
            'startFilePos' => 517,
            'endTokenPos' => 143,
            'endFilePos' => 779,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 36,
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
      'customer' => 
      array (
        'name' => 'customer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Address\\Models',
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'currentClassName' => 'Modules\\Address\\Models\\Address',
        'aliasName' => NULL,
      ),
      'country' => 
      array (
        'name' => 'country',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Address\\Models',
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'currentClassName' => 'Modules\\Address\\Models\\Address',
        'aliasName' => NULL,
      ),
      'isBilling' => 
      array (
        'name' => 'isBilling',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 53,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Address\\Models',
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'currentClassName' => 'Modules\\Address\\Models\\Address',
        'aliasName' => NULL,
      ),
      'isShipping' => 
      array (
        'name' => 'isShipping',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Address\\Models',
        'declaringClassName' => 'Modules\\Address\\Models\\Address',
        'implementingClassName' => 'Modules\\Address\\Models\\Address',
        'currentClassName' => 'Modules\\Address\\Models\\Address',
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