<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/MakesAssertions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Dusk\Concerns\MakesAssertions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e7f4574ea3000dcbfb16e0d6675f72528cc726ec5293104006d30eca1c8f6715-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/MakesAssertions.php',
      ),
    ),
    'namespace' => 'Laravel\\Dusk\\Concerns',
    'name' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
    'shortName' => 'MakesAssertions',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 1218,
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
      'madeSourceAssertion' => 
      array (
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'name' => 'madeSourceAssertion',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 45,
            'startFilePos' => 400,
            'endTokenPos' => 45,
            'endFilePos' => 404,
          ),
        ),
        'docComment' => '/**
 * Indicates the browser has made an assertion about the source code of the page.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 40,
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
      'assertTitle' => 
      array (
        'name' => 'assertTitle',
        'parameters' => 
        array (
          'title' => 
          array (
            'name' => 'title',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 25,
            'endLine' => 25,
            'startColumn' => 33,
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
 * Assert that the page title matches the given text.
 *
 * @param  string  $title
 * @return $this
 */',
        'startLine' => 25,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertTitleContains' => 
      array (
        'name' => 'assertTitleContains',
        'parameters' => 
        array (
          'title' => 
          array (
            'name' => 'title',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
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
 * Assert that the page title contains the given text.
 *
 * @param  string  $title
 * @return $this
 */',
        'startLine' => 41,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertHasCookie' => 
      array (
        'name' => 'assertHasCookie',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decrypt' => 
          array (
            'name' => 'decrypt',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 58,
                'endLine' => 58,
                'startTokenPos' => 184,
                'startFilePos' => 1433,
                'endTokenPos' => 184,
                'endFilePos' => 1436,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 44,
            'endColumn' => 58,
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
 * Assert that the given encrypted cookie is present.
 *
 * @param  string  $name
 * @param  bool  $decrypt
 * @return $this
 */',
        'startLine' => 58,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertHasPlainCookie' => 
      array (
        'name' => 'assertHasPlainCookie',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 42,
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
 * Assert that the given unencrypted cookie is present.
 *
 * @param  string  $name
 * @return $this
 */',
        'startLine' => 76,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertCookieMissing' => 
      array (
        'name' => 'assertCookieMissing',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decrypt' => 
          array (
            'name' => 'decrypt',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 88,
                'endLine' => 88,
                'startTokenPos' => 288,
                'startFilePos' => 2151,
                'endTokenPos' => 288,
                'endFilePos' => 2154,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 48,
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
 * Assert that the given encrypted cookie is not present.
 *
 * @param  string  $name
 * @param  bool  $decrypt
 * @return $this
 */',
        'startLine' => 88,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertPlainCookieMissing' => 
      array (
        'name' => 'assertPlainCookieMissing',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 46,
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
 * Assert that the given unencrypted cookie is not present.
 *
 * @param  string  $name
 * @return $this
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertCookieValue' => 
      array (
        'name' => 'assertCookieValue',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 39,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 46,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'decrypt' => 
          array (
            'name' => 'decrypt',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 119,
                'endLine' => 119,
                'startTokenPos' => 393,
                'startFilePos' => 2906,
                'endTokenPos' => 393,
                'endFilePos' => 2909,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 54,
            'endColumn' => 68,
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
 * Assert that an encrypted cookie has a given value.
 *
 * @param  string  $name
 * @param  string  $value
 * @param  bool  $decrypt
 * @return $this
 */',
        'startLine' => 119,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertPlainCookieValue' => 
      array (
        'name' => 'assertPlainCookieValue',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
            'startColumn' => 44,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
            'startColumn' => 51,
            'endColumn' => 56,
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
 * Assert that an unencrypted cookie has a given value.
 *
 * @param  string  $name
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 139,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSee' => 
      array (
        'name' => 'assertSee',
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
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 31,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'ignoreCase' => 
          array (
            'name' => 'ignoreCase',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 151,
                'endLine' => 151,
                'startTokenPos' => 509,
                'startFilePos' => 3702,
                'endTokenPos' => 509,
                'endFilePos' => 3706,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 151,
            'endLine' => 151,
            'startColumn' => 38,
            'endColumn' => 56,
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
 * Assert that the given text is present on the page.
 *
 * @param  string  $text
 * @param  bool  $ignoreCase
 * @return $this
 */',
        'startLine' => 151,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertDontSee' => 
      array (
        'name' => 'assertDontSee',
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
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 35,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'ignoreCase' => 
          array (
            'name' => 'ignoreCase',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 163,
                'endLine' => 163,
                'startTokenPos' => 547,
                'startFilePos' => 4004,
                'endTokenPos' => 547,
                'endFilePos' => 4008,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 42,
            'endColumn' => 60,
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
 * Assert that the given text is not present on the page.
 *
 * @param  string  $text
 * @param  bool  $ignoreCase
 * @return $this
 */',
        'startLine' => 163,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSeeIn' => 
      array (
        'name' => 'assertSeeIn',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 44,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'ignoreCase' => 
          array (
            'name' => 'ignoreCase',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 176,
                'endLine' => 176,
                'startTokenPos' => 588,
                'startFilePos' => 4356,
                'endTokenPos' => 588,
                'endFilePos' => 4360,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 176,
            'endLine' => 176,
            'startColumn' => 51,
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
 * Assert that the given text is present within the selector.
 *
 * @param  string  $selector
 * @param  string  $text
 * @param  bool  $ignoreCase
 * @return $this
 */',
        'startLine' => 176,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertDontSeeIn' => 
      array (
        'name' => 'assertDontSeeIn',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 48,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'ignoreCase' => 
          array (
            'name' => 'ignoreCase',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 198,
                'endLine' => 198,
                'startTokenPos' => 684,
                'startFilePos' => 4989,
                'endTokenPos' => 684,
                'endFilePos' => 4993,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 55,
            'endColumn' => 73,
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
 * Assert that the given text is not present within the selector.
 *
 * @param  string  $selector
 * @param  string  $text
 * @param  bool  $ignoreCase
 * @return $this
 */',
        'startLine' => 198,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSeeAnythingIn' => 
      array (
        'name' => 'assertSeeAnythingIn',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 218,
            'endLine' => 218,
            'startColumn' => 41,
            'endColumn' => 49,
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
 * Assert that any text is present within the selector.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 218,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSeeNothingIn' => 
      array (
        'name' => 'assertSeeNothingIn',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 238,
            'endLine' => 238,
            'startColumn' => 40,
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
 * Assert that no text is present within the selector.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 238,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertCount' => 
      array (
        'name' => 'assertCount',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expected' => 
          array (
            'name' => 'expected',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 44,
            'endColumn' => 52,
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
 * Assert that a given element is present a given amount of times.
 *
 * @param  string  $selector
 * @param  int  $expected
 * @return $this
 */',
        'startLine' => 259,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertScript' => 
      array (
        'name' => 'assertScript',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 34,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expected' => 
          array (
            'name' => 'expected',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 279,
                'endLine' => 279,
                'startTokenPos' => 997,
                'startFilePos' => 7104,
                'endTokenPos' => 997,
                'endFilePos' => 7107,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 47,
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
 * Assert that the given JavaScript expression evaluates to the given value.
 *
 * @param  string  $expression
 * @param  mixed  $expected
 * @return $this
 */',
        'startLine' => 279,
        'endLine' => 290,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSourceHas' => 
      array (
        'name' => 'assertSourceHas',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
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
            'startColumn' => 37,
            'endColumn' => 41,
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
 * Assert that the given source code is present on the page.
 *
 * @param  string  $code
 * @return $this
 */',
        'startLine' => 298,
        'endLine' => 308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSourceMissing' => 
      array (
        'name' => 'assertSourceMissing',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 316,
            'endLine' => 316,
            'startColumn' => 41,
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
 * Assert that the given source code is not present on the page.
 *
 * @param  string  $code
 * @return $this
 */',
        'startLine' => 316,
        'endLine' => 326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSeeLink' => 
      array (
        'name' => 'assertSeeLink',
        'parameters' => 
        array (
          'link' => 
          array (
            'name' => 'link',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 35,
            'endColumn' => 39,
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
 * Assert that the given link is present on the page.
 *
 * @param  string  $link
 * @return $this
 */',
        'startLine' => 334,
        'endLine' => 348,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertDontSeeLink' => 
      array (
        'name' => 'assertDontSeeLink',
        'parameters' => 
        array (
          'link' => 
          array (
            'name' => 'link',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 356,
            'endLine' => 356,
            'startColumn' => 39,
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
 * Assert that the given link is not present on the page.
 *
 * @param  string  $link
 * @return $this
 */',
        'startLine' => 356,
        'endLine' => 370,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'seeLink' => 
      array (
        'name' => 'seeLink',
        'parameters' => 
        array (
          'link' => 
          array (
            'name' => 'link',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 378,
            'endLine' => 378,
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
 * Determine if the given link is visible.
 *
 * @param  string  $link
 * @return bool
 */',
        'startLine' => 378,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertInputValue' => 
      array (
        'name' => 'assertInputValue',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 401,
            'endLine' => 401,
            'startColumn' => 38,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 401,
            'endLine' => 401,
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
 * Assert that the given input field has the given value.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 401,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertInputValueIsNot' => 
      array (
        'name' => 'assertInputValueIsNot',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 419,
            'endLine' => 419,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 419,
            'endLine' => 419,
            'startColumn' => 51,
            'endColumn' => 56,
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
 * Assert that the given input field does not have the given value.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 419,
        'endLine' => 428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'inputValue' => 
      array (
        'name' => 'inputValue',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 436,
            'endLine' => 436,
            'startColumn' => 32,
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
 * Get the value of the given input or text area field.
 *
 * @param  string  $field
 * @return string
 */',
        'startLine' => 436,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertInputPresent' => 
      array (
        'name' => 'assertInputPresent',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 451,
            'endLine' => 451,
            'startColumn' => 40,
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
 * Assert that the given input field is present.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 451,
        'endLine' => 458,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertInputMissing' => 
      array (
        'name' => 'assertInputMissing',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 40,
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
 * Assert that the given input field is not visible.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 466,
        'endLine' => 473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertChecked' => 
      array (
        'name' => 'assertChecked',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 482,
            'endLine' => 482,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 482,
                'endLine' => 482,
                'startTokenPos' => 1734,
                'startFilePos' => 12126,
                'endTokenPos' => 1734,
                'endFilePos' => 12129,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 482,
            'endLine' => 482,
            'startColumn' => 43,
            'endColumn' => 55,
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
 * Assert that the given checkbox is checked.
 *
 * @param  string  $field
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 482,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertNotChecked' => 
      array (
        'name' => 'assertNotChecked',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 501,
            'endLine' => 501,
            'startColumn' => 38,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 501,
                'endLine' => 501,
                'startTokenPos' => 1801,
                'startFilePos' => 12607,
                'endTokenPos' => 1801,
                'endFilePos' => 12610,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 501,
            'endLine' => 501,
            'startColumn' => 46,
            'endColumn' => 58,
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
 * Assert that the given checkbox is not checked.
 *
 * @param  string  $field
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 501,
        'endLine' => 511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertIndeterminate' => 
      array (
        'name' => 'assertIndeterminate',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 520,
            'endLine' => 520,
            'startColumn' => 41,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 520,
                'endLine' => 520,
                'startTokenPos' => 1868,
                'startFilePos' => 13093,
                'endTokenPos' => 1868,
                'endFilePos' => 13096,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 520,
            'endLine' => 520,
            'startColumn' => 49,
            'endColumn' => 61,
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
 * Assert that the given checkbox is in an indeterminate state.
 *
 * @param  string  $field
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 520,
        'endLine' => 531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertRadioSelected' => 
      array (
        'name' => 'assertRadioSelected',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 540,
            'endLine' => 540,
            'startColumn' => 41,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 540,
            'endLine' => 540,
            'startColumn' => 49,
            'endColumn' => 54,
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
 * Assert that the given radio field is selected.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 540,
        'endLine' => 550,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertRadioNotSelected' => 
      array (
        'name' => 'assertRadioNotSelected',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 559,
            'endLine' => 559,
            'startColumn' => 44,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 559,
                'endLine' => 559,
                'startTokenPos' => 2003,
                'startFilePos' => 14094,
                'endTokenPos' => 2003,
                'endFilePos' => 14097,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 559,
            'endLine' => 559,
            'startColumn' => 52,
            'endColumn' => 64,
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
 * Assert that the given radio field is not selected.
 *
 * @param  string  $field
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 559,
        'endLine' => 569,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSelected' => 
      array (
        'name' => 'assertSelected',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 578,
            'endLine' => 578,
            'startColumn' => 36,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 578,
            'endLine' => 578,
            'startColumn' => 44,
            'endColumn' => 49,
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
 * Assert that the given dropdown has the given value selected.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 578,
        'endLine' => 586,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertNotSelected' => 
      array (
        'name' => 'assertNotSelected',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 595,
            'endLine' => 595,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 595,
            'endLine' => 595,
            'startColumn' => 47,
            'endColumn' => 52,
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
 * Assert that the given dropdown does not have the given value selected.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 595,
        'endLine' => 603,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSelectHasOptions' => 
      array (
        'name' => 'assertSelectHasOptions',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 612,
            'endLine' => 612,
            'startColumn' => 44,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 612,
            'endLine' => 612,
            'startColumn' => 52,
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
 * Assert that the given array of values are available to be selected.
 *
 * @param  string  $field
 * @param  array  $values
 * @return $this
 */',
        'startLine' => 612,
        'endLine' => 627,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSelectMissingOptions' => 
      array (
        'name' => 'assertSelectMissingOptions',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 48,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 56,
            'endColumn' => 68,
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
 * Assert that the given array of values are not available to be selected.
 *
 * @param  string  $field
 * @param  array  $values
 * @return $this
 */',
        'startLine' => 636,
        'endLine' => 645,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSelectHasOption' => 
      array (
        'name' => 'assertSelectHasOption',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 654,
            'endLine' => 654,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 654,
            'endLine' => 654,
            'startColumn' => 51,
            'endColumn' => 56,
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
 * Assert that the given value is available to be selected on the given field.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 654,
        'endLine' => 657,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertSelectMissingOption' => 
      array (
        'name' => 'assertSelectMissingOption',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 47,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 55,
            'endColumn' => 60,
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
 * Assert that the given value is not available to be selected.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 666,
        'endLine' => 669,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'selected' => 
      array (
        'name' => 'selected',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 678,
            'endLine' => 678,
            'startColumn' => 30,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 678,
            'endLine' => 678,
            'startColumn' => 38,
            'endColumn' => 43,
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
 * Determine if the given value is selected for the given select field.
 *
 * @param  string  $field
 * @param  string  $value
 * @return bool
 */',
        'startLine' => 678,
        'endLine' => 685,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertValue' => 
      array (
        'name' => 'assertValue',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 694,
            'endLine' => 694,
            'startColumn' => 44,
            'endColumn' => 49,
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
 * Assert that the element matching the given selector has the given value.
 *
 * @param  string  $selector
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 694,
        'endLine' => 712,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertValueIsNot' => 
      array (
        'name' => 'assertValueIsNot',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 721,
            'endLine' => 721,
            'startColumn' => 38,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 721,
            'endLine' => 721,
            'startColumn' => 49,
            'endColumn' => 54,
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
 * Assert that the element matching the given selector does not have the given value.
 *
 * @param  string  $selector
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 721,
        'endLine' => 739,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'ensureElementSupportsValueAttribute' => 
      array (
        'name' => 'ensureElementSupportsValueAttribute',
        'parameters' => 
        array (
          'element' => 
          array (
            'name' => 'element',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 57,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fullSelector' => 
          array (
            'name' => 'fullSelector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 67,
            'endColumn' => 79,
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
 * Ensure the given element supports the \'value\' attribute.
 *
 * @param  mixed  $element
 * @param  string  $fullSelector
 * @return void
 */',
        'startLine' => 748,
        'endLine' => 761,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertAttribute' => 
      array (
        'name' => 'assertAttribute',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 771,
            'endLine' => 771,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 771,
            'endLine' => 771,
            'startColumn' => 48,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 771,
            'endLine' => 771,
            'startColumn' => 60,
            'endColumn' => 65,
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
 * Assert that the element matching the given selector has the given value in the provided attribute.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 771,
        'endLine' => 789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertAttributeMissing' => 
      array (
        'name' => 'assertAttributeMissing',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 798,
            'endLine' => 798,
            'startColumn' => 44,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 798,
            'endLine' => 798,
            'startColumn' => 55,
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
 * Assert that the element matching the given selector is missing the provided attribute.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @return $this
 */',
        'startLine' => 798,
        'endLine' => 810,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertAttributeContains' => 
      array (
        'name' => 'assertAttributeContains',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 820,
            'endLine' => 820,
            'startColumn' => 45,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 820,
            'endLine' => 820,
            'startColumn' => 56,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 820,
            'endLine' => 820,
            'startColumn' => 68,
            'endColumn' => 73,
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
 * Assert that the element matching the given selector contains the given value in the provided attribute.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 820,
        'endLine' => 838,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertAttributeDoesntContain' => 
      array (
        'name' => 'assertAttributeDoesntContain',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 848,
            'endLine' => 848,
            'startColumn' => 50,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 848,
            'endLine' => 848,
            'startColumn' => 61,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 848,
            'endLine' => 848,
            'startColumn' => 73,
            'endColumn' => 78,
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
 * Assert that the element matching the given selector does not contain the given value in the provided attribute.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 848,
        'endLine' => 863,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertAriaAttribute' => 
      array (
        'name' => 'assertAriaAttribute',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 873,
            'endLine' => 873,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 873,
            'endLine' => 873,
            'startColumn' => 52,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 873,
            'endLine' => 873,
            'startColumn' => 64,
            'endColumn' => 69,
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
 * Assert that the element matching the given selector has the given value in the provided aria attribute.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 873,
        'endLine' => 876,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertDataAttribute' => 
      array (
        'name' => 'assertDataAttribute',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 52,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 64,
            'endColumn' => 69,
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
 * Assert that the element matching the given selector has the given value in the provided data attribute.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 886,
        'endLine' => 889,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertVisible' => 
      array (
        'name' => 'assertVisible',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 897,
            'endLine' => 897,
            'startColumn' => 35,
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
 * Assert that the element matching the given selector is visible.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 897,
        'endLine' => 907,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertPresent' => 
      array (
        'name' => 'assertPresent',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 915,
            'endLine' => 915,
            'startColumn' => 35,
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
 * Assert that the element matching the given selector is present.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 915,
        'endLine' => 925,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertNotPresent' => 
      array (
        'name' => 'assertNotPresent',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 933,
            'endLine' => 933,
            'startColumn' => 38,
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
 * Assert that the element matching the given selector is not present in the source.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 933,
        'endLine' => 943,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertMissing' => 
      array (
        'name' => 'assertMissing',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 951,
            'endLine' => 951,
            'startColumn' => 35,
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
 * Assert that the element matching the given selector is not visible.
 *
 * @param  string  $selector
 * @return $this
 */',
        'startLine' => 951,
        'endLine' => 967,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertDialogOpened' => 
      array (
        'name' => 'assertDialogOpened',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 975,
            'endLine' => 975,
            'startColumn' => 40,
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
 * Assert that a JavaScript dialog with the given message has been opened.
 *
 * @param  string  $message
 * @return $this
 */',
        'startLine' => 975,
        'endLine' => 986,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertEnabled' => 
      array (
        'name' => 'assertEnabled',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 994,
            'endLine' => 994,
            'startColumn' => 35,
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
 * Assert that the given field is enabled.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 994,
        'endLine' => 1004,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertDisabled' => 
      array (
        'name' => 'assertDisabled',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1012,
            'endLine' => 1012,
            'startColumn' => 36,
            'endColumn' => 41,
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
 * Assert that the given field is disabled.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 1012,
        'endLine' => 1022,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertButtonEnabled' => 
      array (
        'name' => 'assertButtonEnabled',
        'parameters' => 
        array (
          'button' => 
          array (
            'name' => 'button',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1030,
            'endLine' => 1030,
            'startColumn' => 41,
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
 * Assert that the given button is enabled.
 *
 * @param  string  $button
 * @return $this
 */',
        'startLine' => 1030,
        'endLine' => 1040,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertButtonDisabled' => 
      array (
        'name' => 'assertButtonDisabled',
        'parameters' => 
        array (
          'button' => 
          array (
            'name' => 'button',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1048,
            'endLine' => 1048,
            'startColumn' => 42,
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
 * Assert that the given button is disabled.
 *
 * @param  string  $button
 * @return $this
 */',
        'startLine' => 1048,
        'endLine' => 1058,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertFocused' => 
      array (
        'name' => 'assertFocused',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1066,
            'endLine' => 1066,
            'startColumn' => 35,
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
 * Assert that the given field is focused.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 1066,
        'endLine' => 1076,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertNotFocused' => 
      array (
        'name' => 'assertNotFocused',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1084,
            'endLine' => 1084,
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
 * Assert that the given field is not focused.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 1084,
        'endLine' => 1094,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertVue' => 
      array (
        'name' => 'assertVue',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1104,
            'endLine' => 1104,
            'startColumn' => 31,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1104,
            'endLine' => 1104,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'componentSelector' => 
          array (
            'name' => 'componentSelector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1104,
                'endLine' => 1104,
                'startTokenPos' => 3954,
                'startFilePos' => 28733,
                'endTokenPos' => 3954,
                'endFilePos' => 28736,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1104,
            'endLine' => 1104,
            'startColumn' => 45,
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
 * Assert that the Vue component\'s attribute at the given key has the given value.
 *
 * @param  string  $key
 * @param  mixed  $value
 * @param  string|null  $componentSelector
 * @return $this
 */',
        'startLine' => 1104,
        'endLine' => 1115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertVueIsNot' => 
      array (
        'name' => 'assertVueIsNot',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1125,
            'endLine' => 1125,
            'startColumn' => 36,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1125,
            'endLine' => 1125,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'componentSelector' => 
          array (
            'name' => 'componentSelector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1125,
                'endLine' => 1125,
                'startTokenPos' => 4028,
                'startFilePos' => 29331,
                'endTokenPos' => 4028,
                'endFilePos' => 29334,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1125,
            'endLine' => 1125,
            'startColumn' => 50,
            'endColumn' => 74,
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
 * Assert that a given Vue component data property does not match the given value.
 *
 * @param  string  $key
 * @param  mixed  $value
 * @param  string|null  $componentSelector
 * @return $this
 */',
        'startLine' => 1125,
        'endLine' => 1136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertVueContains' => 
      array (
        'name' => 'assertVueContains',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1146,
            'endLine' => 1146,
            'startColumn' => 39,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1146,
            'endLine' => 1146,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'componentSelector' => 
          array (
            'name' => 'componentSelector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1146,
                'endLine' => 1146,
                'startTokenPos' => 4102,
                'startFilePos' => 29941,
                'endTokenPos' => 4102,
                'endFilePos' => 29944,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1146,
            'endLine' => 1146,
            'startColumn' => 53,
            'endColumn' => 77,
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
 * Assert that a given Vue component data propertys is an array and contains the given value.
 *
 * @param  string  $key
 * @param  string  $value
 * @param  string|null  $componentSelector
 * @return $this
 */',
        'startLine' => 1146,
        'endLine' => 1158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertVueDoesntContain' => 
      array (
        'name' => 'assertVueDoesntContain',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1168,
            'endLine' => 1168,
            'startColumn' => 44,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1168,
            'endLine' => 1168,
            'startColumn' => 50,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'componentSelector' => 
          array (
            'name' => 'componentSelector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1168,
                'endLine' => 1168,
                'startTokenPos' => 4177,
                'startFilePos' => 30567,
                'endTokenPos' => 4177,
                'endFilePos' => 30570,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1168,
            'endLine' => 1168,
            'startColumn' => 58,
            'endColumn' => 82,
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
 * Assert that a given Vue component data property is an array and does not contain the given value.
 *
 * @param  string  $key
 * @param  string  $value
 * @param  string|null  $componentSelector
 * @return $this
 */',
        'startLine' => 1168,
        'endLine' => 1171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'assertVueDoesNotContain' => 
      array (
        'name' => 'assertVueDoesNotContain',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1181,
            'endLine' => 1181,
            'startColumn' => 45,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1181,
            'endLine' => 1181,
            'startColumn' => 51,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'componentSelector' => 
          array (
            'name' => 'componentSelector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1181,
                'endLine' => 1181,
                'startTokenPos' => 4218,
                'startFilePos' => 31000,
                'endTokenPos' => 4218,
                'endFilePos' => 31003,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1181,
            'endLine' => 1181,
            'startColumn' => 59,
            'endColumn' => 83,
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
 * Assert that a given Vue component data property is an array and does not contain the given value.
 *
 * @param  string  $key
 * @param  string  $value
 * @param  string|null  $componentSelector
 * @return $this
 */',
        'startLine' => 1181,
        'endLine' => 1193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'aliasName' => NULL,
      ),
      'vueAttribute' => 
      array (
        'name' => 'vueAttribute',
        'parameters' => 
        array (
          'componentSelector' => 
          array (
            'name' => 'componentSelector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1202,
            'endLine' => 1202,
            'startColumn' => 34,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1202,
            'endLine' => 1202,
            'startColumn' => 54,
            'endColumn' => 57,
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
 * Retrieve the value of the Vue component\'s attribute at the given key.
 *
 * @param  string  $componentSelector
 * @param  string  $key
 * @return mixed
 */',
        'startLine' => 1202,
        'endLine' => 1217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\MakesAssertions',
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