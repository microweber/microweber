<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Billing/Jobs/ProcessWebhookJob.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Billing\Jobs\ProcessWebhookJob
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-d92aa15964ae0ed47b63758da1059e6c10ef8b544bf89bf5517bf2ac66fc109d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Billing/Jobs/ProcessWebhookJob.php',
      ),
    ),
    'namespace' => 'Modules\\Billing\\Jobs',
    'name' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
    'shortName' => 'ProcessWebhookJob',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 81,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Bus\\Dispatchable',
      1 => 'Illuminate\\Queue\\InteractsWithQueue',
      2 => 'Illuminate\\Bus\\Queueable',
      3 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'tries' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'implementingClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'name' => 'tries',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '3',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 74,
            'startFilePos' => 524,
            'endTokenPos' => 74,
            'endFilePos' => 524,
          ),
        ),
        'docComment' => '/**
 * The number of times the job may be attempted.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'backoff' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'implementingClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'name' => 'backoff',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[30, 60, 120]',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 85,
            'startFilePos' => 628,
            'endTokenPos' => 93,
            'endFilePos' => 640,
          ),
        ),
        'docComment' => '/**
 * The number of seconds to wait before retrying the job.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'webhookLog' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'implementingClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'name' => 'webhookLog',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Billing\\Models\\WebhookLog',
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
        'startColumn' => 9,
        'endColumn' => 37,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'webhookLog' => 
          array (
            'name' => 'webhookLog',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Billing\\Models\\WebhookLog',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 9,
            'endColumn' => 37,
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
 * Create a new job instance.
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
        'namespace' => 'Modules\\Billing\\Jobs',
        'declaringClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'implementingClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'currentClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'aliasName' => NULL,
      ),
      'handle' => 
      array (
        'name' => 'handle',
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
 * Execute the job.
 */',
        'startLine' => 38,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Jobs',
        'declaringClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'implementingClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'currentClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'aliasName' => NULL,
      ),
      'failed' => 
      array (
        'name' => 'failed',
        'parameters' => 
        array (
          'exception' => 
          array (
            'name' => 'exception',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 28,
            'endColumn' => 48,
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
 * Handle a job failure.
 */',
        'startLine' => 71,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Jobs',
        'declaringClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'implementingClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
        'currentClassName' => 'Modules\\Billing\\Jobs\\ProcessWebhookJob',
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