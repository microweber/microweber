<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/DDxElement.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\DDxElement
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-609857e3e8306a33c441686217f58a1d42355af5bbb3c6eeff791643464a7a0c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\DDxElement',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/DDxElement.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\DDxElement',
    'shortName' => 'DDxElement',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An alternative, closely-related condition typically considered later in the
 * differential diagnosis process along with the signs that are used to
 * distinguish it.
 *
 * @see https://schema.org/DDxElement
 * @see https://health-lifesci.schema.org
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 369,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\DDxElementContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\MedicalIntangibleContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'code' => 
      array (
        'name' => 'code',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
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
 * A medical code for the entity, taken from a controlled vocabulary or
 * ontology such as ICD-9, DiseasesDB, MeSH, SNOMED-CT, RxNorm, etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalCodeContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalCodeContract[] $code
 *
 * @return static
 *
 * @see https://schema.org/code
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 83,
            'endLine' => 83,
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
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'diagnosis' => 
      array (
        'name' => 'diagnosis',
        'parameters' => 
        array (
          'diagnosis' => 
          array (
            'name' => 'diagnosis',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 99,
            'endLine' => 99,
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
 * One or more alternative conditions considered in the differential
 * diagnosis process as output of a diagnosis process.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalConditionContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalConditionContract[] $diagnosis
 *
 * @return static
 *
 * @see https://schema.org/diagnosis
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 99,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 116,
            'endLine' => 116,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'distinguishingSign' => 
      array (
        'name' => 'distinguishingSign',
        'parameters' => 
        array (
          'distinguishingSign' => 
          array (
            'name' => 'distinguishingSign',
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
 * One of a set of signs and symptoms that can be used to distinguish this
 * diagnosis from others in the differential diagnosis.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalSignOrSymptomContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalSignOrSymptomContract[] $distinguishingSign
 *
 * @return static
 *
 * @see https://schema.org/distinguishingSign
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 149,
            'endLine' => 149,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'guideline' => 
      array (
        'name' => 'guideline',
        'parameters' => 
        array (
          'guideline' => 
          array (
            'name' => 'guideline',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 164,
            'endLine' => 164,
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
 * A medical guideline related to this entity.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalGuidelineContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalGuidelineContract[] $guideline
 *
 * @return static
 *
 * @see https://schema.org/guideline
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 164,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 182,
            'endLine' => 182,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 197,
            'endLine' => 197,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'legalStatus' => 
      array (
        'name' => 'legalStatus',
        'parameters' => 
        array (
          'legalStatus' => 
          array (
            'name' => 'legalStatus',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 213,
            'endLine' => 213,
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
 * The drug or supplement\'s legal status, including any controlled substance
 * schedules that apply.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DrugLegalStatusContract|\\Spatie\\SchemaOrg\\Contracts\\DrugLegalStatusContract[]|\\Spatie\\SchemaOrg\\Contracts\\MedicalEnumerationContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalEnumerationContract[]|string|string[] $legalStatus
 *
 * @return static
 *
 * @see https://schema.org/legalStatus
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 213,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 229,
            'endLine' => 229,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'medicineSystem' => 
      array (
        'name' => 'medicineSystem',
        'parameters' => 
        array (
          'medicineSystem' => 
          array (
            'name' => 'medicineSystem',
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
 * The system of medicine that includes this MedicalEntity, for example
 * \'evidence-based\', \'homeopathic\', \'chiropractic\', etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicineSystemContract|\\Spatie\\SchemaOrg\\Contracts\\MedicineSystemContract[] $medicineSystem
 *
 * @return static
 *
 * @see https://schema.org/medicineSystem
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 259,
            'endLine' => 259,
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
        'startLine' => 259,
        'endLine' => 262,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 274,
            'endLine' => 274,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'recognizingAuthority' => 
      array (
        'name' => 'recognizingAuthority',
        'parameters' => 
        array (
          'recognizingAuthority' => 
          array (
            'name' => 'recognizingAuthority',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 290,
            'endLine' => 290,
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
 * If applicable, the organization that officially recognizes this entity as
 * part of its endorsed system of medicine.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $recognizingAuthority
 *
 * @return static
 *
 * @see https://schema.org/recognizingAuthority
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 290,
        'endLine' => 293,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'relevantSpecialty' => 
      array (
        'name' => 'relevantSpecialty',
        'parameters' => 
        array (
          'relevantSpecialty' => 
          array (
            'name' => 'relevantSpecialty',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 305,
            'endLine' => 305,
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
 * If applicable, a medical specialty in which this entity is relevant.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalSpecialtyContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalSpecialtyContract[] $relevantSpecialty
 *
 * @return static
 *
 * @see https://schema.org/relevantSpecialty
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 305,
        'endLine' => 308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 321,
            'endLine' => 321,
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
        'startLine' => 321,
        'endLine' => 324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'aliasName' => NULL,
      ),
      'study' => 
      array (
        'name' => 'study',
        'parameters' => 
        array (
          'study' => 
          array (
            'name' => 'study',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 336,
            'endLine' => 336,
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
 * A medical study or trial related to this entity.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalStudyContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalStudyContract[] $study
 *
 * @return static
 *
 * @see https://schema.org/study
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 336,
        'endLine' => 339,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
            'startLine' => 351,
            'endLine' => 351,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'implementingClassName' => 'Spatie\\SchemaOrg\\DDxElement',
        'currentClassName' => 'Spatie\\SchemaOrg\\DDxElement',
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