<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MerchantReturnPolicy.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\MerchantReturnPolicy
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3173735569eb5c65d4c0b128c61e5860ee9ced089627eeb2232b8d38f2bc1b54-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MerchantReturnPolicy.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
    'shortName' => 'MerchantReturnPolicy',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A MerchantReturnPolicy provides information about product return policies
 * associated with an [[Organization]], [[Product]], or [[Offer]].
 *
 * @see https://schema.org/MerchantReturnPolicy
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 590,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\MerchantReturnPolicyContract',
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
            'startLine' => 37,
            'endLine' => 37,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 60,
            'endLine' => 60,
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
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 74,
            'endLine' => 74,
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
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'applicableCountry' => 
      array (
        'name' => 'applicableCountry',
        'parameters' => 
        array (
          'applicableCountry' => 
          array (
            'name' => 'applicableCountry',
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
 * A country where a particular merchant return policy applies to, for
 * example the two-letter ISO 3166-1 alpha-2 country code.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CountryContract|\\Spatie\\SchemaOrg\\Contracts\\CountryContract[]|string|string[] $applicableCountry
 *
 * @return static
 *
 * @see https://schema.org/applicableCountry
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3001
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'customerRemorseReturnFees' => 
      array (
        'name' => 'customerRemorseReturnFees',
        'parameters' => 
        array (
          'customerRemorseReturnFees' => 
          array (
            'name' => 'customerRemorseReturnFees',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
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
 * The type of return fees if the product is returned due to customer
 * remorse.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnFeesEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnFeesEnumerationContract[] $customerRemorseReturnFees
 *
 * @return static
 *
 * @see https://schema.org/customerRemorseReturnFees
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 108,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'customerRemorseReturnLabelSource' => 
      array (
        'name' => 'customerRemorseReturnLabelSource',
        'parameters' => 
        array (
          'customerRemorseReturnLabelSource' => 
          array (
            'name' => 'customerRemorseReturnLabelSource',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 54,
            'endColumn' => 86,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The method (from an enumeration) by which the customer obtains a return
 * shipping label for a product returned due to customer remorse.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnLabelSourceEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnLabelSourceEnumerationContract[] $customerRemorseReturnLabelSource
 *
 * @return static
 *
 * @see https://schema.org/customerRemorseReturnLabelSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 125,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'customerRemorseReturnShippingFeesAmount' => 
      array (
        'name' => 'customerRemorseReturnShippingFeesAmount',
        'parameters' => 
        array (
          'customerRemorseReturnShippingFeesAmount' => 
          array (
            'name' => 'customerRemorseReturnShippingFeesAmount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 61,
            'endColumn' => 100,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The amount of shipping costs if a product is returned due to customer
 * remorse. Applicable when property [[customerRemorseReturnFees]] equals
 * [[ReturnShippingFees]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[] $customerRemorseReturnShippingFeesAmount
 *
 * @return static
 *
 * @see https://schema.org/customerRemorseReturnShippingFeesAmount
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 143,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 157,
            'endLine' => 157,
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
        'startLine' => 157,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 174,
            'endLine' => 174,
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
        'startLine' => 174,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 192,
            'endLine' => 192,
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
        'startLine' => 192,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 207,
            'endLine' => 207,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'inStoreReturnsOffered' => 
      array (
        'name' => 'inStoreReturnsOffered',
        'parameters' => 
        array (
          'inStoreReturnsOffered' => 
          array (
            'name' => 'inStoreReturnsOffered',
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
 * Are in-store returns offered? (For more advanced return methods use the
 * [[returnMethod]] property.)
 *
 * @param bool|bool[] $inStoreReturnsOffered
 *
 * @return static
 *
 * @see https://schema.org/inStoreReturnsOffered
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'itemCondition' => 
      array (
        'name' => 'itemCondition',
        'parameters' => 
        array (
          'itemCondition' => 
          array (
            'name' => 'itemCondition',
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
 * A predefined value from OfferItemCondition specifying the condition of
 * the product or service, or the products or services included in the
 * offer. Also used for product return policies to specify the condition of
 * products accepted for returns.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OfferItemConditionContract|\\Spatie\\SchemaOrg\\Contracts\\OfferItemConditionContract[] $itemCondition
 *
 * @return static
 *
 * @see https://schema.org/itemCondition
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'itemDefectReturnFees' => 
      array (
        'name' => 'itemDefectReturnFees',
        'parameters' => 
        array (
          'itemDefectReturnFees' => 
          array (
            'name' => 'itemDefectReturnFees',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
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
 * The type of return fees for returns of defect products.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnFeesEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnFeesEnumerationContract[] $itemDefectReturnFees
 *
 * @return static
 *
 * @see https://schema.org/itemDefectReturnFees
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 257,
        'endLine' => 260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'itemDefectReturnLabelSource' => 
      array (
        'name' => 'itemDefectReturnLabelSource',
        'parameters' => 
        array (
          'itemDefectReturnLabelSource' => 
          array (
            'name' => 'itemDefectReturnLabelSource',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 274,
            'endLine' => 274,
            'startColumn' => 49,
            'endColumn' => 76,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The method (from an enumeration) by which the customer obtains a return
 * shipping label for a defect product.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnLabelSourceEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnLabelSourceEnumerationContract[] $itemDefectReturnLabelSource
 *
 * @return static
 *
 * @see https://schema.org/itemDefectReturnLabelSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 274,
        'endLine' => 277,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'itemDefectReturnShippingFeesAmount' => 
      array (
        'name' => 'itemDefectReturnShippingFeesAmount',
        'parameters' => 
        array (
          'itemDefectReturnShippingFeesAmount' => 
          array (
            'name' => 'itemDefectReturnShippingFeesAmount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 291,
            'endLine' => 291,
            'startColumn' => 56,
            'endColumn' => 90,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Amount of shipping costs for defect product returns. Applicable when
 * property [[itemDefectReturnFees]] equals [[ReturnShippingFees]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[] $itemDefectReturnShippingFeesAmount
 *
 * @return static
 *
 * @see https://schema.org/itemDefectReturnShippingFeesAmount
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 291,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 307,
            'endLine' => 307,
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
        'startLine' => 307,
        'endLine' => 310,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 326,
            'endLine' => 326,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'merchantReturnLink' => 
      array (
        'name' => 'merchantReturnLink',
        'parameters' => 
        array (
          'merchantReturnLink' => 
          array (
            'name' => 'merchantReturnLink',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 342,
            'endLine' => 342,
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
 * Specifies a Web page or service by URL, for product returns.
 *
 * @param string|string[] $merchantReturnLink
 *
 * @return static
 *
 * @see https://schema.org/merchantReturnLink
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2288
 */',
        'startLine' => 342,
        'endLine' => 345,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 356,
            'endLine' => 356,
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
        'startLine' => 356,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 371,
            'endLine' => 371,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 387,
            'endLine' => 387,
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
        'startLine' => 387,
        'endLine' => 390,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 405,
            'endLine' => 405,
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
        'startLine' => 405,
        'endLine' => 408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 421,
            'endLine' => 421,
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
        'startLine' => 421,
        'endLine' => 424,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'returnLabelSource' => 
      array (
        'name' => 'returnLabelSource',
        'parameters' => 
        array (
          'returnLabelSource' => 
          array (
            'name' => 'returnLabelSource',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 438,
            'endLine' => 438,
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
 * The method (from an enumeration) by which the customer obtains a return
 * shipping label for a product returned for any reason.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ReturnLabelSourceEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\ReturnLabelSourceEnumerationContract[] $returnLabelSource
 *
 * @return static
 *
 * @see https://schema.org/returnLabelSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 438,
        'endLine' => 441,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 454,
            'endLine' => 454,
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
        'startLine' => 454,
        'endLine' => 457,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 470,
            'endLine' => 470,
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
        'startLine' => 470,
        'endLine' => 473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'returnPolicyCountry' => 
      array (
        'name' => 'returnPolicyCountry',
        'parameters' => 
        array (
          'returnPolicyCountry' => 
          array (
            'name' => 'returnPolicyCountry',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 491,
            'endLine' => 491,
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
 * The country where the product has to be sent to for returns, for example
 * "Ireland" using the [[name]] property of [[Country]]. You can also
 * provide the two-letter [ISO 3166-1 alpha-2 country
 * code](http://en.wikipedia.org/wiki/ISO_3166-1). Note that this can be
 * different from the country where the product was originally shipped from
 * or sent to.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CountryContract|\\Spatie\\SchemaOrg\\Contracts\\CountryContract[]|string|string[] $returnPolicyCountry
 *
 * @return static
 *
 * @see https://schema.org/returnPolicyCountry
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 491,
        'endLine' => 494,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'aliasName' => NULL,
      ),
      'returnPolicySeasonalOverride' => 
      array (
        'name' => 'returnPolicySeasonalOverride',
        'parameters' => 
        array (
          'returnPolicySeasonalOverride' => 
          array (
            'name' => 'returnPolicySeasonalOverride',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 507,
            'endLine' => 507,
            'startColumn' => 50,
            'endColumn' => 78,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Seasonal override of a return policy.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MerchantReturnPolicySeasonalOverrideContract|\\Spatie\\SchemaOrg\\Contracts\\MerchantReturnPolicySeasonalOverrideContract[] $returnPolicySeasonalOverride
 *
 * @return static
 *
 * @see https://schema.org/returnPolicySeasonalOverride
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2880
 */',
        'startLine' => 507,
        'endLine' => 510,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 524,
            'endLine' => 524,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 540,
            'endLine' => 540,
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
        'startLine' => 540,
        'endLine' => 543,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 555,
            'endLine' => 555,
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
        'startLine' => 555,
        'endLine' => 558,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 569,
            'endLine' => 569,
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
        'startLine' => 569,
        'endLine' => 572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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
            'startLine' => 586,
            'endLine' => 586,
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
        'startLine' => 586,
        'endLine' => 589,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
        'currentClassName' => 'Spatie\\SchemaOrg\\MerchantReturnPolicy',
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