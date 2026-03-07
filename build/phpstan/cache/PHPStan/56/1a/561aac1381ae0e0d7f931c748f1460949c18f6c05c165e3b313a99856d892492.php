<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Observation.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\Observation
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-56f3df55c96aded4ff53b67c72f9adeecac269370723b954b3c9f1637bf8d06e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\Observation',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Observation.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\Observation',
    'shortName' => 'Observation',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Instances of the class [[Observation]] are used to specify observations about
 * an entity at a particular time. The principal properties of an
 * [[Observation]] are [[observationAbout]], [[measuredProperty]], [[statType]],
 * [[value] and [[observationDate]]  and [[measuredProperty]]. Some but not all
 * Observations represent a [[QuantitativeValue]]. Quantitative observations can
 * be about a [[StatisticalVariable]], which is an abstract specification about
 * which we can make observations that are grounded at a particular location and
 * time.
 *
 * Observations can also encode a subset of simple RDF-like statements (its
 * observationAbout, a StatisticalVariable, defining the measuredPoperty; its
 * observationAbout property indicating the entity the statement is about, and
 * [[value]] )
 *
 * In the context of a quantitative knowledge graph, typical properties could
 * include [[measuredProperty]], [[observationAbout]], [[observationDate]],
 * [[value]], [[unitCode]], [[unitText]], [[measurementMethod]].
 *
 * @see https://schema.org/Observation
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 35,
    'endLine' => 548,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\ObservationContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\StructuredValueContract',
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
            'startLine' => 54,
            'endLine' => 54,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 77,
            'endLine' => 77,
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
        'startLine' => 77,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 91,
            'endLine' => 91,
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
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 105,
            'endLine' => 105,
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
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 122,
            'endLine' => 122,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 140,
            'endLine' => 140,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 155,
            'endLine' => 155,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 171,
            'endLine' => 171,
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
        'startLine' => 171,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'marginOfError' => 
      array (
        'name' => 'marginOfError',
        'parameters' => 
        array (
          'marginOfError' => 
          array (
            'name' => 'marginOfError',
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
 * A [[marginOfError]] for an [[Observation]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $marginOfError
 *
 * @return static
 *
 * @see https://schema.org/marginOfError
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'maxValue' => 
      array (
        'name' => 'maxValue',
        'parameters' => 
        array (
          'maxValue' => 
          array (
            'name' => 'maxValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 201,
            'endLine' => 201,
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
 * The upper value of some characteristic or property.
 *
 * @param float|float[]|int|int[] $maxValue
 *
 * @return static
 *
 * @see https://schema.org/maxValue
 */',
        'startLine' => 201,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'measuredProperty' => 
      array (
        'name' => 'measuredProperty',
        'parameters' => 
        array (
          'measuredProperty' => 
          array (
            'name' => 'measuredProperty',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 221,
            'endLine' => 221,
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
 * The measuredProperty of an [[Observation]], typically via its
 * [[StatisticalVariable]]. There are various kinds of applicable
 * [[Property]]: a schema.org property, a property from other RDF-compatible
 * systems, e.g. W3C RDF Data Cube, Data Commons, Wikidata, or schema.org
 * extensions such as [GS1\'s](https://www.gs1.org/voc/?show=properties).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyContract[] $measuredProperty
 *
 * @return static
 *
 * @see https://schema.org/measuredProperty
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
 */',
        'startLine' => 221,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'measurementDenominator' => 
      array (
        'name' => 'measurementDenominator',
        'parameters' => 
        array (
          'measurementDenominator' => 
          array (
            'name' => 'measurementDenominator',
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
 * Identifies the denominator variable when an observation represents a
 * ratio or percentage.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\StatisticalVariableContract|\\Spatie\\SchemaOrg\\Contracts\\StatisticalVariableContract[] $measurementDenominator
 *
 * @return static
 *
 * @see https://schema.org/measurementDenominator
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2564
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'measurementMethod' => 
      array (
        'name' => 'measurementMethod',
        'parameters' => 
        array (
          'measurementMethod' => 
          array (
            'name' => 'measurementMethod',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 255,
            'endLine' => 255,
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
 * A subproperty of [[measurementTechnique]] that can be used for specifying
 * specific methods, in particular via [[MeasurementMethodEnum]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\MeasurementMethodEnumContract|\\Spatie\\SchemaOrg\\Contracts\\MeasurementMethodEnumContract[]|string|string[] $measurementMethod
 *
 * @return static
 *
 * @see https://schema.org/measurementMethod
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1425
 */',
        'startLine' => 255,
        'endLine' => 258,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'measurementQualifier' => 
      array (
        'name' => 'measurementQualifier',
        'parameters' => 
        array (
          'measurementQualifier' => 
          array (
            'name' => 'measurementQualifier',
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
 * Provides additional qualification to an observation. For example, a GDP
 * observation measures the Nominal value.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\EnumerationContract[] $measurementQualifier
 *
 * @return static
 *
 * @see https://schema.org/measurementQualifier
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2564
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'measurementTechnique' => 
      array (
        'name' => 'measurementTechnique',
        'parameters' => 
        array (
          'measurementTechnique' => 
          array (
            'name' => 'measurementTechnique',
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
 * A technique, method or technology used in an [[Observation]],
 * [[StatisticalVariable]] or [[Dataset]] (or [[DataDownload]],
 * [[DataCatalog]]), corresponding to the method used for measuring the
 * corresponding variable(s) (for datasets, described using
 * [[variableMeasured]]; for [[Observation]], a [[StatisticalVariable]]).
 * Often but not necessarily each [[variableMeasured]] will have an explicit
 * representation as (or mapping to) an property such as those defined in
 * Schema.org, or other RDF vocabularies and "knowledge graphs". In that
 * case the subproperty of [[variableMeasured]] called [[measuredProperty]]
 * is applicable.
 *
 * The [[measurementTechnique]] property helps when extra clarification is
 * needed about how a [[measuredProperty]] was measured. This is oriented
 * towards scientific and scholarly dataset publication but may have broader
 * applicability; it is not intended as a full representation of
 * measurement, but can often serve as a high level summary for dataset
 * discovery.
 *
 * For example, if [[variableMeasured]] is: molecule concentration,
 * [[measurementTechnique]] could be: "mass spectrometry" or "nmr
 * spectroscopy" or "colorimetry" or "immunofluorescence". If the
 * [[variableMeasured]] is "depression rating", the [[measurementTechnique]]
 * could be "Zung Scale" or "HAM-D" or "Beck Depression Inventory".
 *
 * If there are several [[variableMeasured]] properties recorded for some
 * given data object, use a [[PropertyValue]] for each [[variableMeasured]]
 * and attach the corresponding [[measurementTechnique]]. The value can also
 * be from an enumeration, organized as a [[MeasurementMetholdEnumeration]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\MeasurementMethodEnumContract|\\Spatie\\SchemaOrg\\Contracts\\MeasurementMethodEnumContract[]|string|string[] $measurementTechnique
 *
 * @return static
 *
 * @see https://schema.org/measurementTechnique
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1425
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'minValue' => 
      array (
        'name' => 'minValue',
        'parameters' => 
        array (
          'minValue' => 
          array (
            'name' => 'minValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 329,
            'endLine' => 329,
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
 * The lower value of some characteristic or property.
 *
 * @param float|float[]|int|int[] $minValue
 *
 * @return static
 *
 * @see https://schema.org/minValue
 */',
        'startLine' => 329,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 343,
            'endLine' => 343,
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
        'startLine' => 343,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'observationAbout' => 
      array (
        'name' => 'observationAbout',
        'parameters' => 
        array (
          'observationAbout' => 
          array (
            'name' => 'observationAbout',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
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
 * The [[observationAbout]] property identifies an entity, often a
 * [[Place]], associated with an [[Observation]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[]|\\Spatie\\SchemaOrg\\Contracts\\ThingContract|\\Spatie\\SchemaOrg\\Contracts\\ThingContract[] $observationAbout
 *
 * @return static
 *
 * @see https://schema.org/observationAbout
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
 */',
        'startLine' => 360,
        'endLine' => 363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'observationDate' => 
      array (
        'name' => 'observationDate',
        'parameters' => 
        array (
          'observationDate' => 
          array (
            'name' => 'observationDate',
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
 * The observationDate of an [[Observation]].
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $observationDate
 *
 * @return static
 *
 * @see https://schema.org/observationDate
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'observationPeriod' => 
      array (
        'name' => 'observationPeriod',
        'parameters' => 
        array (
          'observationPeriod' => 
          array (
            'name' => 'observationPeriod',
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
 * The length of time an Observation took place over. The format follows
 * `P[0-9]*[Y|M|D|h|m|s]`. For example, P1Y is Period 1 Year, P3M is Period
 * 3 Months, P3h is Period 3 hours.
 *
 * @param string|string[] $observationPeriod
 *
 * @return static
 *
 * @see https://schema.org/observationPeriod
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2564
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 409,
            'endLine' => 409,
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
        'startLine' => 409,
        'endLine' => 412,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 425,
            'endLine' => 425,
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
        'startLine' => 425,
        'endLine' => 428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 440,
            'endLine' => 440,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'unitCode' => 
      array (
        'name' => 'unitCode',
        'parameters' => 
        array (
          'unitCode' => 
          array (
            'name' => 'unitCode',
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
 * The unit of measurement given using the UN/CEFACT Common Code (3
 * characters) or a URL. Other codes than the UN/CEFACT Common Code may be
 * used with a prefix followed by a colon.
 *
 * @param string|string[] $unitCode
 *
 * @return static
 *
 * @see https://schema.org/unitCode
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'unitText' => 
      array (
        'name' => 'unitText',
        'parameters' => 
        array (
          'unitText' => 
          array (
            'name' => 'unitText',
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
 * A string or text indicating the unit of measurement. Useful if you cannot
 * provide a standard unit code for
 * <a href=\'unitCode\'>unitCode</a>.
 *
 * @param string|string[] $unitText
 *
 * @return static
 *
 * @see https://schema.org/unitText
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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
            'startLine' => 486,
            'endLine' => 486,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'value' => 
      array (
        'name' => 'value',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
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
 * The value of a [[QuantitativeValue]] (including [[Observation]]) or
 * property value node.
 *
 * * For [[QuantitativeValue]] and [[MonetaryAmount]], the recommended type
 * for values is \'Number\'.
 * * For [[PropertyValue]], it can be \'Text\', \'Number\', \'Boolean\', or
 * \'StructuredValue\'.
 * * Use values from 0123456789 (Unicode \'DIGIT ZERO\' (U+0030) to \'DIGIT
 * NINE\' (U+0039)) rather than superficially similar Unicode symbols.
 * * Use \'.\' (Unicode \'FULL STOP\' (U+002E)) rather than \',\' to indicate a
 * decimal point. Avoid using these symbols as a readability separator.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract|\\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract[]|bool|bool[]|float|float[]|int|int[]|string|string[] $value
 *
 * @return static
 *
 * @see https://schema.org/value
 */',
        'startLine' => 510,
        'endLine' => 513,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'valueReference' => 
      array (
        'name' => 'valueReference',
        'parameters' => 
        array (
          'valueReference' => 
          array (
            'name' => 'valueReference',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
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
 * A secondary value that provides additional information on the original
 * value, e.g. a reference temperature or a type of measurement.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\EnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\EnumerationContract[]|\\Spatie\\SchemaOrg\\Contracts\\MeasurementTypeEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\MeasurementTypeEnumerationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|\\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QualitativeValueContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|\\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract|\\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract[]|string|string[] $valueReference
 *
 * @return static
 *
 * @see https://schema.org/valueReference
 */',
        'startLine' => 525,
        'endLine' => 528,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
        'aliasName' => NULL,
      ),
      'variableMeasured' => 
      array (
        'name' => 'variableMeasured',
        'parameters' => 
        array (
          'variableMeasured' => 
          array (
            'name' => 'variableMeasured',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 544,
            'endLine' => 544,
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
 * The variableMeasured property can indicate (repeated as necessary) the
 * variables that are measured in some dataset, either described as text or
 * as pairs of identifier and description using PropertyValue, or more
 * explicitly as a [[StatisticalVariable]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|\\Spatie\\SchemaOrg\\Contracts\\PropertyContract[]|\\Spatie\\SchemaOrg\\Contracts\\StatisticalVariableContract|\\Spatie\\SchemaOrg\\Contracts\\StatisticalVariableContract[]|string|string[] $variableMeasured
 *
 * @return static
 *
 * @see https://schema.org/variableMeasured
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1083
 */',
        'startLine' => 544,
        'endLine' => 547,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Observation',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Observation',
        'currentClassName' => 'Spatie\\SchemaOrg\\Observation',
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