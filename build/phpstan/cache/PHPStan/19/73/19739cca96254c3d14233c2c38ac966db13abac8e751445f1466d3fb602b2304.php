<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Facade.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Facade
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e8bf4df0bf178cfec6da9468872f75d4f3a39d82e830e55866eb1f2ab5f9f640-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Facade',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Facade.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Facade',
    'shortName' => 'Facade',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 363,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
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
      'app' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'name' => 'app',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The application instance being facaded.
 *
 * @var \\Illuminate\\Contracts\\Foundation\\Application|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'resolvedInstance' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'name' => 'resolvedInstance',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The resolved object instances.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cached' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'name' => 'cached',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 103,
            'startFilePos' => 823,
            'endTokenPos' => 103,
            'endFilePos' => 826,
          ),
        ),
        'docComment' => '/**
 * Indicates if the resolved instance should be cached.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 36,
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
      'resolved' => 
      array (
        'name' => 'resolved',
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
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 37,
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
 * Run a Closure when the facade has been resolved.
 *
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 47,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'spy' => 
      array (
        'name' => 'spy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert the facade into a Mockery spy.
 *
 * @return \\Mockery\\MockInterface
 */',
        'startLine' => 65,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'partialMock' => 
      array (
        'name' => 'partialMock',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initiate a partial mock on the facade.
 *
 * @return \\Mockery\\MockInterface
 */',
        'startLine' => 81,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'shouldReceive' => 
      array (
        'name' => 'shouldReceive',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initiate a mock expectation on the facade.
 *
 * @return \\Mockery\\Expectation
 */',
        'startLine' => 97,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'expects' => 
      array (
        'name' => 'expects',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initiate a mock expectation on the facade.
 *
 * @return \\Mockery\\Expectation
 */',
        'startLine' => 113,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'createFreshMockInstance' => 
      array (
        'name' => 'createFreshMockInstance',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a fresh mock instance for the given class.
 *
 * @return \\Mockery\\MockInterface
 */',
        'startLine' => 129,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'createMock' => 
      array (
        'name' => 'createMock',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a fresh mock instance for the given class.
 *
 * @return \\Mockery\\MockInterface
 */',
        'startLine' => 143,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'isMock' => 
      array (
        'name' => 'isMock',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines whether a mock is set as the instance of the facade.
 *
 * @return bool
 */',
        'startLine' => 155,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'getMockableClass' => 
      array (
        'name' => 'getMockableClass',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the mockable class for the bound instance.
 *
 * @return string|null
 */',
        'startLine' => 168,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'swap' => 
      array (
        'name' => 'swap',
        'parameters' => 
        array (
          'instance' => 
          array (
            'name' => 'instance',
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
 * Hotswap the underlying instance behind the facade.
 *
 * @param  mixed  $instance
 * @return void
 */',
        'startLine' => 181,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'isFake' => 
      array (
        'name' => 'isFake',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines whether a "fake" has been set as the facade instance.
 *
 * @return bool
 */',
        'startLine' => 195,
        'endLine' => 201,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'getFacadeRoot' => 
      array (
        'name' => 'getFacadeRoot',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the root object behind the facade.
 *
 * @return mixed
 */',
        'startLine' => 208,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 220,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'resolveFacadeInstance' => 
      array (
        'name' => 'resolveFacadeInstance',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 53,
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
 * Resolve the facade root instance from the container.
 *
 * @param  string  $name
 * @return mixed
 */',
        'startLine' => 231,
        'endLine' => 244,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'clearResolvedInstance' => 
      array (
        'name' => 'clearResolvedInstance',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 252,
            'endLine' => 252,
            'startColumn' => 50,
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
 * Clear a resolved facade instance.
 *
 * @param  string  $name
 * @return void
 */',
        'startLine' => 252,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'clearResolvedInstances' => 
      array (
        'name' => 'clearResolvedInstances',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Clear all of the resolved instances.
 *
 * @return void
 */',
        'startLine' => 262,
        'endLine' => 265,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'defaultAliases' => 
      array (
        'name' => 'defaultAliases',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application default aliases.
 *
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 272,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'getFacadeApplication' => 
      array (
        'name' => 'getFacadeApplication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application instance behind the facade.
 *
 * @return \\Illuminate\\Contracts\\Foundation\\Application|null
 */',
        'startLine' => 328,
        'endLine' => 331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      'setFacadeApplication' => 
      array (
        'name' => 'setFacadeApplication',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 339,
            'endLine' => 339,
            'startColumn' => 49,
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
 * Set the application instance.
 *
 * @param  \\Illuminate\\Contracts\\Foundation\\Application|null  $app
 * @return void
 */',
        'startLine' => 339,
        'endLine' => 342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'aliasName' => NULL,
      ),
      '__callStatic' => 
      array (
        'name' => '__callStatic',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 353,
            'endLine' => 353,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'args' => 
          array (
            'name' => 'args',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 353,
            'endLine' => 353,
            'startColumn' => 50,
            'endColumn' => 54,
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
 * Handle dynamic, static calls to the object.
 *
 * @param  string  $method
 * @param  array  $args
 * @return mixed
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 353,
        'endLine' => 362,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Facade',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Facade',
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