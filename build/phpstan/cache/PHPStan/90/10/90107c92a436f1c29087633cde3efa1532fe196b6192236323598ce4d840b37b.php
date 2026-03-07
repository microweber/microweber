<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../alexwestergaard/php-ga4/src/Facade/Group/PurchaseFacade.php-PHPStan\BetterReflection\Reflection\ReflectionClass-AlexWestergaard\PhpGa4\Facade\Group\PurchaseFacade
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-bc7eee746a40e3111ae3eea3630ce6aded26e4c92a7a3c6ac0953fd9a60ed000-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../alexwestergaard/php-ga4/src/Facade/Group/PurchaseFacade.php',
      ),
    ),
    'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
    'name' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
    'shortName' => 'PurchaseFacade',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 68,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\hasItemsFacade',
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
      'setCurrency' => 
      array (
        'name' => 'setCurrency',
        'parameters' => 
        array (
          'iso' => 
          array (
            'name' => 'iso',
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
            'startLine' => 15,
            'endLine' => 15,
            'startColumn' => 33,
            'endColumn' => 43,
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
 * Currency of the items associated with the event, in 3-letter ISO 4217 format. \\
 * \\* If you set value then currency is required for revenue metrics to be computed accurately.
 *
 * @link ISO-Codes https://en.wikipedia.org/wiki/ISO_4217#Active_codes
 * @var currency
 * @param string $iso eg. USD
 */',
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'aliasName' => NULL,
      ),
      'setTransactionId' => 
      array (
        'name' => 'setTransactionId',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
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
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 38,
            'endColumn' => 47,
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
 * The unique identifier of a transaction. \\
 * The transaction_id parameter helps you avoid getting duplicate events for a purchase.
 *
 * @var transaction_id
 * @param string $id eg. T_12345
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'aliasName' => NULL,
      ),
      'setValue' => 
      array (
        'name' => 'setValue',
        'parameters' => 
        array (
          'val' => 
          array (
            'name' => 'val',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 30,
            'endColumn' => 43,
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
 * The monetary value of the event.
 *
 * @var value
 * @param integer|float $val eg. 7.77
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'aliasName' => NULL,
      ),
      'setAffiliation' => 
      array (
        'name' => 'setAffiliation',
        'parameters' => 
        array (
          'affiliation' => 
          array (
            'name' => 'affiliation',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 36,
            'endColumn' => 54,
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
 * A product affiliation to designate a supplying company or brick and mortar store location. \\
 * Event-level and item-level affiliation parameters are independent.
 *
 * @var affiliation
 * @param string $affiliation eg. Google Store
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'aliasName' => NULL,
      ),
      'setCoupon' => 
      array (
        'name' => 'setCoupon',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 31,
            'endColumn' => 42,
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
 * The coupon name/code associated with the event. \\
 * Event-level and item-level coupon parameters are independent.
 *
 * @var coupon
 * @param string $code eg. SUMMER_FUN
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'aliasName' => NULL,
      ),
      'setShipping' => 
      array (
        'name' => 'setShipping',
        'parameters' => 
        array (
          'cost' => 
          array (
            'name' => 'cost',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 33,
            'endColumn' => 43,
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
 * Shipping cost associated with a transaction.
 *
 * @var shipping
 * @param float $cost eg. 3.33
 */',
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'aliasName' => NULL,
      ),
      'setTax' => 
      array (
        'name' => 'setTax',
        'parameters' => 
        array (
          'tax' => 
          array (
            'name' => 'tax',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 28,
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
        'docComment' => '/**
 * Tax cost associated with a transaction.
 *
 * @var tax
 * @param float $tax eg. 1.11
 */',
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\PurchaseFacade',
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