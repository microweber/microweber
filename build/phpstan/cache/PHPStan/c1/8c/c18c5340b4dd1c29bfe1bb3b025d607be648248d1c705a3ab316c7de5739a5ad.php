<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/LocationFeatureSpecification.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\LocationFeatureSpecification
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-dec9db2e94f1f3a236212d59bb7b26673dd124d330f6f18f17915280cb379943-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/LocationFeatureSpecification.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
    'shortName' => 'LocationFeatureSpecification',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Specifies a location feature by providing a structured value representing a
 * feature of an accommodation as a property-value pair of varying degrees of
 * formality.
 *
 * @see https://schema.org/LocationFeatureSpecification
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 437,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\LocationFeatureSpecificationContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\PropertyValueContract',
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
            'startLine' => 39,
            'endLine' => 39,
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
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 53,
            'endLine' => 53,
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
        'startLine' => 53,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 67,
            'endLine' => 67,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 84,
            'endLine' => 84,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'aliasName' => NULL,
      ),
      'hoursAvailable' => 
      array (
        'name' => 'hoursAvailable',
        'parameters' => 
        array (
          'hoursAvailable' => 
          array (
            'name' => 'hoursAvailable',
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
 * The hours during which this service or contact is available.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OpeningHoursSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\OpeningHoursSpecificationContract[] $hoursAvailable
 *
 * @return static
 *
 * @see https://schema.org/hoursAvailable
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 116,
            'endLine' => 116,
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
        'startLine' => 116,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 131,
            'endLine' => 131,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 147,
            'endLine' => 147,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 161,
            'endLine' => 161,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 178,
            'endLine' => 178,
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
        'startLine' => 178,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 221,
            'endLine' => 221,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 235,
            'endLine' => 235,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 249,
            'endLine' => 249,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 264,
            'endLine' => 264,
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
        'startLine' => 264,
        'endLine' => 267,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'aliasName' => NULL,
      ),
      'propertyID' => 
      array (
        'name' => 'propertyID',
        'parameters' => 
        array (
          'propertyID' => 
          array (
            'name' => 'propertyID',
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
 * A commonly used identifier for the characteristic represented by the
 * property, e.g. a manufacturer or a standard code for a property.
 * propertyID can be
 * (1) a prefixed string, mainly meant to be used with standards for product
 * properties; (2) a site-specific, non-prefixed string (e.g. the primary
 * key of the property or the vendor-specific ID of the property), or (3)
 * a URL indicating the type of the property, either pointing to an external
 * vocabulary, or a Web resource that describes the property (e.g. a
 * glossary entry).
 * Standards bodies should promote a standard prefix for the identifiers of
 * properties from their standards.
 *
 * @param string|string[] $propertyID
 *
 * @return static
 *
 * @see https://schema.org/propertyID
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 304,
            'endLine' => 304,
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
        'startLine' => 304,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 319,
            'endLine' => 319,
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
        'startLine' => 319,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 335,
            'endLine' => 335,
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
        'startLine' => 335,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 351,
            'endLine' => 351,
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
        'startLine' => 351,
        'endLine' => 354,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 365,
            'endLine' => 365,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'aliasName' => NULL,
      ),
      'validFrom' => 
      array (
        'name' => 'validFrom',
        'parameters' => 
        array (
          'validFrom' => 
          array (
            'name' => 'validFrom',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 379,
            'endLine' => 379,
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
 * The date when the item becomes valid.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $validFrom
 *
 * @return static
 *
 * @see https://schema.org/validFrom
 */',
        'startLine' => 379,
        'endLine' => 382,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'aliasName' => NULL,
      ),
      'validThrough' => 
      array (
        'name' => 'validThrough',
        'parameters' => 
        array (
          'validThrough' => 
          array (
            'name' => 'validThrough',
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
 * The date after when the item is not valid. For example the end of an
 * offer, salary period, or a period of opening hours.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $validThrough
 *
 * @return static
 *
 * @see https://schema.org/validThrough
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 418,
            'endLine' => 418,
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
        'startLine' => 418,
        'endLine' => 421,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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
            'startLine' => 433,
            'endLine' => 433,
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
        'startLine' => 433,
        'endLine' => 436,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\LocationFeatureSpecification',
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