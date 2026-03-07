<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Omnipay.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\Omnipay
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-44e511dcb49c2ea482b61a1cee0abd695d2470f6c0cabd9cbddcf46ecfbf4390',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\Omnipay',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Omnipay.php',
      ),
    ),
    'namespace' => 'Omnipay',
    'name' => 'Omnipay\\Omnipay',
    'shortName' => 'Omnipay',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Omnipay class
 *
 * Provides static access to the gateway factory methods.  This is the
 * recommended route for creation and establishment of payment gateway
 * objects via the standard GatewayFactory.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create(\'ExpressGateway\');
 *
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
 * @method static array  all()
 * @method static array  replace(array $gateways)
 * @method static string register(string $className)
 * @method static array  find()
 * @method static array  getSupportedGateways()
 * @codingStandardsIgnoreStart
 * @method static \\Omnipay\\Common\\GatewayInterface create(string $class, ClientInterface $httpClient = null, \\Symfony\\Component\\HttpFoundation\\Request $httpRequest = null)
 * @codingStandardsIgnoreEnd
 *
 * @see \\Omnipay\\Common\\GatewayFactory
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 55,
    'endLine' => 118,
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
      'factory' => 
      array (
        'declaringClassName' => 'Omnipay\\Omnipay',
        'implementingClassName' => 'Omnipay\\Omnipay',
        'name' => 'factory',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Internal factory storage
 *
 * @var GatewayFactory
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 28,
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
      'getFactory' => 
      array (
        'name' => 'getFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the gateway factory
 *
 * Creates a new empty GatewayFactory if none has been set previously.
 *
 * @return GatewayFactory A GatewayFactory instance
 */',
        'startLine' => 72,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Omnipay',
        'declaringClassName' => 'Omnipay\\Omnipay',
        'implementingClassName' => 'Omnipay\\Omnipay',
        'currentClassName' => 'Omnipay\\Omnipay',
        'aliasName' => NULL,
      ),
      'setFactory' => 
      array (
        'name' => 'setFactory',
        'parameters' => 
        array (
          'factory' => 
          array (
            'name' => 'factory',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 101,
                'startFilePos' => 2240,
                'endTokenPos' => 101,
                'endFilePos' => 2243,
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
                      'name' => 'Omnipay\\Common\\GatewayFactory',
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
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 39,
            'endColumn' => 69,
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
 * Set the gateway factory
 *
 * @param GatewayFactory $factory A GatewayFactory instance
 */',
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Omnipay',
        'declaringClassName' => 'Omnipay\\Omnipay',
        'implementingClassName' => 'Omnipay\\Omnipay',
        'currentClassName' => 'Omnipay\\Omnipay',
        'aliasName' => NULL,
      ),
      '__callStatic' => 
      array (
        'name' => '__callStatic',
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 50,
            'endColumn' => 60,
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
 * Static function call router.
 *
 * All other function calls to the Omnipay class are routed to the
 * factory.  e.g. Omnipay::getSupportedGateways(1, 2, 3, 4) is routed to the
 * factory\'s getSupportedGateways method and passed the parameters 1, 2, 3, 4.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   $gateway = Omnipay::create(\'ExpressGateway\');
 * </code>
 *
 * @see GatewayFactory
 *
 * @param string $method     The factory method to invoke.
 * @param array  $parameters Parameters passed to the factory method.
 *
 * @return mixed
 */',
        'startLine' => 112,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Omnipay',
        'declaringClassName' => 'Omnipay\\Omnipay',
        'implementingClassName' => 'Omnipay\\Omnipay',
        'currentClassName' => 'Omnipay\\Omnipay',
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