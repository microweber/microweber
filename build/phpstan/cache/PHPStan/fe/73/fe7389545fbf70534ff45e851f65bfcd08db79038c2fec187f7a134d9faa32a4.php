<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cookie/CookieJar.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Cookie\CookieJar
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-251c0bf077063d16dc0f2413865a293b8af3dac723e27718622bb1e220195b38-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Cookie\\CookieJar',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Cookie/CookieJar.php',
      ),
    ),
    'namespace' => 'Illuminate\\Cookie',
    'name' => 'Illuminate\\Cookie\\CookieJar',
    'shortName' => 'CookieJar',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 242,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Cookie\\QueueingFactory',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\InteractsWithTime',
      1 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'path' => 
      array (
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'name' => 'path',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'/\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 62,
            'startFilePos' => 442,
            'endTokenPos' => 62,
            'endFilePos' => 444,
          ),
        ),
        'docComment' => '/**
 * The default path (if specified).
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'domain' => 
      array (
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'name' => 'domain',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The default domain (if specified).
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'secure' => 
      array (
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'name' => 'secure',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The default secure setting (defaults to null).
 *
 * @var bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sameSite' => 
      array (
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'name' => 'sameSite',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'lax\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 87,
            'startFilePos' => 806,
            'endTokenPos' => 87,
            'endFilePos' => 810,
          ),
        ),
        'docComment' => '/**
 * The default SameSite option (defaults to lax).
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'queued' => 
      array (
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'name' => 'queued',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 98,
            'startFilePos' => 962,
            'endTokenPos' => 99,
            'endFilePos' => 963,
          ),
        ),
        'docComment' => '/**
 * All of the cookies queued for sending.
 *
 * @var \\Symfony\\Component\\HttpFoundation\\Cookie[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 27,
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
      'make' => 
      array (
        'name' => 'make',
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
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 26,
            'endColumn' => 30,
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
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'minutes' => 
          array (
            'name' => 'minutes',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 120,
                'startFilePos' => 1421,
                'endTokenPos' => 120,
                'endFilePos' => 1421,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 41,
            'endColumn' => 52,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 127,
                'startFilePos' => 1432,
                'endTokenPos' => 127,
                'endFilePos' => 1435,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 55,
            'endColumn' => 66,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'domain' => 
          array (
            'name' => 'domain',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 134,
                'startFilePos' => 1448,
                'endTokenPos' => 134,
                'endFilePos' => 1451,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 69,
            'endColumn' => 82,
            'parameterIndex' => 4,
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
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 141,
                'startFilePos' => 1464,
                'endTokenPos' => 141,
                'endFilePos' => 1467,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 85,
            'endColumn' => 98,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'httpOnly' => 
          array (
            'name' => 'httpOnly',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 148,
                'startFilePos' => 1482,
                'endTokenPos' => 148,
                'endFilePos' => 1485,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 101,
            'endColumn' => 116,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'raw' => 
          array (
            'name' => 'raw',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 155,
                'startFilePos' => 1495,
                'endTokenPos' => 155,
                'endFilePos' => 1499,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 119,
            'endColumn' => 130,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
          'sameSite' => 
          array (
            'name' => 'sameSite',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 162,
                'startFilePos' => 1514,
                'endTokenPos' => 162,
                'endFilePos' => 1517,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 133,
            'endColumn' => 148,
            'parameterIndex' => 8,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new cookie instance.
 *
 * @param  string  $name
 * @param  string  $value
 * @param  int  $minutes
 * @param  string|null  $path
 * @param  string|null  $domain
 * @param  bool|null  $secure
 * @param  bool  $httpOnly
 * @param  bool  $raw
 * @param  string|null  $sameSite
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie
 */',
        'startLine' => 64,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'forever' => 
      array (
        'name' => 'forever',
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
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 29,
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
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 36,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 283,
                'startFilePos' => 2262,
                'endTokenPos' => 283,
                'endFilePos' => 2265,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'domain' => 
          array (
            'name' => 'domain',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 290,
                'startFilePos' => 2278,
                'endTokenPos' => 290,
                'endFilePos' => 2281,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 58,
            'endColumn' => 71,
            'parameterIndex' => 3,
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
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 297,
                'startFilePos' => 2294,
                'endTokenPos' => 297,
                'endFilePos' => 2297,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 74,
            'endColumn' => 87,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'httpOnly' => 
          array (
            'name' => 'httpOnly',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 304,
                'startFilePos' => 2312,
                'endTokenPos' => 304,
                'endFilePos' => 2315,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 90,
            'endColumn' => 105,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'raw' => 
          array (
            'name' => 'raw',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 311,
                'startFilePos' => 2325,
                'endTokenPos' => 311,
                'endFilePos' => 2329,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 108,
            'endColumn' => 119,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'sameSite' => 
          array (
            'name' => 'sameSite',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 318,
                'startFilePos' => 2344,
                'endTokenPos' => 318,
                'endFilePos' => 2347,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 122,
            'endColumn' => 137,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a cookie that lasts "forever" (400 days).
 *
 * @param  string  $name
 * @param  string  $value
 * @param  string|null  $path
 * @param  string|null  $domain
 * @param  bool|null  $secure
 * @param  bool  $httpOnly
 * @param  bool  $raw
 * @param  string|null  $sameSite
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie
 */',
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'forget' => 
      array (
        'name' => 'forget',
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
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 99,
                'endLine' => 99,
                'startTokenPos' => 374,
                'startFilePos' => 2719,
                'endTokenPos' => 374,
                'endFilePos' => 2722,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 35,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'domain' => 
          array (
            'name' => 'domain',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 99,
                'endLine' => 99,
                'startTokenPos' => 381,
                'startFilePos' => 2735,
                'endTokenPos' => 381,
                'endFilePos' => 2738,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 49,
            'endColumn' => 62,
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
 * Expire the given cookie.
 *
 * @param  string  $name
 * @param  string|null  $path
 * @param  string|null  $domain
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie
 */',
        'startLine' => 99,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'hasQueued' => 
      array (
        'name' => 'hasQueued',
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
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 31,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 111,
                'endLine' => 111,
                'startTokenPos' => 426,
                'startFilePos' => 3016,
                'endTokenPos' => 426,
                'endFilePos' => 3019,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 37,
            'endColumn' => 48,
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
 * Determine if a cookie has been queued.
 *
 * @param  string  $key
 * @param  string|null  $path
 * @return bool
 */',
        'startLine' => 111,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'queued' => 
      array (
        'name' => 'queued',
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
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 28,
            'endColumn' => 31,
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
                'startLine' => 124,
                'endLine' => 124,
                'startTokenPos' => 469,
                'startFilePos' => 3353,
                'endTokenPos' => 469,
                'endFilePos' => 3356,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 34,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 124,
                'endLine' => 124,
                'startTokenPos' => 476,
                'startFilePos' => 3367,
                'endTokenPos' => 476,
                'endFilePos' => 3370,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 51,
            'endColumn' => 62,
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
 * Get a queued cookie instance.
 *
 * @param  string  $key
 * @param  mixed  $default
 * @param  string|null  $path
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie|null
 */',
        'startLine' => 124,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'queue' => 
      array (
        'name' => 'queue',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 141,
            'endLine' => 141,
            'startColumn' => 27,
            'endColumn' => 40,
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
 * Queue a cookie to send with the next response.
 *
 * @param  mixed  ...$parameters
 * @return void
 */',
        'startLine' => 141,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'expire' => 
      array (
        'name' => 'expire',
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
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 164,
                'endLine' => 164,
                'startTokenPos' => 703,
                'startFilePos' => 4420,
                'endTokenPos' => 703,
                'endFilePos' => 4423,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 35,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'domain' => 
          array (
            'name' => 'domain',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 164,
                'endLine' => 164,
                'startTokenPos' => 710,
                'startFilePos' => 4436,
                'endTokenPos' => 710,
                'endFilePos' => 4439,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 49,
            'endColumn' => 62,
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
 * Queue a cookie to expire with the next response.
 *
 * @param  string  $name
 * @param  string|null  $path
 * @param  string|null  $domain
 * @return void
 */',
        'startLine' => 164,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'unqueue' => 
      array (
        'name' => 'unqueue',
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
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 29,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 176,
                'endLine' => 176,
                'startTokenPos' => 751,
                'startFilePos' => 4703,
                'endTokenPos' => 751,
                'endFilePos' => 4706,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 36,
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
 * Remove a cookie from the queue.
 *
 * @param  string  $name
 * @param  string|null  $path
 * @return void
 */',
        'startLine' => 176,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'getPathAndDomain' => 
      array (
        'name' => 'getPathAndDomain',
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
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'domain' => 
          array (
            'name' => 'domain',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 48,
            'endColumn' => 54,
            'parameterIndex' => 1,
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
                'startLine' => 200,
                'endLine' => 200,
                'startTokenPos' => 847,
                'startFilePos' => 5266,
                'endTokenPos' => 847,
                'endFilePos' => 5269,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'sameSite' => 
          array (
            'name' => 'sameSite',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 200,
                'endLine' => 200,
                'startTokenPos' => 854,
                'startFilePos' => 5284,
                'endTokenPos' => 854,
                'endFilePos' => 5287,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 73,
            'endColumn' => 88,
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
 * Get the path and domain, or the default values.
 *
 * @param  string  $path
 * @param  string|null  $domain
 * @param  bool|null  $secure
 * @param  string|null  $sameSite
 * @return array
 */',
        'startLine' => 200,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'setDefaultPathAndDomain' => 
      array (
        'name' => 'setDefaultPathAndDomain',
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
            'startLine' => 214,
            'endLine' => 214,
            'startColumn' => 45,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'domain' => 
          array (
            'name' => 'domain',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 214,
            'endLine' => 214,
            'startColumn' => 52,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'secure' => 
          array (
            'name' => 'secure',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 214,
                'endLine' => 214,
                'startTokenPos' => 929,
                'startFilePos' => 5745,
                'endTokenPos' => 929,
                'endFilePos' => 5749,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 214,
            'endLine' => 214,
            'startColumn' => 61,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'sameSite' => 
          array (
            'name' => 'sameSite',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 214,
                'endLine' => 214,
                'startTokenPos' => 936,
                'startFilePos' => 5764,
                'endTokenPos' => 936,
                'endFilePos' => 5767,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 214,
            'endLine' => 214,
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
 * Set the default path and domain for the jar.
 *
 * @param  string  $path
 * @param  string|null  $domain
 * @param  bool|null  $secure
 * @param  string|null  $sameSite
 * @return $this
 */',
        'startLine' => 214,
        'endLine' => 219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'getQueuedCookies' => 
      array (
        'name' => 'getQueuedCookies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the cookies which have been queued for the next request.
 *
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie[]
 */',
        'startLine' => 226,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
        'aliasName' => NULL,
      ),
      'flushQueuedCookies' => 
      array (
        'name' => 'flushQueuedCookies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the cookies which have been queued for the next request.
 *
 * @return $this
 */',
        'startLine' => 236,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Cookie',
        'declaringClassName' => 'Illuminate\\Cookie\\CookieJar',
        'implementingClassName' => 'Illuminate\\Cookie\\CookieJar',
        'currentClassName' => 'Illuminate\\Cookie\\CookieJar',
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