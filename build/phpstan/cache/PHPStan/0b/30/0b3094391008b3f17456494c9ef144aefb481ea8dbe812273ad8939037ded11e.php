<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/ProviderInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-GeoIp2\ProviderInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-64d7c3c3aa685b6dcafdf1aa944843ac02520e7401f45968de75f94daf5f464c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'GeoIp2\\ProviderInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/ProviderInterface.php',
      ),
    ),
    'namespace' => 'GeoIp2',
    'name' => 'GeoIp2\\ProviderInterface',
    'shortName' => 'ProviderInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 22,
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
            'startLine' => 14,
            'endLine' => 14,
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
 * @param string $ipAddress an IPv4 or IPv6 address to lookup
 *
 * @return \\GeoIp2\\Model\\Country a Country model for the requested IP address
 */',
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2',
        'declaringClassName' => 'GeoIp2\\ProviderInterface',
        'implementingClassName' => 'GeoIp2\\ProviderInterface',
        'currentClassName' => 'GeoIp2\\ProviderInterface',
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
            'startLine' => 21,
            'endLine' => 21,
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
 * @param string $ipAddress an IPv4 or IPv6 address to lookup
 *
 * @return \\GeoIp2\\Model\\City a City model for the requested IP address
 */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2',
        'declaringClassName' => 'GeoIp2\\ProviderInterface',
        'implementingClassName' => 'GeoIp2\\ProviderInterface',
        'currentClassName' => 'GeoIp2\\ProviderInterface',
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