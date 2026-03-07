<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Record/Traits.php-PHPStan\BetterReflection\Reflection\ReflectionClass-GeoIp2\Record\Traits
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5fe40ffbf16ff8f98dba71c1b3ba275df84d0df4f321665a0b4c67151eb46d32-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'GeoIp2\\Record\\Traits',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../geoip2/geoip2/src/Record/Traits.php',
      ),
    ),
    'namespace' => 'GeoIp2\\Record',
    'name' => 'GeoIp2\\Record\\Traits',
    'shortName' => 'Traits',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Contains data for the traits record associated with an IP address.
 *
 * This record is returned by all location services and databases.
 *
 * @property-read int|null $autonomousSystemNumber The autonomous system number
 * associated with the IP address. See
 * https://en.wikipedia.org/wiki/Autonomous_system_(Internet%29. This attribute
 * is only available from the City Plus and Insights web services and the
 * GeoIP2 Enterprise database.
 * @property-read string|null $autonomousSystemOrganization The organization
 * associated with the registered autonomous system number for the IP address.
 * See https://en.wikipedia.org/wiki/Autonomous_system_(Internet%29. This
 * attribute is only available from the City Plus and Insights web services and
 * the GeoIP2 Enterprise database.
 * @property-read string|null $connectionType The connection type may take the
 * following  values: "Dialup", "Cable/DSL", "Corporate", "Cellular".
 * Additional values may be added in the future. This attribute is only
 * available in the GeoIP2 Enterprise database.
 * @property-read string|null $domain The second level domain associated with the
 * IP address. This will be something like "example.com" or "example.co.uk",
 * not "foo.example.com". This attribute is only available from the
 * City Plus and Insights web services and the GeoIP2 Enterprise
 * database.
 * @property-read string $ipAddress The IP address that the data in the model
 * is for. If you performed a "me" lookup against the web service, this
 * will be the externally routable IP address for the system the code is
 * running on. If the system is behind a NAT, this may differ from the IP
 * address locally assigned to it. This attribute is returned by all end
 * points.
 * @property-read bool $isAnonymous This is true if the IP address belongs to
 * any sort of anonymous network. This property is only available from GeoIP2
 * Insights.
 * @property-read bool $isAnonymousProxy *Deprecated.* Please see our GeoIP2
 * Anonymous IP database
 * (https://www.maxmind.com/en/geoip2-anonymous-ip-database) to determine
 * whether the IP address is used by an anonymizing service.
 * @property-read bool $isAnonymousVpn This is true if the IP address is
 * registered to an anonymous VPN provider. If a VPN provider does not register
 * subnets under names associated with them, we will likely only flag their IP
 * ranges using the isHostingProvider property. This property is only available
 * from GeoIP2 Insights.
 * @property-read bool $isHostingProvider This is true if the IP address belongs
 * to a hosting or VPN provider (see description of isAnonymousVpn property).
 * This property is only available from GeoIP2 Insights.
 * @property-read bool $isLegitimateProxy This attribute is true if MaxMind
 * believes this IP address to be a legitimate proxy, such as an internal
 * VPN used by a corporation. This attribute is only available in the GeoIP2
 * Enterprise database.
 * @property-read bool $isPublicProxy This is true if the IP address belongs to
 * a public proxy. This property is only available from GeoIP2 Insights.
 * @property-read bool $isResidentialProxy This is true if the IP address is
 * on a suspected anonymizing network and belongs to a residential ISP. This
 * property is only available from GeoIP2 Insights.
 * @property-read bool $isSatelliteProvider *Deprecated.* Due to the
 * increased coverage by mobile carriers, very few satellite providers now
 * serve multiple countries. As a result, the output does not provide
 * sufficiently relevant data for us to maintain it.
 * @property-read bool $isTorExitNode This is true if the IP address is a Tor
 * exit node. This property is only available from GeoIP2 Insights.
 * @property-read string|null $isp The name of the ISP associated with the IP
 * address. This attribute is only available from the City Plus and Insights
 * web services and the GeoIP2 Enterprise database.
 * @property-read string $network The network in CIDR notation associated with
 * the record. In particular, this is the largest network where all of the
 * fields besides $ipAddress have the same value.
 * @property-read string|null $organization The name of the organization
 * associated with the IP address. This attribute is only available from the
 * City Plus and Insights web services and the GeoIP2 Enterprise database.
 * @property-read string|null $mobileCountryCode The [mobile country code
 * (MCC)](https://en.wikipedia.org/wiki/Mobile_country_code) associated with
 * the IP address and ISP. This property is available from the City Plus and
 * Insights web services and the GeoIP2 Enterprise database.
 * @property-read string|null $mobileNetworkCode The [mobile network code
 * (MNC)](https://en.wikipedia.org/wiki/Mobile_country_code) associated with
 * the IP address and ISP. This property is available from the City Plus and
 * Insights web services and the GeoIP2 Enterprise database.
 * @property-read float|null $staticIpScore An indicator of how static or
 * dynamic an IP address is. This property is only available from GeoIP2
 * Insights.
 * @property-read int|null $userCount The estimated number of users sharing
 * the IP/network during the past 24 hours. For IPv4, the count is for the
 * individual IP. For IPv6, the count is for the /64 network. This property is
 * only available from GeoIP2 Insights.
 * @property-read string|null $userType <p>The user type associated with the IP
 *  address. This can be one of the following values:</p>
 *  <ul>
 *    <li>business
 *    <li>cafe
 *    <li>cellular
 *    <li>college
 *    <li>consumer_privacy_network
 *    <li>content_delivery_network
 *    <li>dialup
 *    <li>government
 *    <li>hosting
 *    <li>library
 *    <li>military
 *    <li>residential
 *    <li>router
 *    <li>school
 *    <li>search_engine_spider
 *    <li>traveler
 * </ul>
 * <p>
 *   This attribute is only available from the Insights web service and the
 *   GeoIP2 Enterprise database.
 * </p>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 118,
    'endLine' => 158,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'GeoIp2\\Record\\AbstractRecord',
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
        'declaringClassName' => 'GeoIp2\\Record\\Traits',
        'implementingClassName' => 'GeoIp2\\Record\\Traits',
        'name' => 'validAttributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'autonomousSystemNumber\', \'autonomousSystemOrganization\', \'connectionType\', \'domain\', \'ipAddress\', \'isAnonymous\', \'isAnonymousProxy\', \'isAnonymousVpn\', \'isHostingProvider\', \'isLegitimateProxy\', \'isp\', \'isPublicProxy\', \'isResidentialProxy\', \'isSatelliteProvider\', \'isTorExitNode\', \'mobileCountryCode\', \'mobileNetworkCode\', \'network\', \'organization\', \'staticIpScore\', \'userCount\', \'userType\']',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 148,
            'startTokenPos' => 40,
            'startFilePos' => 6213,
            'endTokenPos' => 108,
            'endFilePos' => 6786,
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
        'startLine' => 125,
        'endLine' => 148,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'record' => 
          array (
            'name' => 'record',
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
                      'name' => 'array',
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
            'startLine' => 150,
            'endLine' => 150,
            'startColumn' => 33,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 150,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'GeoIp2\\Record',
        'declaringClassName' => 'GeoIp2\\Record\\Traits',
        'implementingClassName' => 'GeoIp2\\Record\\Traits',
        'currentClassName' => 'GeoIp2\\Record\\Traits',
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