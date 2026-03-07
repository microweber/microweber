<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/Http/Middleware/VerifyCsrfToken.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\App\Http\Middleware\VerifyCsrfToken
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-66f5b283e4c18cebeaacf9806a4794e25d6196f62f76e44ed2d318423614ce0b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/Http/Middleware/VerifyCsrfToken.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\App\\Http\\Middleware',
    'name' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
    'shortName' => 'VerifyCsrfToken',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @deprecated
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 102,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
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
      'except' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'name' => 'except',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 23,
            'startTokenPos' => 61,
            'startFilePos' => 559,
            'endTokenPos' => 65,
            'endFilePos' => 576,
          ),
        ),
        'docComment' => '/**
 * The URIs that should be excluded from CSRF verification.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 23,
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
            'startLine' => 35,
            'endLine' => 35,
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 38,
            'endColumn' => 51,
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
 * @param \\Illuminate\\Http\\Request $request
 * @param \\Closure $next
 * @return mixed
 *
 * @throws \\Illuminate\\Session\\TokenMismatchException
 */',
        'startLine' => 35,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Http\\Middleware',
        'declaringClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
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
            'startLine' => 84,
            'endLine' => 84,
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
            'startLine' => 84,
            'endLine' => 84,
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
 * @param \\Illuminate\\Http\\Request $request
 * @param \\Symfony\\Component\\HttpFoundation\\Response $response
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 84,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\App\\Http\\Middleware',
        'declaringClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'aliasName' => NULL,
      ),
      'forceAddAddXsrfTokenCookie' => 
      array (
        'name' => 'forceAddAddXsrfTokenCookie',
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
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 48,
            'endColumn' => 55,
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
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 58,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 96,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Http\\Middleware',
        'declaringClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'implementingClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
        'currentClassName' => 'MicroweberPackages\\App\\Http\\Middleware\\VerifyCsrfToken',
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