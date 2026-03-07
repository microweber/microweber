<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/JavaScriptExecutor.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Facebook\WebDriver\JavaScriptExecutor
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8a1a7a9dcb70f3529a4b85d61c531f8d15137eb39f118c9828cde560baf568e2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Facebook\\WebDriver\\JavaScriptExecutor',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../php-webdriver/webdriver/lib/JavaScriptExecutor.php',
      ),
    ),
    'namespace' => 'Facebook\\WebDriver',
    'name' => 'Facebook\\WebDriver\\JavaScriptExecutor',
    'shortName' => 'JavaScriptExecutor',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * WebDriver interface implemented by drivers that support JavaScript.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 35,
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
    ),
    'immediateMethods' => 
    array (
      'executeScript' => 
      array (
        'name' => 'executeScript',
        'parameters' => 
        array (
          'script' => 
          array (
            'name' => 'script',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 35,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 19,
                'endLine' => 19,
                'startTokenPos' => 32,
                'startFilePos' => 632,
                'endTokenPos' => 33,
                'endFilePos' => 633,
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
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 44,
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
 * Inject a snippet of JavaScript into the page for execution in the context
 * of the currently selected frame. The executed script is assumed to be
 * synchronous and the result of evaluating the script will be returned.
 *
 * @param string $script The script to inject.
 * @param array $arguments The arguments of the script.
 * @return mixed The return value of the script.
 */',
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 66,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\JavaScriptExecutor',
        'implementingClassName' => 'Facebook\\WebDriver\\JavaScriptExecutor',
        'currentClassName' => 'Facebook\\WebDriver\\JavaScriptExecutor',
        'aliasName' => NULL,
      ),
      'executeAsyncScript' => 
      array (
        'name' => 'executeAsyncScript',
        'parameters' => 
        array (
          'script' => 
          array (
            'name' => 'script',
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
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 34,
                'endLine' => 34,
                'startTokenPos' => 54,
                'startFilePos' => 1228,
                'endTokenPos' => 55,
                'endFilePos' => 1229,
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 49,
            'endColumn' => 69,
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
 * Inject a snippet of JavaScript into the page for asynchronous execution in
 * the context of the currently selected frame.
 *
 * The driver will pass a callback as the last argument to the snippet, and
 * block until the callback is invoked.
 *
 * @see WebDriverExecuteAsyncScriptTestCase
 *
 * @param string $script The script to inject.
 * @param array $arguments The arguments of the script.
 * @return mixed The value passed by the script to the callback.
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Facebook\\WebDriver',
        'declaringClassName' => 'Facebook\\WebDriver\\JavaScriptExecutor',
        'implementingClassName' => 'Facebook\\WebDriver\\JavaScriptExecutor',
        'currentClassName' => 'Facebook\\WebDriver\\JavaScriptExecutor',
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