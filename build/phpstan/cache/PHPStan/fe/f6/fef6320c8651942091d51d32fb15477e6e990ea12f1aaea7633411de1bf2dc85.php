<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../alexwestergaard/php-ga4/src/Facade/Group/BeginCheckoutFacade.php-PHPStan\BetterReflection\Reflection\ReflectionClass-AlexWestergaard\PhpGa4\Facade\Group\BeginCheckoutFacade
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-aca67c6b538679d56dd0882e2c83cc253508eb562e1d0753436f335bd42d5af9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../alexwestergaard/php-ga4/src/Facade/Group/BeginCheckoutFacade.php',
      ),
    ),
    'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
    'name' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
    'shortName' => 'BeginCheckoutFacade',
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
    'endLine' => 33,
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
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
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
            'startLine' => 23,
            'endLine' => 23,
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
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
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
            'startLine' => 32,
            'endLine' => 32,
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
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'AlexWestergaard\\PhpGa4\\Facade\\Group',
        'declaringClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'implementingClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
        'currentClassName' => 'AlexWestergaard\\PhpGa4\\Facade\\Group\\BeginCheckoutFacade',
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