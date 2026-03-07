<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../ezyang/htmlpurifier/library/HTMLPurifier.php-PHPStan\BetterReflection\Reflection\ReflectionClass-HTMLPurifier
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0aeaee6d17529c7a2a5a778eceea8092f3191d68cef762652fe3e8e82d759cbe-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'HTMLPurifier',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../ezyang/htmlpurifier/library/HTMLPurifier.php',
      ),
    ),
    'namespace' => NULL,
    'name' => 'HTMLPurifier',
    'shortName' => 'HTMLPurifier',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Facade that coordinates HTML Purifier\'s subsystems in order to purify HTML.
 *
 * @note There are several points in which configuration can be specified
 *       for HTML Purifier.  The precedence of these (from lowest to
 *       highest) is as follows:
 *          -# Instance: new HTMLPurifier($config)
 *          -# Invocation: purify($html, $config)
 *       These configurations are entirely independent of each other and
 *       are *not* merged (this behavior may change in the future).
 *
 * @todo We need an easier way to inject strategies using the configuration
 *       object.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 54,
    'endLine' => 295,
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
      'VERSION' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'4.19.0\'',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 33,
            'startFilePos' => 2359,
            'endTokenPos' => 33,
            'endFilePos' => 2366,
          ),
        ),
        'docComment' => '/**
 * Constant with version of HTML Purifier.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
    ),
    'immediateProperties' => 
    array (
      'version' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'version',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'4.19.0\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 61,
            'startTokenPos' => 22,
            'startFilePos' => 2265,
            'endTokenPos' => 22,
            'endFilePos' => 2272,
          ),
        ),
        'docComment' => '/**
 * Version of HTML Purifier.
 * @type string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'config' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'config',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Global configuration object.
 * @type HTMLPurifier_Config
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'filters' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'filters',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 51,
            'startFilePos' => 2639,
            'endTokenPos' => 53,
            'endFilePos' => 2645,
          ),
        ),
        'docComment' => '/**
 * Array of extra filter objects to run on HTML,
 * for backwards compatibility.
 * @type HTMLPurifier_Filter[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'instance' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'instance',
        'modifiers' => 20,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Single instance of HTML Purifier.
 * @type HTMLPurifier
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'strategy' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'strategy',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @type HTMLPurifier_Strategy_Core
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'generator' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'generator',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @type HTMLPurifier_Generator
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'context' => 
      array (
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'name' => 'context',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Resultant context of last run purification.
 * Is an array of contexts if the last called method was purifyArray().
 * @type HTMLPurifier_Context
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 102,
        'endLine' => 102,
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
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 98,
                'startFilePos' => 3624,
                'endTokenPos' => 98,
                'endFilePos' => 3627,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 33,
            'endColumn' => 46,
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
 * Initializes the purifier.
 *
 * @param HTMLPurifier_Config|mixed $config Optional HTMLPurifier_Config object
 *                for all instances of the purifier, if omitted, a default
 *                configuration is supplied (which can be overridden on a
 *                per-use basis).
 *                The parameter can also be any type that
 *                HTMLPurifier_Config::create() supports.
 */',
        'startLine' => 114,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'currentClassName' => 'HTMLPurifier',
        'aliasName' => NULL,
      ),
      'addFilter' => 
      array (
        'name' => 'addFilter',
        'parameters' => 
        array (
          'filter' => 
          array (
            'name' => 'filter',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
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
 * Adds a filter to process the output. First come first serve
 *
 * @param HTMLPurifier_Filter $filter HTMLPurifier_Filter object
 */',
        'startLine' => 125,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'currentClassName' => 'HTMLPurifier',
        'aliasName' => NULL,
      ),
      'purify' => 
      array (
        'name' => 'purify',
        'parameters' => 
        array (
          'html' => 
          array (
            'name' => 'html',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 146,
                'endLine' => 146,
                'startTokenPos' => 188,
                'startFilePos' => 4767,
                'endTokenPos' => 188,
                'endFilePos' => 4770,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 146,
            'endLine' => 146,
            'startColumn' => 35,
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
 * Filters an HTML snippet/document to be XSS-free and standards-compliant.
 *
 * @param string $html String of HTML to purify
 * @param HTMLPurifier_Config $config Config object for this operation,
 *                if omitted, defaults to the config object specified during this
 *                object\'s construction. The parameter can also be any type
 *                that HTMLPurifier_Config::create() supports.
 *
 * @return string Purified HTML
 */',
        'startLine' => 146,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'currentClassName' => 'HTMLPurifier',
        'aliasName' => NULL,
      ),
      'purifyArray' => 
      array (
        'name' => 'purifyArray',
        'parameters' => 
        array (
          'array_of_html' => 
          array (
            'name' => 'array_of_html',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 33,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 771,
                'startFilePos' => 8284,
                'endTokenPos' => 771,
                'endFilePos' => 8287,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 49,
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
 * Filters an array of HTML snippets
 *
 * @param string[] $array_of_html Array of html snippets
 * @param HTMLPurifier_Config $config Optional config object for this operation.
 *                See HTMLPurifier::purify() for more details.
 *
 * @return string[] Array of purified HTML
 */',
        'startLine' => 240,
        'endLine' => 254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'currentClassName' => 'HTMLPurifier',
        'aliasName' => NULL,
      ),
      'instance' => 
      array (
        'name' => 'instance',
        'parameters' => 
        array (
          'prototype' => 
          array (
            'name' => 'prototype',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 266,
                'endLine' => 266,
                'startTokenPos' => 905,
                'startFilePos' => 9190,
                'endTokenPos' => 905,
                'endFilePos' => 9193,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 266,
            'endLine' => 266,
            'startColumn' => 37,
            'endColumn' => 53,
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
 * Singleton for enforcing just one HTML Purifier in your system
 *
 * @param HTMLPurifier|HTMLPurifier_Config $prototype Optional prototype
 *                   HTMLPurifier instance to overload singleton with,
 *                   or HTMLPurifier_Config instance to configure the
 *                   generated version with.
 *
 * @return HTMLPurifier
 */',
        'startLine' => 266,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'currentClassName' => 'HTMLPurifier',
        'aliasName' => NULL,
      ),
      'getInstance' => 
      array (
        'name' => 'getInstance',
        'parameters' => 
        array (
          'prototype' => 
          array (
            'name' => 'prototype',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 291,
                'endLine' => 291,
                'startTokenPos' => 1016,
                'startFilePos' => 10093,
                'endTokenPos' => 1016,
                'endFilePos' => 10096,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 291,
            'endLine' => 291,
            'startColumn' => 40,
            'endColumn' => 56,
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
 * Singleton for enforcing just one HTML Purifier in your system
 *
 * @param HTMLPurifier|HTMLPurifier_Config $prototype Optional prototype
 *                   HTMLPurifier instance to overload singleton with,
 *                   or HTMLPurifier_Config instance to configure the
 *                   generated version with.
 *
 * @return HTMLPurifier
 * @note Backwards compatibility, see instance()
 */',
        'startLine' => 291,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'HTMLPurifier',
        'implementingClassName' => 'HTMLPurifier',
        'currentClassName' => 'HTMLPurifier',
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