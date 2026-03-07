<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/CSSList/CSSBlockList.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sabberworm\CSS\CSSList\CSSBlockList
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-098781711677840156f6289c68346a627fb746f584a5efa80a57caa53d8c95be-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/CSSList/CSSBlockList.php',
      ),
    ),
    'namespace' => 'Sabberworm\\CSS\\CSSList',
    'name' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
    'shortName' => 'CSSBlockList',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * A `CSSBlockList` is a `CSSList` whose `DeclarationBlock`s are guaranteed to contain valid declaration blocks or
 * at-rules.
 *
 * Most `CSSList`s conform to this category but some at-rules (such as `@keyframes`) do not.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 184,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSList',
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
      'getAllDeclarationBlocks' => 
      array (
        'name' => 'getAllDeclarationBlocks',
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
 * Gets all `DeclarationBlock` objects recursively, no matter how deeply nested the selectors are.
 *
 * @return list<DeclarationBlock>
 */',
        'startLine' => 30,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'aliasName' => NULL,
      ),
      'getAllRuleSets' => 
      array (
        'name' => 'getAllRuleSets',
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
 * Returns all `RuleSet` objects recursively found in the tree, no matter how deeply nested the rule sets are.
 *
 * @return list<RuleSet>
 */',
        'startLine' => 50,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'aliasName' => NULL,
      ),
      'getAllValues' => 
      array (
        'name' => 'getAllValues',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 317,
                'startFilePos' => 2781,
                'endTokenPos' => 317,
                'endFilePos' => 2784,
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
                      'name' => 'Sabberworm\\CSS\\CSSElement',
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
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 9,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'ruleSearchPattern' => 
          array (
            'name' => 'ruleSearchPattern',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 85,
                'endLine' => 85,
                'startTokenPos' => 327,
                'startFilePos' => 2824,
                'endTokenPos' => 327,
                'endFilePos' => 2827,
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
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 9,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'searchInFunctionArguments' => 
          array (
            'name' => 'searchInFunctionArguments',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 336,
                'startFilePos' => 2872,
                'endTokenPos' => 336,
                'endFilePos' => 2876,
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
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 9,
            'endColumn' => 47,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns all `Value` objects found recursively in `Declaration`s in the tree.
 *
 * @param CSSElement|null $element
 *        This is the `CSSList` or `RuleSet` to start the search from (defaults to the whole document).
 * @param string|null $ruleSearchPattern
 *        This allows filtering rules by property name
 *        (e.g. if "color" is passed, only `Value`s from `color` properties will be returned,
 *        or if "font-" is provided, `Value`s from all font rules, like `font-size`, and including `font` itself,
 *        will be returned).
 * @param bool $searchInFunctionArguments whether to also return `Value` objects used as `CSSFunction` arguments.
 *
 * @return list<Value>
 *
 * @see RuleSet->getRules()
 */',
        'startLine' => 83,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'aliasName' => NULL,
      ),
      'getAllSelectors' => 
      array (
        'name' => 'getAllSelectors',
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
                'startLine' => 139,
                'endLine' => 139,
                'startTokenPos' => 704,
                'startFilePos' => 4996,
                'endTokenPos' => 704,
                'endFilePos' => 4999,
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
            'startLine' => 139,
            'endLine' => 139,
            'startColumn' => 40,
            'endColumn' => 72,
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
 * @return list<Selector>
 */',
        'startLine' => 139,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Sabberworm\\CSS\\CSSList',
        'declaringClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'implementingClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
        'currentClassName' => 'Sabberworm\\CSS\\CSSList\\CSSBlockList',
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