<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MediaManipulationRatingEnumeration.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\MediaManipulationRatingEnumeration
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ad91b2156f4f8a8ac228214878b5d507b7ee10ac458259853bb2e8096c3d613c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/MediaManipulationRatingEnumeration.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
    'shortName' => 'MediaManipulationRatingEnumeration',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 *  Codes for use with the [[mediaAuthenticityCategory]] property, indicating
 * the authenticity of a media object (in the context of how it was published or
 * shared). In general these codes are not mutually exclusive, although some
 * combinations (such as \'original\' versus \'transformed\', \'edited\' and \'staged\')
 * would be contradictory if applied in the same [[MediaReview]]. Note that the
 * application of these codes is with regard to a piece of media shared or
 * published in a particular context.
 *
 * @see https://schema.org/MediaManipulationRatingEnumeration
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 *
 * @method static supersededBy($supersededBy) The value should be instance of pending types Class|Class[]|Enumeration|Enumeration[]|Property|Property[]
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 25,
    'endLine' => 388,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\MediaManipulationRatingEnumerationContract',
      1 => 'Spatie\\SchemaOrg\\Contracts\\EnumerationContract',
      2 => 'Spatie\\SchemaOrg\\Contracts\\IntangibleContract',
      3 => 'Spatie\\SchemaOrg\\Contracts\\ThingContract',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'DecontextualizedContent' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'name' => 'DecontextualizedContent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/DecontextualizedContent\'',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 62,
            'startFilePos' => 3084,
            'endTokenPos' => 62,
            'endFilePos' => 3127,
          ),
        ),
        'docComment' => '/**
 * Content coded \'missing context\' in a [[MediaReview]], considered in the
 * context of how it was published or shared.
 *
 * For a [[VideoObject]] to be \'missing context\': Presenting unaltered video
 * in an inaccurate manner that misrepresents the footage. For example,
 * using incorrect dates or locations, altering the transcript or sharing
 * brief clips from a longer video to mislead viewers. (A video rated
 * \'original\' can also be missing context.)
 *
 * For an [[ImageObject]] to be \'missing context\': Presenting unaltered
 * images in an inaccurate manner to misrepresent the image and mislead the
 * viewer. For example, a common tactic is using an unaltered image but
 * saying it came from a different time or place. (An image rated \'original\'
 * can also be missing context.)
 *
 * For an [[ImageObject]] with embedded text to be \'missing context\': An
 * unaltered image presented in an inaccurate manner to misrepresent the
 * image and mislead the viewer. For example, a common tactic is using an
 * unaltered image but saying it came from a different time or place. (An
 * \'original\' image with inaccurate text would generally fall in this
 * category.)
 *
 * For an [[AudioObject]] to be \'missing context\': Unaltered audio presented
 * in an inaccurate manner that misrepresents it. For example, using
 * incorrect dates or locations, or sharing brief clips from a longer
 * recording to mislead viewers. (Audio rated “original” can also be
 * missing context.)
 *
 * @see https://schema.org/DecontextualizedContent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 88,
      ),
      'EditedOrCroppedContent' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'name' => 'EditedOrCroppedContent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/EditedOrCroppedContent\'',
          'attributes' => 
          array (
            'startLine' => 87,
            'endLine' => 87,
            'startTokenPos' => 75,
            'startFilePos' => 4408,
            'endTokenPos' => 75,
            'endFilePos' => 4450,
          ),
        ),
        'docComment' => '/**
 * Content coded \'edited or cropped content\' in a [[MediaReview]],
 * considered in the context of how it was published or shared.
 *
 * For a [[VideoObject]] to be \'edited or cropped content\': The video has
 * been edited or rearranged. This category applies to time edits, including
 * editing multiple videos together to alter the story being told or editing
 * out large portions from a video.
 *
 * For an [[ImageObject]] to be \'edited or cropped content\': Presenting a
 * part of an image from a larger whole to mislead the viewer.
 *
 * For an [[ImageObject]] with embedded text to be \'edited or cropped
 * content\': Presenting a part of an image from a larger whole to mislead
 * the viewer.
 *
 * For an [[AudioObject]] to be \'edited or cropped content\': The audio has
 * been edited or rearranged. This category applies to time edits, including
 * editing multiple audio clips together to alter the story being told or
 * editing out large portions from the recording.
 *
 * @see https://schema.org/EditedOrCroppedContent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 86,
      ),
      'OriginalMediaContent' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'name' => 'OriginalMediaContent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/OriginalMediaContent\'',
          'attributes' => 
          array (
            'startLine' => 113,
            'endLine' => 113,
            'startTokenPos' => 88,
            'startFilePos' => 5591,
            'endTokenPos' => 88,
            'endFilePos' => 5631,
          ),
        ),
        'docComment' => '/**
 * Content coded \'as original media content\' in a [[MediaReview]],
 * considered in the context of how it was published or shared.
 *
 * For a [[VideoObject]] to be \'original\': No evidence the footage has been
 * misleadingly altered or manipulated, though it may contain false or
 * misleading claims.
 *
 * For an [[ImageObject]] to be \'original\': No evidence the image has been
 * misleadingly altered or manipulated, though it may still contain false or
 * misleading claims.
 *
 * For an [[ImageObject]] with embedded text to be \'original\': No evidence
 * the image has been misleadingly altered or manipulated, though it may
 * still contain false or misleading claims.
 *
 * For an [[AudioObject]] to be \'original\': No evidence the audio has been
 * misleadingly altered or manipulated, though it may contain false or
 * misleading claims.
 *
 * @see https://schema.org/OriginalMediaContent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 82,
      ),
      'SatireOrParodyContent' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'name' => 'SatireOrParodyContent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/SatireOrParodyContent\'',
          'attributes' => 
          array (
            'startLine' => 144,
            'endLine' => 144,
            'startTokenPos' => 101,
            'startFilePos' => 7314,
            'endTokenPos' => 101,
            'endFilePos' => 7355,
          ),
        ),
        'docComment' => '/**
 * Content coded \'satire or parody content\' in a [[MediaReview]], considered
 * in the context of how it was published or shared.
 *
 * For a [[VideoObject]] to be \'satire or parody content\': A video that was
 * created as political or humorous commentary and is presented in that
 * context. (Reshares of satire/parody content that do not include relevant
 * context are more likely to fall under the “missing context” rating.)
 *
 * For an [[ImageObject]] to be \'satire or parody content\': An image that
 * was created as political or humorous commentary and is presented in that
 * context. (Reshares of satire/parody content that do not include relevant
 * context are more likely to fall under the “missing context” rating.)
 *
 * For an [[ImageObject]] with embedded text to be \'satire or parody
 * content\': An image that was created as political or humorous commentary
 * and is presented in that context. (Reshares of satire/parody content that
 * do not include relevant context are more likely to fall under the
 * “missing context” rating.)
 *
 * For an [[AudioObject]] to be \'satire or parody content\': Audio that was
 * created as political or humorous commentary and is presented in that
 * context. (Reshares of satire/parody content that do not include relevant
 * context are more likely to fall under the “missing context” rating.)
 *
 * @see https://schema.org/SatireOrParodyContent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 144,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 84,
      ),
      'StagedContent' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'name' => 'StagedContent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/StagedContent\'',
          'attributes' => 
          array (
            'startLine' => 168,
            'endLine' => 168,
            'startTokenPos' => 114,
            'startFilePos' => 8329,
            'endTokenPos' => 114,
            'endFilePos' => 8362,
          ),
        ),
        'docComment' => '/**
 * Content coded \'staged content\' in a [[MediaReview]], considered in the
 * context of how it was published or shared.
 *
 * For a [[VideoObject]] to be \'staged content\': A video that has been
 * created using actors or similarly contrived.
 *
 * For an [[ImageObject]] to be \'staged content\': An image that was created
 * using actors or similarly contrived, such as a screenshot of a fake
 * tweet.
 *
 * For an [[ImageObject]] with embedded text to be \'staged content\': An
 * image that was created using actors or similarly contrived, such as a
 * screenshot of a fake tweet.
 *
 * For an [[AudioObject]] to be \'staged content\': Audio that has been
 * created using actors or similarly contrived.
 *
 * @see https://schema.org/StagedContent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 68,
      ),
      'TransformedContent' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'name' => 'TransformedContent',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://schema.org/TransformedContent\'',
          'attributes' => 
          array (
            'startLine' => 196,
            'endLine' => 196,
            'startTokenPos' => 127,
            'startFilePos' => 9691,
            'endTokenPos' => 127,
            'endFilePos' => 9729,
          ),
        ),
        'docComment' => '/**
 * Content coded \'transformed content\' in a [[MediaReview]], considered in
 * the context of how it was published or shared.
 *
 * For a [[VideoObject]] to be \'transformed content\':  or all of the video
 * has been manipulated to transform the footage itself. This category
 * includes using tools like the Adobe Suite to change the speed of the
 * video, add or remove visual elements or dub audio. Deepfakes are also a
 * subset of transformation.
 *
 * For an [[ImageObject]] to be \'transformed content\': Adding or deleting
 * visual elements to give the image a different meaning with the intention
 * to mislead.
 *
 * For an [[ImageObject]] with embedded text to be \'transformed content\':
 * Adding or deleting visual elements to give the image a different meaning
 * with the intention to mislead.
 *
 * For an [[AudioObject]] to be \'transformed content\': Part or all of the
 * audio has been manipulated to alter the words or sounds, or the audio has
 * been synthetically generated, such as to create a sound-alike voice.
 *
 * @see https://schema.org/TransformedContent
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2450
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 196,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 78,
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
            'startLine' => 216,
            'endLine' => 216,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 230,
            'endLine' => 230,
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
        'startLine' => 230,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 244,
            'endLine' => 244,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 261,
            'endLine' => 261,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 279,
            'endLine' => 279,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 294,
            'endLine' => 294,
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
        'startLine' => 294,
        'endLine' => 297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 310,
            'endLine' => 310,
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
        'startLine' => 310,
        'endLine' => 313,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 324,
            'endLine' => 324,
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
        'startLine' => 324,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 339,
            'endLine' => 339,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 355,
            'endLine' => 355,
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
        'startLine' => 355,
        'endLine' => 358,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 370,
            'endLine' => 370,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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
            'startLine' => 384,
            'endLine' => 384,
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
        'declaringClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'implementingClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
        'currentClassName' => 'Spatie\\SchemaOrg\\MediaManipulationRatingEnumeration',
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