<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Security/HtmlSanitizer/MwHtmlSanitizerDomVisitor.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerDomVisitor
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-f07bd79d33abbb25c5e8214c53a8238fc7cf32d069be862892cc54ec8577dfc1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Security/HtmlSanitizer/MwHtmlSanitizerDomVisitor.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
    'name' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
    'shortName' => 'MwHtmlSanitizerDomVisitor',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 31,
    'endLine' => 199,
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
      'config' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'name' => 'config',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\HtmlSanitizer\\HtmlSanitizerConfig',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'elementsConfig' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'name' => 'elementsConfig',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Registry of allowed/blocked elements:
 * * If an element is present as a key and contains an array, the element should be allowed
 *   and the array is the list of allowed attributes.
 * * If an element is present as a key and contains "false", the element should be blocked.
 * * If an element is not present as a key, the element should be dropped.
 *
 * @var array<string, false|array<string, bool>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'forcedAttributes' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'name' => 'forcedAttributes',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Registry of attributes to forcefully set on nodes, index by element and attribute.
 *
 * @var array<string, array<string, string>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributeSanitizers' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'name' => 'attributeSanitizers',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 125,
            'startFilePos' => 2216,
            'endTokenPos' => 126,
            'endFilePos' => 2217,
          ),
        ),
        'docComment' => '/**
 * Registry of attributes sanitizers indexed by element name and attribute name for
 * faster sanitization.
 *
 * @var array<string, array<string, list<AttributeSanitizerInterface>>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'config' => 
          array (
            'name' => 'config',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerConfig',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 33,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'elementsConfig' => 
          array (
            'name' => 'elementsConfig',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 64,
            'endColumn' => 84,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array<string, false|array<string, bool>> $elementsConfig
 */',
        'startLine' => 64,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'currentClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'aliasName' => NULL,
      ),
      'visit' => 
      array (
        'name' => 'visit',
        'parameters' => 
        array (
          'domNode' => 
          array (
            'name' => 'domNode',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMDocumentFragment',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 27,
            'endColumn' => 55,
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
                  'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
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
        'docComment' => NULL,
        'startLine' => 79,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'currentClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'aliasName' => NULL,
      ),
      'visitNode' => 
      array (
        'name' => 'visitNode',
        'parameters' => 
        array (
          'domNode' => 
          array (
            'name' => 'domNode',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMNode',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 32,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cursor' => 
          array (
            'name' => 'cursor',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Model\\Cursor',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 51,
            'endColumn' => 64,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 87,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'currentClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'aliasName' => NULL,
      ),
      'enterNode' => 
      array (
        'name' => 'enterNode',
        'parameters' => 
        array (
          'domNodeName' => 
          array (
            'name' => 'domNodeName',
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
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 32,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'domNode' => 
          array (
            'name' => 'domNode',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMNode',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 53,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'cursor' => 
          array (
            'name' => 'cursor',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Model\\Cursor',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 72,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 103,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'currentClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'aliasName' => NULL,
      ),
      'visitChildren' => 
      array (
        'name' => 'visitChildren',
        'parameters' => 
        array (
          'domNode' => 
          array (
            'name' => 'domNode',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMNode',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 36,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cursor' => 
          array (
            'name' => 'cursor',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Model\\Cursor',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 55,
            'endColumn' => 68,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 130,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'currentClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'aliasName' => NULL,
      ),
      'setAttributes' => 
      array (
        'name' => 'setAttributes',
        'parameters' => 
        array (
          'domNodeName' => 
          array (
            'name' => 'domNodeName',
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
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 36,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'domNode' => 
          array (
            'name' => 'domNode',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMNode',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 57,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'node' => 
          array (
            'name' => 'node',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomNode',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 76,
            'endColumn' => 103,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'allowedAttributes' => 
          array (
            'name' => 'allowedAttributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 151,
                'endLine' => 151,
                'startTokenPos' => 754,
                'startFilePos' => 5483,
                'endTokenPos' => 755,
                'endFilePos' => 5484,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 106,
            'endColumn' => 134,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set attributes from a DOM node to a sanitized node.
 */',
        'startLine' => 151,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Security\\HtmlSanitizer',
        'declaringClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'implementingClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
        'currentClassName' => 'MicroweberPackages\\Security\\HtmlSanitizer\\MwHtmlSanitizerDomVisitor',
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