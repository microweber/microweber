<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/Message/RequestInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\Common\Message\RequestInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f9bf19dfdde042c3ad1ee3bc7561ef5a858d2ff5bbb96dc65eda8afbdc916b35-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\Common\\Message\\RequestInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/Message/RequestInterface.php',
      ),
    ),
    'namespace' => 'Omnipay\\Common\\Message',
    'name' => 'Omnipay\\Common\\Message\\RequestInterface',
    'shortName' => 'RequestInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Request Interface
 *
 * This interface class defines the standard functions that any Omnipay request
 * interface needs to be able to provide.  It is an extension of MessageInterface.
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 51,
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
                'startLine' => 21,
                'endLine' => 21,
                'startTokenPos' => 34,
                'startFilePos' => 487,
                'endTokenPos' => 36,
                'endFilePos' => 493,
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
            'startLine' => 21,
            'endLine' => 21,
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
 * Initialize request with parameters
 * @param array $parameters The parameters to send
 */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 60,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
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
 * Get all request parameters
 *
 * @return array
 */',
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'aliasName' => NULL,
      ),
      'getResponse' => 
      array (
        'name' => 'getResponse',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the response to this request (if the request has been sent)
 *
 * @return ResponseInterface
 */',
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'aliasName' => NULL,
      ),
      'send' => 
      array (
        'name' => 'send',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Send the request
 *
 * @return ResponseInterface
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 27,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'aliasName' => NULL,
      ),
      'sendData' => 
      array (
        'name' => 'sendData',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
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
            'startColumn' => 30,
            'endColumn' => 34,
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
 * Send the request with specified data
 *
 * @param  mixed             $data The data to send
 * @return ResponseInterface
 */',
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\RequestInterface',
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