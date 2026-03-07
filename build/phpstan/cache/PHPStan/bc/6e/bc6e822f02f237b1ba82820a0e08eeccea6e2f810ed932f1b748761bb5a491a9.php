<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Routing/UrlGenerator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Routing\UrlGenerator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-39c99dcbb23d172d46448112f43060e2125d2a008c2ad4130aecc1f98cba27a0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Routing/UrlGenerator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Routing',
    'name' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
    'shortName' => 'UrlGenerator',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method string query(string $path, array $query = [], mixed $extra = [], bool|null $secure = null)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 113,
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
    ),
    'immediateMethods' => 
    array (
      'current' => 
      array (
        'name' => 'current',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current URL for the request.
 *
 * @return string
 */',
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'previous' => 
      array (
        'name' => 'previous',
        'parameters' => 
        array (
          'fallback' => 
          array (
            'name' => 'fallback',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 23,
                'endLine' => 23,
                'startTokenPos' => 38,
                'startFilePos' => 467,
                'endTokenPos' => 38,
                'endFilePos' => 471,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 30,
            'endColumn' => 46,
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
 * Get the URL for the previous request.
 *
 * @param  mixed  $fallback
 * @return string
 */',
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'to' => 
      array (
        'name' => 'to',
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
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 24,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 33,
                'endLine' => 33,
                'startTokenPos' => 57,
                'startFilePos' => 703,
                'endTokenPos' => 58,
                'endFilePos' => 704,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 31,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'secure' => 
          array (
            'name' => 'secure',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 33,
                'endLine' => 33,
                'startTokenPos' => 65,
                'startFilePos' => 717,
                'endTokenPos' => 65,
                'endFilePos' => 720,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 44,
            'endColumn' => 57,
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
 * Generate an absolute URL to the given path.
 *
 * @param  string  $path
 * @param  mixed  $extra
 * @param  bool|null  $secure
 * @return string
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'secure' => 
      array (
        'name' => 'secure',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 28,
            'endColumn' => 32,
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
                'startLine' => 42,
                'endLine' => 42,
                'startTokenPos' => 84,
                'startFilePos' => 939,
                'endTokenPos' => 85,
                'endFilePos' => 940,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 35,
            'endColumn' => 50,
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
 * Generate a secure, absolute URL to the given path.
 *
 * @param  string  $path
 * @param  array  $parameters
 * @return string
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'asset' => 
      array (
        'name' => 'asset',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'secure' => 
          array (
            'name' => 'secure',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 104,
                'startFilePos' => 1145,
                'endTokenPos' => 104,
                'endFilePos' => 1148,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 34,
            'endColumn' => 47,
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
 * Generate the URL to an application asset.
 *
 * @param  string  $path
 * @param  bool|null  $secure
 * @return string
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'route' => 
      array (
        'name' => 'route',
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
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 27,
            'endColumn' => 31,
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
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 123,
                'startFilePos' => 1424,
                'endTokenPos' => 124,
                'endFilePos' => 1425,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 34,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 131,
                'startFilePos' => 1440,
                'endTokenPos' => 131,
                'endFilePos' => 1443,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 52,
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
 * Get the URL to a named route.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  bool  $absolute
 * @return string
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 69,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'signedRoute' => 
      array (
        'name' => 'signedRoute',
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
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 33,
            'endColumn' => 37,
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
                'startLine' => 76,
                'endLine' => 76,
                'startTokenPos' => 150,
                'startFilePos' => 1810,
                'endTokenPos' => 151,
                'endFilePos' => 1811,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 40,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'expiration' => 
          array (
            'name' => 'expiration',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 76,
                'endLine' => 76,
                'startTokenPos' => 158,
                'startFilePos' => 1828,
                'endTokenPos' => 158,
                'endFilePos' => 1831,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 58,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 76,
                'endLine' => 76,
                'startTokenPos' => 165,
                'startFilePos' => 1846,
                'endTokenPos' => 165,
                'endFilePos' => 1849,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 78,
            'endColumn' => 93,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a signed route URL for a named route.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  \\DateTimeInterface|\\DateInterval|int|null  $expiration
 * @param  bool  $absolute
 * @return string
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 95,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'temporarySignedRoute' => 
      array (
        'name' => 'temporarySignedRoute',
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 42,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expiration' => 
          array (
            'name' => 'expiration',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 49,
            'endColumn' => 59,
            'parameterIndex' => 1,
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
                'startLine' => 87,
                'endLine' => 87,
                'startTokenPos' => 187,
                'startFilePos' => 2195,
                'endTokenPos' => 188,
                'endFilePos' => 2196,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 62,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 87,
                'endLine' => 87,
                'startTokenPos' => 195,
                'startFilePos' => 2211,
                'endTokenPos' => 195,
                'endFilePos' => 2214,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 80,
            'endColumn' => 95,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a temporary signed route URL for a named route.
 *
 * @param  string  $name
 * @param  \\DateTimeInterface|\\DateInterval|int  $expiration
 * @param  array  $parameters
 * @param  bool  $absolute
 * @return string
 */',
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 97,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'action' => 
      array (
        'name' => 'action',
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 28,
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
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 214,
                'startFilePos' => 2459,
                'endTokenPos' => 215,
                'endFilePos' => 2460,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 97,
                'endLine' => 97,
                'startTokenPos' => 222,
                'startFilePos' => 2475,
                'endTokenPos' => 222,
                'endFilePos' => 2478,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 55,
            'endColumn' => 70,
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
 * Get the URL to a controller action.
 *
 * @param  string|array  $action
 * @param  mixed  $parameters
 * @param  bool  $absolute
 * @return string
 */',
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 72,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'getRootControllerNamespace' => 
      array (
        'name' => 'getRootControllerNamespace',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the root controller namespace.
 *
 * @return string
 */',
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'aliasName' => NULL,
      ),
      'setRootControllerNamespace' => 
      array (
        'name' => 'setRootControllerNamespace',
        'parameters' => 
        array (
          'rootNamespace' => 
          array (
            'name' => 'rootNamespace',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 48,
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
 * Set the root controller namespace.
 *
 * @param  string  $rootNamespace
 * @return $this
 */',
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 63,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Routing',
        'declaringClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'implementingClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
        'currentClassName' => 'Illuminate\\Contracts\\Routing\\UrlGenerator',
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