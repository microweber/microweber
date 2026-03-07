<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../pragmarx/google2fa/src/Google2FA.php-PHPStan\BetterReflection\Reflection\ReflectionClass-PragmaRX\Google2FA\Google2FA
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-bc43c55a11753f353d239a1dc0181ab63856956b995615132c49fd0cb72686bf-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'PragmaRX\\Google2FA\\Google2FA',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../pragmarx/google2fa/src/Google2FA.php',
      ),
    ),
    'namespace' => 'PragmaRX\\Google2FA',
    'name' => 'PragmaRX\\Google2FA\\Google2FA',
    'shortName' => 'Google2FA',
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
    'endLine' => 517,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'PragmaRX\\Google2FA\\Support\\QRCode',
      1 => 'PragmaRX\\Google2FA\\Support\\Base32',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'algorithm' => 
      array (
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'name' => 'algorithm',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\PragmaRX\\Google2FA\\Support\\Constants::SHA1',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 56,
            'startFilePos' => 420,
            'endTokenPos' => 58,
            'endFilePos' => 434,
          ),
        ),
        'docComment' => '/**
 * Algorithm.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'oneTimePasswordLength' => 
      array (
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'name' => 'oneTimePasswordLength',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '6',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 69,
            'startFilePos' => 554,
            'endTokenPos' => 69,
            'endFilePos' => 554,
          ),
        ),
        'docComment' => '/**
 * Length of the Token generated.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'keyRegeneration' => 
      array (
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'name' => 'keyRegeneration',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '30',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 80,
            'startFilePos' => 672,
            'endTokenPos' => 80,
            'endFilePos' => 673,
          ),
        ),
        'docComment' => '/**
 * Interval between key regeneration.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'secret' => 
      array (
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'name' => 'secret',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Secret.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'window' => 
      array (
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'name' => 'window',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 98,
            'startFilePos' => 836,
            'endTokenPos' => 98,
            'endFilePos' => 836,
          ),
        ),
        'docComment' => '/**
 * Window.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 26,
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
      'findValidOTP' => 
      array (
        'name' => 'findValidOTP',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 68,
            'endLine' => 69,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 70,
            'endLine' => 71,
            'startColumn' => 9,
            'endColumn' => 12,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'window' => 
          array (
            'name' => 'window',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'startingTimestamp' => 
          array (
            'name' => 'startingTimestamp',
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
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 9,
            'endColumn' => 18,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'oldTimestamp' => 
          array (
            'name' => 'oldTimestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 75,
                'endLine' => 75,
                'startTokenPos' => 139,
                'startFilePos' => 1633,
                'endTokenPos' => 139,
                'endFilePos' => 1636,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 9,
            'endColumn' => 28,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Find a valid One Time Password.
 *
 * @param string   $secret
 * @param string   $key
 * @param int|null $window
 * @param int      $startingTimestamp
 * @param int      $timestamp
 * @param int|null $oldTimestamp
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\IncompatibleWithGoogleAuthenticatorException
 *
 * @return bool|int
 */',
        'startLine' => 67,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'generateHotp' => 
      array (
        'name' => 'generateHotp',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 102,
            'endLine' => 103,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'counter' => 
          array (
            'name' => 'counter',
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
            'startColumn' => 9,
            'endColumn' => 16,
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
 * Generate the HMAC OTP.
 *
 * @param string $secret
 * @param int    $counter
 *
 * @return string
 */',
        'startLine' => 101,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'generateSecretKey' => 
      array (
        'name' => 'generateSecretKey',
        'parameters' => 
        array (
          'length' => 
          array (
            'name' => 'length',
            'default' => 
            array (
              'code' => '32',
              'attributes' => 
              array (
                'startLine' => 126,
                'endLine' => 126,
                'startTokenPos' => 296,
                'startFilePos' => 2961,
                'endTokenPos' => 296,
                'endFilePos' => 2962,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 126,
            'endLine' => 126,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 126,
                'endLine' => 126,
                'startTokenPos' => 303,
                'startFilePos' => 2975,
                'endTokenPos' => 303,
                'endFilePos' => 2976,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 126,
            'endLine' => 126,
            'startColumn' => 53,
            'endColumn' => 64,
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
 * Generate a digit secret key in base32 format.
 *
 * @param int    $length
 * @param string $prefix
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\IncompatibleWithGoogleAuthenticatorException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 *
 * @return string
 */',
        'startLine' => 126,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getCurrentOtp' => 
      array (
        'name' => 'getCurrentOtp',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 143,
            'endLine' => 144,
            'startColumn' => 9,
            'endColumn' => 15,
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
 * Get the current one time password for a key.
 *
 * @param string $secret
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\IncompatibleWithGoogleAuthenticatorException
 *
 * @return string
 */',
        'startLine' => 142,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getAlgorithm' => 
      array (
        'name' => 'getAlgorithm',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the HMAC algorithm.
 *
 * @return string
 */',
        'startLine' => 154,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getKeyRegeneration' => 
      array (
        'name' => 'getKeyRegeneration',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get key regeneration.
 *
 * @return int
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
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getOneTimePasswordLength' => 
      array (
        'name' => 'getOneTimePasswordLength',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get OTP length.
 *
 * @return int
 */',
        'startLine' => 174,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getSecret' => 
      array (
        'name' => 'getSecret',
        'parameters' => 
        array (
          'secret' => 
          array (
            'name' => 'secret',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 188,
                'endLine' => 188,
                'startTokenPos' => 441,
                'startFilePos' => 4271,
                'endTokenPos' => 441,
                'endFilePos' => 4274,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 187,
            'endLine' => 188,
            'startColumn' => 9,
            'endColumn' => 22,
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
 * Get secret.
 *
 * @param string|null $secret
 *
 * @return string
 */',
        'startLine' => 186,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getTimestamp' => 
      array (
        'name' => 'getTimestamp',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the current Unix Timestamp divided by the $keyRegeneration
 * period.
 *
 * @return int
 **/',
        'startLine' => 199,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getValidAlgorithms' => 
      array (
        'name' => 'getValidAlgorithms',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a list of valid HMAC algorithms.
 *
 * @return array
 */',
        'startLine' => 209,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'getWindow' => 
      array (
        'name' => 'getWindow',
        'parameters' => 
        array (
          'window' => 
          array (
            'name' => 'window',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 225,
                'endLine' => 225,
                'startTokenPos' => 548,
                'startFilePos' => 5011,
                'endTokenPos' => 548,
                'endFilePos' => 5014,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 31,
            'endColumn' => 44,
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
 * Get the OTP window.
 *
 * @param null|int $window
 *
 * @return int
 */',
        'startLine' => 225,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'makeStartingTimestamp' => 
      array (
        'name' => 'makeStartingTimestamp',
        'parameters' => 
        array (
          'window' => 
          array (
            'name' => 'window',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 44,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 53,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'oldTimestamp' => 
          array (
            'name' => 'oldTimestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 239,
                'endLine' => 239,
                'startTokenPos' => 591,
                'startFilePos' => 5369,
                'endTokenPos' => 591,
                'endFilePos' => 5372,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 65,
            'endColumn' => 84,
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
 * Make a window based starting timestamp.
 *
 * @param int|null $window
 * @param int      $timestamp
 * @param int|null $oldTimestamp
 *
 * @return mixed
 */',
        'startLine' => 239,
        'endLine' => 244,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'makeTimestamp' => 
      array (
        'name' => 'makeTimestamp',
        'parameters' => 
        array (
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 253,
                'endLine' => 253,
                'startTokenPos' => 654,
                'startFilePos' => 5755,
                'endTokenPos' => 654,
                'endFilePos' => 5758,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 253,
            'endLine' => 253,
            'startColumn' => 38,
            'endColumn' => 54,
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
 * Get/use a starting timestamp for key verification.
 *
 * @param string|int|null $timestamp
 *
 * @return int
 */',
        'startLine' => 253,
        'endLine' => 260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'oathTotp' => 
      array (
        'name' => 'oathTotp',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 276,
            'endLine' => 277,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'counter' => 
          array (
            'name' => 'counter',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 278,
            'endLine' => 278,
            'startColumn' => 9,
            'endColumn' => 16,
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
 * Takes the secret key and the timestamp and returns the one time
 * password.
 *
 * @param string $secret  Secret key in binary form.
 * @param int    $counter Timestamp as returned by getTimestamp.
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws Exceptions\\IncompatibleWithGoogleAuthenticatorException
 *
 * @return string
 */',
        'startLine' => 275,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'oathTruncate' => 
      array (
        'name' => 'oathTruncate',
        'parameters' => 
        array (
          'hash' => 
          array (
            'name' => 'hash',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 302,
            'endLine' => 303,
            'startColumn' => 9,
            'endColumn' => 13,
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
 * Extracts the OTP from the SHA1 hash.
 *
 * @param string $hash
 *
 * @return string
 **/',
        'startLine' => 301,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'removeInvalidChars' => 
      array (
        'name' => 'removeInvalidChars',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 324,
            'endLine' => 324,
            'startColumn' => 40,
            'endColumn' => 46,
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
 * Remove invalid chars from a base 32 string.
 *
 * @param string $string
 *
 * @return string|null
 */',
        'startLine' => 324,
        'endLine' => 331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'setEnforceGoogleAuthenticatorCompatibility' => 
      array (
        'name' => 'setEnforceGoogleAuthenticatorCompatibility',
        'parameters' => 
        array (
          'enforceGoogleAuthenticatorCompatibility' => 
          array (
            'name' => 'enforceGoogleAuthenticatorCompatibility',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 341,
            'endLine' => 341,
            'startColumn' => 9,
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
 * Setter for the enforce Google Authenticator compatibility property.
 *
 * @param mixed $enforceGoogleAuthenticatorCompatibility
 *
 * @return $this
 */',
        'startLine' => 340,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'setAlgorithm' => 
      array (
        'name' => 'setAlgorithm',
        'parameters' => 
        array (
          'algorithm' => 
          array (
            'name' => 'algorithm',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 357,
            'endLine' => 357,
            'startColumn' => 34,
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
 * Set the HMAC hashing algorithm.
 *
 * @param mixed $algorithm
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidAlgorithmException
 *
 * @return \\PragmaRX\\Google2FA\\Google2FA
 */',
        'startLine' => 357,
        'endLine' => 367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'setKeyRegeneration' => 
      array (
        'name' => 'setKeyRegeneration',
        'parameters' => 
        array (
          'keyRegeneration' => 
          array (
            'name' => 'keyRegeneration',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 374,
            'endLine' => 374,
            'startColumn' => 40,
            'endColumn' => 55,
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
 * Set key regeneration.
 *
 * @param mixed $keyRegeneration
 */',
        'startLine' => 374,
        'endLine' => 377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'setOneTimePasswordLength' => 
      array (
        'name' => 'setOneTimePasswordLength',
        'parameters' => 
        array (
          'oneTimePasswordLength' => 
          array (
            'name' => 'oneTimePasswordLength',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 384,
            'endLine' => 384,
            'startColumn' => 46,
            'endColumn' => 67,
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
 * Set OTP length.
 *
 * @param mixed $oneTimePasswordLength
 */',
        'startLine' => 384,
        'endLine' => 387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'setSecret' => 
      array (
        'name' => 'setSecret',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 395,
            'endLine' => 396,
            'startColumn' => 9,
            'endColumn' => 15,
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
 * Set secret.
 *
 * @param mixed $secret
 */',
        'startLine' => 394,
        'endLine' => 399,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'setWindow' => 
      array (
        'name' => 'setWindow',
        'parameters' => 
        array (
          'window' => 
          array (
            'name' => 'window',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 406,
            'endLine' => 406,
            'startColumn' => 31,
            'endColumn' => 37,
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
 * Set the OTP window.
 *
 * @param mixed $window
 */',
        'startLine' => 406,
        'endLine' => 409,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'verify' => 
      array (
        'name' => 'verify',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 428,
            'endLine' => 429,
            'startColumn' => 9,
            'endColumn' => 12,
            'parameterIndex' => 0,
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 430,
            'endLine' => 431,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'window' => 
          array (
            'name' => 'window',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 432,
                'endLine' => 432,
                'startTokenPos' => 1152,
                'startFilePos' => 10164,
                'endTokenPos' => 1152,
                'endFilePos' => 10167,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 432,
            'endLine' => 432,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 433,
                'endLine' => 433,
                'startTokenPos' => 1159,
                'startFilePos' => 10191,
                'endTokenPos' => 1159,
                'endFilePos' => 10194,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 433,
            'endLine' => 433,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'oldTimestamp' => 
          array (
            'name' => 'oldTimestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 434,
                'endLine' => 434,
                'startTokenPos' => 1166,
                'startFilePos' => 10221,
                'endTokenPos' => 1166,
                'endFilePos' => 10224,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 434,
            'endLine' => 434,
            'startColumn' => 9,
            'endColumn' => 28,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Verifies a user inputted key against the current timestamp. Checks $window
 * keys either side of the timestamp.
 *
 * @param string   $key          User specified key
 * @param string   $secret
 * @param null|int $window
 * @param null|int $timestamp
 * @param null|int $oldTimestamp
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\IncompatibleWithGoogleAuthenticatorException
 *
 * @return bool|int
 */',
        'startLine' => 427,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'verifyKey' => 
      array (
        'name' => 'verifyKey',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 462,
            'endLine' => 463,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 464,
            'endLine' => 465,
            'startColumn' => 9,
            'endColumn' => 12,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'window' => 
          array (
            'name' => 'window',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 466,
                'endLine' => 466,
                'startTokenPos' => 1225,
                'startFilePos' => 11148,
                'endTokenPos' => 1225,
                'endFilePos' => 11151,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 467,
                'endLine' => 467,
                'startTokenPos' => 1232,
                'startFilePos' => 11175,
                'endTokenPos' => 1232,
                'endFilePos' => 11178,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'oldTimestamp' => 
          array (
            'name' => 'oldTimestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 468,
                'endLine' => 468,
                'startTokenPos' => 1239,
                'startFilePos' => 11205,
                'endTokenPos' => 1239,
                'endFilePos' => 11208,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 468,
            'endLine' => 468,
            'startColumn' => 9,
            'endColumn' => 28,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Verifies a user inputted key against the current timestamp. Checks $window
 * keys either side of the timestamp.
 *
 * @param string   $secret
 * @param string   $key          User specified key
 * @param int|null $window
 * @param null|int $timestamp
 * @param null|int $oldTimestamp
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\IncompatibleWithGoogleAuthenticatorException
 *
 * @return bool|int
 */',
        'startLine' => 461,
        'endLine' => 480,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'aliasName' => NULL,
      ),
      'verifyKeyNewer' => 
      array (
        'name' => 'verifyKeyNewer',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 501,
            'endLine' => 502,
            'startColumn' => 9,
            'endColumn' => 15,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 503,
            'endLine' => 504,
            'startColumn' => 9,
            'endColumn' => 12,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'oldTimestamp' => 
          array (
            'name' => 'oldTimestamp',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 9,
            'endColumn' => 21,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'window' => 
          array (
            'name' => 'window',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 506,
                'endLine' => 506,
                'startTokenPos' => 1327,
                'startFilePos' => 12490,
                'endTokenPos' => 1327,
                'endFilePos' => 12493,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 506,
            'endLine' => 506,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'timestamp' => 
          array (
            'name' => 'timestamp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 507,
                'endLine' => 507,
                'startTokenPos' => 1334,
                'startFilePos' => 12517,
                'endTokenPos' => 1334,
                'endFilePos' => 12520,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 507,
            'endLine' => 507,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Verifies a user inputted key against the current timestamp. Checks $window
 * keys either side of the timestamp, but ensures that the given key is newer than
 * the given oldTimestamp. Useful if you need to ensure that a single key cannot
 * be used twice.
 *
 * @param string   $secret
 * @param string   $key          User specified key
 * @param int|null $oldTimestamp The timestamp from the last verified key
 * @param int|null $window
 * @param int|null $timestamp
 *
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\SecretKeyTooShortException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\InvalidCharactersException
 * @throws \\PragmaRX\\Google2FA\\Exceptions\\IncompatibleWithGoogleAuthenticatorException
 *
 * @return bool|int
 */',
        'startLine' => 500,
        'endLine' => 516,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'PragmaRX\\Google2FA',
        'declaringClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'implementingClassName' => 'PragmaRX\\Google2FA\\Google2FA',
        'currentClassName' => 'PragmaRX\\Google2FA\\Google2FA',
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