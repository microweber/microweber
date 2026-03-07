<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Billing/Models/WebhookLog.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Billing\Models\WebhookLog
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-da67522df90c5eff5d4ccd7e59b7aa9a5a8a715bc9b7f708bf42eb7b5462d155',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Billing\\Models\\WebhookLog',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Billing/Models/WebhookLog.php',
      ),
    ),
    'namespace' => 'Modules\\Billing\\Models',
    'name' => 'Modules\\Billing\\Models\\WebhookLog',
    'shortName' => 'WebhookLog',
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
    'endLine' => 110,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
    ),
    'immediateConstants' => 
    array (
      'STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 103,
            'startFilePos' => 582,
            'endTokenPos' => 103,
            'endFilePos' => 590,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'STATUS_PROCESSING' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'STATUS_PROCESSING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'processing\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 112,
            'startFilePos' => 623,
            'endTokenPos' => 112,
            'endFilePos' => 634,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'STATUS_COMPLETED' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'STATUS_COMPLETED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'completed\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 121,
            'startFilePos' => 666,
            'endTokenPos' => 121,
            'endFilePos' => 676,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'STATUS_FAILED' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'STATUS_FAILED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'failed\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 130,
            'startFilePos' => 705,
            'endTokenPos' => 130,
            'endFilePos' => 712,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'STATUS_RETRYING' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'STATUS_RETRYING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'retrying\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 139,
            'startFilePos' => 743,
            'endTokenPos' => 139,
            'endFilePos' => 752,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'provider\', \'event_type\', \'event_id\', \'payload\', \'status\', \'attempts\', \'error_message\', \'processed_at\']',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 22,
            'startTokenPos' => 43,
            'startFilePos' => 276,
            'endTokenPos' => 69,
            'endFilePos' => 450,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'payload\' => \'array\', \'processed_at\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 27,
            'startTokenPos' => 78,
            'startFilePos' => 477,
            'endTokenPos' => 94,
            'endFilePos' => 551,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Billing\\Database\\Factories\\WebhookLogFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new factory instance for the model.
 */',
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'scopePending' => 
      array (
        'name' => 'scopePending',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Scope to get pending webhooks.
 */',
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'scopeFailed' => 
      array (
        'name' => 'scopeFailed',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Scope to get failed webhooks that need retry.
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'markAsCompleted' => 
      array (
        'name' => 'markAsCompleted',
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
 * Mark webhook as completed.
 */',
        'startLine' => 62,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'markAsFailed' => 
      array (
        'name' => 'markAsFailed',
        'parameters' => 
        array (
          'errorMessage' => 
          array (
            'name' => 'errorMessage',
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
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 34,
            'endColumn' => 53,
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
 * Mark webhook as failed with error message.
 */',
        'startLine' => 73,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'markAsProcessing' => 
      array (
        'name' => 'markAsProcessing',
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
 * Mark webhook as processing.
 */',
        'startLine' => 85,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'markForRetry' => 
      array (
        'name' => 'markForRetry',
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
 * Mark webhook for retry.
 */',
        'startLine' => 96,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'aliasName' => NULL,
      ),
      'canRetry' => 
      array (
        'name' => 'canRetry',
        'parameters' => 
        array (
          'maxRetries' => 
          array (
            'name' => 'maxRetries',
            'default' => 
            array (
              'code' => '3',
              'attributes' => 
              array (
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 433,
                'startFilePos' => 2362,
                'endTokenPos' => 433,
                'endFilePos' => 2362,
              ),
            ),
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
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 30,
            'endColumn' => 48,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if webhook can be retried.
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Billing\\Models',
        'declaringClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'implementingClassName' => 'Modules\\Billing\\Models\\WebhookLog',
        'currentClassName' => 'Modules\\Billing\\Models\\WebhookLog',
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