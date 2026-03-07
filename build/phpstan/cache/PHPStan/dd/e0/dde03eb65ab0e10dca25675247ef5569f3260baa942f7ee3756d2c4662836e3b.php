<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Invoice/Models/Invoice.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Invoice\Models\Invoice
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-cbfac8f2526c7592b4d9bf2b62193118bd6a65ed5b21a58de07b3898bb15842a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Invoice\\Models\\Invoice',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Invoice/Models/Invoice.php',
      ),
    ),
    'namespace' => 'Modules\\Invoice\\Models',
    'name' => 'Modules\\Invoice\\Models\\Invoice',
    'shortName' => 'Invoice',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 99,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
    ),
    'immediateConstants' => 
    array (
      'STATUS_DRAFT' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_DRAFT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'draft\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 63,
            'startFilePos' => 402,
            'endTokenPos' => 63,
            'endFilePos' => 408,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'STATUS_SENT' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_SENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sent\'',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 72,
            'startFilePos' => 435,
            'endTokenPos' => 72,
            'endFilePos' => 440,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'STATUS_VIEWED' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_VIEWED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'viewed\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 81,
            'startFilePos' => 469,
            'endTokenPos' => 81,
            'endFilePos' => 476,
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
      'STATUS_OVERDUE' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_OVERDUE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'overdue\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 90,
            'startFilePos' => 506,
            'endTokenPos' => 90,
            'endFilePos' => 514,
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
      'STATUS_PAID' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_PAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'paid\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 99,
            'startFilePos' => 541,
            'endTokenPos' => 99,
            'endFilePos' => 546,
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
      'STATUS_COMPLETED' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_COMPLETED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'completed\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 108,
            'startFilePos' => 578,
            'endTokenPos' => 108,
            'endFilePos' => 588,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'STATUS_VOID' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_VOID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'void\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 117,
            'startFilePos' => 615,
            'endTokenPos' => 117,
            'endFilePos' => 620,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'STATUS_UNPAID' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_UNPAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unpaid\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 126,
            'startFilePos' => 650,
            'endTokenPos' => 126,
            'endFilePos' => 657,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'STATUS_PARTIALLY_PAID' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_PARTIALLY_PAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'partially_paid\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 135,
            'startFilePos' => 694,
            'endTokenPos' => 135,
            'endFilePos' => 709,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'STATUS_REFUNDED' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'STATUS_REFUNDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'refunded\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 144,
            'startFilePos' => 740,
            'endTokenPos' => 144,
            'endFilePos' => 749,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
    ),
    'immediateProperties' => 
    array (
      'factory' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'factory',
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
          'code' => '\\Modules\\Invoice\\Database\\Factories\\InvoiceFactory::class',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 52,
            'startFilePos' => 318,
            'endTokenPos' => 54,
            'endFilePos' => 374,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 97,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'invoice_date\', \'due_date\', \'invoice_number\', \'reference_number\', \'customer_id\', \'company_id\', \'invoice_template_id\', \'status\', \'paid_status\', \'sub_total\', \'discount\', \'discount_type\', \'discount_val\', \'total\', \'due_amount\', \'tax_per_item\', \'discount_per_item\', \'tax\', \'notes\', \'unique_hash\']',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 49,
            'startTokenPos' => 153,
            'startFilePos' => 780,
            'endTokenPos' => 214,
            'endFilePos' => 1237,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 49,
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
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'invoice_date\' => \'date\', \'due_date\' => \'date\', \'tax_per_item\' => \'boolean\', \'discount_per_item\' => \'boolean\', \'sub_total\' => \'integer\', \'discount_val\' => \'integer\', \'total\' => \'integer\', \'due_amount\' => \'integer\']',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 60,
            'startTokenPos' => 223,
            'startFilePos' => 1264,
            'endTokenPos' => 281,
            'endFilePos' => 1549,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 60,
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
      'items' => 
      array (
        'name' => 'items',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 62,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'currentClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'aliasName' => NULL,
      ),
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
        'startLine' => 67,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'currentClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'aliasName' => NULL,
      ),
      'getNextInvoiceNumber' => 
      array (
        'name' => 'getNextInvoiceNumber',
        'parameters' => 
        array (
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 72,
                'endLine' => 72,
                'startTokenPos' => 347,
                'startFilePos' => 1806,
                'endTokenPos' => 347,
                'endFilePos' => 1807,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
            'startColumn' => 49,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
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
        'startLine' => 72,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'currentClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'aliasName' => NULL,
      ),
      'scopeDraft' => 
      array (
        'name' => 'scopeDraft',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 32,
            'endColumn' => 37,
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
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'currentClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'aliasName' => NULL,
      ),
      'scopePaid' => 
      array (
        'name' => 'scopePaid',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 31,
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
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'currentClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'aliasName' => NULL,
      ),
      'scopeCancelled' => 
      array (
        'name' => 'scopeCancelled',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startColumn' => 36,
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
        'docComment' => NULL,
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Invoice\\Models',
        'declaringClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'implementingClassName' => 'Modules\\Invoice\\Models\\Invoice',
        'currentClassName' => 'Modules\\Invoice\\Models\\Invoice',
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