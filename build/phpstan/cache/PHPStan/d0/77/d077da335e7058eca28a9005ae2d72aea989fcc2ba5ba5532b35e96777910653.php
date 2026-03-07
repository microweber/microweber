<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Offer/Repositories/OfferRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Offer\Repositories\OfferRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-81b053291d19d5f4b4e32e8bc60ed8b0dff8d7627e3c0ad3f07f837454ff375d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Offer/Repositories/OfferRepository.php',
      ),
    ),
    'namespace' => 'Modules\\Offer\\Repositories',
    'name' => 'Modules\\Offer\\Repositories\\OfferRepository',
    'shortName' => 'OfferRepository',
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
    'endLine' => 251,
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
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'name' => 'model',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Modules\\Offer\\Models\\Offer::class',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 42,
            'startFilePos' => 282,
            'endTokenPos' => 44,
            'endFilePos' => 293,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
        'startColumn' => 5,
        'endColumn' => 33,
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
            'startLine' => 13,
            'endLine' => 13,
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
        'startLine' => 13,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
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
        'startLine' => 62,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'aliasName' => NULL,
      ),
      'getProductIdsThatHaveOfferPrice' => 
      array (
        'name' => 'getProductIdsThatHaveOfferPrice',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 90,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
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
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 30,
            'endColumn' => 39,
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
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 42,
            'endColumn' => 49,
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
        'startLine' => 113,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
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
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 36,
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
        'startLine' => 146,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
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
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 29,
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
        'startLine' => 205,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
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
            'startLine' => 233,
            'endLine' => 233,
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
        'startLine' => 233,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Offer\\Repositories',
        'declaringClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'implementingClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
        'currentClassName' => 'Modules\\Offer\\Repositories\\OfferRepository',
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