<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverOptions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\WebDriverOptions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-849d9c6be73ea0d50d7a2aef82d384e032a9a3ddf17256eac47ffb4f2116fb80-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\WebDriverOptions',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverOptions.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver',
    'name' => 'Facebook\\WebDriver\\WebDriverOptions',
    'shortName' => 'WebDriverOptions',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Managing stuff you would do in a browser.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 180,
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
      'executor' => 
      array (
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'name' => 'executor',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var ExecuteMethod
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isW3cCompliant' => 
      array (
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'name' => 'isW3cCompliant',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
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
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'executor' => 
          array (
            'name' => 'executor',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Facebook\\WebDriver\\Remote\\ExecuteMethod',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 33,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'isW3cCompliant' => 
          array (
            'name' => 'isW3cCompliant',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 64,
                'startFilePos' => 528,
                'endTokenPos' => 64,
                'endFilePos' => 532,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 58,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'addCookie' => 
      array (
        'name' => 'addCookie',
        'parameters' => 
        array (
          'cookie' => 
          array (
            'name' => 'cookie',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
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
 * Add a specific cookie.
 *
 * @see Cookie for description of possible cookie properties
 * @param Cookie|array $cookie Cookie object. May be also created from array for compatibility reasons.
 * @return WebDriverOptions The current instance.
 */',
        'startLine' => 37,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'deleteAllCookies' => 
      array (
        'name' => 'deleteAllCookies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete all the cookies that are currently visible.
 *
 * @return WebDriverOptions The current instance.
 */',
        'startLine' => 59,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'deleteCookieNamed' => 
      array (
        'name' => 'deleteCookieNamed',
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
            'startLine' => 72,
            'endLine' => 72,
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
 * Delete the cookie with the given name.
 *
 * @param string $name
 * @return WebDriverOptions The current instance.
 */',
        'startLine' => 72,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'getCookieNamed' => 
      array (
        'name' => 'getCookieNamed',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 36,
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
 * Get the cookie with a given name.
 *
 * @param string $name
 * @throws NoSuchCookieException In W3C compliant mode if no cookie with the given name is present
 * @return Cookie|null The cookie, or null in JsonWire mode if no cookie with the given name is present
 */',
        'startLine' => 89,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'getCookies' => 
      array (
        'name' => 'getCookies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all the cookies for the current domain.
 *
 * @return Cookie[] The array of cookies presented.
 */',
        'startLine' => 119,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'timeouts' => 
      array (
        'name' => 'timeouts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the interface for managing driver timeouts.
 *
 * @return WebDriverTimeouts
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
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'window' => 
      array (
        'name' => 'window',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * An abstraction allowing the driver to manipulate the browser\'s window
 *
 * @return WebDriverWindow
 * @see WebDriverWindow
 */',
        'startLine' => 150,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'getLog' => 
      array (
        'name' => 'getLog',
        'parameters' => 
        array (
          'log_type' => 
          array (
            'name' => 'log_type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 162,
            'endLine' => 162,
            'startColumn' => 28,
            'endColumn' => 36,
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
 * Get the log for a given log type. Log buffer is reset after each request.
 *
 * @param string $log_type The log type.
 * @return array The list of log entries.
 * @see https://github.com/SeleniumHQ/selenium/wiki/JsonWireProtocol#log-type
 */',
        'startLine' => 162,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'aliasName' => NULL,
      ),
      'getAvailableLogTypes' => 
      array (
        'name' => 'getAvailableLogTypes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get available log types.
 *
 * @return array The list of available log types.
 * @see https://github.com/SeleniumHQ/selenium/wiki/JsonWireProtocol#log-type
 */',
        'startLine' => 176,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverOptions',
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