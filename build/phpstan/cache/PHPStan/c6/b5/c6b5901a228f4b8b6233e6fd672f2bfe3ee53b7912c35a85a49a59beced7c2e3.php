<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/Parser/ParserInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\HtmlSanitizer\Parser\ParserInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-84bf3753cf2ff4ae4cec55fabd2c537961a4ea37be22d650988725e05fa871a1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\HtmlSanitizer\\Parser\\ParserInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/Parser/ParserInterface.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Parser',
    'name' => 'Symfony\\Component\\HtmlSanitizer\\Parser\\ParserInterface',
    'shortName' => 'ParserInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Transforms an untrusted HTML input string into a DOM tree.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 29,
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
      'parse' => 
      array (
        'name' => 'parse',
        'parameters' => 
        array (
          'html' => 
          array (
            'name' => 'html',
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
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 27,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Dom\\Node',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'DOMNode',
                  'isIdentifier' => false,
                ),
              ),
              2 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse a given string and returns a DOMNode tree.
 *
 * This method must return null if the string cannot be parsed as HTML.
 *
 * @param string $context The name of the context element in which the HTML is parsed
 */',
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 97,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Parser',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Parser\\ParserInterface',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Parser\\ParserInterface',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\Parser\\ParserInterface',
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