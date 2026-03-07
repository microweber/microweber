<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/Message/ResponseInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\Common\Message\ResponseInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4b4899e5e542bdc6fa123e725e8f3a5e3236e2aed6aaf1f507b46d5a2d884aa8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/Message/ResponseInterface.php',
      ),
    ),
    'namespace' => 'Omnipay\\Common\\Message',
    'name' => 'Omnipay\\Common\\Message\\ResponseInterface',
    'shortName' => 'ResponseInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Response Interface
 *
 * This interface class defines the standard functions that any Omnipay response
 * interface needs to be able to provide.  It is an extension of MessageInterface.
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 65,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Omnipay\\Common\\Message\\MessageInterface',
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
      'getRequest' => 
      array (
        'name' => 'getRequest',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the original request which generated this response
 *
 * @return RequestInterface
 */',
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'isSuccessful' => 
      array (
        'name' => 'isSuccessful',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Is the response successful?
 *
 * @return boolean
 */',
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'isRedirect' => 
      array (
        'name' => 'isRedirect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Does the response require a redirect?
 *
 * @return boolean
 */',
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'isCancelled' => 
      array (
        'name' => 'isCancelled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Is the transaction cancelled by the user?
 *
 * @return boolean
 */',
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'getMessage' => 
      array (
        'name' => 'getMessage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Response Message
 *
 * @return null|string A response message from the payment gateway
 */',
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'getCode' => 
      array (
        'name' => 'getCode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Response code
 *
 * @return null|string A response code from the payment gateway
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'getTransactionReference' => 
      array (
        'name' => 'getTransactionReference',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gateway Reference
 *
 * @return null|string A reference provided by the gateway to represent this transaction
 */',
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\ResponseInterface',
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