<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/EnableTwoFactorAuthentication.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Actions\EnableTwoFactorAuthentication
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-78167a5eec722db0f25ee4032fcc66e5f9eef823c3a8d646dac7650d88833658-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Actions/EnableTwoFactorAuthentication.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Actions',
    'name' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
    'shortName' => 'EnableTwoFactorAuthentication',
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
    'endLine' => 53,
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
      'provider' => 
      array (
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'name' => 'provider',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The two factor authentication provider.
 *
 * @var \\Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 24,
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
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 33,
            'endColumn' => 73,
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
 * Create a new action instance.
 *
 * @param  \\Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider  $provider
 * @return void
 */',
        'startLine' => 26,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'aliasName' => NULL,
      ),
      '__invoke' => 
      array (
        'name' => '__invoke',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 30,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'force' => 
          array (
            'name' => 'force',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 86,
                'startFilePos' => 957,
                'endTokenPos' => 86,
                'endFilePos' => 961,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 37,
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
 * Enable two factor authentication for the user.
 *
 * @param  mixed  $user
 * @param  bool  $force
 * @return void
 */',
        'startLine' => 38,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Actions',
        'declaringClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'implementingClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
        'currentClassName' => 'Laravel\\Fortify\\Actions\\EnableTwoFactorAuthentication',
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