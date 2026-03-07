<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Kernel.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Http\Kernel
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-680b09ceadb49581b6645aa010e2b875a1c2e5b4b693123d88351b2fd7131a42-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Http\\Kernel',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Kernel.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Http',
    'name' => 'Illuminate\\Foundation\\Http\\Kernel',
    'shortName' => 'Kernel',
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
    'endLine' => 702,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Http\\Kernel',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\InteractsWithTime',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'app' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'app',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The application implementation.
 *
 * @var \\Illuminate\\Contracts\\Foundation\\Application
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'router' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'router',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The router instance.
 *
 * @var \\Illuminate\\Routing\\Router
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootstrappers' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'bootstrappers',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\\Illuminate\\Foundation\\Bootstrap\\LoadEnvironmentVariables::class, \\Illuminate\\Foundation\\Bootstrap\\LoadConfiguration::class, \\Illuminate\\Foundation\\Bootstrap\\HandleExceptions::class, \\Illuminate\\Foundation\\Bootstrap\\RegisterFacades::class, \\Illuminate\\Foundation\\Bootstrap\\RegisterProviders::class, \\Illuminate\\Foundation\\Bootstrap\\BootProviders::class]',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 50,
            'startTokenPos' => 118,
            'startFilePos' => 1020,
            'endTokenPos' => 150,
            'endFilePos' => 1428,
          ),
        ),
        'docComment' => '/**
 * The bootstrap classes for the application.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'middleware' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'middleware',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 161,
            'startFilePos' => 1570,
            'endTokenPos' => 162,
            'endFilePos' => 1571,
          ),
        ),
        'docComment' => '/**
 * The application\'s middleware stack.
 *
 * @var array<int, class-string|string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'middlewareGroups' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'middlewareGroups',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 173,
            'startFilePos' => 1741,
            'endTokenPos' => 174,
            'endFilePos' => 1742,
          ),
        ),
        'docComment' => '/**
 * The application\'s route middleware groups.
 *
 * @var array<string, array<int, class-string|string>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'routeMiddleware' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'routeMiddleware',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 73,
            'endLine' => 73,
            'startTokenPos' => 185,
            'startFilePos' => 1918,
            'endTokenPos' => 186,
            'endFilePos' => 1919,
          ),
        ),
        'docComment' => '/**
 * The application\'s route middleware.
 *
 * @var array<string, class-string|string>
 *
 * @deprecated
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 73,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'middlewareAliases' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'middlewareAliases',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 80,
            'endLine' => 80,
            'startTokenPos' => 197,
            'startFilePos' => 2073,
            'endTokenPos' => 198,
            'endFilePos' => 2074,
          ),
        ),
        'docComment' => '/**
 * The application\'s middleware aliases.
 *
 * @var array<string, class-string|string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 80,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'requestLifecycleDurationHandlers' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'requestLifecycleDurationHandlers',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 87,
            'startTokenPos' => 209,
            'startFilePos' => 2225,
            'endTokenPos' => 210,
            'endFilePos' => 2226,
          ),
        ),
        'docComment' => '/**
 * All of the registered request duration handlers.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 53,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'requestStartedAt' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'requestStartedAt',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * When the kernel starting handling the current request.
 *
 * @var \\Illuminate\\Support\\Carbon|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'middlewarePriority' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'name' => 'middlewarePriority',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\\Illuminate\\Foundation\\Http\\Middleware\\HandlePrecognitiveRequests::class, \\Illuminate\\Cookie\\Middleware\\EncryptCookies::class, \\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse::class, \\Illuminate\\Session\\Middleware\\StartSession::class, \\Illuminate\\View\\Middleware\\ShareErrorsFromSession::class, \\Illuminate\\Contracts\\Auth\\Middleware\\AuthenticatesRequests::class, \\Illuminate\\Routing\\Middleware\\ThrottleRequests::class, \\Illuminate\\Routing\\Middleware\\ThrottleRequestsWithRedis::class, \\Illuminate\\Contracts\\Session\\Middleware\\AuthenticatesSessions::class, \\Illuminate\\Routing\\Middleware\\SubstituteBindings::class, \\Illuminate\\Auth\\Middleware\\Authorize::class]',
          'attributes' => 
          array (
            'startLine' => 103,
            'endLine' => 115,
            'startTokenPos' => 228,
            'startFilePos' => 2596,
            'endTokenPos' => 285,
            'endFilePos' => 3357,
          ),
        ),
        'docComment' => '/**
 * The priority-sorted list of middleware.
 *
 * Forces non-global middleware to always be in the given order.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 103,
        'endLine' => 115,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Foundation\\Application',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'router' => 
          array (
            'name' => 'router',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Routing\\Router',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 51,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new HTTP kernel instance.
 *
 * @param  \\Illuminate\\Contracts\\Foundation\\Application  $app
 * @param  \\Illuminate\\Routing\\Router  $router
 * @return void
 */',
        'startLine' => 124,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 28,
            'endColumn' => 35,
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
 * Handle an incoming HTTP request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Illuminate\\Http\\Response
 */',
        'startLine' => 138,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'sendRequestThroughRouter' => 
      array (
        'name' => 'sendRequestThroughRouter',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 165,
            'endLine' => 165,
            'startColumn' => 49,
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
 * Send the given request through the middleware / router.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return \\Illuminate\\Http\\Response
 */',
        'startLine' => 165,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'bootstrap' => 
      array (
        'name' => 'bootstrap',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Bootstrap the application for HTTP requests.
 *
 * @return void
 */',
        'startLine' => 184,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'dispatchToRouter' => 
      array (
        'name' => 'dispatchToRouter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the route dispatcher callback.
 *
 * @return \\Closure
 */',
        'startLine' => 196,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'terminate' => 
      array (
        'name' => 'terminate',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Call the terminate method on any terminable middleware.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Illuminate\\Http\\Response  $response
 * @return void
 */',
        'startLine' => 212,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'terminateMiddleware' => 
      array (
        'name' => 'terminateMiddleware',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 244,
            'endLine' => 244,
            'startColumn' => 44,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 244,
            'endLine' => 244,
            'startColumn' => 54,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Call the terminate method on any terminable middleware.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Illuminate\\Http\\Response  $response
 * @return void
 */',
        'startLine' => 244,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'whenRequestLifecycleIsLongerThan' => 
      array (
        'name' => 'whenRequestLifecycleIsLongerThan',
        'parameters' => 
        array (
          'threshold' => 
          array (
            'name' => 'threshold',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 54,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'handler' => 
          array (
            'name' => 'handler',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 66,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register a callback to be invoked when the requests lifecycle duration exceeds a given amount of time.
 *
 * @param  \\DateTimeInterface|\\Carbon\\CarbonInterval|float|int  $threshold
 * @param  callable  $handler
 * @return void
 */',
        'startLine' => 273,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'requestStartedAt' => 
      array (
        'name' => 'requestStartedAt',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * When the request being handled started.
 *
 * @return \\Illuminate\\Support\\Carbon|null
 */',
        'startLine' => 294,
        'endLine' => 297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'gatherRouteMiddleware' => 
      array (
        'name' => 'gatherRouteMiddleware',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 305,
            'endLine' => 305,
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
 * Gather the route middleware for the given request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return array
 */',
        'startLine' => 305,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'parseMiddleware' => 
      array (
        'name' => 'parseMiddleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 40,
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
 * Parse a middleware string to get the name and parameters.
 *
 * @param  string  $middleware
 * @return array
 */',
        'startLine' => 320,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'hasMiddleware' => 
      array (
        'name' => 'hasMiddleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 337,
            'endLine' => 337,
            'startColumn' => 35,
            'endColumn' => 45,
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
 * Determine if the kernel has a given middleware.
 *
 * @param  string  $middleware
 * @return bool
 */',
        'startLine' => 337,
        'endLine' => 340,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'prependMiddleware' => 
      array (
        'name' => 'prependMiddleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 39,
            'endColumn' => 49,
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
 * Add a new middleware to the beginning of the stack if it does not already exist.
 *
 * @param  string  $middleware
 * @return $this
 */',
        'startLine' => 348,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'pushMiddleware' => 
      array (
        'name' => 'pushMiddleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 363,
            'endLine' => 363,
            'startColumn' => 36,
            'endColumn' => 46,
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
 * Add a new middleware to end of the stack if it does not already exist.
 *
 * @param  string  $middleware
 * @return $this
 */',
        'startLine' => 363,
        'endLine' => 370,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'prependMiddlewareToGroup' => 
      array (
        'name' => 'prependMiddlewareToGroup',
        'parameters' => 
        array (
          'group' => 
          array (
            'name' => 'group',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 381,
            'endLine' => 381,
            'startColumn' => 46,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 381,
            'endLine' => 381,
            'startColumn' => 54,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepend the given middleware to the given middleware group.
 *
 * @param  string  $group
 * @param  string  $middleware
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 381,
        'endLine' => 394,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'appendMiddlewareToGroup' => 
      array (
        'name' => 'appendMiddlewareToGroup',
        'parameters' => 
        array (
          'group' => 
          array (
            'name' => 'group',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 405,
            'endLine' => 405,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 405,
            'endLine' => 405,
            'startColumn' => 53,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Append the given middleware to the given middleware group.
 *
 * @param  string  $group
 * @param  string  $middleware
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 405,
        'endLine' => 418,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'prependToMiddlewarePriority' => 
      array (
        'name' => 'prependToMiddlewarePriority',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 426,
            'endLine' => 426,
            'startColumn' => 49,
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
 * Prepend the given middleware to the middleware priority list.
 *
 * @param  string  $middleware
 * @return $this
 */',
        'startLine' => 426,
        'endLine' => 435,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'appendToMiddlewarePriority' => 
      array (
        'name' => 'appendToMiddlewarePriority',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 443,
            'endLine' => 443,
            'startColumn' => 48,
            'endColumn' => 58,
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
 * Append the given middleware to the middleware priority list.
 *
 * @param  string  $middleware
 * @return $this
 */',
        'startLine' => 443,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'addToMiddlewarePriorityBefore' => 
      array (
        'name' => 'addToMiddlewarePriorityBefore',
        'parameters' => 
        array (
          'before' => 
          array (
            'name' => 'before',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 51,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 60,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the given middleware to the middleware priority list before other middleware.
 *
 * @param  array|string  $before
 * @param  string  $middleware
 * @return $this
 */',
        'startLine' => 461,
        'endLine' => 464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'addToMiddlewarePriorityAfter' => 
      array (
        'name' => 'addToMiddlewarePriorityAfter',
        'parameters' => 
        array (
          'after' => 
          array (
            'name' => 'after',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 473,
            'endLine' => 473,
            'startColumn' => 50,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 473,
            'endLine' => 473,
            'startColumn' => 58,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the given middleware to the middleware priority list after other middleware.
 *
 * @param  array|string  $after
 * @param  string  $middleware
 * @return $this
 */',
        'startLine' => 473,
        'endLine' => 476,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'addToMiddlewarePriorityRelative' => 
      array (
        'name' => 'addToMiddlewarePriorityRelative',
        'parameters' => 
        array (
          'existing' => 
          array (
            'name' => 'existing',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 56,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 67,
            'endColumn' => 77,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'after' => 
          array (
            'name' => 'after',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 486,
                'endLine' => 486,
                'startTokenPos' => 1761,
                'startFilePos' => 13458,
                'endTokenPos' => 1761,
                'endFilePos' => 13461,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 80,
            'endColumn' => 92,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the given middleware to the middleware priority list relative to other middleware.
 *
 * @param  string|array  $existing
 * @param  string  $middleware
 * @param  bool  $after
 * @return $this
 */',
        'startLine' => 486,
        'endLine' => 515,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'syncMiddlewareToRouter' => 
      array (
        'name' => 'syncMiddlewareToRouter',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sync the current state of the middleware to the router.
 *
 * @return void
 */',
        'startLine' => 522,
        'endLine' => 533,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'getMiddlewarePriority' => 
      array (
        'name' => 'getMiddlewarePriority',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the priority-sorted list of middleware.
 *
 * @return array
 */',
        'startLine' => 540,
        'endLine' => 543,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'bootstrappers' => 
      array (
        'name' => 'bootstrappers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the bootstrap classes for the application.
 *
 * @return array
 */',
        'startLine' => 550,
        'endLine' => 553,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'reportException' => 
      array (
        'name' => 'reportException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
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
            'startLine' => 561,
            'endLine' => 561,
            'startColumn' => 40,
            'endColumn' => 51,
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
 * Report the exception to the exception handler.
 *
 * @param  \\Throwable  $e
 * @return void
 */',
        'startLine' => 561,
        'endLine' => 564,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'renderException' => 
      array (
        'name' => 'renderException',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 573,
            'endLine' => 573,
            'startColumn' => 40,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'e' => 
          array (
            'name' => 'e',
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
            'startLine' => 573,
            'endLine' => 573,
            'startColumn' => 50,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the exception to a response.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Throwable  $e
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 573,
        'endLine' => 576,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'getGlobalMiddleware' => 
      array (
        'name' => 'getGlobalMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s global middleware.
 *
 * @return array
 */',
        'startLine' => 583,
        'endLine' => 586,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'setGlobalMiddleware' => 
      array (
        'name' => 'setGlobalMiddleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
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
            'startLine' => 594,
            'endLine' => 594,
            'startColumn' => 41,
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
 * Set the application\'s global middleware.
 *
 * @param  array  $middleware
 * @return $this
 */',
        'startLine' => 594,
        'endLine' => 601,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'getMiddlewareGroups' => 
      array (
        'name' => 'getMiddlewareGroups',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s route middleware groups.
 *
 * @return array
 */',
        'startLine' => 608,
        'endLine' => 611,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'setMiddlewareGroups' => 
      array (
        'name' => 'setMiddlewareGroups',
        'parameters' => 
        array (
          'groups' => 
          array (
            'name' => 'groups',
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
            'startLine' => 619,
            'endLine' => 619,
            'startColumn' => 41,
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
 * Set the application\'s middleware groups.
 *
 * @param  array  $groups
 * @return $this
 */',
        'startLine' => 619,
        'endLine' => 626,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'getRouteMiddleware' => 
      array (
        'name' => 'getRouteMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s route middleware aliases.
 *
 * @return array
 *
 * @deprecated
 */',
        'startLine' => 635,
        'endLine' => 638,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'getMiddlewareAliases' => 
      array (
        'name' => 'getMiddlewareAliases',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s route middleware aliases.
 *
 * @return array
 */',
        'startLine' => 645,
        'endLine' => 648,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'setMiddlewareAliases' => 
      array (
        'name' => 'setMiddlewareAliases',
        'parameters' => 
        array (
          'aliases' => 
          array (
            'name' => 'aliases',
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
            'startLine' => 656,
            'endLine' => 656,
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
 * Set the application\'s route middleware aliases.
 *
 * @param  array  $aliases
 * @return $this
 */',
        'startLine' => 656,
        'endLine' => 663,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'setMiddlewarePriority' => 
      array (
        'name' => 'setMiddlewarePriority',
        'parameters' => 
        array (
          'priority' => 
          array (
            'name' => 'priority',
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
            'startLine' => 671,
            'endLine' => 671,
            'startColumn' => 43,
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
 * Set the application\'s middleware priority.
 *
 * @param  array  $priority
 * @return $this
 */',
        'startLine' => 671,
        'endLine' => 678,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'getApplication' => 
      array (
        'name' => 'getApplication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the Laravel application instance.
 *
 * @return \\Illuminate\\Contracts\\Foundation\\Application
 */',
        'startLine' => 685,
        'endLine' => 688,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'aliasName' => NULL,
      ),
      'setApplication' => 
      array (
        'name' => 'setApplication',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Foundation\\Application',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 696,
            'endLine' => 696,
            'startColumn' => 36,
            'endColumn' => 51,
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
 * Set the Laravel application instance.
 *
 * @param  \\Illuminate\\Contracts\\Foundation\\Application  $app
 * @return $this
 */',
        'startLine' => 696,
        'endLine' => 701,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Kernel',
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