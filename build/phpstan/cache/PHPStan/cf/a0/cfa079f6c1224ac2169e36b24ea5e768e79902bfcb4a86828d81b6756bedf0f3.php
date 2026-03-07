<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Answer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\Answer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-edff04d1e2fd300240a2c49fada35b8339b5f36a96f1443a62563f17acb387ae-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\Answer',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Answer.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\Answer',
    'shortName' => 'Answer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An answer offered to a question; perhaps correct, perhaps opinionated or
 * wrong.
 *
 * @see https://schema.org/Answer
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 2201,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\AnswerContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\CommentContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract',
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
            'startLine' => 29,
            'endLine' => 29,
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
        'startLine' => 29,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 45,
            'endLine' => 45,
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
        'startLine' => 45,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 63,
            'endLine' => 63,
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
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 81,
            'endLine' => 81,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 97,
            'endLine' => 97,
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
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 113,
            'endLine' => 113,
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
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 130,
            'endLine' => 130,
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
        'startLine' => 130,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 147,
            'endLine' => 147,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 166,
            'endLine' => 166,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 180,
            'endLine' => 180,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 197,
            'endLine' => 197,
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
        'startLine' => 197,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 220,
            'endLine' => 220,
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
        'startLine' => 220,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 235,
            'endLine' => 235,
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
        'startLine' => 235,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 249,
            'endLine' => 249,
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
        'startLine' => 249,
        'endLine' => 252,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 263,
            'endLine' => 263,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
        'aliasName' => NULL,
      ),
      'answerExplanation' => 
      array (
        'name' => 'answerExplanation',
        'parameters' => 
        array (
          'answerExplanation' => 
          array (
            'name' => 'answerExplanation',
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
 * A step-by-step or full explanation about Answer. Can outline how this
 * Answer was achieved or contain more broad clarification or statement
 * about it.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CommentContract|\\Spatie\\SchemaOrg\\Contracts\\CommentContract[]|\\Spatie\\SchemaOrg\\Contracts\\WebContentContract|\\Spatie\\SchemaOrg\\Contracts\\WebContentContract[] $answerExplanation
 *
 * @return static
 *
 * @see https://schema.org/answerExplanation
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2636
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 301,
            'endLine' => 301,
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
        'startLine' => 301,
        'endLine' => 304,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 318,
            'endLine' => 318,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 333,
            'endLine' => 333,
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
        'startLine' => 333,
        'endLine' => 336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 347,
            'endLine' => 347,
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
        'startLine' => 347,
        'endLine' => 350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 362,
            'endLine' => 362,
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
        'startLine' => 362,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 378,
            'endLine' => 378,
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
        'startLine' => 378,
        'endLine' => 381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 392,
            'endLine' => 392,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 406,
            'endLine' => 406,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 420,
            'endLine' => 420,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 435,
            'endLine' => 435,
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
        'startLine' => 435,
        'endLine' => 438,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 449,
            'endLine' => 449,
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
        'startLine' => 449,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 465,
            'endLine' => 465,
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
        'startLine' => 465,
        'endLine' => 468,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 488,
            'endLine' => 488,
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
        'startLine' => 488,
        'endLine' => 491,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
 * The location depicted or described in the content. For example, the
 * location in a photograph or painting.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $contentLocation
 *
 * @return static
 *
 * @see https://schema.org/contentLocation
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 517,
            'endLine' => 517,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 534,
            'endLine' => 534,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 548,
            'endLine' => 548,
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
        'startLine' => 548,
        'endLine' => 551,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 562,
            'endLine' => 562,
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
        'startLine' => 562,
        'endLine' => 565,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 580,
            'endLine' => 580,
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
        'startLine' => 580,
        'endLine' => 583,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 595,
            'endLine' => 595,
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
        'startLine' => 595,
        'endLine' => 598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 612,
            'endLine' => 612,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 637,
            'endLine' => 637,
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
        'startLine' => 637,
        'endLine' => 640,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 656,
            'endLine' => 656,
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
        'startLine' => 656,
        'endLine' => 659,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 671,
            'endLine' => 671,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 688,
            'endLine' => 688,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 703,
            'endLine' => 703,
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
        'startLine' => 703,
        'endLine' => 706,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 718,
            'endLine' => 718,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 733,
            'endLine' => 733,
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
        'startLine' => 733,
        'endLine' => 736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 747,
            'endLine' => 747,
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
        'startLine' => 747,
        'endLine' => 750,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 764,
            'endLine' => 764,
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
        'startLine' => 764,
        'endLine' => 767,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 781,
            'endLine' => 781,
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
        'startLine' => 781,
        'endLine' => 784,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 795,
            'endLine' => 795,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
        'aliasName' => NULL,
      ),
      'downvoteCount' => 
      array (
        'name' => 'downvoteCount',
        'parameters' => 
        array (
          'downvoteCount' => 
          array (
            'name' => 'downvoteCount',
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
 * The number of downvotes this question, answer or comment has received
 * from the community.
 *
 * @param int|int[] $downvoteCount
 *
 * @return static
 *
 * @see https://schema.org/downvoteCount
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 838,
            'endLine' => 838,
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
        'startLine' => 838,
        'endLine' => 841,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 852,
            'endLine' => 852,
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
        'startLine' => 852,
        'endLine' => 855,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 870,
            'endLine' => 870,
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
        'startLine' => 870,
        'endLine' => 873,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 888,
            'endLine' => 888,
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
        'startLine' => 888,
        'endLine' => 891,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 903,
            'endLine' => 903,
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
        'startLine' => 903,
        'endLine' => 906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 918,
            'endLine' => 918,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 945,
            'endLine' => 945,
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
        'startLine' => 945,
        'endLine' => 948,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 959,
            'endLine' => 959,
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
        'startLine' => 959,
        'endLine' => 962,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
 * A creative work that this work is an
 * example/instance/realization/derivation of.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $exampleOfWork
 *
 * @return static
 *
 * @see https://schema.org/exampleOfWork
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 993,
            'endLine' => 993,
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
        'startLine' => 993,
        'endLine' => 996,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1014,
            'endLine' => 1014,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1029,
            'endLine' => 1029,
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
        'startLine' => 1029,
        'endLine' => 1032,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1046,
            'endLine' => 1046,
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
        'startLine' => 1046,
        'endLine' => 1049,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1060,
            'endLine' => 1060,
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
        'startLine' => 1060,
        'endLine' => 1063,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1075,
            'endLine' => 1075,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1089,
            'endLine' => 1089,
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
        'startLine' => 1089,
        'endLine' => 1092,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1107,
            'endLine' => 1107,
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
        'startLine' => 1107,
        'endLine' => 1110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1122,
            'endLine' => 1122,
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
        'startLine' => 1122,
        'endLine' => 1125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1140,
            'endLine' => 1140,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1157,
            'endLine' => 1157,
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
        'startLine' => 1157,
        'endLine' => 1160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1172,
            'endLine' => 1172,
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
        'startLine' => 1172,
        'endLine' => 1175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1190,
            'endLine' => 1190,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1204,
            'endLine' => 1204,
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
        'startLine' => 1204,
        'endLine' => 1207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1219,
            'endLine' => 1219,
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
        'startLine' => 1219,
        'endLine' => 1222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1235,
            'endLine' => 1235,
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
        'startLine' => 1235,
        'endLine' => 1238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1249,
            'endLine' => 1249,
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
        'startLine' => 1249,
        'endLine' => 1252,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1264,
            'endLine' => 1264,
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
        'startLine' => 1264,
        'endLine' => 1267,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1280,
            'endLine' => 1280,
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
        'startLine' => 1280,
        'endLine' => 1283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1295,
            'endLine' => 1295,
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
        'startLine' => 1295,
        'endLine' => 1298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1310,
            'endLine' => 1310,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1325,
            'endLine' => 1325,
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
        'startLine' => 1325,
        'endLine' => 1328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1340,
            'endLine' => 1340,
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
        'startLine' => 1340,
        'endLine' => 1343,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1356,
            'endLine' => 1356,
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
        'startLine' => 1356,
        'endLine' => 1359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1384,
            'endLine' => 1384,
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
        'startLine' => 1384,
        'endLine' => 1387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1399,
            'endLine' => 1399,
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
        'startLine' => 1399,
        'endLine' => 1402,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1416,
            'endLine' => 1416,
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
        'startLine' => 1416,
        'endLine' => 1419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1431,
            'endLine' => 1431,
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
        'startLine' => 1431,
        'endLine' => 1434,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1445,
            'endLine' => 1445,
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
        'startLine' => 1445,
        'endLine' => 1448,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1467,
            'endLine' => 1467,
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
        'startLine' => 1467,
        'endLine' => 1470,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
        'aliasName' => NULL,
      ),
      'parentItem' => 
      array (
        'name' => 'parentItem',
        'parameters' => 
        array (
          'parentItem' => 
          array (
            'name' => 'parentItem',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1484,
            'endLine' => 1484,
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
 * The parent of a question, answer or item in general. Typically used for
 * Q/A discussion threads e.g. a chain of comments with the first comment
 * being an [[Article]] or other [[CreativeWork]]. See also [[comment]]
 * which points from something to a comment about it.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CommentContract|\\Spatie\\SchemaOrg\\Contracts\\CommentContract[]|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $parentItem
 *
 * @return static
 *
 * @see https://schema.org/parentItem
 */',
        'startLine' => 1484,
        'endLine' => 1487,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1502,
            'endLine' => 1502,
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
        'startLine' => 1502,
        'endLine' => 1505,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1516,
            'endLine' => 1516,
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
        'startLine' => 1516,
        'endLine' => 1519,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1531,
            'endLine' => 1531,
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
        'startLine' => 1531,
        'endLine' => 1534,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1546,
            'endLine' => 1546,
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
        'startLine' => 1546,
        'endLine' => 1549,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1563,
            'endLine' => 1563,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1577,
            'endLine' => 1577,
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
        'startLine' => 1577,
        'endLine' => 1580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1591,
            'endLine' => 1591,
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
        'startLine' => 1591,
        'endLine' => 1594,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1606,
            'endLine' => 1606,
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
        'startLine' => 1606,
        'endLine' => 1609,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1630,
            'endLine' => 1630,
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
        'startLine' => 1630,
        'endLine' => 1633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1645,
            'endLine' => 1645,
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
        'startLine' => 1645,
        'endLine' => 1648,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1660,
            'endLine' => 1660,
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
        'startLine' => 1660,
        'endLine' => 1663,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1674,
            'endLine' => 1674,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1688,
            'endLine' => 1688,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1704,
            'endLine' => 1704,
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
        'startLine' => 1704,
        'endLine' => 1707,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1726,
            'endLine' => 1726,
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
        'startLine' => 1726,
        'endLine' => 1729,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1743,
            'endLine' => 1743,
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
        'startLine' => 1743,
        'endLine' => 1746,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1760,
            'endLine' => 1760,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1782,
            'endLine' => 1782,
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
        'startLine' => 1782,
        'endLine' => 1785,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
        'aliasName' => NULL,
      ),
      'sharedContent' => 
      array (
        'name' => 'sharedContent',
        'parameters' => 
        array (
          'sharedContent' => 
          array (
            'name' => 'sharedContent',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1797,
            'endLine' => 1797,
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
 * A CreativeWork such as an image, video, or audio clip shared as part of
 * this posting.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $sharedContent
 *
 * @return static
 *
 * @see https://schema.org/sharedContent
 */',
        'startLine' => 1797,
        'endLine' => 1800,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1817,
            'endLine' => 1817,
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
        'startLine' => 1817,
        'endLine' => 1820,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1831,
            'endLine' => 1831,
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
        'startLine' => 1831,
        'endLine' => 1834,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1847,
            'endLine' => 1847,
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
        'startLine' => 1847,
        'endLine' => 1850,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1866,
            'endLine' => 1866,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1882,
            'endLine' => 1882,
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
        'startLine' => 1882,
        'endLine' => 1885,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1897,
            'endLine' => 1897,
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
        'startLine' => 1897,
        'endLine' => 1900,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1914,
            'endLine' => 1914,
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
        'startLine' => 1914,
        'endLine' => 1917,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1931,
            'endLine' => 1931,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1961,
            'endLine' => 1961,
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
        'startLine' => 1961,
        'endLine' => 1964,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1975,
            'endLine' => 1975,
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
        'startLine' => 1975,
        'endLine' => 1978,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 1989,
            'endLine' => 1989,
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
        'startLine' => 1989,
        'endLine' => 1992,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2003,
            'endLine' => 2003,
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
        'startLine' => 2003,
        'endLine' => 2006,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2018,
            'endLine' => 2018,
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
        'startLine' => 2018,
        'endLine' => 2021,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2034,
            'endLine' => 2034,
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
        'startLine' => 2034,
        'endLine' => 2037,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2050,
            'endLine' => 2050,
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
        'startLine' => 2050,
        'endLine' => 2053,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2064,
            'endLine' => 2064,
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
        'startLine' => 2064,
        'endLine' => 2067,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
        'aliasName' => NULL,
      ),
      'upvoteCount' => 
      array (
        'name' => 'upvoteCount',
        'parameters' => 
        array (
          'upvoteCount' => 
          array (
            'name' => 'upvoteCount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2079,
            'endLine' => 2079,
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
 * The number of upvotes this question, answer or comment has received from
 * the community.
 *
 * @param int|int[] $upvoteCount
 *
 * @return static
 *
 * @see https://schema.org/upvoteCount
 */',
        'startLine' => 2079,
        'endLine' => 2082,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2093,
            'endLine' => 2093,
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
        'startLine' => 2093,
        'endLine' => 2096,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2121,
            'endLine' => 2121,
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
        'startLine' => 2121,
        'endLine' => 2124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2135,
            'endLine' => 2135,
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
        'startLine' => 2135,
        'endLine' => 2138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2149,
            'endLine' => 2149,
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
        'startLine' => 2149,
        'endLine' => 2152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2164,
            'endLine' => 2164,
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
        'startLine' => 2164,
        'endLine' => 2167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2179,
            'endLine' => 2179,
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
        'startLine' => 2179,
        'endLine' => 2182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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
            'startLine' => 2197,
            'endLine' => 2197,
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
        'startLine' => 2197,
        'endLine' => 2200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Answer',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Answer',
        'currentClassName' => 'Spatie\\SchemaOrg\\Answer',
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