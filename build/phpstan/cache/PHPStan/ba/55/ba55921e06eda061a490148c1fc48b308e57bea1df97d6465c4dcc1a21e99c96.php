<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverWindow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\WebDriverWindow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-01a5ac38f6237a9ea9d75f620201488455521db7626d1167e458234675b212f6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\WebDriverWindow',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverWindow.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver',
    'name' => 'Facebook\\WebDriver\\WebDriverWindow',
    'shortName' => 'WebDriverWindow',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * An abstraction allowing the driver to manipulate the browser\'s window
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 188,
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
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
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
        'startLine' => 19,
        'endLine' => 19,
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
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
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
        'startLine' => 23,
        'endLine' => 23,
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
            'startLine' => 25,
            'endLine' => 25,
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
                'startLine' => 25,
                'endLine' => 25,
                'startTokenPos' => 69,
                'startFilePos' => 623,
                'endTokenPos' => 69,
                'endFilePos' => 627,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 25,
            'endLine' => 25,
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
        'startLine' => 25,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'getPosition' => 
      array (
        'name' => 'getPosition',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the position of the current window, relative to the upper left corner
 * of the screen.
 *
 * @return WebDriverPoint The current window position.
 */',
        'startLine' => 37,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'getSize' => 
      array (
        'name' => 'getSize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the size of the current window. This will return the outer window
 * dimension, not just the view port.
 *
 * @return WebDriverDimension The current window size.
 */',
        'startLine' => 56,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'minimize' => 
      array (
        'name' => 'minimize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Minimizes the current window if it is not already minimized.
 *
 * @return WebDriverWindow The instance.
 */',
        'startLine' => 74,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'maximize' => 
      array (
        'name' => 'maximize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Maximizes the current window if it is not already maximized
 *
 * @return WebDriverWindow The instance.
 */',
        'startLine' => 90,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'fullscreen' => 
      array (
        'name' => 'fullscreen',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Makes the current window full screen.
 *
 * @return WebDriverWindow The instance.
 */',
        'startLine' => 109,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'setSize' => 
      array (
        'name' => 'setSize',
        'parameters' => 
        array (
          'size' => 
          array (
            'name' => 'size',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Facebook\\WebDriver\\WebDriverDimension',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 126,
            'endLine' => 126,
            'startColumn' => 29,
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
 * Set the size of the current window. This will change the outer window
 * dimension, not just the view port.
 *
 * @return WebDriverWindow The instance.
 */',
        'startLine' => 126,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'setPosition' => 
      array (
        'name' => 'setPosition',
        'parameters' => 
        array (
          'position' => 
          array (
            'name' => 'position',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Facebook\\WebDriver\\WebDriverPoint',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 33,
            'endColumn' => 56,
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
 * Set the position of the current window. This is relative to the upper left
 * corner of the screen.
 *
 * @return WebDriverWindow The instance.
 */',
        'startLine' => 144,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'getScreenOrientation' => 
      array (
        'name' => 'getScreenOrientation',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current browser orientation.
 *
 * @return string Either LANDSCAPE|PORTRAIT
 */',
        'startLine' => 161,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'aliasName' => NULL,
      ),
      'setScreenOrientation' => 
      array (
        'name' => 'setScreenOrientation',
        'parameters' => 
        array (
          'orientation' => 
          array (
            'name' => 'orientation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 174,
            'endLine' => 174,
            'startColumn' => 42,
            'endColumn' => 53,
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
 * Set the browser orientation. The orientation should either
 * LANDSCAPE|PORTRAIT
 *
 * @param string $orientation
 * @throws IndexOutOfBoundsException
 * @return WebDriverWindow The instance.
 */',
        'startLine' => 174,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverWindow',
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