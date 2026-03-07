<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/GatewayInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\Common\GatewayInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f32ea429d837e806ff69f8622364a2849104edc69864d84525a4d6b31172dc10-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\Common\\GatewayInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/GatewayInterface.php',
      ),
    ),
    'namespace' => 'Omnipay\\Common',
    'name' => 'Omnipay\\Common\\GatewayInterface',
    'shortName' => 'GatewayInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Payment gateway interface
 *
 * This interface class defines the standard functions that any
 * Omnipay gateway needs to define.
 *
 *
 * @method \\Omnipay\\Common\\Message\\NotificationInterface acceptNotification(array $options = array()) (Optional method)
 *         Receive and handle an instant payment notification (IPN)
 * @method \\Omnipay\\Common\\Message\\RequestInterface authorize(array $options = array())               (Optional method)
 *         Authorize an amount on the customers card
 * @method \\Omnipay\\Common\\Message\\RequestInterface completeAuthorize(array $options = array())       (Optional method)
 *         Handle return from off-site gateways after authorization
 * @method \\Omnipay\\Common\\Message\\RequestInterface capture(array $options = array())                 (Optional method)
 *         Capture an amount you have previously authorized
 * @method \\Omnipay\\Common\\Message\\RequestInterface purchase(array $options = array())                (Optional method)
 *         Authorize and immediately capture an amount on the customers card
 * @method \\Omnipay\\Common\\Message\\RequestInterface completePurchase(array $options = array())        (Optional method)
 *         Handle return from off-site gateways after purchase
 * @method \\Omnipay\\Common\\Message\\RequestInterface refund(array $options = array())                  (Optional method)
 *         Refund an already processed transaction
 * @method \\Omnipay\\Common\\Message\\RequestInterface fetchTransaction(array $options = [])             (Optional method)
 *         Fetches transaction information
 * @method \\Omnipay\\Common\\Message\\RequestInterface void(array $options = array())                    (Optional method)
 *         Generally can only be called up to 24 hours after submitting a transaction
 * @method \\Omnipay\\Common\\Message\\RequestInterface createCard(array $options = array())              (Optional method)
 *         The returned response object includes a cardReference, which can be used for future transactions
 * @method \\Omnipay\\Common\\Message\\RequestInterface updateCard(array $options = array())              (Optional method)
 *         Update a stored card
 * @method \\Omnipay\\Common\\Message\\RequestInterface deleteCard(array $options = array())              (Optional method)
 *         Delete a stored card
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 40,
    'endLine' => 82,
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
 * @return string
 */',
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\GatewayInterface',
        'implementingClassName' => 'Omnipay\\Common\\GatewayInterface',
        'currentClassName' => 'Omnipay\\Common\\GatewayInterface',
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
 * Get gateway short name
 *
 * This name can be used with GatewayFactory as an alias of the gateway class,
 * to create new instances of this gateway.
 * @return string
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\GatewayInterface',
        'implementingClassName' => 'Omnipay\\Common\\GatewayInterface',
        'currentClassName' => 'Omnipay\\Common\\GatewayInterface',
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
 * @return array
 */',
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\GatewayInterface',
        'implementingClassName' => 'Omnipay\\Common\\GatewayInterface',
        'currentClassName' => 'Omnipay\\Common\\GatewayInterface',
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
                'startLine' => 75,
                'endLine' => 75,
                'startTokenPos' => 63,
                'startFilePos' => 3351,
                'endTokenPos' => 65,
                'endFilePos' => 3357,
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
            'startLine' => 75,
            'endLine' => 75,
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
 * Initialize gateway with parameters
 * @return $this
 */',
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 60,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\GatewayInterface',
        'implementingClassName' => 'Omnipay\\Common\\GatewayInterface',
        'currentClassName' => 'Omnipay\\Common\\GatewayInterface',
        'aliasName' => NULL,
      ),
      'getParameters' => 
      array (
        'name' => 'getParameters',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all gateway parameters
 * @return array
 */',
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common',
        'declaringClassName' => 'Omnipay\\Common\\GatewayInterface',
        'implementingClassName' => 'Omnipay\\Common\\GatewayInterface',
        'currentClassName' => 'Omnipay\\Common\\GatewayInterface',
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