<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MerchantReturnPolicySeasonalOverride.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\MerchantReturnPolicySeasonalOverride
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-24125d7c3f94c35260eeaf668d0ad271fbfdcd7e4b464ce07edea56232402d1f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MerchantReturnPolicySeasonalOverride.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
    'shortName' => 'MerchantReturnPolicySeasonalOverride',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A seasonal override of a return policy, for example used for holidays.
 *
 * @see https://schema.org/MerchantReturnPolicySeasonalOverride
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 359,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\MerchantReturnPolicySeasonalOverrideContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 65,
            'endLine' => 65,
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
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 82,
            'endLine' => 82,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 98,
            'endLine' => 98,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'merchantReturnDays' => 
      array (
        'name' => 'merchantReturnDays',
        'parameters' => 
        array (
          'merchantReturnDays' => 
          array (
            'name' => 'merchantReturnDays',
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
 * Specifies either a fixed return date or the number of days (from the
 * delivery date) that a product can be returned. Used when the
 * [[returnPolicyCategory]] property is specified as
 * [[MerchantReturnFiniteReturnWindow]].
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[]|int|int[] $merchantReturnDays
 *
 * @return static
 *
 * @see https://schema.org/merchantReturnDays
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 180,
            'endLine' => 180,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 195,
            'endLine' => 195,
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
        'startLine' => 195,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'refundType' => 
      array (
        'name' => 'refundType',
        'parameters' => 
        array (
          'refundType' => 
          array (
            'name' => 'refundType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 211,
            'endLine' => 211,
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
 * A refund type, from an enumerated list.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\RefundTypeEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\RefundTypeEnumerationContract[] $refundType
 *
 * @return static
 *
 * @see https://schema.org/refundType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 211,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'restockingFee' => 
      array (
        'name' => 'restockingFee',
        'parameters' => 
        array (
          'restockingFee' => 
          array (
            'name' => 'restockingFee',
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
 * Use [[MonetaryAmount]] to specify a fixed restocking fee for product
 * returns, or use [[Number]] to specify a percentage of the product price
 * paid by the customer.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[]|float|float[]|int|int[] $restockingFee
 *
 * @return static
 *
 * @see https://schema.org/restockingFee
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'returnFees' => 
      array (
        'name' => 'returnFees',
        'parameters' => 
        array (
          'returnFees' => 
          array (
            'name' => 'returnFees',
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
 * The type of return fees for purchased products (for any return reason).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnFeesEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnFeesEnumerationContract[] $returnFees
 *
 * @return static
 *
 * @see https://schema.org/returnFees
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'returnMethod' => 
      array (
        'name' => 'returnMethod',
        'parameters' => 
        array (
          'returnMethod' => 
          array (
            'name' => 'returnMethod',
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
 * The type of return method offered, specified from an enumeration.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnMethodEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnMethodEnumerationContract[] $returnMethod
 *
 * @return static
 *
 * @see https://schema.org/returnMethod
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'returnPolicyCategory' => 
      array (
        'name' => 'returnPolicyCategory',
        'parameters' => 
        array (
          'returnPolicyCategory' => 
          array (
            'name' => 'returnPolicyCategory',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
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
 * Specifies an applicable return policy (from an enumeration).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MerchantReturnEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\MerchantReturnEnumerationContract[] $returnPolicyCategory
 *
 * @return static
 *
 * @see https://schema.org/returnPolicyCategory
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 277,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'aliasName' => NULL,
      ),
      'returnShippingFeesAmount' => 
      array (
        'name' => 'returnShippingFeesAmount',
        'parameters' => 
        array (
          'returnShippingFeesAmount' => 
          array (
            'name' => 'returnShippingFeesAmount',
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
 * Amount of shipping costs for product returns (for any reason). Applicable
 * when property [[returnFees]] equals [[ReturnShippingFees]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[] $returnShippingFeesAmount
 *
 * @return static
 *
 * @see https://schema.org/returnShippingFeesAmount
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 310,
            'endLine' => 310,
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
        'startLine' => 310,
        'endLine' => 313,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 326,
            'endLine' => 326,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 341,
            'endLine' => 341,
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
        'startLine' => 341,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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
            'startLine' => 355,
            'endLine' => 355,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicySeasonalOverride',
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