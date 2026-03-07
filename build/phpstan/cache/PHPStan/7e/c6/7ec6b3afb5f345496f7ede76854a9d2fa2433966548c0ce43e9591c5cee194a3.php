<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Http/Controllers/WebhookController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Http\Controllers\WebhookController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-62ee45afc49387cd5a49daded32fb240ee1630f9d94e15aa8d5af46ff2889539-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Http/Controllers/WebhookController.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
    'name' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
    'shortName' => 'WebhookController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 364,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Routing\\Controller',
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new WebhookController instance.
 *
 * @return void
 */',
        'startLine' => 27,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleWebhook' => 
      array (
        'name' => 'handleWebhook',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 35,
            'endColumn' => 50,
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
 * Handle a Stripe webhook call.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 40,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleCustomerSubscriptionCreated' => 
      array (
        'name' => 'handleCustomerSubscriptionCreated',
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
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 58,
            'endColumn' => 71,
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
 * Handle customer subscription created.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 66,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'newSubscriptionType' => 
      array (
        'name' => 'newSubscriptionType',
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
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 44,
            'endColumn' => 57,
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
 * Determines the type that should be used when new subscriptions are created from the Stripe dashboard.
 *
 * @param  array  $payload
 * @return string
 */',
        'startLine' => 121,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleCustomerSubscriptionUpdated' => 
      array (
        'name' => 'handleCustomerSubscriptionUpdated',
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
            'startLine' => 132,
            'endLine' => 132,
            'startColumn' => 58,
            'endColumn' => 71,
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
 * Handle customer subscription updated.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response|null
 */',
        'startLine' => 132,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleCustomerSubscriptionDeleted' => 
      array (
        'name' => 'handleCustomerSubscriptionDeleted',
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
            'startLine' => 217,
            'endLine' => 217,
            'startColumn' => 58,
            'endColumn' => 71,
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
 * Handle the cancellation of a customer subscription.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 217,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleCustomerUpdated' => 
      array (
        'name' => 'handleCustomerUpdated',
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
            'startLine' => 236,
            'endLine' => 236,
            'startColumn' => 46,
            'endColumn' => 59,
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
 * Handle customer updated.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 236,
        'endLine' => 243,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleCustomerDeleted' => 
      array (
        'name' => 'handleCustomerDeleted',
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
            'startLine' => 251,
            'endLine' => 251,
            'startColumn' => 46,
            'endColumn' => 59,
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
 * Handle deleted customer.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 251,
        'endLine' => 267,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handlePaymentMethodAutomaticallyUpdated' => 
      array (
        'name' => 'handlePaymentMethodAutomaticallyUpdated',
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
            'startLine' => 275,
            'endLine' => 275,
            'startColumn' => 64,
            'endColumn' => 77,
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
 * Handle payment method automatically updated by vendor.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 275,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleInvoicePaymentActionRequired' => 
      array (
        'name' => 'handleInvoicePaymentActionRequired',
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
            'startLine' => 290,
            'endLine' => 290,
            'startColumn' => 59,
            'endColumn' => 72,
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
 * Handle payment action required for invoice.
 *
 * @param  array  $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 290,
        'endLine' => 319,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'getUserByStripeId' => 
      array (
        'name' => 'getUserByStripeId',
        'parameters' => 
        array (
          'stripeId' => 
          array (
            'name' => 'stripeId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 327,
            'endLine' => 327,
            'startColumn' => 42,
            'endColumn' => 50,
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
 * Get the customer instance by Stripe ID.
 *
 * @param  string|null  $stripeId
 * @return \\Laravel\\Cashier\\Billable|null
 */',
        'startLine' => 327,
        'endLine' => 330,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'successMethod' => 
      array (
        'name' => 'successMethod',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 338,
                'endLine' => 338,
                'startTokenPos' => 2018,
                'startFilePos' => 11376,
                'endTokenPos' => 2019,
                'endFilePos' => 11377,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 338,
            'endLine' => 338,
            'startColumn' => 38,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Handle successful calls on the controller.
 *
 * @param  array  $parameters
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 338,
        'endLine' => 341,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'missingMethod' => 
      array (
        'name' => 'missingMethod',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 349,
                'endLine' => 349,
                'startTokenPos' => 2051,
                'startFilePos' => 11670,
                'endTokenPos' => 2052,
                'endFilePos' => 11671,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 349,
            'endLine' => 349,
            'startColumn' => 38,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Handle calls to missing methods on the controller.
 *
 * @param  array  $parameters
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 349,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'setMaxNetworkRetries' => 
      array (
        'name' => 'setMaxNetworkRetries',
        'parameters' => 
        array (
          'retries' => 
          array (
            'name' => 'retries',
            'default' => 
            array (
              'code' => '3',
              'attributes' => 
              array (
                'startLine' => 360,
                'endLine' => 360,
                'startTokenPos' => 2078,
                'startFilePos' => 11929,
                'endTokenPos' => 2078,
                'endFilePos' => 11929,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 45,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the number of automatic retries due to an object lock timeout from Stripe.
 *
 * @param  int  $retries
 * @return void
 */',
        'startLine' => 360,
        'endLine' => 363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'declaringClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
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