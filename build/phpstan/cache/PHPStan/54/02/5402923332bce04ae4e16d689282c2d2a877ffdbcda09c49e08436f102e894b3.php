<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/RSA.php-PHPStan\BetterReflection\Reflection\ReflectionClass-phpseclib3\Crypt\RSA
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5ce18b508fbd1c2db0dbeabb2dde706c8506841463239ae74d418fbc0beb4f62-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'phpseclib3\\Crypt\\RSA',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/RSA.php',
      ),
    ),
    'namespace' => 'phpseclib3\\Crypt',
    'name' => 'phpseclib3\\Crypt\\RSA',
    'shortName' => 'RSA',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Pure-PHP PKCS#1 compliant implementation of RSA.
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 69,
    'endLine' => 933,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ALGORITHM' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'ALGORITHM',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'RSA\'',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 76,
            'startTokenPos' => 66,
            'startFilePos' => 2172,
            'endTokenPos' => 66,
            'endFilePos' => 2176,
          ),
        ),
        'docComment' => '/**
 * Algorithm Name
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 28,
      ),
      'ENCRYPTION_OAEP' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'ENCRYPTION_OAEP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '1',
          'attributes' => 
          array (
            'startLine' => 89,
            'endLine' => 89,
            'startTokenPos' => 77,
            'startFilePos' => 2550,
            'endTokenPos' => 77,
            'endFilePos' => 2550,
          ),
        ),
        'docComment' => '/**
 * Use {@link http://en.wikipedia.org/wiki/Optimal_Asymmetric_Encryption_Padding Optimal Asymmetric Encryption Padding}
 * (OAEP) for encryption / decryption.
 *
 * Uses sha256 by default
 *
 * @see self::setHash()
 * @see self::setMGFHash()
 * @see self::encrypt()
 * @see self::decrypt()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 30,
      ),
      'ENCRYPTION_PKCS1' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'ENCRYPTION_PKCS1',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 88,
            'startFilePos' => 2923,
            'endTokenPos' => 88,
            'endFilePos' => 2923,
          ),
        ),
        'docComment' => '/**
 * Use PKCS#1 padding.
 *
 * Although self::PADDING_OAEP / self::PADDING_PSS  offers more security, including PKCS#1 padding is necessary for purposes of backwards
 * compatibility with protocols (like SSH-1) written before OAEP\'s introduction.
 *
 * @see self::encrypt()
 * @see self::decrypt()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'ENCRYPTION_NONE' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'ENCRYPTION_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '4',
          'attributes' => 
          array (
            'startLine' => 111,
            'endLine' => 111,
            'startTokenPos' => 99,
            'startFilePos' => 3290,
            'endTokenPos' => 99,
            'endFilePos' => 3290,
          ),
        ),
        'docComment' => '/**
 * Do not use any padding
 *
 * Although this method is not recommended it can none-the-less sometimes be useful if you\'re trying to decrypt some legacy
 * stuff, if you\'re trying to diagnose why an encrypted message isn\'t decrypting, etc.
 *
 * @see self::encrypt()
 * @see self::decrypt()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 30,
      ),
      'SIGNATURE_PSS' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'SIGNATURE_PSS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '16',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 110,
            'startFilePos' => 3625,
            'endTokenPos' => 110,
            'endFilePos' => 3626,
          ),
        ),
        'docComment' => '/**
 * Use the Probabilistic Signature Scheme for signing
 *
 * Uses sha256 and 0 as the salt length
 *
 * @see self::setSaltLength()
 * @see self::setMGFHash()
 * @see self::setHash()
 * @see self::sign()
 * @see self::verify()
 * @see self::setHash()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'SIGNATURE_RELAXED_PKCS1' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'SIGNATURE_RELAXED_PKCS1',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '32',
          'attributes' => 
          array (
            'startLine' => 134,
            'endLine' => 134,
            'startTokenPos' => 121,
            'startFilePos' => 3843,
            'endTokenPos' => 121,
            'endFilePos' => 3844,
          ),
        ),
        'docComment' => '/**
 * Use a relaxed version of PKCS#1 padding for signature verification
 *
 * @see self::sign()
 * @see self::verify()
 * @see self::setHash()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 134,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'SIGNATURE_PKCS1' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'SIGNATURE_PKCS1',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '64',
          'attributes' => 
          array (
            'startLine' => 143,
            'endLine' => 143,
            'startTokenPos' => 132,
            'startFilePos' => 4032,
            'endTokenPos' => 132,
            'endFilePos' => 4033,
          ),
        ),
        'docComment' => '/**
 * Use PKCS#1 padding for signature verification
 *
 * @see self::sign()
 * @see self::verify()
 * @see self::setHash()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 143,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
    ),
    'immediateProperties' => 
    array (
      'encryptionPadding' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'encryptionPadding',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'self::ENCRYPTION_OAEP',
          'attributes' => 
          array (
            'startLine' => 150,
            'endLine' => 150,
            'startTokenPos' => 143,
            'startFilePos' => 4142,
            'endTokenPos' => 145,
            'endFilePos' => 4162,
          ),
        ),
        'docComment' => '/**
 * Encryption padding mode
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 150,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 57,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'signaturePadding' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'signaturePadding',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'self::SIGNATURE_PSS',
          'attributes' => 
          array (
            'startLine' => 157,
            'endLine' => 157,
            'startTokenPos' => 156,
            'startFilePos' => 4269,
            'endTokenPos' => 158,
            'endFilePos' => 4287,
          ),
        ),
        'docComment' => '/**
 * Signature padding mode
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 157,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 54,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hLen' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'hLen',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Length of hash function output
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 164,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sLen' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'sLen',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Length of salt
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 171,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'label' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'label',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 178,
            'endLine' => 178,
            'startTokenPos' => 183,
            'startFilePos' => 4551,
            'endTokenPos' => 183,
            'endFilePos' => 4552,
          ),
        ),
        'docComment' => '/**
 * Label
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 178,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mgfHash' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'mgfHash',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Hash function for the Mask Generation Function
 *
 * @var Hash
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 185,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mgfHLen' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'mgfHLen',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Length of MGF hash function output
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 192,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'modulus' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'modulus',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Modulus (ie. n)
 *
 * @var Math\\BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 199,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'k' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'k',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Modulus length
 *
 * @var Math\\BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 206,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 17,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exponent' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'exponent',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Exponent (ie. e or d)
 *
 * @var Math\\BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 213,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultExponent' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'defaultExponent',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '65537',
          'attributes' => 
          array (
            'startLine' => 221,
            'endLine' => 221,
            'startTokenPos' => 231,
            'startFilePos' => 5247,
            'endTokenPos' => 231,
            'endFilePos' => 5251,
          ),
        ),
        'docComment' => '/**
 * Default public exponent
 *
 * @var int
 * @link http://en.wikipedia.org/wiki/65537_%28number%29
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 221,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'enableBlinding' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'enableBlinding',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 228,
            'endLine' => 228,
            'startTokenPos' => 244,
            'startFilePos' => 5358,
            'endTokenPos' => 244,
            'endFilePos' => 5361,
          ),
        ),
        'docComment' => '/**
 * Enable Blinding?
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 228,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'configFile' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'configFile',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * OpenSSL configuration file name.
 *
 * @see self::createKey()
 * @var ?string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 236,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'smallestPrime' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'smallestPrime',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '4096',
          'attributes' => 
          array (
            'startLine' => 250,
            'endLine' => 250,
            'startTokenPos' => 266,
            'startFilePos' => 6239,
            'endTokenPos' => 266,
            'endFilePos' => 6242,
          ),
        ),
        'docComment' => '/**
 * Smallest Prime
 *
 * Per <http://cseweb.ucsd.edu/~hovav/dist/survey.pdf#page=5>, this number ought not result in primes smaller
 * than 256 bits. As a consequence if the key you\'re trying to create is 1024 bits and you\'ve set smallestPrime
 * to 384 bits then you\'re going to get a 384 bit prime and a 640 bit prime (384 + 1024 % 384). At least if
 * engine is set to self::ENGINE_INTERNAL. If Engine is set to self::ENGINE_OPENSSL then smallest Prime is
 * ignored (ie. multi-prime RSA support is more intended as a way to speed up RSA key generation when there\'s
 * a chance neither gmp nor OpenSSL are installed)
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 250,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'publicExponent' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'name' => 'publicExponent',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Public Exponent
 *
 * @var Math\\BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 257,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 30,
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
      'setExponent' => 
      array (
        'name' => 'setExponent',
        'parameters' => 
        array (
          'val' => 
          array (
            'name' => 'val',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 266,
            'endLine' => 266,
            'startColumn' => 40,
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
 * Sets the public exponent for key generation
 *
 * This will be 65537 unless changed.
 *
 * @param int $val
 */',
        'startLine' => 266,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'setSmallestPrime' => 
      array (
        'name' => 'setSmallestPrime',
        'parameters' => 
        array (
          'val' => 
          array (
            'name' => 'val',
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
            'startColumn' => 45,
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
 * Sets the smallest prime number in bits. Used for key generation
 *
 * This will be 4096 unless changed.
 *
 * @param int $val
 */',
        'startLine' => 278,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'setOpenSSLConfigPath' => 
      array (
        'name' => 'setOpenSSLConfigPath',
        'parameters' => 
        array (
          'val' => 
          array (
            'name' => 'val',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 290,
            'endLine' => 290,
            'startColumn' => 49,
            'endColumn' => 52,
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
 * Sets the OpenSSL config file path
 *
 * Set to the empty string to use the default config file
 *
 * @param string $val
 */',
        'startLine' => 290,
        'endLine' => 293,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'createKey' => 
      array (
        'name' => 'createKey',
        'parameters' => 
        array (
          'bits' => 
          array (
            'name' => 'bits',
            'default' => 
            array (
              'code' => '2048',
              'attributes' => 
              array (
                'startLine' => 303,
                'endLine' => 303,
                'startTokenPos' => 368,
                'startFilePos' => 7333,
                'endTokenPos' => 368,
                'endFilePos' => 7336,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 38,
            'endColumn' => 49,
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
 * Create a private key
 *
 * The public key can be extracted from the private key
 *
 * @return PrivateKey
 * @param int $bits
 */',
        'startLine' => 303,
        'endLine' => 431,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'onLoad' => 
      array (
        'name' => 'onLoad',
        'parameters' => 
        array (
          'components' => 
          array (
            'name' => 'components',
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
            'startLine' => 438,
            'endLine' => 438,
            'startColumn' => 38,
            'endColumn' => 54,
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
 * OnLoad Handler
 *
 * @return bool
 */',
        'startLine' => 438,
        'endLine' => 479,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'initialize_static_variables' => 
      array (
        'name' => 'initialize_static_variables',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initialize static variables
 */',
        'startLine' => 484,
        'endLine' => 491,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Constructor
 *
 * PublicKey and PrivateKey objects can only be created from abstract RSA class
 */',
        'startLine' => 498,
        'endLine' => 505,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'i2osp' => 
      array (
        'name' => 'i2osp',
        'parameters' => 
        array (
          'x' => 
          array (
            'name' => 'x',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 516,
            'endLine' => 516,
            'startColumn' => 30,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'xLen' => 
          array (
            'name' => 'xLen',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 516,
            'endLine' => 516,
            'startColumn' => 34,
            'endColumn' => 38,
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
 * Integer-to-Octet-String primitive
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-4.1 RFC3447#section-4.1}.
 *
 * @param bool|Math\\BigInteger $x
 * @param int $xLen
 * @return bool|string
 */',
        'startLine' => 516,
        'endLine' => 526,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'os2ip' => 
      array (
        'name' => 'os2ip',
        'parameters' => 
        array (
          'x' => 
          array (
            'name' => 'x',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 536,
            'endLine' => 536,
            'startColumn' => 30,
            'endColumn' => 31,
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
 * Octet-String-to-Integer primitive
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-4.2 RFC3447#section-4.2}.
 *
 * @param string $x
 * @return Math\\BigInteger
 */',
        'startLine' => 536,
        'endLine' => 539,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'emsa_pkcs1_v1_5_encode' => 
      array (
        'name' => 'emsa_pkcs1_v1_5_encode',
        'parameters' => 
        array (
          'm' => 
          array (
            'name' => 'm',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 551,
            'endLine' => 551,
            'startColumn' => 47,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'emLen' => 
          array (
            'name' => 'emLen',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 551,
            'endLine' => 551,
            'startColumn' => 51,
            'endColumn' => 56,
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
 * EMSA-PKCS1-V1_5-ENCODE
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-9.2 RFC3447#section-9.2}.
 *
 * @param string $m
 * @param int $emLen
 * @throws \\LengthException if the intended encoded message length is too short
 * @return string
 */',
        'startLine' => 551,
        'endLine' => 597,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'emsa_pkcs1_v1_5_encode_without_null' => 
      array (
        'name' => 'emsa_pkcs1_v1_5_encode_without_null',
        'parameters' => 
        array (
          'm' => 
          array (
            'name' => 'm',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
            'startColumn' => 60,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'emLen' => 
          array (
            'name' => 'emLen',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
            'startColumn' => 64,
            'endColumn' => 69,
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
 * EMSA-PKCS1-V1_5-ENCODE (without NULL)
 *
 * Quoting https://tools.ietf.org/html/rfc8017#page-65,
 *
 * "The parameters field associated with id-sha1, id-sha224, id-sha256,
 *  id-sha384, id-sha512, id-sha512/224, and id-sha512/256 should
 *  generally be omitted, but if present, it shall have a value of type
 *  NULL"
 *
 * @param string $m
 * @param int $emLen
 * @return string
 */',
        'startLine' => 613,
        'endLine' => 656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'mgf1' => 
      array (
        'name' => 'mgf1',
        'parameters' => 
        array (
          'mgfSeed' => 
          array (
            'name' => 'mgfSeed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 667,
            'endLine' => 667,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'maskLen' => 
          array (
            'name' => 'maskLen',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 667,
            'endLine' => 667,
            'startColumn' => 39,
            'endColumn' => 46,
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
 * MGF1
 *
 * See {@link http://tools.ietf.org/html/rfc3447#appendix-B.2.1 RFC3447#appendix-B.2.1}.
 *
 * @param string $mgfSeed
 * @param int $maskLen
 * @return string
 */',
        'startLine' => 667,
        'endLine' => 679,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'getLength' => 
      array (
        'name' => 'getLength',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the key size
 *
 * More specifically, this returns the size of the modulo in bits.
 *
 * @return int
 */',
        'startLine' => 688,
        'endLine' => 691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'withHash' => 
      array (
        'name' => 'withHash',
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
            ),
            'startLine' => 701,
            'endLine' => 701,
            'startColumn' => 30,
            'endColumn' => 34,
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
 * Determines which hashing function should be used
 *
 * Used with signature production / verification and (if the encryption mode is self::PADDING_OAEP) encryption and
 * decryption.
 *
 * @param string $hash
 */',
        'startLine' => 701,
        'endLine' => 726,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'withMGFHash' => 
      array (
        'name' => 'withMGFHash',
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
            ),
            'startLine' => 736,
            'endLine' => 736,
            'startColumn' => 33,
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
 * Determines which hashing function should be used for the mask generation function
 *
 * The mask generation function is used by self::PADDING_OAEP and self::PADDING_PSS and although it\'s
 * best if Hash and MGFHash are set to the same thing this is not a requirement.
 *
 * @param string $hash
 */',
        'startLine' => 736,
        'endLine' => 761,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'getMGFHash' => 
      array (
        'name' => 'getMGFHash',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the MGF hash algorithm currently being used
 *
 */',
        'startLine' => 767,
        'endLine' => 770,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'withSaltLength' => 
      array (
        'name' => 'withSaltLength',
        'parameters' => 
        array (
          'sLen' => 
          array (
            'name' => 'sLen',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 784,
            'endLine' => 784,
            'startColumn' => 36,
            'endColumn' => 40,
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
 * Determines the salt length
 *
 * Used by RSA::PADDING_PSS
 *
 * To quote from {@link http://tools.ietf.org/html/rfc3447#page-38 RFC3447#page-38}:
 *
 *    Typical salt lengths in octets are hLen (the length of the output
 *    of the hash function Hash) and 0.
 *
 * @param int $sLen
 */',
        'startLine' => 784,
        'endLine' => 789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'getSaltLength' => 
      array (
        'name' => 'getSaltLength',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the salt length currently being used
 *
 */',
        'startLine' => 795,
        'endLine' => 798,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'withLabel' => 
      array (
        'name' => 'withLabel',
        'parameters' => 
        array (
          'label' => 
          array (
            'name' => 'label',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 814,
            'endLine' => 814,
            'startColumn' => 31,
            'endColumn' => 36,
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
 * Determines the label
 *
 * Used by RSA::PADDING_OAEP
 *
 * To quote from {@link http://tools.ietf.org/html/rfc3447#page-17 RFC3447#page-17}:
 *
 *    Both the encryption and the decryption operations of RSAES-OAEP take
 *    the value of a label L as input.  In this version of PKCS #1, L is
 *    the empty string; other uses of the label are outside the scope of
 *    this document.
 *
 * @param string $label
 */',
        'startLine' => 814,
        'endLine' => 819,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'getLabel' => 
      array (
        'name' => 'getLabel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the label currently being used
 *
 */',
        'startLine' => 825,
        'endLine' => 828,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'withPadding' => 
      array (
        'name' => 'withPadding',
        'parameters' => 
        array (
          'padding' => 
          array (
            'name' => 'padding',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 837,
            'endLine' => 837,
            'startColumn' => 33,
            'endColumn' => 40,
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
 * Determines the padding modes
 *
 * Example: $key->withPadding(RSA::ENCRYPTION_PKCS1 | RSA::SIGNATURE_PKCS1);
 *
 * @param int $padding
 */',
        'startLine' => 837,
        'endLine' => 883,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'getPadding' => 
      array (
        'name' => 'getPadding',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the padding currently being used
 *
 */',
        'startLine' => 889,
        'endLine' => 892,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'getEngine' => 
      array (
        'name' => 'getEngine',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the current engine being used
 *
 * OpenSSL is only used in this class (and it\'s subclasses) for key generation
 * Even then it depends on the parameters you\'re using. It\'s not used for
 * multi-prime RSA nor is it used if the key length is outside of the range
 * supported by OpenSSL
 *
 * @see self::useInternalEngine()
 * @see self::useBestEngine()
 * @return string
 */',
        'startLine' => 906,
        'endLine' => 914,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'enableBlinding' => 
      array (
        'name' => 'enableBlinding',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Enable RSA Blinding
 *
 */',
        'startLine' => 920,
        'endLine' => 923,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
        'aliasName' => NULL,
      ),
      'disableBlinding' => 
      array (
        'name' => 'disableBlinding',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Disable RSA Blinding
 *
 */',
        'startLine' => 929,
        'endLine' => 932,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA',
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