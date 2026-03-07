<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Model/Country.php-PHPStan\BetterReflection\Reflection\ReflectionClass-GeoIp2\Model\Country
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ee5ceaf3dd51e3e61d1c31106e602c7a97b6e3cbe07c0aa79f4a68a2c5323fb6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'GeoIp2\\Model\\Country',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Model/Country.php',
      ),
    ),
    'namespace' => 'GeoIp2\\Model',
    'name' => 'GeoIp2\\Model\\Country',
    'shortName' => 'Country',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Model class for the data returned by GeoIP2 Country web service and database.
 *
 * See https://dev.maxmind.com/geoip/docs/web-services?lang=en for more details.
 *
 * @property-read \\GeoIp2\\Record\\Continent $continent Continent data for the
 * requested IP address.
 * @property-read \\GeoIp2\\Record\\Country $country Country data for the requested
 * IP address. This object represents the country where MaxMind believes the
 * end user is located.
 * @property-read \\GeoIp2\\Record\\MaxMind $maxmind Data related to your MaxMind
 * account.
 * @property-read \\GeoIp2\\Record\\Country $registeredCountry Registered country
 * data for the requested IP address. This record represents the country
 * where the ISP has registered a given IP block and may differ from the
 * user\'s country.
 * @property-read \\GeoIp2\\Record\\RepresentedCountry $representedCountry
 * Represented country data for the requested IP address. The represented
 * country is used for things like military bases. It is only present when
 * the represented country differs from the country.
 * @property-read \\GeoIp2\\Record\\Traits $traits Data for the traits of the
 * requested IP address.
 * @property-read array $raw The raw data from the web service.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 31,
    'endLine' => 96,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'GeoIp2\\Model\\AbstractModel',
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
      'continent' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'continent',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\GeoIp2\\Record\\Continent
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'country' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'country',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\GeoIp2\\Record\\Country
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'locales' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'locales',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var array<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'maxmind' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'maxmind',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\GeoIp2\\Record\\MaxMind
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'registeredCountry' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'registeredCountry',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\GeoIp2\\Record\\Country
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'representedCountry' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'representedCountry',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\GeoIp2\\Record\\RepresentedCountry
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'traits' => 
      array (
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'name' => 'traits',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var \\GeoIp2\\Record\\Traits
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 22,
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
          'raw' => 
          array (
            'name' => 'raw',
            'default' => NULL,
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 33,
            'endColumn' => 42,
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
                'startLine' => 71,
                'endLine' => 71,
                'startTokenPos' => 95,
                'startFilePos' => 1978,
                'endTokenPos' => 97,
                'endFilePos' => 1983,
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 45,
            'endColumn' => 67,
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
 * @ignore
 */',
        'startLine' => 71,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Model',
        'declaringClassName' => 'GeoIp2\\Model\\Country',
        'implementingClassName' => 'GeoIp2\\Model\\Country',
        'currentClassName' => 'GeoIp2\\Model\\Country',
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