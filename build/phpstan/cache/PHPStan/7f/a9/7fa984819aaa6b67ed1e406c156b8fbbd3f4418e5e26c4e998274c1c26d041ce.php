<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/VideoGameSeries.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\VideoGameSeries
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f3f922989f22702417158044e120968e6f4d15138d60bebf0aa8ec009e0150b5-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/VideoGameSeries.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\VideoGameSeries',
    'shortName' => 'VideoGameSeries',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A video game series.
 *
 * @see https://schema.org/VideoGameSeries
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 2491,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\VideoGameSeriesContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeriesContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      4 => 'Spatie\\SchemaOrg\\Contracts\\SeriesContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 46,
            'endLine' => 46,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 64,
            'endLine' => 64,
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
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 82,
            'endLine' => 82,
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
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 98,
            'endLine' => 98,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 114,
            'endLine' => 114,
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
        'startLine' => 114,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 131,
            'endLine' => 131,
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
        'startLine' => 131,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 148,
            'endLine' => 148,
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
        'startLine' => 148,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 167,
            'endLine' => 167,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 181,
            'endLine' => 181,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 198,
            'endLine' => 198,
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
        'startLine' => 198,
        'endLine' => 201,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 214,
            'endLine' => 214,
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
        'startLine' => 214,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'actors' => 
      array (
        'name' => 'actors',
        'parameters' => 
        array (
          'actors' => 
          array (
            'name' => 'actors',
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
 * An actor, e.g. in TV, radio, movie, video games etc. Actors can be
 * associated with individual items or with a series, episode, clip.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $actors
 *
 * @return static
 *
 * @see https://schema.org/actors
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 252,
            'endLine' => 252,
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
        'startLine' => 252,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 267,
            'endLine' => 267,
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
        'startLine' => 267,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 281,
            'endLine' => 281,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 295,
            'endLine' => 295,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 315,
            'endLine' => 315,
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
        'startLine' => 315,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 332,
            'endLine' => 332,
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
        'startLine' => 332,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 347,
            'endLine' => 347,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 361,
            'endLine' => 361,
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
        'startLine' => 361,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 376,
            'endLine' => 376,
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
        'startLine' => 376,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 392,
            'endLine' => 392,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 406,
            'endLine' => 406,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 420,
            'endLine' => 420,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 434,
            'endLine' => 434,
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
        'startLine' => 434,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'characterAttribute' => 
      array (
        'name' => 'characterAttribute',
        'parameters' => 
        array (
          'characterAttribute' => 
          array (
            'name' => 'characterAttribute',
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
 * A piece of data that represents a particular aspect of a fictional
 * character (skill, power, character points, advantage, disadvantage).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $characterAttribute
 *
 * @return static
 *
 * @see https://schema.org/characterAttribute
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'cheatCode' => 
      array (
        'name' => 'cheatCode',
        'parameters' => 
        array (
          'cheatCode' => 
          array (
            'name' => 'cheatCode',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 463,
            'endLine' => 463,
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
 * Cheat codes to the game.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $cheatCode
 *
 * @return static
 *
 * @see https://schema.org/cheatCode
 */',
        'startLine' => 463,
        'endLine' => 466,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 478,
            'endLine' => 478,
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
        'startLine' => 478,
        'endLine' => 481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 492,
            'endLine' => 492,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 508,
            'endLine' => 508,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 531,
            'endLine' => 531,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'containsSeason' => 
      array (
        'name' => 'containsSeason',
        'parameters' => 
        array (
          'containsSeason' => 
          array (
            'name' => 'containsSeason',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 545,
            'endLine' => 545,
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
 * A season that is part of the media series.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeasonContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeasonContract[] $containsSeason
 *
 * @return static
 *
 * @see https://schema.org/containsSeason
 */',
        'startLine' => 545,
        'endLine' => 548,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 560,
            'endLine' => 560,
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
        'startLine' => 560,
        'endLine' => 563,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 574,
            'endLine' => 574,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 605,
            'endLine' => 605,
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
        'startLine' => 605,
        'endLine' => 608,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 619,
            'endLine' => 619,
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
        'startLine' => 619,
        'endLine' => 622,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 652,
            'endLine' => 652,
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
        'startLine' => 652,
        'endLine' => 655,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 669,
            'endLine' => 669,
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
        'startLine' => 669,
        'endLine' => 672,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 694,
            'endLine' => 694,
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
        'startLine' => 694,
        'endLine' => 697,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 713,
            'endLine' => 713,
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
        'startLine' => 713,
        'endLine' => 716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 728,
            'endLine' => 728,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 745,
            'endLine' => 745,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 760,
            'endLine' => 760,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 775,
            'endLine' => 775,
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
        'startLine' => 775,
        'endLine' => 778,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 790,
            'endLine' => 790,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 804,
            'endLine' => 804,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 821,
            'endLine' => 821,
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
        'startLine' => 821,
        'endLine' => 824,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 837,
            'endLine' => 837,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'directors' => 
      array (
        'name' => 'directors',
        'parameters' => 
        array (
          'directors' => 
          array (
            'name' => 'directors',
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
 * A director of e.g. TV, radio, movie, video games etc. content. Directors
 * can be associated with individual items or with a series, episode, clip.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $directors
 *
 * @return static
 *
 * @see https://schema.org/directors
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 869,
            'endLine' => 869,
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
        'startLine' => 869,
        'endLine' => 872,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 883,
            'endLine' => 883,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 911,
            'endLine' => 911,
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
        'startLine' => 911,
        'endLine' => 914,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 925,
            'endLine' => 925,
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
        'startLine' => 925,
        'endLine' => 928,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 943,
            'endLine' => 943,
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
        'startLine' => 943,
        'endLine' => 946,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 961,
            'endLine' => 961,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 976,
            'endLine' => 976,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 991,
            'endLine' => 991,
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
        'startLine' => 991,
        'endLine' => 994,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1018,
            'endLine' => 1018,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1032,
            'endLine' => 1032,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1048,
            'endLine' => 1048,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'episode' => 
      array (
        'name' => 'episode',
        'parameters' => 
        array (
          'episode' => 
          array (
            'name' => 'episode',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1062,
            'endLine' => 1062,
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
 * An episode of a TV, radio or game media within a series or season.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EpisodeContract|\\Spatie\\SchemaOrg\\Contracts\\EpisodeContract[] $episode
 *
 * @return static
 *
 * @see https://schema.org/episode
 */',
        'startLine' => 1062,
        'endLine' => 1065,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'episodes' => 
      array (
        'name' => 'episodes',
        'parameters' => 
        array (
          'episodes' => 
          array (
            'name' => 'episodes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1076,
            'endLine' => 1076,
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
 * An episode of a TV/radio series or season.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EpisodeContract|\\Spatie\\SchemaOrg\\Contracts\\EpisodeContract[] $episodes
 *
 * @return static
 *
 * @see https://schema.org/episodes
 */',
        'startLine' => 1076,
        'endLine' => 1079,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1091,
            'endLine' => 1091,
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
        'startLine' => 1091,
        'endLine' => 1094,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1110,
            'endLine' => 1110,
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
        'startLine' => 1110,
        'endLine' => 1113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1146,
            'endLine' => 1146,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1163,
            'endLine' => 1163,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'gameItem' => 
      array (
        'name' => 'gameItem',
        'parameters' => 
        array (
          'gameItem' => 
          array (
            'name' => 'gameItem',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1178,
            'endLine' => 1178,
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
 * An item is an object within the game world that can be collected by a
 * player or, occasionally, a non-player character.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $gameItem
 *
 * @return static
 *
 * @see https://schema.org/gameItem
 */',
        'startLine' => 1178,
        'endLine' => 1181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'gameLocation' => 
      array (
        'name' => 'gameLocation',
        'parameters' => 
        array (
          'gameLocation' => 
          array (
            'name' => 'gameLocation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1192,
            'endLine' => 1192,
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
 * Real or fictional location of the game (or part of game).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[]|\\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract|\\Spatie\\SchemaOrg\\Contracts\\PostalAddressContract[]|string|string[] $gameLocation
 *
 * @return static
 *
 * @see https://schema.org/gameLocation
 */',
        'startLine' => 1192,
        'endLine' => 1195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'gamePlatform' => 
      array (
        'name' => 'gamePlatform',
        'parameters' => 
        array (
          'gamePlatform' => 
          array (
            'name' => 'gamePlatform',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1207,
            'endLine' => 1207,
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
 * The electronic systems used to play [video
 * games](http://en.wikipedia.org/wiki/Category:Video_game_platforms).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[]|string|string[] $gamePlatform
 *
 * @return static
 *
 * @see https://schema.org/gamePlatform
 */',
        'startLine' => 1207,
        'endLine' => 1210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1221,
            'endLine' => 1221,
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
        'startLine' => 1221,
        'endLine' => 1224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
 * Indicates an item or CreativeWork that is part of this item, or
 * CreativeWork (in some sense).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[] $hasPart
 *
 * @return static
 *
 * @see https://schema.org/hasPart
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1250,
            'endLine' => 1250,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1268,
            'endLine' => 1268,
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
        'startLine' => 1268,
        'endLine' => 1271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1283,
            'endLine' => 1283,
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
        'startLine' => 1283,
        'endLine' => 1286,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1301,
            'endLine' => 1301,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1318,
            'endLine' => 1318,
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
        'startLine' => 1318,
        'endLine' => 1321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1333,
            'endLine' => 1333,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1351,
            'endLine' => 1351,
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
        'startLine' => 1351,
        'endLine' => 1354,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1365,
            'endLine' => 1365,
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
        'startLine' => 1365,
        'endLine' => 1368,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1380,
            'endLine' => 1380,
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
        'startLine' => 1380,
        'endLine' => 1383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1396,
            'endLine' => 1396,
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
        'startLine' => 1396,
        'endLine' => 1399,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1410,
            'endLine' => 1410,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
 * Indicates an item or CreativeWork that this item, or CreativeWork (in
 * some sense), is part of.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkContract[]|string|string[] $isPartOf
 *
 * @return static
 *
 * @see https://schema.org/isPartOf
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'issn' => 
      array (
        'name' => 'issn',
        'parameters' => 
        array (
          'issn' => 
          array (
            'name' => 'issn',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1441,
            'endLine' => 1441,
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
 * The International Standard Serial Number (ISSN) that identifies this
 * serial publication. You can repeat this property to identify different
 * formats of, or the linking ISSN (ISSN-L) for, this serial publication.
 *
 * @param string|string[] $issn
 *
 * @return static
 *
 * @see https://schema.org/issn
 */',
        'startLine' => 1441,
        'endLine' => 1444,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1457,
            'endLine' => 1457,
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
        'startLine' => 1457,
        'endLine' => 1460,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1472,
            'endLine' => 1472,
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
        'startLine' => 1472,
        'endLine' => 1475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1487,
            'endLine' => 1487,
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
        'startLine' => 1487,
        'endLine' => 1490,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1502,
            'endLine' => 1502,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1517,
            'endLine' => 1517,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1533,
            'endLine' => 1533,
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
        'startLine' => 1533,
        'endLine' => 1536,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1561,
            'endLine' => 1561,
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
        'startLine' => 1561,
        'endLine' => 1564,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1576,
            'endLine' => 1576,
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
        'startLine' => 1576,
        'endLine' => 1579,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1593,
            'endLine' => 1593,
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
        'startLine' => 1593,
        'endLine' => 1596,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1608,
            'endLine' => 1608,
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
        'startLine' => 1608,
        'endLine' => 1611,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'musicBy' => 
      array (
        'name' => 'musicBy',
        'parameters' => 
        array (
          'musicBy' => 
          array (
            'name' => 'musicBy',
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
 * The composer of the soundtrack.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MusicGroupContract|\\Spatie\\SchemaOrg\\Contracts\\MusicGroupContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $musicBy
 *
 * @return static
 *
 * @see https://schema.org/musicBy
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1636,
            'endLine' => 1636,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'numberOfEpisodes' => 
      array (
        'name' => 'numberOfEpisodes',
        'parameters' => 
        array (
          'numberOfEpisodes' => 
          array (
            'name' => 'numberOfEpisodes',
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
 * The number of episodes in this season or series.
 *
 * @param int|int[] $numberOfEpisodes
 *
 * @return static
 *
 * @see https://schema.org/numberOfEpisodes
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'numberOfPlayers' => 
      array (
        'name' => 'numberOfPlayers',
        'parameters' => 
        array (
          'numberOfPlayers' => 
          array (
            'name' => 'numberOfPlayers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1664,
            'endLine' => 1664,
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
 * Indicate how many people can play this game (minimum, maximum, or range).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $numberOfPlayers
 *
 * @return static
 *
 * @see https://schema.org/numberOfPlayers
 */',
        'startLine' => 1664,
        'endLine' => 1667,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'numberOfSeasons' => 
      array (
        'name' => 'numberOfSeasons',
        'parameters' => 
        array (
          'numberOfSeasons' => 
          array (
            'name' => 'numberOfSeasons',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1678,
            'endLine' => 1678,
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
 * The number of seasons in this series.
 *
 * @param int|int[] $numberOfSeasons
 *
 * @return static
 *
 * @see https://schema.org/numberOfSeasons
 */',
        'startLine' => 1678,
        'endLine' => 1681,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1700,
            'endLine' => 1700,
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
        'startLine' => 1700,
        'endLine' => 1703,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1718,
            'endLine' => 1718,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'playMode' => 
      array (
        'name' => 'playMode',
        'parameters' => 
        array (
          'playMode' => 
          array (
            'name' => 'playMode',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1734,
            'endLine' => 1734,
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
 * Indicates whether this game is multi-player, co-op or single-player.  The
 * game can be marked as multi-player, co-op and single-player at the same
 * time.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GamePlayModeContract|\\Spatie\\SchemaOrg\\Contracts\\GamePlayModeContract[] $playMode
 *
 * @return static
 *
 * @see https://schema.org/playMode
 */',
        'startLine' => 1734,
        'endLine' => 1737,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1748,
            'endLine' => 1748,
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
        'startLine' => 1748,
        'endLine' => 1751,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1763,
            'endLine' => 1763,
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
        'startLine' => 1763,
        'endLine' => 1766,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1778,
            'endLine' => 1778,
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
        'startLine' => 1778,
        'endLine' => 1781,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'productionCompany' => 
      array (
        'name' => 'productionCompany',
        'parameters' => 
        array (
          'productionCompany' => 
          array (
            'name' => 'productionCompany',
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
 * The production company or studio responsible for the item, e.g. series,
 * video game, episode etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $productionCompany
 *
 * @return static
 *
 * @see https://schema.org/productionCompany
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1810,
            'endLine' => 1810,
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
        'startLine' => 1810,
        'endLine' => 1813,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1824,
            'endLine' => 1824,
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
        'startLine' => 1824,
        'endLine' => 1827,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1838,
            'endLine' => 1838,
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
        'startLine' => 1838,
        'endLine' => 1841,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1853,
            'endLine' => 1853,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1877,
            'endLine' => 1877,
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
        'startLine' => 1877,
        'endLine' => 1880,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'quest' => 
      array (
        'name' => 'quest',
        'parameters' => 
        array (
          'quest' => 
          array (
            'name' => 'quest',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1892,
            'endLine' => 1892,
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
 * The task that a player-controlled character, or group of characters may
 * complete in order to gain a reward.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $quest
 *
 * @return static
 *
 * @see https://schema.org/quest
 */',
        'startLine' => 1892,
        'endLine' => 1895,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1907,
            'endLine' => 1907,
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
        'startLine' => 1907,
        'endLine' => 1910,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1922,
            'endLine' => 1922,
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
        'startLine' => 1922,
        'endLine' => 1925,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1936,
            'endLine' => 1936,
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
        'startLine' => 1936,
        'endLine' => 1939,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1950,
            'endLine' => 1950,
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
        'startLine' => 1950,
        'endLine' => 1953,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1966,
            'endLine' => 1966,
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
        'startLine' => 1966,
        'endLine' => 1969,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 1988,
            'endLine' => 1988,
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
        'startLine' => 1988,
        'endLine' => 1991,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2022,
            'endLine' => 2022,
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
        'startLine' => 2022,
        'endLine' => 2025,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2044,
            'endLine' => 2044,
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
        'startLine' => 2044,
        'endLine' => 2047,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'season' => 
      array (
        'name' => 'season',
        'parameters' => 
        array (
          'season' => 
          array (
            'name' => 'season',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2058,
            'endLine' => 2058,
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
 * A season in a media series.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeasonContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeasonContract[]|string|string[] $season
 *
 * @return static
 *
 * @see https://schema.org/season
 */',
        'startLine' => 2058,
        'endLine' => 2061,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'seasons' => 
      array (
        'name' => 'seasons',
        'parameters' => 
        array (
          'seasons' => 
          array (
            'name' => 'seasons',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2072,
            'endLine' => 2072,
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
 * A season in a media series.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeasonContract|\\Spatie\\SchemaOrg\\Contracts\\CreativeWorkSeasonContract[] $seasons
 *
 * @return static
 *
 * @see https://schema.org/seasons
 */',
        'startLine' => 2072,
        'endLine' => 2075,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2092,
            'endLine' => 2092,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2106,
            'endLine' => 2106,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2122,
            'endLine' => 2122,
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
        'startLine' => 2122,
        'endLine' => 2125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2141,
            'endLine' => 2141,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2157,
            'endLine' => 2157,
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
        'startLine' => 2157,
        'endLine' => 2160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2173,
            'endLine' => 2173,
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
        'startLine' => 2173,
        'endLine' => 2176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2188,
            'endLine' => 2188,
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
        'startLine' => 2188,
        'endLine' => 2191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2205,
            'endLine' => 2205,
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
        'startLine' => 2205,
        'endLine' => 2208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2222,
            'endLine' => 2222,
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
        'startLine' => 2222,
        'endLine' => 2225,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2252,
            'endLine' => 2252,
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
        'startLine' => 2252,
        'endLine' => 2255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2266,
            'endLine' => 2266,
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
        'startLine' => 2266,
        'endLine' => 2269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2280,
            'endLine' => 2280,
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
        'startLine' => 2280,
        'endLine' => 2283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2294,
            'endLine' => 2294,
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
        'startLine' => 2294,
        'endLine' => 2297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2309,
            'endLine' => 2309,
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
        'startLine' => 2309,
        'endLine' => 2312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'aliasName' => NULL,
      ),
      'trailer' => 
      array (
        'name' => 'trailer',
        'parameters' => 
        array (
          'trailer' => 
          array (
            'name' => 'trailer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2323,
            'endLine' => 2323,
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
 * The trailer of a movie or TV/radio series, season, episode, etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\VideoObjectContract|\\Spatie\\SchemaOrg\\Contracts\\VideoObjectContract[] $trailer
 *
 * @return static
 *
 * @see https://schema.org/trailer
 */',
        'startLine' => 2323,
        'endLine' => 2326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2339,
            'endLine' => 2339,
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
        'startLine' => 2339,
        'endLine' => 2342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2355,
            'endLine' => 2355,
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
        'startLine' => 2355,
        'endLine' => 2358,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2369,
            'endLine' => 2369,
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
        'startLine' => 2369,
        'endLine' => 2372,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2383,
            'endLine' => 2383,
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
        'startLine' => 2383,
        'endLine' => 2386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2411,
            'endLine' => 2411,
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
        'startLine' => 2411,
        'endLine' => 2414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2425,
            'endLine' => 2425,
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
        'startLine' => 2425,
        'endLine' => 2428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2439,
            'endLine' => 2439,
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
        'startLine' => 2439,
        'endLine' => 2442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2454,
            'endLine' => 2454,
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
        'startLine' => 2454,
        'endLine' => 2457,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2469,
            'endLine' => 2469,
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
        'startLine' => 2469,
        'endLine' => 2472,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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
            'startLine' => 2487,
            'endLine' => 2487,
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
        'startLine' => 2487,
        'endLine' => 2490,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
        'currentClassName' => 'Spatie\\SchemaOrg\\VideoGameSeries',
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