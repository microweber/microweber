<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/PronounceableText.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\PronounceableText
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-28208a6546c861bc7650bd03f553134fb07b706e6543b50e3e84ca31cc14f7d3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\PronounceableText',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/PronounceableText.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\PronounceableText',
    'shortName' => 'PronounceableText',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Data type: PronounceableText.
 *
 * @see https://schema.org/PronounceableText
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2108
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 85,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Spatie\\SchemaOrg\\BaseType',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Contracts\\PronounceableTextContract',
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
      'inLanguage' => 
      array (
        'name' => 'inLanguage',
        'parameters' => 
        array (
          'inLanguage' => 
          array (
            'name' => 'inLanguage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
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
 * The language of the content or performance or used in an action. Please
 * use one of the language codes from the [IETF BCP 47
 * standard](http://tools.ietf.org/html/bcp47). See also
 * [[availableLanguage]].
 *
 * @param \\Spatie\\SchemaOrg\\Contracts\\LanguageContract|\\Spatie\\SchemaOrg\\Contracts\\LanguageContract[]|string|string[] $inLanguage
 *
 * @return static
 *
 * @see https://schema.org/inLanguage
 * @link https://github.com/schemaorg/schemaorg/issues/2382
 */',
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'currentClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'aliasName' => NULL,
      ),
      'phoneticText' => 
      array (
        'name' => 'phoneticText',
        'parameters' => 
        array (
          'phoneticText' => 
          array (
            'name' => 'phoneticText',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
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
 * Representation of a text [[textValue]] using the specified
 * [[speechToTextMarkup]]. For example the city name of Houston in IPA:
 * /ˈhjuːstən/.
 *
 * @param string|string[] $phoneticText
 *
 * @return static
 *
 * @see https://schema.org/phoneticText
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2108
 */',
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'currentClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'aliasName' => NULL,
      ),
      'speechToTextMarkup' => 
      array (
        'name' => 'speechToTextMarkup',
        'parameters' => 
        array (
          'speechToTextMarkup' => 
          array (
            'name' => 'speechToTextMarkup',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
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
 * Form of markup used. eg. [SSML](https://www.w3.org/TR/speech-synthesis11)
 * or [IPA](https://www.wikidata.org/wiki/Property:P898).
 *
 * @param string|string[] $speechToTextMarkup
 *
 * @return static
 *
 * @see https://schema.org/speechToTextMarkup
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2108
 */',
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'currentClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'aliasName' => NULL,
      ),
      'textValue' => 
      array (
        'name' => 'textValue',
        'parameters' => 
        array (
          'textValue' => 
          array (
            'name' => 'textValue',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
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
 * Text value being annotated.
 *
 * @param string|string[] $textValue
 *
 * @return static
 *
 * @see https://schema.org/textValue
 * @see https://pending.schema.org
 * @link https://github.com/schemaorg/schemaorg/issues/2108
 */',
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'implementingClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
        'currentClassName' => 'Spatie\\SchemaOrg\\PronounceableText',
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