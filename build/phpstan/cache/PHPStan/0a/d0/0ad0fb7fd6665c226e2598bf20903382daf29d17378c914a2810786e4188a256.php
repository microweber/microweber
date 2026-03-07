<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/UserPlusOnes.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\UserPlusOnes
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-10ed5c90938f5fe3c8580cc5e9f3dc3fe38c6431a72703a088d04ad29d0f2544-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/UserPlusOnes.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\UserPlusOnes',
    'shortName' => 'UserPlusOnes',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * UserInteraction and its subtypes is an old way of talking about users
 * interacting with pages. It is generally better to use [[Action]]-based
 * vocabulary, alongside types such as [[Comment]].
 *
 * @see https://schema.org/UserPlusOnes
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 861,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\UserPlusOnesContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\EventContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\UserInteractionContract',
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
      'about' => 
      array (
        'name' => 'about',
        'parameters' => 
        array (
          'about' => 
          array (
            'name' => 'about',
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
 * The subject matter of the content.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $about
 *
 * @return static
 *
 * @see https://schema.org/about
 * @link https://github.com/schemaorg/schemaorg/issues/1670
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'actor' => 
      array (
        'name' => 'actor',
        'parameters' => 
        array (
          'actor' => 
          array (
            'name' => 'actor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 46,
            'endLine' => 46,
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
 * An actor (individual or a group), e.g. in TV, radio, movie, video games
 * etc., or in an event. Actors can be associated with individual items or
 * with a series, episode, clip.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PerformingGroupContract|\\Spatie\\SchemaOrg\\Contracts\\PerformingGroupContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $actor
 *
 * @return static
 *
 * @see https://schema.org/actor
 */',
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 69,
            'endLine' => 69,
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
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 84,
            'endLine' => 84,
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
        'startLine' => 84,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 98,
            'endLine' => 98,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'attendee' => 
      array (
        'name' => 'attendee',
        'parameters' => 
        array (
          'attendee' => 
          array (
            'name' => 'attendee',
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
 * A person or organization attending the event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $attendee
 *
 * @return static
 *
 * @see https://schema.org/attendee
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'attendees' => 
      array (
        'name' => 'attendees',
        'parameters' => 
        array (
          'attendees' => 
          array (
            'name' => 'attendees',
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
 * A person attending the event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $attendees
 *
 * @return static
 *
 * @see https://schema.org/attendees
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 140,
            'endLine' => 140,
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
        'startLine' => 140,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'composer' => 
      array (
        'name' => 'composer',
        'parameters' => 
        array (
          'composer' => 
          array (
            'name' => 'composer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 155,
            'endLine' => 155,
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
 * The person or organization who wrote a composition, or who is the
 * composer of a work performed at some event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $composer
 *
 * @return static
 *
 * @see https://schema.org/composer
 */',
        'startLine' => 155,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'contributor' => 
      array (
        'name' => 'contributor',
        'parameters' => 
        array (
          'contributor' => 
          array (
            'name' => 'contributor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
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
 * A secondary contributor to the CreativeWork or Event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $contributor
 *
 * @return static
 *
 * @see https://schema.org/contributor
 */',
        'startLine' => 169,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 183,
            'endLine' => 183,
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
        'startLine' => 183,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'director' => 
      array (
        'name' => 'director',
        'parameters' => 
        array (
          'director' => 
          array (
            'name' => 'director',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 199,
            'endLine' => 199,
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
 * A director of e.g. TV, radio, movie, video gaming etc. content, or of an
 * event. Directors can be associated with individual items or with a
 * series, episode, clip.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $director
 *
 * @return static
 *
 * @see https://schema.org/director
 */',
        'startLine' => 199,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 216,
            'endLine' => 216,
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
        'startLine' => 216,
        'endLine' => 219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'doorTime' => 
      array (
        'name' => 'doorTime',
        'parameters' => 
        array (
          'doorTime' => 
          array (
            'name' => 'doorTime',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 230,
            'endLine' => 230,
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
 * The time admission will commence.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $doorTime
 *
 * @return static
 *
 * @see https://schema.org/doorTime
 */',
        'startLine' => 230,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'duration' => 
      array (
        'name' => 'duration',
        'parameters' => 
        array (
          'duration' => 
          array (
            'name' => 'duration',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 245,
            'endLine' => 245,
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
 * The duration of the item (movie, audio recording, event, etc.) in [ISO
 * 8601 duration format](http://en.wikipedia.org/wiki/ISO_8601).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DurationContract|\\Spatie\\SchemaOrg\\Contracts\\DurationContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $duration
 *
 * @return static
 *
 * @see https://schema.org/duration
 */',
        'startLine' => 245,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'endDate' => 
      array (
        'name' => 'endDate',
        'parameters' => 
        array (
          'endDate' => 
          array (
            'name' => 'endDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
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
 * The end date and time of the item (in [ISO 8601 date
 * format](http://en.wikipedia.org/wiki/ISO_8601)).
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $endDate
 *
 * @return static
 *
 * @see https://schema.org/endDate
 * @link https://github.com/schemaorg/schemaorg/issues/2486
 */',
        'startLine' => 261,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'eventAttendanceMode' => 
      array (
        'name' => 'eventAttendanceMode',
        'parameters' => 
        array (
          'eventAttendanceMode' => 
          array (
            'name' => 'eventAttendanceMode',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 278,
            'endLine' => 278,
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
 * The eventAttendanceMode of an event indicates whether it occurs online,
 * offline, or a mix.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventAttendanceModeEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\EventAttendanceModeEnumerationContract[] $eventAttendanceMode
 *
 * @return static
 *
 * @see https://schema.org/eventAttendanceMode
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1842
 */',
        'startLine' => 278,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'eventSchedule' => 
      array (
        'name' => 'eventSchedule',
        'parameters' => 
        array (
          'eventSchedule' => 
          array (
            'name' => 'eventSchedule',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 308,
            'endLine' => 308,
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
 * Associates an [[Event]] with a [[Schedule]]. There are circumstances
 * where it is preferable to share a schedule for a series of
 *       repeating events rather than data on the individual events
 * themselves. For example, a website or application might prefer to publish
 * a schedule for a weekly
 *       gym class rather than provide data on every event. A schedule could
 * be processed by applications to add forthcoming events to a calendar. An
 * [[Event]] that
 *       is associated with a [[Schedule]] using this property should not
 * have [[startDate]] or [[endDate]] properties. These are instead defined
 * within the associated
 *       [[Schedule]], this avoids any ambiguity for clients using the data.
 * The property might have repeated values to specify different schedules,
 * e.g. for different months
 *       or seasons.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ScheduleContract|\\Spatie\\SchemaOrg\\Contracts\\ScheduleContract[] $eventSchedule
 *
 * @return static
 *
 * @see https://schema.org/eventSchedule
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1457
 */',
        'startLine' => 308,
        'endLine' => 311,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'eventStatus' => 
      array (
        'name' => 'eventStatus',
        'parameters' => 
        array (
          'eventStatus' => 
          array (
            'name' => 'eventStatus',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 323,
            'endLine' => 323,
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
 * An eventStatus of an event represents its status; particularly useful
 * when an event is cancelled or rescheduled.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventStatusTypeContract|\\Spatie\\SchemaOrg\\Contracts\\EventStatusTypeContract[] $eventStatus
 *
 * @return static
 *
 * @see https://schema.org/eventStatus
 */',
        'startLine' => 323,
        'endLine' => 326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 338,
            'endLine' => 338,
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
        'startLine' => 338,
        'endLine' => 341,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 355,
            'endLine' => 355,
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
        'startLine' => 355,
        'endLine' => 358,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 373,
            'endLine' => 373,
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
        'startLine' => 373,
        'endLine' => 376,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 388,
            'endLine' => 388,
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
        'startLine' => 388,
        'endLine' => 391,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'inLanguage' => 
      array (
        'name' => 'inLanguage',
        'parameters' => 
        array (
          'inLanguage' => 
          array (
            'name' => 'inLanguage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 406,
            'endLine' => 406,
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
 * The language of the content or performance or used in an action. Please
 * use one of the language codes from the [IETF BCP 47
 * standard](http://tools.ietf.org/html/bcp47). See also
 * [[availableLanguage]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\LanguageContract|\\Spatie\\SchemaOrg\\Contracts\\LanguageContract[]|string|string[] $inLanguage
 *
 * @return static
 *
 * @see https://schema.org/inLanguage
 * @link https://github.com/schemaorg/schemaorg/issues/2382
 */',
        'startLine' => 406,
        'endLine' => 409,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 420,
            'endLine' => 420,
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
        'startLine' => 420,
        'endLine' => 423,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 436,
            'endLine' => 436,
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
        'startLine' => 436,
        'endLine' => 439,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 451,
            'endLine' => 451,
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
        'startLine' => 451,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 467,
            'endLine' => 467,
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
        'startLine' => 467,
        'endLine' => 470,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 481,
            'endLine' => 481,
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
        'startLine' => 481,
        'endLine' => 484,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'maximumPhysicalAttendeeCapacity' => 
      array (
        'name' => 'maximumPhysicalAttendeeCapacity',
        'parameters' => 
        array (
          'maximumPhysicalAttendeeCapacity' => 
          array (
            'name' => 'maximumPhysicalAttendeeCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 499,
            'endLine' => 499,
            'startColumn' => 53,
            'endColumn' => 84,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The maximum physical attendee capacity of an [[Event]] whose
 * [[eventAttendanceMode]] is [[OfflineEventAttendanceMode]] (or the offline
 * aspects, in the case of a [[MixedEventAttendanceMode]]).
 *
 * @param int|int[] $maximumPhysicalAttendeeCapacity
 *
 * @return static
 *
 * @see https://schema.org/maximumPhysicalAttendeeCapacity
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1842
 */',
        'startLine' => 499,
        'endLine' => 502,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'maximumVirtualAttendeeCapacity' => 
      array (
        'name' => 'maximumVirtualAttendeeCapacity',
        'parameters' => 
        array (
          'maximumVirtualAttendeeCapacity' => 
          array (
            'name' => 'maximumVirtualAttendeeCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 517,
            'endLine' => 517,
            'startColumn' => 52,
            'endColumn' => 82,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The maximum virtual attendee capacity of an [[Event]] whose
 * [[eventAttendanceMode]] is [[OnlineEventAttendanceMode]] (or the online
 * aspects, in the case of a [[MixedEventAttendanceMode]]).
 *
 * @param int|int[] $maximumVirtualAttendeeCapacity
 *
 * @return static
 *
 * @see https://schema.org/maximumVirtualAttendeeCapacity
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1842
 */',
        'startLine' => 517,
        'endLine' => 520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 531,
            'endLine' => 531,
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
        'startLine' => 531,
        'endLine' => 534,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 553,
            'endLine' => 553,
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
        'startLine' => 553,
        'endLine' => 556,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'organizer' => 
      array (
        'name' => 'organizer',
        'parameters' => 
        array (
          'organizer' => 
          array (
            'name' => 'organizer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
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
 * An organizer of an Event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $organizer
 *
 * @return static
 *
 * @see https://schema.org/organizer
 */',
        'startLine' => 567,
        'endLine' => 570,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'performer' => 
      array (
        'name' => 'performer',
        'parameters' => 
        array (
          'performer' => 
          array (
            'name' => 'performer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 582,
            'endLine' => 582,
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
 * A performer at the event&#x2014;for example, a presenter, musician,
 * musical group or actor.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $performer
 *
 * @return static
 *
 * @see https://schema.org/performer
 */',
        'startLine' => 582,
        'endLine' => 585,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'performers' => 
      array (
        'name' => 'performers',
        'parameters' => 
        array (
          'performers' => 
          array (
            'name' => 'performers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 597,
            'endLine' => 597,
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
 * The main performer or performers of the event&#x2014;for example, a
 * presenter, musician, or actor.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $performers
 *
 * @return static
 *
 * @see https://schema.org/performers
 */',
        'startLine' => 597,
        'endLine' => 600,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 612,
            'endLine' => 612,
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
        'startLine' => 612,
        'endLine' => 615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'previousStartDate' => 
      array (
        'name' => 'previousStartDate',
        'parameters' => 
        array (
          'previousStartDate' => 
          array (
            'name' => 'previousStartDate',
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
 * Used in conjunction with eventStatus for rescheduled or cancelled events.
 * This property contains the previously scheduled start date. For
 * rescheduled events, the startDate property should be used for the newly
 * scheduled start date. In the (rare) case of an event that has been
 * postponed and rescheduled multiple times, this field may be repeated.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $previousStartDate
 *
 * @return static
 *
 * @see https://schema.org/previousStartDate
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'recordedIn' => 
      array (
        'name' => 'recordedIn',
        'parameters' => 
        array (
          'recordedIn' => 
          array (
            'name' => 'recordedIn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 644,
            'endLine' => 644,
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
 * The CreativeWork that captured all or part of this Event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $recordedIn
 *
 * @return static
 *
 * @see https://schema.org/recordedIn
 */',
        'startLine' => 644,
        'endLine' => 647,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'remainingAttendeeCapacity' => 
      array (
        'name' => 'remainingAttendeeCapacity',
        'parameters' => 
        array (
          'remainingAttendeeCapacity' => 
          array (
            'name' => 'remainingAttendeeCapacity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 658,
            'endLine' => 658,
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
 * The number of attendee places for an event that remain unallocated.
 *
 * @param int|int[] $remainingAttendeeCapacity
 *
 * @return static
 *
 * @see https://schema.org/remainingAttendeeCapacity
 */',
        'startLine' => 658,
        'endLine' => 661,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 672,
            'endLine' => 672,
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
        'startLine' => 672,
        'endLine' => 675,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 688,
            'endLine' => 688,
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
        'startLine' => 688,
        'endLine' => 691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 704,
            'endLine' => 704,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'startDate' => 
      array (
        'name' => 'startDate',
        'parameters' => 
        array (
          'startDate' => 
          array (
            'name' => 'startDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 720,
            'endLine' => 720,
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
 * The start date and time of the item (in [ISO 8601 date
 * format](http://en.wikipedia.org/wiki/ISO_8601)).
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $startDate
 *
 * @return static
 *
 * @see https://schema.org/startDate
 * @link https://github.com/schemaorg/schemaorg/issues/2486
 */',
        'startLine' => 720,
        'endLine' => 723,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'subEvent' => 
      array (
        'name' => 'subEvent',
        'parameters' => 
        array (
          'subEvent' => 
          array (
            'name' => 'subEvent',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 736,
            'endLine' => 736,
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
 * An Event that is part of this event. For example, a conference event
 * includes many presentations, each of which is a subEvent of the
 * conference.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $subEvent
 *
 * @return static
 *
 * @see https://schema.org/subEvent
 */',
        'startLine' => 736,
        'endLine' => 739,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'subEvents' => 
      array (
        'name' => 'subEvents',
        'parameters' => 
        array (
          'subEvents' => 
          array (
            'name' => 'subEvents',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 751,
            'endLine' => 751,
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
 * Events that are a part of this event. For example, a conference event
 * includes many presentations, each subEvents of the conference.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $subEvents
 *
 * @return static
 *
 * @see https://schema.org/subEvents
 */',
        'startLine' => 751,
        'endLine' => 754,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 766,
            'endLine' => 766,
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
        'startLine' => 766,
        'endLine' => 769,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'superEvent' => 
      array (
        'name' => 'superEvent',
        'parameters' => 
        array (
          'superEvent' => 
          array (
            'name' => 'superEvent',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 782,
            'endLine' => 782,
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
 * An event that this event is a part of. For example, a collection of
 * individual music performances might each have a music festival as their
 * superEvent.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $superEvent
 *
 * @return static
 *
 * @see https://schema.org/superEvent
 */',
        'startLine' => 782,
        'endLine' => 785,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'translator' => 
      array (
        'name' => 'translator',
        'parameters' => 
        array (
          'translator' => 
          array (
            'name' => 'translator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 798,
            'endLine' => 798,
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
 * Organization or person who adapts a creative work to different languages,
 * regional differences and technical requirements of a target market, or
 * that translates during some event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $translator
 *
 * @return static
 *
 * @see https://schema.org/translator
 */',
        'startLine' => 798,
        'endLine' => 801,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'typicalAgeRange' => 
      array (
        'name' => 'typicalAgeRange',
        'parameters' => 
        array (
          'typicalAgeRange' => 
          array (
            'name' => 'typicalAgeRange',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 812,
            'endLine' => 812,
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
 * The typical expected age range, e.g. \'7-9\', \'11-\'.
 *
 * @param string|string[] $typicalAgeRange
 *
 * @return static
 *
 * @see https://schema.org/typicalAgeRange
 */',
        'startLine' => 812,
        'endLine' => 815,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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
            'startLine' => 826,
            'endLine' => 826,
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
        'startLine' => 826,
        'endLine' => 829,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'workFeatured' => 
      array (
        'name' => 'workFeatured',
        'parameters' => 
        array (
          'workFeatured' => 
          array (
            'name' => 'workFeatured',
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
 * A work featured in some event, e.g. exhibited in an ExhibitionEvent.
 *        Specific subproperties are available for workPerformed (e.g. a
 * play), or a workPresented (a Movie at a ScreeningEvent).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $workFeatured
 *
 * @return static
 *
 * @see https://schema.org/workFeatured
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'aliasName' => NULL,
      ),
      'workPerformed' => 
      array (
        'name' => 'workPerformed',
        'parameters' => 
        array (
          'workPerformed' => 
          array (
            'name' => 'workPerformed',
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
 * A work performed in some event, for example a play performed in a
 * TheaterEvent.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $workPerformed
 *
 * @return static
 *
 * @see https://schema.org/workPerformed
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
        'currentClassName' => 'Spatie\\SchemaOrg\\UserPlusOnes',
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