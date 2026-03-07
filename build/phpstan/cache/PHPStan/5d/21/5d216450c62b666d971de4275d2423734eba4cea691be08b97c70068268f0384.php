<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/BaseStripeClient.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\BaseStripeClient
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6cd1fb5b51593f4e56427864cd51250cedca3f08d91232e0e2425c2d0a0c7dd4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\BaseStripeClient',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/BaseStripeClient.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\BaseStripeClient',
    'shortName' => 'BaseStripeClient',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 501,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Stripe\\StripeClientInterface',
      1 => 'Stripe\\StripeStreamingClientInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'DEFAULT_API_BASE' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'DEFAULT_API_BASE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://api.stripe.com\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 33,
            'startFilePos' => 225,
            'endTokenPos' => 33,
            'endFilePos' => 248,
          ),
        ),
        'docComment' => '/** @var string default base URL for Stripe\'s API */',
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'DEFAULT_CONNECT_BASE' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'DEFAULT_CONNECT_BASE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://connect.stripe.com\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 44,
            'startFilePos' => 348,
            'endTokenPos' => 44,
            'endFilePos' => 375,
          ),
        ),
        'docComment' => '/** @var string default base URL for Stripe\'s OAuth API */',
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 62,
      ),
      'DEFAULT_FILES_BASE' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'DEFAULT_FILES_BASE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://files.stripe.com\'',
          'attributes' => 
          array (
            'startLine' => 16,
            'endLine' => 16,
            'startTokenPos' => 55,
            'startFilePos' => 473,
            'endTokenPos' => 55,
            'endFilePos' => 498,
          ),
        ),
        'docComment' => '/** @var string default base URL for Stripe\'s Files API */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
      'DEFAULT_METER_EVENTS_BASE' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'DEFAULT_METER_EVENTS_BASE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://meter-events.stripe.com\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 66,
            'startFilePos' => 610,
            'endTokenPos' => 66,
            'endFilePos' => 642,
          ),
        ),
        'docComment' => '/** @var string default base URL for Stripe\'s Meter Events API */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 72,
      ),
      'DEFAULT_CONFIG' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'DEFAULT_CONFIG',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[
    \'api_key\' => null,
    \'app_info\' => null,
    \'client_id\' => null,
    \'stripe_account\' => null,
    \'stripe_context\' => null,
    \'stripe_version\' => \\Stripe\\Util\\ApiVersion::CURRENT,
    \'api_base\' => self::DEFAULT_API_BASE,
    \'connect_base\' => self::DEFAULT_CONNECT_BASE,
    \'files_base\' => self::DEFAULT_FILES_BASE,
    \'meter_events_base\' => self::DEFAULT_METER_EVENTS_BASE,
    // inherit from global
    \'max_network_retries\' => null,
]',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 35,
            'startTokenPos' => 77,
            'startFilePos' => 720,
            'endTokenPos' => 168,
            'endFilePos' => 1224,
          ),
        ),
        'docComment' => '/** @var array<string, null|int|string> */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'config' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'config',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var array<string, mixed> */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultOpts' => 
      array (
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'name' => 'defaultOpts',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var \\Stripe\\Util\\RequestOptions */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
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
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 78,
                'endLine' => 78,
                'startTokenPos' => 197,
                'startFilePos' => 3676,
                'endTokenPos' => 198,
                'endFilePos' => 3677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 33,
            'endColumn' => 44,
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
 * Initializes a new instance of the {@link BaseStripeClient} class.
 *
 * The constructor takes a single argument. The argument can be a string, in which case it
 * should be the API key. It can also be an array with various configuration settings.
 *
 * Configuration settings include the following options:
 *
 * - api_key (null|string): the Stripe API key, to be used in regular API requests.
 * - app_info (null|array): information to identify a plugin that integrates Stripe using this library.
 *                          Expects: array{name: string, version?: string, url?: string, partner_id?: string}
 * - client_id (null|string): the Stripe client ID, to be used in OAuth requests.
 * - stripe_account (null|string): a Stripe account ID. If set, all requests sent by the client
 *   will automatically use the {@code Stripe-Account} header with that account ID.
 * - stripe_context (null|string): a Stripe account or compartment ID. If set, all requests sent by the client
 *   will automatically use the {@code Stripe-Context} header with that ID.
 * - stripe_version (null|string): a Stripe API version. If set, all requests sent by the client
 *   will include the {@code Stripe-Version} header with that API version.
 * - max_network_retries (null|int): the number of times this client should retry API failures; defaults to 0.
 *
 * The following configuration settings are also available, though setting these should rarely be necessary
 * (only useful if you want to send requests to a mock server like stripe-mock):
 *
 * - api_base (string): the base URL for regular API requests. Defaults to
 *   {@link DEFAULT_API_BASE}.
 * - connect_base (string): the base URL for OAuth requests. Defaults to
 *   {@link DEFAULT_CONNECT_BASE}.
 * - files_base (string): the base URL for file creation requests. Defaults to
 *   {@link DEFAULT_FILES_BASE}.
 * - meter_events_base (string): the base URL for high throughput requests. Defaults to
 *   {@link DEFAULT_METER_EVENTS_BASE}.
 *
 * @param array<string, mixed>|string $config the API key as a string, or an array containing
 *   the client configuration settings
 */',
        'startLine' => 78,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getApiKey' => 
      array (
        'name' => 'getApiKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the API key used by the client to send requests.
 *
 * @return null|string the API key used by the client to send requests
 */',
        'startLine' => 109,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getClientId' => 
      array (
        'name' => 'getClientId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the client ID used by the client in OAuth requests.
 *
 * @return null|string the client ID used by the client in OAuth requests
 */',
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getStripeAccount' => 
      array (
        'name' => 'getStripeAccount',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the Stripe account ID used by the client to send requests.
 *
 * @return null|string the Stripe account ID used by the client to send requests
 */',
        'startLine' => 129,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getApiBase' => 
      array (
        'name' => 'getApiBase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the base URL for Stripe\'s API.
 *
 * @return string the base URL for Stripe\'s API
 */',
        'startLine' => 139,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getConnectBase' => 
      array (
        'name' => 'getConnectBase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the base URL for Stripe\'s OAuth API.
 *
 * @return string the base URL for Stripe\'s OAuth API
 */',
        'startLine' => 149,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getFilesBase' => 
      array (
        'name' => 'getFilesBase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the base URL for Stripe\'s Files API.
 *
 * @return string the base URL for Stripe\'s Files API
 */',
        'startLine' => 159,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getMeterEventsBase' => 
      array (
        'name' => 'getMeterEventsBase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the base URL for Stripe\'s Meter Events API.
 *
 * @return string the base URL for Stripe\'s Meter Events API
 */',
        'startLine' => 169,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getMaxNetworkRetries' => 
      array (
        'name' => 'getMaxNetworkRetries',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the configured number of retries.
 *
 * @return int the number of times this client will retry failed requests
 */',
        'startLine' => 179,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'getAppInfo' => 
      array (
        'name' => 'getAppInfo',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the app info for this client.
 *
 * @return null|array information to identify a plugin that integrates Stripe using this library
 */',
        'startLine' => 189,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'request' => 
      array (
        'name' => 'request',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 38,
            'endColumn' => 42,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 45,
            'endColumn' => 51,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 54,
            'endColumn' => 58,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sends a request to Stripe\'s API.
 *
 * @param \'delete\'|\'get\'|\'post\' $method the HTTP method
 * @param string $path the path of the request
 * @param array $params the parameters of the request
 * @param array|\\Stripe\\Util\\RequestOptions $opts the special modifiers of the request
 *
 * @return StripeObject the object returned by Stripe\'s API
 */',
        'startLine' => 204,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'rawRequest' => 
      array (
        'name' => 'rawRequest',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 32,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 239,
                'endLine' => 239,
                'startTokenPos' => 827,
                'startFilePos' => 8789,
                'endTokenPos' => 827,
                'endFilePos' => 8792,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 48,
            'endColumn' => 61,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 239,
                'endLine' => 239,
                'startTokenPos' => 834,
                'startFilePos' => 8803,
                'endTokenPos' => 835,
                'endFilePos' => 8804,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 64,
            'endColumn' => 73,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'maxNetworkRetries' => 
          array (
            'name' => 'maxNetworkRetries',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 239,
                'endLine' => 239,
                'startTokenPos' => 842,
                'startFilePos' => 8828,
                'endTokenPos' => 842,
                'endFilePos' => 8831,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 76,
            'endColumn' => 100,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sends a raw request to Stripe\'s API. This is the lowest level method for interacting
 * with the Stripe API. This method is useful for interacting with endpoints that are not
 * covered yet in stripe-php.
 *
 * @param \'delete\'|\'get\'|\'post\' $method the HTTP method
 * @param string $path the path of the request
 * @param null|array $params the parameters of the request
 * @param array $opts the special modifiers of the request
 * @param null|int $maxNetworkRetries
 *
 * @return ApiResponse
 */',
        'startLine' => 239,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'requestStream' => 
      array (
        'name' => 'requestStream',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 35,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 44,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'readBodyChunkCallable' => 
          array (
            'name' => 'readBodyChunkCallable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 51,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 75,
            'endColumn' => 81,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 84,
            'endColumn' => 88,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sends a request to Stripe\'s API, passing chunks of the streamed response
 * into a user-provided $readBodyChunkCallable callback.
 *
 * @param \'delete\'|\'get\'|\'post\' $method the HTTP method
 * @param string $path the path of the request
 * @param callable $readBodyChunkCallable a function that will be called
 * @param array $params the parameters of the request
 * @param array|\\Stripe\\Util\\RequestOptions $opts the special modifiers of the request
 *
 * with chunks of bytes from the body if the request is successful
 */',
        'startLine' => 280,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'requestCollection' => 
      array (
        'name' => 'requestCollection',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 39,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 48,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 55,
            'endColumn' => 61,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 64,
            'endColumn' => 68,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sends a request to Stripe\'s API.
 *
 * @param \'delete\'|\'get\'|\'post\' $method the HTTP method
 * @param string $path the path of the request
 * @param array $params the parameters of the request
 * @param array|\\Stripe\\Util\\RequestOptions $opts the special modifiers of the request
 *
 * @return Collection|V2\\Collection of ApiResources
 */',
        'startLine' => 299,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'requestSearchResult' => 
      array (
        'name' => 'requestSearchResult',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 50,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 57,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 66,
            'endColumn' => 70,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sends a request to Stripe\'s API.
 *
 * @param \'delete\'|\'get\'|\'post\' $method the HTTP method
 * @param string $path the path of the request
 * @param array $params the parameters of the request
 * @param array|\\Stripe\\Util\\RequestOptions $opts the special modifiers of the request
 *
 * @return SearchResult of ApiResources
 */',
        'startLine' => 333,
        'endLine' => 345,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'apiKeyForRequest' => 
      array (
        'name' => 'apiKeyForRequest',
        'parameters' => 
        array (
          'opts' => 
          array (
            'name' => 'opts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 354,
            'endLine' => 354,
            'startColumn' => 39,
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
 * @param \\Stripe\\Util\\RequestOptions $opts
 *
 * @return string
 *
 * @throws Exception\\AuthenticationException
 */',
        'startLine' => 354,
        'endLine' => 367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'validateConfig' => 
      array (
        'name' => 'validateConfig',
        'parameters' => 
        array (
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
            'startLine' => 374,
            'endLine' => 374,
            'startColumn' => 37,
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
 * @param array<string, mixed> $config
 *
 * @throws Exception\\InvalidArgumentException
 */',
        'startLine' => 374,
        'endLine' => 453,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'deserialize' => 
      array (
        'name' => 'deserialize',
        'parameters' => 
        array (
          'json' => 
          array (
            'name' => 'json',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 463,
            'endLine' => 463,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'apiMode' => 
          array (
            'name' => 'apiMode',
            'default' => 
            array (
              'code' => '\'v1\'',
              'attributes' => 
              array (
                'startLine' => 463,
                'endLine' => 463,
                'startTokenPos' => 2251,
                'startFilePos' => 17687,
                'endTokenPos' => 2251,
                'endFilePos' => 17690,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 463,
            'endLine' => 463,
            'startColumn' => 40,
            'endColumn' => 54,
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
 * Deserializes the raw JSON string returned by rawRequest into a similar class.
 *
 * @param string $json
 * @param \'v1\'|\'v2\' $apiMode
 *
 * @return StripeObject
 * */',
        'startLine' => 463,
        'endLine' => 466,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
        'aliasName' => NULL,
      ),
      'parseThinEvent' => 
      array (
        'name' => 'parseThinEvent',
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
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sigHeader' => 
          array (
            'name' => 'sigHeader',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 46,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'secret' => 
          array (
            'name' => 'secret',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 58,
            'endColumn' => 64,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'tolerance' => 
          array (
            'name' => 'tolerance',
            'default' => 
            array (
              'code' => '\\Stripe\\Webhook::DEFAULT_TOLERANCE',
              'attributes' => 
              array (
                'startLine' => 486,
                'endLine' => 486,
                'startTokenPos' => 2302,
                'startFilePos' => 18732,
                'endTokenPos' => 2304,
                'endFilePos' => 18757,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 486,
            'endLine' => 486,
            'startColumn' => 67,
            'endColumn' => 105,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a V2\\Events instance using the provided JSON payload. Throws an
 * Exception\\UnexpectedValueException if the payload is not valid JSON, and
 * an Exception\\SignatureVerificationException if the signature
 * verification fails for any reason.
 *
 * @param string $payload the payload sent by Stripe
 * @param string $sigHeader the contents of the signature header sent by
 *  Stripe
 * @param string $secret secret used to generate the signature
 * @param int $tolerance maximum difference allowed between the header\'s
 *  timestamp and the current time. Defaults to 300 seconds (5 min)
 *
 * @return ThinEvent
 *
 * @throws Exception\\SignatureVerificationException if the verification fails
 * @throws Exception\\UnexpectedValueException if the payload is not valid JSON,
 */',
        'startLine' => 486,
        'endLine' => 500,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BaseStripeClient',
        'implementingClassName' => 'Stripe\\BaseStripeClient',
        'currentClassName' => 'Stripe\\BaseStripeClient',
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