<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Billing/Http/Controllers/WebhookController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Billing\Http\Controllers\WebhookController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-e19b5f642bfde2c21da627181ab10feca831d0983d846360a3537e0fdf5e8fef',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Billing/Http/Controllers/WebhookController.php',
      ),
    ),
    'namespace' => 'Modules\\Billing\\Http\\Controllers',
    'name' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
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
    'startLine' => 15,
    'endLine' => 153,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController',
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
 */',
        'startLine' => 20,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Http\\Controllers',
        'declaringClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
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
            'startLine' => 33,
            'endLine' => 33,
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
 * @param \\Illuminate\\Http\\Request $request
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 33,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Http\\Controllers',
        'declaringClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'logWebhook' => 
      array (
        'name' => 'logWebhook',
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
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
                  'name' => 'Modules\\Billing\\Models\\WebhookLog',
                  'isIdentifier' => false,
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
 * Log webhook to database.
 *
 * @param array $payload
 * @return WebhookLog|null
 */',
        'startLine' => 66,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Billing\\Http\\Controllers',
        'declaringClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'aliasName' => NULL,
      ),
      'handleInvoicePaid' => 
      array (
        'name' => 'handleInvoicePaid',
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
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 42,
            'endColumn' => 55,
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
 * Handle invoice paid event.
 *
 * @param array $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 102,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Billing\\Http\\Controllers',
        'declaringClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
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
            'startLine' => 135,
            'endLine' => 135,
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
 * Handle customer subscription updated event.
 *
 * @param array $payload
 * @return \\Symfony\\Component\\HttpFoundation\\Response|null
 */',
        'startLine' => 135,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Billing\\Http\\Controllers',
        'declaringClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'implementingClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
        'currentClassName' => 'Modules\\Billing\\Http\\Controllers\\WebhookController',
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