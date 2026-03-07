<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Auth/PasswordBroker.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Auth\PasswordBroker
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-05d8bf33cf040c5fde007b1ec4e4bf864c02b306fd9feda1d51a083c3cb0abf2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Auth/PasswordBroker.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Auth',
    'name' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
    'shortName' => 'PasswordBroker',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 61,
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
      'RESET_LINK_SENT' => 
      array (
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'name' => 'RESET_LINK_SENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'passwords.sent\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 26,
            'startFilePos' => 215,
            'endTokenPos' => 26,
            'endFilePos' => 230,
          ),
        ),
        'docComment' => '/**
 * Constant representing a successfully sent reminder.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'PASSWORD_RESET' => 
      array (
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'name' => 'PASSWORD_RESET',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'passwords.reset\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 37,
            'startFilePos' => 363,
            'endTokenPos' => 37,
            'endFilePos' => 379,
          ),
        ),
        'docComment' => '/**
 * Constant representing a successfully reset password.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'INVALID_USER' => 
      array (
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'name' => 'INVALID_USER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'passwords.user\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 48,
            'startFilePos' => 508,
            'endTokenPos' => 48,
            'endFilePos' => 523,
          ),
        ),
        'docComment' => '/**
 * Constant representing the user not found response.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 42,
      ),
      'INVALID_TOKEN' => 
      array (
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'name' => 'INVALID_TOKEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'passwords.token\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 59,
            'startFilePos' => 642,
            'endTokenPos' => 59,
            'endFilePos' => 658,
          ),
        ),
        'docComment' => '/**
 * Constant representing an invalid token.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'RESET_THROTTLED' => 
      array (
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'name' => 'RESET_THROTTLED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'passwords.throttled\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 70,
            'startFilePos' => 788,
            'endTokenPos' => 70,
            'endFilePos' => 808,
          ),
        ),
        'docComment' => '/**
 * Constant representing a throttled reset attempt.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'sendResetLink' => 
      array (
        'name' => 'sendResetLink',
        'parameters' => 
        array (
          'credentials' => 
          array (
            'name' => 'credentials',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 35,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 93,
                'startFilePos' => 1052,
                'endTokenPos' => 93,
                'endFilePos' => 1055,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 55,
            'endColumn' => 79,
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
 * Send a password reset link to a user.
 *
 * @param  array  $credentials
 * @param  \\Closure|null  $callback
 * @return string
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 81,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'aliasName' => NULL,
      ),
      'reset' => 
      array (
        'name' => 'reset',
        'parameters' => 
        array (
          'credentials' => 
          array (
            'name' => 'credentials',
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
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 27,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 47,
            'endColumn' => 63,
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
 * Reset the password for the given token.
 *
 * @param  array  $credentials
 * @param  \\Closure  $callback
 * @return mixed
 */',
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Auth',
        'declaringClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'implementingClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
        'currentClassName' => 'Illuminate\\Contracts\\Auth\\PasswordBroker',
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