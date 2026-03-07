<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/Chrome/ChromeDevToolsDriver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\Chrome\ChromeDevToolsDriver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-44fc296e12770dd23a9fd645298fb1b56be0f7ec5ccb2f32b7ee3dd3638337f0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/Chrome/ChromeDevToolsDriver.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver\\Chrome',
    'name' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
    'shortName' => 'ChromeDevToolsDriver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Provide access to Chrome DevTools Protocol (CDP) commands via HTTP endpoint of Chromedriver.
 *
 * @see https://chromedevtools.github.io/devtools-protocol/
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 46,
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
      'SEND_COMMAND' => 
      array (
        'declaringClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'name' => 'SEND_COMMAND',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'method\' => \'POST\', \'url\' => \'/session/:sessionId/goog/cdp/execute\']',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 17,
            'startTokenPos' => 28,
            'startFilePos' => 321,
            'endTokenPos' => 44,
            'endFilePos' => 412,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'driver' => 
      array (
        'declaringClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'name' => 'driver',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var RemoteWebDriver
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
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
          'driver' => 
          array (
            'name' => 'driver',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Facebook\\WebDriver\\Remote\\RemoteWebDriver',
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
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver\\Chrome',
        'declaringClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'currentClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'aliasName' => NULL,
      ),
      'execute' => 
      array (
        'name' => 'execute',
        'parameters' => 
        array (
          'command' => 
          array (
            'name' => 'command',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 36,
                'endLine' => 36,
                'startTokenPos' => 95,
                'startFilePos' => 884,
                'endTokenPos' => 96,
                'endFilePos' => 885,
              ),
            ),
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
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 39,
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
 * Executes a Chrome DevTools command
 *
 * @param string $command The DevTools command to execute
 * @param array $parameters Optional parameters to the command
 * @return array The result of the command
 */',
        'startLine' => 36,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver\\Chrome',
        'declaringClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'implementingClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
        'currentClassName' => 'Facebook\\WebDriver\\Chrome\\ChromeDevToolsDriver',
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