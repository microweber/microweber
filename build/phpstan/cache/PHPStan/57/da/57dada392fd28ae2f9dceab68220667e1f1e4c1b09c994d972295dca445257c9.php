<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/UnitPriceSpecification.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\UnitPriceSpecification
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-610768921edb2e823ed8b71ac35ab29963bc94da4a0e2d9be58802064c1fd008-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/UnitPriceSpecification.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
    'shortName' => 'UnitPriceSpecification',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The price asked for a given offer by the respective organization or person.
 *
 * @see https://schema.org/UnitPriceSpecification
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 550,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\UnitPriceSpecificationContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\PriceSpecificationContract',
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
            'startLine' => 37,
            'endLine' => 37,
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
        'startLine' => 37,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 51,
            'endLine' => 51,
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
        'startLine' => 51,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'billingDuration' => 
      array (
        'name' => 'billingDuration',
        'parameters' => 
        array (
          'billingDuration' => 
          array (
            'name' => 'billingDuration',
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
 * Specifies for how long this price (or price component) will be billed.
 * Can be used, for example, to model the contractual duration of a
 * subscription or payment plan. Type can be either a Duration or a Number
 * (in which case the unit of measurement, for example month, is specified
 * by the unitCode property).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DurationContract|\\Spatie\\SchemaOrg\\Contracts\\DurationContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $billingDuration
 *
 * @return static
 *
 * @see https://schema.org/billingDuration
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2689
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'billingIncrement' => 
      array (
        'name' => 'billingIncrement',
        'parameters' => 
        array (
          'billingIncrement' => 
          array (
            'name' => 'billingIncrement',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
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
 * This property specifies the minimal quantity and rounding increment that
 * will be the basis for the billing. The unit of measurement is specified
 * by the unitCode property.
 *
 * @param float|float[]|int|int[] $billingIncrement
 *
 * @return static
 *
 * @see https://schema.org/billingIncrement
 */',
        'startLine' => 87,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'billingStart' => 
      array (
        'name' => 'billingStart',
        'parameters' => 
        array (
          'billingStart' => 
          array (
            'name' => 'billingStart',
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
 * Specifies after how much time this price (or price component) becomes
 * valid and billing starts. Can be used, for example, to model a price
 * increase after the first year of a subscription. The unit of measurement
 * is specified by the unitCode property.
 *
 * @param float|float[]|int|int[] $billingStart
 *
 * @return static
 *
 * @see https://schema.org/billingStart
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2689
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 120,
            'endLine' => 120,
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
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 137,
            'endLine' => 137,
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
        'startLine' => 137,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'eligibleQuantity' => 
      array (
        'name' => 'eligibleQuantity',
        'parameters' => 
        array (
          'eligibleQuantity' => 
          array (
            'name' => 'eligibleQuantity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 153,
            'endLine' => 153,
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
 * The interval and unit of measurement of ordering quantities for which the
 * offer or price specification is valid. This allows e.g. specifying that a
 * certain freight charge is valid only for a certain quantity.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $eligibleQuantity
 *
 * @return static
 *
 * @see https://schema.org/eligibleQuantity
 */',
        'startLine' => 153,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'eligibleTransactionVolume' => 
      array (
        'name' => 'eligibleTransactionVolume',
        'parameters' => 
        array (
          'eligibleTransactionVolume' => 
          array (
            'name' => 'eligibleTransactionVolume',
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
 * The transaction volume, in a monetary unit, for which the offer or price
 * specification is valid, e.g. for indicating a minimal purchasing volume,
 * to express free shipping above a certain order volume, or to limit the
 * acceptance of credit cards to purchases to a certain minimal amount.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PriceSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\PriceSpecificationContract[] $eligibleTransactionVolume
 *
 * @return static
 *
 * @see https://schema.org/eligibleTransactionVolume
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 188,
            'endLine' => 188,
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
        'startLine' => 188,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 203,
            'endLine' => 203,
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
        'startLine' => 203,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 219,
            'endLine' => 219,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'maxPrice' => 
      array (
        'name' => 'maxPrice',
        'parameters' => 
        array (
          'maxPrice' => 
          array (
            'name' => 'maxPrice',
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
 * The highest price if the price is a range.
 *
 * @param float|float[]|int|int[] $maxPrice
 *
 * @return static
 *
 * @see https://schema.org/maxPrice
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'membershipPointsEarned' => 
      array (
        'name' => 'membershipPointsEarned',
        'parameters' => 
        array (
          'membershipPointsEarned' => 
          array (
            'name' => 'membershipPointsEarned',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 251,
            'endLine' => 251,
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
 * The number of membership points earned by the member. If necessary, the
 * unitText can be used to express the units the points are issued in. (E.g.
 * stars, miles, etc.)
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|float|float[]|int|int[] $membershipPointsEarned
 *
 * @return static
 *
 * @see https://schema.org/membershipPointsEarned
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2085
 */',
        'startLine' => 251,
        'endLine' => 254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'minPrice' => 
      array (
        'name' => 'minPrice',
        'parameters' => 
        array (
          'minPrice' => 
          array (
            'name' => 'minPrice',
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
 * The lowest price if the price is a range.
 *
 * @param float|float[]|int|int[] $minPrice
 *
 * @return static
 *
 * @see https://schema.org/minPrice
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 279,
            'endLine' => 279,
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
        'startLine' => 279,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 294,
            'endLine' => 294,
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
        'startLine' => 294,
        'endLine' => 297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'price' => 
      array (
        'name' => 'price',
        'parameters' => 
        array (
          'price' => 
          array (
            'name' => 'price',
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
 * The offer price of a product, or of a price component when attached to
 * PriceSpecification and its subtypes.
 *
 * Usage guidelines:
 *
 * * Use the [[priceCurrency]] property (with standard formats: [ISO 4217
 * currency format](http://en.wikipedia.org/wiki/ISO_4217), e.g. "USD";
 * [Ticker symbol](https://en.wikipedia.org/wiki/List_of_cryptocurrencies)
 * for cryptocurrencies, e.g. "BTC"; well known names for [Local Exchange
 * Trading
 * Systems](https://en.wikipedia.org/wiki/Local_exchange_trading_system)
 * (LETS) and other currency types, e.g. "Ithaca HOUR") instead of including
 * [ambiguous
 * symbols](http://en.wikipedia.org/wiki/Dollar_sign#Currencies_that_use_the_dollar_or_peso_sign)
 * such as \'$\' in the value.
 * * Use \'.\' (Unicode \'FULL STOP\' (U+002E)) rather than \',\' to indicate a
 * decimal point. Avoid using these symbols as a readability separator.
 * * Note that both
 * [RDFa](http://www.w3.org/TR/xhtml-rdfa-primer/#using-the-content-attribute)
 * and Microdata syntax allow the use of a "content=" attribute for
 * publishing simple machine-readable values alongside more human-friendly
 * formatting.
 * * Use values from 0123456789 (Unicode \'DIGIT ZERO\' (U+0030) to \'DIGIT
 * NINE\' (U+0039)) rather than superficially similar Unicode symbols.
 *
 * @param float|float[]|int|int[]|string|string[] $price
 *
 * @return static
 *
 * @see https://schema.org/price
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'priceComponentType' => 
      array (
        'name' => 'priceComponentType',
        'parameters' => 
        array (
          'priceComponentType' => 
          array (
            'name' => 'priceComponentType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
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
 * Identifies a price component (for example, a line item on an invoice),
 * part of the total price for an offer.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PriceComponentTypeEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\PriceComponentTypeEnumerationContract[] $priceComponentType
 *
 * @return static
 *
 * @see https://schema.org/priceComponentType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2689
 */',
        'startLine' => 348,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'priceCurrency' => 
      array (
        'name' => 'priceCurrency',
        'parameters' => 
        array (
          'priceCurrency' => 
          array (
            'name' => 'priceCurrency',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
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
 * The currency of the price, or a price component when attached to
 * [[PriceSpecification]] and its subtypes.
 *
 * Use standard formats: [ISO 4217 currency
 * format](http://en.wikipedia.org/wiki/ISO_4217), e.g. "USD"; [Ticker
 * symbol](https://en.wikipedia.org/wiki/List_of_cryptocurrencies) for
 * cryptocurrencies, e.g. "BTC"; well known names for [Local Exchange
 * Trading
 * Systems](https://en.wikipedia.org/wiki/Local_exchange_trading_system)
 * (LETS) and other currency types, e.g. "Ithaca HOUR".
 *
 * @param string|string[] $priceCurrency
 *
 * @return static
 *
 * @see https://schema.org/priceCurrency
 */',
        'startLine' => 371,
        'endLine' => 374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'priceType' => 
      array (
        'name' => 'priceType',
        'parameters' => 
        array (
          'priceType' => 
          array (
            'name' => 'priceType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 391,
            'endLine' => 391,
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
 * Defines the type of a price specified for an offered product, for example
 * a list price, a (temporary) sale price or a manufacturer suggested retail
 * price. If multiple prices are specified for an offer the [[priceType]]
 * property can be used to identify the type of each such specified price.
 * The value of priceType can be specified as a value from enumeration
 * PriceTypeEnumeration or as a free form text string for price types that
 * are not already predefined in PriceTypeEnumeration.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PriceTypeEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\PriceTypeEnumerationContract[]|string|string[] $priceType
 *
 * @return static
 *
 * @see https://schema.org/priceType
 */',
        'startLine' => 391,
        'endLine' => 394,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'referenceQuantity' => 
      array (
        'name' => 'referenceQuantity',
        'parameters' => 
        array (
          'referenceQuantity' => 
          array (
            'name' => 'referenceQuantity',
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
 * The reference quantity for which a certain price applies, e.g. 1 EUR per
 * 4 kWh of electricity. This property is a replacement for
 * unitOfMeasurement for the advanced cases where the price does not relate
 * to a standard unit.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[] $referenceQuantity
 *
 * @return static
 *
 * @see https://schema.org/referenceQuantity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 424,
            'endLine' => 424,
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
        'startLine' => 424,
        'endLine' => 427,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 439,
            'endLine' => 439,
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
        'startLine' => 439,
        'endLine' => 442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 455,
            'endLine' => 455,
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
        'startLine' => 455,
        'endLine' => 458,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 471,
            'endLine' => 471,
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
        'startLine' => 471,
        'endLine' => 474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 485,
            'endLine' => 485,
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
        'startLine' => 485,
        'endLine' => 488,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'validForMemberTier' => 
      array (
        'name' => 'validForMemberTier',
        'parameters' => 
        array (
          'validForMemberTier' => 
          array (
            'name' => 'validForMemberTier',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
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
 * The membership program tier an Offer (or a PriceSpecification,
 * OfferShippingDetails, or MerchantReturnPolicy under an Offer) is valid
 * for.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MemberProgramTierContract|\\Spatie\\SchemaOrg\\Contracts\\MemberProgramTierContract[] $validForMemberTier
 *
 * @return static
 *
 * @see https://schema.org/validForMemberTier
 * @see https://pending.schema.org
 */',
        'startLine' => 502,
        'endLine' => 505,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 516,
            'endLine' => 516,
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
        'startLine' => 516,
        'endLine' => 519,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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
            'startLine' => 531,
            'endLine' => 531,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'aliasName' => NULL,
      ),
      'valueAddedTaxIncluded' => 
      array (
        'name' => 'valueAddedTaxIncluded',
        'parameters' => 
        array (
          'valueAddedTaxIncluded' => 
          array (
            'name' => 'valueAddedTaxIncluded',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 546,
            'endLine' => 546,
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
 * Specifies whether the applicable value-added tax (VAT) is included in the
 * price specification or not.
 *
 * @param bool|bool[] $valueAddedTaxIncluded
 *
 * @return static
 *
 * @see https://schema.org/valueAddedTaxIncluded
 */',
        'startLine' => 546,
        'endLine' => 549,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'implementingClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
        'currentClassName' => 'Spatie\\SchemaOrg\\UnitPriceSpecification',
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