<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-client/src/RequestExceptionInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Psr\Http\Client\RequestExceptionInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1bcf5fc2b4a6e1a982020caddab497edd889124fd8d17aea1efb1895cb96ee38-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Psr\\Http\\Client\\RequestExceptionInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-client/src/RequestExceptionInterface.php',
      ),
    ),
    'namespace' => 'Psr\\Http\\Client',
    'name' => 'Psr\\Http\\Client\\RequestExceptionInterface',
    'shortName' => 'RequestExceptionInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Exception for when a request failed.
 *
 * Examples:
 *      - Request is invalid (e.g. method is missing)
 *      - Runtime request errors (e.g. the body stream is not seekable)
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
        'declaringClassName' => 'Psr\\Http\\Client\\RequestExceptionInterface',
        'implementingClassName' => 'Psr\\Http\\Client\\RequestExceptionInterface',
        'currentClassName' => 'Psr\\Http\\Client\\RequestExceptionInterface',
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