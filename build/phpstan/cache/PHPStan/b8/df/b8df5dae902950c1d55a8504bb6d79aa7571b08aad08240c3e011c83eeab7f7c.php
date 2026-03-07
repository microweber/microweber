<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Offer/Models/Offer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Offer\Models\Offer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-20f4591e3054f140d9aaf405468f4fc6ecf258a336ae7ddeaa95f1f4fdeae46a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Offer\\Models\\Offer',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Offer/Models/Offer.php',
      ),
    ),
    'namespace' => 'Modules\\Offer\\Models',
    'name' => 'Modules\\Offer\\Models\\Offer',
    'shortName' => 'Offer',
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
    'endLine' => 229,
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
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'name' => 'table',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'offers\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 43,
            'startFilePos' => 264,
            'endTokenPos' => 43,
            'endFilePos' => 271,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'name' => 'fillable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '["product_id", "price_id", "offer_price", "created_at", "updated_at", "expires_at", "created_by", "edited_by", "is_active"]',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 25,
            'startTokenPos' => 52,
            'startFilePos' => 298,
            'endTokenPos' => 81,
            'endFilePos' => 499,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 25,
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
      'product' => 
      array (
        'name' => 'product',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the product associated with the offer.
 */',
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'offerData' => 
          array (
            'name' => 'offerData',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 32,
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
        'startLine' => 35,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
        'aliasName' => NULL,
      ),
      'getAll' => 
      array (
        'name' => 'getAll',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 86,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
        'aliasName' => NULL,
      ),
      'getPrice' => 
      array (
        'name' => 'getPrice',
        'parameters' => 
        array (
          'productId' => 
          array (
            'name' => 'productId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 37,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'priceId' => 
          array (
            'name' => 'priceId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 49,
            'endColumn' => 56,
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
        'startLine' => 112,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
        'aliasName' => NULL,
      ),
      'getByProductId' => 
      array (
        'name' => 'getByProductId',
        'parameters' => 
        array (
          'productId' => 
          array (
            'name' => 'productId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 43,
            'endColumn' => 52,
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
        'startLine' => 129,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
        'aliasName' => NULL,
      ),
      'getById' => 
      array (
        'name' => 'getById',
        'parameters' => 
        array (
          'offerId' => 
          array (
            'name' => 'offerId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 36,
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
        'docComment' => NULL,
        'startLine' => 186,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
        'aliasName' => NULL,
      ),
      'deleteById' => 
      array (
        'name' => 'deleteById',
        'parameters' => 
        array (
          'offerId' => 
          array (
            'name' => 'offerId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 39,
            'endColumn' => 46,
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
        'startLine' => 212,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Models',
        'declaringClassName' => 'Modules\\Offer\\Models\\Offer',
        'implementingClassName' => 'Modules\\Offer\\Models\\Offer',
        'currentClassName' => 'Modules\\Offer\\Models\\Offer',
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