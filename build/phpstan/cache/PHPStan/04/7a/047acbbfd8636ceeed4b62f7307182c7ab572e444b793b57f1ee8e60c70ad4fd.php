<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/HtmlSanitizerInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cccd3485e72cbb9a3c28a304675adc311d54a4c1e2ee25f7829eb1f5d5daabe3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/HtmlSanitizerInterface.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\HtmlSanitizer',
    'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
    'shortName' => 'HtmlSanitizerInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Sanitizes an untrusted HTML input for safe insertion into a document\'s DOM.
 *
 * This interface is inspired by the W3C Standard Draft about a HTML Sanitizer API
 * ({@see https://wicg.github.io/sanitizer-api/}).
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 42,
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
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'sanitize' => 
      array (
        'name' => 'sanitize',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 30,
            'endColumn' => 42,
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
 * Sanitizes an untrusted HTML input for a <body> context.
 *
 * This method is NOT context sensitive: it assumes the returned HTML string
 * will be injected in a "body" context, and therefore will drop tags only
 * allowed in the "head" element. To sanitize a string for injection
 * in the "head" element, use {@see HtmlSanitizerInterface::sanitizeFor()}.
 */',
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
        'aliasName' => NULL,
      ),
      'sanitizeFor' => 
      array (
        'name' => 'sanitizeFor',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 33,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'input' => 
          array (
            'name' => 'input',
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 50,
            'endColumn' => 62,
            'parameterIndex' => 1,
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
 * Sanitizes an untrusted HTML input for a given context.
 *
 * This method is context sensitive: by providing a parent element name
 * (body, head, title, ...), the sanitizer will adapt its rules to only
 * allow elements that are valid inside the given parent element.
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 72,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerInterface',
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