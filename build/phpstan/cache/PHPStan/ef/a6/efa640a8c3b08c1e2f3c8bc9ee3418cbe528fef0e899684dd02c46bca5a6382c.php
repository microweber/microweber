<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Invoice/Models/InvoiceItem.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Invoice\Models\InvoiceItem
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-3a90d3b996bf5e9ec1ed4c589d4725a1ae18b2201ab50f22e7a5a948a2a4118e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Invoice/Models/InvoiceItem.php',
      ),
    ),
    'namespace' => 'Modules\\Invoice\\Models',
    'name' => 'Modules\\Invoice\\Models\\InvoiceItem',
    'shortName' => 'InvoiceItem',
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
    'endLine' => 42,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'implementingClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'invoice_id\', \'name\', \'description\', \'price\', \'quantity\']',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 16,
            'startTokenPos' => 33,
            'startFilePos' => 197,
            'endTokenPos' => 49,
            'endFilePos' => 300,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 16,
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
        'declaringClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'implementingClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'price\' => \'integer\', \'quantity\' => \'integer\']',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 21,
            'startTokenPos' => 58,
            'startFilePos' => 327,
            'endTokenPos' => 73,
            'endFilePos' => 395,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 21,
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
      'invoice' => 
      array (
        'name' => 'invoice',
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
        'startLine' => 23,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'implementingClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'currentClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'aliasName' => NULL,
      ),
      'getFormattedPriceAttribute' => 
      array (
        'name' => 'getFormattedPriceAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'implementingClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'currentClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'aliasName' => NULL,
      ),
      'getSubtotalAttribute' => 
      array (
        'name' => 'getSubtotalAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 33,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'implementingClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'currentClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'aliasName' => NULL,
      ),
      'getFormattedSubtotalAttribute' => 
      array (
        'name' => 'getFormattedSubtotalAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'implementingClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
        'currentClassName' => 'Modules\\Invoice\\Models\\InvoiceItem',
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