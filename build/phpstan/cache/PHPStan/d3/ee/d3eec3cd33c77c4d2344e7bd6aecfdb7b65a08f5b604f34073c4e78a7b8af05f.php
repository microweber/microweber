<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/ProfessionalService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\ProfessionalService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e80cdaf52a3bd5b883da4281fe456f3dcaae36f2eb5d62667bc7662f8beabbb9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/ProfessionalService.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\ProfessionalService',
    'shortName' => 'ProfessionalService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Original definition: "provider of professional services."
 *
 * The general [[ProfessionalService]] type for local businesses was deprecated
 * due to confusion with [[Service]]. For reference, the types that it included
 * were: [[Dentist]],
 *         [[AccountingService]], [[Attorney]], [[Notary]], as well as types for
 * several kinds of [[HomeAndConstructionBusiness]]: [[Electrician]],
 * [[GeneralContractor]],
 *         [[HousePainter]], [[Locksmith]], [[Plumber]], [[RoofingContractor]].
 * [[LegalService]] was introduced as a more inclusive supertype of
 * [[Attorney]].
 *
 * @see https://schema.org/ProfessionalService
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 2055,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\ProfessionalServiceContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\LocalBusinessContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\OrganizationContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\PlaceContract',
      4 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
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
            'startLine' => 40,
            'endLine' => 40,
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
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 59,
            'endLine' => 59,
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
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 81,
            'endLine' => 81,
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
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 104,
            'endLine' => 104,
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
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 118,
            'endLine' => 118,
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
        'startLine' => 118,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 136,
            'endLine' => 136,
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
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 151,
            'endLine' => 151,
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
        'startLine' => 151,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 165,
            'endLine' => 165,
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
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 179,
            'endLine' => 179,
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
        'startLine' => 179,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 196,
            'endLine' => 196,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 210,
            'endLine' => 210,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 224,
            'endLine' => 224,
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
        'startLine' => 224,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 238,
            'endLine' => 238,
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
        'startLine' => 238,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 258,
            'endLine' => 258,
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
        'startLine' => 258,
        'endLine' => 261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 273,
            'endLine' => 273,
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
        'startLine' => 273,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 288,
            'endLine' => 288,
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
        'startLine' => 288,
        'endLine' => 291,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 303,
            'endLine' => 303,
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
        'startLine' => 303,
        'endLine' => 306,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 317,
            'endLine' => 317,
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
        'startLine' => 317,
        'endLine' => 320,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 331,
            'endLine' => 331,
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
        'startLine' => 331,
        'endLine' => 334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 345,
            'endLine' => 345,
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
        'startLine' => 345,
        'endLine' => 348,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 359,
            'endLine' => 359,
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
        'startLine' => 359,
        'endLine' => 362,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 374,
            'endLine' => 374,
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
        'startLine' => 374,
        'endLine' => 377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 392,
            'endLine' => 392,
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
        'startLine' => 392,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 414,
            'endLine' => 414,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 431,
            'endLine' => 431,
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
        'startLine' => 431,
        'endLine' => 434,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 445,
            'endLine' => 445,
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
        'startLine' => 445,
        'endLine' => 448,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 462,
            'endLine' => 462,
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
        'startLine' => 462,
        'endLine' => 465,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 476,
            'endLine' => 476,
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
        'startLine' => 476,
        'endLine' => 479,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 495,
            'endLine' => 495,
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
        'startLine' => 495,
        'endLine' => 498,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 514,
            'endLine' => 514,
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
        'startLine' => 514,
        'endLine' => 517,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 529,
            'endLine' => 529,
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
        'startLine' => 529,
        'endLine' => 532,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 543,
            'endLine' => 543,
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
        'startLine' => 543,
        'endLine' => 546,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 557,
            'endLine' => 557,
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
        'startLine' => 557,
        'endLine' => 560,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 571,
            'endLine' => 571,
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
        'startLine' => 571,
        'endLine' => 574,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 592,
            'endLine' => 592,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 607,
            'endLine' => 607,
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
        'startLine' => 607,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 621,
            'endLine' => 621,
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
        'startLine' => 621,
        'endLine' => 624,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 635,
            'endLine' => 635,
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
        'startLine' => 635,
        'endLine' => 638,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 649,
            'endLine' => 649,
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
        'startLine' => 649,
        'endLine' => 652,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 663,
            'endLine' => 663,
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
        'startLine' => 663,
        'endLine' => 666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 677,
            'endLine' => 677,
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
        'startLine' => 677,
        'endLine' => 680,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 691,
            'endLine' => 691,
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
        'startLine' => 691,
        'endLine' => 694,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 706,
            'endLine' => 706,
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
        'startLine' => 706,
        'endLine' => 709,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 723,
            'endLine' => 723,
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
        'startLine' => 723,
        'endLine' => 726,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 737,
            'endLine' => 737,
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
        'startLine' => 737,
        'endLine' => 740,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 755,
            'endLine' => 755,
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
        'startLine' => 755,
        'endLine' => 758,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 771,
            'endLine' => 771,
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
        'startLine' => 771,
        'endLine' => 774,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 788,
            'endLine' => 788,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 806,
            'endLine' => 806,
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
        'startLine' => 806,
        'endLine' => 809,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 823,
            'endLine' => 823,
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
        'startLine' => 823,
        'endLine' => 826,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 842,
            'endLine' => 842,
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
        'startLine' => 842,
        'endLine' => 845,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 858,
            'endLine' => 858,
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
        'startLine' => 858,
        'endLine' => 861,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 875,
            'endLine' => 875,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 892,
            'endLine' => 892,
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
        'startLine' => 892,
        'endLine' => 895,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 909,
            'endLine' => 909,
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
        'startLine' => 909,
        'endLine' => 912,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 926,
            'endLine' => 926,
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
        'startLine' => 926,
        'endLine' => 929,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 942,
            'endLine' => 942,
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
        'startLine' => 942,
        'endLine' => 945,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 958,
            'endLine' => 958,
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
        'startLine' => 958,
        'endLine' => 961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 978,
            'endLine' => 978,
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
        'startLine' => 978,
        'endLine' => 981,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1006,
            'endLine' => 1006,
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
        'startLine' => 1006,
        'endLine' => 1009,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1020,
            'endLine' => 1020,
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
        'startLine' => 1020,
        'endLine' => 1023,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1036,
            'endLine' => 1036,
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
        'startLine' => 1036,
        'endLine' => 1039,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1052,
            'endLine' => 1052,
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
        'startLine' => 1052,
        'endLine' => 1055,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1067,
            'endLine' => 1067,
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
        'startLine' => 1067,
        'endLine' => 1070,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1081,
            'endLine' => 1081,
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
        'startLine' => 1081,
        'endLine' => 1084,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1097,
            'endLine' => 1097,
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
        'startLine' => 1097,
        'endLine' => 1100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1113,
            'endLine' => 1113,
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
        'startLine' => 1113,
        'endLine' => 1116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1131,
            'endLine' => 1131,
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
        'startLine' => 1131,
        'endLine' => 1134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1146,
            'endLine' => 1146,
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
        'startLine' => 1146,
        'endLine' => 1149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1163,
            'endLine' => 1163,
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
        'startLine' => 1163,
        'endLine' => 1166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1177,
            'endLine' => 1177,
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
        'startLine' => 1177,
        'endLine' => 1180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1193,
            'endLine' => 1193,
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
        'startLine' => 1193,
        'endLine' => 1196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1224,
            'endLine' => 1224,
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
        'startLine' => 1224,
        'endLine' => 1227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1240,
            'endLine' => 1240,
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
        'startLine' => 1240,
        'endLine' => 1243,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1259,
            'endLine' => 1259,
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
        'startLine' => 1259,
        'endLine' => 1262,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1278,
            'endLine' => 1278,
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
        'startLine' => 1278,
        'endLine' => 1281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1293,
            'endLine' => 1293,
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
        'startLine' => 1293,
        'endLine' => 1296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1310,
            'endLine' => 1310,
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
        'startLine' => 1310,
        'endLine' => 1313,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1324,
            'endLine' => 1324,
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
        'startLine' => 1324,
        'endLine' => 1327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1339,
            'endLine' => 1339,
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
        'startLine' => 1339,
        'endLine' => 1342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1354,
            'endLine' => 1354,
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
        'startLine' => 1354,
        'endLine' => 1357,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1369,
            'endLine' => 1369,
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
        'startLine' => 1369,
        'endLine' => 1372,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1383,
            'endLine' => 1383,
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
        'startLine' => 1383,
        'endLine' => 1386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1398,
            'endLine' => 1398,
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
        'startLine' => 1398,
        'endLine' => 1401,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1414,
            'endLine' => 1414,
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
        'startLine' => 1414,
        'endLine' => 1417,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1428,
            'endLine' => 1428,
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
        'startLine' => 1428,
        'endLine' => 1431,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1442,
            'endLine' => 1442,
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
        'startLine' => 1442,
        'endLine' => 1445,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1456,
            'endLine' => 1456,
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
        'startLine' => 1456,
        'endLine' => 1459,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1470,
            'endLine' => 1470,
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
        'startLine' => 1470,
        'endLine' => 1473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1485,
            'endLine' => 1485,
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
        'startLine' => 1485,
        'endLine' => 1488,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1500,
            'endLine' => 1500,
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
        'startLine' => 1500,
        'endLine' => 1503,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1514,
            'endLine' => 1514,
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
        'startLine' => 1514,
        'endLine' => 1517,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1529,
            'endLine' => 1529,
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
        'startLine' => 1529,
        'endLine' => 1532,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1543,
            'endLine' => 1543,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1560,
            'endLine' => 1560,
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
        'startLine' => 1560,
        'endLine' => 1563,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1574,
            'endLine' => 1574,
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
        'startLine' => 1574,
        'endLine' => 1577,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1601,
            'endLine' => 1601,
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
        'startLine' => 1601,
        'endLine' => 1604,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1615,
            'endLine' => 1615,
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
        'startLine' => 1615,
        'endLine' => 1618,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1636,
            'endLine' => 1636,
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
        'startLine' => 1636,
        'endLine' => 1639,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1650,
            'endLine' => 1650,
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
        'startLine' => 1650,
        'endLine' => 1653,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1665,
            'endLine' => 1665,
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
        'startLine' => 1665,
        'endLine' => 1668,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1679,
            'endLine' => 1679,
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
        'startLine' => 1679,
        'endLine' => 1682,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1693,
            'endLine' => 1693,
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
        'startLine' => 1693,
        'endLine' => 1696,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1707,
            'endLine' => 1707,
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
        'startLine' => 1707,
        'endLine' => 1710,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1722,
            'endLine' => 1722,
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
        'startLine' => 1722,
        'endLine' => 1725,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1736,
            'endLine' => 1736,
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
        'startLine' => 1736,
        'endLine' => 1739,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1751,
            'endLine' => 1751,
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
        'startLine' => 1751,
        'endLine' => 1754,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1775,
            'endLine' => 1775,
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
        'startLine' => 1775,
        'endLine' => 1778,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1789,
            'endLine' => 1789,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1803,
            'endLine' => 1803,
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
        'startLine' => 1803,
        'endLine' => 1806,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1819,
            'endLine' => 1819,
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
        'startLine' => 1819,
        'endLine' => 1822,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1834,
            'endLine' => 1834,
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
        'startLine' => 1834,
        'endLine' => 1837,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1848,
            'endLine' => 1848,
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
        'startLine' => 1848,
        'endLine' => 1851,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1865,
            'endLine' => 1865,
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
        'startLine' => 1865,
        'endLine' => 1868,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1879,
            'endLine' => 1879,
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
        'startLine' => 1879,
        'endLine' => 1882,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1894,
            'endLine' => 1894,
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
        'startLine' => 1894,
        'endLine' => 1897,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1911,
            'endLine' => 1911,
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
        'startLine' => 1911,
        'endLine' => 1914,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1927,
            'endLine' => 1927,
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
        'startLine' => 1927,
        'endLine' => 1930,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1943,
            'endLine' => 1943,
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
        'startLine' => 1943,
        'endLine' => 1946,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1958,
            'endLine' => 1958,
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
        'startLine' => 1958,
        'endLine' => 1961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1973,
            'endLine' => 1973,
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
        'startLine' => 1973,
        'endLine' => 1976,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 1987,
            'endLine' => 1987,
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
        'startLine' => 1987,
        'endLine' => 1990,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 2005,
            'endLine' => 2005,
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
        'startLine' => 2005,
        'endLine' => 2008,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 2023,
            'endLine' => 2023,
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
        'startLine' => 2023,
        'endLine' => 2026,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 2037,
            'endLine' => 2037,
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
        'startLine' => 2037,
        'endLine' => 2040,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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
            'startLine' => 2051,
            'endLine' => 2051,
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
        'startLine' => 2051,
        'endLine' => 2054,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
        'currentClassName' => 'Spatie\\SchemaOrg\\ProfessionalService',
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