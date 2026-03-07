<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Helper/XSSSecurity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Helper\XSSSecurity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-ccaa8d7993ca45fb73d6cd6dcb3e9b5ca73b1ab34e39c3a99dab9b40b7c8f4b0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Helper/XSSSecurity.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Helper',
    'name' => 'MicroweberPackages\\Helper\\XSSSecurity',
    'shortName' => 'XSSSecurity',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This is the security class.
 *
 * Some code in this class it taken from CodeIgniter 3.
 * See the original here: http://bit.ly/1oQnpjn.
 *
 * @author Andrey Andreev <narf@bofh.bg>
 * @author Derek Jones <derek.jones@ellislab.com>
 * @author Graham Campbell <graham@cachethq.io>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 440,
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
      'xssHash' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'name' => 'xssHash',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * A random hash for protecting urls.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'evil' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'name' => 'evil',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The evil attributes.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 20,
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
          'evil' => 
          array (
            'name' => 'evil',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 36,
                'endLine' => 36,
                'startTokenPos' => 42,
                'startFilePos' => 699,
                'endTokenPos' => 42,
                'endFilePos' => 702,
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
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 33,
            'endColumn' => 50,
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
 * Create a new security instance.
 *
 * @param string[]|null $evil
 */',
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'clean' => 
      array (
        'name' => 'clean',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 27,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'method' => 
          array (
            'name' => 'method',
            'default' => 
            array (
              'code' => '\'process\'',
              'attributes' => 
              array (
                'startLine' => 48,
                'endLine' => 48,
                'startTokenPos' => 98,
                'startFilePos' => 997,
                'endTokenPos' => 98,
                'endFilePos' => 1005,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 32,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * XSS clean.
 *
 * @param string|string[] $str
 *
 * @return string
 */',
        'startLine' => 48,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'process' => 
      array (
        'name' => 'process',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 32,
            'endColumn' => 35,
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
 * Process a string for cleaning.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 74,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'xssHash' => 
      array (
        'name' => 'xssHash',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generates the XSS hash if needed and returns it.
 *
 * @return string
 */',
        'startLine' => 185,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'removeInvisibleCharacters' => 
      array (
        'name' => 'removeInvisibleCharacters',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 202,
            'endLine' => 202,
            'startColumn' => 50,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'urlEncoded' => 
          array (
            'name' => 'urlEncoded',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 202,
                'endLine' => 202,
                'startTokenPos' => 783,
                'startFilePos' => 4982,
                'endTokenPos' => 783,
                'endFilePos' => 4985,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 202,
            'endLine' => 202,
            'startColumn' => 56,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Removes invisible characters.
 *
 * @param string $str
 * @param bool   $urlEncoded
 *
 * @return string
 */',
        'startLine' => 202,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'entityDecode' => 
      array (
        'name' => 'entityDecode',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 227,
            'endLine' => 227,
            'startColumn' => 37,
            'endColumn' => 40,
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
 * HTML entities decode.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 227,
        'endLine' => 260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'compactExplodedWords' => 
      array (
        'name' => 'compactExplodedWords',
        'parameters' => 
        array (
          'matches' => 
          array (
            'name' => 'matches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 269,
            'endLine' => 269,
            'startColumn' => 45,
            'endColumn' => 52,
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
 * Compact exploded words.
 *
 * @param array $matches
 *
 * @return string
 */',
        'startLine' => 269,
        'endLine' => 272,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'removeEvilAttributes' => 
      array (
        'name' => 'removeEvilAttributes',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 281,
            'endLine' => 281,
            'startColumn' => 42,
            'endColumn' => 45,
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
 * Remove evil html attributes.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 281,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'sanitizeNaughtyHtml' => 
      array (
        'name' => 'sanitizeNaughtyHtml',
        'parameters' => 
        array (
          'matches' => 
          array (
            'name' => 'matches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 305,
            'endLine' => 305,
            'startColumn' => 44,
            'endColumn' => 51,
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
 * Sanitize naughty html.
 *
 * @param array $matches
 *
 * @return string
 */',
        'startLine' => 305,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'jsLinkRemoval' => 
      array (
        'name' => 'jsLinkRemoval',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 38,
            'endColumn' => 43,
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
 * JS link removal.
 *
 * @param array $match
 *
 * @return string
 */',
        'startLine' => 318,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'jsImgRemoval' => 
      array (
        'name' => 'jsImgRemoval',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 338,
            'endLine' => 338,
            'startColumn' => 37,
            'endColumn' => 42,
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
 * JS image removal.
 *
 * @param array $match
 *
 * @return string
 */',
        'startLine' => 338,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'convertAttribute' => 
      array (
        'name' => 'convertAttribute',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 358,
            'endLine' => 358,
            'startColumn' => 41,
            'endColumn' => 46,
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
 * Attribute conversion.
 *
 * @param array $match
 *
 * @return string
 */',
        'startLine' => 358,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'filterAttributes' => 
      array (
        'name' => 'filterAttributes',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 370,
            'endLine' => 370,
            'startColumn' => 41,
            'endColumn' => 44,
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
 * Attribute filtering.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 370,
        'endLine' => 381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'decodeEntity' => 
      array (
        'name' => 'decodeEntity',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 390,
            'endLine' => 390,
            'startColumn' => 37,
            'endColumn' => 42,
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
 * HTML entity decode callback.
 *
 * @param array $match
 *
 * @return string
 */',
        'startLine' => 390,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'aliasName' => NULL,
      ),
      'doNeverAllowed' => 
      array (
        'name' => 'doNeverAllowed',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 406,
            'endLine' => 406,
            'startColumn' => 39,
            'endColumn' => 42,
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
 * Do never allowed.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 406,
        'endLine' => 439,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Helper\\XSSSecurity',
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