<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/JobPosting.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\JobPosting
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4d1d9e87d93d0ef2d394356b5e6c9a569fcfb25ae5191663e772ec801d8df179-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\JobPosting',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/JobPosting.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\JobPosting',
    'shortName' => 'JobPosting',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A listing that describes a job opening in a certain organization.
 *
 * @see https://schema.org/JobPosting
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 812,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\JobPostingContract',
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
            'startLine' => 35,
            'endLine' => 35,
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
        'startLine' => 35,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 49,
            'endLine' => 49,
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
        'startLine' => 49,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'applicantLocationRequirements' => 
      array (
        'name' => 'applicantLocationRequirements',
        'parameters' => 
        array (
          'applicantLocationRequirements' => 
          array (
            'name' => 'applicantLocationRequirements',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
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
 * The location(s) applicants can apply from. This is usually used for
 * telecommuting jobs where the applicant does not need to be in a physical
 * office. Note: This should not be used for citizenship or work visa
 * requirements.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract|\\Spatie\\SchemaOrg\\Contracts\\AdministrativeAreaContract[] $applicantLocationRequirements
 *
 * @return static
 *
 * @see https://schema.org/applicantLocationRequirements
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2083
 */',
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'applicationContact' => 
      array (
        'name' => 'applicationContact',
        'parameters' => 
        array (
          'applicationContact' => 
          array (
            'name' => 'applicationContact',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
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
 * Contact details for further information relevant to this job posting.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\ContactPointContract|\\Spatie\\SchemaOrg\\Contracts\\ContactPointContract[] $applicationContact
 *
 * @return static
 *
 * @see https://schema.org/applicationContact
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2396
 */',
        'startLine' => 84,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'baseSalary' => 
      array (
        'name' => 'baseSalary',
        'parameters' => 
        array (
          'baseSalary' => 
          array (
            'name' => 'baseSalary',
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
 * The base salary of the job or of an employee in an EmployeeRole.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[]|\\Spatie\\SchemaOrg\\Contracts\\PriceSpecificationContract|\\Spatie\\SchemaOrg\\Contracts\\PriceSpecificationContract[]|float|float[]|int|int[] $baseSalary
 *
 * @return static
 *
 * @see https://schema.org/baseSalary
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'benefits' => 
      array (
        'name' => 'benefits',
        'parameters' => 
        array (
          'benefits' => 
          array (
            'name' => 'benefits',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
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
 * Description of benefits associated with the job.
 *
 * @param string|string[] $benefits
 *
 * @return static
 *
 * @see https://schema.org/benefits
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'datePosted' => 
      array (
        'name' => 'datePosted',
        'parameters' => 
        array (
          'datePosted' => 
          array (
            'name' => 'datePosted',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 126,
            'endLine' => 126,
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
 * Publication date of an online listing.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $datePosted
 *
 * @return static
 *
 * @see https://schema.org/datePosted
 */',
        'startLine' => 126,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 140,
            'endLine' => 140,
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
        'startLine' => 140,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'directApply' => 
      array (
        'name' => 'directApply',
        'parameters' => 
        array (
          'directApply' => 
          array (
            'name' => 'directApply',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
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
 * Indicates whether an [[url]] that is associated with a [[JobPosting]]
 * enables direct application for the job, via the posting website. A job
 * posting is considered to have directApply of [[True]] if an application
 * process for the specified job can be directly initiated via the url(s)
 * given (noting that e.g. multiple internet domains might nevertheless be
 * involved at an implementation level). A value of [[False]] is appropriate
 * if there is no clear path to applying directly online for the specified
 * job, navigating directly from the JobPosting url(s) supplied.
 *
 * @param bool|bool[] $directApply
 *
 * @return static
 *
 * @see https://schema.org/directApply
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2907
 */',
        'startLine' => 163,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 180,
            'endLine' => 180,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'educationRequirements' => 
      array (
        'name' => 'educationRequirements',
        'parameters' => 
        array (
          'educationRequirements' => 
          array (
            'name' => 'educationRequirements',
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
 * Educational background needed for the position or Occupation.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract[]|string|string[] $educationRequirements
 *
 * @return static
 *
 * @see https://schema.org/educationRequirements
 * @see https://pending.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'eligibilityToWorkRequirement' => 
      array (
        'name' => 'eligibilityToWorkRequirement',
        'parameters' => 
        array (
          'eligibilityToWorkRequirement' => 
          array (
            'name' => 'eligibilityToWorkRequirement',
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
 * The legal requirements such as citizenship, visa and other documentation
 * required for an applicant to this job.
 *
 * @param string|string[] $eligibilityToWorkRequirement
 *
 * @return static
 *
 * @see https://schema.org/eligibilityToWorkRequirement
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2384
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'employerOverview' => 
      array (
        'name' => 'employerOverview',
        'parameters' => 
        array (
          'employerOverview' => 
          array (
            'name' => 'employerOverview',
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
 * A description of the employer, career opportunities and work environment
 * for this position.
 *
 * @param string|string[] $employerOverview
 *
 * @return static
 *
 * @see https://schema.org/employerOverview
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2396
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'employmentType' => 
      array (
        'name' => 'employmentType',
        'parameters' => 
        array (
          'employmentType' => 
          array (
            'name' => 'employmentType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 244,
            'endLine' => 244,
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
 * Type of employment (e.g. full-time, part-time, contract, temporary,
 * seasonal, internship).
 *
 * @param string|string[] $employmentType
 *
 * @return static
 *
 * @see https://schema.org/employmentType
 */',
        'startLine' => 244,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'employmentUnit' => 
      array (
        'name' => 'employmentUnit',
        'parameters' => 
        array (
          'employmentUnit' => 
          array (
            'name' => 'employmentUnit',
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
 * Indicates the department, unit and/or facility where the employee reports
 * and/or in which the job is to be performed.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[] $employmentUnit
 *
 * @return static
 *
 * @see https://schema.org/employmentUnit
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2296
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'estimatedSalary' => 
      array (
        'name' => 'estimatedSalary',
        'parameters' => 
        array (
          'estimatedSalary' => 
          array (
            'name' => 'estimatedSalary',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
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
 * An estimated salary for a job posting or occupation, based on a variety
 * of variables including, but not limited to industry, job title, and
 * location. Estimated salaries  are often computed by outside organizations
 * rather than the hiring organization, who may not have committed to the
 * estimated value.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountDistributionContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountDistributionContract[]|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountContract[]|float|float[]|int|int[] $estimatedSalary
 *
 * @return static
 *
 * @see https://schema.org/estimatedSalary
 * @link https://github.com/schemaorg/schemaorg/issues/1698
 */',
        'startLine' => 280,
        'endLine' => 283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'experienceInPlaceOfEducation' => 
      array (
        'name' => 'experienceInPlaceOfEducation',
        'parameters' => 
        array (
          'experienceInPlaceOfEducation' => 
          array (
            'name' => 'experienceInPlaceOfEducation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 300,
            'endLine' => 300,
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
 * Indicates whether a [[JobPosting]] will accept experience (as indicated
 * by [[OccupationalExperienceRequirements]]) in place of its formal
 * educational qualifications (as indicated by [[educationRequirements]]).
 * If true, indicates that satisfying one of these requirements is
 * sufficient.
 *
 * @param bool|bool[] $experienceInPlaceOfEducation
 *
 * @return static
 *
 * @see https://schema.org/experienceInPlaceOfEducation
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2681
 */',
        'startLine' => 300,
        'endLine' => 303,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'experienceRequirements' => 
      array (
        'name' => 'experienceRequirements',
        'parameters' => 
        array (
          'experienceRequirements' => 
          array (
            'name' => 'experienceRequirements',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 316,
            'endLine' => 316,
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
 * Description of skills and experience needed for the position or
 * Occupation.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OccupationalExperienceRequirementsContract|\\Spatie\\SchemaOrg\\Contracts\\OccupationalExperienceRequirementsContract[]|string|string[] $experienceRequirements
 *
 * @return static
 *
 * @see https://schema.org/experienceRequirements
 * @link https://github.com/schemaorg/schemaorg/issues/1698
 */',
        'startLine' => 316,
        'endLine' => 319,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'hiringOrganization' => 
      array (
        'name' => 'hiringOrganization',
        'parameters' => 
        array (
          'hiringOrganization' => 
          array (
            'name' => 'hiringOrganization',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 330,
            'endLine' => 330,
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
 * Organization or Person offering the job position.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OrganizationContract|\\Spatie\\SchemaOrg\\Contracts\\OrganizationContract[]|\\Spatie\\SchemaOrg\\Contracts\\PersonContract|\\Spatie\\SchemaOrg\\Contracts\\PersonContract[] $hiringOrganization
 *
 * @return static
 *
 * @see https://schema.org/hiringOrganization
 */',
        'startLine' => 330,
        'endLine' => 333,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 348,
            'endLine' => 348,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 363,
            'endLine' => 363,
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
        'startLine' => 363,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'incentiveCompensation' => 
      array (
        'name' => 'incentiveCompensation',
        'parameters' => 
        array (
          'incentiveCompensation' => 
          array (
            'name' => 'incentiveCompensation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 377,
            'endLine' => 377,
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
 * Description of bonus and commission compensation aspects of the job.
 *
 * @param string|string[] $incentiveCompensation
 *
 * @return static
 *
 * @see https://schema.org/incentiveCompensation
 */',
        'startLine' => 377,
        'endLine' => 380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'incentives' => 
      array (
        'name' => 'incentives',
        'parameters' => 
        array (
          'incentives' => 
          array (
            'name' => 'incentives',
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
 * Description of bonus and commission compensation aspects of the job.
 *
 * @param string|string[] $incentives
 *
 * @return static
 *
 * @see https://schema.org/incentives
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'industry' => 
      array (
        'name' => 'industry',
        'parameters' => 
        array (
          'industry' => 
          array (
            'name' => 'industry',
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
 * The industry associated with the job position.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $industry
 *
 * @return static
 *
 * @see https://schema.org/industry
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'jobBenefits' => 
      array (
        'name' => 'jobBenefits',
        'parameters' => 
        array (
          'jobBenefits' => 
          array (
            'name' => 'jobBenefits',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 419,
            'endLine' => 419,
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
 * Description of benefits associated with the job.
 *
 * @param string|string[] $jobBenefits
 *
 * @return static
 *
 * @see https://schema.org/jobBenefits
 */',
        'startLine' => 419,
        'endLine' => 422,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'jobImmediateStart' => 
      array (
        'name' => 'jobImmediateStart',
        'parameters' => 
        array (
          'jobImmediateStart' => 
          array (
            'name' => 'jobImmediateStart',
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
 * An indicator as to whether a position is available for an immediate
 * start.
 *
 * @param bool|bool[] $jobImmediateStart
 *
 * @return static
 *
 * @see https://schema.org/jobImmediateStart
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2244
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'jobLocation' => 
      array (
        'name' => 'jobLocation',
        'parameters' => 
        array (
          'jobLocation' => 
          array (
            'name' => 'jobLocation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 451,
            'endLine' => 451,
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
 * A (typically single) geographic location associated with the job
 * position.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\PlaceContract|\\Spatie\\SchemaOrg\\Contracts\\PlaceContract[] $jobLocation
 *
 * @return static
 *
 * @see https://schema.org/jobLocation
 */',
        'startLine' => 451,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'jobLocationType' => 
      array (
        'name' => 'jobLocationType',
        'parameters' => 
        array (
          'jobLocationType' => 
          array (
            'name' => 'jobLocationType',
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
 * A description of the job location (e.g. TELECOMMUTE for telecommute
 * jobs).
 *
 * @param string|string[] $jobLocationType
 *
 * @return static
 *
 * @see https://schema.org/jobLocationType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/1591
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'jobStartDate' => 
      array (
        'name' => 'jobStartDate',
        'parameters' => 
        array (
          'jobStartDate' => 
          array (
            'name' => 'jobStartDate',
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
 * The date on which a successful applicant for this job would be expected
 * to start work. Choose a specific date in the future or use the
 * jobImmediateStart property to indicate the position is to be filled as
 * soon as possible.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[]|string|string[] $jobStartDate
 *
 * @return static
 *
 * @see https://schema.org/jobStartDate
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2244
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 503,
            'endLine' => 503,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 517,
            'endLine' => 517,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'occupationalCategory' => 
      array (
        'name' => 'occupationalCategory',
        'parameters' => 
        array (
          'occupationalCategory' => 
          array (
            'name' => 'occupationalCategory',
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
 * A category describing the job, preferably using a term from a taxonomy
 * such as [BLS O*NET-SOC](http://www.onetcenter.org/taxonomy.html),
 * [ISCO-08](https://www.ilo.org/public/english/bureau/stat/isco/isco08/) or
 * similar, with the property repeated for each applicable value. Ideally
 * the taxonomy should be identified, and both the textual label and formal
 * code for the category should be provided.
 *
 * Note: for historical reasons, any textual label and formal code provided
 * as a literal may be assumed to be from O*NET-SOC.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CategoryCodeContract|\\Spatie\\SchemaOrg\\Contracts\\CategoryCodeContract[]|string|string[] $occupationalCategory
 *
 * @return static
 *
 * @see https://schema.org/occupationalCategory
 * @see https://pending.schema.org
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'physicalRequirement' => 
      array (
        'name' => 'physicalRequirement',
        'parameters' => 
        array (
          'physicalRequirement' => 
          array (
            'name' => 'physicalRequirement',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 559,
            'endLine' => 559,
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
 * A description of the types of physical activity associated with the job.
 * Defined terms such as those in O*net may be used, but note that there is
 * no way to specify the level of ability as well as its nature when using a
 * defined term.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $physicalRequirement
 *
 * @return static
 *
 * @see https://schema.org/physicalRequirement
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2384
 */',
        'startLine' => 559,
        'endLine' => 562,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 574,
            'endLine' => 574,
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
        'startLine' => 574,
        'endLine' => 577,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'qualifications' => 
      array (
        'name' => 'qualifications',
        'parameters' => 
        array (
          'qualifications' => 
          array (
            'name' => 'qualifications',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 589,
            'endLine' => 589,
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
 * Specific qualifications required for this role or Occupation.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract[]|string|string[] $qualifications
 *
 * @return static
 *
 * @see https://schema.org/qualifications
 * @see https://pending.schema.org
 */',
        'startLine' => 589,
        'endLine' => 592,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'relevantOccupation' => 
      array (
        'name' => 'relevantOccupation',
        'parameters' => 
        array (
          'relevantOccupation' => 
          array (
            'name' => 'relevantOccupation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 604,
            'endLine' => 604,
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
 * The Occupation for the JobPosting.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\OccupationContract|\\Spatie\\SchemaOrg\\Contracts\\OccupationContract[] $relevantOccupation
 *
 * @return static
 *
 * @see https://schema.org/relevantOccupation
 * @link https://github.com/schemaorg/schemaorg/issues/1698
 */',
        'startLine' => 604,
        'endLine' => 607,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'responsibilities' => 
      array (
        'name' => 'responsibilities',
        'parameters' => 
        array (
          'responsibilities' => 
          array (
            'name' => 'responsibilities',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 619,
            'endLine' => 619,
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
 * Responsibilities associated with this role or Occupation.
 *
 * @param string|string[] $responsibilities
 *
 * @return static
 *
 * @see https://schema.org/responsibilities
 * @link https://github.com/schemaorg/schemaorg/issues/1698
 */',
        'startLine' => 619,
        'endLine' => 622,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'salaryCurrency' => 
      array (
        'name' => 'salaryCurrency',
        'parameters' => 
        array (
          'salaryCurrency' => 
          array (
            'name' => 'salaryCurrency',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 635,
            'endLine' => 635,
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
 * The currency (coded using [ISO
 * 4217](http://en.wikipedia.org/wiki/ISO_4217)) used for the main salary
 * information in this job posting or for this employee.
 *
 * @param string|string[] $salaryCurrency
 *
 * @return static
 *
 * @see https://schema.org/salaryCurrency
 */',
        'startLine' => 635,
        'endLine' => 638,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 651,
            'endLine' => 651,
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
        'startLine' => 651,
        'endLine' => 654,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'securityClearanceRequirement' => 
      array (
        'name' => 'securityClearanceRequirement',
        'parameters' => 
        array (
          'securityClearanceRequirement' => 
          array (
            'name' => 'securityClearanceRequirement',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 667,
            'endLine' => 667,
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
 * A description of any security clearance requirements of the job.
 *
 * @param string|string[] $securityClearanceRequirement
 *
 * @return static
 *
 * @see https://schema.org/securityClearanceRequirement
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2384
 */',
        'startLine' => 667,
        'endLine' => 670,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'sensoryRequirement' => 
      array (
        'name' => 'sensoryRequirement',
        'parameters' => 
        array (
          'sensoryRequirement' => 
          array (
            'name' => 'sensoryRequirement',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 686,
            'endLine' => 686,
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
 * A description of any sensory requirements and levels necessary to
 * function on the job, including hearing and vision. Defined terms such as
 * those in O*net may be used, but note that there is no way to specify the
 * level of ability as well as its nature when using a defined term.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $sensoryRequirement
 *
 * @return static
 *
 * @see https://schema.org/sensoryRequirement
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2384
 */',
        'startLine' => 686,
        'endLine' => 689,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'skills' => 
      array (
        'name' => 'skills',
        'parameters' => 
        array (
          'skills' => 
          array (
            'name' => 'skills',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 703,
            'endLine' => 703,
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
 * A statement of knowledge, skill, ability, task or any other assertion
 * expressing a competency that is either claimed by a person, an
 * organization or desired or required to fulfill a role or to work in an
 * occupation.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $skills
 *
 * @return static
 *
 * @see https://schema.org/skills
 */',
        'startLine' => 703,
        'endLine' => 706,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'specialCommitments' => 
      array (
        'name' => 'specialCommitments',
        'parameters' => 
        array (
          'specialCommitments' => 
          array (
            'name' => 'specialCommitments',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 718,
            'endLine' => 718,
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
 * Any special commitments associated with this job posting. Valid entries
 * include VeteranCommit, MilitarySpouseCommit, etc.
 *
 * @param string|string[] $specialCommitments
 *
 * @return static
 *
 * @see https://schema.org/specialCommitments
 */',
        'startLine' => 718,
        'endLine' => 721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 733,
            'endLine' => 733,
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
        'startLine' => 733,
        'endLine' => 736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'title' => 
      array (
        'name' => 'title',
        'parameters' => 
        array (
          'title' => 
          array (
            'name' => 'title',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 747,
            'endLine' => 747,
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
 * The title of the job.
 *
 * @param string|string[] $title
 *
 * @return static
 *
 * @see https://schema.org/title
 */',
        'startLine' => 747,
        'endLine' => 750,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'totalJobOpenings' => 
      array (
        'name' => 'totalJobOpenings',
        'parameters' => 
        array (
          'totalJobOpenings' => 
          array (
            'name' => 'totalJobOpenings',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 764,
            'endLine' => 764,
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
 * The number of positions open for this job posting. Use a positive
 * integer. Do not use if the number of positions is unclear or not known.
 *
 * @param int|int[] $totalJobOpenings
 *
 * @return static
 *
 * @see https://schema.org/totalJobOpenings
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2329
 */',
        'startLine' => 764,
        'endLine' => 767,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 778,
            'endLine' => 778,
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
        'startLine' => 778,
        'endLine' => 781,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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
            'startLine' => 793,
            'endLine' => 793,
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
        'startLine' => 793,
        'endLine' => 796,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'aliasName' => NULL,
      ),
      'workHours' => 
      array (
        'name' => 'workHours',
        'parameters' => 
        array (
          'workHours' => 
          array (
            'name' => 'workHours',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 808,
            'endLine' => 808,
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
 * The typical working hours for this job (e.g. 1st shift, night shift,
 * 8am-5pm).
 *
 * @param string|string[] $workHours
 *
 * @return static
 *
 * @see https://schema.org/workHours
 */',
        'startLine' => 808,
        'endLine' => 811,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'implementingClassName' => 'Spatie\\SchemaOrg\\JobPosting',
        'currentClassName' => 'Spatie\\SchemaOrg\\JobPosting',
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