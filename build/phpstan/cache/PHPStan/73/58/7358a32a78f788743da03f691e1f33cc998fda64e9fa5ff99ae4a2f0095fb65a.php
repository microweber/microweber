<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/CSSList/CSSList.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sabberworm\CSS\CSSList\CSSList
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c83b34a2df0dd023f4821d6c797118654b385b09ffdc2bf0bd022a1f572224c9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/CSSList/CSSList.php',
      ),
    ),
    'namespace' => 'Sabberworm\\CSS\\CSSList',
    'name' => 'Sabberworm\\CSS\\CSSList\\CSSList',
    'shortName' => 'CSSList',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * This is the most generic container available. It can contain `DeclarationBlock`s (rule sets with a selector),
 * `RuleSet`s as well as other `CSSList` objects.
 *
 * It can also contain `Import` and `Charset` objects stemming from at-rules.
 *
 * Note that `CSSListItem` extends both `Commentable` and `Renderable`,
 * so those interfaces must also be implemented by concrete subclasses.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 478,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Sabberworm\\CSS\\CSSElement',
      1 => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
      2 => 'Sabberworm\\CSS\\Position\\Positionable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Sabberworm\\CSS\\Comment\\CommentContainer',
      1 => 'Sabberworm\\CSS\\Position\\Position',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'contents' => 
      array (
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'name' => 'contents',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 160,
            'startFilePos' => 1523,
            'endTokenPos' => 161,
            'endFilePos' => 1524,
          ),
        ),
        'docComment' => '/**
 * @var array<int<0, max>, CSSListItem>
 *
 * @internal since 8.8.0
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 29,
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
          'lineNumber' => 
          array (
            'name' => 'lineNumber',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 54,
                'endLine' => 54,
                'startTokenPos' => 179,
                'startFilePos' => 1638,
                'endTokenPos' => 179,
                'endFilePos' => 1641,
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
                      'name' => 'int',
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
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 33,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param int<1, max>|null $lineNumber
 */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'parseList' => 
      array (
        'name' => 'parseList',
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
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 38,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'list' => 
          array (
            'name' => 'list',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSList',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 64,
            'endColumn' => 76,
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
        'docComment' => '/**
 * @throws UnexpectedTokenException
 * @throws SourceException
 *
 * @internal since V8.8.0
 */',
        'startLine' => 65,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'parseListItem' => 
      array (
        'name' => 'parseListItem',
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
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 43,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'list' => 
          array (
            'name' => 'list',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSList',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 69,
            'endColumn' => 81,
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
 * @return CSSListItem|false|null
 *         If `null` is returned, it means the end of the list has been reached.
 *         If `false` is returned, it means an invalid item has been encountered,
 *         but parsing of the next item should proceed.
 *
 * @throws SourceException
 * @throws UnexpectedEOFException
 * @throws UnexpectedTokenException
 */',
        'startLine' => 115,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'parseAtRule' => 
      array (
        'name' => 'parseAtRule',
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
            'startLine' => 162,
            'endLine' => 162,
            'startColumn' => 41,
            'endColumn' => 64,
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
                  'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
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
 * @throws SourceException
 * @throws UnexpectedTokenException
 * @throws UnexpectedEOFException
 */',
        'startLine' => 162,
        'endLine' => 243,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'identifierIs' => 
      array (
        'name' => 'identifierIs',
        'parameters' => 
        array (
          'identifier' => 
          array (
            'name' => 'identifier',
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
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 42,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'match' => 
          array (
            'name' => 'match',
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
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 62,
            'endColumn' => 74,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Tests an identifier for a given value. Since identifiers are all keywords, they can be vendor-prefixed.
 * We need to check for these versions too.
 */',
        'startLine' => 249,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'prepend' => 
      array (
        'name' => 'prepend',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 29,
            'endColumn' => 45,
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
 * Prepends an item to the list of contents.
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
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'append' => 
      array (
        'name' => 'append',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 269,
            'endLine' => 269,
            'startColumn' => 28,
            'endColumn' => 44,
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
 * Appends an item to the list of contents.
 */',
        'startLine' => 269,
        'endLine' => 272,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'splice' => 
      array (
        'name' => 'splice',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 28,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'length' => 
          array (
            'name' => 'length',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 279,
                'endLine' => 279,
                'startTokenPos' => 1767,
                'startFilePos' => 10531,
                'endTokenPos' => 1767,
                'endFilePos' => 10534,
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
                      'name' => 'int',
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
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 41,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'replacement' => 
          array (
            'name' => 'replacement',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 279,
                'endLine' => 279,
                'startTokenPos' => 1777,
                'startFilePos' => 10559,
                'endTokenPos' => 1777,
                'endFilePos' => 10562,
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
                      'name' => 'array',
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
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 62,
            'endColumn' => 87,
            'parameterIndex' => 2,
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
 * Splices the list of contents.
 *
 * @param array<int, CSSListItem> $replacement
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
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'insertBefore' => 
      array (
        'name' => 'insertBefore',
        'parameters' => 
        array (
          'item' => 
          array (
            'name' => 'item',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 288,
            'endLine' => 288,
            'startColumn' => 34,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sibling' => 
          array (
            'name' => 'sibling',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 288,
            'endLine' => 288,
            'startColumn' => 53,
            'endColumn' => 72,
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
        'docComment' => '/**
 * Inserts an item in the CSS list before its sibling. If the desired sibling cannot be found,
 * the item is appended at the end.
 */',
        'startLine' => 288,
        'endLine' => 295,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'remove' => 
      array (
        'name' => 'remove',
        'parameters' => 
        array (
          'itemToRemove' => 
          array (
            'name' => 'itemToRemove',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 306,
            'endLine' => 306,
            'startColumn' => 28,
            'endColumn' => 52,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Removes an item from the CSS list.
 *
 * @param CSSListItem $itemToRemove
 *        May be a `RuleSet` (most likely a `DeclarationBlock`), an `Import`,
 *        a `Charset` or another `CSSList` (most likely a `MediaQuery`)
 *
 * @return bool whether the item was removed
 */',
        'startLine' => 306,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'replace' => 
      array (
        'name' => 'replace',
        'parameters' => 
        array (
          'oldItem' => 
          array (
            'name' => 'oldItem',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\CSSList\\CSSListItem',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 325,
            'endLine' => 325,
            'startColumn' => 29,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'newItem' => 
          array (
            'name' => 'newItem',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 325,
            'endLine' => 325,
            'startColumn' => 51,
            'endColumn' => 58,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replaces an item from the CSS list.
 *
 * @param CSSListItem $oldItem
 *        May be a `RuleSet` (most likely a `DeclarationBlock`), an `Import`, a `Charset`
 *        or another `CSSList` (most likely a `MediaQuery`)
 * @param CSSListItem|array<CSSListItem> $newItem
 */',
        'startLine' => 325,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'setContents' => 
      array (
        'name' => 'setContents',
        'parameters' => 
        array (
          'contents' => 
          array (
            'name' => 'contents',
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
            'startLine' => 343,
            'endLine' => 343,
            'startColumn' => 33,
            'endColumn' => 47,
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
 * @param array<int, CSSListItem> $contents
 */',
        'startLine' => 343,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'removeDeclarationBlockBySelector' => 
      array (
        'name' => 'removeDeclarationBlockBySelector',
        'parameters' => 
        array (
          'selectors' => 
          array (
            'name' => 'selectors',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 357,
            'endLine' => 357,
            'startColumn' => 54,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'removeAll' => 
          array (
            'name' => 'removeAll',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 357,
                'endLine' => 357,
                'startTokenPos' => 2142,
                'startFilePos' => 13060,
                'endTokenPos' => 2142,
                'endFilePos' => 13064,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 357,
            'endLine' => 357,
            'startColumn' => 66,
            'endColumn' => 88,
            'parameterIndex' => 1,
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
 * Removes a declaration block from the CSS list if it matches all given selectors.
 *
 * @param DeclarationBlock|array<Selector>|string $selectors the selectors to match
 * @param bool $removeAll whether to stop at the first declaration block found or remove all blocks
 */',
        'startLine' => 357,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'renderListContents' => 
      array (
        'name' => 'renderListContents',
        'parameters' => 
        array (
          'outputFormat' => 
          array (
            'name' => 'outputFormat',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Sabberworm\\CSS\\OutputFormat',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 390,
            'endLine' => 390,
            'startColumn' => 43,
            'endColumn' => 68,
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
        'docComment' => NULL,
        'startLine' => 390,
        'endLine' => 422,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
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
        'docComment' => '/**
 * Return true if the list can not be further outdented. Only important when rendering.
 */',
        'startLine' => 427,
        'endLine' => 427,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'getContents' => 
      array (
        'name' => 'getContents',
        'parameters' => 
        array (
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
 * Returns the stored items.
 *
 * @return array<int<0, max>, CSSListItem>
 */',
        'startLine' => 434,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'getArrayRepresentation' => 
      array (
        'name' => 'getArrayRepresentation',
        'parameters' => 
        array (
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
 * @return array<string, bool|int|float|string|array<mixed>|null>
 *
 * @internal
 */',
        'startLine' => 444,
        'endLine' => 447,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'selectorsMatch' => 
      array (
        'name' => 'selectorsMatch',
        'parameters' => 
        array (
          'selectors1' => 
          array (
            'name' => 'selectors1',
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
            'startLine' => 453,
            'endLine' => 453,
            'startColumn' => 44,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'selectors2' => 
          array (
            'name' => 'selectors2',
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
            'startLine' => 453,
            'endLine' => 453,
            'startColumn' => 63,
            'endColumn' => 79,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param list<Selector> $selectors1
 * @param list<Selector> $selectors2
 */',
        'startLine' => 453,
        'endLine' => 462,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'aliasName' => NULL,
      ),
      'getSelectorStrings' => 
      array (
        'name' => 'getSelectorStrings',
        'parameters' => 
        array (
          'selectors' => 
          array (
            'name' => 'selectors',
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
            'startLine' => 469,
            'endLine' => 469,
            'startColumn' => 48,
            'endColumn' => 63,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param list<Selector> $selectors
 *
 * @return list<string>
 */',
        'startLine' => 469,
        'endLine' => 477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
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