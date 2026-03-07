<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Database/Reader.php-PHPStan\BetterReflection\Reflection\ReflectionClass-GeoIp2\Database\Reader
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ed9caeef519662b607d82e67d2ee37e503751cb8e02418ad19c93a77814b9fa3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'GeoIp2\\Database\\Reader',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Database/Reader.php',
      ),
    ),
    'namespace' => 'GeoIp2\\Database',
    'name' => 'GeoIp2\\Database\\Reader',
    'shortName' => 'Reader',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Instances of this class provide a reader for the GeoIP2 database format.
 * IP addresses can be looked up using the database specific methods.
 *
 * ## Usage ##
 *
 * The basic API for this class is the same for every database. First, you
 * create a reader object, specifying a file name. You then call the method
 * corresponding to the specific database, passing it the IP address you want
 * to look up.
 *
 * If the request succeeds, the method call will return a model class for
 * the method you called. This model in turn contains multiple record classes,
 * each of which represents part of the data returned by the database. If
 * the database does not contain the requested information, the attributes
 * on the record class will have a `null` value.
 *
 * If the address is not in the database, an
 * {@link \\GeoIp2\\Exception\\AddressNotFoundException} exception will be
 * thrown. If an invalid IP address is passed to one of the methods, a
 * SPL {@link \\InvalidArgumentException} will be thrown. If the database is
 * corrupt or invalid, a {@link \\MaxMind\\Db\\Reader\\InvalidDatabaseException}
 * will be thrown.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 45,
    'endLine' => 299,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'GeoIp2\\ProviderInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'dbReader' => 
      array (
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'name' => 'dbReader',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var DbReader
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dbType' => 
      array (
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'name' => 'dbType',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'locales' => 
      array (
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'name' => 'locales',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var array<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 21,
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
          'filename' => 
          array (
            'name' => 'filename',
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
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 9,
            'endColumn' => 24,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'locales' => 
          array (
            'name' => 'locales',
            'default' => 
            array (
              'code' => '[\'en\']',
              'attributes' => 
              array (
                'startLine' => 74,
                'endLine' => 74,
                'startTokenPos' => 137,
                'startFilePos' => 2331,
                'endTokenPos' => 139,
                'endFilePos' => 2336,
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
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 9,
            'endColumn' => 31,
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
 * Constructor.
 *
 * @param string $filename the path to the GeoIP2 database file
 * @param array  $locales  list of locale codes to use in name property
 *                         from most preferred to least preferred
 *
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 72,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'city' => 
      array (
        'name' => 'city',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 26,
            'endColumn' => 42,
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
            'name' => 'GeoIp2\\Model\\City',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 City model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 91,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'country' => 
      array (
        'name' => 'country',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 29,
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
            'name' => 'GeoIp2\\Model\\Country',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 Country model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 107,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'anonymousIp' => 
      array (
        'name' => 'anonymousIp',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 33,
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
            'name' => 'GeoIp2\\Model\\AnonymousIp',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 Anonymous IP model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 123,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'asn' => 
      array (
        'name' => 'asn',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 25,
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
            'name' => 'GeoIp2\\Model\\Asn',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoLite2 ASN model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 143,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'connectionType' => 
      array (
        'name' => 'connectionType',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 36,
            'endColumn' => 52,
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
            'name' => 'GeoIp2\\Model\\ConnectionType',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 Connection Type model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 163,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'domain' => 
      array (
        'name' => 'domain',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 28,
            'endColumn' => 44,
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
            'name' => 'GeoIp2\\Model\\Domain',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 Domain model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 183,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'enterprise' => 
      array (
        'name' => 'enterprise',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 32,
            'endColumn' => 48,
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
            'name' => 'GeoIp2\\Model\\Enterprise',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 Enterprise model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 203,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'isp' => 
      array (
        'name' => 'isp',
        'parameters' => 
        array (
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 25,
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
            'name' => 'GeoIp2\\Model\\Isp',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method returns a GeoIP2 ISP model.
 *
 * @param string $ipAddress an IPv4 or IPv6 address as a string
 *
 * @throws \\GeoIp2\\Exception\\AddressNotFoundException  if the address is
 *                                                     not in the database
 * @throws \\MaxMind\\Db\\Reader\\InvalidDatabaseException if the database
 *                                                     is corrupt or invalid
 */',
        'startLine' => 219,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'modelFor' => 
      array (
        'name' => 'modelFor',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
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
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 31,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 46,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 60,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'GeoIp2\\Model\\AbstractModel',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 229,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'flatModelFor' => 
      array (
        'name' => 'flatModelFor',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
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
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 50,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 64,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'GeoIp2\\Model\\AbstractModel',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 239,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'getRecord' => 
      array (
        'name' => 'getRecord',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
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
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 32,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'ipAddress' => 
          array (
            'name' => 'ipAddress',
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
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 61,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 249,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'metadata' => 
      array (
        'name' => 'metadata',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'MaxMind\\Db\\Reader\\Metadata',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws \\InvalidArgumentException if arguments are passed to the method
 * @throws \\BadMethodCallException   if the database has been closed
 *
 * @return \\MaxMind\\Db\\Reader\\Metadata object for the database
 */',
        'startLine' => 287,
        'endLine' => 290,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
        'aliasName' => NULL,
      ),
      'close' => 
      array (
        'name' => 'close',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Closes the GeoIP2 database and returns the resources to the system.
 */',
        'startLine' => 295,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Database',
        'declaringClassName' => 'GeoIp2\\Database\\Reader',
        'implementingClassName' => 'GeoIp2\\Database\\Reader',
        'currentClassName' => 'GeoIp2\\Database\\Reader',
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