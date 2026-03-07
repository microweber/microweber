<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-message/src/ResponseInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Psr\Http\Message\ResponseInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1cc32dded30a668714b17688a2be8a0053effb7fabe7655bd4459ecfbc6eeb44-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Psr\\Http\\Message\\ResponseInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-message/src/ResponseInterface.php',
      ),
    ),
    'namespace' => 'Psr\\Http\\Message',
    'name' => 'Psr\\Http\\Message\\ResponseInterface',
    'shortName' => 'ResponseInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 68,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Psr\\Http\\Message\\MessageInterface',
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
      'getStatusCode' => 
      array (
        'name' => 'getStatusCode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the response status code.
 *
 * The status code is a 3-digit integer result code of the server\'s attempt
 * to understand and satisfy the request.
 *
 * @return int Status code.
 */',
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'currentClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'withStatus' => 
      array (
        'name' => 'withStatus',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'reasonPhrase' => 
          array (
            'name' => 'reasonPhrase',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 52,
                'endLine' => 52,
                'startTokenPos' => 52,
                'startFilePos' => 1910,
                'endTokenPos' => 52,
                'endFilePos' => 1911,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 43,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\ResponseInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified status code and, optionally, reason phrase.
 *
 * If no reason phrase is specified, implementations MAY choose to default
 * to the RFC 7231 or IANA recommended reason phrase for the response\'s
 * status code.
 *
 * This method MUST be implemented in such a way as to retain the
 * immutability of the message, and MUST return an instance that has the
 * updated status and reason phrase.
 *
 * @link http://tools.ietf.org/html/rfc7231#section-6
 * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
 * @param int $code The 3-digit integer result code to set.
 * @param string $reasonPhrase The reason phrase to use with the
 *     provided status code; if none is provided, implementations MAY
 *     use the defaults as suggested in the HTTP specification.
 * @return static
 * @throws \\InvalidArgumentException For invalid status code arguments.
 */',
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 88,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'currentClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'aliasName' => NULL,
      ),
      'getReasonPhrase' => 
      array (
        'name' => 'getReasonPhrase',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the response reason phrase associated with the status code.
 *
 * Because a reason phrase is not a required element in a response
 * status line, the reason phrase value MAY be null. Implementations MAY
 * choose to return the default RFC 7231 recommended reason phrase (or those
 * listed in the IANA HTTP Status Code Registry) for the response\'s
 * status code.
 *
 * @link http://tools.ietf.org/html/rfc7231#section-6
 * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
 * @return string Reason phrase; must return an empty string if none present.
 */',
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\ResponseInterface',
        'currentClassName' => 'Psr\\Http\\Message\\ResponseInterface',
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