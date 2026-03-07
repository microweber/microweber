<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Notifications/VerifyEmail.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\Notifications\VerifyEmail
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-d2b51e983b50766024d291d77c08983d4b6218c728b2ba8ce9a89fdb9a1719f7',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Notifications/VerifyEmail.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User\\Notifications',
    'name' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
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
    'startLine' => 15,
    'endLine' => 62,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Auth\\Notifications\\VerifyEmail',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Queue\\ShouldQueue',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Bus\\Queueable',
      1 => 'Illuminate\\Queue\\InteractsWithQueue',
      2 => 'Illuminate\\Queue\\SerializesModels',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
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
            'startLine' => 26,
            'endLine' => 26,
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
 * Get the notification\'s delivery channels.
 *
 * @param  mixed  $notifiable
 * @return array
 */',
        'startLine' => 26,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
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
            'startLine' => 37,
            'endLine' => 37,
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
        'startLine' => 37,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
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
            'startLine' => 58,
            'endLine' => 58,
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
 * Get the array representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return array
 */',
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\VerifyEmail',
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