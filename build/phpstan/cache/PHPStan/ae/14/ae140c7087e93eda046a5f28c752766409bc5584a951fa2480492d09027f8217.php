<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/Common/Traits/PasswordProtected.php-PHPStan\BetterReflection\Reflection\ReflectionClass-phpseclib3\Crypt\Common\Traits\PasswordProtected
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7f6f2604e9110905c7883f07f75d9d88702d515f1bd533910955c83d482fcd12-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../phpseclib/phpseclib/phpseclib/Crypt/Common/Traits/PasswordProtected.php',
      ),
    ),
    'namespace' => 'phpseclib3\\Crypt\\Common\\Traits',
    'name' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
    'shortName' => 'PasswordProtected',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Password Protected Trait for Private Keys
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 46,
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
      'password' => 
      array (
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
        'name' => 'password',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 25,
            'startFilePos' => 551,
            'endTokenPos' => 25,
            'endFilePos' => 555,
          ),
        ),
        'docComment' => '/**
 * Password
 *
 * @var string|bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
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
      'withPassword' => 
      array (
        'name' => 'withPassword',
        'parameters' => 
        array (
          'password' => 
          array (
            'name' => 'password',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 40,
                'endLine' => 40,
                'startTokenPos' => 40,
                'startFilePos' => 960,
                'endTokenPos' => 40,
                'endFilePos' => 964,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 34,
            'endColumn' => 50,
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
 * Sets the password
 *
 * Private keys can be encrypted with a password.  To unset the password, pass in the empty string or false.
 * Or rather, pass in $password such that empty($password) && !is_string($password) is true.
 *
 * @see self::createKey()
 * @see self::load()
 * @param string|bool $password
 */',
        'startLine' => 40,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'phpseclib3\\Crypt\\Common\\Traits',
        'declaringClassName' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
        'implementingClassName' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
        'currentClassName' => 'phpseclib3\\Crypt\\Common\\Traits\\PasswordProtected',
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