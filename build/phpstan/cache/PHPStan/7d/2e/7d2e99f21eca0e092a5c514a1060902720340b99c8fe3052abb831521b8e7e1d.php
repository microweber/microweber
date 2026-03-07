<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/TwoFactorAuthenticatable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Fortify\TwoFactorAuthenticatable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-dbea0fc7b4c502b6188e18ef7885fff666f0a32872627ff0a1ff16c98b0ab8bd-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/fortify/src/TwoFactorAuthenticatable.php',
      ),
    ),
    'namespace' => 'Laravel\\Fortify',
    'name' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
    'shortName' => 'TwoFactorAuthenticatable',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 90,
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
      'hasEnabledTwoFactorAuthentication' => 
      array (
        'name' => 'hasEnabledTwoFactorAuthentication',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if two-factor authentication has been enabled.
 *
 * @return bool
 */',
        'startLine' => 21,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify',
        'declaringClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'aliasName' => NULL,
      ),
      'recoveryCodes' => 
      array (
        'name' => 'recoveryCodes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the user\'s two factor authentication recovery codes.
 *
 * @return array
 */',
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify',
        'declaringClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'aliasName' => NULL,
      ),
      'replaceRecoveryCode' => 
      array (
        'name' => 'replaceRecoveryCode',
        'parameters' => 
        array (
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
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 41,
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
 * Replace the given recovery code with a new one in the user\'s stored codes.
 *
 * @param  string  $code
 * @return void
 */',
        'startLine' => 47,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify',
        'declaringClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'aliasName' => NULL,
      ),
      'twoFactorQrCodeSvg' => 
      array (
        'name' => 'twoFactorQrCodeSvg',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the QR code SVG of the user\'s two factor authentication QR code URL.
 *
 * @return string
 */',
        'startLine' => 65,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify',
        'declaringClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'aliasName' => NULL,
      ),
      'twoFactorQrCodeUrl' => 
      array (
        'name' => 'twoFactorQrCodeUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the two factor authentication QR code URL.
 *
 * @return string
 */',
        'startLine' => 82,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Fortify',
        'declaringClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'implementingClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
        'currentClassName' => 'Laravel\\Fortify\\TwoFactorAuthenticatable',
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