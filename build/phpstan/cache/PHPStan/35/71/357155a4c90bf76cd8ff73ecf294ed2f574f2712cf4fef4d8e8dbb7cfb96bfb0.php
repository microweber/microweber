<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MolecularEntity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\MolecularEntity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-73f64869a59de8d6066327b93a6fa444a9d53b75640856232bd89106cbbb051a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MolecularEntity.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\MolecularEntity',
    'shortName' => 'MolecularEntity',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Any constitutionally or isotopically distinct atom, molecule, ion, ion pair,
 * radical, radical ion, complex, conformer etc., identifiable as a separately
 * distinguishable entity.
 *
 * @see https://schema.org/MolecularEntity
 * @see https://pending.schema.org
 * @link http://bioschemas.org
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 588,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\MolecularEntityContract',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 136,
            'endLine' => 136,
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
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 150,
            'endLine' => 150,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 167,
            'endLine' => 167,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 184,
            'endLine' => 184,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 201,
            'endLine' => 201,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 235,
            'endLine' => 235,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 253,
            'endLine' => 253,
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
        'startLine' => 253,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 268,
            'endLine' => 268,
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
        'startLine' => 268,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'inChI' => 
      array (
        'name' => 'inChI',
        'parameters' => 
        array (
          'inChI' => 
          array (
            'name' => 'inChI',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 286,
            'endLine' => 286,
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
 * Non-proprietary identifier for molecular entity that can be used in
 * printed and electronic data sources thus enabling easier linking of
 * diverse data compilations.
 *
 * @param string|string[] $inChI
 *
 * @return static
 *
 * @see https://schema.org/inChI
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
 */',
        'startLine' => 286,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'inChIKey' => 
      array (
        'name' => 'inChIKey',
        'parameters' => 
        array (
          'inChIKey' => 
          array (
            'name' => 'inChIKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
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
 * InChIKey is a hashed version of the full InChI (using the SHA-256
 * algorithm).
 *
 * @param string|string[] $inChIKey
 *
 * @return static
 *
 * @see https://schema.org/inChIKey
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
 */',
        'startLine' => 303,
        'endLine' => 306,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 319,
            'endLine' => 319,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 336,
            'endLine' => 336,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 353,
            'endLine' => 353,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 370,
            'endLine' => 370,
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
        'startLine' => 370,
        'endLine' => 373,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'iupacName' => 
      array (
        'name' => 'iupacName',
        'parameters' => 
        array (
          'iupacName' => 
          array (
            'name' => 'iupacName',
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
 * Systematic method of naming chemical compounds as recommended by the
 * International Union of Pure and Applied Chemistry (IUPAC).
 *
 * @param string|string[] $iupacName
 *
 * @return static
 *
 * @see https://schema.org/iupacName
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 403,
            'endLine' => 403,
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
        'startLine' => 403,
        'endLine' => 406,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'molecularFormula' => 
      array (
        'name' => 'molecularFormula',
        'parameters' => 
        array (
          'molecularFormula' => 
          array (
            'name' => 'molecularFormula',
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
 * The empirical formula is the simplest whole number ratio of all the atoms
 * in a molecule.
 *
 * @param string|string[] $molecularFormula
 *
 * @return static
 *
 * @see https://schema.org/molecularFormula
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'molecularWeight' => 
      array (
        'name' => 'molecularWeight',
        'parameters' => 
        array (
          'molecularWeight' => 
          array (
            'name' => 'molecularWeight',
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
 * This is the molecular weight of the entity being described, not of the
 * parent. Units should be included in the form \'<Number> <unit>\', for
 * example \'12 amu\' or as \'<QuantitativeValue>.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|string|string[] $molecularWeight
 *
 * @return static
 *
 * @see https://schema.org/molecularWeight
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'monoisotopicMolecularWeight' => 
      array (
        'name' => 'monoisotopicMolecularWeight',
        'parameters' => 
        array (
          'monoisotopicMolecularWeight' => 
          array (
            'name' => 'monoisotopicMolecularWeight',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 458,
            'endLine' => 458,
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
 * The monoisotopic mass is the sum of the masses of the atoms in a molecule
 * using the unbound, ground-state, rest mass of the principal (most
 * abundant) isotope for each element instead of the isotopic average mass.
 * Please include the units in the form \'<Number> <unit>\', for example
 * \'770.230488 g/mol\' or as \'<QuantitativeValue>.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract|\\Spatie\\SchemaOrg\\Contracts\\QuantitativeValueContract[]|string|string[] $monoisotopicMolecularWeight
 *
 * @return static
 *
 * @see https://schema.org/monoisotopicMolecularWeight
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
 */',
        'startLine' => 458,
        'endLine' => 461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 472,
            'endLine' => 472,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 487,
            'endLine' => 487,
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
        'startLine' => 487,
        'endLine' => 490,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 503,
            'endLine' => 503,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 519,
            'endLine' => 519,
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
        'startLine' => 519,
        'endLine' => 522,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'aliasName' => NULL,
      ),
      'smiles' => 
      array (
        'name' => 'smiles',
        'parameters' => 
        array (
          'smiles' => 
          array (
            'name' => 'smiles',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 538,
            'endLine' => 538,
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
 * A specification in form of a line notation for describing the structure
 * of chemical species using short ASCII strings.  Double bond
 * stereochemistry \\ indicators may need to be escaped in the string in
 * formats where the backslash is an escape character.
 *
 * @param string|string[] $smiles
 *
 * @return static
 *
 * @see https://schema.org/smiles
 * @see https://pending.schema.org
 * @link http://www.bioschemas.org/MolecularEntity
 */',
        'startLine' => 538,
        'endLine' => 541,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 553,
            'endLine' => 553,
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
        'startLine' => 553,
        'endLine' => 556,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 570,
            'endLine' => 570,
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
        'startLine' => 570,
        'endLine' => 573,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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
            'startLine' => 584,
            'endLine' => 584,
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
        'startLine' => 584,
        'endLine' => 587,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
        'currentClassName' => 'Spatie\\SchemaOrg\\MolecularEntity',
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