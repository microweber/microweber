<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/CSSList/Document.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sabberworm\CSS\CSSList\Document
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d8a8b6c09accfaacaf20b10723cc13e58930f7fd5bde1c60918c0ab865a32590-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sabberworm\\CSS\\CSSList\\Document',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/CSSList/Document.php',
      ),
    ),
    'namespace' => 'Sabberworm\\CSS\\CSSList',
    'name' => 'Sabberworm\\CSS\\CSSList\\Document',
    'shortName' => 'Document',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This class represents the root of a parsed CSS file. It contains all top-level CSS contents: mostly declaration
 * blocks, but also any at-rules encountered (`Import` and `Charset`).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 65,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
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
          'parserState' => 
          array (
            'name' => 'parserState',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\Parsing\\ParserState',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 34,
            'endColumn' => 57,
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
            'name' => 'Sabberworm\\CSS\\CSSList\\Document',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws SourceException
 *
 * @internal since V8.8.0
 */',
        'startLine' => 23,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'aliasName' => NULL,
      ),
      'getSelectorsBySpecificity' => 
      array (
        'name' => 'getSelectorsBySpecificity',
        'parameters' => 
        array (
          'specificitySearch' => 
          array (
            'name' => 'specificitySearch',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 45,
                'endLine' => 45,
                'startTokenPos' => 116,
                'startFilePos' => 1411,
                'endTokenPos' => 116,
                'endFilePos' => 1414,
              ),
            ),
            'type' => 
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
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 47,
            'endColumn' => 79,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns all `Selector` objects with the requested specificity found recursively in the tree.
 *
 * Note that this does not yield the full `DeclarationBlock` that the selector belongs to
 * (and, currently, there is no way to get to that).
 *
 * @param string|null $specificitySearch
 *        An optional filter by specificity.
 *        May contain a comparison operator and a number or just a number (defaults to "==").
 *
 * @return list<Selector>
 *
 * @example `getSelectorsBySpecificity(\'>= 100\')`
 */',
        'startLine' => 45,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
          'outputFormat' => 
          array (
            'name' => 'outputFormat',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 53,
                'endLine' => 53,
                'startTokenPos' => 151,
                'startFilePos' => 1631,
                'endTokenPos' => 151,
                'endFilePos' => 1634,
              ),
            ),
            'type' => 
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
                      'name' => 'Sabberworm\\CSS\\OutputFormat',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 28,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => true,
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
 * Overrides `render()` to make format argument optional.
 */',
        'startLine' => 53,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'aliasName' => NULL,
      ),
      'isRootList' => 
      array (
        'name' => 'isRootList',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 61,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\Document',
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