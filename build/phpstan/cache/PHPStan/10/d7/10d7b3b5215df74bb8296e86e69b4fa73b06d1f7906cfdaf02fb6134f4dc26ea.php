<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/VitalSign.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\VitalSign
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2379dcb2531310956dabcc9d396e85b75e2faafb6e9dcff2c5eae31871f3ba47-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\VitalSign',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/VitalSign.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\VitalSign',
    'shortName' => 'VitalSign',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Vital signs are measures of various physiological functions in order to
 * assess the most basic body functions.
 *
 * @see https://schema.org/VitalSign
 * @see https://health-lifesci.schema.org
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 625,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\VitalSignContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\MedicalConditionContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\MedicalSignContract',
      4 => 'Spatie\\SchemaOrg\\Contracts\\MedicalSignOrSymptomContract',
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
            'startLine' => 40,
            'endLine' => 40,
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
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 54,
            'endLine' => 54,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'associatedAnatomy' => 
      array (
        'name' => 'associatedAnatomy',
        'parameters' => 
        array (
          'associatedAnatomy' => 
          array (
            'name' => 'associatedAnatomy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
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
 * The anatomy of the underlying organ system or structures associated with
 * this entity.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AnatomicalStructureContract|\\Spatie\\SchemaOrg\\Contracts\\AnatomicalStructureContract[]|\\Spatie\\SchemaOrg\\Contracts\\AnatomicalSystemContract|\\Spatie\\SchemaOrg\\Contracts\\AnatomicalSystemContract[]|\\Spatie\\SchemaOrg\\Contracts\\SuperficialAnatomyContract|\\Spatie\\SchemaOrg\\Contracts\\SuperficialAnatomyContract[] $associatedAnatomy
 *
 * @return static
 *
 * @see https://schema.org/associatedAnatomy
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 70,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 86,
            'endLine' => 86,
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
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 100,
            'endLine' => 100,
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
        'startLine' => 100,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'differentialDiagnosis' => 
      array (
        'name' => 'differentialDiagnosis',
        'parameters' => 
        array (
          'differentialDiagnosis' => 
          array (
            'name' => 'differentialDiagnosis',
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
 * One of a set of differential diagnoses for the condition. Specifically, a
 * closely-related or competing diagnosis typically considered later in the
 * cognitive process whereby this medical condition is distinguished from
 * others most likely responsible for a similar collection of signs and
 * symptoms to reach the most parsimonious diagnosis or diagnoses in a
 * patient.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DDxElementContract|\\Spatie\\SchemaOrg\\Contracts\\DDxElementContract[] $differentialDiagnosis
 *
 * @return static
 *
 * @see https://schema.org/differentialDiagnosis
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'drug' => 
      array (
        'name' => 'drug',
        'parameters' => 
        array (
          'drug' => 
          array (
            'name' => 'drug',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
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
 * Specifying a drug or medicine used in a medication procedure.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DrugContract|\\Spatie\\SchemaOrg\\Contracts\\DrugContract[] $drug
 *
 * @return static
 *
 * @see https://schema.org/drug
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 152,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'epidemiology' => 
      array (
        'name' => 'epidemiology',
        'parameters' => 
        array (
          'epidemiology' => 
          array (
            'name' => 'epidemiology',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 168,
            'endLine' => 168,
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
 * The characteristics of associated patients, such as age, gender, race
 * etc.
 *
 * @param string|string[] $epidemiology
 *
 * @return static
 *
 * @see https://schema.org/epidemiology
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 168,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'expectedPrognosis' => 
      array (
        'name' => 'expectedPrognosis',
        'parameters' => 
        array (
          'expectedPrognosis' => 
          array (
            'name' => 'expectedPrognosis',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 184,
            'endLine' => 184,
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
 * The likely outcome in either the short term or long term of the medical
 * condition.
 *
 * @param string|string[] $expectedPrognosis
 *
 * @return static
 *
 * @see https://schema.org/expectedPrognosis
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 184,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 201,
            'endLine' => 201,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 216,
            'endLine' => 216,
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
        'startLine' => 216,
        'endLine' => 219,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 234,
            'endLine' => 234,
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
        'startLine' => 234,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'identifyingExam' => 
      array (
        'name' => 'identifyingExam',
        'parameters' => 
        array (
          'identifyingExam' => 
          array (
            'name' => 'identifyingExam',
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
 * A physical examination that can identify this sign.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PhysicalExamContract|\\Spatie\\SchemaOrg\\Contracts\\PhysicalExamContract[] $identifyingExam
 *
 * @return static
 *
 * @see https://schema.org/identifyingExam
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'identifyingTest' => 
      array (
        'name' => 'identifyingTest',
        'parameters' => 
        array (
          'identifyingTest' => 
          array (
            'name' => 'identifyingTest',
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
 * A diagnostic test that can identify this sign.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalTestContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalTestContract[] $identifyingTest
 *
 * @return static
 *
 * @see https://schema.org/identifyingTest
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 279,
            'endLine' => 279,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 295,
            'endLine' => 295,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 311,
            'endLine' => 311,
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
        'startLine' => 311,
        'endLine' => 314,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 327,
            'endLine' => 327,
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
        'startLine' => 327,
        'endLine' => 330,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 341,
            'endLine' => 341,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'naturalProgression' => 
      array (
        'name' => 'naturalProgression',
        'parameters' => 
        array (
          'naturalProgression' => 
          array (
            'name' => 'naturalProgression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 357,
            'endLine' => 357,
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
 * The expected progression of the condition if it is not treated and
 * allowed to progress naturally.
 *
 * @param string|string[] $naturalProgression
 *
 * @return static
 *
 * @see https://schema.org/naturalProgression
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 357,
        'endLine' => 360,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'pathophysiology' => 
      array (
        'name' => 'pathophysiology',
        'parameters' => 
        array (
          'pathophysiology' => 
          array (
            'name' => 'pathophysiology',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 373,
            'endLine' => 373,
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
 * Changes in the normal mechanical, physical, and biochemical functions
 * that are associated with this activity or condition.
 *
 * @param string|string[] $pathophysiology
 *
 * @return static
 *
 * @see https://schema.org/pathophysiology
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 373,
        'endLine' => 376,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'possibleComplication' => 
      array (
        'name' => 'possibleComplication',
        'parameters' => 
        array (
          'possibleComplication' => 
          array (
            'name' => 'possibleComplication',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 390,
            'endLine' => 390,
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
 * A possible unexpected and unfavorable evolution of a medical condition.
 * Complications may include worsening of the signs or symptoms of the
 * disease, extension of the condition to other organ systems, etc.
 *
 * @param string|string[] $possibleComplication
 *
 * @return static
 *
 * @see https://schema.org/possibleComplication
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 390,
        'endLine' => 393,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'possibleTreatment' => 
      array (
        'name' => 'possibleTreatment',
        'parameters' => 
        array (
          'possibleTreatment' => 
          array (
            'name' => 'possibleTreatment',
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
 * A possible treatment to address this condition, sign or symptom.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract[] $possibleTreatment
 *
 * @return static
 *
 * @see https://schema.org/possibleTreatment
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 420,
            'endLine' => 420,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'primaryPrevention' => 
      array (
        'name' => 'primaryPrevention',
        'parameters' => 
        array (
          'primaryPrevention' => 
          array (
            'name' => 'primaryPrevention',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 436,
            'endLine' => 436,
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
 * A preventative therapy used to prevent an initial occurrence of the
 * medical condition, such as vaccination.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract[] $primaryPrevention
 *
 * @return static
 *
 * @see https://schema.org/primaryPrevention
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 436,
        'endLine' => 439,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 452,
            'endLine' => 452,
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
        'startLine' => 452,
        'endLine' => 455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 467,
            'endLine' => 467,
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
        'startLine' => 467,
        'endLine' => 470,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'riskFactor' => 
      array (
        'name' => 'riskFactor',
        'parameters' => 
        array (
          'riskFactor' => 
          array (
            'name' => 'riskFactor',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 483,
            'endLine' => 483,
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
 * A modifiable or non-modifiable factor that increases the risk of a
 * patient contracting this condition, e.g. age,  coexisting condition.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalRiskFactorContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalRiskFactorContract[] $riskFactor
 *
 * @return static
 *
 * @see https://schema.org/riskFactor
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 483,
        'endLine' => 486,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 499,
            'endLine' => 499,
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
        'startLine' => 499,
        'endLine' => 502,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'secondaryPrevention' => 
      array (
        'name' => 'secondaryPrevention',
        'parameters' => 
        array (
          'secondaryPrevention' => 
          array (
            'name' => 'secondaryPrevention',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 515,
            'endLine' => 515,
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
 * A preventative therapy used to prevent reoccurrence of the medical
 * condition after an initial episode of the condition.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract[] $secondaryPrevention
 *
 * @return static
 *
 * @see https://schema.org/secondaryPrevention
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 515,
        'endLine' => 518,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'signOrSymptom' => 
      array (
        'name' => 'signOrSymptom',
        'parameters' => 
        array (
          'signOrSymptom' => 
          array (
            'name' => 'signOrSymptom',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 532,
            'endLine' => 532,
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
 * A sign or symptom of this condition. Signs are objective or physically
 * observable manifestations of the medical condition while symptoms are the
 * subjective experience of the medical condition.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalSignOrSymptomContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalSignOrSymptomContract[] $signOrSymptom
 *
 * @return static
 *
 * @see https://schema.org/signOrSymptom
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 532,
        'endLine' => 535,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'stage' => 
      array (
        'name' => 'stage',
        'parameters' => 
        array (
          'stage' => 
          array (
            'name' => 'stage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 547,
            'endLine' => 547,
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
 * The stage of the condition, if applicable.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalConditionStageContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalConditionStageContract[] $stage
 *
 * @return static
 *
 * @see https://schema.org/stage
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 547,
        'endLine' => 550,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'status' => 
      array (
        'name' => 'status',
        'parameters' => 
        array (
          'status' => 
          array (
            'name' => 'status',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 562,
            'endLine' => 562,
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
 * The status of the study (enumerated).
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EventStatusTypeContract|\\Spatie\\SchemaOrg\\Contracts\\EventStatusTypeContract[]|\\Spatie\\SchemaOrg\\Contracts\\MedicalStudyStatusContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalStudyStatusContract[]|string|string[] $status
 *
 * @return static
 *
 * @see https://schema.org/status
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 562,
        'endLine' => 565,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 577,
            'endLine' => 577,
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
        'startLine' => 577,
        'endLine' => 580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 592,
            'endLine' => 592,
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
        'startLine' => 592,
        'endLine' => 595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'aliasName' => NULL,
      ),
      'typicalTest' => 
      array (
        'name' => 'typicalTest',
        'parameters' => 
        array (
          'typicalTest' => 
          array (
            'name' => 'typicalTest',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 607,
            'endLine' => 607,
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
 * A medical test typically performed given this condition.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalTestContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalTestContract[] $typicalTest
 *
 * @return static
 *
 * @see https://schema.org/typicalTest
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 607,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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
            'startLine' => 621,
            'endLine' => 621,
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
        'startLine' => 621,
        'endLine' => 624,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'implementingClassName' => 'Spatie\\SchemaOrg\\VitalSign',
        'currentClassName' => 'Spatie\\SchemaOrg\\VitalSign',
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