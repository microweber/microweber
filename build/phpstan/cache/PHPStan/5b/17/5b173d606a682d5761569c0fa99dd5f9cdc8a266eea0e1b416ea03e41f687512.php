<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/tests/Feature/Regression/FrontendCheckoutRegressionTest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Tests\Feature\Regression\FrontendCheckoutRegressionTest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-83b8868924c8d4dc0c914da61851df7b6c4a6218d3a638701e770940be8bad7a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'filename' => '/home/headless/Documents/GitHub/microweber/tests/Feature/Regression/FrontendCheckoutRegressionTest.php',
      ),
    ),
    'namespace' => 'Tests\\Feature\\Regression',
    'name' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
    'shortName' => 'FrontendCheckoutRegressionTest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Full Regression Test Suite - Frontend Checkout Flow
 *
 * End-to-end testing of the complete checkout flow including:
 * - Add to cart
 * - Cart management
 * - Checkout process
 * - Payment integration
 *
 * @covers \\Modules\\Cart
 * @covers \\Modules\\Checkout
 * @covers \\Modules\\Order
 * @covers \\Modules\\Payment
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 419,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Tests\\TestCase',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Testing\\RefreshDatabase',
      1 => 'Illuminate\\Foundation\\Testing\\WithFaker',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'setUp' => 
      array (
        'name' => 'setUp',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 33,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_complete_checkout_flow_with_bank_transfer' => 
      array (
        'name' => 'it_complete_checkout_flow_with_bank_transfer',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test complete checkout flow with bank transfer
 */',
        'startLine' => 42,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_checkout_flow_with_paypal' => 
      array (
        'name' => 'it_checkout_flow_with_paypal',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test PayPal checkout flow
 */',
        'startLine' => 98,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_cart_persists_across_sessions' => 
      array (
        'name' => 'it_cart_persists_across_sessions',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test cart persistence across sessions
 */',
        'startLine' => 138,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_cart_quantity_update' => 
      array (
        'name' => 'it_cart_quantity_update',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test cart item quantity update
 */',
        'startLine' => 164,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_cart_item_removal' => 
      array (
        'name' => 'it_cart_item_removal',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test cart item removal
 */',
        'startLine' => 193,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_checkout_validates_required_fields' => 
      array (
        'name' => 'it_checkout_validates_required_fields',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test checkout validation
 */',
        'startLine' => 226,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_checkout_fails_with_empty_cart' => 
      array (
        'name' => 'it_checkout_fails_with_empty_cart',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test checkout with empty cart
 */',
        'startLine' => 250,
        'endLine' => 263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_checkout_validates_stock' => 
      array (
        'name' => 'it_checkout_validates_stock',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test stock validation during checkout
 */',
        'startLine' => 268,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_coupon_code_application' => 
      array (
        'name' => 'it_coupon_code_application',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test coupon code application
 */',
        'startLine' => 290,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_shipping_calculation' => 
      array (
        'name' => 'it_shipping_calculation',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test shipping calculation
 */',
        'startLine' => 323,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'it_order_confirmation_email_sent' => 
      array (
        'name' => 'it_order_confirmation_email_sent',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'PHPUnit\\Framework\\Attributes\\Test',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * Test order confirmation email
 */',
        'startLine' => 352,
        'endLine' => 378,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'createTestProduct' => 
      array (
        'name' => 'createTestProduct',
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
                'startLine' => 383,
                'endLine' => 383,
                'startTokenPos' => 1969,
                'startFilePos' => 10407,
                'endTokenPos' => 1970,
                'endFilePos' => 10408,
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
            'startLine' => 383,
            'endLine' => 383,
            'startColumn' => 40,
            'endColumn' => 61,
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
            'name' => 'Modules\\Product\\Models\\Product',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a test product
 */',
        'startLine' => 383,
        'endLine' => 394,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'aliasName' => NULL,
      ),
      'seedPaymentProviders' => 
      array (
        'name' => 'seedPaymentProviders',
        'parameters' => 
        array (
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
 * Seed payment providers
 */',
        'startLine' => 399,
        'endLine' => 418,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\FrontendCheckoutRegressionTest',
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