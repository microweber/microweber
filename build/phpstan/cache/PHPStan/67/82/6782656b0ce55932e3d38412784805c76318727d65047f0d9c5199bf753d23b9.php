<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/PhysicalTherapy.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\PhysicalTherapy
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d18ba01918d88272f5f4d4c3a1a5752cca181110b8deda9ca64fcac2f1b04b8f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/PhysicalTherapy.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
    'shortName' => 'PhysicalTherapy',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A process of progressive physical care and rehabilitation aimed at improving
 * a health condition.
 *
 * @see https://schema.org/PhysicalTherapy
 * @see https://health-lifesci.schema.org
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 531,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\PhysicalTherapyContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\MedicalProcedureContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract',
      4 => 'Spatie\\SchemaOrg\\Contracts\\TherapeuticProcedureContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'adverseOutcome' => 
      array (
        'name' => 'adverseOutcome',
        'parameters' => 
        array (
          'adverseOutcome' => 
          array (
            'name' => 'adverseOutcome',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 59,
            'endLine' => 59,
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
 * A possible complication and/or side effect of this therapy. If it is
 * known that an adverse outcome is serious (resulting in death, disability,
 * or permanent damage; requiring hospitalization; or otherwise
 * life-threatening or requiring immediate medical attention), tag it as a
 * seriousAdverseOutcome instead.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract[] $adverseOutcome
 *
 * @return static
 *
 * @see https://schema.org/adverseOutcome
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 73,
            'endLine' => 73,
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
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'bodyLocation' => 
      array (
        'name' => 'bodyLocation',
        'parameters' => 
        array (
          'bodyLocation' => 
          array (
            'name' => 'bodyLocation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
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
 * Location in the body of the anatomical structure.
 *
 * @param string|string[] $bodyLocation
 *
 * @return static
 *
 * @see https://schema.org/bodyLocation
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 104,
            'endLine' => 104,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'contraindication' => 
      array (
        'name' => 'contraindication',
        'parameters' => 
        array (
          'contraindication' => 
          array (
            'name' => 'contraindication',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
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
 * A contraindication for this therapy.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalContraindicationContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalContraindicationContract[]|string|string[] $contraindication
 *
 * @return static
 *
 * @see https://schema.org/contraindication
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 133,
            'endLine' => 133,
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
        'startLine' => 133,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 150,
            'endLine' => 150,
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
        'startLine' => 150,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'doseSchedule' => 
      array (
        'name' => 'doseSchedule',
        'parameters' => 
        array (
          'doseSchedule' => 
          array (
            'name' => 'doseSchedule',
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
 * A dosing schedule for the drug for a given population, either observed,
 * recommended, or maximum dose based on the type used.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DoseScheduleContract|\\Spatie\\SchemaOrg\\Contracts\\DoseScheduleContract[] $doseSchedule
 *
 * @return static
 *
 * @see https://schema.org/doseSchedule
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 181,
            'endLine' => 181,
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
        'startLine' => 181,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'duplicateTherapy' => 
      array (
        'name' => 'duplicateTherapy',
        'parameters' => 
        array (
          'duplicateTherapy' => 
          array (
            'name' => 'duplicateTherapy',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 196,
            'endLine' => 196,
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
 * A therapy that duplicates or overlaps this one.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalTherapyContract[] $duplicateTherapy
 *
 * @return static
 *
 * @see https://schema.org/duplicateTherapy
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 196,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'followup' => 
      array (
        'name' => 'followup',
        'parameters' => 
        array (
          'followup' => 
          array (
            'name' => 'followup',
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
 * Typical or recommended followup care after the procedure is performed.
 *
 * @param string|string[] $followup
 *
 * @return static
 *
 * @see https://schema.org/followup
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 228,
            'endLine' => 228,
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
        'startLine' => 228,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 243,
            'endLine' => 243,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'howPerformed' => 
      array (
        'name' => 'howPerformed',
        'parameters' => 
        array (
          'howPerformed' => 
          array (
            'name' => 'howPerformed',
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
 * How the procedure is performed.
 *
 * @param string|string[] $howPerformed
 *
 * @return static
 *
 * @see https://schema.org/howPerformed
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 276,
            'endLine' => 276,
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
        'startLine' => 276,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 291,
            'endLine' => 291,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 307,
            'endLine' => 307,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 323,
            'endLine' => 323,
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
        'startLine' => 323,
        'endLine' => 326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 339,
            'endLine' => 339,
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
        'startLine' => 339,
        'endLine' => 342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 353,
            'endLine' => 353,
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
        'startLine' => 353,
        'endLine' => 356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 368,
            'endLine' => 368,
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
        'startLine' => 368,
        'endLine' => 371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'preparation' => 
      array (
        'name' => 'preparation',
        'parameters' => 
        array (
          'preparation' => 
          array (
            'name' => 'preparation',
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
 * Typical preparation that a patient must undergo before having the
 * procedure performed.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract[]|string|string[] $preparation
 *
 * @return static
 *
 * @see https://schema.org/preparation
 * @see https://health-lifesci.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'procedureType' => 
      array (
        'name' => 'procedureType',
        'parameters' => 
        array (
          'procedureType' => 
          array (
            'name' => 'procedureType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
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
 * The type of procedure, for example Surgical, Noninvasive, or
 * Percutaneous.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalProcedureTypeContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalProcedureTypeContract[] $procedureType
 *
 * @return static
 *
 * @see https://schema.org/procedureType
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 400,
        'endLine' => 403,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 416,
            'endLine' => 416,
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
        'startLine' => 416,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 431,
            'endLine' => 431,
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
        'startLine' => 431,
        'endLine' => 434,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 447,
            'endLine' => 447,
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
        'startLine' => 447,
        'endLine' => 450,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'aliasName' => NULL,
      ),
      'seriousAdverseOutcome' => 
      array (
        'name' => 'seriousAdverseOutcome',
        'parameters' => 
        array (
          'seriousAdverseOutcome' => 
          array (
            'name' => 'seriousAdverseOutcome',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 468,
            'endLine' => 468,
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
 * A possible serious complication and/or serious side effect of this
 * therapy. Serious adverse outcomes include those that are
 * life-threatening; result in death, disability, or permanent damage;
 * require hospitalization or prolong existing hospitalization; cause
 * congenital anomalies or birth defects; or jeopardize the patient and may
 * require medical or surgical intervention to prevent one of the outcomes
 * in this definition.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalEntityContract[] $seriousAdverseOutcome
 *
 * @return static
 *
 * @see https://schema.org/seriousAdverseOutcome
 * @see https://health-lifesci.schema.org
 */',
        'startLine' => 468,
        'endLine' => 471,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 483,
            'endLine' => 483,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 498,
            'endLine' => 498,
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
        'startLine' => 498,
        'endLine' => 501,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 513,
            'endLine' => 513,
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
        'startLine' => 513,
        'endLine' => 516,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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
            'startLine' => 527,
            'endLine' => 527,
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
        'startLine' => 527,
        'endLine' => 530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
        'currentClassName' => 'Spatie\\SchemaOrg\\PhysicalTherapy',
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