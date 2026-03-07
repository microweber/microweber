<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/OpenApi/Models/AnnotationParser.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\OpenApi\Models\AnnotationParser
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-c436f72abdd52a17782a3d8bb3b955013955268d35554963a551ad281e093b0d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/OpenApi/Models/AnnotationParser.php',
      ),
    ),
    'namespace' => 'Modules\\OpenApi\\Models',
    'name' => 'Modules\\OpenApi\\Models\\AnnotationParser',
    'shortName' => 'AnnotationParser',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 6,
    'endLine' => 113,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'DOCBLOCK_PATTERN' => 
      array (
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'name' => 'DOCBLOCK_PATTERN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'%\\/\\*\\*.*\\*\\/%s\'',
          'attributes' => 
          array (
            'startLine' => 8,
            'endLine' => 8,
            'startTokenPos' => 19,
            'startFilePos' => 97,
            'endTokenPos' => 19,
            'endFilePos' => 113,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 8,
        'endLine' => 8,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'ANNOTATION_PATTERN' => 
      array (
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'name' => 'ANNOTATION_PATTERN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/(?:\\*\\s*\\@)(?P<tag>[a-zA-Z]+)\\s(?P<value>.+)\\n/\'',
          'attributes' => 
          array (
            'startLine' => 9,
            'endLine' => 9,
            'startTokenPos' => 28,
            'startFilePos' => 147,
            'endTokenPos' => 28,
            'endFilePos' => 196,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 9,
        'endLine' => 9,
        'startColumn' => 5,
        'endColumn' => 82,
      ),
      'DESCRIPTION_PATTERN' => 
      array (
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'name' => 'DESCRIPTION_PATTERN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/\\s*\\*\\s*(?P<description>[^@\\/\\s\\*].+)/\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 37,
            'startFilePos' => 231,
            'endTokenPos' => 37,
            'endFilePos' => 271,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 74,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'hasDocBlock' => 
      array (
        'name' => 'hasDocBlock',
        'parameters' => 
        array (
          'block' => 
          array (
            'name' => 'block',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 33,
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
 * Verify if the string has a docBlock in it.
 *
 * @param string $block
 * @return bool
 */',
        'startLine' => 18,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\OpenApi\\Models',
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'currentClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'aliasName' => NULL,
      ),
      'getAnnotations' => 
      array (
        'name' => 'getAnnotations',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 36,
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
 * Extract annotations from a docBlock
 * @param string $text
 * @return array
 */',
        'startLine' => 32,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\OpenApi\\Models',
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'currentClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'aliasName' => NULL,
      ),
      'getDescription' => 
      array (
        'name' => 'getDescription',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startColumn' => 36,
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
 * Retrieves any text that is not a * follow by a space or an annotation
 * @param string $text
 * @return string
 */',
        'startLine' => 54,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\OpenApi\\Models',
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'currentClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'aliasName' => NULL,
      ),
      'extractDocBlock' => 
      array (
        'name' => 'extractDocBlock',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Separate the docBlock from the content
 * @param string $text
 * @return array
 */',
        'startLine' => 72,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\OpenApi\\Models',
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'currentClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'aliasName' => NULL,
      ),
      'parse' => 
      array (
        'name' => 'parse',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 94,
            'endLine' => 94,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parses a string for a single
 * @param string $text
 * @return array
 */',
        'startLine' => 94,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\OpenApi\\Models',
        'declaringClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'implementingClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
        'currentClassName' => 'Modules\\OpenApi\\Models\\AnnotationParser',
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