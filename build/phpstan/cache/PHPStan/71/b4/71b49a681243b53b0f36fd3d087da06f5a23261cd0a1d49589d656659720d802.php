<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/AllowsCoupons.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Concerns\AllowsCoupons
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d1c5019cd101a5e2dcdce53eaabee41263886585ada6af1334c69bd8ce125ac2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/AllowsCoupons.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier\\Concerns',
    'name' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
    'shortName' => 'AllowsCoupons',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 111,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Laravel\\Cashier\\Concerns\\InteractsWithStripe',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'couponId' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'name' => 'couponId',
        'modifiers' => 1,
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
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 39,
            'startFilePos' => 254,
            'endTokenPos' => 39,
            'endFilePos' => 257,
          ),
        ),
        'docComment' => '/**
 * The coupon ID being applied.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'promotionCodeId' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'name' => 'promotionCodeId',
        'modifiers' => 1,
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
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 53,
            'startFilePos' => 359,
            'endTokenPos' => 53,
            'endFilePos' => 362,
          ),
        ),
        'docComment' => '/**
 * The promotion code ID being applied.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowPromotionCodes' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'name' => 'allowPromotionCodes',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 66,
            'startFilePos' => 532,
            'endTokenPos' => 66,
            'endFilePos' => 536,
          ),
        ),
        'docComment' => '/**
 * Determines if user redeemable promotion codes are available in Stripe Checkout.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 45,
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
      'withCoupon' => 
      array (
        'name' => 'withCoupon',
        'parameters' => 
        array (
          'couponId' => 
          array (
            'name' => 'couponId',
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 32,
            'endColumn' => 48,
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
 * The coupon ID to be applied.
 *
 * @return $this
 */',
        'startLine' => 34,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'aliasName' => NULL,
      ),
      'withPromotionCode' => 
      array (
        'name' => 'withPromotionCode',
        'parameters' => 
        array (
          'promotionCodeId' => 
          array (
            'name' => 'promotionCodeId',
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
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
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
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 39,
            'endColumn' => 62,
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
 * The promotion code ID to apply.
 *
 * @return $this
 */',
        'startLine' => 46,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'aliasName' => NULL,
      ),
      'allowPromotionCodes' => 
      array (
        'name' => 'allowPromotionCodes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Enables user redeemable promotion codes for a Stripe Checkout session.
 *
 * @return $this
 */',
        'startLine' => 58,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'aliasName' => NULL,
      ),
      'checkoutDiscounts' => 
      array (
        'name' => 'checkoutDiscounts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the discounts for a Stripe Checkout session.
 *
 * @return array[]|null
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\InvalidCoupon
 */',
        'startLine' => 72,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'aliasName' => NULL,
      ),
      'validateCouponForCheckout' => 
      array (
        'name' => 'validateCouponForCheckout',
        'parameters' => 
        array (
          'couponId' => 
          array (
            'name' => 'couponId',
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
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Validate that a coupon can be used in checkout sessions.
 *
 * @param  string  $couponId
 * @return void
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\InvalidCoupon
 * @throws \\Stripe\\Exception\\ApiErrorException
 */',
        'startLine' => 98,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\AllowsCoupons',
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