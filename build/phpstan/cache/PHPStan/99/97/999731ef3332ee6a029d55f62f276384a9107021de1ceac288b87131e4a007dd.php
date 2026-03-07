<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Notifications/AnonymousNotifiable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Notifications\AnonymousNotifiable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-047206f0df5b245cf7dbb37dc148b8f686e64ebdbfd91905e6f91d88f9436047-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Notifications/AnonymousNotifiable.php',
      ),
    ),
    'namespace' => 'Illuminate\\Notifications',
    'name' => 'Illuminate\\Notifications\\AnonymousNotifiable',
    'shortName' => 'AnonymousNotifiable',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 79,
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
      'routes' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'implementingClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'name' => 'routes',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 31,
            'startFilePos' => 268,
            'endTokenPos' => 32,
            'endFilePos' => 269,
          ),
        ),
        'docComment' => '/**
 * All of the notification routing information.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 24,
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
      'route' => 
      array (
        'name' => 'route',
        'parameters' => 
        array (
          'channel' => 
          array (
            'name' => 'channel',
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
            'startColumn' => 27,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'route' => 
          array (
            'name' => 'route',
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
            'startColumn' => 37,
            'endColumn' => 42,
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
 * Add routing information to the target.
 *
 * @param  string  $channel
 * @param  mixed  $route
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 26,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications',
        'declaringClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'implementingClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'currentClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'aliasName' => NULL,
      ),
      'notify' => 
      array (
        'name' => 'notify',
        'parameters' => 
        array (
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
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
 * Send the given notification.
 *
 * @param  mixed  $notification
 * @return void
 */',
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications',
        'declaringClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'implementingClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'currentClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'aliasName' => NULL,
      ),
      'notifyNow' => 
      array (
        'name' => 'notifyNow',
        'parameters' => 
        array (
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 31,
            'endColumn' => 43,
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
 * Send the given notification immediately.
 *
 * @param  mixed  $notification
 * @return void
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications',
        'declaringClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'implementingClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'currentClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'aliasName' => NULL,
      ),
      'routeNotificationFor' => 
      array (
        'name' => 'routeNotificationFor',
        'parameters' => 
        array (
          'driver' => 
          array (
            'name' => 'driver',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 42,
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
 * Get the notification routing information for the given driver.
 *
 * @param  string  $driver
 * @return mixed
 */',
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications',
        'declaringClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'implementingClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'currentClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
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
 * Get the value of the notifiable\'s primary key.
 *
 * @return mixed
 */',
        'startLine' => 75,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications',
        'declaringClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'implementingClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
        'currentClassName' => 'Illuminate\\Notifications\\AnonymousNotifiable',
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