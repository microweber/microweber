<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Encryption/Encrypter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Encryption\Encrypter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8b042b5ccbb71bd75550f79478a4b7d4f1cb2397c45a4b41a1f4967675657ea6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Encryption/Encrypter.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Encryption',
    'name' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
    'shortName' => 'Encrypter',
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
    'endLine' => 49,
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
      'encrypt' => 
      array (
        'name' => 'encrypt',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 29,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'serialize' => 
          array (
            'name' => 'serialize',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 16,
                'endLine' => 16,
                'startTokenPos' => 32,
                'startFilePos' => 355,
                'endTokenPos' => 32,
                'endFilePos' => 358,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 60,
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
 * Encrypt the given value.
 *
 * @param  mixed  $value
 * @param  bool  $serialize
 * @return string
 *
 * @throws \\Illuminate\\Contracts\\Encryption\\EncryptException
 */',
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'aliasName' => NULL,
      ),
      'decrypt' => 
      array (
        'name' => 'decrypt',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'unserialize' => 
          array (
            'name' => 'unserialize',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 51,
                'startFilePos' => 630,
                'endTokenPos' => 51,
                'endFilePos' => 633,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 39,
            'endColumn' => 57,
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
 * Decrypt the given value.
 *
 * @param  string  $payload
 * @param  bool  $unserialize
 * @return mixed
 *
 * @throws \\Illuminate\\Contracts\\Encryption\\DecryptException
 */',
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'aliasName' => NULL,
      ),
      'getKey' => 
      array (
        'name' => 'getKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the encryption key that the encrypter is currently using.
 *
 * @return string
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'aliasName' => NULL,
      ),
      'getAllKeys' => 
      array (
        'name' => 'getAllKeys',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current encryption key and all previous encryption keys.
 *
 * @return array
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'aliasName' => NULL,
      ),
      'getPreviousKeys' => 
      array (
        'name' => 'getPreviousKeys',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the previous encryption keys.
 *
 * @return array
 */',
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\Encrypter',
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