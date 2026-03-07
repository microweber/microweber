<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/tests/Feature/Regression/BillingRegressionTest.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Tests\Feature\Regression\BillingRegressionTest
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-1edbf86096a4fb816244b572c9cd7a84f0caed15f8f90fe295b0ba2858e3495e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'filename' => '/home/headless/Documents/GitHub/microweber/tests/Feature/Regression/BillingRegressionTest.php',
      ),
    ),
    'namespace' => 'Tests\\Feature\\Regression',
    'name' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
    'shortName' => 'BillingRegressionTest',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Full Regression Test Suite - Billing & Subscriptions
 *
 * End-to-end testing of the complete billing flow including:
 * - Subscription creation
 * - Webhook handling
 * - Subscription cancellation
 * - Payment processing
 *
 * @covers \\Modules\\Billing
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 381,
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
      'admin' => 
      array (
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'name' => 'admin',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'customer' => 
      array (
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'name' => 'customer',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Models\\User',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 29,
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
        'startLine' => 34,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_complete_subscription_lifecycle' => 
      array (
        'name' => 'it_complete_subscription_lifecycle',
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
 * Test complete subscription flow: create → webhook → cancel
 */',
        'startLine' => 65,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_subscription_with_trial_period' => 
      array (
        'name' => 'it_subscription_with_trial_period',
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
 * Test subscription with trial period
 */',
        'startLine' => 114,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_webhook_signature_verification' => 
      array (
        'name' => 'it_webhook_signature_verification',
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
 * Test webhook signature verification
 */',
        'startLine' => 137,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_subscription_plan_change' => 
      array (
        'name' => 'it_subscription_plan_change',
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
 * Test subscription upgrade/downgrade
 */',
        'startLine' => 167,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_failed_payment_webhook_handling' => 
      array (
        'name' => 'it_failed_payment_webhook_handling',
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
 * Test failed payment webhook handling
 */',
        'startLine' => 201,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_subscription_renewal' => 
      array (
        'name' => 'it_subscription_renewal',
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
 * Test subscription renewal
 */',
        'startLine' => 233,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_admin_can_manage_all_subscriptions' => 
      array (
        'name' => 'it_admin_can_manage_all_subscriptions',
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
 * Test admin can view all subscriptions
 */',
        'startLine' => 262,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'it_subscription_stats_calculation' => 
      array (
        'name' => 'it_subscription_stats_calculation',
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
 * Test subscription stats calculation
 */',
        'startLine' => 285,
        'endLine' => 301,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'createTestPlan' => 
      array (
        'name' => 'createTestPlan',
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
                'startLine' => 306,
                'endLine' => 306,
                'startTokenPos' => 1802,
                'startFilePos' => 9858,
                'endTokenPos' => 1803,
                'endFilePos' => 9859,
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
            'startLine' => 306,
            'endLine' => 306,
            'startColumn' => 37,
            'endColumn' => 58,
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
            'name' => 'Modules\\Billing\\Models\\SubscriptionPlan',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a test subscription plan
 */',
        'startLine' => 306,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'subscribeToPlan' => 
      array (
        'name' => 'subscribeToPlan',
        'parameters' => 
        array (
          'plan' => 
          array (
            'name' => 'plan',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Billing\\Models\\SubscriptionPlan',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 322,
            'endLine' => 322,
            'startColumn' => 38,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 322,
                'endLine' => 322,
                'startTokenPos' => 1902,
                'startFilePos' => 10325,
                'endTokenPos' => 1903,
                'endFilePos' => 10326,
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
            'startLine' => 322,
            'endLine' => 322,
            'startColumn' => 62,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Billing\\Models\\Subscription',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Subscribe to a plan
 */',
        'startLine' => 322,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'simulateWebhook' => 
      array (
        'name' => 'simulateWebhook',
        'parameters' => 
        array (
          'eventType' => 
          array (
            'name' => 'eventType',
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
            'startLine' => 351,
            'endLine' => 351,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 351,
            'endLine' => 351,
            'startColumn' => 57,
            'endColumn' => 67,
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
 * Simulate Stripe webhook
 */',
        'startLine' => 351,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'aliasName' => NULL,
      ),
      'generateStripeSignature' => 
      array (
        'name' => 'generateStripeSignature',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
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
            'startLine' => 370,
            'endLine' => 370,
            'startColumn' => 46,
            'endColumn' => 59,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate Stripe webhook signature
 */',
        'startLine' => 370,
        'endLine' => 380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Tests\\Feature\\Regression',
        'declaringClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'implementingClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
        'currentClassName' => 'Tests\\Feature\\Regression\\BillingRegressionTest',
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