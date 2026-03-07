<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\WebDriver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-66d61f66e14f82cd7683da62b0589a07603670880933d4b35b384defe7fb2768-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\WebDriver',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriver.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver',
    'name' => 'Facebook\\WebDriver\\WebDriver',
    'shortName' => 'WebDriver',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * The interface for WebDriver.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 143,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Facebook\\WebDriver\\WebDriverSearchContext',
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
      'close' => 
      array (
        'name' => 'close',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Close the current window.
 *
 * @return WebDriver The current instance.
 */',
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
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
            'startColumn' => 25,
            'endColumn' => 28,
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
 * Load a new web page in the current browser window.
 *
 * @param string $url
 * @return WebDriver The current instance.
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'getCurrentURL' => 
      array (
        'name' => 'getCurrentURL',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a string representing the current URL that the browser is looking at.
 *
 * @return string The current URL.
 */',
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'getPageSource' => 
      array (
        'name' => 'getPageSource',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the source of the last loaded page.
 *
 * @return string The current page source.
 */',
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'getTitle' => 
      array (
        'name' => 'getTitle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the title of the current page.
 *
 * @return string The title of the current page.
 */',
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'getWindowHandle' => 
      array (
        'name' => 'getWindowHandle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an opaque handle to this window that uniquely identifies it within
 * this driver instance.
 *
 * @return string The current window handle.
 */',
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'getWindowHandles' => 
      array (
        'name' => 'getWindowHandles',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all window handles available to the current session.
 *
 * @return array An array of string containing all available window handles.
 */',
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'quit' => 
      array (
        'name' => 'quit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Quits this driver, closing every associated window.
 */',
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 27,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'takeScreenshot' => 
      array (
        'name' => 'takeScreenshot',
        'parameters' => 
        array (
          'save_as' => 
          array (
            'name' => 'save_as',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 74,
                'endLine' => 74,
                'startTokenPos' => 125,
                'startFilePos' => 1774,
                'endTokenPos' => 125,
                'endFilePos' => 1777,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 74,
            'endLine' => 74,
            'startColumn' => 36,
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
 * Take a screenshot of the current page.
 *
 * @param string $save_as The path of the screenshot to be saved.
 * @return string The screenshot in PNG format.
 */',
        'startLine' => 74,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'wait' => 
      array (
        'name' => 'wait',
        'parameters' => 
        array (
          'timeout_in_second' => 
          array (
            'name' => 'timeout_in_second',
            'default' => 
            array (
              'code' => '30',
              'attributes' => 
              array (
                'startLine' => 89,
                'endLine' => 89,
                'startTokenPos' => 142,
                'startFilePos' => 2187,
                'endTokenPos' => 142,
                'endFilePos' => 2188,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 9,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'interval_in_millisecond' => 
          array (
            'name' => 'interval_in_millisecond',
            'default' => 
            array (
              'code' => '250',
              'attributes' => 
              array (
                'startLine' => 90,
                'endLine' => 90,
                'startTokenPos' => 149,
                'startFilePos' => 2226,
                'endTokenPos' => 149,
                'endFilePos' => 2228,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 90,
            'endLine' => 90,
            'startColumn' => 9,
            'endColumn' => 38,
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
 * Construct a new WebDriverWait by the current WebDriver instance.
 * Sample usage:
 *
 *   $driver->wait(20, 1000)->until(
 *     WebDriverExpectedCondition::titleIs(\'WebDriver Page\')
 *   );
 *
 * @param int $timeout_in_second
 * @param int $interval_in_millisecond
 * @return WebDriverWait
 */',
        'startLine' => 88,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 6,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'manage' => 
      array (
        'name' => 'manage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An abstraction for managing stuff you would do in a browser menu. For
 * example, adding and deleting cookies.
 *
 * @return WebDriverOptions
 */',
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'navigate' => 
      array (
        'name' => 'navigate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An abstraction allowing the driver to access the browser\'s history and to
 * navigate to a given URL.
 *
 * @return WebDriverNavigationInterface
 * @see WebDriverNavigation
 */',
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'switchTo' => 
      array (
        'name' => 'switchTo',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Switch to a different window or frame.
 *
 * @return WebDriverTargetLocator
 * @see WebDriverTargetLocator
 */',
        'startLine' => 116,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
        'aliasName' => NULL,
      ),
      'execute' => 
      array (
        'name' => 'execute',
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
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 29,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 36,
            'endColumn' => 42,
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
 * @param string $name
 * @param array $params
 * @return mixed
 */',
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriver',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriver',
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