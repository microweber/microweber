<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/WaitsForElements.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Dusk\Concerns\WaitsForElements
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ae4cc3b01f28e80da77d82ee706d6f4362fbd74445d75c5e8bb5d12d1122758e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Concerns/WaitsForElements.php',
      ),
    ),
    'namespace' => 'Laravel\\Dusk\\Concerns',
    'name' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
    'shortName' => 'WaitsForElements',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 434,
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
      'whenAvailable' => 
      array (
        'name' => 'whenAvailable',
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
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 46,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 26,
                'endLine' => 26,
                'startTokenPos' => 73,
                'startFilePos' => 743,
                'endTokenPos' => 73,
                'endFilePos' => 746,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 65,
            'endColumn' => 79,
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
 * Execute the given callback in a scoped browser once the selector is available.
 *
 * @param  string  $selector
 * @param  \\Closure  $callback
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 26,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitFor' => 
      array (
        'name' => 'waitFor',
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
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 40,
                'endLine' => 40,
                'startTokenPos' => 116,
                'startFilePos' => 1126,
                'endTokenPos' => 116,
                'endFilePos' => 1129,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 40,
            'endColumn' => 54,
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
 * Wait for the given selector to become visible.
 *
 * @param  string  $selector
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 40,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntilMissing' => 
      array (
        'name' => 'waitUntilMissing',
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
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 38,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 58,
                'endLine' => 58,
                'startTokenPos' => 201,
                'startFilePos' => 1701,
                'endTokenPos' => 201,
                'endFilePos' => 1704,
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
            'startColumn' => 49,
            'endColumn' => 63,
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
 * Wait for the given selector to be removed.
 *
 * @param  string  $selector
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 58,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntilMissingText' => 
      array (
        'name' => 'waitUntilMissingText',
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
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 42,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 82,
                'endLine' => 82,
                'startTokenPos' => 320,
                'startFilePos' => 2434,
                'endTokenPos' => 320,
                'endFilePos' => 2437,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 49,
            'endColumn' => 63,
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
 * Wait for the given text to be removed.
 *
 * @param  string  $text
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 82,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForText' => 
      array (
        'name' => 'waitForText',
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
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 103,
                'endLine' => 103,
                'startTokenPos' => 433,
                'startFilePos' => 3099,
                'endTokenPos' => 433,
                'endFilePos' => 3102,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'ignoreCase' => 
          array (
            'name' => 'ignoreCase',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 103,
                'endLine' => 103,
                'startTokenPos' => 440,
                'startFilePos' => 3119,
                'endTokenPos' => 440,
                'endFilePos' => 3123,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 57,
            'endColumn' => 75,
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
 * Wait for the given text to become visible.
 *
 * @param  array|string  $text
 * @param  int|null  $seconds
 * @param  bool  $ignoreCase
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 103,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForTextIn' => 
      array (
        'name' => 'waitForTextIn',
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
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 35,
            'endColumn' => 43,
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
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 46,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 125,
                'endLine' => 125,
                'startTokenPos' => 560,
                'startFilePos' => 3870,
                'endTokenPos' => 560,
                'endFilePos' => 3873,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 53,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'ignoreCase' => 
          array (
            'name' => 'ignoreCase',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 125,
                'endLine' => 125,
                'startTokenPos' => 567,
                'startFilePos' => 3890,
                'endTokenPos' => 567,
                'endFilePos' => 3894,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 70,
            'endColumn' => 88,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Wait for the given text to become visible inside the given selector.
 *
 * @param  string  $selector
 * @param  array|string  $text
 * @param  int|null  $seconds
 * @param  bool  $ignoreCase
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 125,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForLink' => 
      array (
        'name' => 'waitForLink',
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
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 661,
                'startFilePos' => 4492,
                'endTokenPos' => 661,
                'endFilePos' => 4495,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 40,
            'endColumn' => 54,
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
 * Wait for the given link to become visible.
 *
 * @param  string  $link
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 143,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForInput' => 
      array (
        'name' => 'waitForInput',
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
            'startLine' => 159,
            'endLine' => 159,
            'startColumn' => 34,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 159,
                'endLine' => 159,
                'startTokenPos' => 740,
                'startFilePos' => 4944,
                'endTokenPos' => 740,
                'endFilePos' => 4947,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 159,
            'endLine' => 159,
            'startColumn' => 42,
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
 * Wait for an input field to become visible.
 *
 * @param  string  $field
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 159,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForLocation' => 
      array (
        'name' => 'waitForLocation',
        'parameters' => 
        array (
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
            'startLine' => 173,
            'endLine' => 173,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 173,
                'endLine' => 173,
                'startTokenPos' => 789,
                'startFilePos' => 5348,
                'endTokenPos' => 789,
                'endFilePos' => 5351,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 173,
            'endLine' => 173,
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
 * Wait for the given location.
 *
 * @param  string  $path
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 173,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForRoute' => 
      array (
        'name' => 'waitForRoute',
        'parameters' => 
        array (
          'route' => 
          array (
            'name' => 'route',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 192,
            'endLine' => 192,
            'startColumn' => 34,
            'endColumn' => 39,
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
                'startLine' => 192,
                'endLine' => 192,
                'startTokenPos' => 884,
                'startFilePos' => 6067,
                'endTokenPos' => 885,
                'endFilePos' => 6068,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 192,
            'endLine' => 192,
            'startColumn' => 42,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 192,
                'endLine' => 192,
                'startTokenPos' => 892,
                'startFilePos' => 6082,
                'endTokenPos' => 892,
                'endFilePos' => 6085,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 192,
            'endLine' => 192,
            'startColumn' => 60,
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
 * Wait for the given location using a named route.
 *
 * @param  string  $route
 * @param  array  $parameters
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 192,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntilEnabled' => 
      array (
        'name' => 'waitUntilEnabled',
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
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 38,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 204,
                'endLine' => 204,
                'startTokenPos' => 936,
                'startFilePos' => 6396,
                'endTokenPos' => 936,
                'endFilePos' => 6399,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 49,
            'endColumn' => 63,
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
 * Wait until an element is enabled.
 *
 * @param  string  $selector
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 204,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntilDisabled' => 
      array (
        'name' => 'waitUntilDisabled',
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
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 1024,
                'startFilePos' => 6922,
                'endTokenPos' => 1024,
                'endFilePos' => 6925,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 50,
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
 * Wait until an element is disabled.
 *
 * @param  string  $selector
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 222,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntil' => 
      array (
        'name' => 'waitUntil',
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
            'startLine' => 243,
            'endLine' => 243,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 243,
                'endLine' => 243,
                'startTokenPos' => 1114,
                'startFilePos' => 7552,
                'endTokenPos' => 1114,
                'endFilePos' => 7555,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 243,
            'endLine' => 243,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 243,
                'endLine' => 243,
                'startTokenPos' => 1121,
                'startFilePos' => 7569,
                'endTokenPos' => 1121,
                'endFilePos' => 7572,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 243,
            'endLine' => 243,
            'startColumn' => 57,
            'endColumn' => 71,
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
 * Wait until the given script returns true.
 *
 * @param  string  $script
 * @param  int|null  $seconds
 * @param  string|null  $message
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 243,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntilVue' => 
      array (
        'name' => 'waitUntilVue',
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
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 34,
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
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 40,
            'endColumn' => 45,
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
                'startLine' => 267,
                'endLine' => 267,
                'startTokenPos' => 1248,
                'startFilePos' => 8276,
                'endTokenPos' => 1248,
                'endFilePos' => 8279,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 48,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 267,
                'endLine' => 267,
                'startTokenPos' => 1255,
                'startFilePos' => 8293,
                'endTokenPos' => 1255,
                'endFilePos' => 8296,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 75,
            'endColumn' => 89,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Wait until the Vue component\'s attribute at the given key has the given value.
 *
 * @param  string  $key
 * @param  string  $value
 * @param  string|null  $componentSelector
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 267,
        'endLine' => 274,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUntilVueIsNot' => 
      array (
        'name' => 'waitUntilVueIsNot',
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
            'startLine' => 285,
            'endLine' => 285,
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
            'startLine' => 285,
            'endLine' => 285,
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
                'startLine' => 285,
                'endLine' => 285,
                'startTokenPos' => 1335,
                'startFilePos' => 8868,
                'endTokenPos' => 1335,
                'endFilePos' => 8871,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 285,
            'endLine' => 285,
            'startColumn' => 53,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 285,
                'endLine' => 285,
                'startTokenPos' => 1342,
                'startFilePos' => 8885,
                'endTokenPos' => 1342,
                'endFilePos' => 8888,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 285,
            'endLine' => 285,
            'startColumn' => 80,
            'endColumn' => 94,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Wait until the Vue component\'s attribute at the given key does not have the given value.
 *
 * @param  string  $key
 * @param  string  $value
 * @param  string|null  $componentSelector
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 285,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForDialog' => 
      array (
        'name' => 'waitForDialog',
        'parameters' => 
        array (
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 300,
                'endLine' => 300,
                'startTokenPos' => 1416,
                'startFilePos' => 9276,
                'endTokenPos' => 1416,
                'endFilePos' => 9279,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 300,
            'endLine' => 300,
            'startColumn' => 35,
            'endColumn' => 49,
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
 * Wait for a JavaScript dialog to open.
 *
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 300,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForReload' => 
      array (
        'name' => 'waitForReload',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 320,
                'endLine' => 320,
                'startTokenPos' => 1493,
                'startFilePos' => 9828,
                'endTokenPos' => 1493,
                'endFilePos' => 9831,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 35,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 320,
                'endLine' => 320,
                'startTokenPos' => 1500,
                'startFilePos' => 9845,
                'endTokenPos' => 1500,
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
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 53,
            'endColumn' => 67,
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
 * Wait for the current page to reload.
 *
 * @param  \\Closure|null  $callback
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 320,
        'endLine' => 333,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'clickAndWaitForReload' => 
      array (
        'name' => 'clickAndWaitForReload',
        'parameters' => 
        array (
          'selector' => 
          array (
            'name' => 'selector',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 342,
                'endLine' => 342,
                'startTokenPos' => 1612,
                'startFilePos' => 10483,
                'endTokenPos' => 1612,
                'endFilePos' => 10486,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 342,
                'endLine' => 342,
                'startTokenPos' => 1619,
                'startFilePos' => 10500,
                'endTokenPos' => 1619,
                'endFilePos' => 10503,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 61,
            'endColumn' => 75,
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
 * Click an element and wait for the page to reload.
 *
 * @param  string|null  $selector
 * @param  int|null  $seconds
 * @return $this
 */',
        'startLine' => 342,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitForEvent' => 
      array (
        'name' => 'waitForEvent',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 359,
            'endLine' => 359,
            'startColumn' => 34,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'target' => 
          array (
            'name' => 'target',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 359,
                'endLine' => 359,
                'startTokenPos' => 1676,
                'startFilePos' => 10976,
                'endTokenPos' => 1676,
                'endFilePos' => 10979,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 359,
            'endLine' => 359,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 359,
                'endLine' => 359,
                'startTokenPos' => 1683,
                'startFilePos' => 10993,
                'endTokenPos' => 1683,
                'endFilePos' => 10996,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 359,
            'endLine' => 359,
            'startColumn' => 57,
            'endColumn' => 71,
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
 * Wait for the given event type to occur on a target.
 *
 * @param  string  $type
 * @param  string|null  $target
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 359,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'waitUsing' => 
      array (
        'name' => 'waitUsing',
        'parameters' => 
        array (
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 52,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 392,
                'endLine' => 392,
                'startTokenPos' => 1854,
                'startFilePos' => 12061,
                'endTokenPos' => 1854,
                'endFilePos' => 12064,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 71,
            'endColumn' => 85,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Wait for the given callback to be true.
 *
 * @param  int|null  $seconds
 * @param  int  $interval
 * @param  \\Closure  $callback
 * @param  string|null  $message
 * @return $this
 *
 * @throws \\Facebook\\WebDriver\\Exception\\TimeoutException
 */',
        'startLine' => 392,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'formatTimeOutMessage' => 
      array (
        'name' => 'formatTimeOutMessage',
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
            'startLine' => 419,
            'endLine' => 419,
            'startColumn' => 45,
            'endColumn' => 52,
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
            'startLine' => 419,
            'endLine' => 419,
            'startColumn' => 55,
            'endColumn' => 63,
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
 * Prepare custom TimeoutException message for sprintf().
 *
 * @param  string  $message
 * @param  string  $expected
 * @return string
 */',
        'startLine' => 419,
        'endLine' => 422,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'aliasName' => NULL,
      ),
      'escapePercentCharacters' => 
      array (
        'name' => 'escapePercentCharacters',
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
            'startLine' => 430,
            'endLine' => 430,
            'startColumn' => 48,
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
        'docComment' => '/**
 * Escape percent characters in preparation for sending the given message to "sprintf".
 *
 * @param  string  $message
 * @return string
 */',
        'startLine' => 430,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Laravel\\Dusk\\Concerns',
        'declaringClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'implementingClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
        'currentClassName' => 'Laravel\\Dusk\\Concerns\\WaitsForElements',
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