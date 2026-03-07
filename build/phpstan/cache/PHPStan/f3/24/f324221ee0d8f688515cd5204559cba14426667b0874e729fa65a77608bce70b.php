<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Password.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Password
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7e389d93974e1d76a77e07ab88b360916556418a9be1f86cc22e27c7f2b35a98-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Password',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Password.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Password',
    'shortName' => 'Password',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Contracts\\Auth\\PasswordBroker broker(string|null $name = null)
 * @method static string getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 * @method static string sendResetLink(array $credentials, \\Closure|null $callback = null)
 * @method static mixed reset(array $credentials, \\Closure $callback)
 * @method static \\Illuminate\\Contracts\\Auth\\CanResetPassword|null getUser(array $credentials)
 * @method static string createToken(\\Illuminate\\Contracts\\Auth\\CanResetPassword $user)
 * @method static void deleteToken(\\Illuminate\\Contracts\\Auth\\CanResetPassword $user)
 * @method static bool tokenExists(\\Illuminate\\Contracts\\Auth\\CanResetPassword $user, string $token)
 * @method static \\Illuminate\\Auth\\Passwords\\TokenRepositoryInterface getRepository()
 * @method static \\Illuminate\\Support\\Timebox getTimebox()
 *
 * @see \\Illuminate\\Auth\\Passwords\\PasswordBrokerManager
 * @see \\Illuminate\\Auth\\Passwords\\PasswordBroker
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 75,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Facades\\Facade',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ResetLinkSent' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'ResetLinkSent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::RESET_LINK_SENT',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 32,
            'startFilePos' => 1249,
            'endTokenPos' => 34,
            'endFilePos' => 1279,
          ),
        ),
        'docComment' => '/**
 * Constant representing a successfully sent password reset email.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
      'PasswordReset' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'PasswordReset',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::PASSWORD_RESET',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 45,
            'startFilePos' => 1411,
            'endTokenPos' => 47,
            'endFilePos' => 1440,
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
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 57,
      ),
      'InvalidUser' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'InvalidUser',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::INVALID_USER',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 58,
            'startFilePos' => 1599,
            'endTokenPos' => 60,
            'endFilePos' => 1626,
          ),
        ),
        'docComment' => '/**
 * Constant indicating the user could not be found when attempting a password reset.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'InvalidToken' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'InvalidToken',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::INVALID_TOKEN',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 71,
            'startFilePos' => 1759,
            'endTokenPos' => 73,
            'endFilePos' => 1787,
          ),
        ),
        'docComment' => '/**
 * Constant representing an invalid password reset token.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'ResetThrottled' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'ResetThrottled',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::RESET_THROTTLED',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 84,
            'startFilePos' => 1925,
            'endTokenPos' => 86,
            'endFilePos' => 1955,
          ),
        ),
        'docComment' => '/**
 * Constant representing a throttled password reset attempt.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 59,
      ),
      'RESET_LINK_SENT' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'RESET_LINK_SENT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::RESET_LINK_SENT',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 95,
            'startFilePos' => 1987,
            'endTokenPos' => 97,
            'endFilePos' => 2017,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 60,
      ),
      'PASSWORD_RESET' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'PASSWORD_RESET',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::PASSWORD_RESET',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 106,
            'startFilePos' => 2047,
            'endTokenPos' => 108,
            'endFilePos' => 2076,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
      'INVALID_USER' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'INVALID_USER',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::INVALID_USER',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 117,
            'startFilePos' => 2104,
            'endTokenPos' => 119,
            'endFilePos' => 2131,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'INVALID_TOKEN' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'INVALID_TOKEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::INVALID_TOKEN',
          'attributes' => 
          array (
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 128,
            'startFilePos' => 2160,
            'endTokenPos' => 130,
            'endFilePos' => 2188,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'RESET_THROTTLED' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'name' => 'RESET_THROTTLED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\\Illuminate\\Contracts\\Auth\\PasswordBroker::RESET_THROTTLED',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 139,
            'startFilePos' => 2219,
            'endTokenPos' => 141,
            'endFilePos' => 2249,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 60,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 */',
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Password',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Password',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Password',
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