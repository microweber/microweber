<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/CDCPMDRecord.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\CDCPMDRecord
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ea02fab857d8edf3dcc4a9f33b40d082a2d4418714a820e7e73c15e07f5d9534-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/CDCPMDRecord.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
    'shortName' => 'CDCPMDRecord',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A CDCPMDRecord is a data structure representing a record in a CDC tabular
 * data format
 *       used for hospital data reporting. See
 * [documentation](/docs/cdc-covid.html) for details, and the linked CDC
 * materials for authoritative
 *       definitions used as the source here.
 *
 * @see https://schema.org/CDCPMDRecord
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 506,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\CDCPMDRecordContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\StructuredValueContract',
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
            'startLine' => 43,
            'endLine' => 43,
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
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 57,
            'endLine' => 57,
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
        'startLine' => 57,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdCollectionDate' => 
      array (
        'name' => 'cvdCollectionDate',
        'parameters' => 
        array (
          'cvdCollectionDate' => 
          array (
            'name' => 'cvdCollectionDate',
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
 * collectiondate - Date for which patient counts are reported.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[]|string|string[] $cvdCollectionDate
 *
 * @return static
 *
 * @see https://schema.org/cvdCollectionDate
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdFacilityCounty' => 
      array (
        'name' => 'cvdFacilityCounty',
        'parameters' => 
        array (
          'cvdFacilityCounty' => 
          array (
            'name' => 'cvdFacilityCounty',
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
 * Name of the County of the NHSN facility that this data record applies to.
 * Use [[cvdFacilityId]] to identify the facility. To provide other details,
 * [[healthcareReportingData]] can be used on a [[Hospital]] entry.
 *
 * @param string|string[] $cvdFacilityCounty
 *
 * @return static
 *
 * @see https://schema.org/cvdFacilityCounty
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdFacilityId' => 
      array (
        'name' => 'cvdFacilityId',
        'parameters' => 
        array (
          'cvdFacilityId' => 
          array (
            'name' => 'cvdFacilityId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
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
 * Identifier of the NHSN facility that this data record applies to. Use
 * [[cvdFacilityCounty]] to indicate the county. To provide other details,
 * [[healthcareReportingData]] can be used on a [[Hospital]] entry.
 *
 * @param string|string[] $cvdFacilityId
 *
 * @return static
 *
 * @see https://schema.org/cvdFacilityId
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 */',
        'startLine' => 109,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumBeds' => 
      array (
        'name' => 'cvdNumBeds',
        'parameters' => 
        array (
          'cvdNumBeds' => 
          array (
            'name' => 'cvdNumBeds',
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
 * numbeds - HOSPITAL INPATIENT BEDS: Inpatient beds, including all staffed,
 * licensed, and overflow (surge) beds used for inpatients.
 *
 * @param float|float[]|int|int[] $cvdNumBeds
 *
 * @return static
 *
 * @see https://schema.org/cvdNumBeds
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumBedsOcc' => 
      array (
        'name' => 'cvdNumBedsOcc',
        'parameters' => 
        array (
          'cvdNumBedsOcc' => 
          array (
            'name' => 'cvdNumBedsOcc',
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
 * numbedsocc - HOSPITAL INPATIENT BED OCCUPANCY: Total number of staffed
 * inpatient beds that are occupied.
 *
 * @param float|float[]|int|int[] $cvdNumBedsOcc
 *
 * @return static
 *
 * @see https://schema.org/cvdNumBedsOcc
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumC19Died' => 
      array (
        'name' => 'cvdNumC19Died',
        'parameters' => 
        array (
          'cvdNumC19Died' => 
          array (
            'name' => 'cvdNumC19Died',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
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
 * numc19died - DEATHS: Patients with suspected or confirmed COVID-19 who
 * died in the hospital, ED, or any overflow location.
 *
 * @param float|float[]|int|int[] $cvdNumC19Died
 *
 * @return static
 *
 * @see https://schema.org/cvdNumC19Died
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 */',
        'startLine' => 160,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumC19HOPats' => 
      array (
        'name' => 'cvdNumC19HOPats',
        'parameters' => 
        array (
          'cvdNumC19HOPats' => 
          array (
            'name' => 'cvdNumC19HOPats',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
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
 * numc19hopats - HOSPITAL ONSET: Patients hospitalized in an NHSN inpatient
 * care location with onset of suspected or confirmed COVID-19 14 or more
 * days after hospitalization.
 *
 * @param float|float[]|int|int[] $cvdNumC19HOPats
 *
 * @return static
 *
 * @see https://schema.org/cvdNumC19HOPats
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 */',
        'startLine' => 178,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumC19HospPats' => 
      array (
        'name' => 'cvdNumC19HospPats',
        'parameters' => 
        array (
          'cvdNumC19HospPats' => 
          array (
            'name' => 'cvdNumC19HospPats',
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
 * numc19hosppats - HOSPITALIZED: Patients currently hospitalized in an
 * inpatient care location who have suspected or confirmed COVID-19.
 *
 * @param float|float[]|int|int[] $cvdNumC19HospPats
 *
 * @return static
 *
 * @see https://schema.org/cvdNumC19HospPats
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumC19MechVentPats' => 
      array (
        'name' => 'cvdNumC19MechVentPats',
        'parameters' => 
        array (
          'cvdNumC19MechVentPats' => 
          array (
            'name' => 'cvdNumC19MechVentPats',
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
 * numc19mechventpats - HOSPITALIZED and VENTILATED: Patients hospitalized
 * in an NHSN inpatient care location who have suspected or confirmed
 * COVID-19 and are on a mechanical ventilator.
 *
 * @param float|float[]|int|int[] $cvdNumC19MechVentPats
 *
 * @return static
 *
 * @see https://schema.org/cvdNumC19MechVentPats
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumC19OFMechVentPats' => 
      array (
        'name' => 'cvdNumC19OFMechVentPats',
        'parameters' => 
        array (
          'cvdNumC19OFMechVentPats' => 
          array (
            'name' => 'cvdNumC19OFMechVentPats',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 45,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * numc19ofmechventpats - ED/OVERFLOW and VENTILATED: Patients with
 * suspected or confirmed COVID-19 who are in the ED or any overflow
 * location awaiting an inpatient bed and on a mechanical ventilator.
 *
 * @param float|float[]|int|int[] $cvdNumC19OFMechVentPats
 *
 * @return static
 *
 * @see https://schema.org/cvdNumC19OFMechVentPats
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 */',
        'startLine' => 231,
        'endLine' => 234,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumC19OverflowPats' => 
      array (
        'name' => 'cvdNumC19OverflowPats',
        'parameters' => 
        array (
          'cvdNumC19OverflowPats' => 
          array (
            'name' => 'cvdNumC19OverflowPats',
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
 * numc19overflowpats - ED/OVERFLOW: Patients with suspected or confirmed
 * COVID-19 who are in the ED or any overflow location awaiting an inpatient
 * bed.
 *
 * @param float|float[]|int|int[] $cvdNumC19OverflowPats
 *
 * @return static
 *
 * @see https://schema.org/cvdNumC19OverflowPats
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumICUBeds' => 
      array (
        'name' => 'cvdNumICUBeds',
        'parameters' => 
        array (
          'cvdNumICUBeds' => 
          array (
            'name' => 'cvdNumICUBeds',
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
 * numicubeds - ICU BEDS: Total number of staffed inpatient intensive care
 * unit (ICU) beds.
 *
 * @param float|float[]|int|int[] $cvdNumICUBeds
 *
 * @return static
 *
 * @see https://schema.org/cvdNumICUBeds
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumICUBedsOcc' => 
      array (
        'name' => 'cvdNumICUBedsOcc',
        'parameters' => 
        array (
          'cvdNumICUBedsOcc' => 
          array (
            'name' => 'cvdNumICUBedsOcc',
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
 * numicubedsocc - ICU BED OCCUPANCY: Total number of staffed inpatient ICU
 * beds that are occupied.
 *
 * @param float|float[]|int|int[] $cvdNumICUBedsOcc
 *
 * @return static
 *
 * @see https://schema.org/cvdNumICUBedsOcc
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumTotBeds' => 
      array (
        'name' => 'cvdNumTotBeds',
        'parameters' => 
        array (
          'cvdNumTotBeds' => 
          array (
            'name' => 'cvdNumTotBeds',
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
 * numtotbeds - ALL HOSPITAL BEDS: Total number of all inpatient and
 * outpatient beds, including all staffed, ICU, licensed, and overflow
 * (surge) beds used for inpatients or outpatients.
 *
 * @param float|float[]|int|int[] $cvdNumTotBeds
 *
 * @return static
 *
 * @see https://schema.org/cvdNumTotBeds
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumVent' => 
      array (
        'name' => 'cvdNumVent',
        'parameters' => 
        array (
          'cvdNumVent' => 
          array (
            'name' => 'cvdNumVent',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 317,
            'endLine' => 317,
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
 * numvent - MECHANICAL VENTILATORS: Total number of ventilators available.
 *
 * @param float|float[]|int|int[] $cvdNumVent
 *
 * @return static
 *
 * @see https://schema.org/cvdNumVent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 */',
        'startLine' => 317,
        'endLine' => 320,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'aliasName' => NULL,
      ),
      'cvdNumVentUse' => 
      array (
        'name' => 'cvdNumVentUse',
        'parameters' => 
        array (
          'cvdNumVentUse' => 
          array (
            'name' => 'cvdNumVentUse',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
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
 * numventuse - MECHANICAL VENTILATORS IN USE: Total number of ventilators
 * in use.
 *
 * @param float|float[]|int|int[] $cvdNumVentUse
 *
 * @return static
 *
 * @see https://schema.org/cvdNumVentUse
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2521
 */',
        'startLine' => 334,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
 * Publication date of an online listing.
 *
 * @param \\DateTimeInterface|\\DateTimeInterface[] $datePosted
 *
 * @return static
 *
 * @see https://schema.org/datePosted
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 362,
            'endLine' => 362,
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
        'startLine' => 362,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 379,
            'endLine' => 379,
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
        'startLine' => 379,
        'endLine' => 382,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 397,
            'endLine' => 397,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 412,
            'endLine' => 412,
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
        'startLine' => 412,
        'endLine' => 415,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 428,
            'endLine' => 428,
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
        'startLine' => 428,
        'endLine' => 431,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 442,
            'endLine' => 442,
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
        'startLine' => 442,
        'endLine' => 445,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 457,
            'endLine' => 457,
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
        'startLine' => 457,
        'endLine' => 460,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 473,
            'endLine' => 473,
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
        'startLine' => 473,
        'endLine' => 476,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 488,
            'endLine' => 488,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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
            'startLine' => 502,
            'endLine' => 502,
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
        'startLine' => 502,
        'endLine' => 505,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'implementingClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
        'currentClassName' => 'Spatie\\SchemaOrg\\CDCPMDRecord',
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