<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-807d1800823e5989fedbf181faef5fe91c3fd12b7945aaa7cdc38d9e60d439af-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
    'name' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
    'shortName' => 'VerifyCsrfToken',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 250,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\InteractsWithTime',
      1 => 'Illuminate\\Foundation\\Http\\Middleware\\Concerns\\ExcludesPaths',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'app' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'name' => 'app',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The application instance.
 *
 * @var \\Illuminate\\Contracts\\Foundation\\Application
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'encrypter' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'name' => 'encrypter',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The encrypter implementation.
 *
 * @var \\Illuminate\\Contracts\\Encryption\\Encrypter
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'except' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'name' => 'except',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 103,
            'startFilePos' => 1055,
            'endTokenPos' => 104,
            'endFilePos' => 1056,
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
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'neverVerify' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'name' => 'neverVerify',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 117,
            'startFilePos' => 1218,
            'endTokenPos' => 118,
            'endFilePos' => 1219,
          ),
        ),
        'docComment' => '/**
 * The globally ignored URIs that should be excluded from CSRF verification.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'addHttpCookie' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'name' => 'addHttpCookie',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 129,
            'startFilePos' => 1372,
            'endTokenPos' => 129,
            'endFilePos' => 1375,
          ),
        ),
        'docComment' => '/**
 * Indicates whether the XSRF-TOKEN cookie should be set on the response.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
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
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'encrypter' => 
          array (
            'name' => 'encrypter',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 51,
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
 * Create a new middleware instance.
 *
 * @param  \\Illuminate\\Contracts\\Foundation\\Application  $app
 * @param  \\Illuminate\\Contracts\\Encryption\\Encrypter  $encrypter
 * @return void
 */',
        'startLine' => 65,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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
            'startLine' => 80,
            'endLine' => 80,
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
            'startLine' => 80,
            'endLine' => 80,
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
 * @throws \\Illuminate\\Session\\TokenMismatchException
 */',
        'startLine' => 80,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'isReading' => 
      array (
        'name' => 'isReading',
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
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 34,
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
 * Determine if the HTTP request uses a ‘read’ verb.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return bool
 */',
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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
        'startLine' => 114,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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
        'startLine' => 124,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'tokensMatch' => 
      array (
        'name' => 'tokensMatch',
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
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 36,
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
 * Determine if the session and input CSRF tokens match.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return bool
 */',
        'startLine' => 135,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'getTokenFromRequest' => 
      array (
        'name' => 'getTokenFromRequest',
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
            'startLine' => 150,
            'endLine' => 150,
            'startColumn' => 44,
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
 * Get the CSRF token from the request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return string|null
 */',
        'startLine' => 150,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'shouldAddXsrfTokenCookie' => 
      array (
        'name' => 'shouldAddXsrfTokenCookie',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the cookie should be added to the response.
 *
 * @return bool
 */',
        'startLine' => 170,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'addCookieToResponse' => 
      array (
        'name' => 'addCookieToResponse',
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
            'startLine' => 182,
            'endLine' => 182,
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
            'startLine' => 182,
            'endLine' => 182,
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
 * Add the CSRF token to the response cookies.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Symfony\\Component\\HttpFoundation\\Response  $response
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 182,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'newCookie' => 
      array (
        'name' => 'newCookie',
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
            'startLine' => 202,
            'endLine' => 202,
            'startColumn' => 34,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 202,
            'endLine' => 202,
            'startColumn' => 44,
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
 * Create a new "XSRF-TOKEN" cookie that contains the CSRF token.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  array  $config
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie
 */',
        'startLine' => 202,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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
            'startLine' => 224,
            'endLine' => 224,
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
 * Indicate that the given URIs should be excluded from CSRF verification.
 *
 * @param  array|string  $uris
 * @return void
 */',
        'startLine' => 224,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'serialized' => 
      array (
        'name' => 'serialized',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the cookie contents should be serialized.
 *
 * @return bool
 */',
        'startLine' => 236,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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
        'startLine' => 246,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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