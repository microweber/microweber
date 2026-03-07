<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/Visitor/Node/NodeInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\HtmlSanitizer\Visitor\Node\NodeInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ac5f81882251deca41a23f5f0fd47ed93417bc922f49759857d37e8ab76456b5-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/Visitor/Node/NodeInterface.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node',
    'name' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
    'shortName' => 'NodeInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Represents the sanitized version of a DOM node in the sanitized tree.
 *
 * Once the sanitization is done, nodes are rendered into the final output string.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 37,
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
      'addChild' => 
      array (
        'name' => 'addChild',
        'parameters' => 
        array (
          'node' => 
          array (
            'name' => 'node',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 30,
            'endColumn' => 39,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a child node to this node.
 */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'aliasName' => NULL,
      ),
      'getParent' => 
      array (
        'name' => 'getParent',
        'parameters' => 
        array (
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
                  'name' => 'self',
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
        'docComment' => '/**
 * Return the parent node of this node, or null if it has no parent node.
 */',
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
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
 * Render this node as a string, recursively rendering its children as well.
 */',
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node',
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
        'currentClassName' => 'Symfony\\Component\\HtmlSanitizer\\Visitor\\Node\\NodeInterface',
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