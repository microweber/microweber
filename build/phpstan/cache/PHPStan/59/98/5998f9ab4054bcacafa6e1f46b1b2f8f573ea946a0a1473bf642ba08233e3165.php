<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-message/src/UriInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Psr\Http\Message\UriInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8d96e63fb984db31eaedd858ae60549dcf6332c939d4cc940ca97f1fe6d91d1e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Psr\\Http\\Message\\UriInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/http-message/src/UriInterface.php',
      ),
    ),
    'namespace' => 'Psr\\Http\\Message',
    'name' => 'Psr\\Http\\Message\\UriInterface',
    'shortName' => 'UriInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Value object representing a URI.
 *
 * This interface is meant to represent URIs according to RFC 3986 and to
 * provide methods for most common operations. Additional functionality for
 * working with URIs can be provided on top of the interface or externally.
 * Its primary use is for HTTP requests, but may also be used in other
 * contexts.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * Typically the Host header will be also be present in the request message.
 * For server-side requests, the scheme will typically be discoverable in the
 * server parameters.
 *
 * @link http://tools.ietf.org/html/rfc3986 (the URI specification)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 324,
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
      'getScheme' => 
      array (
        'name' => 'getScheme',
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
 * Retrieve the scheme component of the URI.
 *
 * If no scheme is present, this method MUST return an empty string.
 *
 * The value returned MUST be normalized to lowercase, per RFC 3986
 * Section 3.1.
 *
 * The trailing ":" character is not part of the scheme and MUST NOT be
 * added.
 *
 * @see https://tools.ietf.org/html/rfc3986#section-3.1
 * @return string The URI scheme.
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getAuthority' => 
      array (
        'name' => 'getAuthority',
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
 * Retrieve the authority component of the URI.
 *
 * If no authority information is present, this method MUST return an empty
 * string.
 *
 * The authority syntax of the URI is:
 *
 * <pre>
 * [user-info@]host[:port]
 * </pre>
 *
 * If the port component is not set or is the standard port for the current
 * scheme, it SHOULD NOT be included.
 *
 * @see https://tools.ietf.org/html/rfc3986#section-3.2
 * @return string The URI authority, in "[user-info@]host[:port]" format.
 */',
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getUserInfo' => 
      array (
        'name' => 'getUserInfo',
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
 * Retrieve the user information component of the URI.
 *
 * If no user information is present, this method MUST return an empty
 * string.
 *
 * If a user is present in the URI, this will return that value;
 * additionally, if the password is also present, it will be appended to the
 * user value, with a colon (":") separating the values.
 *
 * The trailing "@" character is not part of the user information and MUST
 * NOT be added.
 *
 * @return string The URI user information, in "username[:password]" format.
 */',
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getHost' => 
      array (
        'name' => 'getHost',
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
 * Retrieve the host component of the URI.
 *
 * If no host is present, this method MUST return an empty string.
 *
 * The value returned MUST be normalized to lowercase, per RFC 3986
 * Section 3.2.2.
 *
 * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
 * @return string The URI host.
 */',
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getPort' => 
      array (
        'name' => 'getPort',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'int',
                  'isIdentifier' => true,
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
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve the port component of the URI.
 *
 * If a port is present, and it is non-standard for the current scheme,
 * this method MUST return it as an integer. If the port is the standard port
 * used with the current scheme, this method SHOULD return null.
 *
 * If no port is present, and no scheme is present, this method MUST return
 * a null value.
 *
 * If no port is present, but a scheme is present, this method MAY return
 * the standard port for that scheme, but SHOULD return null.
 *
 * @return null|int The URI port.
 */',
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getPath' => 
      array (
        'name' => 'getPath',
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
 * Retrieve the path component of the URI.
 *
 * The path can either be empty or absolute (starting with a slash) or
 * rootless (not starting with a slash). Implementations MUST support all
 * three syntaxes.
 *
 * Normally, the empty path "" and absolute path "/" are considered equal as
 * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
 * do this normalization because in contexts with a trimmed base path, e.g.
 * the front controller, this difference becomes significant. It\'s the task
 * of the user to handle both "" and "/".
 *
 * The value returned MUST be percent-encoded, but MUST NOT double-encode
 * any characters. To determine what characters to encode, please refer to
 * RFC 3986, Sections 2 and 3.3.
 *
 * As an example, if the value should include a slash ("/") not intended as
 * delimiter between path segments, that value MUST be passed in encoded
 * form (e.g., "%2F") to the instance.
 *
 * @see https://tools.ietf.org/html/rfc3986#section-2
 * @see https://tools.ietf.org/html/rfc3986#section-3.3
 * @return string The URI path.
 */',
        'startLine' => 135,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getQuery' => 
      array (
        'name' => 'getQuery',
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
 * Retrieve the query string of the URI.
 *
 * If no query string is present, this method MUST return an empty string.
 *
 * The leading "?" character is not part of the query and MUST NOT be
 * added.
 *
 * The value returned MUST be percent-encoded, but MUST NOT double-encode
 * any characters. To determine what characters to encode, please refer to
 * RFC 3986, Sections 2 and 3.4.
 *
 * As an example, if a value in a key/value pair of the query string should
 * include an ampersand ("&") not intended as a delimiter between values,
 * that value MUST be passed in encoded form (e.g., "%26") to the instance.
 *
 * @see https://tools.ietf.org/html/rfc3986#section-2
 * @see https://tools.ietf.org/html/rfc3986#section-3.4
 * @return string The URI query string.
 */',
        'startLine' => 157,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'getFragment' => 
      array (
        'name' => 'getFragment',
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
 * Retrieve the fragment component of the URI.
 *
 * If no fragment is present, this method MUST return an empty string.
 *
 * The leading "#" character is not part of the fragment and MUST NOT be
 * added.
 *
 * The value returned MUST be percent-encoded, but MUST NOT double-encode
 * any characters. To determine what characters to encode, please refer to
 * RFC 3986, Sections 2 and 3.5.
 *
 * @see https://tools.ietf.org/html/rfc3986#section-2
 * @see https://tools.ietf.org/html/rfc3986#section-3.5
 * @return string The URI fragment.
 */',
        'startLine' => 175,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withScheme' => 
      array (
        'name' => 'withScheme',
        'parameters' => 
        array (
          'scheme' => 
          array (
            'name' => 'scheme',
            'default' => NULL,
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
            'startLine' => 192,
            'endLine' => 192,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified scheme.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified scheme.
 *
 * Implementations MUST support the schemes "http" and "https" case
 * insensitively, and MAY accommodate other schemes if required.
 *
 * An empty scheme is equivalent to removing the scheme.
 *
 * @param string $scheme The scheme to use with the new instance.
 * @return static A new instance with the specified scheme.
 * @throws \\InvalidArgumentException for invalid or unsupported schemes.
 */',
        'startLine' => 192,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 61,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withUserInfo' => 
      array (
        'name' => 'withUserInfo',
        'parameters' => 
        array (
          'user' => 
          array (
            'name' => 'user',
            'default' => NULL,
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
            'startLine' => 208,
            'endLine' => 208,
            'startColumn' => 34,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'password' => 
          array (
            'name' => 'password',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 208,
                'endLine' => 208,
                'startTokenPos' => 165,
                'startFilePos' => 7833,
                'endTokenPos' => 165,
                'endFilePos' => 7836,
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
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'startLine' => 208,
            'endLine' => 208,
            'startColumn' => 48,
            'endColumn' => 71,
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
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified user information.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified user information.
 *
 * Password is optional, but the user information MUST include the
 * user; an empty string for the user is equivalent to removing user
 * information.
 *
 * @param string $user The user name to use for authority.
 * @param null|string $password The password associated with $user.
 * @return static A new instance with the specified user information.
 */',
        'startLine' => 208,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 87,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withHost' => 
      array (
        'name' => 'withHost',
        'parameters' => 
        array (
          'host' => 
          array (
            'name' => 'host',
            'default' => NULL,
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
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 30,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified host.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified host.
 *
 * An empty host value is equivalent to removing the host.
 *
 * @param string $host The hostname to use with the new instance.
 * @return static A new instance with the specified host.
 * @throws \\InvalidArgumentException for invalid hostnames.
 */',
        'startLine' => 222,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withPort' => 
      array (
        'name' => 'withPort',
        'parameters' => 
        array (
          'port' => 
          array (
            'name' => 'port',
            'default' => NULL,
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
                      'name' => 'int',
                      'isIdentifier' => true,
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
            'startLine' => 241,
            'endLine' => 241,
            'startColumn' => 30,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified port.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified port.
 *
 * Implementations MUST raise an exception for ports outside the
 * established TCP and UDP port ranges.
 *
 * A null value provided for the port is equivalent to removing the port
 * information.
 *
 * @param null|int $port The port to use with the new instance; a null value
 *     removes the port information.
 * @return static A new instance with the specified port.
 * @throws \\InvalidArgumentException for invalid ports.
 */',
        'startLine' => 241,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withPath' => 
      array (
        'name' => 'withPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
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
            'startLine' => 265,
            'endLine' => 265,
            'startColumn' => 30,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified path.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified path.
 *
 * The path can either be empty or absolute (starting with a slash) or
 * rootless (not starting with a slash). Implementations MUST support all
 * three syntaxes.
 *
 * If the path is intended to be domain-relative rather than path relative then
 * it must begin with a slash ("/"). Paths not starting with a slash ("/")
 * are assumed to be relative to some base path known to the application or
 * consumer.
 *
 * Users can provide both encoded and decoded path characters.
 * Implementations ensure the correct encoding as outlined in getPath().
 *
 * @param string $path The path to use with the new instance.
 * @return static A new instance with the specified path.
 * @throws \\InvalidArgumentException for invalid paths.
 */',
        'startLine' => 265,
        'endLine' => 265,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withQuery' => 
      array (
        'name' => 'withQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
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
            'startLine' => 282,
            'endLine' => 282,
            'startColumn' => 31,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified query string.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified query string.
 *
 * Users can provide both encoded and decoded query characters.
 * Implementations ensure the correct encoding as outlined in getQuery().
 *
 * An empty query string value is equivalent to removing the query string.
 *
 * @param string $query The query string to use with the new instance.
 * @return static A new instance with the specified query string.
 * @throws \\InvalidArgumentException for invalid query strings.
 */',
        'startLine' => 282,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      'withFragment' => 
      array (
        'name' => 'withFragment',
        'parameters' => 
        array (
          'fragment' => 
          array (
            'name' => 'fragment',
            'default' => NULL,
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
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 34,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Psr\\Http\\Message\\UriInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified URI fragment.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified URI fragment.
 *
 * Users can provide both encoded and decoded fragment characters.
 * Implementations ensure the correct encoding as outlined in getFragment().
 *
 * An empty fragment value is equivalent to removing the fragment.
 *
 * @param string $fragment The fragment to use with the new instance.
 * @return static A new instance with the specified fragment.
 */',
        'startLine' => 298,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
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
 * Return the string representation as a URI reference.
 *
 * Depending on which components of the URI are present, the resulting
 * string is either a full URI or relative reference according to RFC 3986,
 * Section 4.1. The method concatenates the various components of the URI,
 * using the appropriate delimiters:
 *
 * - If a scheme is present, it MUST be suffixed by ":".
 * - If an authority is present, it MUST be prefixed by "//".
 * - The path can be concatenated without delimiters. But there are two
 *   cases where the path has to be adjusted to make the URI reference
 *   valid as PHP does not allow to throw an exception in __toString():
 *     - If the path is rootless and an authority is present, the path MUST
 *       be prefixed by "/".
 *     - If the path is starting with more than one "/" and no authority is
 *       present, the starting slashes MUST be reduced to one.
 * - If a query is present, it MUST be prefixed by "?".
 * - If a fragment is present, it MUST be prefixed by "#".
 *
 * @see http://tools.ietf.org/html/rfc3986#section-4.1
 * @return string
 */',
        'startLine' => 323,
        'endLine' => 323,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Http\\Message',
        'declaringClassName' => 'Psr\\Http\\Message\\UriInterface',
        'implementingClassName' => 'Psr\\Http\\Message\\UriInterface',
        'currentClassName' => 'Psr\\Http\\Message\\UriInterface',
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