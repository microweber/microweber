<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../dompdf/dompdf/src/Frame/FrameTree.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Dompdf\Frame\FrameTree
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-afb047edd5e3327701fbce9494ebc1f618274735853adca257b69573d2fed197-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Dompdf\\Frame\\FrameTree',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../dompdf/dompdf/src/Frame/FrameTree.php',
      ),
    ),
    'namespace' => 'Dompdf\\Frame',
    'name' => 'Dompdf\\Frame\\FrameTree',
    'shortName' => 'FrameTree',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Represents an entire document as a tree of frames
 *
 * The FrameTree consists of {@link Frame} objects each tied to specific
 * DOMNode objects in a specific DomDocument.  The FrameTree has the same
 * structure as the DomDocument, but adds additional capabilities for
 * styling and layout.
 *
 * @package dompdf
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 324,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'IteratorAggregate',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'HIDDEN_TAGS' => 
      array (
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'name' => 'HIDDEN_TAGS',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '["area", "base", "basefont", "head", "style", "meta", "title", "colgroup", "noembed", "param", "#comment"]',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 47,
            'startTokenPos' => 65,
            'startFilePos' => 809,
            'endTokenPos' => 99,
            'endFilePos' => 1008,
          ),
        ),
        'docComment' => '/**
 * Tags to ignore while parsing the tree
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_dom' => 
      array (
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'name' => '_dom',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The main DomDocument
 *
 * @see http://ca2.php.net/manual/en/ref.dom.php
 * @var DOMDocument
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_root' => 
      array (
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'name' => '_root',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The root node of the FrameTree.
 *
 * @var Frame
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_absolute_frames' => 
      array (
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'name' => '_absolute_frames',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Subtrees of absolutely positioned elements
 *
 * @var array of Frames
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_registry' => 
      array (
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'name' => '_registry',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * A mapping of {@link Frame} objects to DOMNode objects
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 25,
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
          'dom' => 
          array (
            'name' => 'dom',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMDocument',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 33,
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
 * Class constructor
 *
 * @param DOMDocument $dom the main DomDocument object representing the current html document
 */',
        'startLine' => 83,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'get_dom' => 
      array (
        'name' => 'get_dom',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the DOMDocument object representing the current html document
 *
 * @return DOMDocument
 */',
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'get_root' => 
      array (
        'name' => 'get_root',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the root frame of the tree
 *
 * @return Frame
 */',
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'get_frame' => 
      array (
        'name' => 'get_frame',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 31,
            'endColumn' => 33,
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
 * Returns a specific frame given its id
 *
 * @param string $id
 *
 * @return Frame|null
 */',
        'startLine' => 117,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'get_frames' => 
      array (
        'name' => 'get_frames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Dompdf\\Frame\\FrameTreeIterator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a post-order iterator for all frames in the tree
 *
 * @deprecated Iterate the tree directly instead
 * @return FrameTreeIterator
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'getIterator' => 
      array (
        'name' => 'getIterator',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Dompdf\\Frame\\FrameTreeIterator',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a post-order iterator for all frames in the tree
 *
 * @return FrameTreeIterator
 */',
        'startLine' => 138,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'build_tree' => 
      array (
        'name' => 'build_tree',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Builds the tree
 */',
        'startLine' => 146,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'fix_tables' => 
      array (
        'name' => 'fix_tables',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds missing TBODYs around TR
 */',
        'startLine' => 165,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      '_remove_node' => 
      array (
        'name' => '_remove_node',
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
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'children' => 
          array (
            'name' => 'children',
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
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 52,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'index' => 
          array (
            'name' => 'index',
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
            'startColumn' => 70,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove a child from a node
 *
 * Remove a child from a node. If the removed node results in two
 * adjacent #text nodes then combine them.
 *
 * @param DOMNode $node the current DOMNode being considered
 * @param array $children an array of nodes that are the children of $node
 * @param int $index index from the $children array of the node to remove
 */',
        'startLine' => 213,
        'endLine' => 226,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      '_build_tree_r' => 
      array (
        'name' => '_build_tree_r',
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
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 38,
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
 * Recursively adds {@link Frame} objects to the tree
 *
 * Recursively build a tree of Frame objects based on a dom tree.
 * No layout information is calculated at this time, although the
 * tree may be adjusted (i.e. nodes and frames for generated content
 * and images may be created).
 *
 * @param DOMNode $node the current DOMNode being considered
 *
 * @return Frame
 */',
        'startLine' => 240,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
        'aliasName' => NULL,
      ),
      'insert_node' => 
      array (
        'name' => 'insert_node',
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
                'name' => 'DOMElement',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'new_node' => 
          array (
            'name' => 'new_node',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DOMElement',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 51,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'pos' => 
          array (
            'name' => 'pos',
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
            'startColumn' => 73,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param DOMElement $node
 * @param DOMElement $new_node
 * @param string $pos
 *
 * @return mixed
 */',
        'startLine' => 298,
        'endLine' => 323,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf\\Frame',
        'declaringClassName' => 'Dompdf\\Frame\\FrameTree',
        'implementingClassName' => 'Dompdf\\Frame\\FrameTree',
        'currentClassName' => 'Dompdf\\Frame\\FrameTree',
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