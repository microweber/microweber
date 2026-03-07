<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/IPTCDigitalSourceEnumeration.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\IPTCDigitalSourceEnumeration
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cdd95a0cd50b9db922e59030ab75b33255f3a5cf0c74735fef24f9e4e536b814-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/IPTCDigitalSourceEnumeration.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
    'shortName' => 'IPTCDigitalSourceEnumeration',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * [IPTC](https://www.iptc.org/) "Digital Source" codes for use with the
 * [[digitalSourceType]] property, providing information about the source for a
 * digital media object.
 * In general these codes are not declared here to be mutually exclusive,
 * although some combinations would be contradictory if applied simultaneously,
 * or might be considered mutually incompatible by upstream maintainers of the
 * definitions. See the IPTC
 * [documentation](https://www.iptc.org/std/photometadata/documentation/userguide/)
 *  for [detailed definitions](https://cv.iptc.org/newscodes/digitalsourcetype/)
 * of all terms.
 *
 * @see https://schema.org/IPTCDigitalSourceEnumeration
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 *
 * @method static supersededBy($supersededBy) The value should be instance of pending types Class|Class[]|Enumeration|Enumeration[]|Property|Property[]
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 425,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\IPTCDigitalSourceEnumerationContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\EnumerationContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\MediaEnumerationContract',
      4 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'AlgorithmicMediaDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'AlgorithmicMediaDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/AlgorithmicMediaDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 70,
            'startFilePos' => 1903,
            'endTokenPos' => 70,
            'endFilePos' => 1952,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[algorithmic
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/algorithmicMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/AlgorithmicMediaDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 100,
      ),
      'AlgorithmicallyEnhancedDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'AlgorithmicallyEnhancedDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/AlgorithmicallyEnhancedDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 83,
            'startFilePos' => 2452,
            'endTokenPos' => 83,
            'endFilePos' => 2508,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[algorithmically
 * enhanced](https://cv.iptc.org/newscodes/digitalsourcetype/algorithmicallyEnhanced)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/AlgorithmicallyEnhancedDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 114,
      ),
      'CompositeCaptureDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'CompositeCaptureDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/CompositeCaptureDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 96,
            'startFilePos' => 2980,
            'endTokenPos' => 96,
            'endFilePos' => 3029,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[composite
 * capture](https://cv.iptc.org/newscodes/digitalsourcetype/compositeCapture)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/CompositeCaptureDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 100,
      ),
      'CompositeDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'CompositeDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/CompositeDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 109,
            'startFilePos' => 3487,
            'endTokenPos' => 109,
            'endFilePos' => 3529,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[algorithmic
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/algorithmicMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/CompositeDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 86,
      ),
      'CompositeSyntheticDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'CompositeSyntheticDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/CompositeSyntheticDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 89,
            'endLine' => 89,
            'startTokenPos' => 122,
            'startFilePos' => 4009,
            'endTokenPos' => 122,
            'endFilePos' => 4060,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[composite
 * synthetic](https://cv.iptc.org/newscodes/digitalsourcetype/compositeSynthetic)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/CompositeSyntheticDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 104,
      ),
      'CompositeWithTrainedAlgorithmicMediaDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 135,
            'startFilePos' => 4615,
            'endTokenPos' => 135,
            'endFilePos' => 4684,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[composite with trained algorithmic
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/compositeWithTrainedAlgorithmicMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 140,
      ),
      'DataDrivenMediaDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'DataDrivenMediaDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/DataDrivenMediaDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 113,
            'endLine' => 113,
            'startTokenPos' => 148,
            'startFilePos' => 5153,
            'endTokenPos' => 148,
            'endFilePos' => 5201,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[data driven
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/dataDrivenMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/DataDrivenMediaDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 98,
      ),
      'DigitalArtDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'DigitalArtDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/DigitalArtDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 161,
            'startFilePos' => 5649,
            'endTokenPos' => 161,
            'endFilePos' => 5692,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[digital
 * art](https://cv.iptc.org/newscodes/digitalsourcetype/digitalArt)\' using
 * the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/DigitalArtDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 88,
      ),
      'DigitalCaptureDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'DigitalCaptureDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/DigitalCaptureDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 137,
            'endLine' => 137,
            'startTokenPos' => 174,
            'startFilePos' => 6160,
            'endTokenPos' => 174,
            'endFilePos' => 6207,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[digital
 * capture](https://cv.iptc.org/newscodes/digitalsourcetype/digitalCapture)</a>\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/DigitalCaptureDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 137,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 96,
      ),
      'MinorHumanEditsDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'MinorHumanEditsDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/MinorHumanEditsDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 149,
            'endLine' => 149,
            'startTokenPos' => 187,
            'startFilePos' => 6676,
            'endTokenPos' => 187,
            'endFilePos' => 6724,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[minor human
 * edits](https://cv.iptc.org/newscodes/digitalsourcetype/minorHumanEdits)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/MinorHumanEditsDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 149,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 98,
      ),
      'MultiFrameComputationalCaptureDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'MultiFrameComputationalCaptureDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/MultiFrameComputationalCaptureDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 161,
            'endLine' => 161,
            'startTokenPos' => 200,
            'startFilePos' => 7224,
            'endTokenPos' => 200,
            'endFilePos' => 7287,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[algorithmic
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/algorithmicMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/MultiFrameComputationalCaptureDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 161,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 128,
      ),
      'NegativeFilmDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'NegativeFilmDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/NegativeFilmDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 173,
            'endLine' => 173,
            'startTokenPos' => 213,
            'startFilePos' => 7747,
            'endTokenPos' => 213,
            'endFilePos' => 7792,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[negative
 * film](https://cv.iptc.org/newscodes/digitalsourcetype/negativeFilm)</a>\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/NegativeFilmDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 173,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 92,
      ),
      'PositiveFilmDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'PositiveFilmDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/PositiveFilmDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 185,
            'endLine' => 185,
            'startTokenPos' => 226,
            'startFilePos' => 8248,
            'endTokenPos' => 226,
            'endFilePos' => 8293,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[positive
 * film](https://cv.iptc.org/newscodes/digitalsourcetype/positiveFilm)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/PositiveFilmDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 185,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 92,
      ),
      'PrintDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'PrintDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/PrintDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 197,
            'endLine' => 197,
            'startTokenPos' => 239,
            'startFilePos' => 8720,
            'endTokenPos' => 239,
            'endFilePos' => 8758,
          ),
        ),
        'docComment' => '/**
 * Content coded as
 * \'[print](https://cv.iptc.org/newscodes/digitalsourcetype/print)\' using
 * the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/PrintDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 197,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 78,
      ),
      'ScreenCaptureDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'ScreenCaptureDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/ScreenCaptureDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 209,
            'endLine' => 209,
            'startTokenPos' => 252,
            'startFilePos' => 9224,
            'endTokenPos' => 252,
            'endFilePos' => 9270,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[algorithmic
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/algorithmicMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/ScreenCaptureDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 209,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 94,
      ),
      'TrainedAlgorithmicMediaDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'TrainedAlgorithmicMediaDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/TrainedAlgorithmicMediaDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 221,
            'endLine' => 221,
            'startTokenPos' => 265,
            'startFilePos' => 9771,
            'endTokenPos' => 265,
            'endFilePos' => 9827,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[trained algorithmic
 * media](https://cv.iptc.org/newscodes/digitalsourcetype/trainedAlgorithmicMedia)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/TrainedAlgorithmicMediaDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 221,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 114,
      ),
      'VirtualRecordingDigitalSource' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'name' => 'VirtualRecordingDigitalSource',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/VirtualRecordingDigitalSource\'',
          'attributes' => 
          array (
            'startLine' => 233,
            'endLine' => 233,
            'startTokenPos' => 278,
            'startFilePos' => 10299,
            'endTokenPos' => 278,
            'endFilePos' => 10348,
          ),
        ),
        'docComment' => '/**
 * Content coded as \'[virtual
 * recording](https://cv.iptc.org/newscodes/digitalsourcetype/virtualRecording)\'
 * using the IPTC [digital source
 * type](https://cv.iptc.org/newscodes/digitalsourcetype/) vocabulary.
 *
 * @see https://schema.org/VirtualRecordingDigitalSource
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/3392
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 233,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 100,
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
            'startLine' => 253,
            'endLine' => 253,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 267,
            'endLine' => 267,
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
        'startLine' => 267,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 281,
            'endLine' => 281,
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
        'startLine' => 281,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 298,
            'endLine' => 298,
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
        'startLine' => 298,
        'endLine' => 301,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 316,
            'endLine' => 316,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 331,
            'endLine' => 331,
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
        'startLine' => 331,
        'endLine' => 334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 347,
            'endLine' => 347,
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
        'startLine' => 347,
        'endLine' => 350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 361,
            'endLine' => 361,
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
        'startLine' => 361,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 376,
            'endLine' => 376,
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
        'startLine' => 376,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 392,
            'endLine' => 392,
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
        'startLine' => 392,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 407,
            'endLine' => 407,
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
        'startLine' => 407,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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
            'startLine' => 421,
            'endLine' => 421,
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
        'startLine' => 421,
        'endLine' => 424,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\IPTCDigitalSourceEnumeration',
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