<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Http\Middleware\TrustProxies
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-393cb34d743ade7f4bc95b4a2c7bff345c83933ee98e821f79976332b0d2e961-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php',
      ),
    ),
    'namespace' => 'Illuminate\\Http\\Middleware',
    'name' => 'Illuminate\\Http\\Middleware\\TrustProxies',
    'shortName' => 'TrustProxies',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 202,
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
      'proxies' => 
      array (
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'name' => 'proxies',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The trusted proxies for the application.
 *
 * @var array<int, string>|string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'headers' => 
      array (
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'name' => 'headers',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Illuminate\\Http\\Request::HEADER_X_FORWARDED_FOR | \\Illuminate\\Http\\Request::HEADER_X_FORWARDED_HOST | \\Illuminate\\Http\\Request::HEADER_X_FORWARDED_PORT | \\Illuminate\\Http\\Request::HEADER_X_FORWARDED_PROTO | \\Illuminate\\Http\\Request::HEADER_X_FORWARDED_PREFIX | \\Illuminate\\Http\\Request::HEADER_X_FORWARDED_AWS_ELB',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 27,
            'startTokenPos' => 38,
            'startFilePos' => 369,
            'endTokenPos' => 70,
            'endFilePos' => 620,
          ),
        ),
        'docComment' => '/**
 * The trusted proxies headers for the application.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'alwaysTrustProxies' => 
      array (
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'name' => 'alwaysTrustProxies',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The proxies that have been configured to always be trusted.
 *
 * @var array<int, string>|string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'alwaysTrustHeaders' => 
      array (
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'name' => 'alwaysTrustHeaders',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The proxies headers that have been configured to always be trusted.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 41,
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
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 28,
            'endColumn' => 43,
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
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 46,
            'endColumn' => 58,
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
 */',
        'startLine' => 52,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'setTrustedProxyIpAddresses' => 
      array (
        'name' => 'setTrustedProxyIpAddresses',
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 51,
            'endColumn' => 66,
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
 * Sets the trusted proxies on the request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return void
 */',
        'startLine' => 67,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'setTrustedProxyIpAddressesToSpecificIps' => 
      array (
        'name' => 'setTrustedProxyIpAddressesToSpecificIps',
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
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 64,
            'endColumn' => 79,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'trustedIps' => 
          array (
            'name' => 'trustedIps',
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
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 82,
            'endColumn' => 98,
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
 * Specify the IP addresses to trust explicitly.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  array  $trustedIps
 * @return void
 */',
        'startLine' => 103,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'setTrustedProxyIpAddressesToTheCallingIp' => 
      array (
        'name' => 'setTrustedProxyIpAddressesToTheCallingIp',
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
            'startLine' => 120,
            'endLine' => 120,
            'startColumn' => 65,
            'endColumn' => 80,
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
 * Set the trusted proxy to be the IP address calling this servers.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return void
 */',
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'getTrustedHeaderNames' => 
      array (
        'name' => 'getTrustedHeaderNames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve trusted header name(s), falling back to defaults if config not set.
 *
 * @return int A bit field of Request::HEADER_*, to set which headers to trust from your proxies.
 */',
        'startLine' => 130,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'headers' => 
      array (
        'name' => 'headers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the trusted headers.
 *
 * @return int
 */',
        'startLine' => 155,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'proxies' => 
      array (
        'name' => 'proxies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the trusted proxies.
 *
 * @return array|string|null
 */',
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'at' => 
      array (
        'name' => 'at',
        'parameters' => 
        array (
          'proxies' => 
          array (
            'name' => 'proxies',
            'default' => NULL,
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 31,
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
 * Specify the IP addresses of proxies that should always be trusted.
 *
 * @param  array|string  $proxies
 * @return void
 */',
        'startLine' => 176,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'aliasName' => NULL,
      ),
      'withHeaders' => 
      array (
        'name' => 'withHeaders',
        'parameters' => 
        array (
          'headers' => 
          array (
            'name' => 'headers',
            'default' => NULL,
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
            'startLine' => 187,
            'endLine' => 187,
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
 * Specify the proxy headers that should always be trusted.
 *
 * @param  int  $headers
 * @return void
 */',
        'startLine' => 187,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
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
        'startLine' => 197,
        'endLine' => 201,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustProxies',
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