<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Record/Country.php-PHPStan\BetterReflection\Reflection\ReflectionClass-GeoIp2\Record\Country
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0078bb8c2da9bcdd7a41e844fd725512bb27fe4a01842b404fbb277a481e4ed9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'GeoIp2\\Record\\Country',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Record/Country.php',
      ),
    ),
    'namespace' => 'GeoIp2\\Record',
    'name' => 'GeoIp2\\Record\\Country',
    'shortName' => 'Country',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Contains data for the country record associated with an IP address.
 *
 * This record is returned by all location services and databases.
 *
 * @property-read int|null $confidence A value from 0-100 indicating MaxMind\'s
 * confidence that the country is correct. This attribute is only available
 * from the Insights service and the GeoIP2 Enterprise database.
 * @property-read int|null $geonameId The GeoName ID for the country. This
 * attribute is returned by all location services and databases.
 * @property-read bool $isInEuropeanUnion This is true if the country is a
 * member state of the European Union. This attribute is returned by all
 * location services and databases.
 * @property-read string|null $isoCode The two-character ISO 3166-1 alpha code
 * for the country. See https://en.wikipedia.org/wiki/ISO_3166-1. This
 * attribute is returned by all location services and databases.
 * @property-read string|null $name The name of the country based on the locales
 * list passed to the constructor. This attribute is returned by all location
 * services and databases.
 * @property-read array|null $names An array map where the keys are locale codes
 * and the values are names. This attribute is returned by all location
 * services and databases.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 30,
    'endLine' => 44,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'GeoIp2\\Record\\AbstractPlaceRecord',
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
      'validAttributes' => 
      array (
        'declaringClassName' => 'GeoIp2\\Record\\Country',
        'implementingClassName' => 'GeoIp2\\Record\\Country',
        'name' => 'validAttributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'confidence\', \'geonameId\', \'isInEuropeanUnion\', \'isoCode\', \'names\']',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 43,
            'startTokenPos' => 35,
            'startFilePos' => 1477,
            'endTokenPos' => 52,
            'endFilePos' => 1591,
          ),
        ),
        'docComment' => '/**
 * @ignore
 *
 * @var array<string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 6,
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