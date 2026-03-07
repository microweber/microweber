<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/DanceGroup.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\DanceGroup
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3d96bbb8f0a957821babf0879b69b8d98fd7fddf9b3ffbe5b9666a5ab448027f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\DanceGroup',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/DanceGroup.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\DanceGroup',
    'shortName' => 'DanceGroup',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A dance group&#x2014;for example, the Alvin Ailey Dance Theater or
 * Riverdance.
 *
 * @see https://schema.org/DanceGroup
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 1438,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\DanceGroupContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\OrganizationContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\PerformingGroupContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
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
            'startLine' => 30,
            'endLine' => 30,
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
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 49,
            'endLine' => 49,
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
        'startLine' => 49,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 72,
            'endLine' => 72,
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
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 86,
            'endLine' => 86,
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
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 104,
            'endLine' => 104,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 119,
            'endLine' => 119,
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
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 133,
            'endLine' => 133,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 147,
            'endLine' => 147,
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
        'startLine' => 147,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 161,
            'endLine' => 161,
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
        'startLine' => 161,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 175,
            'endLine' => 175,
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
        'startLine' => 175,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 189,
            'endLine' => 189,
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
        'startLine' => 189,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 204,
            'endLine' => 204,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 219,
            'endLine' => 219,
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
        'startLine' => 219,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 233,
            'endLine' => 233,
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
        'startLine' => 233,
        'endLine' => 236,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 247,
            'endLine' => 247,
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
        'startLine' => 247,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 265,
            'endLine' => 265,
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
        'startLine' => 265,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 282,
            'endLine' => 282,
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
        'startLine' => 282,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 296,
            'endLine' => 296,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 313,
            'endLine' => 313,
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
        'startLine' => 313,
        'endLine' => 316,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 327,
            'endLine' => 327,
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
        'startLine' => 327,
        'endLine' => 330,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 346,
            'endLine' => 346,
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
        'startLine' => 346,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 365,
            'endLine' => 365,
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
        'startLine' => 365,
        'endLine' => 368,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 380,
            'endLine' => 380,
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
        'startLine' => 380,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 394,
            'endLine' => 394,
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
        'startLine' => 394,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 408,
            'endLine' => 408,
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
        'startLine' => 408,
        'endLine' => 411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 422,
            'endLine' => 422,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 443,
            'endLine' => 443,
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
        'startLine' => 443,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 458,
            'endLine' => 458,
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
        'startLine' => 458,
        'endLine' => 461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 472,
            'endLine' => 472,
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
        'startLine' => 472,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 486,
            'endLine' => 486,
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
        'startLine' => 486,
        'endLine' => 489,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 500,
            'endLine' => 500,
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
        'startLine' => 500,
        'endLine' => 503,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 514,
            'endLine' => 514,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 528,
            'endLine' => 528,
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
        'startLine' => 528,
        'endLine' => 531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 542,
            'endLine' => 542,
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
        'startLine' => 542,
        'endLine' => 545,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 557,
            'endLine' => 557,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 574,
            'endLine' => 574,
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
        'startLine' => 574,
        'endLine' => 577,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 591,
            'endLine' => 591,
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
        'startLine' => 591,
        'endLine' => 594,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 607,
            'endLine' => 607,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 623,
            'endLine' => 623,
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
        'startLine' => 623,
        'endLine' => 626,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 651,
            'endLine' => 651,
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
        'startLine' => 651,
        'endLine' => 654,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 667,
            'endLine' => 667,
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
        'startLine' => 667,
        'endLine' => 670,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 683,
            'endLine' => 683,
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
        'startLine' => 683,
        'endLine' => 686,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 698,
            'endLine' => 698,
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
        'startLine' => 698,
        'endLine' => 701,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 712,
            'endLine' => 712,
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
        'startLine' => 712,
        'endLine' => 715,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 728,
            'endLine' => 728,
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
        'startLine' => 728,
        'endLine' => 731,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 744,
            'endLine' => 744,
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
        'startLine' => 744,
        'endLine' => 747,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 762,
            'endLine' => 762,
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
        'startLine' => 762,
        'endLine' => 765,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 777,
            'endLine' => 777,
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
        'startLine' => 777,
        'endLine' => 780,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 794,
            'endLine' => 794,
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
        'startLine' => 794,
        'endLine' => 797,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 841,
            'endLine' => 841,
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
        'startLine' => 841,
        'endLine' => 844,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 857,
            'endLine' => 857,
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
        'startLine' => 857,
        'endLine' => 860,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 876,
            'endLine' => 876,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 895,
            'endLine' => 895,
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
        'startLine' => 895,
        'endLine' => 898,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 912,
            'endLine' => 912,
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
        'startLine' => 912,
        'endLine' => 915,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 926,
            'endLine' => 926,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 941,
            'endLine' => 941,
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
        'startLine' => 941,
        'endLine' => 944,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 956,
            'endLine' => 956,
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
        'startLine' => 956,
        'endLine' => 959,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 971,
            'endLine' => 971,
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
        'startLine' => 971,
        'endLine' => 974,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 985,
            'endLine' => 985,
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
        'startLine' => 985,
        'endLine' => 988,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1001,
            'endLine' => 1001,
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
        'startLine' => 1001,
        'endLine' => 1004,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1015,
            'endLine' => 1015,
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
        'startLine' => 1015,
        'endLine' => 1018,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1030,
            'endLine' => 1030,
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
        'startLine' => 1030,
        'endLine' => 1033,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1045,
            'endLine' => 1045,
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
        'startLine' => 1045,
        'endLine' => 1048,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
 * A member of this organization.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $members
 *
 * @return static
 *
 * @see https://schema.org/members
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1074,
            'endLine' => 1074,
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
        'startLine' => 1074,
        'endLine' => 1077,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1088,
            'endLine' => 1088,
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
        'startLine' => 1088,
        'endLine' => 1091,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1105,
            'endLine' => 1105,
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
        'startLine' => 1105,
        'endLine' => 1108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1119,
            'endLine' => 1119,
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
        'startLine' => 1119,
        'endLine' => 1122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1140,
            'endLine' => 1140,
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
        'startLine' => 1140,
        'endLine' => 1143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1154,
            'endLine' => 1154,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1169,
            'endLine' => 1169,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1184,
            'endLine' => 1184,
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
        'startLine' => 1184,
        'endLine' => 1187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1208,
            'endLine' => 1208,
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
        'startLine' => 1208,
        'endLine' => 1211,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1222,
            'endLine' => 1222,
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
        'startLine' => 1222,
        'endLine' => 1225,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1236,
            'endLine' => 1236,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1252,
            'endLine' => 1252,
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
        'startLine' => 1252,
        'endLine' => 1255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1267,
            'endLine' => 1267,
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
        'startLine' => 1267,
        'endLine' => 1270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1281,
            'endLine' => 1281,
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
        'startLine' => 1281,
        'endLine' => 1284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1298,
            'endLine' => 1298,
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
        'startLine' => 1298,
        'endLine' => 1301,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1312,
            'endLine' => 1312,
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
        'startLine' => 1312,
        'endLine' => 1315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1328,
            'endLine' => 1328,
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
        'startLine' => 1328,
        'endLine' => 1331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1344,
            'endLine' => 1344,
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
        'startLine' => 1344,
        'endLine' => 1347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1359,
            'endLine' => 1359,
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
        'startLine' => 1359,
        'endLine' => 1362,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1374,
            'endLine' => 1374,
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
        'startLine' => 1374,
        'endLine' => 1377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1388,
            'endLine' => 1388,
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
        'startLine' => 1388,
        'endLine' => 1391,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1406,
            'endLine' => 1406,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1420,
            'endLine' => 1420,
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
        'startLine' => 1420,
        'endLine' => 1423,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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
            'startLine' => 1434,
            'endLine' => 1434,
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
        'startLine' => 1434,
        'endLine' => 1437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
        'currentClassName' => 'Spatie\\SchemaOrg\\DanceGroup',
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