<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/socialite/src/SocialiteManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Socialite\SocialiteManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b7f888b5c787e2ebbcaa8fd6b4be753da195e92dde8b24e253bf9c09a1d340ea-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Socialite\\SocialiteManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/socialite/src/SocialiteManager.php',
      ),
    ),
    'namespace' => 'Laravel\\Socialite',
    'name' => 'Laravel\\Socialite\\SocialiteManager',
    'shortName' => 'SocialiteManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 325,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Manager',
    'implementsClassNames' => 
    array (
      0 => 'Laravel\\Socialite\\Contracts\\Factory',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'app' => 
      array (
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'name' => 'app',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The application instance.
 *
 * @var \\Illuminate\\Contracts\\Foundation\\Application
 *
 * @deprecated Will be removed in a future Socialite release.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 19,
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
      'with' => 
      array (
        'name' => 'with',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 26,
            'endColumn' => 32,
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
 * Get a driver instance.
 *
 * @param  string  $driver
 * @return mixed
 */',
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createGithubDriver' => 
      array (
        'name' => 'createGithubDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 52,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createFacebookDriver' => 
      array (
        'name' => 'createFacebookDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 66,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createGoogleDriver' => 
      array (
        'name' => 'createGoogleDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 80,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createLinkedinDriver' => 
      array (
        'name' => 'createLinkedinDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 94,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createLinkedinOpenidDriver' => 
      array (
        'name' => 'createLinkedinOpenidDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 108,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createBitbucketDriver' => 
      array (
        'name' => 'createBitbucketDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 122,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createGitlabDriver' => 
      array (
        'name' => 'createGitlabDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 136,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createTwitterDriver' => 
      array (
        'name' => 'createTwitterDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\One\\AbstractProvider|\\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 150,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createTwitterOAuth2Driver' => 
      array (
        'name' => 'createTwitterOAuth2Driver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 168,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createXDriver' => 
      array (
        'name' => 'createXDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 182,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createTwitchDriver' => 
      array (
        'name' => 'createTwitchDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 196,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createSlackDriver' => 
      array (
        'name' => 'createSlackDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 210,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'createSlackOpenidDriver' => 
      array (
        'name' => 'createSlackOpenidDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create an instance of the specified driver.
 *
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 224,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'buildProvider' => 
      array (
        'name' => 'buildProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 46,
            'endColumn' => 52,
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
 * Build an OAuth 2 provider instance.
 *
 * @param  string  $provider
 * @param  array  $config
 * @return \\Laravel\\Socialite\\Two\\AbstractProvider
 */',
        'startLine' => 240,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'formatConfig' => 
      array (
        'name' => 'formatConfig',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
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
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 34,
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
 * Format the server configuration.
 *
 * @param  array  $config
 * @return array
 */',
        'startLine' => 263,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'formatRedirectUrl' => 
      array (
        'name' => 'formatRedirectUrl',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
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
            'startLine' => 278,
            'endLine' => 278,
            'startColumn' => 42,
            'endColumn' => 54,
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
 * Format the callback URL, resolving a relative URI if needed.
 *
 * @param  array  $config
 * @return string
 */',
        'startLine' => 278,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'forgetDrivers' => 
      array (
        'name' => 'forgetDrivers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Forget all of the resolved driver instances.
 *
 * @return $this
 */',
        'startLine' => 292,
        'endLine' => 297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'setContainer' => 
      array (
        'name' => 'setContainer',
        'parameters' => 
        array (
          'container' => 
          array (
            'name' => 'container',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 305,
            'endLine' => 305,
            'startColumn' => 34,
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
 * Set the container instance used by the manager.
 *
 * @param  \\Illuminate\\Contracts\\Container\\Container  $container
 * @return $this
 */',
        'startLine' => 305,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'aliasName' => NULL,
      ),
      'getDefaultDriver' => 
      array (
        'name' => 'getDefaultDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default driver name.
 *
 * @return string
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 321,
        'endLine' => 324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Socialite',
        'declaringClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'implementingClassName' => 'Laravel\\Socialite\\SocialiteManager',
        'currentClassName' => 'Laravel\\Socialite\\SocialiteManager',
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