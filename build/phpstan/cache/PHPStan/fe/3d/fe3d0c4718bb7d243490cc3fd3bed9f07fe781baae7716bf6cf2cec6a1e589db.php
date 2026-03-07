<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-client/src/NetworkExceptionInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Psr\Http\Client\NetworkExceptionInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-93b74d294718e772af07780a6df2bb13671701df8a1d1e752d79cb3decd08f66-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Psr\\Http\\Client\\NetworkExceptionInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-client/src/NetworkExceptionInterface.php',
      ),
    ),
    'namespace' => 'Psr\\Http\\Client',
    'name' => 'Psr\\Http\\Client\\NetworkExceptionInterface',
    'shortName' => 'NetworkExceptionInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Thrown when the request cannot be completed because of network issues.
 *
 * There is no response object as this exception is thrown when no response has been received.
 *
 * Example: the target host name can not be resolved or the connection failed.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 24,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Psr\\Http\\Client\\ClientExceptionInterface',
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
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\RequestInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the request.
 *
 * The request object MAY be a different object from the one passed to ClientInterface::sendRequest()
 *
 * @return RequestInterface
 */',
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Client',
        'declaringClassName' => 'Psr\\Http\\Client\\NetworkExceptionInterface',
        'implementingClassName' => 'Psr\\Http\\Client\\NetworkExceptionInterface',
        'currentClassName' => 'Psr\\Http\\Client\\NetworkExceptionInterface',
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