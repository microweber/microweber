<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverElement.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\WebDriverElement
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-89367f4fff6fbd1a1c037906cb93c6f054a20b91be9a08254de49746bb4a60d6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\WebDriverElement',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/WebDriverElement.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver',
    'name' => 'Facebook\\WebDriver\\WebDriverElement',
    'shortName' => 'WebDriverElement',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Interface for an HTML element in the WebDriver framework.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 154,
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
      'clear' => 
      array (
        'name' => 'clear',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * If this element is a TEXTAREA or text INPUT element, this will clear the value.
 *
 * @return WebDriverElement The current instance.
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
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'click' => 
      array (
        'name' => 'click',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Click this element.
 *
 * @return WebDriverElement The current instance.
 */',
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getAttribute' => 
      array (
        'name' => 'getAttribute',
        'parameters' => 
        array (
          'attribute_name' => 
          array (
            'name' => 'attribute_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 34,
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
 * Get the value of the given attribute of the element.
 * Attribute is meant what is declared in the HTML markup of the element.
 * To read a value of a IDL "JavaScript" property (like `innerHTML`), use `getDomProperty()` method.
 *
 * @param string $attribute_name The name of the attribute.
 * @return string|null The value of the attribute.
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getCSSValue' => 
      array (
        'name' => 'getCSSValue',
        'parameters' => 
        array (
          'css_property_name' => 
          array (
            'name' => 'css_property_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 33,
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
 * Get the value of a given CSS property.
 *
 * @param string $css_property_name The name of the CSS property.
 * @return string The value of the CSS property.
 */',
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getLocation' => 
      array (
        'name' => 'getLocation',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the location of element relative to the top-left corner of the page.
 *
 * @return WebDriverPoint The location of the element.
 */',
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getLocationOnScreenOnceScrolledIntoView' => 
      array (
        'name' => 'getLocationOnScreenOnceScrolledIntoView',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Try scrolling the element into the view port and return the location of
 * element relative to the top-left corner of the page afterwards.
 *
 * @return WebDriverPoint The location of the element.
 */',
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
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
 * Get the size of element.
 *
 * @return WebDriverDimension The dimension of the element.
 */',
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getTagName' => 
      array (
        'name' => 'getTagName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the tag name of this element.
 *
 * @return string The tag name.
 */',
        'startLine' => 82,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getText' => 
      array (
        'name' => 'getText',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the visible (i.e. not hidden by CSS) innerText of this element,
 * including sub-elements, without any leading or trailing whitespace.
 *
 * @return string The visible innerText of this element.
 */',
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'isDisplayed' => 
      array (
        'name' => 'isDisplayed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Is this element displayed or not? This method avoids the problem of having
 * to parse an element\'s "style" attribute.
 *
 * @return bool
 */',
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'isEnabled' => 
      array (
        'name' => 'isEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Is the element currently enabled or not? This will generally return true
 * for everything but disabled input elements.
 *
 * @return bool
 */',
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'isSelected' => 
      array (
        'name' => 'isSelected',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine whether or not this element is selected or not.
 *
 * @return bool
 */',
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'sendKeys' => 
      array (
        'name' => 'sendKeys',
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
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 30,
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
 * Simulate typing into an element, which may set its value.
 *
 * @param mixed $value The data to be typed.
 * @return WebDriverElement The current instance.
 */',
        'startLine' => 121,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'submit' => 
      array (
        'name' => 'submit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * If this current element is a form, or an element within a form, then this
 * will be submitted to the remote server.
 *
 * @return WebDriverElement The current instance.
 */',
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'aliasName' => NULL,
      ),
      'getID' => 
      array (
        'name' => 'getID',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the opaque ID of the element.
 *
 * @return string The opaque ID.
 */',
        'startLine' => 136,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'implementingClassName' => 'Facebook\\WebDriver\\WebDriverElement',
        'currentClassName' => 'Facebook\\WebDriver\\WebDriverElement',
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