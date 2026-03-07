<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Console/WebhookCommand.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Console\WebhookCommand
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-fce9123ce380a2bb3cab19339655a0571eaec86fd31ed0026cb148be4295c39a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Console/WebhookCommand.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier\\Console',
    'name' => 'Laravel\\Cashier\\Console\\WebhookCommand',
    'shortName' => 'WebhookCommand',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
      0 => 
      array (
        'name' => 'Symfony\\Component\\Console\\Attribute\\AsCommand',
        'isRepeated' => false,
        'arguments' => 
        array (
          'name' => 
          array (
            'code' => '\'cashier:webhook\'',
            'attributes' => 
            array (
              'startLine' => 9,
              'endLine' => 9,
              'startTokenPos' => 28,
              'startFilePos' => 174,
              'endTokenPos' => 28,
              'endFilePos' => 190,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 9,
    'endLine' => 63,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Console\\Command',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'DEFAULT_EVENTS' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'implementingClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'name' => 'DEFAULT_EVENTS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'customer.subscription.created\', \'customer.subscription.updated\', \'customer.subscription.deleted\', \'customer.updated\', \'customer.deleted\', \'payment_method.automatically_updated\', \'invoice.payment_action_required\', \'invoice.payment_succeeded\']',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 21,
            'startTokenPos' => 50,
            'startFilePos' => 267,
            'endTokenPos' => 76,
            'endFilePos' => 580,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'signature' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'implementingClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'name' => 'signature',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'cashier:webhook
            {--disabled : Immediately disable the webhook after creation}
            {--url= : The URL endpoint for the webhook}
            {--api-version= : The Stripe API version the webhook should use}\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 31,
            'startTokenPos' => 87,
            'startFilePos' => 707,
            'endTokenPos' => 87,
            'endFilePos' => 930,
          ),
        ),
        'docComment' => '/**
 * The name and signature of the console command.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 78,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'description' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'implementingClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'name' => 'description',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'Create the Stripe webhook to interact with Cashier\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 98,
            'startFilePos' => 1045,
            'endTokenPos' => 98,
            'endFilePos' => 1096,
          ),
        ),
        'docComment' => '/**
 * The console command description.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 82,
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
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Execute the console command.
 *
 * @return void
 */',
        'startLine' => 45,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Console',
        'declaringClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'implementingClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
        'currentClassName' => 'Laravel\\Cashier\\Console\\WebhookCommand',
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