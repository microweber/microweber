<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverTargetLocator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\WebDriverTargetLocator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-92b4106be11a2df5c69479dfbbc547e2ab2c6b5a3db236fa5d4c7f45a343b3c2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverTargetLocator.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver',
    'name' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
    'shortName' => 'WebDriverTargetLocator',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Used to locate a given frame or window.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 69,
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
      'WINDOW_TYPE_WINDOW' => 
      array (
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'name' => 'WINDOW_TYPE_WINDOW',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'window\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 25,
            'startFilePos' => 185,
            'endTokenPos' => 25,
            'endFilePos' => 192,
          ),
        ),
        'docComment' => '/** @var string */',
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'WINDOW_TYPE_TAB' => 
      array (
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'name' => 'WINDOW_TYPE_TAB',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tab\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 38,
            'startFilePos' => 253,
            'endTokenPos' => 38,
            'endFilePos' => 257,
          ),
        ),
        'docComment' => '/** @var string */',
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'defaultContent' => 
      array (
        'name' => 'defaultContent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the current browsing context to the current top-level browsing context.
 * This is the same as calling `RemoteTargetLocator::frame(null);`
 *
 * @return WebDriver The driver focused on the top window or the first frame.
 */',
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'aliasName' => NULL,
      ),
      'frame' => 
      array (
        'name' => 'frame',
        'parameters' => 
        array (
          'frame' => 
          array (
            'name' => 'frame',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
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
 * Switch to the iframe by its id or name.
 *
 * @param WebDriverElement|null|int|string $frame The WebDriverElement, the id or the name of the frame.
 * When null, switch to the current top-level browsing context When int, switch to the WindowProxy identified
 * by the value. When an Element, switch to that Element.
 *
 * @throws \\InvalidArgumentException
 * @return WebDriver The driver focused on the given frame.
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'aliasName' => NULL,
      ),
      'window' => 
      array (
        'name' => 'window',
        'parameters' => 
        array (
          'handle' => 
          array (
            'name' => 'handle',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 28,
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
 * Switch the focus to another window by its handle.
 *
 * @param string $handle The handle of the window to be focused on.
 * @return WebDriver The driver focused on the given window.
 * @see WebDriver::getWindowHandles
 */',
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'aliasName' => NULL,
      ),
      'alert' => 
      array (
        'name' => 'alert',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Switch to the currently active modal dialog for this particular driver instance.
 *
 * @return WebDriverAlert
 */',
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'aliasName' => NULL,
      ),
      'activeElement' => 
      array (
        'name' => 'activeElement',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Switches to the element that currently has focus within the document currently "switched to",
 * or the body element if this cannot be detected.
 *
 * @return WebDriverElement
 */',
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverTargetLocator',
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