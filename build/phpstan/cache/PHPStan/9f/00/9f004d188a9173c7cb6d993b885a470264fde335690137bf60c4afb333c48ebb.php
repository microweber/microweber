<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/FinancialIncentive.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\FinancialIncentive
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-852f27ef41bb5233498ca9abd0a47c2df196b07feaaa657ef16a04c92ab08f86-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/FinancialIncentive.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\FinancialIncentive',
    'shortName' => 'FinancialIncentive',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * <p>Represents financial incentives for goods/services offered by an
 * organization (or individual).</p>
 *
 * <p>Typically contains the [[name]] of the incentive, the
 * [[incentivizedItem]], the [[incentiveAmount]], the [[incentiveStatus]],
 * [[incentiveType]], the [[provider]] of the incentive, and
 * [[eligibleWithSupplier]].</p>
 *
 * <p>Optionally contains criteria on whether the incentive is limited based on
 * [[purchaseType]], [[purchasePriceLimit]], [[incomeLimit]], and the
 * [[qualifiedExpense]].
 *
 * @see https://schema.org/FinancialIncentive
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 550,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\FinancialIncentiveContract',
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
            'startLine' => 47,
            'endLine' => 47,
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
        'startLine' => 47,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 61,
            'endLine' => 61,
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
        'startLine' => 61,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'areaServed' => 
      array (
        'name' => 'areaServed',
        'parameters' => 
        array (
          'areaServed' => 
          array (
            'name' => 'areaServed',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
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
 * The geographic area where a service or offered item is provided.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract|\\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract[]|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract|\\Spatie\\SchemaOrg\\Contracts\\GeoShapeContract[]|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[]|string|string[] $areaServed
 *
 * @return static
 *
 * @see https://schema.org/areaServed
 */',
        'startLine' => 75,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 89,
            'endLine' => 89,
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
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 106,
            'endLine' => 106,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'eligibleWithSupplier' => 
      array (
        'name' => 'eligibleWithSupplier',
        'parameters' => 
        array (
          'eligibleWithSupplier' => 
          array (
            'name' => 'eligibleWithSupplier',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
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
 * The supplier of the incentivized item/service for which the incentive is
 * valid for such as a utility company, merchant, or contractor.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $eligibleWithSupplier
 *
 * @return static
 *
 * @see https://schema.org/eligibleWithSupplier
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
 */',
        'startLine' => 123,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 141,
            'endLine' => 141,
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
        'startLine' => 141,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 156,
            'endLine' => 156,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'incentiveAmount' => 
      array (
        'name' => 'incentiveAmount',
        'parameters' => 
        array (
          'incentiveAmount' => 
          array (
            'name' => 'incentiveAmount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 250,
            'endLine' => 250,
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
 * Describes the amount that can be redeemed from this incentive.
 *
 * <p>[[QuantitativeValue]]: Use this for incentives based on price (either
 * raw amount or percentage-based). For a raw amount example, "You can claim
 * $2,500 - $7,500 from the total cost of installation" would be represented
 * as the following:</p>
 *     {
 *         "@type": "QuantitativeValue",
 *         “minValue”: 2500,
 *         “maxValue”: 7500,
 *         "unitCode": "USD"
 *     }
 * <p>[[QuantitivateValue]] can also be used for percentage amounts. In such
 * cases, value is used to represent the incentive’s percentage, while
 * maxValue represents a limit (if one exists) to that incentive. The
 * unitCode should be \'P1\' and the unitText should be \'%\', while
 * valueReference should be used for holding the currency type. For example,
 * "You can claim up to 30% of the total cost of installation, up to a
 * maximum of $7,500" would be:</p>
 *     {
 *         "@type": "QuantitativeValue",
 *         "value": 30,
 *         "unitCode": "P1",
 *         "unitText": "%",
 *         “maxValue”: 7500,
 *         “valueReference”: “USD”
 *     }
 * <p>[[UnitPriceSpecification]]: Use this for incentives that are based on
 * amounts rather than price. For example, a net metering rebate that pays
 * $10/kWh, up to $1,000:</p>
 *     {
 *         "@type": "UnitPriceSpecification",
 *         "price": 10,
 *         "priceCurrency": "USD",
 *         "referenceQuantity": 1,
 *         "unitCode": "DO3",
 *         "unitText": "kw/h",
 *         "maxPrice": 1000,
 *         "description": "$10 / kwh up to $1000"
 *     }
 * <p>[[LoanOrCredit]]: Use for incentives that are loan based. For example,
 * a loan of $4,000 - $50,000 with a repayment term of 10 years, interest
 * free would look like:</p>
 *     {
 *         "@type": "LoanOrCredit",
 *         "loanTerm": {
 *                 "@type":"QuantitativeValue",
 *                 "value":"10",
 *                 "unitCode": "ANN"
 *             },
 *         "amount":[
 *             {
 *                 "@type": "QuantitativeValue",
 *                 "Name":"fixed interest rate",
 *                 "value":"0",
 *             },
 *         ],
 *         "amount":[
 *             {
 *                 "@type": "MonetaryAmount",
 *                 "Name":"min loan amount",
 *                 "value":"4000",
 *                 "currency":"CAD"
 *             },
 *             {
 *                 "@type": "MonetaryAmount",
 *                 "Name":"max loan amount",
 *                 "value":"50000",
 *                 "currency":"CAD"
 *             }
 *         ],
 *     }
 *
 * In summary: <ul>* Use [[QuantitativeValue]] for absolute/percentage-based
 * incentives applied on the price of a good/service.
 * * Use [[UnitPriceSpecification]] for incentives based on a per-unit basis
 * (e.g. net metering).
 * * Use [[LoanOrCredit]] for loans/credits.
 * .
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\LoanOrCreditContract|\\Spatie\\SchemaOrg\\Contracts\\LoanOrCreditContract[]|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|\\Spatie\\SchemaOrg\\Contracts\\UnitPriceSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\UnitPriceSpecificationContract[] $incentiveAmount
 *
 * @return static
 *
 * @see https://schema.org/incentiveAmount
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
 */',
        'startLine' => 250,
        'endLine' => 253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'incentiveStatus' => 
      array (
        'name' => 'incentiveStatus',
        'parameters' => 
        array (
          'incentiveStatus' => 
          array (
            'name' => 'incentiveStatus',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 266,
            'endLine' => 266,
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
 * The status of the incentive (active, on hold, retired, etc.).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\IncentiveStatusContract|\\Spatie\\SchemaOrg\\Contracts\\IncentiveStatusContract[] $incentiveStatus
 *
 * @return static
 *
 * @see https://schema.org/incentiveStatus
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
 */',
        'startLine' => 266,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'incentiveType' => 
      array (
        'name' => 'incentiveType',
        'parameters' => 
        array (
          'incentiveType' => 
          array (
            'name' => 'incentiveType',
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
 * The type of incentive offered (tax credit/rebate, tax deduction, tax
 * waiver, subsidies, etc.).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\IncentiveTypeContract|\\Spatie\\SchemaOrg\\Contracts\\IncentiveTypeContract[] $incentiveType
 *
 * @return static
 *
 * @see https://schema.org/incentiveType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'incentivizedItem' => 
      array (
        'name' => 'incentivizedItem',
        'parameters' => 
        array (
          'incentivizedItem' => 
          array (
            'name' => 'incentivizedItem',
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
 * The type or specific product(s) and/or service(s) being incentivized.
 * <p>DefinedTermSets are used for product and service categories such as
 * the United Nations Standard Products and Services Code:</p>
 *     {
 *         "@type": "DefinedTerm",
 *         "inDefinedTermSet": "https://www.unspsc.org/",
 *         "termCode": "261315XX",
 *         "name": "Photovoltaic module"
 *     }
 *
 * <p>For a specific product or service, use the Product type:</p>
 *     {
 *         "@type": "Product",
 *         "name": "Kenmore White 17" Microwave",
 *     }
 * For multiple different incentivized items, use multiple [[DefinedTerm]]
 * or [[Product]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\ProductContract|\\Spatie\\SchemaOrg\\Contracts\\ProductContract[] $incentivizedItem
 *
 * @return static
 *
 * @see https://schema.org/incentivizedItem
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'incomeLimit' => 
      array (
        'name' => 'incomeLimit',
        'parameters' => 
        array (
          'incomeLimit' => 
          array (
            'name' => 'incomeLimit',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 344,
            'endLine' => 344,
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
 * Optional. Income limit for which the incentive is applicable for.
 *
 * <p>If MonetaryAmount is specified, this should be based on annualized
 * income (e.g. if an incentive is limited to those making <$114,000
 * annually):</p>
 *     {
 *         "@type": "MonetaryAmount",
 *         "maxValue": 114000,
 *         "currency": "USD",
 *     }
 *
 * Use Text for incentives that are limited based on other criteria, for
 * example if an incentive is only available to recipients making 120% of
 * the median poverty income in their area.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[]|string|string[] $incomeLimit
 *
 * @return static
 *
 * @see https://schema.org/incomeLimit
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
 */',
        'startLine' => 344,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 374,
            'endLine' => 374,
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
        'startLine' => 374,
        'endLine' => 377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 389,
            'endLine' => 389,
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
        'startLine' => 389,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 406,
            'endLine' => 406,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
 * The publisher of the article in question.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $publisher
 *
 * @return static
 *
 * @see https://schema.org/publisher
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'purchasePriceLimit' => 
      array (
        'name' => 'purchasePriceLimit',
        'parameters' => 
        array (
          'purchasePriceLimit' => 
          array (
            'name' => 'purchasePriceLimit',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 437,
            'endLine' => 437,
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
 * Optional. The maximum price the item can have and still qualify for this
 * offer.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[] $purchasePriceLimit
 *
 * @return static
 *
 * @see https://schema.org/purchasePriceLimit
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
 */',
        'startLine' => 437,
        'endLine' => 440,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'purchaseType' => 
      array (
        'name' => 'purchaseType',
        'parameters' => 
        array (
          'purchaseType' => 
          array (
            'name' => 'purchaseType',
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
 * Optional. The type of purchase the consumer must make in order to qualify
 * for this incentive.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PurchaseTypeContract|\\Spatie\\SchemaOrg\\Contracts\\PurchaseTypeContract[] $purchaseType
 *
 * @return static
 *
 * @see https://schema.org/purchaseType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'aliasName' => NULL,
      ),
      'qualifiedExpense' => 
      array (
        'name' => 'qualifiedExpense',
        'parameters' => 
        array (
          'qualifiedExpense' => 
          array (
            'name' => 'qualifiedExpense',
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
 * Optional. The types of expenses that are covered by the incentive. For
 * example some incentives are only for the goods (tangible items) but the
 * services (labor) are excluded.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\IncentiveQualifiedExpenseTypeContract|\\Spatie\\SchemaOrg\\Contracts\\IncentiveQualifiedExpenseTypeContract[] $qualifiedExpense
 *
 * @return static
 *
 * @see https://schema.org/qualifiedExpense
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3572
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 488,
            'endLine' => 488,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 503,
            'endLine' => 503,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 517,
            'endLine' => 517,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 531,
            'endLine' => 531,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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
            'startLine' => 546,
            'endLine' => 546,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'implementingClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
        'currentClassName' => 'Spatie\\SchemaOrg\\FinancialIncentive',
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