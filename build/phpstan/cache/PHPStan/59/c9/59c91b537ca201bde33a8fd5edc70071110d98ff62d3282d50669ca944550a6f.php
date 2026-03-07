<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Distillery.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\Distillery
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3a6c702754d3ac88bf882517ea847d7bad2e001b2e60de490ab542703faf5d8e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\Distillery',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Distillery.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\Distillery',
    'shortName' => 'Distillery',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A distillery.
 *
 * @see https://schema.org/Distillery
 * @link https://github.com/schemaorg/schemaorg/issues/743
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 2124,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\DistilleryContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\FoodEstablishmentContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\LocalBusinessContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\OrganizationContract',
      4 => 'Spatie\\SchemaOrg\\Contracts\\PlaceContract',
      5 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
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
      'acceptedPaymentMethod' => 
      array (
        'name' => 'acceptedPaymentMethod',
        'parameters' => 
        array (
          'acceptedPaymentMethod' => 
          array (
            'name' => 'acceptedPaymentMethod',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
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
 * The payment method(s) that are accepted in general by an organization, or
 * for some specific demand or offer.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\LoanOrCreditContract|\\Spatie\\SchemaOrg\\Contracts\\LoanOrCreditContract[]|\\Spatie\\SchemaOrg\\Contracts\\PaymentMethodContract|\\Spatie\\SchemaOrg\\Contracts\\PaymentMethodContract[]|string|string[] $acceptedPaymentMethod
 *
 * @return static
 *
 * @see https://schema.org/acceptedPaymentMethod
 * @link https://github.com/schemaorg/schemaorg/issues/3537
 */',
        'startLine' => 32,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'acceptsReservations' => 
      array (
        'name' => 'acceptsReservations',
        'parameters' => 
        array (
          'acceptsReservations' => 
          array (
            'name' => 'acceptsReservations',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
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
 * Indicates whether a FoodEstablishment accepts reservations. Values can be
 * Boolean, an URL at which reservations can be made or (for backwards
 * compatibility) the strings ```Yes``` or ```No```.
 *
 * @param bool|bool[]|string|string[] $acceptsReservations
 *
 * @return static
 *
 * @see https://schema.org/acceptsReservations
 */',
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'actionableFeedbackPolicy' => 
      array (
        'name' => 'actionableFeedbackPolicy',
        'parameters' => 
        array (
          'actionableFeedbackPolicy' => 
          array (
            'name' => 'actionableFeedbackPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 46,
            'endColumn' => 70,
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
 * For a [[NewsMediaOrganization]] or other news-related [[Organization]], a
 * statement about public engagement activities (for news media, the
 * newsroom’s), including involving the public - digitally or otherwise --
 * in coverage decisions, reporting and activities after publication.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $actionableFeedbackPolicy
 *
 * @return static
 *
 * @see https://schema.org/actionableFeedbackPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 67,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 89,
            'endLine' => 89,
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
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 112,
            'endLine' => 112,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 126,
            'endLine' => 126,
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
        'startLine' => 126,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'agentInteractionStatistic' => 
      array (
        'name' => 'agentInteractionStatistic',
        'parameters' => 
        array (
          'agentInteractionStatistic' => 
          array (
            'name' => 'agentInteractionStatistic',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 144,
            'endLine' => 144,
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
 * The number of completed interactions for this entity, in a particular
 * role (the \'agent\'), in a particular action (indicated in the statistic),
 * and in a particular context (i.e. interactionService).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\InteractionCounterContract|\\Spatie\\SchemaOrg\\Contracts\\InteractionCounterContract[] $agentInteractionStatistic
 *
 * @return static
 *
 * @see https://schema.org/agentInteractionStatistic
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2858
 */',
        'startLine' => 144,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 159,
            'endLine' => 159,
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
        'startLine' => 159,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 173,
            'endLine' => 173,
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
        'startLine' => 173,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'alumni' => 
      array (
        'name' => 'alumni',
        'parameters' => 
        array (
          'alumni' => 
          array (
            'name' => 'alumni',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 187,
            'endLine' => 187,
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
 * Alumni of an organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $alumni
 *
 * @return static
 *
 * @see https://schema.org/alumni
 */',
        'startLine' => 187,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 204,
            'endLine' => 204,
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
        'startLine' => 204,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'areaServed' => 
      array (
        'name' => 'areaServed',
        'parameters' => 
        array (
          'areaServed' => 
          array (
            'name' => 'areaServed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 218,
            'endLine' => 218,
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
 * The geographic area where a service or offered item is provided.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract|\\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract[]|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[]|string|string[] $areaServed
 *
 * @return static
 *
 * @see https://schema.org/areaServed
 */',
        'startLine' => 218,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 232,
            'endLine' => 232,
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
        'startLine' => 232,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 246,
            'endLine' => 246,
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
        'startLine' => 246,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 266,
            'endLine' => 266,
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
        'startLine' => 266,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'branchOf' => 
      array (
        'name' => 'branchOf',
        'parameters' => 
        array (
          'branchOf' => 
          array (
            'name' => 'branchOf',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 281,
            'endLine' => 281,
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
 * The larger organization that this local business is a branch of, if any.
 * Not to be confused with (anatomical) [[branch]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $branchOf
 *
 * @return static
 *
 * @see https://schema.org/branchOf
 */',
        'startLine' => 281,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 296,
            'endLine' => 296,
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
        'startLine' => 296,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'companyRegistration' => 
      array (
        'name' => 'companyRegistration',
        'parameters' => 
        array (
          'companyRegistration' => 
          array (
            'name' => 'companyRegistration',
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
 * The official registration number of a business including the organization
 * that issued it such as Company House or Chamber of Commerce.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CertificationContract|\\Spatie\\SchemaOrg\\Contracts\\CertificationContract[] $companyRegistration
 *
 * @return static
 *
 * @see https://schema.org/companyRegistration
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'contactPoint' => 
      array (
        'name' => 'contactPoint',
        'parameters' => 
        array (
          'contactPoint' => 
          array (
            'name' => 'contactPoint',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 325,
            'endLine' => 325,
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
 * A contact point for a person or organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ContactPointContract|\\Spatie\\SchemaOrg\\Contracts\\ContactPointContract[] $contactPoint
 *
 * @return static
 *
 * @see https://schema.org/contactPoint
 */',
        'startLine' => 325,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'contactPoints' => 
      array (
        'name' => 'contactPoints',
        'parameters' => 
        array (
          'contactPoints' => 
          array (
            'name' => 'contactPoints',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 339,
            'endLine' => 339,
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
 * A contact point for a person or organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ContactPointContract|\\Spatie\\SchemaOrg\\Contracts\\ContactPointContract[] $contactPoints
 *
 * @return static
 *
 * @see https://schema.org/contactPoints
 */',
        'startLine' => 339,
        'endLine' => 342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 353,
            'endLine' => 353,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 367,
            'endLine' => 367,
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
        'startLine' => 367,
        'endLine' => 370,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 382,
            'endLine' => 382,
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
        'startLine' => 382,
        'endLine' => 385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'correctionsPolicy' => 
      array (
        'name' => 'correctionsPolicy',
        'parameters' => 
        array (
          'correctionsPolicy' => 
          array (
            'name' => 'correctionsPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
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
 * For an [[Organization]] (e.g. [[NewsMediaOrganization]]), a statement
 * describing (in news media, the newsroom’s) disclosure and correction
 * policy for errors.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $correctionsPolicy
 *
 * @return static
 *
 * @see https://schema.org/correctionsPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 400,
        'endLine' => 403,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'currenciesAccepted' => 
      array (
        'name' => 'currenciesAccepted',
        'parameters' => 
        array (
          'currenciesAccepted' => 
          array (
            'name' => 'currenciesAccepted',
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
 * The currency accepted.
 *
 * Use standard formats: [ISO 4217 currency
 * format](http://en.wikipedia.org/wiki/ISO_4217), e.g. "USD"; [Ticker
 * symbol](https://en.wikipedia.org/wiki/List_of_cryptocurrencies) for
 * cryptocurrencies, e.g. "BTC"; well known names for [Local Exchange
 * Trading
 * Systems](https://en.wikipedia.org/wiki/Local_exchange_trading_system)
 * (LETS) and other currency types, e.g. "Ithaca HOUR".
 *
 * @param string|string[] $currenciesAccepted
 *
 * @return static
 *
 * @see https://schema.org/currenciesAccepted
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'department' => 
      array (
        'name' => 'department',
        'parameters' => 
        array (
          'department' => 
          array (
            'name' => 'department',
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
 * A relationship between an organization and a department of that
 * organization, also described as an organization (allowing different urls,
 * logos, opening hours). For example: a store with a pharmacy, or a bakery
 * with a cafe.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $department
 *
 * @return static
 *
 * @see https://schema.org/department
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 453,
            'endLine' => 453,
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
        'startLine' => 453,
        'endLine' => 456,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 470,
            'endLine' => 470,
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
        'startLine' => 470,
        'endLine' => 473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'dissolutionDate' => 
      array (
        'name' => 'dissolutionDate',
        'parameters' => 
        array (
          'dissolutionDate' => 
          array (
            'name' => 'dissolutionDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 484,
            'endLine' => 484,
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
 * The date that this organization was dissolved.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $dissolutionDate
 *
 * @return static
 *
 * @see https://schema.org/dissolutionDate
 */',
        'startLine' => 484,
        'endLine' => 487,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'diversityPolicy' => 
      array (
        'name' => 'diversityPolicy',
        'parameters' => 
        array (
          'diversityPolicy' => 
          array (
            'name' => 'diversityPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 503,
            'endLine' => 503,
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
 * Statement on diversity policy by an [[Organization]] e.g. a
 * [[NewsMediaOrganization]]. For a [[NewsMediaOrganization]], a statement
 * describing the newsroom’s diversity policy on both staffing and
 * sources, typically providing staffing data.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $diversityPolicy
 *
 * @return static
 *
 * @see https://schema.org/diversityPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 503,
        'endLine' => 506,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'diversityStaffingReport' => 
      array (
        'name' => 'diversityStaffingReport',
        'parameters' => 
        array (
          'diversityStaffingReport' => 
          array (
            'name' => 'diversityStaffingReport',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 522,
            'endLine' => 522,
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
 * For an [[Organization]] (often but not necessarily a
 * [[NewsMediaOrganization]]), a report on staffing diversity issues. In a
 * news context this might be for example ASNE or RTDNA (US) reports, or
 * self-reported.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ArticleContract|\\Spatie\\SchemaOrg\\Contracts\\ArticleContract[]|string|string[] $diversityStaffingReport
 *
 * @return static
 *
 * @see https://schema.org/diversityStaffingReport
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 522,
        'endLine' => 525,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'duns' => 
      array (
        'name' => 'duns',
        'parameters' => 
        array (
          'duns' => 
          array (
            'name' => 'duns',
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
 * The Dun & Bradstreet DUNS number for identifying an organization or
 * business person.
 *
 * @param string|string[] $duns
 *
 * @return static
 *
 * @see https://schema.org/duns
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'email' => 
      array (
        'name' => 'email',
        'parameters' => 
        array (
          'email' => 
          array (
            'name' => 'email',
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
 * Email address.
 *
 * @param string|string[] $email
 *
 * @return static
 *
 * @see https://schema.org/email
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'employee' => 
      array (
        'name' => 'employee',
        'parameters' => 
        array (
          'employee' => 
          array (
            'name' => 'employee',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
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
 * Someone working for this organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $employee
 *
 * @return static
 *
 * @see https://schema.org/employee
 */',
        'startLine' => 565,
        'endLine' => 568,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'employees' => 
      array (
        'name' => 'employees',
        'parameters' => 
        array (
          'employees' => 
          array (
            'name' => 'employees',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 579,
            'endLine' => 579,
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
 * People working for this organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $employees
 *
 * @return static
 *
 * @see https://schema.org/employees
 */',
        'startLine' => 579,
        'endLine' => 582,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'ethicsPolicy' => 
      array (
        'name' => 'ethicsPolicy',
        'parameters' => 
        array (
          'ethicsPolicy' => 
          array (
            'name' => 'ethicsPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 600,
            'endLine' => 600,
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
 * Statement about ethics policy, e.g. of a [[NewsMediaOrganization]]
 * regarding journalistic and publishing practices, or of a [[Restaurant]],
 * a page describing food source policies. In the case of a
 * [[NewsMediaOrganization]], an ethicsPolicy is typically a statement
 * describing the personal, organizational, and corporate standards of
 * behavior expected by the organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $ethicsPolicy
 *
 * @return static
 *
 * @see https://schema.org/ethicsPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 600,
        'endLine' => 603,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 615,
            'endLine' => 615,
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
        'startLine' => 615,
        'endLine' => 618,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 629,
            'endLine' => 629,
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
        'startLine' => 629,
        'endLine' => 632,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 643,
            'endLine' => 643,
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
        'startLine' => 643,
        'endLine' => 646,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'founder' => 
      array (
        'name' => 'founder',
        'parameters' => 
        array (
          'founder' => 
          array (
            'name' => 'founder',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 657,
            'endLine' => 657,
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
 * A person or organization who founded this organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $founder
 *
 * @return static
 *
 * @see https://schema.org/founder
 */',
        'startLine' => 657,
        'endLine' => 660,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'founders' => 
      array (
        'name' => 'founders',
        'parameters' => 
        array (
          'founders' => 
          array (
            'name' => 'founders',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 671,
            'endLine' => 671,
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
 * A person who founded this organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $founders
 *
 * @return static
 *
 * @see https://schema.org/founders
 */',
        'startLine' => 671,
        'endLine' => 674,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'foundingDate' => 
      array (
        'name' => 'foundingDate',
        'parameters' => 
        array (
          'foundingDate' => 
          array (
            'name' => 'foundingDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 685,
            'endLine' => 685,
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
 * The date that this organization was founded.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $foundingDate
 *
 * @return static
 *
 * @see https://schema.org/foundingDate
 */',
        'startLine' => 685,
        'endLine' => 688,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'foundingLocation' => 
      array (
        'name' => 'foundingLocation',
        'parameters' => 
        array (
          'foundingLocation' => 
          array (
            'name' => 'foundingLocation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 699,
            'endLine' => 699,
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
 * The place where the Organization was founded.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $foundingLocation
 *
 * @return static
 *
 * @see https://schema.org/foundingLocation
 */',
        'startLine' => 699,
        'endLine' => 702,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'funder' => 
      array (
        'name' => 'funder',
        'parameters' => 
        array (
          'funder' => 
          array (
            'name' => 'funder',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 714,
            'endLine' => 714,
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
 * A person or organization that supports (sponsors) something through some
 * kind of financial contribution.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $funder
 *
 * @return static
 *
 * @see https://schema.org/funder
 */',
        'startLine' => 714,
        'endLine' => 717,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 731,
            'endLine' => 731,
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
        'startLine' => 731,
        'endLine' => 734,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 745,
            'endLine' => 745,
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
        'startLine' => 745,
        'endLine' => 748,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 763,
            'endLine' => 763,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 779,
            'endLine' => 779,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 796,
            'endLine' => 796,
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
        'startLine' => 796,
        'endLine' => 799,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 814,
            'endLine' => 814,
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
        'startLine' => 814,
        'endLine' => 817,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 831,
            'endLine' => 831,
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
        'startLine' => 831,
        'endLine' => 834,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 850,
            'endLine' => 850,
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
        'startLine' => 850,
        'endLine' => 853,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 866,
            'endLine' => 866,
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
        'startLine' => 866,
        'endLine' => 869,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 883,
            'endLine' => 883,
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
        'startLine' => 883,
        'endLine' => 886,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 900,
            'endLine' => 900,
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
        'startLine' => 900,
        'endLine' => 903,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 917,
            'endLine' => 917,
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
        'startLine' => 917,
        'endLine' => 920,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 934,
            'endLine' => 934,
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
        'startLine' => 934,
        'endLine' => 937,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 950,
            'endLine' => 950,
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
        'startLine' => 950,
        'endLine' => 953,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'hasCredential' => 
      array (
        'name' => 'hasCredential',
        'parameters' => 
        array (
          'hasCredential' => 
          array (
            'name' => 'hasCredential',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 966,
            'endLine' => 966,
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
 * A credential awarded to the Person or Organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract[] $hasCredential
 *
 * @return static
 *
 * @see https://schema.org/hasCredential
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 */',
        'startLine' => 966,
        'endLine' => 969,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 986,
            'endLine' => 986,
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
        'startLine' => 986,
        'endLine' => 989,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1014,
            'endLine' => 1014,
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
        'startLine' => 1014,
        'endLine' => 1017,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1028,
            'endLine' => 1028,
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
        'startLine' => 1028,
        'endLine' => 1031,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'hasMemberProgram' => 
      array (
        'name' => 'hasMemberProgram',
        'parameters' => 
        array (
          'hasMemberProgram' => 
          array (
            'name' => 'hasMemberProgram',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1044,
            'endLine' => 1044,
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
 * MemberProgram offered by an Organization, for example an eCommerce
 * merchant or an airline.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MemberProgramContract|\\Spatie\\SchemaOrg\\Contracts\\MemberProgramContract[] $hasMemberProgram
 *
 * @return static
 *
 * @see https://schema.org/hasMemberProgram
 * @link https://github.com/schemaorg/schemaorg/issues/3563
 */',
        'startLine' => 1044,
        'endLine' => 1047,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'hasMenu' => 
      array (
        'name' => 'hasMenu',
        'parameters' => 
        array (
          'hasMenu' => 
          array (
            'name' => 'hasMenu',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1059,
            'endLine' => 1059,
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
 * Either the actual menu as a structured representation, as text, or a URL
 * of the menu.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MenuContract|\\Spatie\\SchemaOrg\\Contracts\\MenuContract[]|string|string[] $hasMenu
 *
 * @return static
 *
 * @see https://schema.org/hasMenu
 */',
        'startLine' => 1059,
        'endLine' => 1062,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1075,
            'endLine' => 1075,
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
        'startLine' => 1075,
        'endLine' => 1078,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'hasOfferCatalog' => 
      array (
        'name' => 'hasOfferCatalog',
        'parameters' => 
        array (
          'hasOfferCatalog' => 
          array (
            'name' => 'hasOfferCatalog',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1090,
            'endLine' => 1090,
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
 * Indicates an OfferCatalog listing for this Organization, Person, or
 * Service.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OfferCatalogContract|\\Spatie\\SchemaOrg\\Contracts\\OfferCatalogContract[] $hasOfferCatalog
 *
 * @return static
 *
 * @see https://schema.org/hasOfferCatalog
 */',
        'startLine' => 1090,
        'endLine' => 1093,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'hasPOS' => 
      array (
        'name' => 'hasPOS',
        'parameters' => 
        array (
          'hasPOS' => 
          array (
            'name' => 'hasPOS',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1104,
            'endLine' => 1104,
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
 * Points-of-Sales operated by the organization or person.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $hasPOS
 *
 * @return static
 *
 * @see https://schema.org/hasPOS
 */',
        'startLine' => 1104,
        'endLine' => 1107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1120,
            'endLine' => 1120,
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
        'startLine' => 1120,
        'endLine' => 1123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'hasShippingService' => 
      array (
        'name' => 'hasShippingService',
        'parameters' => 
        array (
          'hasShippingService' => 
          array (
            'name' => 'hasShippingService',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1136,
            'endLine' => 1136,
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
 * Specification of a shipping service offered by the organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ShippingServiceContract|\\Spatie\\SchemaOrg\\Contracts\\ShippingServiceContract[] $hasShippingService
 *
 * @return static
 *
 * @see https://schema.org/hasShippingService
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3617
 */',
        'startLine' => 1136,
        'endLine' => 1139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1154,
            'endLine' => 1154,
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
        'startLine' => 1154,
        'endLine' => 1157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1169,
            'endLine' => 1169,
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
        'startLine' => 1169,
        'endLine' => 1172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'interactionStatistic' => 
      array (
        'name' => 'interactionStatistic',
        'parameters' => 
        array (
          'interactionStatistic' => 
          array (
            'name' => 'interactionStatistic',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1186,
            'endLine' => 1186,
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
 * The number of interactions for the CreativeWork using the WebSite or
 * SoftwareApplication. The most specific child type of InteractionCounter
 * should be used.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\InteractionCounterContract|\\Spatie\\SchemaOrg\\Contracts\\InteractionCounterContract[] $interactionStatistic
 *
 * @return static
 *
 * @see https://schema.org/interactionStatistic
 * @link https://github.com/schemaorg/schemaorg/issues/2421
 */',
        'startLine' => 1186,
        'endLine' => 1189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1200,
            'endLine' => 1200,
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
        'startLine' => 1200,
        'endLine' => 1203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1216,
            'endLine' => 1216,
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
        'startLine' => 1216,
        'endLine' => 1219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'iso6523Code' => 
      array (
        'name' => 'iso6523Code',
        'parameters' => 
        array (
          'iso6523Code' => 
          array (
            'name' => 'iso6523Code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
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
 * An organization identifier as defined in [ISO
 * 6523(-1)](https://en.wikipedia.org/wiki/ISO/IEC_6523). The identifier
 * should be in the `XXXX:YYYYYY:ZZZ` or `XXXX:YYYYYY`format. Where `XXXX`
 * is a 4 digit _ICD_ (International Code Designator), `YYYYYY` is an _OID_
 * (Organization Identifier) with all formatting characters (dots, dashes,
 * spaces) removed with a maximal length of 35 characters, and `ZZZ` is an
 * optional OPI (Organization Part Identifier) with a maximum length of 35
 * characters. The various components (ICD, OID, OPI) are joined with a
 * colon character (ASCII `0x3a`). Note that many existing organization
 * identifiers defined as attributes like
 * [leiCode](https://schema.org/leiCode) (`0199`),
 * [duns](https://schema.org/duns) (`0060`) or
 * [GLN](https://schema.org/globalLocationNumber) (`0088`) can be expressed
 * using ISO-6523. If possible, ISO-6523 codes should be preferred to
 * populating [vatID](https://schema.org/vatID) or
 * [taxID](https://schema.org/taxID), as ISO identifiers are less ambiguous.
 *
 * @param string|string[] $iso6523Code
 *
 * @return static
 *
 * @see https://schema.org/iso6523Code
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2915
 */',
        'startLine' => 1247,
        'endLine' => 1250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1263,
            'endLine' => 1263,
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
        'startLine' => 1263,
        'endLine' => 1266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'knowsAbout' => 
      array (
        'name' => 'knowsAbout',
        'parameters' => 
        array (
          'knowsAbout' => 
          array (
            'name' => 'knowsAbout',
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
 * Of a [[Person]], and less typically of an [[Organization]], to indicate a
 * topic that is known about - suggesting possible expertise but not
 * implying it. We do not distinguish skill levels here, or relate this to
 * educational content, events, objectives or [[JobPosting]] descriptions.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[]|string|string[] $knowsAbout
 *
 * @return static
 *
 * @see https://schema.org/knowsAbout
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1688
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'knowsLanguage' => 
      array (
        'name' => 'knowsLanguage',
        'parameters' => 
        array (
          'knowsLanguage' => 
          array (
            'name' => 'knowsLanguage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1301,
            'endLine' => 1301,
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
 * Of a [[Person]], and less typically of an [[Organization]], to indicate a
 * known language. We do not distinguish skill levels or
 * reading/writing/speaking/signing here. Use language codes from the [IETF
 * BCP 47 standard](http://tools.ietf.org/html/bcp47).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\LanguageContract|\\Spatie\\SchemaOrg\\Contracts\\LanguageContract[]|string|string[] $knowsLanguage
 *
 * @return static
 *
 * @see https://schema.org/knowsLanguage
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1688
 */',
        'startLine' => 1301,
        'endLine' => 1304,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1316,
            'endLine' => 1316,
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
        'startLine' => 1316,
        'endLine' => 1319,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'legalAddress' => 
      array (
        'name' => 'legalAddress',
        'parameters' => 
        array (
          'legalAddress' => 
          array (
            'name' => 'legalAddress',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1333,
            'endLine' => 1333,
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
 * The legal address of an organization which acts as the officially
 * registered address used for legal and tax purposes. The legal address can
 * be different from the place of operations of a business and other
 * addresses can be part of an organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract|\\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract[] $legalAddress
 *
 * @return static
 *
 * @see https://schema.org/legalAddress
 */',
        'startLine' => 1333,
        'endLine' => 1336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'legalName' => 
      array (
        'name' => 'legalName',
        'parameters' => 
        array (
          'legalName' => 
          array (
            'name' => 'legalName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1347,
            'endLine' => 1347,
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
 * The official name of the organization, e.g. the registered company name.
 *
 * @param string|string[] $legalName
 *
 * @return static
 *
 * @see https://schema.org/legalName
 */',
        'startLine' => 1347,
        'endLine' => 1350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'legalRepresentative' => 
      array (
        'name' => 'legalRepresentative',
        'parameters' => 
        array (
          'legalRepresentative' => 
          array (
            'name' => 'legalRepresentative',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1362,
            'endLine' => 1362,
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
 * One or multiple persons who represent this organization legally such as
 * CEO or sole administrator.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $legalRepresentative
 *
 * @return static
 *
 * @see https://schema.org/legalRepresentative
 */',
        'startLine' => 1362,
        'endLine' => 1365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'leiCode' => 
      array (
        'name' => 'leiCode',
        'parameters' => 
        array (
          'leiCode' => 
          array (
            'name' => 'leiCode',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1377,
            'endLine' => 1377,
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
 * An organization identifier that uniquely identifies a legal entity as
 * defined in ISO 17442.
 *
 * @param string|string[] $leiCode
 *
 * @return static
 *
 * @see https://schema.org/leiCode
 */',
        'startLine' => 1377,
        'endLine' => 1380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'location' => 
      array (
        'name' => 'location',
        'parameters' => 
        array (
          'location' => 
          array (
            'name' => 'location',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1392,
            'endLine' => 1392,
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
 * The location of, for example, where an event is happening, where an
 * organization is located, or where an action takes place.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[]|\\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract|\\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract[]|\\Spatie\\SchemaOrg\\Contracts\\VirtualLocationContract|\\Spatie\\SchemaOrg\\Contracts\\VirtualLocationContract[]|string|string[] $location
 *
 * @return static
 *
 * @see https://schema.org/location
 */',
        'startLine' => 1392,
        'endLine' => 1395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1406,
            'endLine' => 1406,
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
        'startLine' => 1406,
        'endLine' => 1409,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1421,
            'endLine' => 1421,
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
        'startLine' => 1421,
        'endLine' => 1424,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1437,
            'endLine' => 1437,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'makesOffer' => 
      array (
        'name' => 'makesOffer',
        'parameters' => 
        array (
          'makesOffer' => 
          array (
            'name' => 'makesOffer',
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
 * A pointer to products or services offered by the organization or person.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OfferContract|\\Spatie\\SchemaOrg\\Contracts\\OfferContract[] $makesOffer
 *
 * @return static
 *
 * @see https://schema.org/makesOffer
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1465,
            'endLine' => 1465,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1479,
            'endLine' => 1479,
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
        'startLine' => 1479,
        'endLine' => 1482,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1493,
            'endLine' => 1493,
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
        'startLine' => 1493,
        'endLine' => 1496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'member' => 
      array (
        'name' => 'member',
        'parameters' => 
        array (
          'member' => 
          array (
            'name' => 'member',
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
 * A member of an Organization or a ProgramMembership. Organizations can be
 * members of organizations; ProgramMembership is typically for individuals.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $member
 *
 * @return static
 *
 * @see https://schema.org/member
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'memberOf' => 
      array (
        'name' => 'memberOf',
        'parameters' => 
        array (
          'memberOf' => 
          array (
            'name' => 'memberOf',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1523,
            'endLine' => 1523,
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
 * An Organization (or ProgramMembership) to which this Person or
 * Organization belongs.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MemberProgramTierContract|\\Spatie\\SchemaOrg\\Contracts\\MemberProgramTierContract[]|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\ProgramMembershipContract|\\Spatie\\SchemaOrg\\Contracts\\ProgramMembershipContract[] $memberOf
 *
 * @return static
 *
 * @see https://schema.org/memberOf
 */',
        'startLine' => 1523,
        'endLine' => 1526,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'members' => 
      array (
        'name' => 'members',
        'parameters' => 
        array (
          'members' => 
          array (
            'name' => 'members',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1537,
            'endLine' => 1537,
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
 * A member of this organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $members
 *
 * @return static
 *
 * @see https://schema.org/members
 */',
        'startLine' => 1537,
        'endLine' => 1540,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'menu' => 
      array (
        'name' => 'menu',
        'parameters' => 
        array (
          'menu' => 
          array (
            'name' => 'menu',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1552,
            'endLine' => 1552,
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
 * Either the actual menu as a structured representation, as text, or a URL
 * of the menu.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MenuContract|\\Spatie\\SchemaOrg\\Contracts\\MenuContract[]|string|string[] $menu
 *
 * @return static
 *
 * @see https://schema.org/menu
 */',
        'startLine' => 1552,
        'endLine' => 1555,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'naics' => 
      array (
        'name' => 'naics',
        'parameters' => 
        array (
          'naics' => 
          array (
            'name' => 'naics',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1567,
            'endLine' => 1567,
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
 * The North American Industry Classification System (NAICS) code for a
 * particular organization or business person.
 *
 * @param string|string[] $naics
 *
 * @return static
 *
 * @see https://schema.org/naics
 */',
        'startLine' => 1567,
        'endLine' => 1570,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1581,
            'endLine' => 1581,
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
        'startLine' => 1581,
        'endLine' => 1584,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'nonprofitStatus' => 
      array (
        'name' => 'nonprofitStatus',
        'parameters' => 
        array (
          'nonprofitStatus' => 
          array (
            'name' => 'nonprofitStatus',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1598,
            'endLine' => 1598,
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
 * nonprofitStatus indicates the legal status of a non-profit organization
 * in its primary place of business.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\NonprofitTypeContract|\\Spatie\\SchemaOrg\\Contracts\\NonprofitTypeContract[] $nonprofitStatus
 *
 * @return static
 *
 * @see https://schema.org/nonprofitStatus
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2543
 */',
        'startLine' => 1598,
        'endLine' => 1601,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'numberOfEmployees' => 
      array (
        'name' => 'numberOfEmployees',
        'parameters' => 
        array (
          'numberOfEmployees' => 
          array (
            'name' => 'numberOfEmployees',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1612,
            'endLine' => 1612,
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
 * The number of employees in an organization, e.g. business.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $numberOfEmployees
 *
 * @return static
 *
 * @see https://schema.org/numberOfEmployees
 */',
        'startLine' => 1612,
        'endLine' => 1615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'openingHours' => 
      array (
        'name' => 'openingHours',
        'parameters' => 
        array (
          'openingHours' => 
          array (
            'name' => 'openingHours',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1639,
            'endLine' => 1639,
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
 * The general opening hours for a business. Opening hours can be specified
 * as a weekly time range, starting with days, then times per day. Multiple
 * days can be listed with commas \',\' separating each day. Day or time
 * ranges are specified using a hyphen \'-\'.
 *
 * * Days are specified using the following two-letter combinations:
 * ```Mo```, ```Tu```, ```We```, ```Th```, ```Fr```, ```Sa```, ```Su```.
 * * Times are specified using 24:00 format. For example, 3pm is specified
 * as ```15:00```, 10am as ```10:00```.
 * * Here is an example: ```<time itemprop="openingHours" datetime="Tu,Th
 * 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>```.
 * * If a business is open 7 days a week, then it can be specified as
 * ```<time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday,
 * all day</time>```.
 *
 * @param string|string[] $openingHours
 *
 * @return static
 *
 * @see https://schema.org/openingHours
 */',
        'startLine' => 1639,
        'endLine' => 1642,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1653,
            'endLine' => 1653,
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
        'startLine' => 1653,
        'endLine' => 1656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'ownershipFundingInfo' => 
      array (
        'name' => 'ownershipFundingInfo',
        'parameters' => 
        array (
          'ownershipFundingInfo' => 
          array (
            'name' => 'ownershipFundingInfo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1674,
            'endLine' => 1674,
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
 * For an [[Organization]] (often but not necessarily a
 * [[NewsMediaOrganization]]), a description of organizational ownership
 * structure; funding and grants. In a news/media setting, this is with
 * particular reference to editorial independence.   Note that the
 * [[funder]] is also available and can be used to make basic funder
 * information machine-readable.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AboutPageContract|\\Spatie\\SchemaOrg\\Contracts\\AboutPageContract[]|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $ownershipFundingInfo
 *
 * @return static
 *
 * @see https://schema.org/ownershipFundingInfo
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 1674,
        'endLine' => 1677,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'owns' => 
      array (
        'name' => 'owns',
        'parameters' => 
        array (
          'owns' => 
          array (
            'name' => 'owns',
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
 * Products owned by the organization or person.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OwnershipInfoContract|\\Spatie\\SchemaOrg\\Contracts\\OwnershipInfoContract[]|\\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[] $owns
 *
 * @return static
 *
 * @see https://schema.org/owns
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'parentOrganization' => 
      array (
        'name' => 'parentOrganization',
        'parameters' => 
        array (
          'parentOrganization' => 
          array (
            'name' => 'parentOrganization',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1703,
            'endLine' => 1703,
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
 * The larger organization that this organization is a [[subOrganization]]
 * of, if any.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $parentOrganization
 *
 * @return static
 *
 * @see https://schema.org/parentOrganization
 */',
        'startLine' => 1703,
        'endLine' => 1706,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'paymentAccepted' => 
      array (
        'name' => 'paymentAccepted',
        'parameters' => 
        array (
          'paymentAccepted' => 
          array (
            'name' => 'paymentAccepted',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1717,
            'endLine' => 1717,
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
 * Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.
 *
 * @param string|string[] $paymentAccepted
 *
 * @return static
 *
 * @see https://schema.org/paymentAccepted
 */',
        'startLine' => 1717,
        'endLine' => 1720,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1731,
            'endLine' => 1731,
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
        'startLine' => 1731,
        'endLine' => 1734,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1745,
            'endLine' => 1745,
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
        'startLine' => 1745,
        'endLine' => 1748,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1760,
            'endLine' => 1760,
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
        'startLine' => 1760,
        'endLine' => 1763,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'priceRange' => 
      array (
        'name' => 'priceRange',
        'parameters' => 
        array (
          'priceRange' => 
          array (
            'name' => 'priceRange',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1774,
            'endLine' => 1774,
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
 * The price range of the business, for example ```$$$```.
 *
 * @param string|string[] $priceRange
 *
 * @return static
 *
 * @see https://schema.org/priceRange
 */',
        'startLine' => 1774,
        'endLine' => 1777,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1789,
            'endLine' => 1789,
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
        'startLine' => 1789,
        'endLine' => 1792,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'publishingPrinciples' => 
      array (
        'name' => 'publishingPrinciples',
        'parameters' => 
        array (
          'publishingPrinciples' => 
          array (
            'name' => 'publishingPrinciples',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1813,
            'endLine' => 1813,
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
 * The publishingPrinciples property indicates (typically via [[URL]]) a
 * document describing the editorial principles of an [[Organization]] (or
 * individual, e.g. a [[Person]] writing a blog) that relate to their
 * activities as a publisher, e.g. ethics or diversity policies. When
 * applied to a [[CreativeWork]] (e.g. [[NewsArticle]]) the principles are
 * those of the party primarily responsible for the creation of the
 * [[CreativeWork]].
 *
 * While such policies are most typically expressed in natural language,
 * sometimes related information (e.g. indicating a [[funder]]) can be
 * expressed using schema.org terminology.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $publishingPrinciples
 *
 * @return static
 *
 * @see https://schema.org/publishingPrinciples
 */',
        'startLine' => 1813,
        'endLine' => 1816,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1827,
            'endLine' => 1827,
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
        'startLine' => 1827,
        'endLine' => 1830,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1841,
            'endLine' => 1841,
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
        'startLine' => 1841,
        'endLine' => 1844,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1857,
            'endLine' => 1857,
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
        'startLine' => 1857,
        'endLine' => 1860,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'seeks' => 
      array (
        'name' => 'seeks',
        'parameters' => 
        array (
          'seeks' => 
          array (
            'name' => 'seeks',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1872,
            'endLine' => 1872,
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
 * A pointer to products or services sought by the organization or person
 * (demand).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DemandContract|\\Spatie\\SchemaOrg\\Contracts\\DemandContract[] $seeks
 *
 * @return static
 *
 * @see https://schema.org/seeks
 */',
        'startLine' => 1872,
        'endLine' => 1875,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'servesCuisine' => 
      array (
        'name' => 'servesCuisine',
        'parameters' => 
        array (
          'servesCuisine' => 
          array (
            'name' => 'servesCuisine',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1886,
            'endLine' => 1886,
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
 * The cuisine of the restaurant.
 *
 * @param string|string[] $servesCuisine
 *
 * @return static
 *
 * @see https://schema.org/servesCuisine
 */',
        'startLine' => 1886,
        'endLine' => 1889,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'serviceArea' => 
      array (
        'name' => 'serviceArea',
        'parameters' => 
        array (
          'serviceArea' => 
          array (
            'name' => 'serviceArea',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1900,
            'endLine' => 1900,
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
 * The geographic area where the service is provided.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract|\\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract[]|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $serviceArea
 *
 * @return static
 *
 * @see https://schema.org/serviceArea
 */',
        'startLine' => 1900,
        'endLine' => 1903,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'skills' => 
      array (
        'name' => 'skills',
        'parameters' => 
        array (
          'skills' => 
          array (
            'name' => 'skills',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1917,
            'endLine' => 1917,
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
 * A statement of knowledge, skill, ability, task or any other assertion
 * expressing a competency that is either claimed by a person, an
 * organization or desired or required to fulfill a role or to work in an
 * occupation.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $skills
 *
 * @return static
 *
 * @see https://schema.org/skills
 */',
        'startLine' => 1917,
        'endLine' => 1920,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1931,
            'endLine' => 1931,
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
        'startLine' => 1931,
        'endLine' => 1934,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1946,
            'endLine' => 1946,
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
        'startLine' => 1946,
        'endLine' => 1949,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 1963,
            'endLine' => 1963,
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
        'startLine' => 1963,
        'endLine' => 1966,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'sponsor' => 
      array (
        'name' => 'sponsor',
        'parameters' => 
        array (
          'sponsor' => 
          array (
            'name' => 'sponsor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1979,
            'endLine' => 1979,
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
 * A person or organization that supports a thing through a pledge, promise,
 * or financial contribution. E.g. a sponsor of a Medical Study or a
 * corporate sponsor of an event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $sponsor
 *
 * @return static
 *
 * @see https://schema.org/sponsor
 */',
        'startLine' => 1979,
        'endLine' => 1982,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'starRating' => 
      array (
        'name' => 'starRating',
        'parameters' => 
        array (
          'starRating' => 
          array (
            'name' => 'starRating',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1996,
            'endLine' => 1996,
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
 * An official rating for a lodging business or food establishment, e.g.
 * from national associations or standards bodies. Use the author property
 * to indicate the rating organization, e.g. as an Organization with name
 * such as (e.g. HOTREC, DEHOGA, WHR, or Hotelstars).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\RatingContract|\\Spatie\\SchemaOrg\\Contracts\\RatingContract[] $starRating
 *
 * @return static
 *
 * @see https://schema.org/starRating
 */',
        'startLine' => 1996,
        'endLine' => 1999,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'subOrganization' => 
      array (
        'name' => 'subOrganization',
        'parameters' => 
        array (
          'subOrganization' => 
          array (
            'name' => 'subOrganization',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2012,
            'endLine' => 2012,
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
 * A relationship between two organizations where the first includes the
 * second, e.g., as a subsidiary. See also: the more specific \'department\'
 * property.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $subOrganization
 *
 * @return static
 *
 * @see https://schema.org/subOrganization
 */',
        'startLine' => 2012,
        'endLine' => 2015,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 2027,
            'endLine' => 2027,
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
        'startLine' => 2027,
        'endLine' => 2030,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'taxID' => 
      array (
        'name' => 'taxID',
        'parameters' => 
        array (
          'taxID' => 
          array (
            'name' => 'taxID',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2042,
            'endLine' => 2042,
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
 * The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US
 * or the CIF/NIF in Spain.
 *
 * @param string|string[] $taxID
 *
 * @return static
 *
 * @see https://schema.org/taxID
 */',
        'startLine' => 2042,
        'endLine' => 2045,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 2056,
            'endLine' => 2056,
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
        'startLine' => 2056,
        'endLine' => 2059,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 2074,
            'endLine' => 2074,
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
        'startLine' => 2074,
        'endLine' => 2077,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'unnamedSourcesPolicy' => 
      array (
        'name' => 'unnamedSourcesPolicy',
        'parameters' => 
        array (
          'unnamedSourcesPolicy' => 
          array (
            'name' => 'unnamedSourcesPolicy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2092,
            'endLine' => 2092,
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
 * For an [[Organization]] (typically a [[NewsMediaOrganization]]), a
 * statement about policy on use of unnamed sources and the decision process
 * required.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $unnamedSourcesPolicy
 *
 * @return static
 *
 * @see https://schema.org/unnamedSourcesPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1525
 */',
        'startLine' => 2092,
        'endLine' => 2095,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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
            'startLine' => 2106,
            'endLine' => 2106,
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
        'startLine' => 2106,
        'endLine' => 2109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'aliasName' => NULL,
      ),
      'vatID' => 
      array (
        'name' => 'vatID',
        'parameters' => 
        array (
          'vatID' => 
          array (
            'name' => 'vatID',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2120,
            'endLine' => 2120,
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
 * The Value-added Tax ID of the organization or person.
 *
 * @param string|string[] $vatID
 *
 * @return static
 *
 * @see https://schema.org/vatID
 */',
        'startLine' => 2120,
        'endLine' => 2123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Distillery',
        'currentClassName' => 'Spatie\\SchemaOrg\\Distillery',
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