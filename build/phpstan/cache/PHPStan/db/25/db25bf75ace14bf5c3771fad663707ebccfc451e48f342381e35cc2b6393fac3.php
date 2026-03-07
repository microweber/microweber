<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/ThirdPartyLibs/XSSSecurity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Utils\ThirdPartyLibs\XSSSecurity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-8fa43df1cf0463bbbf72aa9949ff2022bdd3d89de12d395000e34c70798d73ad',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/ThirdPartyLibs/XSSSecurity.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
    'name' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
    'startLine' => 27,
    'endLine' => 452,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
        'startLine' => 34,
        'endLine' => 34,
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
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
        'startLine' => 41,
        'endLine' => 41,
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
                'startLine' => 48,
                'endLine' => 48,
                'startTokenPos' => 47,
                'startFilePos' => 1182,
                'endTokenPos' => 47,
                'endFilePos' => 1185,
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
            'startLine' => 48,
            'endLine' => 48,
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
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 60,
            'endLine' => 60,
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
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 103,
                'startFilePos' => 1480,
                'endTokenPos' => 103,
                'endFilePos' => 1488,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
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
        'startLine' => 60,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 86,
            'endLine' => 86,
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
        'startLine' => 86,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
        'startLine' => 197,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 214,
            'endLine' => 214,
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
                'startLine' => 214,
                'endLine' => 214,
                'startTokenPos' => 788,
                'startFilePos' => 5463,
                'endTokenPos' => 788,
                'endFilePos' => 5466,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 214,
            'endLine' => 214,
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
        'startLine' => 214,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 239,
            'endLine' => 239,
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
        'startLine' => 239,
        'endLine' => 272,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 281,
            'endLine' => 281,
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
        'startLine' => 281,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 293,
            'endLine' => 293,
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
        'startLine' => 293,
        'endLine' => 308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 317,
            'endLine' => 317,
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
        'startLine' => 317,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 330,
            'endLine' => 330,
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
        'startLine' => 330,
        'endLine' => 341,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 350,
            'endLine' => 350,
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
        'startLine' => 350,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 370,
            'endLine' => 370,
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
        'startLine' => 370,
        'endLine' => 373,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 382,
            'endLine' => 382,
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
        'startLine' => 382,
        'endLine' => 393,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 402,
            'endLine' => 402,
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
        'startLine' => 402,
        'endLine' => 409,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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
            'startLine' => 418,
            'endLine' => 418,
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
        'startLine' => 418,
        'endLine' => 451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\XSSSecurity',
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