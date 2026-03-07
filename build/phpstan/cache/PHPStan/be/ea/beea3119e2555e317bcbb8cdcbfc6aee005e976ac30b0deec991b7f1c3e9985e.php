<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-packages/omnipay-momo-mtn/src/Gateway.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\MoMoMtn\Gateway
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e4b3d09aa2e4d39a93bff21e6298cb7861e41eb9675197fce4019b1f5c18aa77-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\MoMoMtn\\Gateway',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../microweber-packages/omnipay-momo-mtn/src/Gateway.php',
      ),
    ),
    'namespace' => 'Omnipay\\MoMoMtn',
    'name' => 'Omnipay\\MoMoMtn\\Gateway',
    'shortName' => 'Gateway',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * MTN Mobile Money Gateway
 *
 * This gateway provides integration with MTN Mobile Money API for payment processing.
 * It supports both sandbox and production environments.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the MTN MoMo driver
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create(\'MoMoMtn\');
 *
 *   // Initialize the gateway
 *   $gateway->setApiUserId(\'your-api-user-id\');
 *   $gateway->setApiKey(\'your-api-key\');
 *   $gateway->setSubscriptionKey(\'your-subscription-key\');
 *   $gateway->setTargetEnvironment(\'sandbox\'); // or \'production\'
 *   $gateway->setCallbackHost(\'webhook.site\'); // for sandbox
 *
 *   // Create API credentials (sandbox only)
 *   $response = $gateway->createApiUser([
 *       \'subscriptionKey\' => \'your-subscription-key\',
 *       \'callbackHost\' => \'webhook.site\'
 *   ])->send();
 *
 *   // Process payment
 *   $response = $gateway->purchase([
 *       \'amount\' => \'10.00\',
 *       \'currency\' => \'EUR\',
 *       \'payerPhone\' => \'46733123453\',
 *       \'payerMessage\' => \'Payment for order #123\',
 *       \'payeeNote\' => \'Order payment\'
 *   ])->send();
 *
 *   if ($response->isSuccessful()) {
 *       echo "Payment ID: " . $response->getTransactionReference();
 *   }
 * </code>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 55,
    'endLine' => 269,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Omnipay\\Common\\AbstractGateway',
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
      'getName' => 
      array (
        'name' => 'getName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get gateway display name
 *
 * This can be used by carts to get the display name for each gateway.
 */',
        'startLine' => 62,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'getDefaultParameters' => 
      array (
        'name' => 'getDefaultParameters',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define gateway parameters, in the following format:
 *
 * array(
 *     \'username\' => \'\', // string variable
 *     \'testMode\' => false, // boolean variable
 *     \'landingPage\' => array(\'billing\', \'login\'), // enum variable, first item is default
 * );
 */',
        'startLine' => 76,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'getApiUserId' => 
      array (
        'name' => 'getApiUserId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get API User ID
 *
 * @return string
 */',
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'setApiUserId' => 
      array (
        'name' => 'setApiUserId',
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
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 34,
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
 * Set API User ID
 *
 * @param string $value
 * @return $this
 */',
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
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
 * Get API Key
 *
 * @return string
 */',
        'startLine' => 114,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'setApiKey' => 
      array (
        'name' => 'setApiKey',
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
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 31,
            'endColumn' => 36,
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
 * Set API Key
 *
 * @param string $value
 * @return $this
 */',
        'startLine' => 125,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'getSubscriptionKey' => 
      array (
        'name' => 'getSubscriptionKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Subscription Key
 *
 * @return string
 */',
        'startLine' => 135,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'setSubscriptionKey' => 
      array (
        'name' => 'setSubscriptionKey',
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
            ),
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 40,
            'endColumn' => 45,
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
 * Set Subscription Key
 *
 * @param string $value
 * @return $this
 */',
        'startLine' => 146,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'getTargetEnvironment' => 
      array (
        'name' => 'getTargetEnvironment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Target Environment
 *
 * @return string
 */',
        'startLine' => 156,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'setTargetEnvironment' => 
      array (
        'name' => 'setTargetEnvironment',
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
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 42,
            'endColumn' => 47,
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
 * Set Target Environment
 *
 * @param string $value
 * @return $this
 */',
        'startLine' => 167,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'getCallbackHost' => 
      array (
        'name' => 'getCallbackHost',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get Callback Host
 *
 * @return string
 */',
        'startLine' => 177,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'setCallbackHost' => 
      array (
        'name' => 'setCallbackHost',
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
            ),
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 37,
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
 * Set Callback Host
 *
 * @param string $value
 * @return $this
 */',
        'startLine' => 188,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'createApiUser' => 
      array (
        'name' => 'createApiUser',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 199,
                'endLine' => 199,
                'startTokenPos' => 420,
                'startFilePos' => 4903,
                'endTokenPos' => 421,
                'endFilePos' => 4904,
              ),
            ),
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
            'startLine' => 199,
            'endLine' => 199,
            'startColumn' => 35,
            'endColumn' => 56,
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
 * Create API User (Sandbox only)
 *
 * @param array $parameters
 * @return CreateApiUserRequest
 */',
        'startLine' => 199,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'createApiKey' => 
      array (
        'name' => 'createApiKey',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 210,
                'endLine' => 210,
                'startTokenPos' => 457,
                'startFilePos' => 5190,
                'endTokenPos' => 458,
                'endFilePos' => 5191,
              ),
            ),
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
            'startLine' => 210,
            'endLine' => 210,
            'startColumn' => 34,
            'endColumn' => 55,
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
 * Create API Key (Sandbox only)
 *
 * @param array $parameters
 * @return CreateApiKeyRequest
 */',
        'startLine' => 210,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'createToken' => 
      array (
        'name' => 'createToken',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 221,
                'endLine' => 221,
                'startTokenPos' => 494,
                'startFilePos' => 5463,
                'endTokenPos' => 495,
                'endFilePos' => 5464,
              ),
            ),
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
            'startLine' => 221,
            'endLine' => 221,
            'startColumn' => 33,
            'endColumn' => 54,
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
 * Create OAuth Token
 *
 * @param array $parameters
 * @return CreateTokenRequest
 */',
        'startLine' => 221,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'purchase' => 
      array (
        'name' => 'purchase',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 232,
                'endLine' => 232,
                'startTokenPos' => 531,
                'startFilePos' => 5727,
                'endTokenPos' => 532,
                'endFilePos' => 5728,
              ),
            ),
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
            'startLine' => 232,
            'endLine' => 232,
            'startColumn' => 30,
            'endColumn' => 51,
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
 * Purchase request
 *
 * @param array $parameters
 * @return PurchaseRequest
 */',
        'startLine' => 232,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'completePurchase' => 
      array (
        'name' => 'completePurchase',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 243,
                'endLine' => 243,
                'startTokenPos' => 568,
                'startFilePos' => 6013,
                'endTokenPos' => 569,
                'endFilePos' => 6014,
              ),
            ),
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
            'startLine' => 243,
            'endLine' => 243,
            'startColumn' => 38,
            'endColumn' => 59,
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
 * Complete purchase request
 *
 * @param array $parameters
 * @return CompletePurchaseRequest
 */',
        'startLine' => 243,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'checkBalance' => 
      array (
        'name' => 'checkBalance',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 254,
                'endLine' => 254,
                'startTokenPos' => 605,
                'startFilePos' => 6295,
                'endTokenPos' => 606,
                'endFilePos' => 6296,
              ),
            ),
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
            'startLine' => 254,
            'endLine' => 254,
            'startColumn' => 34,
            'endColumn' => 55,
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
 * Check account balance
 *
 * @param array $parameters
 * @return CheckBalanceRequest
 */',
        'startLine' => 254,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'aliasName' => NULL,
      ),
      'checkAccountActive' => 
      array (
        'name' => 'checkAccountActive',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 265,
                'endLine' => 265,
                'startTokenPos' => 642,
                'startFilePos' => 6590,
                'endTokenPos' => 643,
                'endFilePos' => 6591,
              ),
            ),
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
            'startLine' => 265,
            'endLine' => 265,
            'startColumn' => 40,
            'endColumn' => 61,
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
 * Check if account is active
 *
 * @param array $parameters
 * @return CheckAccountActiveRequest
 */',
        'startLine' => 265,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\MoMoMtn',
        'declaringClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'implementingClassName' => 'Omnipay\\MoMoMtn\\Gateway',
        'currentClassName' => 'Omnipay\\MoMoMtn\\Gateway',
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