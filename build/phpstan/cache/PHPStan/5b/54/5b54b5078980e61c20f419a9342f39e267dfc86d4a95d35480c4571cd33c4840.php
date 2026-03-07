<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/Message/NotificationInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Omnipay\Common\Message\NotificationInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ee2708c8efaa4ea6dfba9d1b2ac425da1905ee868dc1130afd4bafb1c0ba5f69-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../omnipay/common/src/Common/Message/NotificationInterface.php',
      ),
    ),
    'namespace' => 'Omnipay\\Common\\Message',
    'name' => 'Omnipay\\Common\\Message\\NotificationInterface',
    'shortName' => 'NotificationInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Incoming notification
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 35,
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
      'STATUS_COMPLETED' => 
      array (
        'declaringClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'name' => 'STATUS_COMPLETED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'completed\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 25,
            'startFilePos' => 163,
            'endTokenPos' => 25,
            'endFilePos' => 173,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'name' => 'STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 34,
            'startFilePos' => 203,
            'endTokenPos' => 34,
            'endFilePos' => 211,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'STATUS_FAILED' => 
      array (
        'declaringClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'name' => 'STATUS_FAILED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'failed\'',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 43,
            'startFilePos' => 240,
            'endTokenPos' => 43,
            'endFilePos' => 247,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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
 * @return string A reference provided by the gateway to represent this transaction
 */',
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'aliasName' => NULL,
      ),
      'getTransactionStatus' => 
      array (
        'name' => 'getTransactionStatus',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Was the transaction successful?
 *
 * @return string Transaction status, one of {@link NotificationInterface::STATUS_COMPLETED},
 * {@link NotificationInterface::STATUS_PENDING}, or {@link NotificationInterface::STATUS_FAILED}.
 */',
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
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
 * @return string A response message from the payment gateway
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Omnipay\\Common\\Message',
        'declaringClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'implementingClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
        'currentClassName' => 'Omnipay\\Common\\Message\\NotificationInterface',
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