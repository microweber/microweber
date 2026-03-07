<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/EducationalOccupationalProgram.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\EducationalOccupationalProgram
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b81e5f43cd2e2ffe553f8f2bb58857f039845a1f7f171202a2bda94167c455c0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/EducationalOccupationalProgram.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
    'shortName' => 'EducationalOccupationalProgram',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A program offered by an institution which determines the learning progress to
 * achieve an outcome, usually a credential like a degree or certificate. This
 * would define a discrete set of opportunities (e.g., job, courses) that
 * together constitute a program with a clear start, end, set of requirements,
 * and transition to a new occupational opportunity (e.g., a job), or sometimes
 * a higher educational opportunity (e.g., an advanced degree).
 *
 * @see https://schema.org/EducationalOccupationalProgram
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 634,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalProgramContract',
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
            'startLine' => 42,
            'endLine' => 42,
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
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 56,
            'endLine' => 56,
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
        'startLine' => 56,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'applicationDeadline' => 
      array (
        'name' => 'applicationDeadline',
        'parameters' => 
        array (
          'applicationDeadline' => 
          array (
            'name' => 'applicationDeadline',
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
 * The date on which the program stops collecting applications for the next
 * enrollment cycle. Flexible application deadlines (for example, a program
 * with rolling admissions) can be described in a textual string, rather
 * than as a DateTime.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[]|string|string[] $applicationDeadline
 *
 * @return static
 *
 * @see https://schema.org/applicationDeadline
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'applicationStartDate' => 
      array (
        'name' => 'applicationStartDate',
        'parameters' => 
        array (
          'applicationStartDate' => 
          array (
            'name' => 'applicationStartDate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
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
 * The date at which the program begins collecting applications for the next
 * enrollment cycle.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $applicationStartDate
 *
 * @return static
 *
 * @see https://schema.org/applicationStartDate
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'dayOfWeek' => 
      array (
        'name' => 'dayOfWeek',
        'parameters' => 
        array (
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
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
 * The day of the week for which these opening hours are valid.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DayOfWeekContract|\\Spatie\\SchemaOrg\\Contracts\\DayOfWeekContract[] $dayOfWeek
 *
 * @return static
 *
 * @see https://schema.org/dayOfWeek
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 120,
            'endLine' => 120,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'educationalCredentialAwarded' => 
      array (
        'name' => 'educationalCredentialAwarded',
        'parameters' => 
        array (
          'educationalCredentialAwarded' => 
          array (
            'name' => 'educationalCredentialAwarded',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
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
 * A description of the qualification, award, certificate, diploma or other
 * educational credential awarded as a consequence of successful completion
 * of this course or program.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract[]|string|string[] $educationalCredentialAwarded
 *
 * @return static
 *
 * @see https://schema.org/educationalCredentialAwarded
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 */',
        'startLine' => 154,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'educationalProgramMode' => 
      array (
        'name' => 'educationalProgramMode',
        'parameters' => 
        array (
          'educationalProgramMode' => 
          array (
            'name' => 'educationalProgramMode',
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
 * Similar to courseMode, the medium or means of delivery of the program as
 * a whole. The value may either be a text label (e.g. "online", "onsite" or
 * "blended"; "synchronous" or "asynchronous"; "full-time" or "part-time")
 * or a URL reference to a term from a controlled vocabulary (e.g.
 * https://ceds.ed.gov/element/001311#Asynchronous ).
 *
 * @param string|string[] $educationalProgramMode
 *
 * @return static
 *
 * @see https://schema.org/educationalProgramMode
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 190,
            'endLine' => 190,
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
        'startLine' => 190,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'financialAidEligible' => 
      array (
        'name' => 'financialAidEligible',
        'parameters' => 
        array (
          'financialAidEligible' => 
          array (
            'name' => 'financialAidEligible',
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
 * A financial aid type or program which students may use to pay for tuition
 * or fees associated with the program.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $financialAidEligible
 *
 * @return static
 *
 * @see https://schema.org/financialAidEligible
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2418
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'hasCourse' => 
      array (
        'name' => 'hasCourse',
        'parameters' => 
        array (
          'hasCourse' => 
          array (
            'name' => 'hasCourse',
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
 * A course or class that is one of the learning opportunities that
 * constitute an educational / occupational program. No information is
 * implied about whether the course is mandatory or optional; no guarantee
 * is implied about whether the course will be available to everyone on the
 * program.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\CourseContract|\\Spatie\\SchemaOrg\\Contracts\\CourseContract[] $hasCourse
 *
 * @return static
 *
 * @see https://schema.org/hasCourse
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2483
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 260,
            'endLine' => 260,
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
        'startLine' => 260,
        'endLine' => 263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 276,
            'endLine' => 276,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'maximumEnrollment' => 
      array (
        'name' => 'maximumEnrollment',
        'parameters' => 
        array (
          'maximumEnrollment' => 
          array (
            'name' => 'maximumEnrollment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 292,
            'endLine' => 292,
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
 * The maximum number of students who may be enrolled in the program.
 *
 * @param int|int[] $maximumEnrollment
 *
 * @return static
 *
 * @see https://schema.org/maximumEnrollment
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
 */',
        'startLine' => 292,
        'endLine' => 295,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 306,
            'endLine' => 306,
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
        'startLine' => 306,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'numberOfCredits' => 
      array (
        'name' => 'numberOfCredits',
        'parameters' => 
        array (
          'numberOfCredits' => 
          array (
            'name' => 'numberOfCredits',
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
 * The number of credits or units awarded by a Course or required to
 * complete an EducationalOccupationalProgram.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract|\\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract[]|int|int[] $numberOfCredits
 *
 * @return static
 *
 * @see https://schema.org/numberOfCredits
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 346,
            'endLine' => 346,
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
        'startLine' => 346,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'occupationalCredentialAwarded' => 
      array (
        'name' => 'occupationalCredentialAwarded',
        'parameters' => 
        array (
          'occupationalCredentialAwarded' => 
          array (
            'name' => 'occupationalCredentialAwarded',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 364,
            'endLine' => 364,
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
 * A description of the qualification, award, certificate, diploma or other
 * occupational credential awarded as a consequence of successful completion
 * of this course or program.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract[]|string|string[] $occupationalCredentialAwarded
 *
 * @return static
 *
 * @see https://schema.org/occupationalCredentialAwarded
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 */',
        'startLine' => 364,
        'endLine' => 367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'offers' => 
      array (
        'name' => 'offers',
        'parameters' => 
        array (
          'offers' => 
          array (
            'name' => 'offers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 386,
            'endLine' => 386,
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
 * An offer to provide this item&#x2014;for example, an offer to sell a
 * product, rent the DVD of a movie, perform a service, or give away tickets
 * to an event. Use [[businessFunction]] to indicate the kind of transaction
 * offered, i.e. sell, lease, etc. This property can also be used to
 * describe a [[Demand]]. While this property is listed as expected on a
 * number of common types, it can be used in others. In that case, using a
 * second type, such as Product or a subtype of Product, can clarify the
 * nature of the offer.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DemandContract|\\Spatie\\SchemaOrg\\Contracts\\DemandContract[]|\\Spatie\\SchemaOrg\\Contracts\\OfferContract|\\Spatie\\SchemaOrg\\Contracts\\OfferContract[] $offers
 *
 * @return static
 *
 * @see https://schema.org/offers
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 */',
        'startLine' => 386,
        'endLine' => 389,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 401,
            'endLine' => 401,
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
        'startLine' => 401,
        'endLine' => 404,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'programPrerequisites' => 
      array (
        'name' => 'programPrerequisites',
        'parameters' => 
        array (
          'programPrerequisites' => 
          array (
            'name' => 'programPrerequisites',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 417,
            'endLine' => 417,
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
 * Prerequisites for enrolling in the program.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\AlignmentObjectContract|\\Spatie\\SchemaOrg\\Contracts\\AlignmentObjectContract[]|\\Spatie\\SchemaOrg\\Contracts\\CourseContract|\\Spatie\\SchemaOrg\\Contracts\\CourseContract[]|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract|\\Spatie\\SchemaOrg\\Contracts\\EducationalOccupationalCredentialContract[]|string|string[] $programPrerequisites
 *
 * @return static
 *
 * @see https://schema.org/programPrerequisites
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2289
 */',
        'startLine' => 417,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'programType' => 
      array (
        'name' => 'programType',
        'parameters' => 
        array (
          'programType' => 
          array (
            'name' => 'programType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 434,
            'endLine' => 434,
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
 * The type of educational or occupational program. For example, classroom,
 * internship, alternance, etc.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract|\\Spatie\\SchemaOrg\\Contracts\\DefinedTermContract[]|string|string[] $programType
 *
 * @return static
 *
 * @see https://schema.org/programType
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2460
 */',
        'startLine' => 434,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 451,
            'endLine' => 451,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'salaryUponCompletion' => 
      array (
        'name' => 'salaryUponCompletion',
        'parameters' => 
        array (
          'salaryUponCompletion' => 
          array (
            'name' => 'salaryUponCompletion',
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
 * The expected salary upon completing the training.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountDistributionContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountDistributionContract[] $salaryUponCompletion
 *
 * @return static
 *
 * @see https://schema.org/salaryUponCompletion
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2289
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 499,
            'endLine' => 499,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 514,
            'endLine' => 514,
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
        'startLine' => 514,
        'endLine' => 517,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'termDuration' => 
      array (
        'name' => 'termDuration',
        'parameters' => 
        array (
          'termDuration' => 
          array (
            'name' => 'termDuration',
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
 * The amount of time in a term as defined by the institution. A term is a
 * length of time where students take one or more classes. Semesters and
 * quarters are common units for term.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DurationContract|\\Spatie\\SchemaOrg\\Contracts\\DurationContract[] $termDuration
 *
 * @return static
 *
 * @see https://schema.org/termDuration
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'termsPerYear' => 
      array (
        'name' => 'termsPerYear',
        'parameters' => 
        array (
          'termsPerYear' => 
          array (
            'name' => 'termsPerYear',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 551,
            'endLine' => 551,
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
 * The number of times terms of study are offered per year. Semesters and
 * quarters are common units for term. For example, if the student can only
 * take 2 semesters for the program in one year, then termsPerYear should be
 * 2.
 *
 * @param float|float[]|int|int[] $termsPerYear
 *
 * @return static
 *
 * @see https://schema.org/termsPerYear
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
 */',
        'startLine' => 551,
        'endLine' => 554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'timeOfDay' => 
      array (
        'name' => 'timeOfDay',
        'parameters' => 
        array (
          'timeOfDay' => 
          array (
            'name' => 'timeOfDay',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
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
 * The time of day the program normally runs. For example, "evenings".
 *
 * @param string|string[] $timeOfDay
 *
 * @return static
 *
 * @see https://schema.org/timeOfDay
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
 */',
        'startLine' => 567,
        'endLine' => 570,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'timeToComplete' => 
      array (
        'name' => 'timeToComplete',
        'parameters' => 
        array (
          'timeToComplete' => 
          array (
            'name' => 'timeToComplete',
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
 * The expected length of time to complete the program if attending
 * full-time.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\DurationContract|\\Spatie\\SchemaOrg\\Contracts\\DurationContract[] $timeToComplete
 *
 * @return static
 *
 * @see https://schema.org/timeToComplete
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2289
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'trainingSalary' => 
      array (
        'name' => 'trainingSalary',
        'parameters' => 
        array (
          'trainingSalary' => 
          array (
            'name' => 'trainingSalary',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 599,
            'endLine' => 599,
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
 * The estimated salary earned while in the program.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountDistributionContract|\\Spatie\\SchemaOrg\\Contracts\\MonetaryAmountDistributionContract[] $trainingSalary
 *
 * @return static
 *
 * @see https://schema.org/trainingSalary
 * @see https://pending.schema.org
 */',
        'startLine' => 599,
        'endLine' => 602,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'aliasName' => NULL,
      ),
      'typicalCreditsPerTerm' => 
      array (
        'name' => 'typicalCreditsPerTerm',
        'parameters' => 
        array (
          'typicalCreditsPerTerm' => 
          array (
            'name' => 'typicalCreditsPerTerm',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 616,
            'endLine' => 616,
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
 * The number of credits or units a full-time student would be expected to
 * take in 1 term however \'term\' is defined by the institution.
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract|\\Spatie\\SchemaOrg\\Contracts\\StructuredValueContract[]|int|int[] $typicalCreditsPerTerm
 *
 * @return static
 *
 * @see https://schema.org/typicalCreditsPerTerm
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2419
 */',
        'startLine' => 616,
        'endLine' => 619,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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
            'startLine' => 630,
            'endLine' => 630,
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
        'startLine' => 630,
        'endLine' => 633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'implementingClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
        'currentClassName' => 'Spatie\\SchemaOrg\\EducationalOccupationalProgram',
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