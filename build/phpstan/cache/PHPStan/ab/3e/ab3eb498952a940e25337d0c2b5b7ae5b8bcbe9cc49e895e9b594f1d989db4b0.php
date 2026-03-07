<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Contracts/TwoFactorAuthenticationProvider.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4a797a662532751233cf2cd3716b30efdac7a1e160dcedada5efd167d283e013-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/Contracts/TwoFactorAuthenticationProvider.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify\\Contracts',
    'name' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
    'shortName' => 'TwoFactorAuthenticationProvider',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 32,
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
      'generateSecretKey' => 
      array (
        'name' => 'generateSecretKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate a new secret key.
 *
 * @return string
 */',
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Contracts',
        'declaringClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'implementingClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'currentClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'aliasName' => NULL,
      ),
      'qrCodeUrl' => 
      array (
        'name' => 'qrCodeUrl',
        'parameters' => 
        array (
          'companyName' => 
          array (
            'name' => 'companyName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 31,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'companyEmail' => 
          array (
            'name' => 'companyEmail',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'secret' => 
          array (
            'name' => 'secret',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 60,
            'endColumn' => 66,
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
 * Get the two factor authentication QR code URL.
 *
 * @param  string  $companyName
 * @param  string  $companyEmail
 * @param  string  $secret
 * @return string
 */',
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 68,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Contracts',
        'declaringClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'implementingClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'currentClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'aliasName' => NULL,
      ),
      'verify' => 
      array (
        'name' => 'verify',
        'parameters' => 
        array (
          'secret' => 
          array (
            'name' => 'secret',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 37,
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
 * Verify the given token.
 *
 * @param  string  $secret
 * @param  string  $code
 * @return bool
 */',
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify\\Contracts',
        'declaringClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'implementingClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
        'currentClassName' => 'Laravel\\Fortify\\Contracts\\TwoFactorAuthenticationProvider',
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