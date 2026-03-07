<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/InteractsWithElements.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Dusk\Concerns\InteractsWithElements
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7deb704923d590484d80ddc595f89d90aec2b117dc9ed52167df855883d2a517-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/InteractsWithElements.php',
      ),
    ),
    'namespace' => 'Laravel\\Dusk\\Concerns',
    'name' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
    'shortName' => 'InteractsWithElements',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 455,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Laravel\\Dusk\\Concerns\\InteractsWithKeyboard',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'elements' => 
      array (
        'name' => 'elements',
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 30,
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
 * Get all of the elements matching the given selector.
 *
 * @param  string  $selector
 * @return \\Facebook\\WebDriver\\Remote\\RemoteWebElement[]
 */',
        'startLine' => 21,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'element' => 
      array (
        'name' => 'element',
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
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 29,
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
 * Get the element matching the given selector.
 *
 * @param  string  $selector
 * @return \\Facebook\\WebDriver\\Remote\\RemoteWebElement|null
 */',
        'startLine' => 32,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'clickLink' => 
      array (
        'name' => 'clickLink',
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 31,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'element' => 
          array (
            'name' => 'element',
            'default' => 
            array (
              'code' => '\'a\'',
              'attributes' => 
              array (
                'startLine' => 44,
                'endLine' => 44,
                'startTokenPos' => 112,
                'startFilePos' => 1058,
                'endTokenPos' => 112,
                'endFilePos' => 1060,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 38,
            'endColumn' => 51,
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
 * Click the link with the given text.
 *
 * @param  string  $link
 * @param  string  $element
 * @return $this
 */',
        'startLine' => 44,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'value' => 
      array (
        'name' => 'value',
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
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 27,
            'endColumn' => 35,
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
                'startLine' => 64,
                'endLine' => 64,
                'startTokenPos' => 206,
                'startFilePos' => 1603,
                'endTokenPos' => 206,
                'endFilePos' => 1606,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 64,
            'endLine' => 64,
            'startColumn' => 38,
            'endColumn' => 50,
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
 * Directly get or set the value attribute of an input field.
 *
 * @param  string  $selector
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 64,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'text' => 
      array (
        'name' => 'text',
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
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 26,
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
 * Get the text of the element matching the given selector.
 *
 * @param  string  $selector
 * @return string
 */',
        'startLine' => 85,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'attribute' => 
      array (
        'name' => 'attribute',
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 31,
            'endColumn' => 39,
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 42,
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
 * Get the given attribute from the element matching the given selector.
 *
 * @param  string  $selector
 * @param  string  $attribute
 * @return string
 */',
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'keys' => 
      array (
        'name' => 'keys',
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
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 26,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keys' => 
          array (
            'name' => 'keys',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 37,
            'endColumn' => 44,
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
 * Send the given keys to the element matching the given selector.
 *
 * @param  string  $selector
 * @param  mixed  $keys
 * @return $this
 */',
        'startLine' => 109,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'type' => 
      array (
        'name' => 'type',
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 26,
            'endColumn' => 31,
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Type the given value in the given field.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 123,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'typeSlowly' => 
      array (
        'name' => 'typeSlowly',
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
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 32,
            'endColumn' => 37,
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
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 40,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'pause' => 
          array (
            'name' => 'pause',
            'default' => 
            array (
              'code' => '100',
              'attributes' => 
              array (
                'startLine' => 138,
                'endLine' => 138,
                'startTokenPos' => 458,
                'startFilePos' => 3456,
                'endTokenPos' => 458,
                'endFilePos' => 3458,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 48,
            'endColumn' => 59,
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
 * Type the given value in the given field slowly.
 *
 * @param  string  $field
 * @param  string  $value
 * @param  int  $pause
 * @return $this
 */',
        'startLine' => 138,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'append' => 
      array (
        'name' => 'append',
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 28,
            'endColumn' => 33,
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
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 36,
            'endColumn' => 41,
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
 * Type the given value in the given field without clearing it.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 152,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'appendSlowly' => 
      array (
        'name' => 'appendSlowly',
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
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 34,
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
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'pause' => 
          array (
            'name' => 'pause',
            'default' => 
            array (
              'code' => '100',
              'attributes' => 
              array (
                'startLine' => 167,
                'endLine' => 167,
                'startTokenPos' => 545,
                'startFilePos' => 4149,
                'endTokenPos' => 545,
                'endFilePos' => 4151,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 50,
            'endColumn' => 61,
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
 * Type the given value in the given field slowly without clearing it.
 *
 * @param  string  $field
 * @param  string  $value
 * @param  int  $pause
 * @return $this
 */',
        'startLine' => 167,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'clear' => 
      array (
        'name' => 'clear',
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
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 27,
            'endColumn' => 32,
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
 * Clear the given field.
 *
 * @param  string  $field
 * @return $this
 */',
        'startLine' => 186,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'select' => 
      array (
        'name' => 'select',
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
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 28,
            'endColumn' => 33,
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
                'startLine' => 200,
                'endLine' => 200,
                'startTokenPos' => 669,
                'startFilePos' => 4903,
                'endTokenPos' => 669,
                'endFilePos' => 4906,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 36,
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
 * Select the given value or random value of a drop-down field.
 *
 * @param  string  $field
 * @param  string|array|null  $value
 * @return $this
 */',
        'startLine' => 200,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'radio' => 
      array (
        'name' => 'radio',
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
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 27,
            'endColumn' => 32,
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
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 35,
            'endColumn' => 40,
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
 * Select the given value of a radio button field.
 *
 * @param  string  $field
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 248,
        'endLine' => 253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'check' => 
      array (
        'name' => 'check',
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
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 27,
            'endColumn' => 32,
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
                'startLine' => 262,
                'endLine' => 262,
                'startTokenPos' => 1001,
                'startFilePos' => 6539,
                'endTokenPos' => 1001,
                'endFilePos' => 6542,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 35,
            'endColumn' => 47,
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
 * Check the given checkbox.
 *
 * @param  string  $field
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 262,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'uncheck' => 
      array (
        'name' => 'uncheck',
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
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 29,
            'endColumn' => 34,
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
                'startLine' => 280,
                'endLine' => 280,
                'startTokenPos' => 1068,
                'startFilePos' => 6924,
                'endTokenPos' => 1068,
                'endFilePos' => 6927,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 37,
            'endColumn' => 49,
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
 * Uncheck the given checkbox.
 *
 * @param  string  $field
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 280,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'attach' => 
      array (
        'name' => 'attach',
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
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 28,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
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
            'startColumn' => 36,
            'endColumn' => 40,
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
 * Attach the given file to the field.
 *
 * @param  string  $field
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 298,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'press' => 
      array (
        'name' => 'press',
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
            'startLine' => 313,
            'endLine' => 313,
            'startColumn' => 27,
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
 * Press the button with the given text or name.
 *
 * @param  string  $button
 * @return $this
 */',
        'startLine' => 313,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'pressAndWaitFor' => 
      array (
        'name' => 'pressAndWaitFor',
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
            'startLine' => 327,
            'endLine' => 327,
            'startColumn' => 37,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => '5',
              'attributes' => 
              array (
                'startLine' => 327,
                'endLine' => 327,
                'startTokenPos' => 1219,
                'startFilePos' => 7962,
                'endTokenPos' => 1219,
                'endFilePos' => 7962,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 327,
            'endLine' => 327,
            'startColumn' => 46,
            'endColumn' => 57,
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
 * Press the button with the given text or name.
 *
 * @param  string  $button
 * @param  int  $seconds
 * @return $this
 */',
        'startLine' => 327,
        'endLine' => 336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'drag' => 
      array (
        'name' => 'drag',
        'parameters' => 
        array (
          'from' => 
          array (
            'name' => 'from',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'to' => 
          array (
            'name' => 'to',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 33,
            'endColumn' => 35,
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
 * Drag an element to another element using selectors.
 *
 * @param  string  $from
 * @param  string  $to
 * @return $this
 */',
        'startLine' => 345,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'dragUp' => 
      array (
        'name' => 'dragUp',
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
            'startLine' => 361,
            'endLine' => 361,
            'startColumn' => 28,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 361,
            'endLine' => 361,
            'startColumn' => 39,
            'endColumn' => 45,
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
 * Drag an element up.
 *
 * @param  string  $selector
 * @param  int  $offset
 * @return $this
 */',
        'startLine' => 361,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'dragDown' => 
      array (
        'name' => 'dragDown',
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
            'startLine' => 373,
            'endLine' => 373,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 373,
            'endLine' => 373,
            'startColumn' => 41,
            'endColumn' => 47,
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
 * Drag an element down.
 *
 * @param  string  $selector
 * @param  int  $offset
 * @return $this
 */',
        'startLine' => 373,
        'endLine' => 376,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'dragLeft' => 
      array (
        'name' => 'dragLeft',
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
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 41,
            'endColumn' => 47,
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
 * Drag an element to the left.
 *
 * @param  string  $selector
 * @param  int  $offset
 * @return $this
 */',
        'startLine' => 385,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'dragRight' => 
      array (
        'name' => 'dragRight',
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
            'startLine' => 397,
            'endLine' => 397,
            'startColumn' => 31,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 397,
            'endLine' => 397,
            'startColumn' => 42,
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
 * Drag an element to the right.
 *
 * @param  string  $selector
 * @param  int  $offset
 * @return $this
 */',
        'startLine' => 397,
        'endLine' => 400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'dragOffset' => 
      array (
        'name' => 'dragOffset',
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
            'startLine' => 410,
            'endLine' => 410,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'x' => 
          array (
            'name' => 'x',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 410,
                'endLine' => 410,
                'startTokenPos' => 1501,
                'startFilePos' => 9840,
                'endTokenPos' => 1501,
                'endFilePos' => 9840,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 410,
            'endLine' => 410,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'y' => 
          array (
            'name' => 'y',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 410,
                'endLine' => 410,
                'startTokenPos' => 1508,
                'startFilePos' => 9848,
                'endTokenPos' => 1508,
                'endFilePos' => 9848,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 410,
            'endLine' => 410,
            'startColumn' => 51,
            'endColumn' => 56,
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
 * Drag an element by the given offset.
 *
 * @param  string  $selector
 * @param  int  $x
 * @param  int  $y
 * @return $this
 */',
        'startLine' => 410,
        'endLine' => 417,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'acceptDialog' => 
      array (
        'name' => 'acceptDialog',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Accept a JavaScript dialog.
 *
 * @return $this
 */',
        'startLine' => 424,
        'endLine' => 429,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'typeInDialog' => 
      array (
        'name' => 'typeInDialog',
        'parameters' => 
        array (
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
            'startLine' => 437,
            'endLine' => 437,
            'startColumn' => 34,
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
 * Type the given value in an open JavaScript prompt dialog.
 *
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 437,
        'endLine' => 442,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'aliasName' => NULL,
      ),
      'dismissDialog' => 
      array (
        'name' => 'dismissDialog',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dismiss a JavaScript dialog.
 *
 * @return $this
 */',
        'startLine' => 449,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\InteractsWithElements',
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