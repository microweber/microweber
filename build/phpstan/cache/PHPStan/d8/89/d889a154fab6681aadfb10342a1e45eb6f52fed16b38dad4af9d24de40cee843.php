<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Middleware/TrustHosts.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Http\Middleware\TrustHosts
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c3929bfbeefdc99b5a7b5068e79c85c3d83f6b364292e8e7d3f8543dc1601ce8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Http/Middleware/TrustHosts.php',
      ),
    ),
    'namespace' => 'Illuminate\\Http\\Middleware',
    'name' => 'Illuminate\\Http\\Middleware\\TrustHosts',
    'shortName' => 'TrustHosts',
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
    'endLine' => 128,
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
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
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
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'alwaysTrust' => 
      array (
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'name' => 'alwaysTrust',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The trusted hosts that have been configured to always be trusted.
 *
 * @var array<int, string>|(callable(): array<int, string>)|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'subdomains' => 
      array (
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'name' => 'subdomains',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates whether subdomains of the application URL should be trusted.
 *
 * @var bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 33,
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
            'startLine' => 37,
            'endLine' => 37,
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
        'startLine' => 37,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'aliasName' => NULL,
      ),
      'hosts' => 
      array (
        'name' => 'hosts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the host patterns that should be trusted.
 *
 * @return array
 */',
        'startLine' => 47,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
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
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 28,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'next' => 
          array (
            'name' => 'next',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 46,
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
 * Handle the incoming request.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @param  \\Closure  $next
 * @return \\Illuminate\\Http\\Response
 */',
        'startLine' => 73,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'aliasName' => NULL,
      ),
      'at' => 
      array (
        'name' => 'at',
        'parameters' => 
        array (
          'hosts' => 
          array (
            'name' => 'hosts',
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
                      'name' => 'callable',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 31,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subdomains' => 
          array (
            'name' => 'subdomains',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 89,
                'endLine' => 89,
                'startTokenPos' => 278,
                'startFilePos' => 2176,
                'endTokenPos' => 278,
                'endFilePos' => 2179,
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 54,
            'endColumn' => 76,
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
 * Specify the hosts that should always be trusted.
 *
 * @param  array<int, string>|(callable(): array<int, string>)  $hosts
 * @param  bool  $subdomains
 * @return void
 */',
        'startLine' => 89,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'aliasName' => NULL,
      ),
      'shouldSpecifyTrustedHosts' => 
      array (
        'name' => 'shouldSpecifyTrustedHosts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application should specify trusted hosts.
 *
 * @return bool
 */',
        'startLine' => 100,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'aliasName' => NULL,
      ),
      'allSubdomainsOfApplicationUrl' => 
      array (
        'name' => 'allSubdomainsOfApplicationUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a regular expression matching the application URL and all of its subdomains.
 *
 * @return string|null
 */',
        'startLine' => 111,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
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
        'startLine' => 123,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Http\\Middleware',
        'declaringClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'implementingClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
        'currentClassName' => 'Illuminate\\Http\\Middleware\\TrustHosts',
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