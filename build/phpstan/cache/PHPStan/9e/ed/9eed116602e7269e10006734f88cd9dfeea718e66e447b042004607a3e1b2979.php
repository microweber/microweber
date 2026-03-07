<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/TextSanitizer/StringSanitizer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\HtmlSanitizer\TextSanitizer\StringSanitizer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b23cc4f530e88ed8ce57addfaaed90e8e766348b3c5e17acd614a81315e194fd-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/TextSanitizer/StringSanitizer.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer',
    'name' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
    'shortName' => 'StringSanitizer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * @internal
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 56,
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
      'REPLACEMENTS' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'name' => 'REPLACEMENTS',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[
    // "&#34;" is shorter than "&quot;"
    \'&quot;\' => \'&#34;\',
    // Fix several potential issues in how browsers interpret attribute values
    \'+\' => \'&#43;\',
    \'=\' => \'&#61;\',
    \'@\' => \'&#64;\',
    \'`\' => \'&#96;\',
    // Some DB engines will transform UTF8 full-width characters with
    // their classical version if the data is saved in a non-UTF8 field
    \'＜\' => \'&#xFF1C;\',
    \'＞\' => \'&#xFF1E;\',
    \'＋\' => \'&#xFF0B;\',
    \'＝\' => \'&#xFF1D;\',
    \'＠\' => \'&#xFF20;\',
    \'｀\' => \'&#xFF40;\',
]',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 37,
            'startTokenPos' => 27,
            'startFilePos' => 380,
            'endTokenPos' => 114,
            'endFilePos' => 964,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'htmlLower' => 
      array (
        'name' => 'htmlLower',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 38,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Applies a transformation to lowercase following W3C HTML Standard.
 *
 * @see https://w3c.github.io/html-reference/terminology.html#case-insensitive
 */',
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'aliasName' => NULL,
      ),
      'encodeHtmlEntities' => 
      array (
        'name' => 'encodeHtmlEntities',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 47,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Encodes the HTML entities in the given string for safe injection in a document\'s DOM.
 */',
        'startLine' => 52,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\TextSanitizer\\StringSanitizer',
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