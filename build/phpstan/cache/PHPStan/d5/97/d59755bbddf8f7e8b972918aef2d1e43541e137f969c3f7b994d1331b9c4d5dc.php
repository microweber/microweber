<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Notifications/NewRegistration.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\Notifications\NewRegistration
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-c1d266cf93c1e4e4eba8116e8623d7551ebc4eee111a6cf2ad8004e3d965efc9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Notifications/NewRegistration.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User\\Notifications',
    'name' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
    'shortName' => 'NewRegistration',
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
    'endLine' => 130,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Notifications\\Notification',
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
      'user' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'name' => 'user',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 17,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'notification' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'name' => 'notification',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 25,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 28,
                'endLine' => 28,
                'startTokenPos' => 96,
                'startFilePos' => 719,
                'endTokenPos' => 96,
                'endFilePos' => 723,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 33,
            'endColumn' => 45,
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
 * Create a new notification instance.
 *
 * @return void
 */',
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'aliasName' => NULL,
      ),
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
            'startLine' => 39,
            'endLine' => 39,
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
 * @param  mixed $notifiable
 * @return array
 */',
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
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
            'startLine' => 50,
            'endLine' => 50,
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
 * Get the mail representation of the notification.
 *
 * @param  mixed $notifiable
 * @return \\Illuminate\\Notifications\\Messages\\MailMessage
 */',
        'startLine' => 50,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
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
            'startLine' => 87,
            'endLine' => 87,
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
 * @param  mixed $notifiable
 * @return array
 */',
        'startLine' => 87,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'aliasName' => NULL,
      ),
      'setNotification' => 
      array (
        'name' => 'setNotification',
        'parameters' => 
        array (
          'noification' => 
          array (
            'name' => 'noification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 37,
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
        'docComment' => NULL,
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'aliasName' => NULL,
      ),
      'message' => 
      array (
        'name' => 'message',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 97,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Notifications',
        'declaringClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'implementingClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
        'currentClassName' => 'MicroweberPackages\\User\\Notifications\\NewRegistration',
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