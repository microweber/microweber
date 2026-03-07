<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/LearningResource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\LearningResource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b981c34543cc34dae4bde2687c5607bf20e2261fd1ec681e17253c0b109d52e7-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\LearningResource',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/LearningResource.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\LearningResource',
    'shortName' => 'LearningResource',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The LearningResource type can be used to indicate [[CreativeWork]]s (whether
 * physical or digital) that have a particular and explicit orientation towards
 * learning, education, skill acquisition, and other educational purposes.
 *
 * [[LearningResource]] is expected to be used as an addition to a primary type
 * such as [[Book]], [[VideoObject]], [[Product]] etc.
 *
 * [[EducationEvent]] serves a similar purpose for event-like things (e.g. a
 * [[Trip]]). A [[LearningResource]] may be created as a result of an
 * [[EducationEvent]], for example by recording one.
 *
 * @see https://schema.org/LearningResource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1401
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 2148,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\LearningResourceContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract',
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
            'startLine' => 38,
            'endLine' => 38,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'abstract' => 
      array (
        'name' => 'abstract',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
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
 * An abstract is a short description that summarizes a [[CreativeWork]].
 *
 * @param string|string[] $abstract
 *
 * @return static
 *
 * @see https://schema.org/abstract
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/276
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessMode' => 
      array (
        'name' => 'accessMode',
        'parameters' => 
        array (
          'accessMode' => 
          array (
            'name' => 'accessMode',
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
 * The human sensory perceptual system or cognitive faculty through which a
 * person may process or perceive information. Values should be drawn from
 * the [approved
 * vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).
 *
 * @param string|string[] $accessMode
 *
 * @return static
 *
 * @see https://schema.org/accessMode
 * @link https://github.com/schemaorg/schemaorg/issues/1100
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessModeSufficient' => 
      array (
        'name' => 'accessModeSufficient',
        'parameters' => 
        array (
          'accessModeSufficient' => 
          array (
            'name' => 'accessModeSufficient',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 90,
            'endLine' => 90,
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
 * A list of single or combined accessModes that are sufficient to
 * understand all the intellectual content of a resource. Values should be
 * drawn from the [approved
 * vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessModeSufficient-vocabulary).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ItemListContract|\\Spatie\\SchemaOrg\\Contracts\\ItemListContract[] $accessModeSufficient
 *
 * @return static
 *
 * @see https://schema.org/accessModeSufficient
 * @link https://github.com/schemaorg/schemaorg/issues/1100
 */',
        'startLine' => 90,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessibilityAPI' => 
      array (
        'name' => 'accessibilityAPI',
        'parameters' => 
        array (
          'accessibilityAPI' => 
          array (
            'name' => 'accessibilityAPI',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
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
 * Indicates that the resource is compatible with the referenced
 * accessibility API. Values should be drawn from the [approved
 * vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).
 *
 * @param string|string[] $accessibilityAPI
 *
 * @return static
 *
 * @see https://schema.org/accessibilityAPI
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessibilityControl' => 
      array (
        'name' => 'accessibilityControl',
        'parameters' => 
        array (
          'accessibilityControl' => 
          array (
            'name' => 'accessibilityControl',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 122,
            'endLine' => 122,
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
 * Identifies input methods that are sufficient to fully control the
 * described resource. Values should be drawn from the [approved
 * vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).
 *
 * @param string|string[] $accessibilityControl
 *
 * @return static
 *
 * @see https://schema.org/accessibilityControl
 */',
        'startLine' => 122,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessibilityFeature' => 
      array (
        'name' => 'accessibilityFeature',
        'parameters' => 
        array (
          'accessibilityFeature' => 
          array (
            'name' => 'accessibilityFeature',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
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
 * Content features of the resource, such as accessible media, alternatives
 * and supported enhancements for accessibility. Values should be drawn from
 * the [approved
 * vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).
 *
 * @param string|string[] $accessibilityFeature
 *
 * @return static
 *
 * @see https://schema.org/accessibilityFeature
 */',
        'startLine' => 139,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessibilityHazard' => 
      array (
        'name' => 'accessibilityHazard',
        'parameters' => 
        array (
          'accessibilityHazard' => 
          array (
            'name' => 'accessibilityHazard',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 156,
            'endLine' => 156,
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
 * A characteristic of the described resource that is physiologically
 * dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should
 * be drawn from the [approved
 * vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).
 *
 * @param string|string[] $accessibilityHazard
 *
 * @return static
 *
 * @see https://schema.org/accessibilityHazard
 */',
        'startLine' => 156,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accessibilitySummary' => 
      array (
        'name' => 'accessibilitySummary',
        'parameters' => 
        array (
          'accessibilitySummary' => 
          array (
            'name' => 'accessibilitySummary',
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
 * A human-readable summary of specific accessibility features or
 * deficiencies, consistent with the other accessibility metadata but
 * expressing subtleties such as "short descriptions are present but long
 * descriptions will be needed for non-visual users" or "short descriptions
 * are present and no long descriptions are needed".
 *
 * @param string|string[] $accessibilitySummary
 *
 * @return static
 *
 * @see https://schema.org/accessibilitySummary
 * @link https://github.com/schemaorg/schemaorg/issues/1100
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'accountablePerson' => 
      array (
        'name' => 'accountablePerson',
        'parameters' => 
        array (
          'accountablePerson' => 
          array (
            'name' => 'accountablePerson',
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
 * Specifies the Person that is legally accountable for the CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $accountablePerson
 *
 * @return static
 *
 * @see https://schema.org/accountablePerson
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'acquireLicensePage' => 
      array (
        'name' => 'acquireLicensePage',
        'parameters' => 
        array (
          'acquireLicensePage' => 
          array (
            'name' => 'acquireLicensePage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 206,
            'endLine' => 206,
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
 * Indicates a page documenting how licenses can be purchased or otherwise
 * acquired, for the current item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $acquireLicensePage
 *
 * @return static
 *
 * @see https://schema.org/acquireLicensePage
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2454
 */',
        'startLine' => 206,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 229,
            'endLine' => 229,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 244,
            'endLine' => 244,
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
        'startLine' => 244,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 258,
            'endLine' => 258,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'alternativeHeadline' => 
      array (
        'name' => 'alternativeHeadline',
        'parameters' => 
        array (
          'alternativeHeadline' => 
          array (
            'name' => 'alternativeHeadline',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 272,
            'endLine' => 272,
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
 * A secondary title of the CreativeWork.
 *
 * @param string|string[] $alternativeHeadline
 *
 * @return static
 *
 * @see https://schema.org/alternativeHeadline
 */',
        'startLine' => 272,
        'endLine' => 275,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'archivedAt' => 
      array (
        'name' => 'archivedAt',
        'parameters' => 
        array (
          'archivedAt' => 
          array (
            'name' => 'archivedAt',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 292,
            'endLine' => 292,
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
 * Indicates a page or other link involved in archival of a
 * [[CreativeWork]]. In the case of [[MediaReview]], the items in a
 * [[MediaReviewItem]] may often become inaccessible, but be archived by
 * archival, journalistic, activist, or law enforcement organizations. In
 * such cases, the referenced page may not directly publish the content.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\WebPageContract|\\Spatie\\SchemaOrg\\Contracts\\WebPageContract[]|string|string[] $archivedAt
 *
 * @return static
 *
 * @see https://schema.org/archivedAt
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'startLine' => 292,
        'endLine' => 295,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'assesses' => 
      array (
        'name' => 'assesses',
        'parameters' => 
        array (
          'assesses' => 
          array (
            'name' => 'assesses',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 309,
            'endLine' => 309,
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
 * The item being described is intended to assess the competency or learning
 * outcome defined by the referenced term.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $assesses
 *
 * @return static
 *
 * @see https://schema.org/assesses
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2427
 */',
        'startLine' => 309,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'associatedMedia' => 
      array (
        'name' => 'associatedMedia',
        'parameters' => 
        array (
          'associatedMedia' => 
          array (
            'name' => 'associatedMedia',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 324,
            'endLine' => 324,
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
 * A media object that encodes this CreativeWork. This property is a synonym
 * for encoding.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MediaObjectContract|\\Spatie\\SchemaOrg\\Contracts\\MediaObjectContract[] $associatedMedia
 *
 * @return static
 *
 * @see https://schema.org/associatedMedia
 */',
        'startLine' => 324,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 338,
            'endLine' => 338,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'audio' => 
      array (
        'name' => 'audio',
        'parameters' => 
        array (
          'audio' => 
          array (
            'name' => 'audio',
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
 * An embedded audio object.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AudioObjectContract|\\Spatie\\SchemaOrg\\Contracts\\AudioObjectContract[]|\\Spatie\\SchemaOrg\\Contracts\\ClipContract|\\Spatie\\SchemaOrg\\Contracts\\ClipContract[]|\\Spatie\\SchemaOrg\\Contracts\\MusicRecordingContract|\\Spatie\\SchemaOrg\\Contracts\\MusicRecordingContract[] $audio
 *
 * @return static
 *
 * @see https://schema.org/audio
 * @link https://github.com/schemaorg/schemaorg/issues/2420
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'author' => 
      array (
        'name' => 'author',
        'parameters' => 
        array (
          'author' => 
          array (
            'name' => 'author',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 369,
            'endLine' => 369,
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
 * The author of this content or rating. Please note that author is special
 * in that HTML 5 provides a special mechanism for indicating authorship via
 * the rel tag. That is equivalent to this and may be used interchangeably.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $author
 *
 * @return static
 *
 * @see https://schema.org/author
 */',
        'startLine' => 369,
        'endLine' => 372,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
 * An award won by or for this item.
 *
 * @param string|string[] $award
 *
 * @return static
 *
 * @see https://schema.org/award
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 397,
            'endLine' => 397,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'character' => 
      array (
        'name' => 'character',
        'parameters' => 
        array (
          'character' => 
          array (
            'name' => 'character',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 411,
            'endLine' => 411,
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
 * Fictional person connected with a creative work.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $character
 *
 * @return static
 *
 * @see https://schema.org/character
 */',
        'startLine' => 411,
        'endLine' => 414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'citation' => 
      array (
        'name' => 'citation',
        'parameters' => 
        array (
          'citation' => 
          array (
            'name' => 'citation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 426,
            'endLine' => 426,
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
 * A citation or reference to another creative work, such as another
 * publication, web page, scholarly article, etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $citation
 *
 * @return static
 *
 * @see https://schema.org/citation
 */',
        'startLine' => 426,
        'endLine' => 429,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'comment' => 
      array (
        'name' => 'comment',
        'parameters' => 
        array (
          'comment' => 
          array (
            'name' => 'comment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
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
 * Comments, typically from users.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CommentContract|\\Spatie\\SchemaOrg\\Contracts\\CommentContract[] $comment
 *
 * @return static
 *
 * @see https://schema.org/comment
 */',
        'startLine' => 440,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'commentCount' => 
      array (
        'name' => 'commentCount',
        'parameters' => 
        array (
          'commentCount' => 
          array (
            'name' => 'commentCount',
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
 * The number of comments this CreativeWork (e.g. Article, Question or
 * Answer) has received. This is most applicable to works published in Web
 * sites with commenting system; additional comments may exist elsewhere.
 *
 * @param int|int[] $commentCount
 *
 * @return static
 *
 * @see https://schema.org/commentCount
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'competencyRequired' => 
      array (
        'name' => 'competencyRequired',
        'parameters' => 
        array (
          'competencyRequired' => 
          array (
            'name' => 'competencyRequired',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 474,
            'endLine' => 474,
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
 * Knowledge, skill, ability or personal attribute that must be demonstrated
 * by a person or other entity in order to do something such as earn an
 * Educational Occupational Credential or understand a LearningResource.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $competencyRequired
 *
 * @return static
 *
 * @see https://schema.org/competencyRequired
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1779
 */',
        'startLine' => 474,
        'endLine' => 477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'conditionsOfAccess' => 
      array (
        'name' => 'conditionsOfAccess',
        'parameters' => 
        array (
          'conditionsOfAccess' => 
          array (
            'name' => 'conditionsOfAccess',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 497,
            'endLine' => 497,
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
 * Conditions that affect the availability of, or method(s) of access to, an
 * item. Typically used for real world items such as an [[ArchiveComponent]]
 * held by an [[ArchiveOrganization]]. This property is not suitable for use
 * as a general Web access control mechanism. It is expressed only in
 * natural language.
 *
 * For example "Available by appointment from the Reading Room" or
 * "Accessible only from logged-in accounts ".
 *
 * @param string|string[] $conditionsOfAccess
 *
 * @return static
 *
 * @see https://schema.org/conditionsOfAccess
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2173
 */',
        'startLine' => 497,
        'endLine' => 500,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'contentLocation' => 
      array (
        'name' => 'contentLocation',
        'parameters' => 
        array (
          'contentLocation' => 
          array (
            'name' => 'contentLocation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 512,
            'endLine' => 512,
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
 * The location depicted or described in the content. For example, the
 * location in a photograph or painting.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $contentLocation
 *
 * @return static
 *
 * @see https://schema.org/contentLocation
 */',
        'startLine' => 512,
        'endLine' => 515,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'contentRating' => 
      array (
        'name' => 'contentRating',
        'parameters' => 
        array (
          'contentRating' => 
          array (
            'name' => 'contentRating',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 526,
            'endLine' => 526,
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
 * Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\RatingContract|\\Spatie\\SchemaOrg\\Contracts\\RatingContract[]|string|string[] $contentRating
 *
 * @return static
 *
 * @see https://schema.org/contentRating
 */',
        'startLine' => 526,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'contentReferenceTime' => 
      array (
        'name' => 'contentReferenceTime',
        'parameters' => 
        array (
          'contentReferenceTime' => 
          array (
            'name' => 'contentReferenceTime',
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
 * The specific time described by a creative work, for works (e.g. articles,
 * video objects etc.) that emphasise a particular moment within an Event.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $contentReferenceTime
 *
 * @return static
 *
 * @see https://schema.org/contentReferenceTime
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1050
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 557,
            'endLine' => 557,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'copyrightHolder' => 
      array (
        'name' => 'copyrightHolder',
        'parameters' => 
        array (
          'copyrightHolder' => 
          array (
            'name' => 'copyrightHolder',
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
 * The party holding the legal copyright to the CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $copyrightHolder
 *
 * @return static
 *
 * @see https://schema.org/copyrightHolder
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'copyrightNotice' => 
      array (
        'name' => 'copyrightNotice',
        'parameters' => 
        array (
          'copyrightNotice' => 
          array (
            'name' => 'copyrightNotice',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 589,
            'endLine' => 589,
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
 * Text of a notice appropriate for describing the copyright aspects of this
 * Creative Work, ideally indicating the owner of the copyright for the
 * Work.
 *
 * @param string|string[] $copyrightNotice
 *
 * @return static
 *
 * @see https://schema.org/copyrightNotice
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2659
 */',
        'startLine' => 589,
        'endLine' => 592,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'copyrightYear' => 
      array (
        'name' => 'copyrightYear',
        'parameters' => 
        array (
          'copyrightYear' => 
          array (
            'name' => 'copyrightYear',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 604,
            'endLine' => 604,
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
 * The year during which the claimed copyright for the CreativeWork was
 * first asserted.
 *
 * @param float|float[]|int|int[] $copyrightYear
 *
 * @return static
 *
 * @see https://schema.org/copyrightYear
 */',
        'startLine' => 604,
        'endLine' => 607,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'correction' => 
      array (
        'name' => 'correction',
        'parameters' => 
        array (
          'correction' => 
          array (
            'name' => 'correction',
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
 * Indicates a correction to a [[CreativeWork]], either via a
 * [[CorrectionComment]], textually or in another document.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CorrectionCommentContract|\\Spatie\\SchemaOrg\\Contracts\\CorrectionCommentContract[]|string|string[] $correction
 *
 * @return static
 *
 * @see https://schema.org/correction
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1950
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 646,
            'endLine' => 646,
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
        'startLine' => 646,
        'endLine' => 649,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'creativeWorkStatus' => 
      array (
        'name' => 'creativeWorkStatus',
        'parameters' => 
        array (
          'creativeWorkStatus' => 
          array (
            'name' => 'creativeWorkStatus',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 665,
            'endLine' => 665,
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
 * The status of a creative work in terms of its stage in a lifecycle.
 * Example terms include Incomplete, Draft, Published, Obsolete. Some
 * organizations define a set of terms for the stages of their publication
 * lifecycle.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $creativeWorkStatus
 *
 * @return static
 *
 * @see https://schema.org/creativeWorkStatus
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/987
 */',
        'startLine' => 665,
        'endLine' => 668,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'creator' => 
      array (
        'name' => 'creator',
        'parameters' => 
        array (
          'creator' => 
          array (
            'name' => 'creator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 680,
            'endLine' => 680,
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
 * The creator/author of this CreativeWork. This is the same as the Author
 * property for CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $creator
 *
 * @return static
 *
 * @see https://schema.org/creator
 */',
        'startLine' => 680,
        'endLine' => 683,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'creditText' => 
      array (
        'name' => 'creditText',
        'parameters' => 
        array (
          'creditText' => 
          array (
            'name' => 'creditText',
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
 * Text that can be used to credit person(s) and/or organization(s)
 * associated with a published Creative Work.
 *
 * @param string|string[] $creditText
 *
 * @return static
 *
 * @see https://schema.org/creditText
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2659
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'dateCreated' => 
      array (
        'name' => 'dateCreated',
        'parameters' => 
        array (
          'dateCreated' => 
          array (
            'name' => 'dateCreated',
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
 * The date on which the CreativeWork was created or the item was added to a
 * DataFeed.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $dateCreated
 *
 * @return static
 *
 * @see https://schema.org/dateCreated
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'dateModified' => 
      array (
        'name' => 'dateModified',
        'parameters' => 
        array (
          'dateModified' => 
          array (
            'name' => 'dateModified',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 727,
            'endLine' => 727,
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
 * The date on which the CreativeWork was most recently modified or when the
 * item\'s entry was modified within a DataFeed.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $dateModified
 *
 * @return static
 *
 * @see https://schema.org/dateModified
 */',
        'startLine' => 727,
        'endLine' => 730,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'datePublished' => 
      array (
        'name' => 'datePublished',
        'parameters' => 
        array (
          'datePublished' => 
          array (
            'name' => 'datePublished',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 742,
            'endLine' => 742,
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
 * Date of first publication or broadcast. For example the date a
 * [[CreativeWork]] was broadcast or a [[Certification]] was issued.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $datePublished
 *
 * @return static
 *
 * @see https://schema.org/datePublished
 */',
        'startLine' => 742,
        'endLine' => 745,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 756,
            'endLine' => 756,
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
        'startLine' => 756,
        'endLine' => 759,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'digitalSourceType' => 
      array (
        'name' => 'digitalSourceType',
        'parameters' => 
        array (
          'digitalSourceType' => 
          array (
            'name' => 'digitalSourceType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 773,
            'endLine' => 773,
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
 * Indicates an IPTCDigitalSourceEnumeration code indicating the nature of
 * the digital source(s) for some [[CreativeWork]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\IPTCDigitalSourceEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\IPTCDigitalSourceEnumerationContract[] $digitalSourceType
 *
 * @return static
 *
 * @see https://schema.org/digitalSourceType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'startLine' => 773,
        'endLine' => 776,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 790,
            'endLine' => 790,
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
        'startLine' => 790,
        'endLine' => 793,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'discussionUrl' => 
      array (
        'name' => 'discussionUrl',
        'parameters' => 
        array (
          'discussionUrl' => 
          array (
            'name' => 'discussionUrl',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 804,
            'endLine' => 804,
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
 * A link to the page containing the comments of the CreativeWork.
 *
 * @param string|string[] $discussionUrl
 *
 * @return static
 *
 * @see https://schema.org/discussionUrl
 */',
        'startLine' => 804,
        'endLine' => 807,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'editEIDR' => 
      array (
        'name' => 'editEIDR',
        'parameters' => 
        array (
          'editEIDR' => 
          array (
            'name' => 'editEIDR',
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
 * An [EIDR](https://eidr.org/) (Entertainment Identifier Registry)
 * [[identifier]] representing a specific edit / edition for a work of film
 * or television.
 *
 * For example, the motion picture known as "Ghostbusters" whose
 * [[titleEIDR]] is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits,
 * e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and
 * "10.5240/8A35-3BEE-6497-5D12-9E4F-3".
 *
 * Since schema.org types like [[Movie]] and [[TVEpisode]] can be used for
 * both works and their multiple expressions, it is possible to use
 * [[titleEIDR]] alone (for a general description), or alongside
 * [[editEIDR]] for a more edit-specific description.
 *
 * @param string|string[] $editEIDR
 *
 * @return static
 *
 * @see https://schema.org/editEIDR
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2469
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'editor' => 
      array (
        'name' => 'editor',
        'parameters' => 
        array (
          'editor' => 
          array (
            'name' => 'editor',
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
 * Specifies the Person who edited the CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $editor
 *
 * @return static
 *
 * @see https://schema.org/editor
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'educationalAlignment' => 
      array (
        'name' => 'educationalAlignment',
        'parameters' => 
        array (
          'educationalAlignment' => 
          array (
            'name' => 'educationalAlignment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 864,
            'endLine' => 864,
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
 * An alignment to an established educational framework.
 *
 * This property should not be used where the nature of the alignment can be
 * described using a simple property, for example to express that a resource
 * [[teaches]] or [[assesses]] a competency.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AlignmentObjectContract|\\Spatie\\SchemaOrg\\Contracts\\AlignmentObjectContract[] $educationalAlignment
 *
 * @return static
 *
 * @see https://schema.org/educationalAlignment
 */',
        'startLine' => 864,
        'endLine' => 867,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'educationalLevel' => 
      array (
        'name' => 'educationalLevel',
        'parameters' => 
        array (
          'educationalLevel' => 
          array (
            'name' => 'educationalLevel',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 882,
            'endLine' => 882,
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
 * The level in terms of progression through an educational or training
 * context. Examples of educational levels include \'beginner\',
 * \'intermediate\' or \'advanced\', and formal sets of level indicators.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $educationalLevel
 *
 * @return static
 *
 * @see https://schema.org/educationalLevel
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1779
 */',
        'startLine' => 882,
        'endLine' => 885,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'educationalUse' => 
      array (
        'name' => 'educationalUse',
        'parameters' => 
        array (
          'educationalUse' => 
          array (
            'name' => 'educationalUse',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 897,
            'endLine' => 897,
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
 * The purpose of a work in the context of education; for example,
 * \'assignment\', \'group work\'.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $educationalUse
 *
 * @return static
 *
 * @see https://schema.org/educationalUse
 */',
        'startLine' => 897,
        'endLine' => 900,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'encoding' => 
      array (
        'name' => 'encoding',
        'parameters' => 
        array (
          'encoding' => 
          array (
            'name' => 'encoding',
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
 * A media object that encodes this CreativeWork. This property is a synonym
 * for associatedMedia.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MediaObjectContract|\\Spatie\\SchemaOrg\\Contracts\\MediaObjectContract[] $encoding
 *
 * @return static
 *
 * @see https://schema.org/encoding
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'encodingFormat' => 
      array (
        'name' => 'encodingFormat',
        'parameters' => 
        array (
          'encodingFormat' => 
          array (
            'name' => 'encodingFormat',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 939,
            'endLine' => 939,
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
 * Media type typically expressed using a MIME format (see [IANA
 * site](http://www.iana.org/assignments/media-types/media-types.xhtml) and
 * [MDN
 * reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)),
 * e.g. application/zip for a SoftwareApplication binary, audio/mpeg for
 * .mp3 etc.
 *
 * In cases where a [[CreativeWork]] has several media type representations,
 * [[encoding]] can be used to indicate each [[MediaObject]] alongside
 * particular [[encodingFormat]] information.
 *
 * Unregistered or niche encoding and file formats can be indicated instead
 * via the most appropriate URL, e.g. defining Web page or a
 * Wikipedia/Wikidata entry.
 *
 * @param string|string[] $encodingFormat
 *
 * @return static
 *
 * @see https://schema.org/encodingFormat
 */',
        'startLine' => 939,
        'endLine' => 942,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'encodings' => 
      array (
        'name' => 'encodings',
        'parameters' => 
        array (
          'encodings' => 
          array (
            'name' => 'encodings',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 953,
            'endLine' => 953,
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
 * A media object that encodes this CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MediaObjectContract|\\Spatie\\SchemaOrg\\Contracts\\MediaObjectContract[] $encodings
 *
 * @return static
 *
 * @see https://schema.org/encodings
 */',
        'startLine' => 953,
        'endLine' => 956,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'exampleOfWork' => 
      array (
        'name' => 'exampleOfWork',
        'parameters' => 
        array (
          'exampleOfWork' => 
          array (
            'name' => 'exampleOfWork',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 968,
            'endLine' => 968,
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
 * A creative work that this work is an
 * example/instance/realization/derivation of.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $exampleOfWork
 *
 * @return static
 *
 * @see https://schema.org/exampleOfWork
 */',
        'startLine' => 968,
        'endLine' => 971,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'expires' => 
      array (
        'name' => 'expires',
        'parameters' => 
        array (
          'expires' => 
          array (
            'name' => 'expires',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 987,
            'endLine' => 987,
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
 * Date the content expires and is no longer useful or available. For
 * example a [[VideoObject]] or [[NewsArticle]] whose availability or
 * relevance is time-limited, a [[ClaimReview]] fact check whose publisher
 * wants to indicate that it may no longer be relevant (or helpful to
 * highlight) after some date, or a [[Certification]] the validity has
 * expired.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $expires
 *
 * @return static
 *
 * @see https://schema.org/expires
 */',
        'startLine' => 987,
        'endLine' => 990,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'fileFormat' => 
      array (
        'name' => 'fileFormat',
        'parameters' => 
        array (
          'fileFormat' => 
          array (
            'name' => 'fileFormat',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1008,
            'endLine' => 1008,
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
 * Media type, typically MIME format (see [IANA
 * site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of
 * the content, e.g. application/zip of a SoftwareApplication binary. In
 * cases where a CreativeWork has several media type representations,
 * \'encoding\' can be used to indicate each MediaObject alongside particular
 * fileFormat information. Unregistered or niche file formats can be
 * indicated instead via the most appropriate URL, e.g. defining Web page or
 * a Wikipedia entry.
 *
 * @param string|string[] $fileFormat
 *
 * @return static
 *
 * @see https://schema.org/fileFormat
 */',
        'startLine' => 1008,
        'endLine' => 1011,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1023,
            'endLine' => 1023,
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
        'startLine' => 1023,
        'endLine' => 1026,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1040,
            'endLine' => 1040,
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
        'startLine' => 1040,
        'endLine' => 1043,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'genre' => 
      array (
        'name' => 'genre',
        'parameters' => 
        array (
          'genre' => 
          array (
            'name' => 'genre',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1054,
            'endLine' => 1054,
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
 * Genre of the creative work, broadcast channel or group.
 *
 * @param string|string[] $genre
 *
 * @return static
 *
 * @see https://schema.org/genre
 */',
        'startLine' => 1054,
        'endLine' => 1057,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'hasPart' => 
      array (
        'name' => 'hasPart',
        'parameters' => 
        array (
          'hasPart' => 
          array (
            'name' => 'hasPart',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1069,
            'endLine' => 1069,
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
 * Indicates an item or CreativeWork that is part of this item, or
 * CreativeWork (in some sense).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $hasPart
 *
 * @return static
 *
 * @see https://schema.org/hasPart
 */',
        'startLine' => 1069,
        'endLine' => 1072,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'headline' => 
      array (
        'name' => 'headline',
        'parameters' => 
        array (
          'headline' => 
          array (
            'name' => 'headline',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1083,
            'endLine' => 1083,
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
 * Headline of the article.
 *
 * @param string|string[] $headline
 *
 * @return static
 *
 * @see https://schema.org/headline
 */',
        'startLine' => 1083,
        'endLine' => 1086,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1101,
            'endLine' => 1101,
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
        'startLine' => 1101,
        'endLine' => 1104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1116,
            'endLine' => 1116,
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
        'startLine' => 1116,
        'endLine' => 1119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1134,
            'endLine' => 1134,
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
        'startLine' => 1134,
        'endLine' => 1137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1151,
            'endLine' => 1151,
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
        'startLine' => 1151,
        'endLine' => 1154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'interactivityType' => 
      array (
        'name' => 'interactivityType',
        'parameters' => 
        array (
          'interactivityType' => 
          array (
            'name' => 'interactivityType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1166,
            'endLine' => 1166,
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
 * The predominant mode of learning supported by the learning resource.
 * Acceptable values are \'active\', \'expositive\', or \'mixed\'.
 *
 * @param string|string[] $interactivityType
 *
 * @return static
 *
 * @see https://schema.org/interactivityType
 */',
        'startLine' => 1166,
        'endLine' => 1169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'interpretedAsClaim' => 
      array (
        'name' => 'interpretedAsClaim',
        'parameters' => 
        array (
          'interpretedAsClaim' => 
          array (
            'name' => 'interpretedAsClaim',
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
 * Used to indicate a specific claim contained, implied, translated or
 * refined from the content of a [[MediaObject]] or other [[CreativeWork]].
 * The interpreting party can be indicated using [[claimInterpreter]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ClaimContract|\\Spatie\\SchemaOrg\\Contracts\\ClaimContract[] $interpretedAsClaim
 *
 * @return static
 *
 * @see https://schema.org/interpretedAsClaim
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1198,
            'endLine' => 1198,
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
        'startLine' => 1198,
        'endLine' => 1201,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'isBasedOn' => 
      array (
        'name' => 'isBasedOn',
        'parameters' => 
        array (
          'isBasedOn' => 
          array (
            'name' => 'isBasedOn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1213,
            'endLine' => 1213,
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
 * A resource from which this work is derived or from which it is a
 * modification or adaptation.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|\\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[]|string|string[] $isBasedOn
 *
 * @return static
 *
 * @see https://schema.org/isBasedOn
 */',
        'startLine' => 1213,
        'endLine' => 1216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'isBasedOnUrl' => 
      array (
        'name' => 'isBasedOnUrl',
        'parameters' => 
        array (
          'isBasedOnUrl' => 
          array (
            'name' => 'isBasedOnUrl',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1229,
            'endLine' => 1229,
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
 * A resource that was used in the creation of this resource. This term can
 * be repeated for multiple sources. For example,
 * http://example.com/great-multiplication-intro.html.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|\\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[]|string|string[] $isBasedOnUrl
 *
 * @return static
 *
 * @see https://schema.org/isBasedOnUrl
 */',
        'startLine' => 1229,
        'endLine' => 1232,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1243,
            'endLine' => 1243,
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
        'startLine' => 1243,
        'endLine' => 1246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'isPartOf' => 
      array (
        'name' => 'isPartOf',
        'parameters' => 
        array (
          'isPartOf' => 
          array (
            'name' => 'isPartOf',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1258,
            'endLine' => 1258,
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
 * Indicates an item or CreativeWork that this item, or CreativeWork (in
 * some sense), is part of.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $isPartOf
 *
 * @return static
 *
 * @see https://schema.org/isPartOf
 */',
        'startLine' => 1258,
        'endLine' => 1261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1274,
            'endLine' => 1274,
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
        'startLine' => 1274,
        'endLine' => 1277,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'learningResourceType' => 
      array (
        'name' => 'learningResourceType',
        'parameters' => 
        array (
          'learningResourceType' => 
          array (
            'name' => 'learningResourceType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1289,
            'endLine' => 1289,
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
 * The predominant type or kind characterizing the learning resource. For
 * example, \'presentation\', \'handout\'.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $learningResourceType
 *
 * @return static
 *
 * @see https://schema.org/learningResourceType
 */',
        'startLine' => 1289,
        'endLine' => 1292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'license' => 
      array (
        'name' => 'license',
        'parameters' => 
        array (
          'license' => 
          array (
            'name' => 'license',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1304,
            'endLine' => 1304,
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
 * A license document that applies to this content, typically indicated by
 * URL.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $license
 *
 * @return static
 *
 * @see https://schema.org/license
 */',
        'startLine' => 1304,
        'endLine' => 1307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'locationCreated' => 
      array (
        'name' => 'locationCreated',
        'parameters' => 
        array (
          'locationCreated' => 
          array (
            'name' => 'locationCreated',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1319,
            'endLine' => 1319,
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
 * The location where the CreativeWork was created, which may not be the
 * same as the location depicted in the CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $locationCreated
 *
 * @return static
 *
 * @see https://schema.org/locationCreated
 */',
        'startLine' => 1319,
        'endLine' => 1322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'mainEntity' => 
      array (
        'name' => 'mainEntity',
        'parameters' => 
        array (
          'mainEntity' => 
          array (
            'name' => 'mainEntity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1334,
            'endLine' => 1334,
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
 * Indicates the primary entity described in some page or other
 * CreativeWork.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $mainEntity
 *
 * @return static
 *
 * @see https://schema.org/mainEntity
 */',
        'startLine' => 1334,
        'endLine' => 1337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1350,
            'endLine' => 1350,
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
        'startLine' => 1350,
        'endLine' => 1353,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'maintainer' => 
      array (
        'name' => 'maintainer',
        'parameters' => 
        array (
          'maintainer' => 
          array (
            'name' => 'maintainer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1378,
            'endLine' => 1378,
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
 * A maintainer of a [[Dataset]], software package
 * ([[SoftwareApplication]]), or other [[Project]]. A maintainer is a
 * [[Person]] or [[Organization]] that manages contributions to, and/or
 * publication of, some (typically complex) artifact. It is common for
 * distributions of software and data to be based on "upstream" sources.
 * When [[maintainer]] is applied to a specific version of something e.g. a
 * particular version or packaging of a [[Dataset]], it is always  possible
 * that the upstream source has a different maintainer. The [[isBasedOn]]
 * property can be used to indicate such relationships between datasets to
 * make the different maintenance roles clear. Similarly in the case of
 * software, a package may have dedicated maintainers working on integration
 * into software distributions such as Ubuntu, as well as upstream
 * maintainers of the underlying work.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $maintainer
 *
 * @return static
 *
 * @see https://schema.org/maintainer
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2311
 */',
        'startLine' => 1378,
        'endLine' => 1381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1393,
            'endLine' => 1393,
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
        'startLine' => 1393,
        'endLine' => 1396,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'materialExtent' => 
      array (
        'name' => 'materialExtent',
        'parameters' => 
        array (
          'materialExtent' => 
          array (
            'name' => 'materialExtent',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1410,
            'endLine' => 1410,
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
 * The quantity of the materials being described or an expression of the
 * physical space they occupy.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|string|string[] $materialExtent
 *
 * @return static
 *
 * @see https://schema.org/materialExtent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1759
 */',
        'startLine' => 1410,
        'endLine' => 1413,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'mentions' => 
      array (
        'name' => 'mentions',
        'parameters' => 
        array (
          'mentions' => 
          array (
            'name' => 'mentions',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1425,
            'endLine' => 1425,
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
 * Indicates that the CreativeWork contains a reference to, but is not
 * necessarily about a concept.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $mentions
 *
 * @return static
 *
 * @see https://schema.org/mentions
 */',
        'startLine' => 1425,
        'endLine' => 1428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1439,
            'endLine' => 1439,
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
        'startLine' => 1439,
        'endLine' => 1442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1461,
            'endLine' => 1461,
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
        'startLine' => 1461,
        'endLine' => 1464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1479,
            'endLine' => 1479,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'position' => 
      array (
        'name' => 'position',
        'parameters' => 
        array (
          'position' => 
          array (
            'name' => 'position',
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
 * The position of an item in a series or sequence of items.
 *
 * @param int|int[]|string|string[] $position
 *
 * @return static
 *
 * @see https://schema.org/position
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1508,
            'endLine' => 1508,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'producer' => 
      array (
        'name' => 'producer',
        'parameters' => 
        array (
          'producer' => 
          array (
            'name' => 'producer',
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
 * The person or organization who produced the work (e.g. music album,
 * movie, TV/radio series etc.).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $producer
 *
 * @return static
 *
 * @see https://schema.org/producer
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'provider' => 
      array (
        'name' => 'provider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1540,
            'endLine' => 1540,
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
 * The service provider, service operator, or service performer; the goods
 * producer. Another party (a seller) may offer those services or goods on
 * behalf of the provider. A provider may also serve as the seller.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $provider
 *
 * @return static
 *
 * @see https://schema.org/provider
 * @see https://pending.schema.org
 */',
        'startLine' => 1540,
        'endLine' => 1543,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'publication' => 
      array (
        'name' => 'publication',
        'parameters' => 
        array (
          'publication' => 
          array (
            'name' => 'publication',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1554,
            'endLine' => 1554,
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
 * A publication event associated with the item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PublicationEventContract|\\Spatie\\SchemaOrg\\Contracts\\PublicationEventContract[] $publication
 *
 * @return static
 *
 * @see https://schema.org/publication
 */',
        'startLine' => 1554,
        'endLine' => 1557,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'publisher' => 
      array (
        'name' => 'publisher',
        'parameters' => 
        array (
          'publisher' => 
          array (
            'name' => 'publisher',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1568,
            'endLine' => 1568,
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
 * The publisher of the article in question.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $publisher
 *
 * @return static
 *
 * @see https://schema.org/publisher
 */',
        'startLine' => 1568,
        'endLine' => 1571,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'publisherImprint' => 
      array (
        'name' => 'publisherImprint',
        'parameters' => 
        array (
          'publisherImprint' => 
          array (
            'name' => 'publisherImprint',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1583,
            'endLine' => 1583,
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
 * The publishing division which published the comic.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $publisherImprint
 *
 * @return static
 *
 * @see https://schema.org/publisherImprint
 * @see https://bib.schema.org
 */',
        'startLine' => 1583,
        'endLine' => 1586,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1607,
            'endLine' => 1607,
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
        'startLine' => 1607,
        'endLine' => 1610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'recordedAt' => 
      array (
        'name' => 'recordedAt',
        'parameters' => 
        array (
          'recordedAt' => 
          array (
            'name' => 'recordedAt',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1622,
            'endLine' => 1622,
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
 * The Event where the CreativeWork was recorded. The CreativeWork may
 * capture all or part of the event.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventContract|\\Spatie\\SchemaOrg\\Contracts\\EventContract[] $recordedAt
 *
 * @return static
 *
 * @see https://schema.org/recordedAt
 */',
        'startLine' => 1622,
        'endLine' => 1625,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'releasedEvent' => 
      array (
        'name' => 'releasedEvent',
        'parameters' => 
        array (
          'releasedEvent' => 
          array (
            'name' => 'releasedEvent',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1637,
            'endLine' => 1637,
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
 * The place and time the release was issued, expressed as a
 * PublicationEvent.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PublicationEventContract|\\Spatie\\SchemaOrg\\Contracts\\PublicationEventContract[] $releasedEvent
 *
 * @return static
 *
 * @see https://schema.org/releasedEvent
 */',
        'startLine' => 1637,
        'endLine' => 1640,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1651,
            'endLine' => 1651,
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
        'startLine' => 1651,
        'endLine' => 1654,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1665,
            'endLine' => 1665,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1681,
            'endLine' => 1681,
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
        'startLine' => 1681,
        'endLine' => 1684,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'schemaVersion' => 
      array (
        'name' => 'schemaVersion',
        'parameters' => 
        array (
          'schemaVersion' => 
          array (
            'name' => 'schemaVersion',
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
 * Indicates (by URL or string) a particular version of a schema used in
 * some CreativeWork. This property was created primarily to
 *     indicate the use of a specific schema.org release, e.g. ```10.0``` as
 * a simple string, or more explicitly via URL,
 * ```https://schema.org/docs/releases.html#v10.0```. There may be
 * situations in which other schemas might usefully be referenced this way,
 * e.g.
 * ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/```
 * but this has not been carefully explored in the community.
 *
 * @param string|string[] $schemaVersion
 *
 * @return static
 *
 * @see https://schema.org/schemaVersion
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'sdDatePublished' => 
      array (
        'name' => 'sdDatePublished',
        'parameters' => 
        array (
          'sdDatePublished' => 
          array (
            'name' => 'sdDatePublished',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1720,
            'endLine' => 1720,
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
 * Indicates the date on which the current structured data was generated /
 * published. Typically used alongside [[sdPublisher]].
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $sdDatePublished
 *
 * @return static
 *
 * @see https://schema.org/sdDatePublished
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1886
 */',
        'startLine' => 1720,
        'endLine' => 1723,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'sdLicense' => 
      array (
        'name' => 'sdLicense',
        'parameters' => 
        array (
          'sdLicense' => 
          array (
            'name' => 'sdLicense',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1737,
            'endLine' => 1737,
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
 * A license document that applies to this structured data, typically
 * indicated by URL.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $sdLicense
 *
 * @return static
 *
 * @see https://schema.org/sdLicense
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1886
 */',
        'startLine' => 1737,
        'endLine' => 1740,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'sdPublisher' => 
      array (
        'name' => 'sdPublisher',
        'parameters' => 
        array (
          'sdPublisher' => 
          array (
            'name' => 'sdPublisher',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1759,
            'endLine' => 1759,
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
 * Indicates the party responsible for generating and publishing the current
 * structured data markup, typically in cases where the structured data is
 * derived automatically from existing published content but published on a
 * different site. For example, student projects and open data initiatives
 * often re-publish existing content with more explicitly structured
 * metadata. The
 * [[sdPublisher]] property helps make such practices more explicit.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $sdPublisher
 *
 * @return static
 *
 * @see https://schema.org/sdPublisher
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1886
 */',
        'startLine' => 1759,
        'endLine' => 1762,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1779,
            'endLine' => 1779,
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
        'startLine' => 1779,
        'endLine' => 1782,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'sourceOrganization' => 
      array (
        'name' => 'sourceOrganization',
        'parameters' => 
        array (
          'sourceOrganization' => 
          array (
            'name' => 'sourceOrganization',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1793,
            'endLine' => 1793,
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
 * The Organization on whose behalf the creator was working.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $sourceOrganization
 *
 * @return static
 *
 * @see https://schema.org/sourceOrganization
 */',
        'startLine' => 1793,
        'endLine' => 1796,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'spatial' => 
      array (
        'name' => 'spatial',
        'parameters' => 
        array (
          'spatial' => 
          array (
            'name' => 'spatial',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1809,
            'endLine' => 1809,
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
 * The "spatial" property can be used in cases when more specific properties
 * (e.g. [[locationCreated]], [[spatialCoverage]], [[contentLocation]]) are
 * not known to be appropriate.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $spatial
 *
 * @return static
 *
 * @see https://schema.org/spatial
 */',
        'startLine' => 1809,
        'endLine' => 1812,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'spatialCoverage' => 
      array (
        'name' => 'spatialCoverage',
        'parameters' => 
        array (
          'spatialCoverage' => 
          array (
            'name' => 'spatialCoverage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1828,
            'endLine' => 1828,
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
 * The spatialCoverage of a CreativeWork indicates the place(s) which are
 * the focus of the content. It is a subproperty of
 *       contentLocation intended primarily for more technical and detailed
 * materials. For example with a Dataset, it indicates
 *       areas that the dataset describes: a dataset of New York weather
 * would have spatialCoverage which was the place: the state of New York.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $spatialCoverage
 *
 * @return static
 *
 * @see https://schema.org/spatialCoverage
 */',
        'startLine' => 1828,
        'endLine' => 1831,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1844,
            'endLine' => 1844,
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
        'startLine' => 1844,
        'endLine' => 1847,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 1859,
            'endLine' => 1859,
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
        'startLine' => 1859,
        'endLine' => 1862,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'teaches' => 
      array (
        'name' => 'teaches',
        'parameters' => 
        array (
          'teaches' => 
          array (
            'name' => 'teaches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1876,
            'endLine' => 1876,
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
 * The item being described is intended to help a person learn the
 * competency or learning outcome defined by the referenced term.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $teaches
 *
 * @return static
 *
 * @see https://schema.org/teaches
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2427
 */',
        'startLine' => 1876,
        'endLine' => 1879,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'temporal' => 
      array (
        'name' => 'temporal',
        'parameters' => 
        array (
          'temporal' => 
          array (
            'name' => 'temporal',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1893,
            'endLine' => 1893,
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
 * The "temporal" property can be used in cases where more specific
 * properties
 * (e.g. [[temporalCoverage]], [[dateCreated]], [[dateModified]],
 * [[datePublished]]) are not known to be appropriate.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[]|string|string[] $temporal
 *
 * @return static
 *
 * @see https://schema.org/temporal
 */',
        'startLine' => 1893,
        'endLine' => 1896,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'temporalCoverage' => 
      array (
        'name' => 'temporalCoverage',
        'parameters' => 
        array (
          'temporalCoverage' => 
          array (
            'name' => 'temporalCoverage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1923,
            'endLine' => 1923,
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
 * The temporalCoverage of a CreativeWork indicates the period that the
 * content applies to, i.e. that it describes, either as a DateTime or as a
 * textual string indicating a time period in [ISO 8601 time interval
 * format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In
 *       the case of a Dataset it will typically indicate the relevant time
 * period in a precise notation (e.g. for a 2011 census dataset, the year
 * 2011 would be written "2011/2012"). Other forms of content, e.g.
 * ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their
 * temporalCoverage in broader terms - textually or via well-known URL.
 *       Written works such as books may sometimes have precise temporal
 * coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601
 * interval format format via "1939/1945".
 *
 * Open-ended date ranges can be written with ".." in place of the end date.
 * For example, "2015-11/.." indicates a range beginning in November 2015
 * and with no specified final date. This is tentative and might be updated
 * in future when ISO 8601 is officially updated.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[]|string|string[] $temporalCoverage
 *
 * @return static
 *
 * @see https://schema.org/temporalCoverage
 */',
        'startLine' => 1923,
        'endLine' => 1926,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'text' => 
      array (
        'name' => 'text',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1937,
            'endLine' => 1937,
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
 * The textual content of this CreativeWork.
 *
 * @param string|string[] $text
 *
 * @return static
 *
 * @see https://schema.org/text
 */',
        'startLine' => 1937,
        'endLine' => 1940,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'thumbnail' => 
      array (
        'name' => 'thumbnail',
        'parameters' => 
        array (
          'thumbnail' => 
          array (
            'name' => 'thumbnail',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1951,
            'endLine' => 1951,
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
 * Thumbnail image for an image or video.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract|\\Spatie\\SchemaOrg\\Contracts\\ImageObjectContract[] $thumbnail
 *
 * @return static
 *
 * @see https://schema.org/thumbnail
 */',
        'startLine' => 1951,
        'endLine' => 1954,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'thumbnailUrl' => 
      array (
        'name' => 'thumbnailUrl',
        'parameters' => 
        array (
          'thumbnailUrl' => 
          array (
            'name' => 'thumbnailUrl',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1965,
            'endLine' => 1965,
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
 * A thumbnail image relevant to the Thing.
 *
 * @param string|string[] $thumbnailUrl
 *
 * @return static
 *
 * @see https://schema.org/thumbnailUrl
 */',
        'startLine' => 1965,
        'endLine' => 1968,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'timeRequired' => 
      array (
        'name' => 'timeRequired',
        'parameters' => 
        array (
          'timeRequired' => 
          array (
            'name' => 'timeRequired',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1980,
            'endLine' => 1980,
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
 * Approximate or typical time it usually takes to work with or through the
 * content of this work for the typical or target audience.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DurationContract|\\Spatie\\SchemaOrg\\Contracts\\DurationContract[] $timeRequired
 *
 * @return static
 *
 * @see https://schema.org/timeRequired
 */',
        'startLine' => 1980,
        'endLine' => 1983,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'translationOfWork' => 
      array (
        'name' => 'translationOfWork',
        'parameters' => 
        array (
          'translationOfWork' => 
          array (
            'name' => 'translationOfWork',
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
 * The work that this work has been translated from. E.g. 物种起源 is a
 * translationOf “On the Origin of Species”.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $translationOfWork
 *
 * @return static
 *
 * @see https://schema.org/translationOfWork
 * @see https://bib.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 2012,
            'endLine' => 2012,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 2026,
            'endLine' => 2026,
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
        'startLine' => 2026,
        'endLine' => 2029,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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
            'startLine' => 2040,
            'endLine' => 2040,
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
        'startLine' => 2040,
        'endLine' => 2043,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'usageInfo' => 
      array (
        'name' => 'usageInfo',
        'parameters' => 
        array (
          'usageInfo' => 
          array (
            'name' => 'usageInfo',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2068,
            'endLine' => 2068,
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
 * The schema.org [[usageInfo]] property indicates further information about
 * a [[CreativeWork]]. This property is applicable both to works that are
 * freely available and to those that require payment or other transactions.
 * It can reference additional information, e.g. community expectations on
 * preferred linking and citation conventions, as well as purchasing
 * details. For something that can be commercially licensed, usageInfo can
 * provide detailed, resource-specific information about licensing options.
 *
 * This property can be used alongside the license property which indicates
 * license(s) applicable to some piece of content. The usageInfo property
 * can provide information about other licensing options, e.g. acquiring
 * commercial usage rights for an image that is also available under
 * non-commercial creative commons licenses.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $usageInfo
 *
 * @return static
 *
 * @see https://schema.org/usageInfo
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2454
 */',
        'startLine' => 2068,
        'endLine' => 2071,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'version' => 
      array (
        'name' => 'version',
        'parameters' => 
        array (
          'version' => 
          array (
            'name' => 'version',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2082,
            'endLine' => 2082,
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
 * The version of the CreativeWork embodied by a specified resource.
 *
 * @param float|float[]|int|int[]|string|string[] $version
 *
 * @return static
 *
 * @see https://schema.org/version
 */',
        'startLine' => 2082,
        'endLine' => 2085,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'video' => 
      array (
        'name' => 'video',
        'parameters' => 
        array (
          'video' => 
          array (
            'name' => 'video',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2096,
            'endLine' => 2096,
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
 * An embedded video object.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ClipContract|\\Spatie\\SchemaOrg\\Contracts\\ClipContract[]|\\Spatie\\SchemaOrg\\Contracts\\VideoObjectContract|\\Spatie\\SchemaOrg\\Contracts\\VideoObjectContract[] $video
 *
 * @return static
 *
 * @see https://schema.org/video
 */',
        'startLine' => 2096,
        'endLine' => 2099,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'wordCount' => 
      array (
        'name' => 'wordCount',
        'parameters' => 
        array (
          'wordCount' => 
          array (
            'name' => 'wordCount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2111,
            'endLine' => 2111,
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
 * The number of words in the text of the CreativeWork such as an Article,
 * Book, etc.
 *
 * @param int|int[] $wordCount
 *
 * @return static
 *
 * @see https://schema.org/wordCount
 */',
        'startLine' => 2111,
        'endLine' => 2114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'workExample' => 
      array (
        'name' => 'workExample',
        'parameters' => 
        array (
          'workExample' => 
          array (
            'name' => 'workExample',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2126,
            'endLine' => 2126,
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
 * Example/instance/realization/derivation of the concept of this creative
 * work. E.g. the paperback edition, first edition, or e-book.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $workExample
 *
 * @return static
 *
 * @see https://schema.org/workExample
 */',
        'startLine' => 2126,
        'endLine' => 2129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'aliasName' => NULL,
      ),
      'workTranslation' => 
      array (
        'name' => 'workTranslation',
        'parameters' => 
        array (
          'workTranslation' => 
          array (
            'name' => 'workTranslation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2144,
            'endLine' => 2144,
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
 * A work that is a translation of the content of this work. E.g. 西遊記
 * has an English workTranslation “Journey to the West”, a German
 * workTranslation “Monkeys Pilgerfahrt” and a Vietnamese  translation
 * Tây du ký bình khảo.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $workTranslation
 *
 * @return static
 *
 * @see https://schema.org/workTranslation
 * @see https://bib.schema.org
 */',
        'startLine' => 2144,
        'endLine' => 2147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LearningResource',
        'currentClassName' => 'Spatie\\SchemaOrg\\LearningResource',
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