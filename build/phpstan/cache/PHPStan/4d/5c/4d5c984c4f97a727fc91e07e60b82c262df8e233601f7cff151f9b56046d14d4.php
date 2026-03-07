<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/ChemicalSubstance.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\ChemicalSubstance
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f909f8c35e35006b283d7deb5fc3fcdca91fb3945bcc257ddb8672b460a88b4a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/ChemicalSubstance.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
    'shortName' => 'ChemicalSubstance',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A chemical substance is \'a portion of matter of constant composition,
 * composed of molecular entities of the same type or of different types\'
 * (source:
 * [ChEBI:59999](https://www.ebi.ac.uk/chebi/searchId.do?chebiId=59999)).
 *
 * @see https://schema.org/ChemicalSubstance
 * @see https://pending.schema.org
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 479,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\ChemicalSubstanceContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'associatedDisease' => 
      array (
        'name' => 'associatedDisease',
        'parameters' => 
        array (
          'associatedDisease' => 
          array (
            'name' => 'associatedDisease',
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
 * Disease associated to this BioChemEntity. Such disease can be a
 * MedicalCondition or a URL. If you want to add an evidence supporting the
 * association, please use PropertyValue.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MedicalConditionContract|\\Spatie\\SchemaOrg\\Contracts\\MedicalConditionContract[]|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|string|string[] $associatedDisease
 *
 * @return static
 *
 * @see https://schema.org/associatedDisease
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/BioChemEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'bioChemInteraction' => 
      array (
        'name' => 'bioChemInteraction',
        'parameters' => 
        array (
          'bioChemInteraction' => 
          array (
            'name' => 'bioChemInteraction',
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
 * A BioChemEntity that is known to interact with this item.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract|\\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract[] $bioChemInteraction
 *
 * @return static
 *
 * @see https://schema.org/bioChemInteraction
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'bioChemSimilarity' => 
      array (
        'name' => 'bioChemSimilarity',
        'parameters' => 
        array (
          'bioChemSimilarity' => 
          array (
            'name' => 'bioChemSimilarity',
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
 * A similar BioChemEntity, e.g., obtained by fingerprint similarity
 * algorithms.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract|\\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract[] $bioChemSimilarity
 *
 * @return static
 *
 * @see https://schema.org/bioChemSimilarity
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'biologicalRole' => 
      array (
        'name' => 'biologicalRole',
        'parameters' => 
        array (
          'biologicalRole' => 
          array (
            'name' => 'biologicalRole',
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
 * A role played by the BioChemEntity within a biological context.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[] $biologicalRole
 *
 * @return static
 *
 * @see https://schema.org/biologicalRole
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'chemicalComposition' => 
      array (
        'name' => 'chemicalComposition',
        'parameters' => 
        array (
          'chemicalComposition' => 
          array (
            'name' => 'chemicalComposition',
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
 * The chemical composition describes the identity and relative ratio of the
 * chemical elements that make up the substance.
 *
 * @param string|string[] $chemicalComposition
 *
 * @return static
 *
 * @see https://schema.org/chemicalComposition
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/ChemicalSubstance
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'chemicalRole' => 
      array (
        'name' => 'chemicalRole',
        'parameters' => 
        array (
          'chemicalRole' => 
          array (
            'name' => 'chemicalRole',
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
 * A role played by the BioChemEntity within a chemical context.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[] $chemicalRole
 *
 * @return static
 *
 * @see https://schema.org/chemicalRole
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/ChemicalSubstance
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 167,
            'endLine' => 167,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 184,
            'endLine' => 184,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'hasBioChemEntityPart' => 
      array (
        'name' => 'hasBioChemEntityPart',
        'parameters' => 
        array (
          'hasBioChemEntityPart' => 
          array (
            'name' => 'hasBioChemEntityPart',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 218,
            'endLine' => 218,
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
 * Indicates a BioChemEntity that (in some sense) has this BioChemEntity as
 * a part.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract|\\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract[] $hasBioChemEntityPart
 *
 * @return static
 *
 * @see https://schema.org/hasBioChemEntityPart
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
 */',
        'startLine' => 218,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'hasMolecularFunction' => 
      array (
        'name' => 'hasMolecularFunction',
        'parameters' => 
        array (
          'hasMolecularFunction' => 
          array (
            'name' => 'hasMolecularFunction',
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
 * Molecular function performed by this BioChemEntity; please use
 * PropertyValue if you want to include any evidence.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|string|string[] $hasMolecularFunction
 *
 * @return static
 *
 * @see https://schema.org/hasMolecularFunction
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/BioChemEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'hasRepresentation' => 
      array (
        'name' => 'hasRepresentation',
        'parameters' => 
        array (
          'hasRepresentation' => 
          array (
            'name' => 'hasRepresentation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 252,
            'endLine' => 252,
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
 * A common representation such as a protein sequence or chemical structure
 * for this entity. For images use schema.org/image.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|string|string[] $hasRepresentation
 *
 * @return static
 *
 * @see https://schema.org/hasRepresentation
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
 */',
        'startLine' => 252,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 270,
            'endLine' => 270,
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
        'startLine' => 270,
        'endLine' => 273,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 285,
            'endLine' => 285,
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
        'startLine' => 285,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'isEncodedByBioChemEntity' => 
      array (
        'name' => 'isEncodedByBioChemEntity',
        'parameters' => 
        array (
          'isEncodedByBioChemEntity' => 
          array (
            'name' => 'isEncodedByBioChemEntity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 301,
            'endLine' => 301,
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
 * Another BioChemEntity encoding by this one.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\GeneContract|\\Spatie\\SchemaOrg\\Contracts\\GeneContract[] $isEncodedByBioChemEntity
 *
 * @return static
 *
 * @see https://schema.org/isEncodedByBioChemEntity
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/Gene
 */',
        'startLine' => 301,
        'endLine' => 304,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'isInvolvedInBiologicalProcess' => 
      array (
        'name' => 'isInvolvedInBiologicalProcess',
        'parameters' => 
        array (
          'isInvolvedInBiologicalProcess' => 
          array (
            'name' => 'isInvolvedInBiologicalProcess',
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
            'startColumn' => 51,
            'endColumn' => 80,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Biological process this BioChemEntity is involved in; please use
 * PropertyValue if you want to include any evidence.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|string|string[] $isInvolvedInBiologicalProcess
 *
 * @return static
 *
 * @see https://schema.org/isInvolvedInBiologicalProcess
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/BioChemEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'isLocatedInSubcellularLocation' => 
      array (
        'name' => 'isLocatedInSubcellularLocation',
        'parameters' => 
        array (
          'isLocatedInSubcellularLocation' => 
          array (
            'name' => 'isLocatedInSubcellularLocation',
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
            'startColumn' => 52,
            'endColumn' => 82,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Subcellular location where this BioChemEntity is located; please use
 * PropertyValue if you want to include any evidence.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract|\\Spatie\\SchemaOrg\\Contracts\\PropertyValueContract[]|string|string[] $isLocatedInSubcellularLocation
 *
 * @return static
 *
 * @see https://schema.org/isLocatedInSubcellularLocation
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/BioChemEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'isPartOfBioChemEntity' => 
      array (
        'name' => 'isPartOfBioChemEntity',
        'parameters' => 
        array (
          'isPartOfBioChemEntity' => 
          array (
            'name' => 'isPartOfBioChemEntity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 352,
            'endLine' => 352,
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
 * Indicates a BioChemEntity that is (in some sense) a part of this
 * BioChemEntity.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract|\\Spatie\\SchemaOrg\\Contracts\\BioChemEntityContract[] $isPartOfBioChemEntity
 *
 * @return static
 *
 * @see https://schema.org/isPartOfBioChemEntity
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
 */',
        'startLine' => 352,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 368,
            'endLine' => 368,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 382,
            'endLine' => 382,
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
        'startLine' => 382,
        'endLine' => 385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 397,
            'endLine' => 397,
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
        'startLine' => 397,
        'endLine' => 400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'potentialUse' => 
      array (
        'name' => 'potentialUse',
        'parameters' => 
        array (
          'potentialUse' => 
          array (
            'name' => 'potentialUse',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 413,
            'endLine' => 413,
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
 * Intended use of the BioChemEntity by humans.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[] $potentialUse
 *
 * @return static
 *
 * @see https://schema.org/potentialUse
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/ChemicalSubstance
 */',
        'startLine' => 413,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 429,
            'endLine' => 429,
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
        'startLine' => 429,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 444,
            'endLine' => 444,
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
        'startLine' => 444,
        'endLine' => 447,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'aliasName' => NULL,
      ),
      'taxonomicRange' => 
      array (
        'name' => 'taxonomicRange',
        'parameters' => 
        array (
          'taxonomicRange' => 
          array (
            'name' => 'taxonomicRange',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 461,
            'endLine' => 461,
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
 * The taxonomic grouping of the organism that expresses, encodes, or in
 * some way related to the BioChemEntity.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|\\Spatie\\SchemaOrg\\Contracts\\TaxonContract|\\Spatie\\SchemaOrg\\Contracts\\TaxonContract[]|string|string[] $taxonomicRange
 *
 * @return static
 *
 * @see https://schema.org/taxonomicRange
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org
 */',
        'startLine' => 461,
        'endLine' => 464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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
            'startLine' => 475,
            'endLine' => 475,
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
        'startLine' => 475,
        'endLine' => 478,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'implementingClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
        'currentClassName' => 'Spatie\\SchemaOrg\\ChemicalSubstance',
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