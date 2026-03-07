<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-146854d177f3fce59e873f7d330afc049fffdd391567799919ec6c737f09dd64-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
    'name' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
    'shortName' => 'PreventRequestsDuringMaintenance',
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
    'endLine' => 192,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Foundation\\Http\\Middleware\\Concerns\\ExcludesPaths',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'app' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
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
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'except' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'name' => 'except',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 68,
            'startFilePos' => 676,
            'endTokenPos' => 69,
            'endFilePos' => 677,
          ),
        ),
        'docComment' => '/**
 * The URIs that should be excluded.
 *
 * @var array<int, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'neverPrevent' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'name' => 'neverPrevent',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 82,
            'startFilePos' => 821,
            'endTokenPos' => 83,
            'endFilePos' => 822,
          ),
        ),
        'docComment' => '/**
 * The URIs that should be accessible during maintenance.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 40,
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 33,
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
 * Create a new middleware instance.
 *
 * @param  \\Illuminate\\Contracts\\Foundation\\Application  $app
 * @return void
 */',
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
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
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'next' => 
          array (
            'name' => 'next',
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
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 38,
            'endColumn' => 50,
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
 * Handle an incoming request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Closure  $next
 * @return mixed
 *
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\HttpException
 * @throws \\ErrorException
 */',
        'startLine' => 59,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'aliasName' => NULL,
      ),
      'hasValidBypassCookie' => 
      array (
        'name' => 'hasValidBypassCookie',
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
            'startLine' => 120,
            'endLine' => 120,
            'startColumn' => 45,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 120,
            'endLine' => 120,
            'startColumn' => 55,
            'endColumn' => 65,
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
 * Determine if the incoming request has a maintenance mode bypass cookie.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  array  $data
 * @return bool
 */',
        'startLine' => 120,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'aliasName' => NULL,
      ),
      'bypassResponse' => 
      array (
        'name' => 'bypassResponse',
        'parameters' => 
        array (
          'secret' => 
          array (
            'name' => 'secret',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 39,
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
 * Redirect the user back to the root of the application with a maintenance mode bypass cookie.
 *
 * @param  string  $secret
 * @return \\Illuminate\\Http\\RedirectResponse
 */',
        'startLine' => 136,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'aliasName' => NULL,
      ),
      'getHeaders' => 
      array (
        'name' => 'getHeaders',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 149,
            'endLine' => 149,
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
 * Get the headers that should be sent with the response.
 *
 * @param  array  $data
 * @return array
 */',
        'startLine' => 149,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'aliasName' => NULL,
      ),
      'getExcludedPaths' => 
      array (
        'name' => 'getExcludedPaths',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the URIs that should be excluded.
 *
 * @return array
 */',
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'aliasName' => NULL,
      ),
      'except' => 
      array (
        'name' => 'except',
        'parameters' => 
        array (
          'uris' => 
          array (
            'name' => 'uris',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 176,
            'endLine' => 176,
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
 * Indicate that the given URIs should always be accessible.
 *
 * @param  array|string  $uris
 * @return void
 */',
        'startLine' => 176,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'aliasName' => NULL,
      ),
      'flushState' => 
      array (
        'name' => 'flushState',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the state of the middleware.
 *
 * @return void
 */',
        'startLine' => 188,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance',
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