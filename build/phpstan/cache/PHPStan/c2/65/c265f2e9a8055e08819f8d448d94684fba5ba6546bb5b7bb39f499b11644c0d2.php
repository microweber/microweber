<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/StatisticalVariable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\StatisticalVariable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-41b015faf0b6df1cc9422f975237cad95d5312daa47c9dcd30565446a5fd3422-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/StatisticalVariable.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\StatisticalVariable',
    'shortName' => 'StatisticalVariable',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * [[StatisticalVariable]] represents any type of statistical metric that can be
 * measured at a place and time. The usage pattern for [[StatisticalVariable]]
 * is typically expressed using [[Observation]] with an explicit
 * [[populationType]], which is a type, typically drawn from Schema.org. Each
 * [[StatisticalVariable]] is marked as a [[ConstraintNode]], meaning that some
 * properties (those listed using [[constraintProperty]]) serve in this setting
 * solely to define the statistical variable rather than literally describe a
 * specific person, place or thing. For example, a [[StatisticalVariable]]
 * Median_Height_Person_Female representing the median height of women, could be
 * written as follows: the population type is [[Person]]; the measuredProperty
 * [[height]]; the [[statType]] [[median]]; the [[gender]] [[Female]]. It is
 * important to note that there are many kinds of scientific quantitative
 * observation which are not fully, perfectly or unambiguously described
 * following this pattern, or with solely Schema.org terminology. The approach
 * taken here is designed to allow partial, incremental or minimal description
 * of [[StatisticalVariable]]s, and the use of detailed sets of entity and
 * property IDs from external repositories. The [[measurementMethod]],
 * [[unitCode]] and [[unitText]] properties can also be used to clarify the
 * specific nature and notation of an observed measurement.
 *
 * @see https://schema.org/StatisticalVariable
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2564
 *
 * @method static populationType($populationType) The value should be instance of pending types Class|Class[]
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 37,
    'endLine' => 402,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\StatisticalVariableContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\ConstraintNodeContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
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
            'startLine' => 57,
            'endLine' => 57,
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
        'startLine' => 57,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 71,
            'endLine' => 71,
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
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'aliasName' => NULL,
      ),
      'constraintProperty' => 
      array (
        'name' => 'constraintProperty',
        'parameters' => 
        array (
          'constraintProperty' => 
          array (
            'name' => 'constraintProperty',
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
 * Indicates a property used as a constraint. For example, in the definition
 * of a [[StatisticalVariable]]. The value is a property, either from within
 * Schema.org or from other compatible (e.g. RDF) systems such as
 * DataCommons.org or Wikidata.org.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyContract[]|string|string[] $constraintProperty
 *
 * @return static
 *
 * @see https://schema.org/constraintProperty
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 104,
            'endLine' => 104,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 121,
            'endLine' => 121,
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
        'startLine' => 121,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 139,
            'endLine' => 139,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 154,
            'endLine' => 154,
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
        'startLine' => 154,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 170,
            'endLine' => 170,
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
        'startLine' => 170,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 190,
            'endLine' => 190,
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
        'startLine' => 190,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 207,
            'endLine' => 207,
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
        'startLine' => 207,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 224,
            'endLine' => 224,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 241,
            'endLine' => 241,
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
        'startLine' => 241,
        'endLine' => 244,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 284,
            'endLine' => 284,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 298,
            'endLine' => 298,
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
        'startLine' => 298,
        'endLine' => 301,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'aliasName' => NULL,
      ),
      'numConstraints' => 
      array (
        'name' => 'numConstraints',
        'parameters' => 
        array (
          'numConstraints' => 
          array (
            'name' => 'numConstraints',
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
 * Indicates the number of constraints property values defined for a
 * particular [[ConstraintNode]] such as [[StatisticalVariable]]. This helps
 * applications understand if they have access to a sufficiently complete
 * description of a [[StatisticalVariable]] or other construct that is
 * defined using properties on template-style nodes.
 *
 * @param int|int[] $numConstraints
 *
 * @return static
 *
 * @see https://schema.org/numConstraints
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2291
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
 * Indicates a potential Action, which describes an idealized action in
 * which this thing would play an \'object\' role.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ActionContract|\\Spatie\\SchemaOrg\\Contracts\\ActionContract[] $potentialAction
 *
 * @return static
 *
 * @see https://schema.org/potentialAction
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 349,
            'endLine' => 349,
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
        'startLine' => 349,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'aliasName' => NULL,
      ),
      'statType' => 
      array (
        'name' => 'statType',
        'parameters' => 
        array (
          'statType' => 
          array (
            'name' => 'statType',
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
 * Indicates the kind of statistic represented by a [[StatisticalVariable]],
 * e.g. mean, count etc. The value of statType is a property, either from
 * within Schema.org (e.g. [[median]], [[marginOfError]], [[maxValue]],
 * [[minValue]]) or from other compatible (e.g. RDF) systems such as
 * DataCommons.org or Wikidata.org.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyContract[]|string|string[] $statType
 *
 * @return static
 *
 * @see https://schema.org/statType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2564
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 384,
            'endLine' => 384,
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
        'startLine' => 384,
        'endLine' => 387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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
            'startLine' => 398,
            'endLine' => 398,
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
        'startLine' => 398,
        'endLine' => 401,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'implementingClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
        'currentClassName' => 'Spatie\\SchemaOrg\\StatisticalVariable',
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