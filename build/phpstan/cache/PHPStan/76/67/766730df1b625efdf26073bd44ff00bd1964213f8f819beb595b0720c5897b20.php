<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/tests/Browser/CriticalFlowsTest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Tests\Browser\CriticalFlowsTest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-eab3b7ec1ecf2510a80388b3b827b78191fd741b16485d7702bdd71c3d149f82',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Tests\\Browser\\CriticalFlowsTest',
        'filename' => '/home/headless/Documents/GitHub/microweber/tests/Browser/CriticalFlowsTest.php',
      ),
    ),
    'namespace' => 'Tests\\Browser',
    'name' => 'Tests\\Browser\\CriticalFlowsTest',
    'shortName' => 'CriticalFlowsTest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Critical Legacy Dusk Flows
 *
 * These tests cover the most critical user flows that must never break:
 * 1. Shop checkout with bank transfer
 * 2. PayPal checkout redirection
 * 3. Admin dashboard with widgets
 * 4. XSS protection in forms
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 543,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Tests\\DuskTestCase',
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
    ),
    'immediateMethods' => 
    array (
      'it_full_shop_checkout_flow_with_bank_transfer' => 
      array (
        'name' => 'it_full_shop_checkout_flow_with_bank_transfer',
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
 * Test the full shop checkout flow using bank transfer payment method.
 * This verifies end-to-end cart, checkout, and order completion functionality.
 */',
        'startLine' => 33,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'it_checkout_with_paypal_redirects_correctly' => 
      array (
        'name' => 'it_checkout_with_paypal_redirects_correctly',
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
 * Test that PayPal checkout properly redirects to PayPal sandbox.
 * Note: This test validates the redirect flow only, not the actual payment.
 */',
        'startLine' => 107,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'it_admin_dashboard_loads_all_widgets' => 
      array (
        'name' => 'it_admin_dashboard_loads_all_widgets',
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
 * Test that admin dashboard loads and displays all widgets correctly.
 * This verifies Filament v5 widget rendering and data loading.
 */',
        'startLine' => 194,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'it_xss_payloads_not_executed_in_inputs' => 
      array (
        'name' => 'it_xss_payloads_not_executed_in_inputs',
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
 * Test that XSS payloads are properly sanitized and not executed.
 * This is a critical security test for all input fields.
 */',
        'startLine' => 252,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'setupShopEnvironment' => 
      array (
        'name' => 'setupShopEnvironment',
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
 * Setup shop environment with required payment and shipping options.
 */',
        'startLine' => 360,
        'endLine' => 405,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'createTestProduct' => 
      array (
        'name' => 'createTestProduct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a test product for checkout tests.
 */',
        'startLine' => 410,
        'endLine' => 448,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'addProductToCart' => 
      array (
        'name' => 'addProductToCart',
        'parameters' => 
        array (
          'browser' => 
          array (
            'name' => 'browser',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Dusk\\Browser',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 453,
            'endLine' => 453,
            'startColumn' => 39,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'product' => 
          array (
            'name' => 'product',
            'default' => NULL,
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
            'startLine' => 453,
            'endLine' => 453,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 1,
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
 * Add a product to cart using the browser.
 */',
        'startLine' => 453,
        'endLine' => 476,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'fillShippingInformation' => 
      array (
        'name' => 'fillShippingInformation',
        'parameters' => 
        array (
          'browser' => 
          array (
            'name' => 'browser',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Dusk\\Browser',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 46,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'uniqueId' => 
          array (
            'name' => 'uniqueId',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 64,
            'endColumn' => 76,
            'parameterIndex' => 1,
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
 * Fill shipping information in checkout form.
 */',
        'startLine' => 481,
        'endLine' => 511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'aliasName' => NULL,
      ),
      'createDashboardTestData' => 
      array (
        'name' => 'createDashboardTestData',
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
 * Create test data for dashboard widgets.
 */',
        'startLine' => 516,
        'endLine' => 542,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Browser',
        'declaringClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'implementingClassName' => 'Tests\\Browser\\CriticalFlowsTest',
        'currentClassName' => 'Tests\\Browser\\CriticalFlowsTest',
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