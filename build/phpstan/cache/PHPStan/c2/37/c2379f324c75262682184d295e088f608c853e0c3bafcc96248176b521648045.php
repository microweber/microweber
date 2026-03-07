<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Residence.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\Residence
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5f50596e18c18c53c648e7595dfd73c79b8f12334f2f239e7a6821c12e87a405-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\Residence',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Residence.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\Residence',
    'shortName' => 'Residence',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The place where a person lives.
 *
 * @see https://schema.org/Residence
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 973,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\ResidenceContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\PlaceContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
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
      'accommodationFloorPlan' => 
      array (
        'name' => 'accommodationFloorPlan',
        'parameters' => 
        array (
          'accommodationFloorPlan' => 
          array (
            'name' => 'accommodationFloorPlan',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 28,
            'endLine' => 28,
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
 * A floorplan of some [[Accommodation]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\FloorPlanContract|\\Spatie\\SchemaOrg\\Contracts\\FloorPlanContract[] $accommodationFloorPlan
 *
 * @return static
 *
 * @see https://schema.org/accommodationFloorPlan
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2373
 */',
        'startLine' => 28,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 50,
            'endLine' => 50,
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
        'startLine' => 50,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 73,
            'endLine' => 73,
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
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'address' => 
      array (
        'name' => 'address',
        'parameters' => 
        array (
          'address' => 
          array (
            'name' => 'address',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
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
 * Physical address of the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract|\\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract[]|string|string[] $address
 *
 * @return static
 *
 * @see https://schema.org/address
 */',
        'startLine' => 87,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 102,
            'endLine' => 102,
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
        'startLine' => 102,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 116,
            'endLine' => 116,
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
        'startLine' => 116,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'amenityFeature' => 
      array (
        'name' => 'amenityFeature',
        'parameters' => 
        array (
          'amenityFeature' => 
          array (
            'name' => 'amenityFeature',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 133,
            'endLine' => 133,
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
 * An amenity feature (e.g. a characteristic or service) of the
 * Accommodation. This generic property does not make a statement about
 * whether the feature is included in an offer for the main accommodation or
 * available at extra costs.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\LocationFeatureSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\LocationFeatureSpecificationContract[] $amenityFeature
 *
 * @return static
 *
 * @see https://schema.org/amenityFeature
 */',
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'branchCode' => 
      array (
        'name' => 'branchCode',
        'parameters' => 
        array (
          'branchCode' => 
          array (
            'name' => 'branchCode',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
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
 * A short textual code (also called "store code") that uniquely identifies
 * a place of business. The code is typically assigned by the
 * parentOrganization and used in structured URLs.
 *
 * For example, in the URL
 * http://www.starbucks.co.uk/store-locator/etc/detail/3047 the code "3047"
 * is a branchCode for a particular branch.
 *
 * @param string|string[] $branchCode
 *
 * @return static
 *
 * @see https://schema.org/branchCode
 */',
        'startLine' => 153,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'containedIn' => 
      array (
        'name' => 'containedIn',
        'parameters' => 
        array (
          'containedIn' => 
          array (
            'name' => 'containedIn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
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
 * The basic containment relation between a place and one that contains it.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $containedIn
 *
 * @return static
 *
 * @see https://schema.org/containedIn
 */',
        'startLine' => 167,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'containedInPlace' => 
      array (
        'name' => 'containedInPlace',
        'parameters' => 
        array (
          'containedInPlace' => 
          array (
            'name' => 'containedInPlace',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 181,
            'endLine' => 181,
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
 * The basic containment relation between a place and one that contains it.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $containedInPlace
 *
 * @return static
 *
 * @see https://schema.org/containedInPlace
 */',
        'startLine' => 181,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'containsPlace' => 
      array (
        'name' => 'containsPlace',
        'parameters' => 
        array (
          'containsPlace' => 
          array (
            'name' => 'containsPlace',
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
 * The basic containment relation between a place and another that it
 * contains.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $containsPlace
 *
 * @return static
 *
 * @see https://schema.org/containsPlace
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 210,
            'endLine' => 210,
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
        'startLine' => 210,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 227,
            'endLine' => 227,
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
        'startLine' => 227,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'event' => 
      array (
        'name' => 'event',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 242,
            'endLine' => 242,
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
 * Upcoming or past event associated with this place, organization, or
 * action.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $event
 *
 * @return static
 *
 * @see https://schema.org/event
 */',
        'startLine' => 242,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'events' => 
      array (
        'name' => 'events',
        'parameters' => 
        array (
          'events' => 
          array (
            'name' => 'events',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 256,
            'endLine' => 256,
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
 * Upcoming or past events associated with this place or organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $events
 *
 * @return static
 *
 * @see https://schema.org/events
 */',
        'startLine' => 256,
        'endLine' => 259,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'faxNumber' => 
      array (
        'name' => 'faxNumber',
        'parameters' => 
        array (
          'faxNumber' => 
          array (
            'name' => 'faxNumber',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 270,
            'endLine' => 270,
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
 * The fax number.
 *
 * @param string|string[] $faxNumber
 *
 * @return static
 *
 * @see https://schema.org/faxNumber
 */',
        'startLine' => 270,
        'endLine' => 273,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geo' => 
      array (
        'name' => 'geo',
        'parameters' => 
        array (
          'geo' => 
          array (
            'name' => 'geo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
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
 * The geo coordinates of the place.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeoCoordinatesContract|\\Spatie\\SchemaOrg\\Contracts\\GeoCoordinatesContract[]|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract[] $geo
 *
 * @return static
 *
 * @see https://schema.org/geo
 */',
        'startLine' => 284,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoContains' => 
      array (
        'name' => 'geoContains',
        'parameters' => 
        array (
          'geoContains' => 
          array (
            'name' => 'geoContains',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 302,
            'endLine' => 302,
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
 * Represents a relationship between two geometries (or the places they
 * represent), relating a containing geometry to a contained geometry. "a
 * contains b iff no points of b lie in the exterior of a, and at least one
 * point of the interior of b lies in the interior of a". As defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoContains
 *
 * @return static
 *
 * @see https://schema.org/geoContains
 */',
        'startLine' => 302,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoCoveredBy' => 
      array (
        'name' => 'geoCoveredBy',
        'parameters' => 
        array (
          'geoCoveredBy' => 
          array (
            'name' => 'geoCoveredBy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
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
 * Represents a relationship between two geometries (or the places they
 * represent), relating a geometry to another that covers it. As defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoCoveredBy
 *
 * @return static
 *
 * @see https://schema.org/geoCoveredBy
 */',
        'startLine' => 318,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoCovers' => 
      array (
        'name' => 'geoCovers',
        'parameters' => 
        array (
          'geoCovers' => 
          array (
            'name' => 'geoCovers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 335,
            'endLine' => 335,
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
 * Represents a relationship between two geometries (or the places they
 * represent), relating a covering geometry to a covered geometry. "Every
 * point of b is a point of (the interior or boundary of) a". As defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoCovers
 *
 * @return static
 *
 * @see https://schema.org/geoCovers
 */',
        'startLine' => 335,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoCrosses' => 
      array (
        'name' => 'geoCrosses',
        'parameters' => 
        array (
          'geoCrosses' => 
          array (
            'name' => 'geoCrosses',
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
 * Represents a relationship between two geometries (or the places they
 * represent), relating a geometry to another that crosses it: "a crosses b:
 * they have some but not all interior points in common, and the dimension
 * of the intersection is less than that of at least one of them". As
 * defined in [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoCrosses
 *
 * @return static
 *
 * @see https://schema.org/geoCrosses
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoDisjoint' => 
      array (
        'name' => 'geoDisjoint',
        'parameters' => 
        array (
          'geoDisjoint' => 
          array (
            'name' => 'geoDisjoint',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 370,
            'endLine' => 370,
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
 * Represents spatial relations in which two geometries (or the places they
 * represent) are topologically disjoint: "they have no point in common.
 * They form a set of disconnected geometries." (A symmetric relationship,
 * as defined in [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).)
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoDisjoint
 *
 * @return static
 *
 * @see https://schema.org/geoDisjoint
 */',
        'startLine' => 370,
        'endLine' => 373,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoEquals' => 
      array (
        'name' => 'geoEquals',
        'parameters' => 
        array (
          'geoEquals' => 
          array (
            'name' => 'geoEquals',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 389,
            'endLine' => 389,
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
 * Represents spatial relations in which two geometries (or the places they
 * represent) are topologically equal, as defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM). "Two geometries are
 * topologically equal if their interiors intersect and no part of the
 * interior or boundary of one geometry intersects the exterior of the
 * other" (a symmetric relationship).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoEquals
 *
 * @return static
 *
 * @see https://schema.org/geoEquals
 */',
        'startLine' => 389,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoIntersects' => 
      array (
        'name' => 'geoIntersects',
        'parameters' => 
        array (
          'geoIntersects' => 
          array (
            'name' => 'geoIntersects',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 405,
            'endLine' => 405,
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
 * Represents spatial relations in which two geometries (or the places they
 * represent) have at least one point in common. As defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoIntersects
 *
 * @return static
 *
 * @see https://schema.org/geoIntersects
 */',
        'startLine' => 405,
        'endLine' => 408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoOverlaps' => 
      array (
        'name' => 'geoOverlaps',
        'parameters' => 
        array (
          'geoOverlaps' => 
          array (
            'name' => 'geoOverlaps',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 422,
            'endLine' => 422,
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
 * Represents a relationship between two geometries (or the places they
 * represent), relating a geometry to another that geospatially overlaps it,
 * i.e. they have some but not all points in common. As defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoOverlaps
 *
 * @return static
 *
 * @see https://schema.org/geoOverlaps
 */',
        'startLine' => 422,
        'endLine' => 425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoTouches' => 
      array (
        'name' => 'geoTouches',
        'parameters' => 
        array (
          'geoTouches' => 
          array (
            'name' => 'geoTouches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 439,
            'endLine' => 439,
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
 * Represents spatial relations in which two geometries (or the places they
 * represent) touch: "they have at least one boundary point in common, but
 * no interior points." (A symmetric relationship, as defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).)
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoTouches
 *
 * @return static
 *
 * @see https://schema.org/geoTouches
 */',
        'startLine' => 439,
        'endLine' => 442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'geoWithin' => 
      array (
        'name' => 'geoWithin',
        'parameters' => 
        array (
          'geoWithin' => 
          array (
            'name' => 'geoWithin',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 456,
            'endLine' => 456,
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
 * Represents a relationship between two geometries (or the places they
 * represent), relating a geometry to one that contains it, i.e. it is
 * inside (i.e. within) its interior. As defined in
 * [DE-9IM](https://en.wikipedia.org/wiki/DE-9IM).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract|\\Spatie\\SchemaOrg\\Contracts\\GeospatialGeometryContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $geoWithin
 *
 * @return static
 *
 * @see https://schema.org/geoWithin
 */',
        'startLine' => 456,
        'endLine' => 459,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'globalLocationNumber' => 
      array (
        'name' => 'globalLocationNumber',
        'parameters' => 
        array (
          'globalLocationNumber' => 
          array (
            'name' => 'globalLocationNumber',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 473,
            'endLine' => 473,
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
 * The [Global Location Number](http://www.gs1.org/gln) (GLN, sometimes also
 * referred to as International Location Number or ILN) of the respective
 * organization, person, or place. The GLN is a 13-digit number used to
 * identify parties and physical locations.
 *
 * @param string|string[] $globalLocationNumber
 *
 * @return static
 *
 * @see https://schema.org/globalLocationNumber
 */',
        'startLine' => 473,
        'endLine' => 476,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 489,
            'endLine' => 489,
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
        'startLine' => 489,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'hasDriveThroughService' => 
      array (
        'name' => 'hasDriveThroughService',
        'parameters' => 
        array (
          'hasDriveThroughService' => 
          array (
            'name' => 'hasDriveThroughService',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 509,
            'endLine' => 509,
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
 * Indicates whether some facility (e.g. [[FoodEstablishment]],
 * [[CovidTestingFacility]]) offers a service that can be used by driving
 * through in a car. In the case of [[CovidTestingFacility]] such facilities
 * could potentially help with social distancing from other
 * potentially-infected users.
 *
 * @param bool|bool[] $hasDriveThroughService
 *
 * @return static
 *
 * @see https://schema.org/hasDriveThroughService
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2490
 */',
        'startLine' => 509,
        'endLine' => 512,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 537,
            'endLine' => 537,
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
        'startLine' => 537,
        'endLine' => 540,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'hasMap' => 
      array (
        'name' => 'hasMap',
        'parameters' => 
        array (
          'hasMap' => 
          array (
            'name' => 'hasMap',
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
 * A URL to a map of the place.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MapContract|\\Spatie\\SchemaOrg\\Contracts\\MapContract[]|string|string[] $hasMap
 *
 * @return static
 *
 * @see https://schema.org/hasMap
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 569,
            'endLine' => 569,
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
        'startLine' => 569,
        'endLine' => 572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 584,
            'endLine' => 584,
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
        'startLine' => 584,
        'endLine' => 587,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'isAccessibleForFree' => 
      array (
        'name' => 'isAccessibleForFree',
        'parameters' => 
        array (
          'isAccessibleForFree' => 
          array (
            'name' => 'isAccessibleForFree',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 598,
            'endLine' => 598,
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
 * A flag to signal that the item, event, or place is accessible for free.
 *
 * @param bool|bool[] $isAccessibleForFree
 *
 * @return static
 *
 * @see https://schema.org/isAccessibleForFree
 */',
        'startLine' => 598,
        'endLine' => 601,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'isicV4' => 
      array (
        'name' => 'isicV4',
        'parameters' => 
        array (
          'isicV4' => 
          array (
            'name' => 'isicV4',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 614,
            'endLine' => 614,
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
 * The International Standard of Industrial Classification of All Economic
 * Activities (ISIC), Revision 4 code for a particular organization,
 * business person, or place.
 *
 * @param string|string[] $isicV4
 *
 * @return static
 *
 * @see https://schema.org/isicV4
 */',
        'startLine' => 614,
        'endLine' => 617,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 630,
            'endLine' => 630,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'latitude' => 
      array (
        'name' => 'latitude',
        'parameters' => 
        array (
          'latitude' => 
          array (
            'name' => 'latitude',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 645,
            'endLine' => 645,
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
 * The latitude of a location. For example ```37.42242``` ([WGS
 * 84](https://en.wikipedia.org/wiki/World_Geodetic_System)).
 *
 * @param float|float[]|int|int[]|string|string[] $latitude
 *
 * @return static
 *
 * @see https://schema.org/latitude
 */',
        'startLine' => 645,
        'endLine' => 648,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 659,
            'endLine' => 659,
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
        'startLine' => 659,
        'endLine' => 662,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'longitude' => 
      array (
        'name' => 'longitude',
        'parameters' => 
        array (
          'longitude' => 
          array (
            'name' => 'longitude',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 674,
            'endLine' => 674,
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
 * The longitude of a location. For example ```-122.08585``` ([WGS
 * 84](https://en.wikipedia.org/wiki/World_Geodetic_System)).
 *
 * @param float|float[]|int|int[]|string|string[] $longitude
 *
 * @return static
 *
 * @see https://schema.org/longitude
 */',
        'startLine' => 674,
        'endLine' => 677,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 690,
            'endLine' => 690,
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
        'startLine' => 690,
        'endLine' => 693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'map' => 
      array (
        'name' => 'map',
        'parameters' => 
        array (
          'map' => 
          array (
            'name' => 'map',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 704,
            'endLine' => 704,
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
 * A URL to a map of the place.
 *
 * @param string|string[] $map
 *
 * @return static
 *
 * @see https://schema.org/map
 */',
        'startLine' => 704,
        'endLine' => 707,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'maps' => 
      array (
        'name' => 'maps',
        'parameters' => 
        array (
          'maps' => 
          array (
            'name' => 'maps',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 718,
            'endLine' => 718,
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
 * A URL to a map of the place.
 *
 * @param string|string[] $maps
 *
 * @return static
 *
 * @see https://schema.org/maps
 */',
        'startLine' => 718,
        'endLine' => 721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'maximumAttendeeCapacity' => 
      array (
        'name' => 'maximumAttendeeCapacity',
        'parameters' => 
        array (
          'maximumAttendeeCapacity' => 
          array (
            'name' => 'maximumAttendeeCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 732,
            'endLine' => 732,
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
 * The total number of individuals that may attend an event or venue.
 *
 * @param int|int[] $maximumAttendeeCapacity
 *
 * @return static
 *
 * @see https://schema.org/maximumAttendeeCapacity
 */',
        'startLine' => 732,
        'endLine' => 735,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 746,
            'endLine' => 746,
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
        'startLine' => 746,
        'endLine' => 749,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'openingHoursSpecification' => 
      array (
        'name' => 'openingHoursSpecification',
        'parameters' => 
        array (
          'openingHoursSpecification' => 
          array (
            'name' => 'openingHoursSpecification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 760,
            'endLine' => 760,
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
 * The opening hours of a certain place.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OpeningHoursSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\OpeningHoursSpecificationContract[] $openingHoursSpecification
 *
 * @return static
 *
 * @see https://schema.org/openingHoursSpecification
 */',
        'startLine' => 760,
        'endLine' => 763,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'photo' => 
      array (
        'name' => 'photo',
        'parameters' => 
        array (
          'photo' => 
          array (
            'name' => 'photo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 774,
            'endLine' => 774,
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
 * A photograph of this place.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract|\\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract[]|\\Spatie\\SchemaOrg\\Contracts\\PhotographContract|\\Spatie\\SchemaOrg\\Contracts\\PhotographContract[] $photo
 *
 * @return static
 *
 * @see https://schema.org/photo
 */',
        'startLine' => 774,
        'endLine' => 777,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'photos' => 
      array (
        'name' => 'photos',
        'parameters' => 
        array (
          'photos' => 
          array (
            'name' => 'photos',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 788,
            'endLine' => 788,
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
 * Photographs of this place.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract|\\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract[]|\\Spatie\\SchemaOrg\\Contracts\\PhotographContract|\\Spatie\\SchemaOrg\\Contracts\\PhotographContract[] $photos
 *
 * @return static
 *
 * @see https://schema.org/photos
 */',
        'startLine' => 788,
        'endLine' => 791,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 803,
            'endLine' => 803,
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
        'startLine' => 803,
        'endLine' => 806,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'publicAccess' => 
      array (
        'name' => 'publicAccess',
        'parameters' => 
        array (
          'publicAccess' => 
          array (
            'name' => 'publicAccess',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 818,
            'endLine' => 818,
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
 * A flag to signal that the [[Place]] is open to public visitors.  If this
 * property is omitted there is no assumed default boolean value.
 *
 * @param bool|bool[] $publicAccess
 *
 * @return static
 *
 * @see https://schema.org/publicAccess
 */',
        'startLine' => 818,
        'endLine' => 821,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 832,
            'endLine' => 832,
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
        'startLine' => 832,
        'endLine' => 835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 846,
            'endLine' => 846,
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
        'startLine' => 846,
        'endLine' => 849,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 862,
            'endLine' => 862,
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
        'startLine' => 862,
        'endLine' => 865,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 876,
            'endLine' => 876,
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
        'startLine' => 876,
        'endLine' => 879,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'smokingAllowed' => 
      array (
        'name' => 'smokingAllowed',
        'parameters' => 
        array (
          'smokingAllowed' => 
          array (
            'name' => 'smokingAllowed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 891,
            'endLine' => 891,
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
 * Indicates whether it is allowed to smoke in the place, e.g. in the
 * restaurant, hotel or hotel room.
 *
 * @param bool|bool[] $smokingAllowed
 *
 * @return static
 *
 * @see https://schema.org/smokingAllowed
 */',
        'startLine' => 891,
        'endLine' => 894,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'specialOpeningHoursSpecification' => 
      array (
        'name' => 'specialOpeningHoursSpecification',
        'parameters' => 
        array (
          'specialOpeningHoursSpecification' => 
          array (
            'name' => 'specialOpeningHoursSpecification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 908,
            'endLine' => 908,
            'startColumn' => 54,
            'endColumn' => 86,
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
 * The special opening hours of a certain place.
 *
 * Use this to explicitly override general opening hours brought in scope by
 * [[openingHoursSpecification]] or [[openingHours]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OpeningHoursSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\OpeningHoursSpecificationContract[] $specialOpeningHoursSpecification
 *
 * @return static
 *
 * @see https://schema.org/specialOpeningHoursSpecification
 */',
        'startLine' => 908,
        'endLine' => 911,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 923,
            'endLine' => 923,
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
        'startLine' => 923,
        'endLine' => 926,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'telephone' => 
      array (
        'name' => 'telephone',
        'parameters' => 
        array (
          'telephone' => 
          array (
            'name' => 'telephone',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 937,
            'endLine' => 937,
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
 * The telephone number.
 *
 * @param string|string[] $telephone
 *
 * @return static
 *
 * @see https://schema.org/telephone
 */',
        'startLine' => 937,
        'endLine' => 940,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
        'aliasName' => NULL,
      ),
      'tourBookingPage' => 
      array (
        'name' => 'tourBookingPage',
        'parameters' => 
        array (
          'tourBookingPage' => 
          array (
            'name' => 'tourBookingPage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 955,
            'endLine' => 955,
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
 * A page providing information on how to book a tour of some [[Place]],
 * such as an [[Accommodation]] or [[ApartmentComplex]] in a real estate
 * setting, as well as other kinds of tours as appropriate.
 *
 * @param string|string[] $tourBookingPage
 *
 * @return static
 *
 * @see https://schema.org/tourBookingPage
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2373
 */',
        'startLine' => 955,
        'endLine' => 958,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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
            'startLine' => 969,
            'endLine' => 969,
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
        'startLine' => 969,
        'endLine' => 972,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Residence',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Residence',
        'currentClassName' => 'Spatie\\SchemaOrg\\Residence',
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