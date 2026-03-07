<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Motorcycle.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\Motorcycle
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f3ecb6f8bff225893a07a96525d18b0d958563ad1ab05c927948560649981b68-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\Motorcycle',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Motorcycle.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\Motorcycle',
    'shortName' => 'Motorcycle',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A motorcycle or motorbike is a single-track, two-wheeled motor vehicle.
 *
 * @see https://schema.org/Motorcycle
 * @see https://auto.schema.org
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 1943,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\MotorcycleContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\ProductContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\VehicleContract',
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
      'accelerationTime' => 
      array (
        'name' => 'accelerationTime',
        'parameters' => 
        array (
          'accelerationTime' => 
          array (
            'name' => 'accelerationTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The time needed to accelerate the vehicle from a given start velocity to
 * a given target velocity.
 *
 * Typical unit code(s): SEC for seconds
 *
 * * Note: There are unfortunately no standard unit codes for seconds/0..100
 * km/h or seconds/0..60 mph. Simply use "SEC" for seconds and indicate the
 * velocities in the [[name]] of the [[QuantitativeValue]], or use
 * [[valueReference]] with a [[QuantitativeValue]] of 0..60 mph or 0..100
 * km/h to specify the reference speeds.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $accelerationTime
 *
 * @return static
 *
 * @see https://schema.org/accelerationTime
 * @see https://auto.schema.org
 */',
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'additionalProperty' => 
      array (
        'name' => 'additionalProperty',
        'parameters' => 
        array (
          'additionalProperty' => 
          array (
            'name' => 'additionalProperty',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 40,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A property-value pair representing an additional characteristic of the
 * entity, e.g. a product feature or another characteristic for which there
 * is no matching property in schema.org.
 *
 * Note: Publishers should be aware that applications designed to use
 * specific schema.org properties (e.g. https://schema.org/width,
 * https://schema.org/color, https://schema.org/gtin13, ...) will typically
 * expect such data to be provided using those properties, rather than using
 * the generic property/value mechanism.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[] $additionalProperty
 *
 * @return static
 *
 * @see https://schema.org/additionalProperty
 */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'additionalType' => 
      array (
        'name' => 'additionalType',
        'parameters' => 
        array (
          'additionalType' => 
          array (
            'name' => 'additionalType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 36,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An additional type for the item, typically used for adding more specific
 * types from external vocabularies in microdata syntax. This is a
 * relationship between something and a class that the thing is in.
 * Typically the value is a URI-identified RDF class, and in this case
 * corresponds to the
 *     use of rdf:type in RDF. Text values can be used sparingly, for cases
 * where useful information can be added without their being an appropriate
 * schema to reference. In the case of text values, the class label should
 * follow the schema.org [style
 * guide](https://schema.org/docs/styleguide.html).
 *
 * @param string|string[] $additionalType
 *
 * @return static
 *
 * @see https://schema.org/additionalType
 */',
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'aggregateRating' => 
      array (
        'name' => 'aggregateRating',
        'parameters' => 
        array (
          'aggregateRating' => 
          array (
            'name' => 'aggregateRating',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The overall rating, based on a collection of reviews or ratings, of the
 * item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AggregateRatingContract|\\Spatie\\SchemaOrg\\Contracts\\AggregateRatingContract[] $aggregateRating
 *
 * @return static
 *
 * @see https://schema.org/aggregateRating
 */',
        'startLine' => 98,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'alternateName' => 
      array (
        'name' => 'alternateName',
        'parameters' => 
        array (
          'alternateName' => 
          array (
            'name' => 'alternateName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An alias for the item.
 *
 * @param string|string[] $alternateName
 *
 * @return static
 *
 * @see https://schema.org/alternateName
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'asin' => 
      array (
        'name' => 'asin',
        'parameters' => 
        array (
          'asin' => 
          array (
            'name' => 'asin',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An Amazon Standard Identification Number (ASIN) is a 10-character
 * alphanumeric unique identifier assigned by Amazon.com and its partners
 * for product identification within the Amazon organization (summary from
 * [Wikipedia](https://en.wikipedia.org/wiki/Amazon_Standard_Identification_Number)\'s
 * article).
 *
 * Note also that this is a definition for how to include ASINs in
 * Schema.org data, and not a definition of ASINs in general - see
 * documentation from Amazon for authoritative details.
 * ASINs are most commonly encoded as text strings, but the [asin] property
 * supports URL/URI as potential values too.
 *
 * @param string|string[] $asin
 *
 * @return static
 *
 * @see https://schema.org/asin
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 138,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'audience' => 
      array (
        'name' => 'audience',
        'parameters' => 
        array (
          'audience' => 
          array (
            'name' => 'audience',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An intended audience, i.e. a group for whom something was created.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AudienceContract|\\Spatie\\SchemaOrg\\Contracts\\AudienceContract[] $audience
 *
 * @return static
 *
 * @see https://schema.org/audience
 */',
        'startLine' => 152,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'award' => 
      array (
        'name' => 'award',
        'parameters' => 
        array (
          'award' => 
          array (
            'name' => 'award',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 166,
            'endLine' => 166,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An award won by or for this item.
 *
 * @param string|string[] $award
 *
 * @return static
 *
 * @see https://schema.org/award
 */',
        'startLine' => 166,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'awards' => 
      array (
        'name' => 'awards',
        'parameters' => 
        array (
          'awards' => 
          array (
            'name' => 'awards',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 180,
            'endLine' => 180,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Awards won by or for this item.
 *
 * @param string|string[] $awards
 *
 * @return static
 *
 * @see https://schema.org/awards
 */',
        'startLine' => 180,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'bodyType' => 
      array (
        'name' => 'bodyType',
        'parameters' => 
        array (
          'bodyType' => 
          array (
            'name' => 'bodyType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates the design and body style of the vehicle (e.g. station wagon,
 * hatchback, etc.).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract[]|string|string[] $bodyType
 *
 * @return static
 *
 * @see https://schema.org/bodyType
 * @see https://auto.schema.org
 */',
        'startLine' => 196,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'brand' => 
      array (
        'name' => 'brand',
        'parameters' => 
        array (
          'brand' => 
          array (
            'name' => 'brand',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 211,
            'endLine' => 211,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The brand(s) associated with a product or service, or the brand(s)
 * maintained by an organization or business person.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\BrandContract|\\Spatie\\SchemaOrg\\Contracts\\BrandContract[]|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $brand
 *
 * @return static
 *
 * @see https://schema.org/brand
 */',
        'startLine' => 211,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'callSign' => 
      array (
        'name' => 'callSign',
        'parameters' => 
        array (
          'callSign' => 
          array (
            'name' => 'callSign',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A [callsign](https://en.wikipedia.org/wiki/Call_sign), as used in
 * broadcasting and radio communications to identify people, radio and TV
 * stations, or vehicles.
 *
 * @param string|string[] $callSign
 *
 * @return static
 *
 * @see https://schema.org/callSign
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2109
 */',
        'startLine' => 229,
        'endLine' => 232,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'cargoVolume' => 
      array (
        'name' => 'cargoVolume',
        'parameters' => 
        array (
          'cargoVolume' => 
          array (
            'name' => 'cargoVolume',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The available volume for cargo or luggage. For automobiles, this is
 * usually the trunk volume.
 *
 * Typical unit code(s): LTR for liters, FTQ for cubic foot/feet
 *
 * Note: You can use [[minValue]] and [[maxValue]] to indicate ranges.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $cargoVolume
 *
 * @return static
 *
 * @see https://schema.org/cargoVolume
 */',
        'startLine' => 248,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'category' => 
      array (
        'name' => 'category',
        'parameters' => 
        array (
          'category' => 
          array (
            'name' => 'category',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A category for the item. Greater signs or slashes can be used to
 * informally indicate a category hierarchy.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CategoryCodeContract|\\Spatie\\SchemaOrg\\Contracts\\CategoryCodeContract[]|\\Spatie\\SchemaOrg\\Contracts\\PhysicalActivityCategoryContract|\\Spatie\\SchemaOrg\\Contracts\\PhysicalActivityCategoryContract[]|\\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[]|string|string[] $category
 *
 * @return static
 *
 * @see https://schema.org/category
 */',
        'startLine' => 263,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'color' => 
      array (
        'name' => 'color',
        'parameters' => 
        array (
          'color' => 
          array (
            'name' => 'color',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The color of the product.
 *
 * @param string|string[] $color
 *
 * @return static
 *
 * @see https://schema.org/color
 */',
        'startLine' => 277,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'colorSwatch' => 
      array (
        'name' => 'colorSwatch',
        'parameters' => 
        array (
          'colorSwatch' => 
          array (
            'name' => 'colorSwatch',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 295,
            'endLine' => 295,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A color swatch image, visualizing the color of a [[Product]]. Should
 * match the textual description specified in the [[color]] property. This
 * can be a URL or a fully described ImageObject.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract|\\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract[]|string|string[] $colorSwatch
 *
 * @return static
 *
 * @see https://schema.org/colorSwatch
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3423
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
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'countryOfAssembly' => 
      array (
        'name' => 'countryOfAssembly',
        'parameters' => 
        array (
          'countryOfAssembly' => 
          array (
            'name' => 'countryOfAssembly',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 311,
            'endLine' => 311,
            'startColumn' => 39,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The place where the product was assembled.
 *
 * @param string|string[] $countryOfAssembly
 *
 * @return static
 *
 * @see https://schema.org/countryOfAssembly
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/991
 */',
        'startLine' => 311,
        'endLine' => 314,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'countryOfLastProcessing' => 
      array (
        'name' => 'countryOfLastProcessing',
        'parameters' => 
        array (
          'countryOfLastProcessing' => 
          array (
            'name' => 'countryOfLastProcessing',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 328,
            'endLine' => 328,
            'startColumn' => 45,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The place where the item (typically [[Product]]) was last processed and
 * tested before importation.
 *
 * @param string|string[] $countryOfLastProcessing
 *
 * @return static
 *
 * @see https://schema.org/countryOfLastProcessing
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/991
 */',
        'startLine' => 328,
        'endLine' => 331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'countryOfOrigin' => 
      array (
        'name' => 'countryOfOrigin',
        'parameters' => 
        array (
          'countryOfOrigin' => 
          array (
            'name' => 'countryOfOrigin',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 353,
            'endLine' => 353,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The country of origin of something, including products as well as
 * creative  works such as movie and TV content.
 *
 * In the case of TV and movie, this would be the country of the principle
 * offices of the production company or individual responsible for the
 * movie. For other kinds of [[CreativeWork]] it is difficult to provide
 * fully general guidance, and properties such as [[contentLocation]] and
 * [[locationCreated]] may be more applicable.
 *
 * In the case of products, the country of origin of the product. The exact
 * interpretation of this may vary by context and product type, and cannot
 * be fully enumerated here.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CountryContract|\\Spatie\\SchemaOrg\\Contracts\\CountryContract[] $countryOfOrigin
 *
 * @return static
 *
 * @see https://schema.org/countryOfOrigin
 */',
        'startLine' => 353,
        'endLine' => 356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'dateVehicleFirstRegistered' => 
      array (
        'name' => 'dateVehicleFirstRegistered',
        'parameters' => 
        array (
          'dateVehicleFirstRegistered' => 
          array (
            'name' => 'dateVehicleFirstRegistered',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 368,
            'endLine' => 368,
            'startColumn' => 48,
            'endColumn' => 74,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The date of the first registration of the vehicle with the respective
 * public authorities.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $dateVehicleFirstRegistered
 *
 * @return static
 *
 * @see https://schema.org/dateVehicleFirstRegistered
 */',
        'startLine' => 368,
        'endLine' => 371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'depth' => 
      array (
        'name' => 'depth',
        'parameters' => 
        array (
          'depth' => 
          array (
            'name' => 'depth',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 383,
            'endLine' => 383,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The depth of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DistanceContract|\\Spatie\\SchemaOrg\\Contracts\\DistanceContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $depth
 *
 * @return static
 *
 * @see https://schema.org/depth
 * @link https://github.com/schemaorg/schemaorg/issues/3617
 */',
        'startLine' => 383,
        'endLine' => 386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'description' => 
      array (
        'name' => 'description',
        'parameters' => 
        array (
          'description' => 
          array (
            'name' => 'description',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 397,
            'endLine' => 397,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A description of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\TextObjectContract|\\Spatie\\SchemaOrg\\Contracts\\TextObjectContract[]|string|string[] $description
 *
 * @return static
 *
 * @see https://schema.org/description
 */',
        'startLine' => 397,
        'endLine' => 400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'disambiguatingDescription' => 
      array (
        'name' => 'disambiguatingDescription',
        'parameters' => 
        array (
          'disambiguatingDescription' => 
          array (
            'name' => 'disambiguatingDescription',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 414,
            'endLine' => 414,
            'startColumn' => 47,
            'endColumn' => 72,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A sub property of description. A short description of the item used to
 * disambiguate from other, similar items. Information from other properties
 * (in particular, name) may be necessary for the description to be useful
 * for disambiguation.
 *
 * @param string|string[] $disambiguatingDescription
 *
 * @return static
 *
 * @see https://schema.org/disambiguatingDescription
 */',
        'startLine' => 414,
        'endLine' => 417,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'driveWheelConfiguration' => 
      array (
        'name' => 'driveWheelConfiguration',
        'parameters' => 
        array (
          'driveWheelConfiguration' => 
          array (
            'name' => 'driveWheelConfiguration',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 429,
            'endLine' => 429,
            'startColumn' => 45,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The drive wheel configuration, i.e. which roadwheels will receive torque
 * from the vehicle\'s engine via the drivetrain.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DriveWheelConfigurationValueContract|\\Spatie\\SchemaOrg\\Contracts\\DriveWheelConfigurationValueContract[]|string|string[] $driveWheelConfiguration
 *
 * @return static
 *
 * @see https://schema.org/driveWheelConfiguration
 */',
        'startLine' => 429,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'emissionsCO2' => 
      array (
        'name' => 'emissionsCO2',
        'parameters' => 
        array (
          'emissionsCO2' => 
          array (
            'name' => 'emissionsCO2',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 446,
            'endLine' => 446,
            'startColumn' => 34,
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
        'docComment' => '/**
 * The CO2 emissions in g/km. When used in combination with a
 * QuantitativeValue, put "g/km" into the unitText property of that value,
 * since there is no UN/CEFACT Common Code for "g/km".
 *
 * @param float|float[]|int|int[] $emissionsCO2
 *
 * @return static
 *
 * @see https://schema.org/emissionsCO2
 * @see https://auto.schema.org
 */',
        'startLine' => 446,
        'endLine' => 449,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'fuelCapacity' => 
      array (
        'name' => 'fuelCapacity',
        'parameters' => 
        array (
          'fuelCapacity' => 
          array (
            'name' => 'fuelCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 34,
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
        'docComment' => '/**
 * The capacity of the fuel tank or in the case of electric cars, the
 * battery. If there are multiple components for storage, this should
 * indicate the total of all storage of the same type.
 *
 * Typical unit code(s): LTR for liters, GLL of US gallons, GLI for UK /
 * imperial gallons, AMH for ampere-hours (for electrical vehicles).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $fuelCapacity
 *
 * @return static
 *
 * @see https://schema.org/fuelCapacity
 * @see https://auto.schema.org
 */',
        'startLine' => 466,
        'endLine' => 469,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'fuelConsumption' => 
      array (
        'name' => 'fuelConsumption',
        'parameters' => 
        array (
          'fuelConsumption' => 
          array (
            'name' => 'fuelConsumption',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 492,
            'endLine' => 492,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The amount of fuel consumed for traveling a particular distance or
 * temporal duration with the given vehicle (e.g. liters per 100 km).
 *
 * * Note 1: There are unfortunately no standard unit codes for liters per
 * 100 km.  Use [[unitText]] to indicate the unit of measurement, e.g. L/100
 * km.
 * * Note 2: There are two ways of indicating the fuel consumption,
 * [[fuelConsumption]] (e.g. 8 liters per 100 km) and [[fuelEfficiency]]
 * (e.g. 30 miles per gallon). They are reciprocal.
 * * Note 3: Often, the absolute value is useful only when related to
 * driving speed ("at 80 km/h") or usage pattern ("city traffic"). You can
 * use [[valueReference]] to link the value for the fuel consumption to
 * another value.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $fuelConsumption
 *
 * @return static
 *
 * @see https://schema.org/fuelConsumption
 */',
        'startLine' => 492,
        'endLine' => 495,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'fuelEfficiency' => 
      array (
        'name' => 'fuelEfficiency',
        'parameters' => 
        array (
          'fuelEfficiency' => 
          array (
            'name' => 'fuelEfficiency',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 518,
            'endLine' => 518,
            'startColumn' => 36,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The distance traveled per unit of fuel used; most commonly miles per
 * gallon (mpg) or kilometers per liter (km/L).
 *
 * * Note 1: There are unfortunately no standard unit codes for miles per
 * gallon or kilometers per liter. Use [[unitText]] to indicate the unit of
 * measurement, e.g. mpg or km/L.
 * * Note 2: There are two ways of indicating the fuel consumption,
 * [[fuelConsumption]] (e.g. 8 liters per 100 km) and [[fuelEfficiency]]
 * (e.g. 30 miles per gallon). They are reciprocal.
 * * Note 3: Often, the absolute value is useful only when related to
 * driving speed ("at 80 km/h") or usage pattern ("city traffic"). You can
 * use [[valueReference]] to link the value for the fuel economy to another
 * value.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $fuelEfficiency
 *
 * @return static
 *
 * @see https://schema.org/fuelEfficiency
 */',
        'startLine' => 518,
        'endLine' => 521,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'fuelType' => 
      array (
        'name' => 'fuelType',
        'parameters' => 
        array (
          'fuelType' => 
          array (
            'name' => 'fuelType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 534,
            'endLine' => 534,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The type of fuel suitable for the engine or engines of the vehicle. If
 * the vehicle has only one engine, this property can be attached directly
 * to the vehicle.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract[]|string|string[] $fuelType
 *
 * @return static
 *
 * @see https://schema.org/fuelType
 */',
        'startLine' => 534,
        'endLine' => 537,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'funding' => 
      array (
        'name' => 'funding',
        'parameters' => 
        array (
          'funding' => 
          array (
            'name' => 'funding',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 551,
            'endLine' => 551,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A [[Grant]] that directly or indirectly provide funding or sponsorship
 * for this item. See also [[ownershipFundingInfo]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GrantContract|\\Spatie\\SchemaOrg\\Contracts\\GrantContract[] $funding
 *
 * @return static
 *
 * @see https://schema.org/funding
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/383
 */',
        'startLine' => 551,
        'endLine' => 554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'gtin' => 
      array (
        'name' => 'gtin',
        'parameters' => 
        array (
          'gtin' => 
          array (
            'name' => 'gtin',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 592,
            'endLine' => 592,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A Global Trade Item Number
 * ([GTIN](https://www.gs1.org/standards/id-keys/gtin)). GTINs identify
 * trade items, including products and services, using numeric
 * identification codes.
 *
 * A correct [[gtin]] value should be a valid GTIN, which means that it
 * should be an all-numeric string of either 8, 12, 13 or 14 digits, or a
 * "GS1 Digital Link" URL based on such a string. The numeric component
 * should also have a [valid GS1 check
 * digit](https://www.gs1.org/services/check-digit-calculator) and meet the
 * other rules for valid GTINs. See also [GS1\'s GTIN
 * Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) and
 * [Wikipedia](https://en.wikipedia.org/wiki/Global_Trade_Item_Number) for
 * more details. Left-padding of the gtin values is not required or
 * encouraged. The [[gtin]] property generalizes the earlier [[gtin8]],
 * [[gtin12]], [[gtin13]], and [[gtin14]] properties.
 *
 * The GS1 [digital link
 * specifications](https://www.gs1.org/standards/Digital-Link/) expresses
 * GTINs as URLs (URIs, IRIs, etc.).
 * Digital Links should be populated into the [[hasGS1DigitalLink]]
 * attribute.
 *
 * Note also that this is a definition for how to include GTINs in
 * Schema.org data, and not a definition of GTINs in general - see the GS1
 * documentation for authoritative details.
 *
 * @param string|string[] $gtin
 *
 * @return static
 *
 * @see https://schema.org/gtin
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 592,
        'endLine' => 595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'gtin12' => 
      array (
        'name' => 'gtin12',
        'parameters' => 
        array (
          'gtin12' => 
          array (
            'name' => 'gtin12',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The GTIN-12 code of the product, or the product to which the offer
 * refers. The GTIN-12 is the 12-digit GS1 Identification Key composed of a
 * U.P.C. Company Prefix, Item Reference, and Check Digit used to identify
 * trade items. See [GS1 GTIN
 * Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more
 * details.
 *
 * @param string|string[] $gtin12
 *
 * @return static
 *
 * @see https://schema.org/gtin12
 */',
        'startLine' => 611,
        'endLine' => 614,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'gtin13' => 
      array (
        'name' => 'gtin13',
        'parameters' => 
        array (
          'gtin13' => 
          array (
            'name' => 'gtin13',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 630,
            'endLine' => 630,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The GTIN-13 code of the product, or the product to which the offer
 * refers. This is equivalent to 13-digit ISBN codes and EAN UCC-13. Former
 * 12-digit UPC codes can be converted into a GTIN-13 code by simply adding
 * a preceding zero. See [GS1 GTIN
 * Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more
 * details.
 *
 * @param string|string[] $gtin13
 *
 * @return static
 *
 * @see https://schema.org/gtin13
 */',
        'startLine' => 630,
        'endLine' => 633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'gtin14' => 
      array (
        'name' => 'gtin14',
        'parameters' => 
        array (
          'gtin14' => 
          array (
            'name' => 'gtin14',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 647,
            'endLine' => 647,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The GTIN-14 code of the product, or the product to which the offer
 * refers. See [GS1 GTIN
 * Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more
 * details.
 *
 * @param string|string[] $gtin14
 *
 * @return static
 *
 * @see https://schema.org/gtin14
 */',
        'startLine' => 647,
        'endLine' => 650,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'gtin8' => 
      array (
        'name' => 'gtin8',
        'parameters' => 
        array (
          'gtin8' => 
          array (
            'name' => 'gtin8',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 664,
            'endLine' => 664,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The GTIN-8 code of the product, or the product to which the offer refers.
 * This code is also known as EAN/UCC-8 or 8-digit EAN. See [GS1 GTIN
 * Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more
 * details.
 *
 * @param string|string[] $gtin8
 *
 * @return static
 *
 * @see https://schema.org/gtin8
 */',
        'startLine' => 664,
        'endLine' => 667,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasAdultConsideration' => 
      array (
        'name' => 'hasAdultConsideration',
        'parameters' => 
        array (
          'hasAdultConsideration' => 
          array (
            'name' => 'hasAdultConsideration',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 681,
            'endLine' => 681,
            'startColumn' => 43,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Used to tag an item to be intended or suitable for consumption or use by
 * adults only.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AdultOrientedEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\AdultOrientedEnumerationContract[] $hasAdultConsideration
 *
 * @return static
 *
 * @see https://schema.org/hasAdultConsideration
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2989
 */',
        'startLine' => 681,
        'endLine' => 684,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasCertification' => 
      array (
        'name' => 'hasCertification',
        'parameters' => 
        array (
          'hasCertification' => 
          array (
            'name' => 'hasCertification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 697,
            'endLine' => 697,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Certification information about a product, organization, service, place,
 * or person.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CertificationContract|\\Spatie\\SchemaOrg\\Contracts\\CertificationContract[] $hasCertification
 *
 * @return static
 *
 * @see https://schema.org/hasCertification
 * @link https://github.com/schemaorg/schemaorg/issues/3230
 */',
        'startLine' => 697,
        'endLine' => 700,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasEnergyConsumptionDetails' => 
      array (
        'name' => 'hasEnergyConsumptionDetails',
        'parameters' => 
        array (
          'hasEnergyConsumptionDetails' => 
          array (
            'name' => 'hasEnergyConsumptionDetails',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 715,
            'endLine' => 715,
            'startColumn' => 49,
            'endColumn' => 76,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Defines the energy efficiency Category (also known as "class" or
 * "rating") for a product according to an international energy efficiency
 * standard.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EnergyConsumptionDetailsContract|\\Spatie\\SchemaOrg\\Contracts\\EnergyConsumptionDetailsContract[] $hasEnergyConsumptionDetails
 *
 * @return static
 *
 * @see https://schema.org/hasEnergyConsumptionDetails
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2670
 */',
        'startLine' => 715,
        'endLine' => 718,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasGS1DigitalLink' => 
      array (
        'name' => 'hasGS1DigitalLink',
        'parameters' => 
        array (
          'hasGS1DigitalLink' => 
          array (
            'name' => 'hasGS1DigitalLink',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 743,
            'endLine' => 743,
            'startColumn' => 39,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The [GS1 digital link](https://www.gs1.org/standards/gs1-digital-link)
 * associated with the object. This URL should conform to the particular
 * requirements of digital links. The link should only contain the
 * Application Identifiers (AIs) that are relevant for the entity being
 * annotated, for instance a [[Product]] or an [[Organization]], and for the
 * correct granularity. In particular, for products:<ul>* A Digital Link
 * that contains a serial number (AI ```21```) should only be present on
 * instances of [[IndividualProduct]]* A Digital Link that contains a lot
 * number (AI ```10```) should be annotated as [[SomeProduct]] if only
 * products from that lot are sold, or [[IndividualProduct]] if there is
 * only a specific product.* A Digital Link that contains a global model
 * number (AI ```8013```)  should be attached to a [[Product]] or a
 * [[ProductModel]]. Other item types should be adapted similarly.
 *
 * @param string|string[] $hasGS1DigitalLink
 *
 * @return static
 *
 * @see https://schema.org/hasGS1DigitalLink
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3475
 */',
        'startLine' => 743,
        'endLine' => 746,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasMeasurement' => 
      array (
        'name' => 'hasMeasurement',
        'parameters' => 
        array (
          'hasMeasurement' => 
          array (
            'name' => 'hasMeasurement',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 763,
            'endLine' => 763,
            'startColumn' => 36,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A measurement of an item, For example, the inseam of pants, the wheel
 * size of a bicycle, the gauge of a screw, or the carbon footprint measured
 * for certification by an authority. Usually an exact measurement, but can
 * also be a range of measurements for adjustable products, for example
 * belts and ski bindings.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $hasMeasurement
 *
 * @return static
 *
 * @see https://schema.org/hasMeasurement
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2811
 */',
        'startLine' => 763,
        'endLine' => 766,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasMerchantReturnPolicy' => 
      array (
        'name' => 'hasMerchantReturnPolicy',
        'parameters' => 
        array (
          'hasMerchantReturnPolicy' => 
          array (
            'name' => 'hasMerchantReturnPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 779,
            'endLine' => 779,
            'startColumn' => 45,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Specifies a MerchantReturnPolicy that may be applicable.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MerchantReturnPolicyContract|\\Spatie\\SchemaOrg\\Contracts\\MerchantReturnPolicyContract[] $hasMerchantReturnPolicy
 *
 * @return static
 *
 * @see https://schema.org/hasMerchantReturnPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 779,
        'endLine' => 782,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'hasProductReturnPolicy' => 
      array (
        'name' => 'hasProductReturnPolicy',
        'parameters' => 
        array (
          'hasProductReturnPolicy' => 
          array (
            'name' => 'hasProductReturnPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 795,
            'endLine' => 795,
            'startColumn' => 44,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates a ProductReturnPolicy that may be applicable.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductReturnPolicyContract|\\Spatie\\SchemaOrg\\Contracts\\ProductReturnPolicyContract[] $hasProductReturnPolicy
 *
 * @return static
 *
 * @see https://schema.org/hasProductReturnPolicy
 * @see https://attic.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 795,
        'endLine' => 798,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'height' => 
      array (
        'name' => 'height',
        'parameters' => 
        array (
          'height' => 
          array (
            'name' => 'height',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 810,
            'endLine' => 810,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The height of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DistanceContract|\\Spatie\\SchemaOrg\\Contracts\\DistanceContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $height
 *
 * @return static
 *
 * @see https://schema.org/height
 * @link https://github.com/schemaorg/schemaorg/issues/3617
 */',
        'startLine' => 810,
        'endLine' => 813,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'identifier' => 
      array (
        'name' => 'identifier',
        'parameters' => 
        array (
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 828,
            'endLine' => 828,
            'startColumn' => 32,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The identifier property represents any kind of identifier for any kind of
 * [[Thing]], such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides
 * dedicated properties for representing many of these, either as textual
 * strings or as URL (URI) links. See [background
 * notes](/docs/datamodel.html#identifierBg) for more details.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|string|string[] $identifier
 *
 * @return static
 *
 * @see https://schema.org/identifier
 */',
        'startLine' => 828,
        'endLine' => 831,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'image' => 
      array (
        'name' => 'image',
        'parameters' => 
        array (
          'image' => 
          array (
            'name' => 'image',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 843,
            'endLine' => 843,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An image of the item. This can be a [[URL]] or a fully described
 * [[ImageObject]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract|\\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract[]|string|string[] $image
 *
 * @return static
 *
 * @see https://schema.org/image
 */',
        'startLine' => 843,
        'endLine' => 846,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'inProductGroupWithID' => 
      array (
        'name' => 'inProductGroupWithID',
        'parameters' => 
        array (
          'inProductGroupWithID' => 
          array (
            'name' => 'inProductGroupWithID',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 860,
            'endLine' => 860,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates the [[productGroupID]] for a [[ProductGroup]] that this product
 * [[isVariantOf]].
 *
 * @param string|string[] $inProductGroupWithID
 *
 * @return static
 *
 * @see https://schema.org/inProductGroupWithID
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1797
 */',
        'startLine' => 860,
        'endLine' => 863,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'isAccessoryOrSparePartFor' => 
      array (
        'name' => 'isAccessoryOrSparePartFor',
        'parameters' => 
        array (
          'isAccessoryOrSparePartFor' => 
          array (
            'name' => 'isAccessoryOrSparePartFor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 875,
            'endLine' => 875,
            'startColumn' => 47,
            'endColumn' => 72,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A pointer to another product (or multiple products) for which this
 * product is an accessory or spare part.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[] $isAccessoryOrSparePartFor
 *
 * @return static
 *
 * @see https://schema.org/isAccessoryOrSparePartFor
 */',
        'startLine' => 875,
        'endLine' => 878,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'isConsumableFor' => 
      array (
        'name' => 'isConsumableFor',
        'parameters' => 
        array (
          'isConsumableFor' => 
          array (
            'name' => 'isConsumableFor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 890,
            'endLine' => 890,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A pointer to another product (or multiple products) for which this
 * product is a consumable.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[] $isConsumableFor
 *
 * @return static
 *
 * @see https://schema.org/isConsumableFor
 */',
        'startLine' => 890,
        'endLine' => 893,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'isFamilyFriendly' => 
      array (
        'name' => 'isFamilyFriendly',
        'parameters' => 
        array (
          'isFamilyFriendly' => 
          array (
            'name' => 'isFamilyFriendly',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 904,
            'endLine' => 904,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates whether this content is family friendly.
 *
 * @param bool|bool[] $isFamilyFriendly
 *
 * @return static
 *
 * @see https://schema.org/isFamilyFriendly
 */',
        'startLine' => 904,
        'endLine' => 907,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'isRelatedTo' => 
      array (
        'name' => 'isRelatedTo',
        'parameters' => 
        array (
          'isRelatedTo' => 
          array (
            'name' => 'isRelatedTo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 918,
            'endLine' => 918,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A pointer to another, somehow related product (or multiple products).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[]|\\Spatie\\SchemaOrg\\Contracts\\ServiceContract|\\Spatie\\SchemaOrg\\Contracts\\ServiceContract[] $isRelatedTo
 *
 * @return static
 *
 * @see https://schema.org/isRelatedTo
 */',
        'startLine' => 918,
        'endLine' => 921,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'isSimilarTo' => 
      array (
        'name' => 'isSimilarTo',
        'parameters' => 
        array (
          'isSimilarTo' => 
          array (
            'name' => 'isSimilarTo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 933,
            'endLine' => 933,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A pointer to another, functionally similar product (or multiple
 * products).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[]|\\Spatie\\SchemaOrg\\Contracts\\ServiceContract|\\Spatie\\SchemaOrg\\Contracts\\ServiceContract[] $isSimilarTo
 *
 * @return static
 *
 * @see https://schema.org/isSimilarTo
 */',
        'startLine' => 933,
        'endLine' => 936,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'isVariantOf' => 
      array (
        'name' => 'isVariantOf',
        'parameters' => 
        array (
          'isVariantOf' => 
          array (
            'name' => 'isVariantOf',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 957,
            'endLine' => 957,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates the kind of product that this is a variant of. In the case of
 * [[ProductModel]], this is a pointer (from a ProductModel) to a base
 * product from which this product is a variant. It is safe to infer that
 * the variant inherits all product features from the base model, unless
 * defined locally. This is not transitive. In the case of a
 * [[ProductGroup]], the group description also serves as a template,
 * representing a set of Products that vary on explicitly defined, specific
 * dimensions only (so it defines both a set of variants, as well as which
 * values distinguish amongst those variants). When used with
 * [[ProductGroup]], this property can apply to any [[Product]] included in
 * the group.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductGroupContract|\\Spatie\\SchemaOrg\\Contracts\\ProductGroupContract[]|\\Spatie\\SchemaOrg\\Contracts\\ProductModelContract|\\Spatie\\SchemaOrg\\Contracts\\ProductModelContract[] $isVariantOf
 *
 * @return static
 *
 * @see https://schema.org/isVariantOf
 */',
        'startLine' => 957,
        'endLine' => 960,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'itemCondition' => 
      array (
        'name' => 'itemCondition',
        'parameters' => 
        array (
          'itemCondition' => 
          array (
            'name' => 'itemCondition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 974,
            'endLine' => 974,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A predefined value from OfferItemCondition specifying the condition of
 * the product or service, or the products or services included in the
 * offer. Also used for product return policies to specify the condition of
 * products accepted for returns.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OfferItemConditionContract|\\Spatie\\SchemaOrg\\Contracts\\OfferItemConditionContract[] $itemCondition
 *
 * @return static
 *
 * @see https://schema.org/itemCondition
 */',
        'startLine' => 974,
        'endLine' => 977,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'keywords' => 
      array (
        'name' => 'keywords',
        'parameters' => 
        array (
          'keywords' => 
          array (
            'name' => 'keywords',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 990,
            'endLine' => 990,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Keywords or tags used to describe some item. Multiple textual entries in
 * a keywords list are typically delimited by commas, or by repeating the
 * property.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $keywords
 *
 * @return static
 *
 * @see https://schema.org/keywords
 */',
        'startLine' => 990,
        'endLine' => 993,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'knownVehicleDamages' => 
      array (
        'name' => 'knownVehicleDamages',
        'parameters' => 
        array (
          'knownVehicleDamages' => 
          array (
            'name' => 'knownVehicleDamages',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1004,
            'endLine' => 1004,
            'startColumn' => 41,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A textual description of known damages, both repaired and unrepaired.
 *
 * @param string|string[] $knownVehicleDamages
 *
 * @return static
 *
 * @see https://schema.org/knownVehicleDamages
 */',
        'startLine' => 1004,
        'endLine' => 1007,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'logo' => 
      array (
        'name' => 'logo',
        'parameters' => 
        array (
          'logo' => 
          array (
            'name' => 'logo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1018,
            'endLine' => 1018,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An associated logo.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract|\\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract[]|string|string[] $logo
 *
 * @return static
 *
 * @see https://schema.org/logo
 */',
        'startLine' => 1018,
        'endLine' => 1021,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'mainEntityOfPage' => 
      array (
        'name' => 'mainEntityOfPage',
        'parameters' => 
        array (
          'mainEntityOfPage' => 
          array (
            'name' => 'mainEntityOfPage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1034,
            'endLine' => 1034,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates a page (or other CreativeWork) for which this thing is the main
 * entity being described. See [background
 * notes](/docs/datamodel.html#mainEntityBackground) for details.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $mainEntityOfPage
 *
 * @return static
 *
 * @see https://schema.org/mainEntityOfPage
 */',
        'startLine' => 1034,
        'endLine' => 1037,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'manufacturer' => 
      array (
        'name' => 'manufacturer',
        'parameters' => 
        array (
          'manufacturer' => 
          array (
            'name' => 'manufacturer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1048,
            'endLine' => 1048,
            'startColumn' => 34,
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
        'docComment' => '/**
 * The manufacturer of the product.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $manufacturer
 *
 * @return static
 *
 * @see https://schema.org/manufacturer
 */',
        'startLine' => 1048,
        'endLine' => 1051,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'material' => 
      array (
        'name' => 'material',
        'parameters' => 
        array (
          'material' => 
          array (
            'name' => 'material',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1063,
            'endLine' => 1063,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A material that something is made from, e.g. leather, wool, cotton,
 * paper.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[]|string|string[] $material
 *
 * @return static
 *
 * @see https://schema.org/material
 */',
        'startLine' => 1063,
        'endLine' => 1066,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'meetsEmissionStandard' => 
      array (
        'name' => 'meetsEmissionStandard',
        'parameters' => 
        array (
          'meetsEmissionStandard' => 
          array (
            'name' => 'meetsEmissionStandard',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1078,
            'endLine' => 1078,
            'startColumn' => 43,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates that the vehicle meets the respective emission standard.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract[]|string|string[] $meetsEmissionStandard
 *
 * @return static
 *
 * @see https://schema.org/meetsEmissionStandard
 * @see https://auto.schema.org
 */',
        'startLine' => 1078,
        'endLine' => 1081,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'mileageFromOdometer' => 
      array (
        'name' => 'mileageFromOdometer',
        'parameters' => 
        array (
          'mileageFromOdometer' => 
          array (
            'name' => 'mileageFromOdometer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1095,
            'endLine' => 1095,
            'startColumn' => 41,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The total distance travelled by the particular vehicle since its initial
 * production, as read from its odometer.
 *
 * Typical unit code(s): KMT for kilometers, SMI for statute miles.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $mileageFromOdometer
 *
 * @return static
 *
 * @see https://schema.org/mileageFromOdometer
 */',
        'startLine' => 1095,
        'endLine' => 1098,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'mobileUrl' => 
      array (
        'name' => 'mobileUrl',
        'parameters' => 
        array (
          'mobileUrl' => 
          array (
            'name' => 'mobileUrl',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1127,
            'endLine' => 1127,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The [[mobileUrl]] property is provided for specific situations in which
 * data consumers need to determine whether one of several provided URLs is
 * a dedicated \'mobile site\'.
 *
 * To discourage over-use, and reflecting intial usecases, the property is
 * expected only on [[Product]] and [[Offer]], rather than [[Thing]]. The
 * general trend in web technology is towards [responsive
 * design](https://en.wikipedia.org/wiki/Responsive_web_design) in which
 * content can be flexibly adapted to a wide range of browsing environments.
 * Pages and sites referenced with the long-established [[url]] property
 * should ideally also be usable on a wide variety of devices, including
 * mobile phones. In most cases, it would be pointless and counter
 * productive to attempt to update all [[url]] markup to use [[mobileUrl]]
 * for more mobile-oriented pages. The property is intended for the case
 * when items (primarily [[Product]] and [[Offer]]) have extra URLs hosted
 * on an additional "mobile site" alongside the main one. It should not be
 * taken as an endorsement of this publication style.
 *
 * @param string|string[] $mobileUrl
 *
 * @return static
 *
 * @see https://schema.org/mobileUrl
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3134
 */',
        'startLine' => 1127,
        'endLine' => 1130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'model' => 
      array (
        'name' => 'model',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1145,
            'endLine' => 1145,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The model of the product. Use with the URL of a ProductModel or a textual
 * representation of the model identifier. The URL of the ProductModel can
 * be from an external source. It is recommended to additionally provide
 * strong product identifiers via the gtin8/gtin13/gtin14 and mpn
 * properties.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ProductModelContract|\\Spatie\\SchemaOrg\\Contracts\\ProductModelContract[]|string|string[] $model
 *
 * @return static
 *
 * @see https://schema.org/model
 */',
        'startLine' => 1145,
        'endLine' => 1148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'modelDate' => 
      array (
        'name' => 'modelDate',
        'parameters' => 
        array (
          'modelDate' => 
          array (
            'name' => 'modelDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1161,
            'endLine' => 1161,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The release date of a vehicle model (often used to differentiate versions
 * of the same make and model).
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $modelDate
 *
 * @return static
 *
 * @see https://schema.org/modelDate
 * @see https://auto.schema.org
 */',
        'startLine' => 1161,
        'endLine' => 1164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'mpn' => 
      array (
        'name' => 'mpn',
        'parameters' => 
        array (
          'mpn' => 
          array (
            'name' => 'mpn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1176,
            'endLine' => 1176,
            'startColumn' => 25,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The Manufacturer Part Number (MPN) of the product, or the product to
 * which the offer refers.
 *
 * @param string|string[] $mpn
 *
 * @return static
 *
 * @see https://schema.org/mpn
 */',
        'startLine' => 1176,
        'endLine' => 1179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'name' => 
      array (
        'name' => 'name',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1190,
            'endLine' => 1190,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The name of the item.
 *
 * @param string|string[] $name
 *
 * @return static
 *
 * @see https://schema.org/name
 */',
        'startLine' => 1190,
        'endLine' => 1193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'negativeNotes' => 
      array (
        'name' => 'negativeNotes',
        'parameters' => 
        array (
          'negativeNotes' => 
          array (
            'name' => 'negativeNotes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1218,
            'endLine' => 1218,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Provides negative considerations regarding something, most typically in
 * pro/con lists for reviews (alongside [[positiveNotes]]). For symmetry
 *
 * In the case of a [[Review]], the property describes the [[itemReviewed]]
 * from the perspective of the review; in the case of a [[Product]], the
 * product itself is being described. Since product descriptions
 * tend to emphasise positive claims, it may be relatively unusual to find
 * [[negativeNotes]] used in this way. Nevertheless for the sake of
 * symmetry, [[negativeNotes]] can be used on [[Product]].
 *
 * The property values can be expressed either as unstructured text
 * (repeated as necessary), or if ordered, as a list (in which case the most
 * negative is at the beginning of the list).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ItemListContract|\\Spatie\\SchemaOrg\\Contracts\\ItemListContract[]|\\Spatie\\SchemaOrg\\Contracts\\ListItemContract|\\Spatie\\SchemaOrg\\Contracts\\ListItemContract[]|\\Spatie\\SchemaOrg\\Contracts\\WebContentContract|\\Spatie\\SchemaOrg\\Contracts\\WebContentContract[]|string|string[] $negativeNotes
 *
 * @return static
 *
 * @see https://schema.org/negativeNotes
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2832
 */',
        'startLine' => 1218,
        'endLine' => 1221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'nsn' => 
      array (
        'name' => 'nsn',
        'parameters' => 
        array (
          'nsn' => 
          array (
            'name' => 'nsn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1236,
            'endLine' => 1236,
            'startColumn' => 25,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates the [NATO stock
 * number](https://en.wikipedia.org/wiki/NATO_Stock_Number) (nsn) of a
 * [[Product]].
 *
 * @param string|string[] $nsn
 *
 * @return static
 *
 * @see https://schema.org/nsn
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2126
 */',
        'startLine' => 1236,
        'endLine' => 1239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'numberOfAirbags' => 
      array (
        'name' => 'numberOfAirbags',
        'parameters' => 
        array (
          'numberOfAirbags' => 
          array (
            'name' => 'numberOfAirbags',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1250,
            'endLine' => 1250,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The number or type of airbags in the vehicle.
 *
 * @param float|float[]|int|int[]|string|string[] $numberOfAirbags
 *
 * @return static
 *
 * @see https://schema.org/numberOfAirbags
 */',
        'startLine' => 1250,
        'endLine' => 1253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'numberOfAxles' => 
      array (
        'name' => 'numberOfAxles',
        'parameters' => 
        array (
          'numberOfAxles' => 
          array (
            'name' => 'numberOfAxles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1266,
            'endLine' => 1266,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The number of axles.
 *
 * Typical unit code(s): C62.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $numberOfAxles
 *
 * @return static
 *
 * @see https://schema.org/numberOfAxles
 */',
        'startLine' => 1266,
        'endLine' => 1269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'numberOfDoors' => 
      array (
        'name' => 'numberOfDoors',
        'parameters' => 
        array (
          'numberOfDoors' => 
          array (
            'name' => 'numberOfDoors',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1282,
            'endLine' => 1282,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The number of doors.
 *
 * Typical unit code(s): C62.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $numberOfDoors
 *
 * @return static
 *
 * @see https://schema.org/numberOfDoors
 */',
        'startLine' => 1282,
        'endLine' => 1285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'numberOfForwardGears' => 
      array (
        'name' => 'numberOfForwardGears',
        'parameters' => 
        array (
          'numberOfForwardGears' => 
          array (
            'name' => 'numberOfForwardGears',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1299,
            'endLine' => 1299,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The total number of forward gears available for the transmission system
 * of the vehicle.
 *
 * Typical unit code(s): C62.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $numberOfForwardGears
 *
 * @return static
 *
 * @see https://schema.org/numberOfForwardGears
 */',
        'startLine' => 1299,
        'endLine' => 1302,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'numberOfPreviousOwners' => 
      array (
        'name' => 'numberOfPreviousOwners',
        'parameters' => 
        array (
          'numberOfPreviousOwners' => 
          array (
            'name' => 'numberOfPreviousOwners',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1315,
            'endLine' => 1315,
            'startColumn' => 44,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The number of owners of the vehicle, including the current one.
 *
 * Typical unit code(s): C62.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $numberOfPreviousOwners
 *
 * @return static
 *
 * @see https://schema.org/numberOfPreviousOwners
 */',
        'startLine' => 1315,
        'endLine' => 1318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'offers' => 
      array (
        'name' => 'offers',
        'parameters' => 
        array (
          'offers' => 
          array (
            'name' => 'offers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1337,
            'endLine' => 1337,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An offer to provide this item&#x2014;for example, an offer to sell a
 * product, rent the DVD of a movie, perform a service, or give away tickets
 * to an event. Use [[businessFunction]] to indicate the kind of transaction
 * offered, i.e. sell, lease, etc. This property can also be used to
 * describe a [[Demand]]. While this property is listed as expected on a
 * number of common types, it can be used in others. In that case, using a
 * second type, such as Product or a subtype of Product, can clarify the
 * nature of the offer.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DemandContract|\\Spatie\\SchemaOrg\\Contracts\\DemandContract[]|\\Spatie\\SchemaOrg\\Contracts\\OfferContract|\\Spatie\\SchemaOrg\\Contracts\\OfferContract[] $offers
 *
 * @return static
 *
 * @see https://schema.org/offers
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 */',
        'startLine' => 1337,
        'endLine' => 1340,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'pattern' => 
      array (
        'name' => 'pattern',
        'parameters' => 
        array (
          'pattern' => 
          array (
            'name' => 'pattern',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1355,
            'endLine' => 1355,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A pattern that something has, for example \'polka dot\', \'striped\',
 * \'Canadian flag\'. Values are typically expressed as text, although links
 * to controlled value schemes are also supported.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $pattern
 *
 * @return static
 *
 * @see https://schema.org/pattern
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1797
 */',
        'startLine' => 1355,
        'endLine' => 1358,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'payload' => 
      array (
        'name' => 'payload',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1382,
            'endLine' => 1382,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The permitted weight of passengers and cargo, EXCLUDING the weight of the
 * empty vehicle.
 *
 * Typical unit code(s): KGM for kilogram, LBR for pound
 *
 * * Note 1: Many databases specify the permitted TOTAL weight instead,
 * which is the sum of [[weight]] and [[payload]]
 * * Note 2: You can indicate additional information in the [[name]] of the
 * [[QuantitativeValue]] node.
 * * Note 3: You may also link to a [[QualitativeValue]] node that provides
 * additional information using [[valueReference]].
 * * Note 4: Note that you can use [[minValue]] and [[maxValue]] to indicate
 * ranges.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $payload
 *
 * @return static
 *
 * @see https://schema.org/payload
 * @see https://auto.schema.org
 */',
        'startLine' => 1382,
        'endLine' => 1385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'positiveNotes' => 
      array (
        'name' => 'positiveNotes',
        'parameters' => 
        array (
          'positiveNotes' => 
          array (
            'name' => 'positiveNotes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1407,
            'endLine' => 1407,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Provides positive considerations regarding something, for example product
 * highlights or (alongside [[negativeNotes]]) pro/con lists for reviews.
 *
 * In the case of a [[Review]], the property describes the [[itemReviewed]]
 * from the perspective of the review; in the case of a [[Product]], the
 * product itself is being described.
 *
 * The property values can be expressed either as unstructured text
 * (repeated as necessary), or if ordered, as a list (in which case the most
 * positive is at the beginning of the list).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ItemListContract|\\Spatie\\SchemaOrg\\Contracts\\ItemListContract[]|\\Spatie\\SchemaOrg\\Contracts\\ListItemContract|\\Spatie\\SchemaOrg\\Contracts\\ListItemContract[]|\\Spatie\\SchemaOrg\\Contracts\\WebContentContract|\\Spatie\\SchemaOrg\\Contracts\\WebContentContract[]|string|string[] $positiveNotes
 *
 * @return static
 *
 * @see https://schema.org/positiveNotes
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2832
 */',
        'startLine' => 1407,
        'endLine' => 1410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'potentialAction' => 
      array (
        'name' => 'potentialAction',
        'parameters' => 
        array (
          'potentialAction' => 
          array (
            'name' => 'potentialAction',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1422,
            'endLine' => 1422,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates a potential Action, which describes an idealized action in
 * which this thing would play an \'object\' role.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ActionContract|\\Spatie\\SchemaOrg\\Contracts\\ActionContract[] $potentialAction
 *
 * @return static
 *
 * @see https://schema.org/potentialAction
 */',
        'startLine' => 1422,
        'endLine' => 1425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'productID' => 
      array (
        'name' => 'productID',
        'parameters' => 
        array (
          'productID' => 
          array (
            'name' => 'productID',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1437,
            'endLine' => 1437,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The product identifier, such as ISBN. For example: ``` meta
 * itemprop="productID" content="isbn:123-456-789" ```.
 *
 * @param string|string[] $productID
 *
 * @return static
 *
 * @see https://schema.org/productID
 */',
        'startLine' => 1437,
        'endLine' => 1440,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'productionDate' => 
      array (
        'name' => 'productionDate',
        'parameters' => 
        array (
          'productionDate' => 
          array (
            'name' => 'productionDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1451,
            'endLine' => 1451,
            'startColumn' => 36,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The date of production of the item, e.g. vehicle.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $productionDate
 *
 * @return static
 *
 * @see https://schema.org/productionDate
 */',
        'startLine' => 1451,
        'endLine' => 1454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'purchaseDate' => 
      array (
        'name' => 'purchaseDate',
        'parameters' => 
        array (
          'purchaseDate' => 
          array (
            'name' => 'purchaseDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1465,
            'endLine' => 1465,
            'startColumn' => 34,
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
        'docComment' => '/**
 * The date the item, e.g. vehicle, was purchased by the current owner.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $purchaseDate
 *
 * @return static
 *
 * @see https://schema.org/purchaseDate
 */',
        'startLine' => 1465,
        'endLine' => 1468,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'releaseDate' => 
      array (
        'name' => 'releaseDate',
        'parameters' => 
        array (
          'releaseDate' => 
          array (
            'name' => 'releaseDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1480,
            'endLine' => 1480,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The release date of a product or product model. This can be used to
 * distinguish the exact variant of a product.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $releaseDate
 *
 * @return static
 *
 * @see https://schema.org/releaseDate
 */',
        'startLine' => 1480,
        'endLine' => 1483,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'review' => 
      array (
        'name' => 'review',
        'parameters' => 
        array (
          'review' => 
          array (
            'name' => 'review',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1494,
            'endLine' => 1494,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A review of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReviewContract|\\Spatie\\SchemaOrg\\Contracts\\ReviewContract[] $review
 *
 * @return static
 *
 * @see https://schema.org/review
 */',
        'startLine' => 1494,
        'endLine' => 1497,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'reviews' => 
      array (
        'name' => 'reviews',
        'parameters' => 
        array (
          'reviews' => 
          array (
            'name' => 'reviews',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1508,
            'endLine' => 1508,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Review of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReviewContract|\\Spatie\\SchemaOrg\\Contracts\\ReviewContract[] $reviews
 *
 * @return static
 *
 * @see https://schema.org/reviews
 */',
        'startLine' => 1508,
        'endLine' => 1511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'sameAs' => 
      array (
        'name' => 'sameAs',
        'parameters' => 
        array (
          'sameAs' => 
          array (
            'name' => 'sameAs',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1524,
            'endLine' => 1524,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * URL of a reference Web page that unambiguously indicates the item\'s
 * identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or
 * official website.
 *
 * @param string|string[] $sameAs
 *
 * @return static
 *
 * @see https://schema.org/sameAs
 */',
        'startLine' => 1524,
        'endLine' => 1527,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'seatingCapacity' => 
      array (
        'name' => 'seatingCapacity',
        'parameters' => 
        array (
          'seatingCapacity' => 
          array (
            'name' => 'seatingCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1543,
            'endLine' => 1543,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The number of persons that can be seated (e.g. in a vehicle), both in
 * terms of the physical space available, and in terms of limitations set by
 * law.
 *
 * Typical unit code(s): C62 for persons.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $seatingCapacity
 *
 * @return static
 *
 * @see https://schema.org/seatingCapacity
 * @see https://auto.schema.org
 */',
        'startLine' => 1543,
        'endLine' => 1546,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'size' => 
      array (
        'name' => 'size',
        'parameters' => 
        array (
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1563,
            'endLine' => 1563,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A standardized size of a product or creative work, specified either
 * through a simple textual string (for example \'XL\', \'32Wx34L\'), a
 * QuantitativeValue with a unitCode, or a comprehensive and structured
 * [[SizeSpecification]]; in other cases, the [[width]], [[height]],
 * [[depth]] and [[weight]] properties may be more applicable.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|\\Spatie\\SchemaOrg\\Contracts\\SizeSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\SizeSpecificationContract[]|string|string[] $size
 *
 * @return static
 *
 * @see https://schema.org/size
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1797
 */',
        'startLine' => 1563,
        'endLine' => 1566,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'sku' => 
      array (
        'name' => 'sku',
        'parameters' => 
        array (
          'sku' => 
          array (
            'name' => 'sku',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1578,
            'endLine' => 1578,
            'startColumn' => 25,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The Stock Keeping Unit (SKU), i.e. a merchant-specific identifier for a
 * product or service, or the product to which the offer refers.
 *
 * @param string|string[] $sku
 *
 * @return static
 *
 * @see https://schema.org/sku
 */',
        'startLine' => 1578,
        'endLine' => 1581,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'slogan' => 
      array (
        'name' => 'slogan',
        'parameters' => 
        array (
          'slogan' => 
          array (
            'name' => 'slogan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1592,
            'endLine' => 1592,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A slogan or motto associated with the item.
 *
 * @param string|string[] $slogan
 *
 * @return static
 *
 * @see https://schema.org/slogan
 */',
        'startLine' => 1592,
        'endLine' => 1595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'speed' => 
      array (
        'name' => 'speed',
        'parameters' => 
        array (
          'speed' => 
          array (
            'name' => 'speed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1618,
            'endLine' => 1618,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The speed range of the vehicle. If the vehicle is powered by an engine,
 * the upper limit of the speed range (indicated by [[maxValue]]) should be
 * the maximum speed achievable under regular conditions.
 *
 * Typical unit code(s): KMH for km/h, HM for mile per hour (0.447 04 m/s),
 * KNT for knot
 *
 * *Note 1: Use [[minValue]] and [[maxValue]] to indicate the range.
 * Typically, the minimal value is zero.
 * * Note 2: There are many different ways of measuring the speed range. You
 * can link to information about how the given value has been determined
 * using the [[valueReference]] property.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $speed
 *
 * @return static
 *
 * @see https://schema.org/speed
 * @see https://auto.schema.org
 */',
        'startLine' => 1618,
        'endLine' => 1621,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'steeringPosition' => 
      array (
        'name' => 'steeringPosition',
        'parameters' => 
        array (
          'steeringPosition' => 
          array (
            'name' => 'steeringPosition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1632,
            'endLine' => 1632,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The position of the steering wheel or similar device (mostly for cars).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\SteeringPositionValueContract|\\Spatie\\SchemaOrg\\Contracts\\SteeringPositionValueContract[] $steeringPosition
 *
 * @return static
 *
 * @see https://schema.org/steeringPosition
 */',
        'startLine' => 1632,
        'endLine' => 1635,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'stupidProperty' => 
      array (
        'name' => 'stupidProperty',
        'parameters' => 
        array (
          'stupidProperty' => 
          array (
            'name' => 'stupidProperty',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1647,
            'endLine' => 1647,
            'startColumn' => 36,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This is a StupidProperty! - for testing only.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $stupidProperty
 *
 * @return static
 *
 * @see https://schema.org/stupidProperty
 * @see https://attic.schema.org
 */',
        'startLine' => 1647,
        'endLine' => 1650,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'subjectOf' => 
      array (
        'name' => 'subjectOf',
        'parameters' => 
        array (
          'subjectOf' => 
          array (
            'name' => 'subjectOf',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1662,
            'endLine' => 1662,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A CreativeWork or Event about this Thing.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|\\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $subjectOf
 *
 * @return static
 *
 * @see https://schema.org/subjectOf
 * @link https://github.com/schemaorg/schemaorg/issues/1670
 */',
        'startLine' => 1662,
        'endLine' => 1665,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'tongueWeight' => 
      array (
        'name' => 'tongueWeight',
        'parameters' => 
        array (
          'tongueWeight' => 
          array (
            'name' => 'tongueWeight',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1688,
            'endLine' => 1688,
            'startColumn' => 34,
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
        'docComment' => '/**
 * The permitted vertical load (TWR) of a trailer attached to the vehicle.
 * Also referred to as Tongue Load Rating (TLR) or Vertical Load Rating
 * (VLR).
 *
 * Typical unit code(s): KGM for kilogram, LBR for pound
 *
 * * Note 1: You can indicate additional information in the [[name]] of the
 * [[QuantitativeValue]] node.
 * * Note 2: You may also link to a [[QualitativeValue]] node that provides
 * additional information using [[valueReference]].
 * * Note 3: Note that you can use [[minValue]] and [[maxValue]] to indicate
 * ranges.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $tongueWeight
 *
 * @return static
 *
 * @see https://schema.org/tongueWeight
 * @see https://auto.schema.org
 */',
        'startLine' => 1688,
        'endLine' => 1691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'trailerWeight' => 
      array (
        'name' => 'trailerWeight',
        'parameters' => 
        array (
          'trailerWeight' => 
          array (
            'name' => 'trailerWeight',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1711,
            'endLine' => 1711,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The permitted weight of a trailer attached to the vehicle.
 *
 * Typical unit code(s): KGM for kilogram, LBR for pound
 * * Note 1: You can indicate additional information in the [[name]] of the
 * [[QuantitativeValue]] node.
 * * Note 2: You may also link to a [[QualitativeValue]] node that provides
 * additional information using [[valueReference]].
 * * Note 3: Note that you can use [[minValue]] and [[maxValue]] to indicate
 * ranges.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $trailerWeight
 *
 * @return static
 *
 * @see https://schema.org/trailerWeight
 * @see https://auto.schema.org
 */',
        'startLine' => 1711,
        'endLine' => 1714,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'url' => 
      array (
        'name' => 'url',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1725,
            'endLine' => 1725,
            'startColumn' => 25,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * URL of the item.
 *
 * @param string|string[] $url
 *
 * @return static
 *
 * @see https://schema.org/url
 */',
        'startLine' => 1725,
        'endLine' => 1728,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleConfiguration' => 
      array (
        'name' => 'vehicleConfiguration',
        'parameters' => 
        array (
          'vehicleConfiguration' => 
          array (
            'name' => 'vehicleConfiguration',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1740,
            'endLine' => 1740,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * A short text indicating the configuration of the vehicle, e.g. \'5dr
 * hatchback ST 2.5 MT 225 hp\' or \'limited edition\'.
 *
 * @param string|string[] $vehicleConfiguration
 *
 * @return static
 *
 * @see https://schema.org/vehicleConfiguration
 */',
        'startLine' => 1740,
        'endLine' => 1743,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleEngine' => 
      array (
        'name' => 'vehicleEngine',
        'parameters' => 
        array (
          'vehicleEngine' => 
          array (
            'name' => 'vehicleEngine',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1754,
            'endLine' => 1754,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Information about the engine or engines of the vehicle.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EngineSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\EngineSpecificationContract[] $vehicleEngine
 *
 * @return static
 *
 * @see https://schema.org/vehicleEngine
 */',
        'startLine' => 1754,
        'endLine' => 1757,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleIdentificationNumber' => 
      array (
        'name' => 'vehicleIdentificationNumber',
        'parameters' => 
        array (
          'vehicleIdentificationNumber' => 
          array (
            'name' => 'vehicleIdentificationNumber',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1769,
            'endLine' => 1769,
            'startColumn' => 49,
            'endColumn' => 76,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The Vehicle Identification Number (VIN) is a unique serial number used by
 * the automotive industry to identify individual motor vehicles.
 *
 * @param string|string[] $vehicleIdentificationNumber
 *
 * @return static
 *
 * @see https://schema.org/vehicleIdentificationNumber
 */',
        'startLine' => 1769,
        'endLine' => 1772,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleInteriorColor' => 
      array (
        'name' => 'vehicleInteriorColor',
        'parameters' => 
        array (
          'vehicleInteriorColor' => 
          array (
            'name' => 'vehicleInteriorColor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1783,
            'endLine' => 1783,
            'startColumn' => 42,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The color or color combination of the interior of the vehicle.
 *
 * @param string|string[] $vehicleInteriorColor
 *
 * @return static
 *
 * @see https://schema.org/vehicleInteriorColor
 */',
        'startLine' => 1783,
        'endLine' => 1786,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleInteriorType' => 
      array (
        'name' => 'vehicleInteriorType',
        'parameters' => 
        array (
          'vehicleInteriorType' => 
          array (
            'name' => 'vehicleInteriorType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1800,
            'endLine' => 1800,
            'startColumn' => 41,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The type or material of the interior of the vehicle (e.g. synthetic
 * fabric, leather, wood, etc.). While most interior types are characterized
 * by the material used, an interior type can also be based on vehicle usage
 * or target audience.
 *
 * @param string|string[] $vehicleInteriorType
 *
 * @return static
 *
 * @see https://schema.org/vehicleInteriorType
 */',
        'startLine' => 1800,
        'endLine' => 1803,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleModelDate' => 
      array (
        'name' => 'vehicleModelDate',
        'parameters' => 
        array (
          'vehicleModelDate' => 
          array (
            'name' => 'vehicleModelDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1815,
            'endLine' => 1815,
            'startColumn' => 38,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The release date of a vehicle model (often used to differentiate versions
 * of the same make and model).
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $vehicleModelDate
 *
 * @return static
 *
 * @see https://schema.org/vehicleModelDate
 */',
        'startLine' => 1815,
        'endLine' => 1818,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleSeatingCapacity' => 
      array (
        'name' => 'vehicleSeatingCapacity',
        'parameters' => 
        array (
          'vehicleSeatingCapacity' => 
          array (
            'name' => 'vehicleSeatingCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1832,
            'endLine' => 1832,
            'startColumn' => 44,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The number of passengers that can be seated in the vehicle, both in terms
 * of the physical space available, and in terms of limitations set by law.
 *
 * Typical unit code(s): C62 for persons.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $vehicleSeatingCapacity
 *
 * @return static
 *
 * @see https://schema.org/vehicleSeatingCapacity
 */',
        'startLine' => 1832,
        'endLine' => 1835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleSpecialUsage' => 
      array (
        'name' => 'vehicleSpecialUsage',
        'parameters' => 
        array (
          'vehicleSpecialUsage' => 
          array (
            'name' => 'vehicleSpecialUsage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1850,
            'endLine' => 1850,
            'startColumn' => 41,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicates whether the vehicle has been used for special purposes, like
 * commercial rental, driving school, or as a taxi. The legislation in many
 * countries requires this information to be revealed when offering a car
 * for sale.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CarUsageTypeContract|\\Spatie\\SchemaOrg\\Contracts\\CarUsageTypeContract[]|string|string[] $vehicleSpecialUsage
 *
 * @return static
 *
 * @see https://schema.org/vehicleSpecialUsage
 * @see https://auto.schema.org
 */',
        'startLine' => 1850,
        'endLine' => 1853,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'vehicleTransmission' => 
      array (
        'name' => 'vehicleTransmission',
        'parameters' => 
        array (
          'vehicleTransmission' => 
          array (
            'name' => 'vehicleTransmission',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1866,
            'endLine' => 1866,
            'startColumn' => 41,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The type of component used for transmitting the power from a rotating
 * power source to the wheels or other relevant component(s) ("gearbox" for
 * cars).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract[]|string|string[] $vehicleTransmission
 *
 * @return static
 *
 * @see https://schema.org/vehicleTransmission
 */',
        'startLine' => 1866,
        'endLine' => 1869,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'weight' => 
      array (
        'name' => 'weight',
        'parameters' => 
        array (
          'weight' => 
          array (
            'name' => 'weight',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1881,
            'endLine' => 1881,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The weight of the product or person.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MassContract|\\Spatie\\SchemaOrg\\Contracts\\MassContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $weight
 *
 * @return static
 *
 * @see https://schema.org/weight
 * @link https://github.com/schemaorg/schemaorg/issues/3617
 */',
        'startLine' => 1881,
        'endLine' => 1884,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'weightTotal' => 
      array (
        'name' => 'weightTotal',
        'parameters' => 
        array (
          'weightTotal' => 
          array (
            'name' => 'weightTotal',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1906,
            'endLine' => 1906,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The permitted total weight of the loaded vehicle, including passengers
 * and cargo and the weight of the empty vehicle.
 *
 * Typical unit code(s): KGM for kilogram, LBR for pound
 *
 * * Note 1: You can indicate additional information in the [[name]] of the
 * [[QuantitativeValue]] node.
 * * Note 2: You may also link to a [[QualitativeValue]] node that provides
 * additional information using [[valueReference]].
 * * Note 3: Note that you can use [[minValue]] and [[maxValue]] to indicate
 * ranges.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $weightTotal
 *
 * @return static
 *
 * @see https://schema.org/weightTotal
 * @see https://auto.schema.org
 */',
        'startLine' => 1906,
        'endLine' => 1909,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'wheelbase' => 
      array (
        'name' => 'wheelbase',
        'parameters' => 
        array (
          'wheelbase' => 
          array (
            'name' => 'wheelbase',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1924,
            'endLine' => 1924,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The distance between the centers of the front and rear wheels.
 *
 * Typical unit code(s): CMT for centimeters, MTR for meters, INH for
 * inches, FOT for foot/feet.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $wheelbase
 *
 * @return static
 *
 * @see https://schema.org/wheelbase
 * @see https://auto.schema.org
 */',
        'startLine' => 1924,
        'endLine' => 1927,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'aliasName' => NULL,
      ),
      'width' => 
      array (
        'name' => 'width',
        'parameters' => 
        array (
          'width' => 
          array (
            'name' => 'width',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1939,
            'endLine' => 1939,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The width of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DistanceContract|\\Spatie\\SchemaOrg\\Contracts\\DistanceContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $width
 *
 * @return static
 *
 * @see https://schema.org/width
 * @link https://github.com/schemaorg/schemaorg/issues/3617
 */',
        'startLine' => 1939,
        'endLine' => 1942,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
        'currentClassName' => 'Spatie\\SchemaOrg\\Motorcycle',
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