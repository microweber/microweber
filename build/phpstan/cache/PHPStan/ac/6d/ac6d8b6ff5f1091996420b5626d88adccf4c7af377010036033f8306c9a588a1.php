<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Admin/Http/Middleware/Admin.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Admin\Http\Middleware\Admin
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-71b5fa16efb2e6384cfc64890ce5186426e8f61baedc54ec3f5accdf81167195',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Admin/Http/Middleware/Admin.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Admin\\Http\\Middleware',
    'name' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
    'shortName' => 'Admin',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 129,
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
      'except' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'implementingClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'name' => 'except',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'admin.login.*\', \'admin.reset.*\']',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 25,
            'startTokenPos' => 74,
            'startFilePos' => 467,
            'endTokenPos' => 82,
            'endFilePos' => 523,
          ),
        ),
        'docComment' => '/**
 * The routes that should be excluded from verification.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 25,
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
            'startLine' => 34,
            'endLine' => 34,
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
            'startLine' => 34,
            'endLine' => 34,
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
 * @param \\Illuminate\\Http\\Request $request
 * @param \\Closure $next
 * @return \\Illuminate\\Http\\Response
 */',
        'startLine' => 34,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Admin\\Http\\Middleware',
        'declaringClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'implementingClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'currentClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'aliasName' => NULL,
      ),
      'requestIsInIframe' => 
      array (
        'name' => 'requestIsInIframe',
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
            'startLine' => 101,
            'endLine' => 101,
            'startColumn' => 40,
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
        'docComment' => NULL,
        'startLine' => 101,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Admin\\Http\\Middleware',
        'declaringClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'implementingClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'currentClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'aliasName' => NULL,
      ),
      'inExceptArray' => 
      array (
        'name' => 'inExceptArray',
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
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 38,
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
 * Determine if the request URI is in except array.
 *
 * @param \\Illuminate\\Http\\Request $request
 * @return bool
 */',
        'startLine' => 117,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Admin\\Http\\Middleware',
        'declaringClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'implementingClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
        'currentClassName' => 'MicroweberPackages\\Admin\\Http\\Middleware\\Admin',
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