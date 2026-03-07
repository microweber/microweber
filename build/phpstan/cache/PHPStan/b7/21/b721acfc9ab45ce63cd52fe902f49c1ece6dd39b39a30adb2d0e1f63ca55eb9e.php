<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/AbstractGateway.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\Common\AbstractGateway
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-11278cab21724c60614b27caa21f86f6b86546167bb5be1a1d45b0e08529fc53-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\Common\\AbstractGateway',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/AbstractGateway.php',
      ),
    ),
    'namespace' => 'Omnipay\\Common',
    'name' => 'Omnipay\\Common\\AbstractGateway',
    'shortName' => 'AbstractGateway',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Base payment gateway class
 *
 * This abstract class should be extended by all payment gateways
 * throughout the Omnipay system.  It enforces implementation of
 * the GatewayInterface interface and defines various common attributes
 * and methods that all gateways should have.
 *
 * Example:
 *
 * <code>
 *   // Initialise the gateway
 *   $gateway->initialize(...);
 *
 *   // Get the gateway parameters.
 *   $parameters = $gateway->getParameters();
 *
 *   // Create a credit card object
 *   $card = new CreditCard(...);
 *
 *   // Do an authorisation transaction on the gateway
 *   if ($gateway->supportsAuthorize()) {
 *       $gateway->authorize(...);
 *   } else {
 *       throw new \\Exception(\'Gateway does not support authorize()\');
 *   }
 * </code>
 *
 * For further code examples see the *omnipay-example* repository on github.
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 44,
    'endLine' => 344,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Omnipay\\Common\\GatewayInterface',
    ),
    'traitClassNames' => 
    array (
      0 => 'Omnipay\\Common\\ParametersTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'httpClient' => 
      array (
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'name' => 'httpClient',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var ClientInterface
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'httpRequest' => 
      array (
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'name' => 'httpRequest',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\Symfony\\Component\\HttpFoundation\\Request
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 27,
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
          'httpClient' => 
          array (
            'name' => 'httpClient',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 97,
                'startFilePos' => 1757,
                'endTokenPos' => 97,
                'endFilePos' => 1760,
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
                      'name' => 'Omnipay\\Common\\Http\\ClientInterface',
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 33,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'httpRequest' => 
          array (
            'name' => 'httpRequest',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 107,
                'startFilePos' => 1791,
                'endTokenPos' => 107,
                'endFilePos' => 1794,
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
                      'name' => 'Symfony\\Component\\HttpFoundation\\Request',
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
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 70,
            'endColumn' => 101,
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
 * Create a new gateway instance
 *
 * @param ClientInterface          $httpClient  A HTTP client to make API calls with
 * @param HttpRequest     $httpRequest A Symfony HTTP request object
 */',
        'startLine' => 67,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'getShortName' => 
      array (
        'name' => 'getShortName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the short name of the Gateway
 *
 * @return string
 */',
        'startLine' => 79,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'initialize' => 
      array (
        'name' => 'initialize',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => 'array()',
              'attributes' => 
              array (
                'startLine' => 90,
                'endLine' => 90,
                'startTokenPos' => 198,
                'startFilePos' => 2369,
                'endTokenPos' => 200,
                'endFilePos' => 2375,
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
            'startLine' => 90,
            'endLine' => 90,
            'startColumn' => 32,
            'endColumn' => 58,
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
 * Initialize this gateway with default parameters
 *
 * @param  array $parameters
 * @return $this
 */',
        'startLine' => 90,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
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
 * @return array
 */',
        'startLine' => 111,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'getParameter' => 
      array (
        'name' => 'getParameter',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 120,
            'endLine' => 120,
            'startColumn' => 34,
            'endColumn' => 37,
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
 * @param  string $key
 * @return mixed
 */',
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'setParameter' => 
      array (
        'name' => 'setParameter',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 34,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 40,
            'endColumn' => 45,
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
 * @param  string $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 130,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'getTestMode' => 
      array (
        'name' => 'getTestMode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 138,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'setTestMode' => 
      array (
        'name' => 'setTestMode',
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
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 33,
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
 * @param  boolean $value
 * @return $this
 */',
        'startLine' => 147,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'getCurrency' => 
      array (
        'name' => 'getCurrency',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 155,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'setCurrency' => 
      array (
        'name' => 'setCurrency',
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
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 33,
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
 * @param  string $value
 * @return $this
 */',
        'startLine' => 164,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsAuthorize' => 
      array (
        'name' => 'supportsAuthorize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Authorize
 *
 * @return boolean True if this gateway supports the authorize() method
 */',
        'startLine' => 174,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsCompleteAuthorize' => 
      array (
        'name' => 'supportsCompleteAuthorize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Complete Authorize
 *
 * @return boolean True if this gateway supports the completeAuthorize() method
 */',
        'startLine' => 184,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsCapture' => 
      array (
        'name' => 'supportsCapture',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Capture
 *
 * @return boolean True if this gateway supports the capture() method
 */',
        'startLine' => 194,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsPurchase' => 
      array (
        'name' => 'supportsPurchase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Purchase
 *
 * @return boolean True if this gateway supports the purchase() method
 */',
        'startLine' => 204,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsCompletePurchase' => 
      array (
        'name' => 'supportsCompletePurchase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Complete Purchase
 *
 * @return boolean True if this gateway supports the completePurchase() method
 */',
        'startLine' => 214,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsFetchTransaction' => 
      array (
        'name' => 'supportsFetchTransaction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Fetch Transaction
 *
 * @return boolean True if this gateway supports the fetchTransaction() method
 */',
        'startLine' => 224,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsRefund' => 
      array (
        'name' => 'supportsRefund',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Refund
 *
 * @return boolean True if this gateway supports the refund() method
 */',
        'startLine' => 234,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsVoid' => 
      array (
        'name' => 'supportsVoid',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports Void
 *
 * @return boolean True if this gateway supports the void() method
 */',
        'startLine' => 244,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsAcceptNotification' => 
      array (
        'name' => 'supportsAcceptNotification',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports AcceptNotification
 *
 * @return boolean True if this gateway supports the acceptNotification() method
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
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsCreateCard' => 
      array (
        'name' => 'supportsCreateCard',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports CreateCard
 *
 * @return boolean True if this gateway supports the create() method
 */',
        'startLine' => 264,
        'endLine' => 267,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsDeleteCard' => 
      array (
        'name' => 'supportsDeleteCard',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports DeleteCard
 *
 * @return boolean True if this gateway supports the delete() method
 */',
        'startLine' => 274,
        'endLine' => 277,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'supportsUpdateCard' => 
      array (
        'name' => 'supportsUpdateCard',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Supports UpdateCard
 *
 * @return boolean True if this gateway supports the update() method
 */',
        'startLine' => 284,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'createRequest' => 
      array (
        'name' => 'createRequest',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 38,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 46,
            'endColumn' => 62,
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
 * Create and initialize a request object
 *
 * This function is usually used to create objects of type
 * Omnipay\\Common\\Message\\AbstractRequest (or a non-abstract subclass of it)
 * and initialise them with using existing parameters from this gateway.
 *
 * Example:
 *
 * <code>
 *   class MyRequest extends \\Omnipay\\Common\\Message\\AbstractRequest {};
 *
 *   class MyGateway extends \\Omnipay\\Common\\AbstractGateway {
 *     function myRequest($parameters) {
 *       $this->createRequest(\'MyRequest\', $parameters);
 *     }
 *   }
 *
 *   // Create the gateway object
 *   $gw = Omnipay::create(\'MyGateway\');
 *
 *   // Create the request object
 *   $myRequest = $gw->myRequest($someParameters);
 * </code>
 *
 * @param string $class The request class name
 * @param array $parameters
 * @return \\Omnipay\\Common\\Message\\AbstractRequest
 */',
        'startLine' => 318,
        'endLine' => 323,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'getDefaultHttpClient' => 
      array (
        'name' => 'getDefaultHttpClient',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the global default HTTP client.
 *
 * @return ClientInterface
 */',
        'startLine' => 330,
        'endLine' => 333,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
      'getDefaultHttpRequest' => 
      array (
        'name' => 'getDefaultHttpRequest',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the global default HTTP request.
 *
 * @return HttpRequest
 */',
        'startLine' => 340,
        'endLine' => 343,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\AbstractGateway',
        'implementingClassName' => 'Omnipay\\Common\\AbstractGateway',
        'currentClassName' => 'Omnipay\\Common\\AbstractGateway',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Omnipay\\Common\\ParametersTrait' => 
        array (
          0 => 
          array (
            'alias' => 'traitSetParameter',
            'method' => 'setParameter',
            'hash' => 'omnipay\\common\\parameterstrait::setparameter',
          ),
          1 => 
          array (
            'alias' => 'traitGetParameter',
            'method' => 'getParameter',
            'hash' => 'omnipay\\common\\parameterstrait::getparameter',
          ),
        ),
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'omnipay\\common\\parameterstrait::setparameter' => 'Omnipay\\Common\\ParametersTrait::setParameter',
        'omnipay\\common\\parameterstrait::getparameter' => 'Omnipay\\Common\\ParametersTrait::getParameter',
      ),
    ),
  ),
));