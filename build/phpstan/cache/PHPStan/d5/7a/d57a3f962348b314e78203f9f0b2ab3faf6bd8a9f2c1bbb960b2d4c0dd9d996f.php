<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/PaymentMethodType.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\PaymentMethodType
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-40e1fab5fdb77ff22d6265551803034d07279b332fc929c2f03b3141160363e4-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/PaymentMethodType.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\PaymentMethodType',
    'shortName' => 'PaymentMethodType',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The type of payment method, only for generic payment types, specific forms of
 * payments, like card payment should be expressed using subclasses of
 * PaymentMethod.
 *
 * @see https://schema.org/PaymentMethodType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3537
 *
 * @method static supersededBy($supersededBy) The value should be instance of pending types Class|Class[]|Enumeration|Enumeration[]|Property|Property[]
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 276,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\PaymentMethodTypeContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\EnumerationContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'ByBankTransferInAdvance' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'ByBankTransferInAdvance',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/ByBankTransferInAdvance\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 62,
            'startFilePos' => 1106,
            'endTokenPos' => 62,
            'endFilePos' => 1149,
          ),
        ),
        'docComment' => '/**
 * Payment in advance by bank transfer, equivalent to
 * ```http://purl.org/goodrelations/v1#ByBankTransferInAdvance```.
 *
 * @see https://schema.org/ByBankTransferInAdvance
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 88,
      ),
      'ByInvoice' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'ByInvoice',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/ByInvoice\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 75,
            'startFilePos' => 1386,
            'endTokenPos' => 75,
            'endFilePos' => 1415,
          ),
        ),
        'docComment' => '/**
 * Payment by invoice, typically after the goods were delivered, equivalent
 * to ```http://purl.org/goodrelations/v1#ByInvoice```.
 *
 * @see https://schema.org/ByInvoice
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 60,
      ),
      'COD' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'COD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/COD\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 88,
            'startFilePos' => 1604,
            'endTokenPos' => 88,
            'endFilePos' => 1627,
          ),
        ),
        'docComment' => '/**
 * Cash on Delivery (COD) payment, equivalent to
 * ```http://purl.org/goodrelations/v1#COD```.
 *
 * @see https://schema.org/COD
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'Cash' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'Cash',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/Cash\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 101,
            'startFilePos' => 1820,
            'endTokenPos' => 101,
            'endFilePos' => 1844,
          ),
        ),
        'docComment' => '/**
 * Payment using cash, on premises, equivalent to
 * ```http://purl.org/goodrelations/v1#Cash```.
 *
 * @see https://schema.org/Cash
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'CheckInAdvance' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'CheckInAdvance',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/CheckInAdvance\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 114,
            'startFilePos' => 2073,
            'endTokenPos' => 114,
            'endFilePos' => 2107,
          ),
        ),
        'docComment' => '/**
 * Payment in advance by sending a check, equivalent to
 * ```http://purl.org/goodrelations/v1#CheckInAdvance```.
 *
 * @see https://schema.org/CheckInAdvance
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 70,
      ),
      'DirectDebit' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'DirectDebit',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/DirectDebit\'',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 127,
            'startFilePos' => 2338,
            'endTokenPos' => 127,
            'endFilePos' => 2369,
          ),
        ),
        'docComment' => '/**
 * Payment in advance by direct debit from the bank, equivalent to
 * ```http://purl.org/goodrelations/v1#DirectDebit```.
 *
 * @see https://schema.org/DirectDebit
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
      'InStorePrepay' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'InStorePrepay',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/InStorePrepay\'',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 140,
            'startFilePos' => 2565,
            'endTokenPos' => 140,
            'endFilePos' => 2598,
          ),
        ),
        'docComment' => '/**
 * Payment in advance in some form of shop or kiosk for goods purchased
 * online.
 *
 * @see https://schema.org/InStorePrepay
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 68,
      ),
      'PhoneCarrierPayment' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'name' => 'PhoneCarrierPayment',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/PhoneCarrierPayment\'',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 153,
            'startFilePos' => 2764,
            'endTokenPos' => 153,
            'endFilePos' => 2803,
          ),
        ),
        'docComment' => '/**
 * Payment by billing via the phone carrier.
 *
 * @see https://schema.org/PhoneCarrierPayment
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 80,
      ),
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
            'startLine' => 104,
            'endLine' => 104,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 118,
            'endLine' => 118,
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
        'startLine' => 118,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 132,
            'endLine' => 132,
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
        'startLine' => 132,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 149,
            'endLine' => 149,
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
        'startLine' => 149,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 167,
            'endLine' => 167,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 182,
            'endLine' => 182,
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
        'startLine' => 182,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 198,
            'endLine' => 198,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 212,
            'endLine' => 212,
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
        'startLine' => 212,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 227,
            'endLine' => 227,
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
        'startLine' => 227,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 243,
            'endLine' => 243,
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
        'startLine' => 243,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 258,
            'endLine' => 258,
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
        'startLine' => 258,
        'endLine' => 261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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
            'startLine' => 272,
            'endLine' => 272,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
        'currentClassName' => 'Spatie\\SchemaOrg\\PaymentMethodType',
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