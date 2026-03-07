<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../dompdf/dompdf/src/FontMetrics.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Dompdf\FontMetrics
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-78bba32741c5b25c749ade71452206ae273368f44dd11c57b67f0b8f1d82eb6b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Dompdf\\FontMetrics',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../dompdf/dompdf/src/FontMetrics.php',
      ),
    ),
    'namespace' => 'Dompdf',
    'name' => 'Dompdf\\FontMetrics',
    'shortName' => 'FontMetrics',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The font metrics class
 *
 * This class provides information about fonts and text.  It can resolve
 * font names into actual installed font files, as well as determine the
 * size of text in a particular font and size.
 *
 * @static
 * @package dompdf
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 710,
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
      'USER_FONTS_FILE' => 
      array (
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'name' => 'USER_FONTS_FILE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '"installed-fonts.json"',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 34,
            'startFilePos' => 756,
            'endTokenPos' => 34,
            'endFilePos' => 777,
          ),
        ),
        'docComment' => '/**
 * Name of the user font families lookup cache file
 *
 * This file must be readable and writable by the executing (webserver)
 * process in order to cache user installed font information.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
    ),
    'immediateProperties' => 
    array (
      'canvas' => 
      array (
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'name' => 'canvas',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Underlying {@link Canvas} object to perform text size calculations
 *
 * @var Canvas
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bundledFonts' => 
      array (
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'name' => 'bundledFonts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 52,
            'startFilePos' => 1047,
            'endTokenPos' => 53,
            'endFilePos' => 1048,
          ),
        ),
        'docComment' => '/**
 * Array of bundled font family names to variants
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'userFonts' => 
      array (
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'name' => 'userFonts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 64,
            'startFilePos' => 1179,
            'endTokenPos' => 65,
            'endFilePos' => 1180,
          ),
        ),
        'docComment' => '/**
 * Array of user defined font family names to variants
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fontFamilies' => 
      array (
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'name' => 'fontFamilies',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * combined list of all font families with absolute paths
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'options' => 
      array (
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'name' => 'options',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var Options
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 21,
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
          'canvas' => 
          array (
            'name' => 'canvas',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Dompdf\\Canvas',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 33,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Dompdf\\Options',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 49,
            'endColumn' => 64,
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
 * Class initialization
 */',
        'startLine' => 69,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'save_font_families' => 
      array (
        'name' => 'save_font_families',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated
 */',
        'startLine' => 79,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'saveFontFamilies' => 
      array (
        'name' => 'saveFontFamilies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Saves the stored font family cache
 *
 * The name and location of the cache file are determined by {@link
 * FontMetrics::USER_FONTS_FILE}. This file should be writable by the
 * webserver process.
 *
 * @see FontMetrics::loadFontFamilies()
 */',
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'load_font_families' => 
      array (
        'name' => 'load_font_families',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated
 */',
        'startLine' => 101,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'loadFontFamilies' => 
      array (
        'name' => 'loadFontFamilies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Loads the stored font family cache
 *
 * @see FontMetrics::saveFontFamilies()
 */',
        'startLine' => 111,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'loadFontFamiliesLegacy' => 
      array (
        'name' => 'loadFontFamiliesLegacy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 123,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'register_font' => 
      array (
        'name' => 'register_font',
        'parameters' => 
        array (
          'style' => 
          array (
            'name' => 'style',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'remote_file' => 
          array (
            'name' => 'remote_file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'context' => 
          array (
            'name' => 'context',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 158,
                'endLine' => 158,
                'startTokenPos' => 576,
                'startFilePos' => 4372,
                'endTokenPos' => 576,
                'endFilePos' => 4375,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 57,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array $style
 * @param string $remote_file
 * @param resource $context
 * @return bool
 * @deprecated
 */',
        'startLine' => 158,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'registerFont' => 
      array (
        'name' => 'registerFont',
        'parameters' => 
        array (
          'style' => 
          array (
            'name' => 'style',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 34,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'remoteFile' => 
          array (
            'name' => 'remoteFile',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 42,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'context' => 
          array (
            'name' => 'context',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 169,
                'endLine' => 169,
                'startTokenPos' => 614,
                'startFilePos' => 4642,
                'endTokenPos' => 614,
                'endFilePos' => 4645,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 55,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array $style
 * @param string $remoteFile
 * @param resource $context
 * @return bool
 */',
        'startLine' => 169,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'get_text_width' => 
      array (
        'name' => 'get_text_width',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 36,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'font' => 
          array (
            'name' => 'font',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 50,
            'endColumn' => 54,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'word_spacing' => 
          array (
            'name' => 'word_spacing',
            'default' => 
            array (
              'code' => '0.0',
              'attributes' => 
              array (
                'startLine' => 277,
                'endLine' => 277,
                'startTokenPos' => 1371,
                'startFilePos' => 8142,
                'endTokenPos' => 1371,
                'endFilePos' => 8144,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 57,
            'endColumn' => 75,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'char_spacing' => 
          array (
            'name' => 'char_spacing',
            'default' => 
            array (
              'code' => '0.0',
              'attributes' => 
              array (
                'startLine' => 277,
                'endLine' => 277,
                'startTokenPos' => 1378,
                'startFilePos' => 8163,
                'endTokenPos' => 1378,
                'endFilePos' => 8165,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 277,
            'endLine' => 277,
            'startColumn' => 78,
            'endColumn' => 96,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param $text
 * @param $font
 * @param $size
 * @param float $word_spacing
 * @param float $char_spacing
 * @return float
 * @deprecated
 */',
        'startLine' => 277,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getTextWidth' => 
      array (
        'name' => 'getTextWidth',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 294,
            'endLine' => 294,
            'startColumn' => 34,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'font' => 
          array (
            'name' => 'font',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 294,
            'endLine' => 294,
            'startColumn' => 48,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 294,
            'endLine' => 294,
            'startColumn' => 55,
            'endColumn' => 65,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'wordSpacing' => 
          array (
            'name' => 'wordSpacing',
            'default' => 
            array (
              'code' => '0.0',
              'attributes' => 
              array (
                'startLine' => 294,
                'endLine' => 294,
                'startTokenPos' => 1436,
                'startFilePos' => 8822,
                'endTokenPos' => 1436,
                'endFilePos' => 8824,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 294,
            'endLine' => 294,
            'startColumn' => 68,
            'endColumn' => 91,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'charSpacing' => 
          array (
            'name' => 'charSpacing',
            'default' => 
            array (
              'code' => '0.0',
              'attributes' => 
              array (
                'startLine' => 294,
                'endLine' => 294,
                'startTokenPos' => 1445,
                'startFilePos' => 8848,
                'endTokenPos' => 1445,
                'endFilePos' => 8850,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 294,
            'endLine' => 294,
            'startColumn' => 94,
            'endColumn' => 117,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculates text size, in points
 *
 * @param string $text        The text to be sized
 * @param string $font        The font file to use
 * @param float  $size        The font size, in points
 * @param float  $wordSpacing Word spacing, if any
 * @param float  $charSpacing Char spacing, if any
 *
 * @return float
 */',
        'startLine' => 294,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'mapTextToFonts' => 
      array (
        'name' => 'mapTextToFonts',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
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
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 36,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fontFamilies' => 
          array (
            'name' => 'fontFamilies',
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
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 50,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'subtype' => 
          array (
            'name' => 'subtype',
            'default' => 
            array (
              'code' => '"normal"',
              'attributes' => 
              array (
                'startLine' => 340,
                'endLine' => 340,
                'startTokenPos' => 1649,
                'startFilePos' => 10620,
                'endTokenPos' => 1649,
                'endFilePos' => 10627,
              ),
            ),
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
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 71,
            'endColumn' => 96,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '-1',
              'attributes' => 
              array (
                'startLine' => 340,
                'endLine' => 340,
                'startTokenPos' => 1658,
                'startFilePos' => 10643,
                'endTokenPos' => 1659,
                'endFilePos' => 10644,
              ),
            ),
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
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 99,
            'endColumn' => 113,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'returnSubstring' => 
          array (
            'name' => 'returnSubstring',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 340,
                'endLine' => 340,
                'startTokenPos' => 1668,
                'startFilePos' => 10671,
                'endTokenPos' => 1668,
                'endFilePos' => 10675,
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
            'startLine' => 340,
            'endLine' => 340,
            'startColumn' => 116,
            'endColumn' => 144,
            'parameterIndex' => 4,
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
 * Maps substrings of text against the provided font list. This is achieved by
 * parsing each character of the string against the supported glyphs for each
 * font. Fonts preference is based on the order of the font list.
 *
 * Returns an array containing substring information that indicates the
 * matched font (if any), start index, substring length, and (optionally)
 * the actual text of the substring.
 *
 * @param string $text            The text to map
 * @param array  $fontFamilies    List of font families to map against
 * @param string $subtype         The font subtype (italic, bold, etc.)
 * @param int    $count           The number of matches to return
 * @param bool   $returnSubstring Should the actual matched text be returned
 * @return array
 */',
        'startLine' => 340,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'get_font_height' => 
      array (
        'name' => 'get_font_height',
        'parameters' => 
        array (
          'font' => 
          array (
            'name' => 'font',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 403,
            'endLine' => 403,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 403,
            'endLine' => 403,
            'startColumn' => 44,
            'endColumn' => 48,
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
 * @param $font
 * @param $size
 * @return float
 * @deprecated
 */',
        'startLine' => 403,
        'endLine' => 406,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getFontHeight' => 
      array (
        'name' => 'getFontHeight',
        'parameters' => 
        array (
          'font' => 
          array (
            'name' => 'font',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 416,
            'endLine' => 416,
            'startColumn' => 35,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 416,
            'endLine' => 416,
            'startColumn' => 42,
            'endColumn' => 52,
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
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculates font height, in points
 *
 * @param string $font The font file to use
 * @param float  $size The font size, in points
 *
 * @return float
 */',
        'startLine' => 416,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getFontBaseline' => 
      array (
        'name' => 'getFontBaseline',
        'parameters' => 
        array (
          'font' => 
          array (
            'name' => 'font',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 429,
            'endLine' => 429,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 429,
            'endLine' => 429,
            'startColumn' => 44,
            'endColumn' => 54,
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
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculates font baseline, in points
 *
 * @param string $font The font file to use
 * @param float  $size The font size, in points
 *
 * @return float
 */',
        'startLine' => 429,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'get_font' => 
      array (
        'name' => 'get_font',
        'parameters' => 
        array (
          'family_raw' => 
          array (
            'name' => 'family_raw',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 30,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subtype_raw' => 
          array (
            'name' => 'subtype_raw',
            'default' => 
            array (
              'code' => '"normal"',
              'attributes' => 
              array (
                'startLine' => 440,
                'endLine' => 440,
                'startTokenPos' => 2247,
                'startFilePos' => 13638,
                'endTokenPos' => 2247,
                'endFilePos' => 13645,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 43,
            'endColumn' => 65,
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
 * @param $family_raw
 * @param string $subtype_raw
 * @return string
 * @deprecated
 */',
        'startLine' => 440,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getFont' => 
      array (
        'name' => 'getFont',
        'parameters' => 
        array (
          'familyRaw' => 
          array (
            'name' => 'familyRaw',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 457,
            'endLine' => 457,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subtypeRaw' => 
          array (
            'name' => 'subtypeRaw',
            'default' => 
            array (
              'code' => '"normal"',
              'attributes' => 
              array (
                'startLine' => 457,
                'endLine' => 457,
                'startTokenPos' => 2282,
                'startFilePos' => 14261,
                'endTokenPos' => 2282,
                'endFilePos' => 14268,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 457,
            'endLine' => 457,
            'startColumn' => 41,
            'endColumn' => 62,
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
 * Resolves a font family & subtype into an actual font file
 * Subtype can be one of \'normal\', \'bold\', \'italic\' or \'bold_italic\'.  If
 * the particular font family has no suitable font file, the default font
 * ({@link Options::defaultFont}) is used.  The font file returned
 * is the absolute pathname to the font file on the system.
 *
 * @param string|null $familyRaw
 * @param string      $subtypeRaw
 *
 * @return string|null
 */',
        'startLine' => 457,
        'endLine' => 527,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'get_family' => 
      array (
        'name' => 'get_family',
        'parameters' => 
        array (
          'family' => 
          array (
            'name' => 'family',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 534,
            'endLine' => 534,
            'startColumn' => 32,
            'endColumn' => 38,
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
 * @param $family
 * @return null|string
 * @deprecated
 */',
        'startLine' => 534,
        'endLine' => 537,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getFamily' => 
      array (
        'name' => 'getFamily',
        'parameters' => 
        array (
          'family' => 
          array (
            'name' => 'family',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 543,
            'endLine' => 543,
            'startColumn' => 31,
            'endColumn' => 37,
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
 * @param string $family
 * @return null|string
 */',
        'startLine' => 543,
        'endLine' => 553,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'get_type' => 
      array (
        'name' => 'get_type',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 560,
            'endLine' => 560,
            'startColumn' => 30,
            'endColumn' => 34,
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
 * @param $type
 * @return string
 * @deprecated
 */',
        'startLine' => 560,
        'endLine' => 563,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getType' => 
      array (
        'name' => 'getType',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 569,
            'endLine' => 569,
            'startColumn' => 29,
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
 * @param string $type
 * @return string
 */',
        'startLine' => 569,
        'endLine' => 590,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'get_font_families' => 
      array (
        'name' => 'get_font_families',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array
 * @deprecated
 */',
        'startLine' => 596,
        'endLine' => 599,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getFontFamilies' => 
      array (
        'name' => 'getFontFamilies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the current font lookup table
 *
 * @return array
 */',
        'startLine' => 606,
        'endLine' => 612,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'setFontFamilies' => 
      array (
        'name' => 'setFontFamilies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert loaded fonts to font lookup table
 *
 * @return array
 */',
        'startLine' => 619,
        'endLine' => 643,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'set_font_family' => 
      array (
        'name' => 'set_font_family',
        'parameters' => 
        array (
          'fontname' => 
          array (
            'name' => 'fontname',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'entry' => 
          array (
            'name' => 'entry',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 48,
            'endColumn' => 53,
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
 * @param string $fontname
 * @param mixed $entry
 * @deprecated
 */',
        'startLine' => 650,
        'endLine' => 653,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'setFontFamily' => 
      array (
        'name' => 'setFontFamily',
        'parameters' => 
        array (
          'fontname' => 
          array (
            'name' => 'fontname',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 659,
            'endLine' => 659,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'entry' => 
          array (
            'name' => 'entry',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 659,
            'endLine' => 659,
            'startColumn' => 46,
            'endColumn' => 51,
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
 * @param string $fontname
 * @param mixed $entry
 */',
        'startLine' => 659,
        'endLine' => 664,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getUserFontsFilePath' => 
      array (
        'name' => 'getUserFontsFilePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 669,
        'endLine' => 672,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'setOptions' => 
      array (
        'name' => 'setOptions',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Dompdf\\Options',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 678,
            'endLine' => 678,
            'startColumn' => 32,
            'endColumn' => 47,
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
 * @param Options $options
 * @return $this
 */',
        'startLine' => 678,
        'endLine' => 683,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getOptions' => 
      array (
        'name' => 'getOptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return Options
 */',
        'startLine' => 688,
        'endLine' => 691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'setCanvas' => 
      array (
        'name' => 'setCanvas',
        'parameters' => 
        array (
          'canvas' => 
          array (
            'name' => 'canvas',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Dompdf\\Canvas',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 697,
            'endLine' => 697,
            'startColumn' => 31,
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
 * @param Canvas $canvas
 * @return $this
 */',
        'startLine' => 697,
        'endLine' => 701,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
        'aliasName' => NULL,
      ),
      'getCanvas' => 
      array (
        'name' => 'getCanvas',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return Canvas
 */',
        'startLine' => 706,
        'endLine' => 709,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\FontMetrics',
        'implementingClassName' => 'Dompdf\\FontMetrics',
        'currentClassName' => 'Dompdf\\FontMetrics',
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