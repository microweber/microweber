<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Chrome/SupportsChrome.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Dusk\Chrome\SupportsChrome
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0176adfb1e323b6d657812b3b7afa49034cf6717c0ce317c3c0bcb305f7a8864-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/dusk/src/Chrome/SupportsChrome.php',
      ),
    ),
    'namespace' => 'Laravel\\Dusk\\Chrome',
    'name' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
    'shortName' => 'SupportsChrome',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 75,
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
      'chromeDriver' => 
      array (
        'declaringClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'implementingClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'name' => 'chromeDriver',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The path to the custom Chromedriver binary.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'chromeProcess' => 
      array (
        'declaringClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'implementingClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'name' => 'chromeProcess',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The Chromedriver process instance.
 *
 * @var \\Symfony\\Component\\Process\\Process
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 36,
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
      'startChromeDriver' => 
      array (
        'name' => 'startChromeDriver',
        'parameters' => 
        array (
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 29,
                'endLine' => 29,
                'startTokenPos' => 47,
                'startFilePos' => 566,
                'endTokenPos' => 48,
                'endFilePos' => 567,
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
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 46,
            'endColumn' => 66,
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
 * Start the Chromedriver process.
 *
 * @param  array  $arguments
 * @return void
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 29,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Dusk\\Chrome',
        'declaringClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'implementingClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'currentClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'aliasName' => NULL,
      ),
      'stopChromeDriver' => 
      array (
        'name' => 'stopChromeDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stop the Chromedriver process.
 *
 * @return void
 */',
        'startLine' => 45,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Dusk\\Chrome',
        'declaringClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'implementingClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'currentClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'aliasName' => NULL,
      ),
      'buildChromeProcess' => 
      array (
        'name' => 'buildChromeProcess',
        'parameters' => 
        array (
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 60,
                'endLine' => 60,
                'startTokenPos' => 153,
                'startFilePos' => 1288,
                'endTokenPos' => 154,
                'endFilePos' => 1289,
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
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 50,
            'endColumn' => 70,
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
 * Build the process to run the Chromedriver.
 *
 * @param  array  $arguments
 * @return \\Symfony\\Component\\Process\\Process
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Laravel\\Dusk\\Chrome',
        'declaringClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'implementingClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'currentClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'aliasName' => NULL,
      ),
      'useChromedriver' => 
      array (
        'name' => 'useChromedriver',
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
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 44,
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
 * Set the path to the custom Chromedriver.
 *
 * @param  string  $path
 * @return void
 */',
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Laravel\\Dusk\\Chrome',
        'declaringClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'implementingClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
        'currentClassName' => 'Laravel\\Dusk\\Chrome\\SupportsChrome',
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