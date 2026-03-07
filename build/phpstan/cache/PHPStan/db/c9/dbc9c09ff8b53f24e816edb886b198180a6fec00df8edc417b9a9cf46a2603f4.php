<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/APIReference.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\APIReference
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-79ec388e05bed9dcf6bd916bcbf11fc06b4ee6915a192c46bdf2567dc3fa11b4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\APIReference',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/APIReference.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\APIReference',
    'shortName' => 'APIReference',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Reference documentation for application programming interfaces (APIs).
 *
 * @see https://schema.org/APIReference
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 2352,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\APIReferenceContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\ArticleContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\TechArticleContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 283,
            'endLine' => 283,
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
        'startLine' => 283,
        'endLine' => 286,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'articleBody' => 
      array (
        'name' => 'articleBody',
        'parameters' => 
        array (
          'articleBody' => 
          array (
            'name' => 'articleBody',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 297,
            'endLine' => 297,
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
 * The actual body of the article.
 *
 * @param string|string[] $articleBody
 *
 * @return static
 *
 * @see https://schema.org/articleBody
 */',
        'startLine' => 297,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'articleSection' => 
      array (
        'name' => 'articleSection',
        'parameters' => 
        array (
          'articleSection' => 
          array (
            'name' => 'articleSection',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 312,
            'endLine' => 312,
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
 * Articles may belong to one or more \'sections\' in a magazine or newspaper,
 * such as Sports, Lifestyle, etc.
 *
 * @param string|string[] $articleSection
 *
 * @return static
 *
 * @see https://schema.org/articleSection
 */',
        'startLine' => 312,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'assembly' => 
      array (
        'name' => 'assembly',
        'parameters' => 
        array (
          'assembly' => 
          array (
            'name' => 'assembly',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
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
 * Library file name, e.g., mscorlib.dll, system.web.dll.
 *
 * @param string|string[] $assembly
 *
 * @return static
 *
 * @see https://schema.org/assembly
 */',
        'startLine' => 326,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'assemblyVersion' => 
      array (
        'name' => 'assemblyVersion',
        'parameters' => 
        array (
          'assemblyVersion' => 
          array (
            'name' => 'assemblyVersion',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 340,
            'endLine' => 340,
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
 * Associated product/technology version. E.g., .NET Framework 4.5.
 *
 * @param string|string[] $assemblyVersion
 *
 * @return static
 *
 * @see https://schema.org/assemblyVersion
 */',
        'startLine' => 340,
        'endLine' => 343,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 357,
            'endLine' => 357,
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
        'startLine' => 357,
        'endLine' => 360,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 372,
            'endLine' => 372,
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
        'startLine' => 372,
        'endLine' => 375,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 386,
            'endLine' => 386,
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
        'startLine' => 386,
        'endLine' => 389,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 401,
            'endLine' => 401,
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
        'startLine' => 401,
        'endLine' => 404,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 417,
            'endLine' => 417,
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
        'startLine' => 417,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 431,
            'endLine' => 431,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 445,
            'endLine' => 445,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'backstory' => 
      array (
        'name' => 'backstory',
        'parameters' => 
        array (
          'backstory' => 
          array (
            'name' => 'backstory',
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
 * For an [[Article]], typically a [[NewsArticle]], the backstory property
 * provides a textual summary giving a brief explanation of why and how an
 * article was created. In a journalistic setting this could include
 * information about reporting process, methods, interviews, data sources,
 * etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $backstory
 *
 * @return static
 *
 * @see https://schema.org/backstory
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1688
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 479,
            'endLine' => 479,
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
        'startLine' => 479,
        'endLine' => 482,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 494,
            'endLine' => 494,
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
        'startLine' => 494,
        'endLine' => 497,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 508,
            'endLine' => 508,
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
        'startLine' => 508,
        'endLine' => 511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 524,
            'endLine' => 524,
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
        'startLine' => 524,
        'endLine' => 527,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 547,
            'endLine' => 547,
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
        'startLine' => 547,
        'endLine' => 550,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
 * The location depicted or described in the content. For example, the
 * location in a photograph or painting.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $contentLocation
 *
 * @return static
 *
 * @see https://schema.org/contentLocation
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 576,
            'endLine' => 576,
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
        'startLine' => 576,
        'endLine' => 579,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 593,
            'endLine' => 593,
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
        'startLine' => 593,
        'endLine' => 596,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 607,
            'endLine' => 607,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 621,
            'endLine' => 621,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 639,
            'endLine' => 639,
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
        'startLine' => 639,
        'endLine' => 642,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 654,
            'endLine' => 654,
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
        'startLine' => 654,
        'endLine' => 657,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 671,
            'endLine' => 671,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 696,
            'endLine' => 696,
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
        'startLine' => 696,
        'endLine' => 699,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 715,
            'endLine' => 715,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 730,
            'endLine' => 730,
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
        'startLine' => 730,
        'endLine' => 733,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 747,
            'endLine' => 747,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 762,
            'endLine' => 762,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 777,
            'endLine' => 777,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 792,
            'endLine' => 792,
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
        'startLine' => 792,
        'endLine' => 795,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'dependencies' => 
      array (
        'name' => 'dependencies',
        'parameters' => 
        array (
          'dependencies' => 
          array (
            'name' => 'dependencies',
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
 * Prerequisites needed to fulfill steps in article.
 *
 * @param string|string[] $dependencies
 *
 * @return static
 *
 * @see https://schema.org/dependencies
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 820,
            'endLine' => 820,
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
        'startLine' => 820,
        'endLine' => 823,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 837,
            'endLine' => 837,
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
        'startLine' => 837,
        'endLine' => 840,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 854,
            'endLine' => 854,
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
        'startLine' => 854,
        'endLine' => 857,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 868,
            'endLine' => 868,
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
        'startLine' => 868,
        'endLine' => 871,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 896,
            'endLine' => 896,
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
        'startLine' => 896,
        'endLine' => 899,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 910,
            'endLine' => 910,
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
        'startLine' => 910,
        'endLine' => 913,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 928,
            'endLine' => 928,
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
        'startLine' => 928,
        'endLine' => 931,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 946,
            'endLine' => 946,
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
        'startLine' => 946,
        'endLine' => 949,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 961,
            'endLine' => 961,
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
        'startLine' => 961,
        'endLine' => 964,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 976,
            'endLine' => 976,
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
        'startLine' => 976,
        'endLine' => 979,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1003,
            'endLine' => 1003,
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
        'startLine' => 1003,
        'endLine' => 1006,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1017,
            'endLine' => 1017,
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
        'startLine' => 1017,
        'endLine' => 1020,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1032,
            'endLine' => 1032,
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
        'startLine' => 1032,
        'endLine' => 1035,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'executableLibraryName' => 
      array (
        'name' => 'executableLibraryName',
        'parameters' => 
        array (
          'executableLibraryName' => 
          array (
            'name' => 'executableLibraryName',
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
 * Library file name, e.g., mscorlib.dll, system.web.dll.
 *
 * @param string|string[] $executableLibraryName
 *
 * @return static
 *
 * @see https://schema.org/executableLibraryName
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1065,
            'endLine' => 1065,
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
        'startLine' => 1065,
        'endLine' => 1068,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1086,
            'endLine' => 1086,
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
        'startLine' => 1086,
        'endLine' => 1089,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1101,
            'endLine' => 1101,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1118,
            'endLine' => 1118,
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
        'startLine' => 1118,
        'endLine' => 1121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1132,
            'endLine' => 1132,
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
        'startLine' => 1132,
        'endLine' => 1135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1147,
            'endLine' => 1147,
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
        'startLine' => 1147,
        'endLine' => 1150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1161,
            'endLine' => 1161,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1179,
            'endLine' => 1179,
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
        'startLine' => 1179,
        'endLine' => 1182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1194,
            'endLine' => 1194,
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
        'startLine' => 1194,
        'endLine' => 1197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1212,
            'endLine' => 1212,
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
        'startLine' => 1212,
        'endLine' => 1215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1229,
            'endLine' => 1229,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1244,
            'endLine' => 1244,
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
        'startLine' => 1244,
        'endLine' => 1247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1262,
            'endLine' => 1262,
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
        'startLine' => 1262,
        'endLine' => 1265,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1276,
            'endLine' => 1276,
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
        'startLine' => 1276,
        'endLine' => 1279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1291,
            'endLine' => 1291,
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
        'startLine' => 1291,
        'endLine' => 1294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1307,
            'endLine' => 1307,
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
        'startLine' => 1307,
        'endLine' => 1310,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1321,
            'endLine' => 1321,
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
        'startLine' => 1321,
        'endLine' => 1324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1336,
            'endLine' => 1336,
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
        'startLine' => 1336,
        'endLine' => 1339,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1352,
            'endLine' => 1352,
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
        'startLine' => 1352,
        'endLine' => 1355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1367,
            'endLine' => 1367,
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
        'startLine' => 1367,
        'endLine' => 1370,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
 * A license document that applies to this content, typically indicated by
 * URL.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $license
 *
 * @return static
 *
 * @see https://schema.org/license
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1397,
            'endLine' => 1397,
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
        'startLine' => 1397,
        'endLine' => 1400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1412,
            'endLine' => 1412,
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
        'startLine' => 1412,
        'endLine' => 1415,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1428,
            'endLine' => 1428,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1456,
            'endLine' => 1456,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1471,
            'endLine' => 1471,
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
        'startLine' => 1471,
        'endLine' => 1474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1488,
            'endLine' => 1488,
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
        'startLine' => 1488,
        'endLine' => 1491,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1503,
            'endLine' => 1503,
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
        'startLine' => 1503,
        'endLine' => 1506,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1517,
            'endLine' => 1517,
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
        'startLine' => 1517,
        'endLine' => 1520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1539,
            'endLine' => 1539,
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
        'startLine' => 1539,
        'endLine' => 1542,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'pageEnd' => 
      array (
        'name' => 'pageEnd',
        'parameters' => 
        array (
          'pageEnd' => 
          array (
            'name' => 'pageEnd',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1553,
            'endLine' => 1553,
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
 * The page on which the work ends; for example "138" or "xvi".
 *
 * @param int|int[]|string|string[] $pageEnd
 *
 * @return static
 *
 * @see https://schema.org/pageEnd
 */',
        'startLine' => 1553,
        'endLine' => 1556,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'pageStart' => 
      array (
        'name' => 'pageStart',
        'parameters' => 
        array (
          'pageStart' => 
          array (
            'name' => 'pageStart',
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
 * The page on which the work starts; for example "135" or "xiii".
 *
 * @param int|int[]|string|string[] $pageStart
 *
 * @return static
 *
 * @see https://schema.org/pageStart
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'pagination' => 
      array (
        'name' => 'pagination',
        'parameters' => 
        array (
          'pagination' => 
          array (
            'name' => 'pagination',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1582,
            'endLine' => 1582,
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
 * Any description of pages that is not separated into pageStart and
 * pageEnd; for example, "1-6, 9, 55" or "10-12, 46-49".
 *
 * @param string|string[] $pagination
 *
 * @return static
 *
 * @see https://schema.org/pagination
 */',
        'startLine' => 1582,
        'endLine' => 1585,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1600,
            'endLine' => 1600,
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
        'startLine' => 1600,
        'endLine' => 1603,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1614,
            'endLine' => 1614,
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
        'startLine' => 1614,
        'endLine' => 1617,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1629,
            'endLine' => 1629,
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
        'startLine' => 1629,
        'endLine' => 1632,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1644,
            'endLine' => 1644,
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
        'startLine' => 1644,
        'endLine' => 1647,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'proficiencyLevel' => 
      array (
        'name' => 'proficiencyLevel',
        'parameters' => 
        array (
          'proficiencyLevel' => 
          array (
            'name' => 'proficiencyLevel',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1659,
            'endLine' => 1659,
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
 * Proficiency needed for this content; expected values: \'Beginner\',
 * \'Expert\'.
 *
 * @param string|string[] $proficiencyLevel
 *
 * @return static
 *
 * @see https://schema.org/proficiencyLevel
 */',
        'startLine' => 1659,
        'endLine' => 1662,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'programmingModel' => 
      array (
        'name' => 'programmingModel',
        'parameters' => 
        array (
          'programmingModel' => 
          array (
            'name' => 'programmingModel',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1673,
            'endLine' => 1673,
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
 * Indicates whether API is managed or unmanaged.
 *
 * @param string|string[] $programmingModel
 *
 * @return static
 *
 * @see https://schema.org/programmingModel
 */',
        'startLine' => 1673,
        'endLine' => 1676,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1690,
            'endLine' => 1690,
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
        'startLine' => 1690,
        'endLine' => 1693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1704,
            'endLine' => 1704,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1718,
            'endLine' => 1718,
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
        'startLine' => 1718,
        'endLine' => 1721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1733,
            'endLine' => 1733,
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
        'startLine' => 1733,
        'endLine' => 1736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1757,
            'endLine' => 1757,
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
        'startLine' => 1757,
        'endLine' => 1760,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1772,
            'endLine' => 1772,
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
        'startLine' => 1772,
        'endLine' => 1775,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1787,
            'endLine' => 1787,
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
        'startLine' => 1787,
        'endLine' => 1790,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1801,
            'endLine' => 1801,
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
        'startLine' => 1801,
        'endLine' => 1804,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1815,
            'endLine' => 1815,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1831,
            'endLine' => 1831,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1853,
            'endLine' => 1853,
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
        'startLine' => 1853,
        'endLine' => 1856,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1870,
            'endLine' => 1870,
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
        'startLine' => 1870,
        'endLine' => 1873,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1887,
            'endLine' => 1887,
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
        'startLine' => 1887,
        'endLine' => 1890,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1909,
            'endLine' => 1909,
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
        'startLine' => 1909,
        'endLine' => 1912,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1929,
            'endLine' => 1929,
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
        'startLine' => 1929,
        'endLine' => 1932,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1943,
            'endLine' => 1943,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1959,
            'endLine' => 1959,
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
        'startLine' => 1959,
        'endLine' => 1962,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 1978,
            'endLine' => 1978,
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
        'startLine' => 1978,
        'endLine' => 1981,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'speakable' => 
      array (
        'name' => 'speakable',
        'parameters' => 
        array (
          'speakable' => 
          array (
            'name' => 'speakable',
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
 * Indicates sections of a Web page that are particularly \'speakable\' in the
 * sense of being highlighted as being especially appropriate for
 * text-to-speech conversion. Other sections of a page may also be usefully
 * spoken in particular circumstances; the \'speakable\' property serves to
 * indicate the parts most likely to be generally useful for speech.
 *
 * The *speakable* property can be repeated an arbitrary number of times,
 * with three kinds of possible \'content-locator\' values:
 *
 * 1.) *id-value* URL references - uses *id-value* of an element in the page
 * being annotated. The simplest use of *speakable* has (potentially
 * relative) URL values, referencing identified sections of the document
 * concerned.
 *
 * 2.) CSS Selectors - addresses content in the annotated page, e.g. via
 * class attribute. Use the [[cssSelector]] property.
 *
 * 3.)  XPaths - addresses content via XPaths (assuming an XML view of the
 * content). Use the [[xpath]] property.
 *
 *
 * For more sophisticated markup of speakable sections beyond simple ID
 * references, either CSS selectors or XPath expressions to pick out
 * document section(s) as speakable. For this
 * we define a supporting type, [[SpeakableSpecification]]  which is defined
 * to be a possible value of the *speakable* property.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\SpeakableSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\SpeakableSpecificationContract[]|string|string[] $speakable
 *
 * @return static
 *
 * @see https://schema.org/speakable
 * @link https://github.com/schemaorg/schemaorg/issues/1389
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2034,
            'endLine' => 2034,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2049,
            'endLine' => 2049,
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
        'startLine' => 2049,
        'endLine' => 2052,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'aliasName' => NULL,
      ),
      'targetPlatform' => 
      array (
        'name' => 'targetPlatform',
        'parameters' => 
        array (
          'targetPlatform' => 
          array (
            'name' => 'targetPlatform',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2063,
            'endLine' => 2063,
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
 * Type of app development: phone, Metro style, desktop, XBox, etc.
 *
 * @param string|string[] $targetPlatform
 *
 * @return static
 *
 * @see https://schema.org/targetPlatform
 */',
        'startLine' => 2063,
        'endLine' => 2066,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2080,
            'endLine' => 2080,
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
        'startLine' => 2080,
        'endLine' => 2083,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2097,
            'endLine' => 2097,
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
        'startLine' => 2097,
        'endLine' => 2100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2127,
            'endLine' => 2127,
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
        'startLine' => 2127,
        'endLine' => 2130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2141,
            'endLine' => 2141,
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
        'startLine' => 2141,
        'endLine' => 2144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2155,
            'endLine' => 2155,
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
        'startLine' => 2155,
        'endLine' => 2158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2169,
            'endLine' => 2169,
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
        'startLine' => 2169,
        'endLine' => 2172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2184,
            'endLine' => 2184,
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
        'startLine' => 2184,
        'endLine' => 2187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2200,
            'endLine' => 2200,
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
        'startLine' => 2200,
        'endLine' => 2203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2216,
            'endLine' => 2216,
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
        'startLine' => 2216,
        'endLine' => 2219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2230,
            'endLine' => 2230,
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
        'startLine' => 2230,
        'endLine' => 2233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2244,
            'endLine' => 2244,
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
        'startLine' => 2244,
        'endLine' => 2247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2272,
            'endLine' => 2272,
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
        'startLine' => 2272,
        'endLine' => 2275,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2286,
            'endLine' => 2286,
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
        'startLine' => 2286,
        'endLine' => 2289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2300,
            'endLine' => 2300,
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
        'startLine' => 2300,
        'endLine' => 2303,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2315,
            'endLine' => 2315,
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
        'startLine' => 2315,
        'endLine' => 2318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2330,
            'endLine' => 2330,
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
        'startLine' => 2330,
        'endLine' => 2333,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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
            'startLine' => 2348,
            'endLine' => 2348,
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
        'startLine' => 2348,
        'endLine' => 2351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'implementingClassName' => 'Spatie\\SchemaOrg\\APIReference',
        'currentClassName' => 'Spatie\\SchemaOrg\\APIReference',
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