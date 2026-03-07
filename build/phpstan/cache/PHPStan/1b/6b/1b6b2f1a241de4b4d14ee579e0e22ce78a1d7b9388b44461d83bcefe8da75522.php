<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Queue/QueueServiceProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Queue\QueueServiceProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1eef0de231d193da800afac53fb93e748c30b124c16afe9bf62b37c2ad9f0a12-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Queue\\QueueServiceProvider',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Queue/QueueServiceProvider.php',
      ),
    ),
    'namespace' => 'Illuminate\\Queue',
    'name' => 'Illuminate\\Queue\\QueueServiceProvider',
    'shortName' => 'QueueServiceProvider',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 346,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\ServiceProvider',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\DeferrableProvider',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'register' => 
      array (
        'name' => 'register',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the service provider.
 *
 * @return void
 */',
        'startLine' => 33,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'configureSerializableClosureUses' => 
      array (
        'name' => 'configureSerializableClosureUses',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configure serializable closures uses.
 *
 * @return void
 */',
        'startLine' => 49,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerManager' => 
      array (
        'name' => 'registerManager',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the queue manager.
 *
 * @return void
 */',
        'startLine' => 73,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerConnection' => 
      array (
        'name' => 'registerConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the default queue connection binding.
 *
 * @return void
 */',
        'startLine' => 90,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerConnectors' => 
      array (
        'name' => 'registerConnectors',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 40,
            'endColumn' => 47,
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
 * Register the connectors on the queue manager.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 103,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerNullConnector' => 
      array (
        'name' => 'registerNullConnector',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 46,
            'endColumn' => 53,
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
 * Register the Null queue connector.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 116,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerSyncConnector' => 
      array (
        'name' => 'registerSyncConnector',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 46,
            'endColumn' => 53,
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
 * Register the Sync queue connector.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 129,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerDatabaseConnector' => 
      array (
        'name' => 'registerDatabaseConnector',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 142,
            'endLine' => 142,
            'startColumn' => 50,
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
 * Register the database queue connector.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 142,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerRedisConnector' => 
      array (
        'name' => 'registerRedisConnector',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 155,
            'endLine' => 155,
            'startColumn' => 47,
            'endColumn' => 54,
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
 * Register the Redis queue connector.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 155,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerBeanstalkdConnector' => 
      array (
        'name' => 'registerBeanstalkdConnector',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 168,
            'endLine' => 168,
            'startColumn' => 52,
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
 * Register the Beanstalkd queue connector.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 168,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerSqsConnector' => 
      array (
        'name' => 'registerSqsConnector',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 181,
            'endLine' => 181,
            'startColumn' => 45,
            'endColumn' => 52,
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
 * Register the Amazon SQS queue connector.
 *
 * @param  \\Illuminate\\Queue\\QueueManager  $manager
 * @return void
 */',
        'startLine' => 181,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerWorker' => 
      array (
        'name' => 'registerWorker',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the queue worker.
 *
 * @return void
 */',
        'startLine' => 193,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerListener' => 
      array (
        'name' => 'registerListener',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the queue listener.
 *
 * @return void
 */',
        'startLine' => 236,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'registerFailedJobServices' => 
      array (
        'name' => 'registerFailedJobServices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the failed job services.
 *
 * @return void
 */',
        'startLine' => 248,
        'endLine' => 274,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'databaseFailedJobProvider' => 
      array (
        'name' => 'databaseFailedJobProvider',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 282,
            'endLine' => 282,
            'startColumn' => 50,
            'endColumn' => 56,
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
 * Create a new database failed job provider.
 *
 * @param  array  $config
 * @return \\Illuminate\\Queue\\Failed\\DatabaseFailedJobProvider
 */',
        'startLine' => 282,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'databaseUuidFailedJobProvider' => 
      array (
        'name' => 'databaseUuidFailedJobProvider',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 295,
            'endLine' => 295,
            'startColumn' => 54,
            'endColumn' => 60,
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
 * Create a new database failed job provider that uses UUIDs as IDs.
 *
 * @param  array  $config
 * @return \\Illuminate\\Queue\\Failed\\DatabaseUuidFailedJobProvider
 */',
        'startLine' => 295,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'dynamoFailedJobProvider' => 
      array (
        'name' => 'dynamoFailedJobProvider',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 48,
            'endColumn' => 54,
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
 * Create a new DynamoDb failed job provider.
 *
 * @param  array  $config
 * @return \\Illuminate\\Queue\\Failed\\DynamoDbFailedJobProvider
 */',
        'startLine' => 308,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'aliasName' => NULL,
      ),
      'provides' => 
      array (
        'name' => 'provides',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the services provided by the provider.
 *
 * @return array
 */',
        'startLine' => 336,
        'endLine' => 345,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Queue',
        'declaringClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'implementingClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
        'currentClassName' => 'Illuminate\\Queue\\QueueServiceProvider',
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