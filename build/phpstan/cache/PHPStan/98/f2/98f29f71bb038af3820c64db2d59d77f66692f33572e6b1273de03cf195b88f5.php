<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Routing/Route.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Routing\Route
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6cdb945509a5d4e2532a4d454142ddde6fb5f837c994913b12e7fc015faa28cc-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Routing\\Route',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Routing/Route.php',
      ),
    ),
    'namespace' => 'Illuminate\\Routing',
    'name' => 'Illuminate\\Routing\\Route',
    'shortName' => 'Route',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 1398,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Conditionable',
      1 => 'Illuminate\\Routing\\CreatesRegularExpressionRouteConstraints',
      2 => 'Illuminate\\Routing\\FiltersControllerMiddleware',
      3 => 'Illuminate\\Support\\Traits\\Macroable',
      4 => 'Illuminate\\Routing\\ResolvesRouteDependencies',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'uri' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'uri',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The URI pattern the route responds to.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'methods' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'methods',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The HTTP methods the route responds to.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'action' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'action',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The route action array.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isFallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'isFallback',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 184,
            'startFilePos' => 1570,
            'endTokenPos' => 184,
            'endFilePos' => 1574,
          ),
        ),
        'docComment' => '/**
 * Indicates whether the route is a fallback route.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'controller' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'controller',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The controller instance.
 *
 * @var mixed
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaults' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'defaults',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 74,
            'endLine' => 74,
            'startTokenPos' => 202,
            'startFilePos' => 1781,
            'endTokenPos' => 203,
            'endFilePos' => 1782,
          ),
        ),
        'docComment' => '/**
 * The default values for the route.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 74,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'wheres' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'wheres',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 81,
            'endLine' => 81,
            'startTokenPos' => 214,
            'startFilePos' => 1892,
            'endTokenPos' => 215,
            'endFilePos' => 1893,
          ),
        ),
        'docComment' => '/**
 * The regular expression requirements.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'parameters' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'parameters',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The array of matched parameters.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'parameterNames' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'parameterNames',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The parameter names for the route.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'originalParameters' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'originalParameters',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The array of the matched parameters\' original values.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'withTrashedBindings' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'withTrashedBindings',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 109,
            'endLine' => 109,
            'startTokenPos' => 247,
            'startFilePos' => 2446,
            'endTokenPos' => 247,
            'endFilePos' => 2450,
          ),
        ),
        'docComment' => '/**
 * Indicates "trashed" models can be retrieved when resolving implicit model bindings for this route.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 109,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'lockSeconds' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'lockSeconds',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates the maximum number of seconds the route should acquire a session lock for.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 116,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'waitSeconds' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'waitSeconds',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates the maximum number of seconds the route should wait while attempting to acquire a session lock.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 123,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'computedMiddleware' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'computedMiddleware',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The computed gathered middleware.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 130,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'compiled' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'compiled',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The compiled version of the route.
 *
 * @var \\Symfony\\Component\\Routing\\CompiledRoute
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 137,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'router' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'router',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The router instance used by the route.
 *
 * @var \\Illuminate\\Routing\\Router
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 144,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'container' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'container',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The container instance used by the route.
 *
 * @var \\Illuminate\\Container\\Container
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 151,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bindingFields' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'bindingFields',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 158,
            'endLine' => 158,
            'startTokenPos' => 300,
            'startFilePos' => 3487,
            'endTokenPos' => 301,
            'endFilePos' => 3488,
          ),
        ),
        'docComment' => '/**
 * The fields that implicit binding should use for a given parameter.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 158,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'validators' => 
      array (
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'name' => 'validators',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The validators used by the routes.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 165,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 30,
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
          'methods' => 
          array (
            'name' => 'methods',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 33,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 43,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'action' => 
          array (
            'name' => 'action',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 49,
            'endColumn' => 55,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new Route instance.
 *
 * @param  array|string  $methods
 * @param  string  $uri
 * @param  \\Closure|array  $action
 * @return void
 */',
        'startLine' => 175,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parseAction' => 
      array (
        'name' => 'parseAction',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 36,
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
 * Parse the route action into a standard array.
 *
 * @param  callable|array|null  $action
 * @return array
 *
 * @throws \\UnexpectedValueException
 */',
        'startLine' => 196,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'run' => 
      array (
        'name' => 'run',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the route action and return the response.
 *
 * @return mixed
 */',
        'startLine' => 206,
        'endLine' => 219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'isControllerAction' => 
      array (
        'name' => 'isControllerAction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks whether the route\'s action is a controller.
 *
 * @return bool
 */',
        'startLine' => 226,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'runCallable' => 
      array (
        'name' => 'runCallable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the route action and return the response.
 *
 * @return mixed
 */',
        'startLine' => 236,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'isSerializedClosure' => 
      array (
        'name' => 'isSerializedClosure',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route action is a serialized Closure.
 *
 * @return bool
 */',
        'startLine' => 252,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'runController' => 
      array (
        'name' => 'runController',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the route action and return the response.
 *
 * @return mixed
 *
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException
 */',
        'startLine' => 264,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getController' => 
      array (
        'name' => 'getController',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the controller instance for the route.
 *
 * @return mixed
 */',
        'startLine' => 276,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getControllerClass' => 
      array (
        'name' => 'getControllerClass',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the controller class used for the route.
 *
 * @return string|null
 */',
        'startLine' => 296,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getControllerMethod' => 
      array (
        'name' => 'getControllerMethod',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the controller method used for the route.
 *
 * @return string
 */',
        'startLine' => 306,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parseControllerCallback' => 
      array (
        'name' => 'parseControllerCallback',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse the controller.
 *
 * @return array
 */',
        'startLine' => 316,
        'endLine' => 319,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'flushController' => 
      array (
        'name' => 'flushController',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the cached container instance on the route.
 *
 * @return void
 */',
        'startLine' => 326,
        'endLine' => 330,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'matches' => 
      array (
        'name' => 'matches',
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
            'startLine' => 339,
            'endLine' => 339,
            'startColumn' => 29,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'includingMethod' => 
          array (
            'name' => 'includingMethod',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 339,
                'endLine' => 339,
                'startTokenPos' => 986,
                'startFilePos' => 7790,
                'endTokenPos' => 986,
                'endFilePos' => 7793,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 339,
            'endLine' => 339,
            'startColumn' => 47,
            'endColumn' => 69,
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
 * Determine if the route matches a given request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  bool  $includingMethod
 * @return bool
 */',
        'startLine' => 339,
        'endLine' => 354,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'compileRoute' => 
      array (
        'name' => 'compileRoute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Compile the route into a Symfony CompiledRoute instance.
 *
 * @return \\Symfony\\Component\\Routing\\CompiledRoute
 */',
        'startLine' => 361,
        'endLine' => 368,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'bind' => 
      array (
        'name' => 'bind',
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
            'startLine' => 376,
            'endLine' => 376,
            'startColumn' => 26,
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
 * Bind the route to a given request for execution.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return $this
 */',
        'startLine' => 376,
        'endLine' => 386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'hasParameters' => 
      array (
        'name' => 'hasParameters',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route has parameters.
 *
 * @return bool
 */',
        'startLine' => 393,
        'endLine' => 396,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'hasParameter' => 
      array (
        'name' => 'hasParameter',
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
            'startLine' => 404,
            'endLine' => 404,
            'startColumn' => 34,
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
 * Determine a given parameter exists from the route.
 *
 * @param  string  $name
 * @return bool
 */',
        'startLine' => 404,
        'endLine' => 411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parameter' => 
      array (
        'name' => 'parameter',
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
            'startLine' => 420,
            'endLine' => 420,
            'startColumn' => 31,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 420,
                'endLine' => 420,
                'startTokenPos' => 1273,
                'startFilePos' => 9623,
                'endTokenPos' => 1273,
                'endFilePos' => 9626,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 420,
            'endLine' => 420,
            'startColumn' => 38,
            'endColumn' => 52,
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
 * Get a given parameter from the route.
 *
 * @param  string  $name
 * @param  string|object|null  $default
 * @return string|object|null
 */',
        'startLine' => 420,
        'endLine' => 423,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'originalParameter' => 
      array (
        'name' => 'originalParameter',
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
            'startLine' => 432,
            'endLine' => 432,
            'startColumn' => 39,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 432,
                'endLine' => 432,
                'startTokenPos' => 1315,
                'startFilePos' => 9940,
                'endTokenPos' => 1315,
                'endFilePos' => 9943,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 432,
            'endLine' => 432,
            'startColumn' => 46,
            'endColumn' => 60,
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
 * Get original value of a given parameter from the route.
 *
 * @param  string  $name
 * @param  string|null  $default
 * @return string|null
 */',
        'startLine' => 432,
        'endLine' => 435,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setParameter' => 
      array (
        'name' => 'setParameter',
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
            'startLine' => 444,
            'endLine' => 444,
            'startColumn' => 34,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 444,
            'endLine' => 444,
            'startColumn' => 41,
            'endColumn' => 46,
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
 * Set a parameter to the given value.
 *
 * @param  string  $name
 * @param  string|object|null  $value
 * @return void
 */',
        'startLine' => 444,
        'endLine' => 449,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'forgetParameter' => 
      array (
        'name' => 'forgetParameter',
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
            'startLine' => 457,
            'endLine' => 457,
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
 * Unset a parameter on the route if it is set.
 *
 * @param  string  $name
 * @return void
 */',
        'startLine' => 457,
        'endLine' => 462,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parameters' => 
      array (
        'name' => 'parameters',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the key / value list of parameters for the route.
 *
 * @return array
 *
 * @throws \\LogicException
 */',
        'startLine' => 471,
        'endLine' => 478,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'originalParameters' => 
      array (
        'name' => 'originalParameters',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the key / value list of original parameters for the route.
 *
 * @return array
 *
 * @throws \\LogicException
 */',
        'startLine' => 487,
        'endLine' => 494,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parametersWithoutNulls' => 
      array (
        'name' => 'parametersWithoutNulls',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the key / value list of parameters without null values.
 *
 * @return array
 */',
        'startLine' => 501,
        'endLine' => 504,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parameterNames' => 
      array (
        'name' => 'parameterNames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the parameter names for the route.
 *
 * @return array
 */',
        'startLine' => 511,
        'endLine' => 518,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'compileParameterNames' => 
      array (
        'name' => 'compileParameterNames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the parameter names for the route.
 *
 * @return array
 */',
        'startLine' => 525,
        'endLine' => 530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'signatureParameters' => 
      array (
        'name' => 'signatureParameters',
        'parameters' => 
        array (
          'conditions' => 
          array (
            'name' => 'conditions',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 538,
                'endLine' => 538,
                'startTokenPos' => 1671,
                'startFilePos' => 12342,
                'endTokenPos' => 1672,
                'endFilePos' => 12343,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 538,
            'endLine' => 538,
            'startColumn' => 41,
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
 * Get the parameters that are listed in the route / controller signature.
 *
 * @param  array  $conditions
 * @return array
 */',
        'startLine' => 538,
        'endLine' => 545,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'bindingFieldFor' => 
      array (
        'name' => 'bindingFieldFor',
        'parameters' => 
        array (
          'parameter' => 
          array (
            'name' => 'parameter',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 553,
            'endLine' => 553,
            'startColumn' => 37,
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
 * Get the binding field for the given parameter.
 *
 * @param  string|int  $parameter
 * @return string|null
 */',
        'startLine' => 553,
        'endLine' => 558,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'bindingFields' => 
      array (
        'name' => 'bindingFields',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the binding fields for the route.
 *
 * @return array
 */',
        'startLine' => 565,
        'endLine' => 568,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setBindingFields' => 
      array (
        'name' => 'setBindingFields',
        'parameters' => 
        array (
          'bindingFields' => 
          array (
            'name' => 'bindingFields',
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
            'startLine' => 576,
            'endLine' => 576,
            'startColumn' => 38,
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
 * Set the binding fields for the route.
 *
 * @param  array  $bindingFields
 * @return $this
 */',
        'startLine' => 576,
        'endLine' => 581,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parentOfParameter' => 
      array (
        'name' => 'parentOfParameter',
        'parameters' => 
        array (
          'parameter' => 
          array (
            'name' => 'parameter',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 589,
            'endLine' => 589,
            'startColumn' => 39,
            'endColumn' => 48,
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
 * Get the parent parameter of the given parameter.
 *
 * @param  string  $parameter
 * @return string|null
 */',
        'startLine' => 589,
        'endLine' => 598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'withTrashed' => 
      array (
        'name' => 'withTrashed',
        'parameters' => 
        array (
          'withTrashed' => 
          array (
            'name' => 'withTrashed',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 606,
                'endLine' => 606,
                'startTokenPos' => 1916,
                'startFilePos' => 13974,
                'endTokenPos' => 1916,
                'endFilePos' => 13977,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 606,
            'endLine' => 606,
            'startColumn' => 33,
            'endColumn' => 51,
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
 * Allow "trashed" models to be retrieved when resolving implicit model bindings for this route.
 *
 * @param  bool  $withTrashed
 * @return $this
 */',
        'startLine' => 606,
        'endLine' => 611,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'allowsTrashedBindings' => 
      array (
        'name' => 'allowsTrashedBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determines if the route allows "trashed" models to be retrieved when resolving implicit model bindings.
 *
 * @return bool
 */',
        'startLine' => 618,
        'endLine' => 621,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'defaults' => 
      array (
        'name' => 'defaults',
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
            'startLine' => 630,
            'endLine' => 630,
            'startColumn' => 30,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 630,
            'endLine' => 630,
            'startColumn' => 36,
            'endColumn' => 41,
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
 * Set a default value for the route.
 *
 * @param  string  $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 630,
        'endLine' => 635,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setDefaults' => 
      array (
        'name' => 'setDefaults',
        'parameters' => 
        array (
          'defaults' => 
          array (
            'name' => 'defaults',
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
            'startLine' => 643,
            'endLine' => 643,
            'startColumn' => 33,
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
 * Set the default values for the route.
 *
 * @param  array  $defaults
 * @return $this
 */',
        'startLine' => 643,
        'endLine' => 648,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'where' => 
      array (
        'name' => 'where',
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
            'startLine' => 657,
            'endLine' => 657,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expression' => 
          array (
            'name' => 'expression',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 657,
                'endLine' => 657,
                'startTokenPos' => 2039,
                'startFilePos' => 15050,
                'endTokenPos' => 2039,
                'endFilePos' => 15053,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 657,
            'endLine' => 657,
            'startColumn' => 34,
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
 * Set a regular expression requirement on the route.
 *
 * @param  array|string  $name
 * @param  string|null  $expression
 * @return $this
 */',
        'startLine' => 657,
        'endLine' => 664,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parseWhere' => 
      array (
        'name' => 'parseWhere',
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
            'startLine' => 673,
            'endLine' => 673,
            'startColumn' => 35,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 673,
            'endLine' => 673,
            'startColumn' => 42,
            'endColumn' => 52,
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
 * Parse arguments to the where method into an array.
 *
 * @param  array|string  $name
 * @param  string  $expression
 * @return array
 */',
        'startLine' => 673,
        'endLine' => 676,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setWheres' => 
      array (
        'name' => 'setWheres',
        'parameters' => 
        array (
          'wheres' => 
          array (
            'name' => 'wheres',
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
            'startLine' => 684,
            'endLine' => 684,
            'startColumn' => 31,
            'endColumn' => 43,
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
 * Set a list of regular expression requirements on the route.
 *
 * @param  array  $wheres
 * @return $this
 */',
        'startLine' => 684,
        'endLine' => 691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'fallback' => 
      array (
        'name' => 'fallback',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Mark this route as a fallback route.
 *
 * @return $this
 */',
        'startLine' => 698,
        'endLine' => 703,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setFallback' => 
      array (
        'name' => 'setFallback',
        'parameters' => 
        array (
          'isFallback' => 
          array (
            'name' => 'isFallback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 711,
            'endLine' => 711,
            'startColumn' => 33,
            'endColumn' => 43,
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
 * Set the fallback value.
 *
 * @param  bool  $isFallback
 * @return $this
 */',
        'startLine' => 711,
        'endLine' => 716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'methods' => 
      array (
        'name' => 'methods',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the HTTP verbs the route responds to.
 *
 * @return array
 */',
        'startLine' => 723,
        'endLine' => 726,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'httpOnly' => 
      array (
        'name' => 'httpOnly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route only responds to HTTP requests.
 *
 * @return bool
 */',
        'startLine' => 733,
        'endLine' => 736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'httpsOnly' => 
      array (
        'name' => 'httpsOnly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route only responds to HTTPS requests.
 *
 * @return bool
 */',
        'startLine' => 743,
        'endLine' => 746,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'secure' => 
      array (
        'name' => 'secure',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route only responds to HTTPS requests.
 *
 * @return bool
 */',
        'startLine' => 753,
        'endLine' => 756,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'domain' => 
      array (
        'name' => 'domain',
        'parameters' => 
        array (
          'domain' => 
          array (
            'name' => 'domain',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 766,
                'endLine' => 766,
                'startTokenPos' => 2353,
                'startFilePos' => 17274,
                'endTokenPos' => 2353,
                'endFilePos' => 17277,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 766,
            'endLine' => 766,
            'startColumn' => 28,
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
 * Get or set the domain for the route.
 *
 * @param  \\BackedEnum|string|null  $domain
 * @return $this|string|null
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 766,
        'endLine' => 785,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getDomain' => 
      array (
        'name' => 'getDomain',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the domain defined for the route.
 *
 * @return string|null
 */',
        'startLine' => 792,
        'endLine' => 796,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getPrefix' => 
      array (
        'name' => 'getPrefix',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the prefix of the route instance.
 *
 * @return string|null
 */',
        'startLine' => 803,
        'endLine' => 806,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'prefix' => 
      array (
        'name' => 'prefix',
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
            'startLine' => 814,
            'endLine' => 814,
            'startColumn' => 28,
            'endColumn' => 34,
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
 * Add a prefix to the route URI.
 *
 * @param  string|null  $prefix
 * @return $this
 */',
        'startLine' => 814,
        'endLine' => 823,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'updatePrefixOnAction' => 
      array (
        'name' => 'updatePrefixOnAction',
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
            'startLine' => 831,
            'endLine' => 831,
            'startColumn' => 45,
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
 * Update the "prefix" attribute on the action array.
 *
 * @param  string  $prefix
 * @return void
 */',
        'startLine' => 831,
        'endLine' => 836,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'uri' => 
      array (
        'name' => 'uri',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the URI associated with the route.
 *
 * @return string
 */',
        'startLine' => 843,
        'endLine' => 846,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setUri' => 
      array (
        'name' => 'setUri',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 854,
            'endLine' => 854,
            'startColumn' => 28,
            'endColumn' => 31,
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
 * Set the URI that the route responds to.
 *
 * @param  string  $uri
 * @return $this
 */',
        'startLine' => 854,
        'endLine' => 859,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'parseUri' => 
      array (
        'name' => 'parseUri',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 867,
            'endLine' => 867,
            'startColumn' => 33,
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
 * Parse the route URI and normalize / store any implicit binding fields.
 *
 * @param  string  $uri
 * @return string
 */',
        'startLine' => 867,
        'endLine' => 874,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getName' => 
      array (
        'name' => 'getName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the route instance.
 *
 * @return string|null
 */',
        'startLine' => 881,
        'endLine' => 884,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'name' => 
      array (
        'name' => 'name',
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
            'startLine' => 894,
            'endLine' => 894,
            'startColumn' => 26,
            'endColumn' => 30,
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
 * Add or change the route name.
 *
 * @param  \\BackedEnum|string  $name
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 894,
        'endLine' => 903,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'named' => 
      array (
        'name' => 'named',
        'parameters' => 
        array (
          'patterns' => 
          array (
            'name' => 'patterns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 911,
            'endLine' => 911,
            'startColumn' => 27,
            'endColumn' => 38,
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
 * Determine whether the route\'s name matches the given patterns.
 *
 * @param  mixed  ...$patterns
 * @return bool
 */',
        'startLine' => 911,
        'endLine' => 924,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'uses' => 
      array (
        'name' => 'uses',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 932,
            'endLine' => 932,
            'startColumn' => 26,
            'endColumn' => 32,
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
 * Set the handler for the route.
 *
 * @param  \\Closure|array|string  $action
 * @return $this
 */',
        'startLine' => 932,
        'endLine' => 944,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'addGroupNamespaceToStringUses' => 
      array (
        'name' => 'addGroupNamespaceToStringUses',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 952,
            'endLine' => 952,
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
 * Parse a string based action for the "uses" fluent method.
 *
 * @param  string  $action
 * @return string
 */',
        'startLine' => 952,
        'endLine' => 961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getActionName' => 
      array (
        'name' => 'getActionName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the action name for the route.
 *
 * @return string
 */',
        'startLine' => 968,
        'endLine' => 971,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getActionMethod' => 
      array (
        'name' => 'getActionMethod',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the method name of the route action.
 *
 * @return string
 */',
        'startLine' => 978,
        'endLine' => 981,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getAction' => 
      array (
        'name' => 'getAction',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 989,
                'endLine' => 989,
                'startTokenPos' => 3295,
                'startFilePos' => 22454,
                'endTokenPos' => 3295,
                'endFilePos' => 22457,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 989,
            'endLine' => 989,
            'startColumn' => 31,
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
 * Get the action array or one of its properties for the route.
 *
 * @param  string|null  $key
 * @return mixed
 */',
        'startLine' => 989,
        'endLine' => 992,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setAction' => 
      array (
        'name' => 'setAction',
        'parameters' => 
        array (
          'action' => 
          array (
            'name' => 'action',
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
            'startLine' => 1000,
            'endLine' => 1000,
            'startColumn' => 31,
            'endColumn' => 43,
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
 * Set the action array for the route.
 *
 * @param  array  $action
 * @return $this
 */',
        'startLine' => 1000,
        'endLine' => 1009,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getMissing' => 
      array (
        'name' => 'getMissing',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the value of the action that should be taken on a missing model exception.
 *
 * @return \\Closure|null
 */',
        'startLine' => 1016,
        'endLine' => 1025,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'missing' => 
      array (
        'name' => 'missing',
        'parameters' => 
        array (
          'missing' => 
          array (
            'name' => 'missing',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1033,
            'endLine' => 1033,
            'startColumn' => 29,
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
 * Define the callable that should be invoked on a missing model exception.
 *
 * @param  \\Closure  $missing
 * @return $this
 */',
        'startLine' => 1033,
        'endLine' => 1038,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'gatherMiddleware' => 
      array (
        'name' => 'gatherMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all middleware, including the ones from the controller.
 *
 * @return array
 */',
        'startLine' => 1045,
        'endLine' => 1056,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'middleware' => 
      array (
        'name' => 'middleware',
        'parameters' => 
        array (
          'middleware' => 
          array (
            'name' => 'middleware',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1064,
                'endLine' => 1064,
                'startTokenPos' => 3572,
                'startFilePos' => 24328,
                'endTokenPos' => 3572,
                'endFilePos' => 24331,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1064,
            'endLine' => 1064,
            'startColumn' => 32,
            'endColumn' => 49,
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
 * Get or set the middlewares attached to the route.
 *
 * @param  array|string|null  $middleware
 * @return $this|array
 */',
        'startLine' => 1064,
        'endLine' => 1083,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'can' => 
      array (
        'name' => 'can',
        'parameters' => 
        array (
          'ability' => 
          array (
            'name' => 'ability',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1092,
            'endLine' => 1092,
            'startColumn' => 25,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'models' => 
          array (
            'name' => 'models',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1092,
                'endLine' => 1092,
                'startTokenPos' => 3719,
                'startFilePos' => 25096,
                'endTokenPos' => 3720,
                'endFilePos' => 25097,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1092,
            'endLine' => 1092,
            'startColumn' => 35,
            'endColumn' => 46,
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
 * Specify that the "Authorize" / "can" middleware should be applied to the route with the given options.
 *
 * @param  \\UnitEnum|string  $ability
 * @param  array|string  $models
 * @return $this
 */',
        'startLine' => 1092,
        'endLine' => 1099,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'controllerMiddleware' => 
      array (
        'name' => 'controllerMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the middleware for the route\'s controller.
 *
 * @return array
 */',
        'startLine' => 1106,
        'endLine' => 1130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'staticallyProvidedControllerMiddleware' => 
      array (
        'name' => 'staticallyProvidedControllerMiddleware',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
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
            'startLine' => 1139,
            'endLine' => 1139,
            'startColumn' => 63,
            'endColumn' => 75,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'method' => 
          array (
            'name' => 'method',
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
            'startLine' => 1139,
            'endLine' => 1139,
            'startColumn' => 78,
            'endColumn' => 91,
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
 * Get the statically provided controller middleware for the given class and method.
 *
 * @param  string  $class
 * @param  string  $method
 * @return array
 */',
        'startLine' => 1139,
        'endLine' => 1150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'withoutMiddleware' => 
      array (
        'name' => 'withoutMiddleware',
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
            'startLine' => 1158,
            'endLine' => 1158,
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
 * Specify middleware that should be removed from the given route.
 *
 * @param  array|string  $middleware
 * @return $this
 */',
        'startLine' => 1158,
        'endLine' => 1165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'excludedMiddleware' => 
      array (
        'name' => 'excludedMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the middleware that should be removed from the route.
 *
 * @return array
 */',
        'startLine' => 1172,
        'endLine' => 1175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'scopeBindings' => 
      array (
        'name' => 'scopeBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the route should enforce scoping of multiple implicit Eloquent bindings.
 *
 * @return $this
 */',
        'startLine' => 1182,
        'endLine' => 1187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'withoutScopedBindings' => 
      array (
        'name' => 'withoutScopedBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the route should not enforce scoping of multiple implicit Eloquent bindings.
 *
 * @return $this
 */',
        'startLine' => 1194,
        'endLine' => 1199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'enforcesScopedBindings' => 
      array (
        'name' => 'enforcesScopedBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route should enforce scoping of multiple implicit Eloquent bindings.
 *
 * @return bool
 */',
        'startLine' => 1206,
        'endLine' => 1209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'preventsScopedBindings' => 
      array (
        'name' => 'preventsScopedBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the route should prevent scoping of multiple implicit Eloquent bindings.
 *
 * @return bool
 */',
        'startLine' => 1216,
        'endLine' => 1219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'block' => 
      array (
        'name' => 'block',
        'parameters' => 
        array (
          'lockSeconds' => 
          array (
            'name' => 'lockSeconds',
            'default' => 
            array (
              'code' => '10',
              'attributes' => 
              array (
                'startLine' => 1228,
                'endLine' => 1228,
                'startTokenPos' => 4309,
                'startFilePos' => 28914,
                'endTokenPos' => 4309,
                'endFilePos' => 28915,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1228,
            'endLine' => 1228,
            'startColumn' => 27,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'waitSeconds' => 
          array (
            'name' => 'waitSeconds',
            'default' => 
            array (
              'code' => '10',
              'attributes' => 
              array (
                'startLine' => 1228,
                'endLine' => 1228,
                'startTokenPos' => 4316,
                'startFilePos' => 28933,
                'endTokenPos' => 4316,
                'endFilePos' => 28934,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1228,
            'endLine' => 1228,
            'startColumn' => 46,
            'endColumn' => 62,
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
 * Specify that the route should not allow concurrent requests from the same session.
 *
 * @param  int|null  $lockSeconds
 * @param  int|null  $waitSeconds
 * @return $this
 */',
        'startLine' => 1228,
        'endLine' => 1234,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'withoutBlocking' => 
      array (
        'name' => 'withoutBlocking',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Specify that the route should allow concurrent requests from the same session.
 *
 * @return $this
 */',
        'startLine' => 1241,
        'endLine' => 1244,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'locksFor' => 
      array (
        'name' => 'locksFor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the maximum number of seconds the route\'s session lock should be held for.
 *
 * @return int|null
 */',
        'startLine' => 1251,
        'endLine' => 1254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'waitsFor' => 
      array (
        'name' => 'waitsFor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the maximum number of seconds to wait while attempting to acquire a session lock.
 *
 * @return int|null
 */',
        'startLine' => 1261,
        'endLine' => 1264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'controllerDispatcher' => 
      array (
        'name' => 'controllerDispatcher',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the dispatcher for the route\'s controller.
 *
 * @return \\Illuminate\\Routing\\Contracts\\ControllerDispatcher
 */',
        'startLine' => 1271,
        'endLine' => 1278,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getValidators' => 
      array (
        'name' => 'getValidators',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the route validators for the instance.
 *
 * @return array
 */',
        'startLine' => 1285,
        'endLine' => 1298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'toSymfonyRoute' => 
      array (
        'name' => 'toSymfonyRoute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert the route to a Symfony route.
 *
 * @return \\Symfony\\Component\\Routing\\Route
 */',
        'startLine' => 1305,
        'endLine' => 1312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getOptionalParameterNames' => 
      array (
        'name' => 'getOptionalParameterNames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the optional parameter names for the route.
 *
 * @return array
 */',
        'startLine' => 1319,
        'endLine' => 1324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'getCompiled' => 
      array (
        'name' => 'getCompiled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the compiled version of the route.
 *
 * @return \\Symfony\\Component\\Routing\\CompiledRoute
 */',
        'startLine' => 1331,
        'endLine' => 1334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setRouter' => 
      array (
        'name' => 'setRouter',
        'parameters' => 
        array (
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
            'startLine' => 1342,
            'endLine' => 1342,
            'startColumn' => 31,
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
 * Set the router instance on the route.
 *
 * @param  \\Illuminate\\Routing\\Router  $router
 * @return $this
 */',
        'startLine' => 1342,
        'endLine' => 1347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'setContainer' => 
      array (
        'name' => 'setContainer',
        'parameters' => 
        array (
          'container' => 
          array (
            'name' => 'container',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Container\\Container',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1355,
            'endLine' => 1355,
            'startColumn' => 34,
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
 * Set the container instance on the route.
 *
 * @param  \\Illuminate\\Container\\Container  $container
 * @return $this
 */',
        'startLine' => 1355,
        'endLine' => 1360,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      'prepareForSerialization' => 
      array (
        'name' => 'prepareForSerialization',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepare the route instance for serialization.
 *
 * @return void
 *
 * @throws \\LogicException
 */',
        'startLine' => 1369,
        'endLine' => 1386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
        'aliasName' => NULL,
      ),
      '__get' => 
      array (
        'name' => '__get',
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
            'startLine' => 1394,
            'endLine' => 1394,
            'startColumn' => 27,
            'endColumn' => 30,
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
 * Dynamically access route parameters.
 *
 * @param  string  $key
 * @return mixed
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
        'namespace' => 'Illuminate\\Routing',
        'declaringClassName' => 'Illuminate\\Routing\\Route',
        'implementingClassName' => 'Illuminate\\Routing\\Route',
        'currentClassName' => 'Illuminate\\Routing\\Route',
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