<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/RSA/PrivateKey.php-PHPStan\BetterReflection\Reflection\ReflectionClass-phpseclib3\Crypt\RSA\PrivateKey
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e7d030387a8233ca707e37f6b4fa8d3ffce41f6daacc78d1c33c71025a488b8f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/RSA/PrivateKey.php',
      ),
    ),
    'namespace' => 'phpseclib3\\Crypt\\RSA',
    'name' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
    'shortName' => 'PrivateKey',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Raw RSA Key Handler
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 530,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'phpseclib3\\Crypt\\RSA',
    'implementsClassNames' => 
    array (
      0 => 'phpseclib3\\Crypt\\Common\\PrivateKey',
    ),
    'traitClassNames' => 
    array (
      0 => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'primes' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'name' => 'primes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Primes for Chinese Remainder Theorem (ie. p and q)
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exponents' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'name' => 'exponents',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Exponents for Chinese Remainder Theorem (ie. dP and dQ)
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'coefficients' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'name' => 'coefficients',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Coefficients for Chinese Remainder Theorem (ie. qInv)
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'privateExponent' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'name' => 'privateExponent',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Private Exponent
 *
 * @var BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 31,
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
      'rsadp' => 
      array (
        'name' => 'rsadp',
        'parameters' => 
        array (
          'c' => 
          array (
            'name' => 'c',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'phpseclib3\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 28,
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
 * RSADP
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-5.1.2 RFC3447#section-5.1.2}.
 *
 * @return bool|BigInteger
 */',
        'startLine' => 65,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'rsasp1' => 
      array (
        'name' => 'rsasp1',
        'parameters' => 
        array (
          'm' => 
          array (
            'name' => 'm',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'phpseclib3\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 29,
            'endColumn' => 41,
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
 * RSASP1
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-5.2.1 RFC3447#section-5.2.1}.
 *
 * @return bool|BigInteger
 */',
        'startLine' => 80,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'exponentiate' => 
      array (
        'name' => 'exponentiate',
        'parameters' => 
        array (
          'x' => 
          array (
            'name' => 'x',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'phpseclib3\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 94,
            'endLine' => 94,
            'startColumn' => 37,
            'endColumn' => 49,
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
 * Exponentiate
 *
 * @param BigInteger $x
 * @return BigInteger
 */',
        'startLine' => 94,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'blind' => 
      array (
        'name' => 'blind',
        'parameters' => 
        array (
          'x' => 
          array (
            'name' => 'x',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'phpseclib3\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 28,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'r' => 
          array (
            'name' => 'r',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'phpseclib3\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 43,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'i' => 
          array (
            'name' => 'i',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
            'startColumn' => 58,
            'endColumn' => 59,
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
 * Performs RSA Blinding
 *
 * Protects against timing attacks by employing RSA Blinding.
 * Returns $x->modPow($this->exponents[$i], $this->primes[$i])
 *
 * @param BigInteger $x
 * @param BigInteger $r
 * @param int $i
 * @return BigInteger
 */',
        'startLine' => 177,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'emsa_pss_encode' => 
      array (
        'name' => 'emsa_pss_encode',
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
            'startLine' => 199,
            'endLine' => 199,
            'startColumn' => 38,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'emBits' => 
          array (
            'name' => 'emBits',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 199,
            'endLine' => 199,
            'startColumn' => 42,
            'endColumn' => 48,
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
 * EMSA-PSS-ENCODE
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-9.1.1 RFC3447#section-9.1.1}.
 *
 * @return string
 * @param string $m
 * @throws \\RuntimeException on encoding error
 * @param int $emBits
 */',
        'startLine' => 199,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'rsassa_pss_sign' => 
      array (
        'name' => 'rsassa_pss_sign',
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
            'startLine' => 233,
            'endLine' => 233,
            'startColumn' => 38,
            'endColumn' => 39,
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
 * RSASSA-PSS-SIGN
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-8.1.1 RFC3447#section-8.1.1}.
 *
 * @param string $m
 * @return bool|string
 */',
        'startLine' => 233,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'rsassa_pkcs1_v1_5_sign' => 
      array (
        'name' => 'rsassa_pkcs1_v1_5_sign',
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
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 45,
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
 * RSASSA-PKCS1-V1_5-SIGN
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-8.2.1 RFC3447#section-8.2.1}.
 *
 * @param string $m
 * @throws \\LengthException if the RSA modulus is too short
 * @return bool|string
 */',
        'startLine' => 259,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'sign' => 
      array (
        'name' => 'sign',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 26,
            'endColumn' => 33,
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
 * Create a signature
 *
 * @see self::verify()
 * @param string $message
 * @return string
 */',
        'startLine' => 289,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'rsaes_pkcs1_v1_5_decrypt' => 
      array (
        'name' => 'rsaes_pkcs1_v1_5_decrypt',
        'parameters' => 
        array (
          'c' => 
          array (
            'name' => 'c',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 309,
            'endLine' => 309,
            'startColumn' => 47,
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
 * RSAES-PKCS1-V1_5-DECRYPT
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-7.2.2 RFC3447#section-7.2.2}.
 *
 * @param string $c
 * @return bool|string
 */',
        'startLine' => 309,
        'endLine' => 339,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'rsaes_oaep_decrypt' => 
      array (
        'name' => 'rsaes_oaep_decrypt',
        'parameters' => 
        array (
          'c' => 
          array (
            'name' => 'c',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 358,
            'endLine' => 358,
            'startColumn' => 41,
            'endColumn' => 42,
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
 * RSAES-OAEP-DECRYPT
 *
 * See {@link http://tools.ietf.org/html/rfc3447#section-7.1.2 RFC3447#section-7.1.2}.  The fact that the error
 * messages aren\'t distinguishable from one another hinders debugging, but, to quote from RFC3447#section-7.1.2:
 *
 *    Note.  Care must be taken to ensure that an opponent cannot
 *    distinguish the different error conditions in Step 3.g, whether by
 *    error message or timing, or, more generally, learn partial
 *    information about the encoded message EM.  Otherwise an opponent may
 *    be able to obtain useful information about the decryption of the
 *    ciphertext C, leading to a chosen-ciphertext attack such as the one
 *    observed by Manger [36].
 *
 * @param string $c
 * @return bool|string
 */',
        'startLine' => 358,
        'endLine' => 406,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'raw_encrypt' => 
      array (
        'name' => 'raw_encrypt',
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
            'startLine' => 417,
            'endLine' => 417,
            'startColumn' => 34,
            'endColumn' => 35,
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
 * Raw Encryption / Decryption
 *
 * Doesn\'t use padding and is not recommended.
 *
 * @param string $m
 * @return bool|string
 * @throws \\LengthException if strlen($m) > $this->k
 */',
        'startLine' => 417,
        'endLine' => 426,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'decrypt' => 
      array (
        'name' => 'decrypt',
        'parameters' => 
        array (
          'ciphertext' => 
          array (
            'name' => 'ciphertext',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 435,
            'endLine' => 435,
            'startColumn' => 29,
            'endColumn' => 39,
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
 * Decryption
 *
 * @see self::encrypt()
 * @param string $ciphertext
 * @return bool|string
 */',
        'startLine' => 435,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'getPublicKey' => 
      array (
        'name' => 'getPublicKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the public key
 *
 * @return mixed
 */',
        'startLine' => 453,
        'endLine' => 467,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'aliasName' => NULL,
      ),
      'toString' => 
      array (
        'name' => 'toString',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 476,
            'endLine' => 476,
            'startColumn' => 30,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 476,
                'endLine' => 476,
                'startTokenPos' => 2752,
                'startFilePos' => 14008,
                'endTokenPos' => 2753,
                'endFilePos' => 14009,
              ),
            ),
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
            'startLine' => 476,
            'endLine' => 476,
            'startColumn' => 37,
            'endColumn' => 55,
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
 * Returns the private key
 *
 * @param string $type
 * @param array $options optional
 * @return string
 */',
        'startLine' => 476,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\RSA',
        'declaringClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
        'currentClassName' => 'phpseclib3\\Crypt\\RSA\\PrivateKey',
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