<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/Common/AsymmetricKey.php-PHPStan\BetterReflection\Reflection\ReflectionClass-phpseclib3\Crypt\Common\AsymmetricKey
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cdc6c405c88a63e684d266679c229a7f40010be285ec818c21e75d738c629d6c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/Common/AsymmetricKey.php',
      ),
    ),
    'namespace' => 'phpseclib3\\Crypt\\Common',
    'name' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
    'shortName' => 'AsymmetricKey',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Base Class for all asymmetric cipher classes
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 581,
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
      'zero' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'zero',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Precomputed Zero
 *
 * @var BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'one' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'one',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Precomputed One
 *
 * @var BigInteger
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'format' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'format',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Format of the loaded key
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hash' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'hash',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Hash function
 *
 * @var Hash
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hmac' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'hmac',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * HMAC function
 *
 * @var Hash
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 18,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'plugins' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'plugins',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 71,
            'endLine' => 71,
            'startTokenPos' => 98,
            'startFilePos' => 1292,
            'endTokenPos' => 99,
            'endFilePos' => 1293,
          ),
        ),
        'docComment' => '/**
 * Supported plugins (lower case)
 *
 * @see self::initialize_static_variables()
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 71,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'invisiblePlugins' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'invisiblePlugins',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 112,
            'startFilePos' => 1450,
            'endTokenPos' => 113,
            'endFilePos' => 1451,
          ),
        ),
        'docComment' => '/**
 * Invisible plugins
 *
 * @see self::initialize_static_variables()
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'engines' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'engines',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 126,
            'startFilePos' => 1557,
            'endTokenPos' => 127,
            'endFilePos' => 1558,
          ),
        ),
        'docComment' => '/**
 * Available Engines
 *
 * @var boolean[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'comment' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'name' => 'comment',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Key Comment
 *
 * @var null|string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 21,
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
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 39,
            'endColumn' => 43,
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
                'startLine' => 99,
                'endLine' => 99,
                'startTokenPos' => 156,
                'startFilePos' => 1784,
                'endTokenPos' => 157,
                'endFilePos' => 1785,
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
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 46,
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
 * @param string $type
 * @return array|string
 */',
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 66,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
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
 * The constructor
 */',
        'startLine' => 104,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
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
        'startLine' => 115,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'load' => 
      array (
        'name' => 'load',
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
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 33,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'password' => 
          array (
            'name' => 'password',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 135,
                'endLine' => 135,
                'startTokenPos' => 329,
                'startFilePos' => 2623,
                'endTokenPos' => 329,
                'endFilePos' => 2627,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 39,
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
 * Load the key
 *
 * @param string $key
 * @param string $password optional
 * @return PublicKey|PrivateKey
 */',
        'startLine' => 135,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadPrivateKey' => 
      array (
        'name' => 'loadPrivateKey',
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
            'startLine' => 181,
            'endLine' => 181,
            'startColumn' => 43,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'password' => 
          array (
            'name' => 'password',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 181,
                'endLine' => 181,
                'startTokenPos' => 673,
                'startFilePos' => 4188,
                'endTokenPos' => 673,
                'endFilePos' => 4189,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 181,
            'endLine' => 181,
            'startColumn' => 49,
            'endColumn' => 62,
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
 * Loads a private key
 *
 * @return PrivateKey
 * @param string|array $key
 * @param string $password optional
 */',
        'startLine' => 181,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadPublicKey' => 
      array (
        'name' => 'loadPublicKey',
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
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 42,
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
 * Loads a public key
 *
 * @return PublicKey
 * @param string|array $key
 */',
        'startLine' => 196,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadParameters' => 
      array (
        'name' => 'loadParameters',
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
            'startLine' => 211,
            'endLine' => 211,
            'startColumn' => 43,
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
 * Loads parameters
 *
 * @return AsymmetricKey
 * @param string|array $key
 */',
        'startLine' => 211,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadFormat' => 
      array (
        'name' => 'loadFormat',
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
            'startLine' => 228,
            'endLine' => 228,
            'startColumn' => 39,
            'endColumn' => 43,
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
            ),
            'startLine' => 228,
            'endLine' => 228,
            'startColumn' => 46,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'password' => 
          array (
            'name' => 'password',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 228,
                'endLine' => 228,
                'startTokenPos' => 872,
                'startFilePos' => 5426,
                'endTokenPos' => 872,
                'endFilePos' => 5430,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 228,
            'endLine' => 228,
            'startColumn' => 52,
            'endColumn' => 68,
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
 * Load the key, assuming a specific format
 *
 * @param string $type
 * @param string $key
 * @param string $password optional
 * @return static
 */',
        'startLine' => 228,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadPrivateKeyFormat' => 
      array (
        'name' => 'loadPrivateKeyFormat',
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
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 49,
            'endColumn' => 53,
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
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 56,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'password' => 
          array (
            'name' => 'password',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 261,
                'endLine' => 261,
                'startTokenPos' => 1088,
                'startFilePos' => 6445,
                'endTokenPos' => 1088,
                'endFilePos' => 6449,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 62,
            'endColumn' => 78,
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
 * Loads a private key
 *
 * @return PrivateKey
 * @param string $type
 * @param string $key
 * @param string $password optional
 */',
        'startLine' => 261,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadPublicKeyFormat' => 
      array (
        'name' => 'loadPublicKeyFormat',
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
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 48,
            'endColumn' => 52,
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
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 55,
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
 * Loads a public key
 *
 * @return PublicKey
 * @param string $type
 * @param string $key
 */',
        'startLine' => 277,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadParametersFormat' => 
      array (
        'name' => 'loadParametersFormat',
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
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 49,
            'endColumn' => 53,
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
            ),
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 56,
            'endColumn' => 59,
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
 * Loads parameters
 *
 * @return AsymmetricKey
 * @param string $type
 * @param string|array $key
 */',
        'startLine' => 293,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'validatePlugin' => 
      array (
        'name' => 'validatePlugin',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 310,
            'endLine' => 310,
            'startColumn' => 46,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 310,
            'endLine' => 310,
            'startColumn' => 55,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'method' => 
          array (
            'name' => 'method',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 310,
                'endLine' => 310,
                'startTokenPos' => 1302,
                'startFilePos' => 7782,
                'endTokenPos' => 1302,
                'endFilePos' => 7785,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 310,
            'endLine' => 310,
            'startColumn' => 62,
            'endColumn' => 75,
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
 * Validate Plugin
 *
 * @param string $format
 * @param string $type
 * @param string $method optional
 * @return mixed
 */',
        'startLine' => 310,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'loadPlugins' => 
      array (
        'name' => 'loadPlugins',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 329,
            'endLine' => 329,
            'startColumn' => 41,
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
        'docComment' => '/**
 * Load Plugins
 *
 * @param string $format
 */',
        'startLine' => 329,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'getSupportedKeyFormats' => 
      array (
        'name' => 'getSupportedKeyFormats',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a list of supported formats.
 *
 * @return array
 */',
        'startLine' => 359,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'addFileFormat' => 
      array (
        'name' => 'addFileFormat',
        'parameters' => 
        array (
          'fullname' => 
          array (
            'name' => 'fullname',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 376,
            'endLine' => 376,
            'startColumn' => 42,
            'endColumn' => 50,
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
 * Add a fileformat plugin
 *
 * The plugin needs to either already be loaded or be auto-loadable.
 * Loading a plugin whose shortname overwrite an existing shortname will overwrite the old plugin.
 *
 * @see self::load()
 * @param string $fullname
 * @return bool
 */',
        'startLine' => 376,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'getLoadedFormat' => 
      array (
        'name' => 'getLoadedFormat',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the format of the loaded key.
 *
 * If the key that was loaded wasn\'t in a valid or if the key was auto-generated
 * with RSA::createKey() then this will throw an exception.
 *
 * @see self::load()
 * @return mixed
 */',
        'startLine' => 399,
        'endLine' => 407,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'getComment' => 
      array (
        'name' => 'getComment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the key\'s comment
 *
 * Not all key formats support comments. If you want to set a comment use toString()
 *
 * @return null|string
 */',
        'startLine' => 416,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'useBestEngine' => 
      array (
        'name' => 'useBestEngine',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Tests engine validity
 *
 */',
        'startLine' => 425,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'useInternalEngine' => 
      array (
        'name' => 'useInternalEngine',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flag to use internal engine only (useful for unit testing)
 *
 */',
        'startLine' => 443,
        'endLine' => 450,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * __toString() magic method
 *
 * @return string
 */',
        'startLine' => 457,
        'endLine' => 460,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
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
            'startLine' => 467,
            'endLine' => 467,
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
 * @param string $hash
 */',
        'startLine' => 467,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'getHash' => 
      array (
        'name' => 'getHash',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the hash algorithm currently being used
 *
 */',
        'startLine' => 481,
        'endLine' => 484,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'computek' => 
      array (
        'name' => 'computek',
        'parameters' => 
        array (
          'h1' => 
          array (
            'name' => 'h1',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 493,
            'endLine' => 493,
            'startColumn' => 33,
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
 * Compute the pseudorandom k for signature generation,
 * using the process specified for deterministic DSA.
 *
 * @param string $h1
 * @return string
 */',
        'startLine' => 493,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'int2octets' => 
      array (
        'name' => 'int2octets',
        'parameters' => 
        array (
          'v' => 
          array (
            'name' => 'v',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 537,
            'endLine' => 537,
            'startColumn' => 33,
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
 * Integer to Octet String
 *
 * @param BigInteger $v
 * @return string
 */',
        'startLine' => 537,
        'endLine' => 548,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'bits2int' => 
      array (
        'name' => 'bits2int',
        'parameters' => 
        array (
          'in' => 
          array (
            'name' => 'in',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 556,
            'endLine' => 556,
            'startColumn' => 33,
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
 * Bit String to Integer
 *
 * @param string $in
 * @return BigInteger
 */',
        'startLine' => 556,
        'endLine' => 565,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'aliasName' => NULL,
      ),
      'bits2octets' => 
      array (
        'name' => 'bits2octets',
        'parameters' => 
        array (
          'in' => 
          array (
            'name' => 'in',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 573,
            'endLine' => 573,
            'startColumn' => 34,
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
 * Bit String to Octet String
 *
 * @param string $in
 * @return string
 */',
        'startLine' => 573,
        'endLine' => 580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'phpseclib3\\Crypt\\Common',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\AsymmetricKey',
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