<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/Settings.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Sabberworm\CSS\Settings
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-796ef05a235adef3d70183df356df32fd7a74e2fa77af089e8d6f539e411fd77-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Sabberworm\\CSS\\Settings',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../sabberworm/php-css-parser/src/Settings.php',
      ),
    ),
    'namespace' => 'Sabberworm\\CSS',
    'name' => 'Sabberworm\\CSS\\Settings',
    'shortName' => 'Settings',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Parser settings class.
 *
 * Configure parser behaviour here.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 124,
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
      'multibyteSupport' => 
      array (
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'name' => 'multibyteSupport',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Multi-byte string support.
 *
 * If `true` (`mbstring` extension must be enabled), will use (slower) `mb_strlen`, `mb_convert_case`, `mb_substr`
 * and `mb_strpos` functions. Otherwise, the normal (ASCII-Only) functions will be used.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultCharset' => 
      array (
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'name' => 'defaultCharset',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'utf-8\'',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 38,
            'startFilePos' => 654,
            'endTokenPos' => 38,
            'endFilePos' => 660,
          ),
        ),
        'docComment' => '/**
 * The default charset for the CSS if no `@charset` declaration is found. Defaults to utf-8.
 *
 * @var non-empty-string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'lenientParsing' => 
      array (
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'name' => 'lenientParsing',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 49,
            'startFilePos' => 818,
            'endTokenPos' => 49,
            'endFilePos' => 821,
          ),
        ),
        'docComment' => '/**
 * Whether the parser silently ignore invalid rules instead of choking on them.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 35,
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
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'withMultibyteSupport' => 
      array (
        'name' => 'withMultibyteSupport',
        'parameters' => 
        array (
          'multibyteSupport' => 
          array (
            'name' => 'multibyteSupport',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 56,
                'endLine' => 56,
                'startTokenPos' => 116,
                'startFilePos' => 1421,
                'endTokenPos' => 116,
                'endFilePos' => 1424,
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
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 42,
            'endColumn' => 70,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Enables/disables multi-byte string support.
 *
 * If `true` (`mbstring` extension must be enabled), will use (slower) `mb_strlen`, `mb_convert_case`, `mb_substr`
 * and `mb_strpos` functions. Otherwise, the normal (ASCII-Only) functions will be used.
 *
 * @return $this fluent interface
 */',
        'startLine' => 56,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'withDefaultCharset' => 
      array (
        'name' => 'withDefaultCharset',
        'parameters' => 
        array (
          'defaultCharset' => 
          array (
            'name' => 'defaultCharset',
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
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 40,
            'endColumn' => 61,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sets the charset to be used if the CSS does not contain an `@charset` declaration.
 *
 * @param non-empty-string $defaultCharset
 *
 * @return $this fluent interface
 */',
        'startLine' => 70,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'withLenientParsing' => 
      array (
        'name' => 'withLenientParsing',
        'parameters' => 
        array (
          'usesLenientParsing' => 
          array (
            'name' => 'usesLenientParsing',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 188,
                'startFilePos' => 2083,
                'endTokenPos' => 188,
                'endFilePos' => 2086,
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
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 40,
            'endColumn' => 70,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures whether the parser should silently ignore invalid rules.
 *
 * @return $this fluent interface
 */',
        'startLine' => 82,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'beStrict' => 
      array (
        'name' => 'beStrict',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Configures the parser to choke on invalid rules.
 *
 * @return $this fluent interface
 */',
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'hasMultibyteSupport' => 
      array (
        'name' => 'hasMultibyteSupport',
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
 * @internal
 */',
        'startLine' => 102,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'getDefaultCharset' => 
      array (
        'name' => 'getDefaultCharset',
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
 * @return non-empty-string
 *
 * @internal
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
        'aliasName' => NULL,
      ),
      'usesLenientParsing' => 
      array (
        'name' => 'usesLenientParsing',
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
 * @internal
 */',
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Sabberworm\\CSS',
        'declaringClassName' => 'Sabberworm\\CSS\\Settings',
        'implementingClassName' => 'Sabberworm\\CSS\\Settings',
        'currentClassName' => 'Sabberworm\\CSS\\Settings',
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