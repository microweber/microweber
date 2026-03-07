<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Application.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Application
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0ce841d92b952e64f73cf1aa49779de6ced8a34d7ad082465df98762575490be-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Application',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Application.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation',
    'name' => 'Illuminate\\Foundation\\Application',
    'shortName' => 'Application',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 1719,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Container\\Container',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Foundation\\Application',
      1 => 'Illuminate\\Contracts\\Foundation\\CachesConfiguration',
      2 => 'Illuminate\\Contracts\\Foundation\\CachesRoutes',
      3 => 'Symfony\\Component\\HttpKernel\\HttpKernelInterface',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
      'VERSION' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'11.48.0\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 229,
            'startFilePos' => 1796,
            'endTokenPos' => 229,
            'endFilePos' => 1804,
          ),
        ),
        'docComment' => '/**
 * The Laravel framework version.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 30,
      ),
    ),
    'immediateProperties' => 
    array (
      'basePath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'basePath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The base path for the Laravel installation.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'registeredCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'registeredCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 247,
            'startFilePos' => 2052,
            'endTokenPos' => 248,
            'endFilePos' => 2053,
          ),
        ),
        'docComment' => '/**
 * The array of registered callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hasBeenBootstrapped' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'hasBeenBootstrapped',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 259,
            'startFilePos' => 2200,
            'endTokenPos' => 259,
            'endFilePos' => 2204,
          ),
        ),
        'docComment' => '/**
 * Indicates if the application has been bootstrapped before.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'booted' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'booted',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 76,
            'startTokenPos' => 270,
            'startFilePos' => 2322,
            'endTokenPos' => 270,
            'endFilePos' => 2326,
          ),
        ),
        'docComment' => '/**
 * Indicates if the application has "booted".
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootingCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'bootingCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 281,
            'startFilePos' => 2449,
            'endTokenPos' => 282,
            'endFilePos' => 2450,
          ),
        ),
        'docComment' => '/**
 * The array of booting callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootedCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'bootedCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 90,
            'endLine' => 90,
            'startTokenPos' => 293,
            'startFilePos' => 2571,
            'endTokenPos' => 294,
            'endFilePos' => 2572,
          ),
        ),
        'docComment' => '/**
 * The array of booted callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'terminatingCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'terminatingCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 305,
            'startFilePos' => 2703,
            'endTokenPos' => 306,
            'endFilePos' => 2704,
          ),
        ),
        'docComment' => '/**
 * The array of terminating callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'serviceProviders' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'serviceProviders',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 104,
            'endLine' => 104,
            'startTokenPos' => 317,
            'startFilePos' => 2876,
            'endTokenPos' => 318,
            'endFilePos' => 2877,
          ),
        ),
        'docComment' => '/**
 * All of the registered service providers.
 *
 * @var array<string, \\Illuminate\\Support\\ServiceProvider>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'loadedProviders' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'loadedProviders',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 111,
            'endLine' => 111,
            'startTokenPos' => 329,
            'startFilePos' => 3005,
            'endTokenPos' => 330,
            'endFilePos' => 3006,
          ),
        ),
        'docComment' => '/**
 * The names of the loaded service providers.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'deferredServices' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'deferredServices',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 118,
            'startTokenPos' => 341,
            'startFilePos' => 3135,
            'endTokenPos' => 342,
            'endFilePos' => 3136,
          ),
        ),
        'docComment' => '/**
 * The deferred services and their providers.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootstrapPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'bootstrapPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom bootstrap path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'appPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'appPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom application path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 132,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'configPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'configPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom configuration path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 139,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'databasePath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'databasePath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom database path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 146,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'langPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'langPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom language file path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 153,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'publicPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'publicPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom public / web path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 160,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'storagePath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'storagePath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom storage path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 167,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'environmentPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'environmentPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom environment path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 174,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'environmentFile' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'environmentFile',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'.env\'',
          'attributes' => 
          array (
            'startLine' => 181,
            'endLine' => 181,
            'startTokenPos' => 409,
            'startFilePos' => 4323,
            'endTokenPos' => 409,
            'endFilePos' => 4328,
          ),
        ),
        'docComment' => '/**
 * The environment file to load during bootstrapping.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 181,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isRunningInConsole' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'isRunningInConsole',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates if the application is running in the console.
 *
 * @var bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 188,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'namespace' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'namespace',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The application namespace.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 195,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mergeFrameworkConfiguration' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'mergeFrameworkConfiguration',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 202,
            'endLine' => 202,
            'startTokenPos' => 434,
            'startFilePos' => 4737,
            'endTokenPos' => 434,
            'endFilePos' => 4740,
          ),
        ),
        'docComment' => '/**
 * Indicates if the framework\'s base configuration should be merged.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 202,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 50,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'absoluteCachePathPrefixes' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'absoluteCachePathPrefixes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'/\', \'\\\\\']',
          'attributes' => 
          array (
            'startLine' => 209,
            'endLine' => 209,
            'startTokenPos' => 445,
            'startFilePos' => 4905,
            'endTokenPos' => 450,
            'endFilePos' => 4915,
          ),
        ),
        'docComment' => '/**
 * The prefixes of absolute cache paths for use during normalization.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 209,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 55,
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
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 217,
                'endLine' => 217,
                'startTokenPos' => 465,
                'startFilePos' => 5097,
                'endTokenPos' => 465,
                'endFilePos' => 5100,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 217,
            'endLine' => 217,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Create a new Illuminate application instance.
 *
 * @param  string|null  $basePath
 * @return void
 */',
        'startLine' => 217,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'configure' => 
      array (
        'name' => 'configure',
        'parameters' => 
        array (
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 235,
                'endLine' => 235,
                'startTokenPos' => 535,
                'startFilePos' => 5625,
                'endTokenPos' => 535,
                'endFilePos' => 5628,
              ),
            ),
            'type' => 
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
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 235,
            'endLine' => 235,
            'startColumn' => 38,
            'endColumn' => 61,
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
 * Begin configuring a new Laravel application instance.
 *
 * @param  string|null  $basePath
 * @return \\Illuminate\\Foundation\\Configuration\\ApplicationBuilder
 */',
        'startLine' => 235,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'inferBasePath' => 
      array (
        'name' => 'inferBasePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Infer the application\'s base directory from the environment.
 *
 * @return string
 */',
        'startLine' => 254,
        'endLine' => 263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'version' => 
      array (
        'name' => 'version',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the version number of the application.
 *
 * @return string
 */',
        'startLine' => 270,
        'endLine' => 273,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerBaseBindings' => 
      array (
        'name' => 'registerBaseBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the basic bindings into the container.
 *
 * @return void
 */',
        'startLine' => 280,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerBaseServiceProviders' => 
      array (
        'name' => 'registerBaseServiceProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register all of the base service providers.
 *
 * @return void
 */',
        'startLine' => 299,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerLaravelCloudServices' => 
      array (
        'name' => 'registerLaravelCloudServices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register any services needed for Laravel Cloud.
 *
 * @return void
 */',
        'startLine' => 312,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bootstrapWith' => 
      array (
        'name' => 'bootstrapWith',
        'parameters' => 
        array (
          'bootstrappers' => 
          array (
            'name' => 'bootstrappers',
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 35,
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
 * Run the given array of bootstrap classes.
 *
 * @param  string[]  $bootstrappers
 * @return void
 */',
        'startLine' => 335,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'afterLoadingEnvironment' => 
      array (
        'name' => 'afterLoadingEnvironment',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 354,
            'endLine' => 354,
            'startColumn' => 45,
            'endColumn' => 61,
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
 * Register a callback to run after loading the environment.
 *
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 354,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'beforeBootstrapping' => 
      array (
        'name' => 'beforeBootstrapping',
        'parameters' => 
        array (
          'bootstrapper' => 
          array (
            'name' => 'bootstrapper',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 368,
            'endLine' => 368,
            'startColumn' => 41,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 368,
            'endLine' => 368,
            'startColumn' => 56,
            'endColumn' => 72,
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
 * Register a callback to run before a bootstrapper.
 *
 * @param  string  $bootstrapper
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 368,
        'endLine' => 371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'afterBootstrapping' => 
      array (
        'name' => 'afterBootstrapping',
        'parameters' => 
        array (
          'bootstrapper' => 
          array (
            'name' => 'bootstrapper',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 40,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 380,
            'endLine' => 380,
            'startColumn' => 55,
            'endColumn' => 71,
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
 * Register a callback to run after a bootstrapper.
 *
 * @param  string  $bootstrapper
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 380,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'hasBeenBootstrapped' => 
      array (
        'name' => 'hasBeenBootstrapped',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application has been bootstrapped before.
 *
 * @return bool
 */',
        'startLine' => 390,
        'endLine' => 393,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setBasePath' => 
      array (
        'name' => 'setBasePath',
        'parameters' => 
        array (
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 401,
            'endLine' => 401,
            'startColumn' => 33,
            'endColumn' => 41,
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
 * Set the base path for the application.
 *
 * @param  string  $basePath
 * @return $this
 */',
        'startLine' => 401,
        'endLine' => 408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bindPathsInContainer' => 
      array (
        'name' => 'bindPathsInContainer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Bind all of the application paths in the container.
 *
 * @return void
 */',
        'startLine' => 415,
        'endLine' => 436,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'path' => 
      array (
        'name' => 'path',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 444,
                'endLine' => 444,
                'startTokenPos' => 1488,
                'startFilePos' => 11348,
                'endTokenPos' => 1488,
                'endFilePos' => 11349,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 444,
            'endLine' => 444,
            'startColumn' => 26,
            'endColumn' => 35,
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
 * Get the path to the application "app" directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 444,
        'endLine' => 447,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useAppPath' => 
      array (
        'name' => 'useAppPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 455,
            'endLine' => 455,
            'startColumn' => 32,
            'endColumn' => 36,
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
 * Set the application directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 455,
        'endLine' => 462,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'basePath' => 
      array (
        'name' => 'basePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 470,
                'endLine' => 470,
                'startTokenPos' => 1572,
                'startFilePos' => 11870,
                'endTokenPos' => 1572,
                'endFilePos' => 11871,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 470,
            'endLine' => 470,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Get the base path of the Laravel installation.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 470,
        'endLine' => 473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bootstrapPath' => 
      array (
        'name' => 'bootstrapPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 481,
                'endLine' => 481,
                'startTokenPos' => 1606,
                'startFilePos' => 12108,
                'endTokenPos' => 1606,
                'endFilePos' => 12109,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 35,
            'endColumn' => 44,
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
 * Get the path to the bootstrap directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 481,
        'endLine' => 484,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getBootstrapProvidersPath' => 
      array (
        'name' => 'getBootstrapProvidersPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the service provider list in the bootstrap directory.
 *
 * @return string
 */',
        'startLine' => 491,
        'endLine' => 494,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useBootstrapPath' => 
      array (
        'name' => 'useBootstrapPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 38,
            'endColumn' => 42,
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
 * Set the bootstrap file directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 502,
        'endLine' => 509,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'configPath' => 
      array (
        'name' => 'configPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 517,
                'endLine' => 517,
                'startTokenPos' => 1704,
                'startFilePos' => 12880,
                'endTokenPos' => 1704,
                'endFilePos' => 12881,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 517,
            'endLine' => 517,
            'startColumn' => 32,
            'endColumn' => 41,
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
 * Get the path to the application configuration files.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 517,
        'endLine' => 520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useConfigPath' => 
      array (
        'name' => 'useConfigPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 528,
            'endLine' => 528,
            'startColumn' => 35,
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
 * Set the configuration directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 528,
        'endLine' => 535,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'databasePath' => 
      array (
        'name' => 'databasePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 543,
                'endLine' => 543,
                'startTokenPos' => 1788,
                'startFilePos' => 13420,
                'endTokenPos' => 1788,
                'endFilePos' => 13421,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 543,
            'endLine' => 543,
            'startColumn' => 34,
            'endColumn' => 43,
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
 * Get the path to the database directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 543,
        'endLine' => 546,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useDatabasePath' => 
      array (
        'name' => 'useDatabasePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 554,
            'endLine' => 554,
            'startColumn' => 37,
            'endColumn' => 41,
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
 * Set the database directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 554,
        'endLine' => 561,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'langPath' => 
      array (
        'name' => 'langPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 569,
                'endLine' => 569,
                'startTokenPos' => 1872,
                'startFilePos' => 13957,
                'endTokenPos' => 1872,
                'endFilePos' => 13958,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 569,
            'endLine' => 569,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Get the path to the language files.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 569,
        'endLine' => 572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useLangPath' => 
      array (
        'name' => 'useLangPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 580,
            'endLine' => 580,
            'startColumn' => 33,
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
 * Set the language file directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 580,
        'endLine' => 587,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'publicPath' => 
      array (
        'name' => 'publicPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 595,
                'endLine' => 595,
                'startTokenPos' => 1946,
                'startFilePos' => 14462,
                'endTokenPos' => 1946,
                'endFilePos' => 14463,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 595,
            'endLine' => 595,
            'startColumn' => 32,
            'endColumn' => 41,
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
 * Get the path to the public / web directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 595,
        'endLine' => 598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'usePublicPath' => 
      array (
        'name' => 'usePublicPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 35,
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
 * Set the public / web directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 606,
        'endLine' => 613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'storagePath' => 
      array (
        'name' => 'storagePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 621,
                'endLine' => 621,
                'startTokenPos' => 2030,
                'startFilePos' => 14999,
                'endTokenPos' => 2030,
                'endFilePos' => 15000,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 621,
            'endLine' => 621,
            'startColumn' => 33,
            'endColumn' => 42,
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
 * Get the path to the storage directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 621,
        'endLine' => 632,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useStoragePath' => 
      array (
        'name' => 'useStoragePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 640,
            'endLine' => 640,
            'startColumn' => 36,
            'endColumn' => 40,
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
 * Set the storage directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 640,
        'endLine' => 647,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'resourcePath' => 
      array (
        'name' => 'resourcePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 655,
                'endLine' => 655,
                'startTokenPos' => 2192,
                'startFilePos' => 15865,
                'endTokenPos' => 2192,
                'endFilePos' => 15866,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 655,
            'endLine' => 655,
            'startColumn' => 34,
            'endColumn' => 43,
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
 * Get the path to the resources directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 655,
        'endLine' => 658,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'viewPath' => 
      array (
        'name' => 'viewPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 668,
                'endLine' => 668,
                'startTokenPos' => 2229,
                'startFilePos' => 16195,
                'endTokenPos' => 2229,
                'endFilePos' => 16196,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 668,
            'endLine' => 668,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Get the path to the views directory.
 *
 * This method returns the first configured path in the array of view paths.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 668,
        'endLine' => 673,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'joinPaths' => 
      array (
        'name' => 'joinPaths',
        'parameters' => 
        array (
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 682,
            'endLine' => 682,
            'startColumn' => 31,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 682,
                'endLine' => 682,
                'startTokenPos' => 2288,
                'startFilePos' => 16545,
                'endTokenPos' => 2288,
                'endFilePos' => 16546,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 682,
            'endLine' => 682,
            'startColumn' => 42,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Join the given paths together.
 *
 * @param  string  $basePath
 * @param  string  $path
 * @return string
 */',
        'startLine' => 682,
        'endLine' => 685,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environmentPath' => 
      array (
        'name' => 'environmentPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the environment file directory.
 *
 * @return string
 */',
        'startLine' => 692,
        'endLine' => 695,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useEnvironmentPath' => 
      array (
        'name' => 'useEnvironmentPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 703,
            'endLine' => 703,
            'startColumn' => 40,
            'endColumn' => 44,
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
 * Set the directory for the environment file.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 703,
        'endLine' => 708,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadEnvironmentFrom' => 
      array (
        'name' => 'loadEnvironmentFrom',
        'parameters' => 
        array (
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 716,
            'endLine' => 716,
            'startColumn' => 41,
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
 * Set the environment file to be loaded during bootstrapping.
 *
 * @param  string  $file
 * @return $this
 */',
        'startLine' => 716,
        'endLine' => 721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environmentFile' => 
      array (
        'name' => 'environmentFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the environment file the application is using.
 *
 * @return string
 */',
        'startLine' => 728,
        'endLine' => 731,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environmentFilePath' => 
      array (
        'name' => 'environmentFilePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the fully qualified path to the environment file.
 *
 * @return string
 */',
        'startLine' => 738,
        'endLine' => 741,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environment' => 
      array (
        'name' => 'environment',
        'parameters' => 
        array (
          'environments' => 
          array (
            'name' => 'environments',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 749,
            'endLine' => 749,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Get or check the current application environment.
 *
 * @param  string|array  ...$environments
 * @return string|bool
 */',
        'startLine' => 749,
        'endLine' => 758,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isLocal' => 
      array (
        'name' => 'isLocal',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is in the local environment.
 *
 * @return bool
 */',
        'startLine' => 765,
        'endLine' => 768,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isProduction' => 
      array (
        'name' => 'isProduction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is in the production environment.
 *
 * @return bool
 */',
        'startLine' => 775,
        'endLine' => 778,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'detectEnvironment' => 
      array (
        'name' => 'detectEnvironment',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 786,
            'endLine' => 786,
            'startColumn' => 39,
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
 * Detect the application\'s current environment.
 *
 * @param  \\Closure  $callback
 * @return string
 */',
        'startLine' => 786,
        'endLine' => 793,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'runningInConsole' => 
      array (
        'name' => 'runningInConsole',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is running in the console.
 *
 * @return bool
 */',
        'startLine' => 800,
        'endLine' => 807,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'runningConsoleCommand' => 
      array (
        'name' => 'runningConsoleCommand',
        'parameters' => 
        array (
          'commands' => 
          array (
            'name' => 'commands',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 815,
            'endLine' => 815,
            'startColumn' => 43,
            'endColumn' => 54,
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
 * Determine if the application is running any of the given console commands.
 *
 * @param  string|array  ...$commands
 * @return bool
 */',
        'startLine' => 815,
        'endLine' => 825,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'runningUnitTests' => 
      array (
        'name' => 'runningUnitTests',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is running unit tests.
 *
 * @return bool
 */',
        'startLine' => 832,
        'endLine' => 835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'hasDebugModeEnabled' => 
      array (
        'name' => 'hasDebugModeEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is running with debug mode enabled.
 *
 * @return bool
 */',
        'startLine' => 842,
        'endLine' => 845,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registered' => 
      array (
        'name' => 'registered',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 853,
            'endLine' => 853,
            'startColumn' => 32,
            'endColumn' => 40,
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
 * Register a new registered listener.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 853,
        'endLine' => 856,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerConfiguredProviders' => 
      array (
        'name' => 'registerConfiguredProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register all of the configured providers.
 *
 * @return void
 */',
        'startLine' => 863,
        'endLine' => 874,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'register' => 
      array (
        'name' => 'register',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 883,
            'endLine' => 883,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'force' => 
          array (
            'name' => 'force',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 883,
                'endLine' => 883,
                'startTokenPos' => 3037,
                'startFilePos' => 21429,
                'endTokenPos' => 3037,
                'endFilePos' => 21433,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 883,
            'endLine' => 883,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register a service provider with the application.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider|string  $provider
 * @param  bool  $force
 * @return \\Illuminate\\Support\\ServiceProvider
 */',
        'startLine' => 883,
        'endLine' => 925,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getProvider' => 
      array (
        'name' => 'getProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 933,
            'endLine' => 933,
            'startColumn' => 33,
            'endColumn' => 41,
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
 * Get the registered service provider instance if it exists.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider|string  $provider
 * @return \\Illuminate\\Support\\ServiceProvider|null
 */',
        'startLine' => 933,
        'endLine' => 938,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getProviders' => 
      array (
        'name' => 'getProviders',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 946,
            'endLine' => 946,
            'startColumn' => 34,
            'endColumn' => 42,
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
 * Get the registered service provider instances if any exist.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider|string  $provider
 * @return array
 */',
        'startLine' => 946,
        'endLine' => 951,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'resolveProvider' => 
      array (
        'name' => 'resolveProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 959,
            'endLine' => 959,
            'startColumn' => 37,
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
 * Resolve a service provider instance from the class name.
 *
 * @param  string  $provider
 * @return \\Illuminate\\Support\\ServiceProvider
 */',
        'startLine' => 959,
        'endLine' => 962,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'markAsRegistered' => 
      array (
        'name' => 'markAsRegistered',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 970,
            'endLine' => 970,
            'startColumn' => 41,
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
 * Mark the given provider as registered.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider  $provider
 * @return void
 */',
        'startLine' => 970,
        'endLine' => 977,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadDeferredProviders' => 
      array (
        'name' => 'loadDeferredProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Load and boot all of the remaining deferred providers.
 *
 * @return void
 */',
        'startLine' => 984,
        'endLine' => 994,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadDeferredProvider' => 
      array (
        'name' => 'loadDeferredProvider',
        'parameters' => 
        array (
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1002,
            'endLine' => 1002,
            'startColumn' => 42,
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
 * Load the provider for a deferred service.
 *
 * @param  string  $service
 * @return void
 */',
        'startLine' => 1002,
        'endLine' => 1016,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerDeferredProvider' => 
      array (
        'name' => 'registerDeferredProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1025,
            'endLine' => 1025,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'service' => 
          array (
            'name' => 'service',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1025,
                'endLine' => 1025,
                'startTokenPos' => 3617,
                'startFilePos' => 25966,
                'endTokenPos' => 3617,
                'endFilePos' => 25969,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1025,
            'endLine' => 1025,
            'startColumn' => 57,
            'endColumn' => 71,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register a deferred provider and service.
 *
 * @param  string  $provider
 * @param  string|null  $service
 * @return void
 */',
        'startLine' => 1025,
        'endLine' => 1041,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1054,
            'endLine' => 1054,
            'startColumn' => 26,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1054,
                'endLine' => 1054,
                'startTokenPos' => 3730,
                'startFilePos' => 26960,
                'endTokenPos' => 3731,
                'endFilePos' => 26961,
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
            'startLine' => 1054,
            'endLine' => 1054,
            'startColumn' => 37,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolve the given type from the container.
 *
 * @template TClass of object
 *
 * @param  string|class-string<TClass>  $abstract
 * @param  array  $parameters
 * @return ($abstract is class-string<TClass> ? TClass : mixed)
 *
 * @throws \\Illuminate\\Contracts\\Container\\BindingResolutionException
 */',
        'startLine' => 1054,
        'endLine' => 1059,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'resolve' => 
      array (
        'name' => 'resolve',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1074,
            'endLine' => 1074,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1074,
                'endLine' => 1074,
                'startTokenPos' => 3783,
                'startFilePos' => 27641,
                'endTokenPos' => 3784,
                'endFilePos' => 27642,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1074,
            'endLine' => 1074,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'raiseEvents' => 
          array (
            'name' => 'raiseEvents',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1074,
                'endLine' => 1074,
                'startTokenPos' => 3791,
                'startFilePos' => 27660,
                'endTokenPos' => 3791,
                'endFilePos' => 27663,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1074,
            'endLine' => 1074,
            'startColumn' => 61,
            'endColumn' => 79,
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
 * Resolve the given type from the container.
 *
 * @template TClass of object
 *
 * @param  string|class-string<TClass>|callable  $abstract
 * @param  array  $parameters
 * @param  bool  $raiseEvents
 * @return ($abstract is class-string<TClass> ? TClass : mixed)
 *
 * @throws \\Illuminate\\Contracts\\Container\\BindingResolutionException
 * @throws \\Illuminate\\Contracts\\Container\\CircularDependencyException
 */',
        'startLine' => 1074,
        'endLine' => 1079,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadDeferredProviderIfNeeded' => 
      array (
        'name' => 'loadDeferredProviderIfNeeded',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1087,
            'endLine' => 1087,
            'startColumn' => 53,
            'endColumn' => 61,
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
 * Load the deferred provider if the given type is a deferred service and the instance has not been loaded.
 *
 * @param  string  $abstract
 * @return void
 */',
        'startLine' => 1087,
        'endLine' => 1092,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bound' => 
      array (
        'name' => 'bound',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1100,
            'endLine' => 1100,
            'startColumn' => 27,
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
 * Determine if the given abstract type has been bound.
 *
 * @param  string  $abstract
 * @return bool
 */',
        'startLine' => 1100,
        'endLine' => 1103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isBooted' => 
      array (
        'name' => 'isBooted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application has booted.
 *
 * @return bool
 */',
        'startLine' => 1110,
        'endLine' => 1113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Boot the application\'s service providers.
 *
 * @return void
 */',
        'startLine' => 1120,
        'endLine' => 1138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bootProvider' => 
      array (
        'name' => 'bootProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Support\\ServiceProvider',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1146,
            'endLine' => 1146,
            'startColumn' => 37,
            'endColumn' => 61,
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
 * Boot the given service provider.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider  $provider
 * @return void
 */',
        'startLine' => 1146,
        'endLine' => 1155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'booting' => 
      array (
        'name' => 'booting',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1163,
            'endLine' => 1163,
            'startColumn' => 29,
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
 * Register a new boot listener.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 1163,
        'endLine' => 1166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1174,
            'endLine' => 1174,
            'startColumn' => 28,
            'endColumn' => 36,
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
 * Register a new "booted" listener.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 1174,
        'endLine' => 1181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'fireAppCallbacks' => 
      array (
        'name' => 'fireAppCallbacks',
        'parameters' => 
        array (
          'callbacks' => 
          array (
            'name' => 'callbacks',
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
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1189,
            'endLine' => 1189,
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
 * Call the booting callbacks for the application.
 *
 * @param  callable[]  $callbacks
 * @return void
 */',
        'startLine' => 1189,
        'endLine' => 1198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
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
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HttpFoundation\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1205,
            'endLine' => 1205,
            'startColumn' => 28,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'self::MAIN_REQUEST',
              'attributes' => 
              array (
                'startLine' => 1205,
                'endLine' => 1205,
                'startTokenPos' => 4237,
                'startFilePos' => 30811,
                'endTokenPos' => 4239,
                'endFilePos' => 30828,
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
            'startLine' => 1205,
            'endLine' => 1205,
            'startColumn' => 53,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'catch' => 
          array (
            'name' => 'catch',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1205,
                'endLine' => 1205,
                'startTokenPos' => 4248,
                'startFilePos' => 30845,
                'endTokenPos' => 4248,
                'endFilePos' => 30848,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1205,
            'endLine' => 1205,
            'startColumn' => 85,
            'endColumn' => 102,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\HttpFoundation\\Response',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * {@inheritdoc}
 *
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 1205,
        'endLine' => 1208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'handleRequest' => 
      array (
        'name' => 'handleRequest',
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
            'startLine' => 1216,
            'endLine' => 1216,
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
 * Handle the incoming HTTP request and send the response to the browser.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return void
 */',
        'startLine' => 1216,
        'endLine' => 1223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'handleCommand' => 
      array (
        'name' => 'handleCommand',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Console\\Input\\InputInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1231,
            'endLine' => 1231,
            'startColumn' => 35,
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
 * Handle the incoming Artisan command.
 *
 * @param  \\Symfony\\Component\\Console\\Input\\InputInterface  $input
 * @return int
 */',
        'startLine' => 1231,
        'endLine' => 1243,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'shouldMergeFrameworkConfiguration' => 
      array (
        'name' => 'shouldMergeFrameworkConfiguration',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the framework\'s base configuration should be merged.
 *
 * @return bool
 */',
        'startLine' => 1250,
        'endLine' => 1253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'dontMergeFrameworkConfiguration' => 
      array (
        'name' => 'dontMergeFrameworkConfiguration',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the framework\'s base configuration should not be merged.
 *
 * @return $this
 */',
        'startLine' => 1260,
        'endLine' => 1265,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'shouldSkipMiddleware' => 
      array (
        'name' => 'shouldSkipMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if middleware has been disabled for the application.
 *
 * @return bool
 */',
        'startLine' => 1272,
        'endLine' => 1276,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedServicesPath' => 
      array (
        'name' => 'getCachedServicesPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the cached services.php file.
 *
 * @return string
 */',
        'startLine' => 1283,
        'endLine' => 1286,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedPackagesPath' => 
      array (
        'name' => 'getCachedPackagesPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the cached packages.php file.
 *
 * @return string
 */',
        'startLine' => 1293,
        'endLine' => 1296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'configurationIsCached' => 
      array (
        'name' => 'configurationIsCached',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application configuration is cached.
 *
 * @return bool
 */',
        'startLine' => 1303,
        'endLine' => 1306,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedConfigPath' => 
      array (
        'name' => 'getCachedConfigPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the configuration cache file.
 *
 * @return string
 */',
        'startLine' => 1313,
        'endLine' => 1316,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'routesAreCached' => 
      array (
        'name' => 'routesAreCached',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application routes are cached.
 *
 * @return bool
 */',
        'startLine' => 1323,
        'endLine' => 1326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedRoutesPath' => 
      array (
        'name' => 'getCachedRoutesPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the routes cache file.
 *
 * @return string
 */',
        'startLine' => 1333,
        'endLine' => 1336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'eventsAreCached' => 
      array (
        'name' => 'eventsAreCached',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application events are cached.
 *
 * @return bool
 */',
        'startLine' => 1343,
        'endLine' => 1346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedEventsPath' => 
      array (
        'name' => 'getCachedEventsPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the events cache file.
 *
 * @return string
 */',
        'startLine' => 1353,
        'endLine' => 1356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'normalizeCachePath' => 
      array (
        'name' => 'normalizeCachePath',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1365,
            'endLine' => 1365,
            'startColumn' => 43,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1365,
            'endLine' => 1365,
            'startColumn' => 49,
            'endColumn' => 56,
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
 * Normalize a relative or absolute path to a cache file.
 *
 * @param  string  $key
 * @param  string  $default
 * @return string
 */',
        'startLine' => 1365,
        'endLine' => 1374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'addAbsoluteCachePathPrefix' => 
      array (
        'name' => 'addAbsoluteCachePathPrefix',
        'parameters' => 
        array (
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1382,
            'endLine' => 1382,
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
 * Add new prefix to list of absolute path prefixes.
 *
 * @param  string  $prefix
 * @return $this
 */',
        'startLine' => 1382,
        'endLine' => 1387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'maintenanceMode' => 
      array (
        'name' => 'maintenanceMode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an instance of the maintenance mode manager implementation.
 *
 * @return \\Illuminate\\Contracts\\Foundation\\MaintenanceMode
 */',
        'startLine' => 1394,
        'endLine' => 1397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isDownForMaintenance' => 
      array (
        'name' => 'isDownForMaintenance',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is currently down for maintenance.
 *
 * @return bool
 */',
        'startLine' => 1404,
        'endLine' => 1407,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'abort' => 
      array (
        'name' => 'abort',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1420,
            'endLine' => 1420,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 1420,
                'endLine' => 1420,
                'startTokenPos' => 4888,
                'startFilePos' => 36067,
                'endTokenPos' => 4888,
                'endFilePos' => 36068,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1420,
            'endLine' => 1420,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'headers' => 
          array (
            'name' => 'headers',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1420,
                'endLine' => 1420,
                'startTokenPos' => 4897,
                'startFilePos' => 36088,
                'endTokenPos' => 4898,
                'endFilePos' => 36089,
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
            'startLine' => 1420,
            'endLine' => 1420,
            'startColumn' => 49,
            'endColumn' => 67,
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
 * Throw an HttpException with the given data.
 *
 * @param  int  $code
 * @param  string  $message
 * @param  array  $headers
 * @return never
 *
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\HttpException
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException
 */',
        'startLine' => 1420,
        'endLine' => 1427,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'terminating' => 
      array (
        'name' => 'terminating',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1435,
            'endLine' => 1435,
            'startColumn' => 33,
            'endColumn' => 41,
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
 * Register a terminating callback with the application.
 *
 * @param  callable|string  $callback
 * @return $this
 */',
        'startLine' => 1435,
        'endLine' => 1440,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'terminate' => 
      array (
        'name' => 'terminate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Terminate the application.
 *
 * @return void
 */',
        'startLine' => 1447,
        'endLine' => 1456,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getLoadedProviders' => 
      array (
        'name' => 'getLoadedProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the service providers that have been loaded.
 *
 * @return array<string, bool>
 */',
        'startLine' => 1463,
        'endLine' => 1466,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'providerIsLoaded' => 
      array (
        'name' => 'providerIsLoaded',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
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
            'startLine' => 1474,
            'endLine' => 1474,
            'startColumn' => 38,
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
 * Determine if the given service provider is loaded.
 *
 * @param  string  $provider
 * @return bool
 */',
        'startLine' => 1474,
        'endLine' => 1477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getDeferredServices' => 
      array (
        'name' => 'getDeferredServices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s deferred services.
 *
 * @return array
 */',
        'startLine' => 1484,
        'endLine' => 1487,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setDeferredServices' => 
      array (
        'name' => 'setDeferredServices',
        'parameters' => 
        array (
          'services' => 
          array (
            'name' => 'services',
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
            'startLine' => 1495,
            'endLine' => 1495,
            'startColumn' => 41,
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
 * Set the application\'s deferred services.
 *
 * @param  array  $services
 * @return void
 */',
        'startLine' => 1495,
        'endLine' => 1498,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isDeferredService' => 
      array (
        'name' => 'isDeferredService',
        'parameters' => 
        array (
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1506,
            'endLine' => 1506,
            'startColumn' => 39,
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
 * Determine if the given service is a deferred service.
 *
 * @param  string  $service
 * @return bool
 */',
        'startLine' => 1506,
        'endLine' => 1509,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'addDeferredServices' => 
      array (
        'name' => 'addDeferredServices',
        'parameters' => 
        array (
          'services' => 
          array (
            'name' => 'services',
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
            'startLine' => 1517,
            'endLine' => 1517,
            'startColumn' => 41,
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
 * Add an array of services to the application\'s deferred services.
 *
 * @param  array  $services
 * @return void
 */',
        'startLine' => 1517,
        'endLine' => 1520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'removeDeferredServices' => 
      array (
        'name' => 'removeDeferredServices',
        'parameters' => 
        array (
          'services' => 
          array (
            'name' => 'services',
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
            'startLine' => 1528,
            'endLine' => 1528,
            'startColumn' => 44,
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
 * Remove an array of services from the application\'s deferred services.
 *
 * @param  array  $services
 * @return void
 */',
        'startLine' => 1528,
        'endLine' => 1533,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'provideFacades' => 
      array (
        'name' => 'provideFacades',
        'parameters' => 
        array (
          'namespace' => 
          array (
            'name' => 'namespace',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1541,
            'endLine' => 1541,
            'startColumn' => 36,
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
 * Configure the real-time facade namespace.
 *
 * @param  string  $namespace
 * @return void
 */',
        'startLine' => 1541,
        'endLine' => 1544,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getLocale' => 
      array (
        'name' => 'getLocale',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application locale.
 *
 * @return string
 */',
        'startLine' => 1551,
        'endLine' => 1554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'currentLocale' => 
      array (
        'name' => 'currentLocale',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application locale.
 *
 * @return string
 */',
        'startLine' => 1561,
        'endLine' => 1564,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getFallbackLocale' => 
      array (
        'name' => 'getFallbackLocale',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application fallback locale.
 *
 * @return string
 */',
        'startLine' => 1571,
        'endLine' => 1574,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setLocale' => 
      array (
        'name' => 'setLocale',
        'parameters' => 
        array (
          'locale' => 
          array (
            'name' => 'locale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1582,
            'endLine' => 1582,
            'startColumn' => 31,
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
 * Set the current application locale.
 *
 * @param  string  $locale
 * @return void
 */',
        'startLine' => 1582,
        'endLine' => 1589,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setFallbackLocale' => 
      array (
        'name' => 'setFallbackLocale',
        'parameters' => 
        array (
          'fallbackLocale' => 
          array (
            'name' => 'fallbackLocale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1597,
            'endLine' => 1597,
            'startColumn' => 39,
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
 * Set the current application fallback locale.
 *
 * @param  string  $fallbackLocale
 * @return void
 */',
        'startLine' => 1597,
        'endLine' => 1602,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isLocale' => 
      array (
        'name' => 'isLocale',
        'parameters' => 
        array (
          'locale' => 
          array (
            'name' => 'locale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1610,
            'endLine' => 1610,
            'startColumn' => 30,
            'endColumn' => 36,
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
 * Determine if the application locale is the given locale.
 *
 * @param  string  $locale
 * @return bool
 */',
        'startLine' => 1610,
        'endLine' => 1613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerCoreContainerAliases' => 
      array (
        'name' => 'registerCoreContainerAliases',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the core class aliases in the container.
 *
 * @return void
 */',
        'startLine' => 1620,
        'endLine' => 1667,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'flush' => 
      array (
        'name' => 'flush',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the container of all bindings and resolved instances.
 *
 * @return void
 */',
        'startLine' => 1674,
        'endLine' => 1692,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getNamespace' => 
      array (
        'name' => 'getNamespace',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application namespace.
 *
 * @return string
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 1701,
        'endLine' => 1718,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
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