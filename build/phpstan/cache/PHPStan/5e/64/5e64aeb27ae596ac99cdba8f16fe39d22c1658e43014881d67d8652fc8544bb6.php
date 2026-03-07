<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Auth/Notifications/VerifyEmail.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Auth\Notifications\VerifyEmail
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-52989bb4f209524f9934df86b15203dcd5df51939d3e8d9c531a7acc9b042b9b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Auth/Notifications/VerifyEmail.php',
      ),
    ),
    'namespace' => 'Illuminate\\Auth\\Notifications',
    'name' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
    'shortName' => 'VerifyEmail',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 114,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Notifications\\Notification',
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
      'createUrlCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'name' => 'createUrlCallback',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that should be used to create the verify email URL.
 *
 * @var \\Closure|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'toMailCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'name' => 'toMailCallback',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that should be used to build the mail message.
 *
 * @var \\Closure|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 34,
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
      'via' => 
      array (
        'name' => 'via',
        'parameters' => 
        array (
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 25,
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
 * Get the notification\'s channels.
 *
 * @param  mixed  $notifiable
 * @return array|string
 */',
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Notifications',
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'currentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'aliasName' => NULL,
      ),
      'toMail' => 
      array (
        'name' => 'toMail',
        'parameters' => 
        array (
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 28,
            'endColumn' => 38,
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
 * Build the mail representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return \\Illuminate\\Notifications\\Messages\\MailMessage
 */',
        'startLine' => 45,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Auth\\Notifications',
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'currentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'aliasName' => NULL,
      ),
      'buildMailMessage' => 
      array (
        'name' => 'buildMailMessage',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 62,
            'endLine' => 62,
            'startColumn' => 41,
            'endColumn' => 44,
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
 * Get the verify email notification mail message for the given URL.
 *
 * @param  string  $url
 * @return \\Illuminate\\Notifications\\Messages\\MailMessage
 */',
        'startLine' => 62,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Auth\\Notifications',
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'currentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'aliasName' => NULL,
      ),
      'verificationUrl' => 
      array (
        'name' => 'verificationUrl',
        'parameters' => 
        array (
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 40,
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
 * Get the verification URL for the given notifiable.
 *
 * @param  mixed  $notifiable
 * @return string
 */',
        'startLine' => 77,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Auth\\Notifications',
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'currentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'aliasName' => NULL,
      ),
      'createUrlUsing' => 
      array (
        'name' => 'createUrlUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
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
            'startColumn' => 43,
            'endColumn' => 51,
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
 * Set a callback that should be used when creating the email verification URL.
 *
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 99,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Auth\\Notifications',
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'currentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'aliasName' => NULL,
      ),
      'toMailUsing' => 
      array (
        'name' => 'toMailUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 110,
            'endLine' => 110,
            'startColumn' => 40,
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
 * Set a callback that should be used when building the notification mail message.
 *
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 110,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Auth\\Notifications',
        'declaringClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'implementingClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
        'currentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
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