<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Encryption/StringEncrypter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Encryption\StringEncrypter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-bb5bbf2b2f4029c61ba92c62383caa0ebc68babed62c3784b5faa6629c4d33fb-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Encryption/StringEncrypter.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Encryption',
    'name' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
    'shortName' => 'StringEncrypter',
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
    'endLine' => 26,
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
      'encryptString' => 
      array (
        'name' => 'encryptString',
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
            'startLine' => 15,
            'endLine' => 15,
            'startColumn' => 35,
            'endColumn' => 63,
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
 * Encrypt a string without serialization.
 *
 * @param  string  $value
 * @return string
 *
 * @throws \\Illuminate\\Contracts\\Encryption\\EncryptException
 */',
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
        'aliasName' => NULL,
      ),
      'decryptString' => 
      array (
        'name' => 'decryptString',
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
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 35,
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
 * Decrypt the given string without unserialization.
 *
 * @param  string  $payload
 * @return string
 *
 * @throws \\Illuminate\\Contracts\\Encryption\\DecryptException
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Encryption',
        'declaringClassName' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
        'implementingClassName' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
        'currentClassName' => 'Illuminate\\Contracts\\Encryption\\StringEncrypter',
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