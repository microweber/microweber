<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/ReplicateAiDriver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Services\Drivers\ReplicateAiDriver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-afbffa2366332061339360722d2843aa9bcd234e0e39fcfa166b973e90027f74',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/ReplicateAiDriver.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Services\\Drivers',
    'name' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
    'shortName' => 'ReplicateAiDriver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 339,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Ai\\Services\\Drivers\\BaseDriver',
    'implementsClassNames' => 
    array (
      0 => 'Modules\\Ai\\Services\\Drivers\\AiImageServiceInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'apiToken' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'name' => 'apiToken',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The Replicate API token.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'apiEndpoint' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'name' => 'apiEndpoint',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\'https://api.replicate.com\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 55,
            'startFilePos' => 466,
            'endTokenPos' => 55,
            'endFilePos' => 492,
          ),
        ),
        'docComment' => '/**
 * The API endpoint for Replicate.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 64,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultImageModel' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'name' => 'defaultImageModel',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\'google/imagen-3\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 68,
            'startFilePos' => 623,
            'endTokenPos' => 68,
            'endFilePos' => 639,
          ),
        ),
        'docComment' => '/**
 * Default model for image generation.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 60,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'useCache' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'name' => 'useCache',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Whether to use caching.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cacheDuration' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'name' => 'cacheDuration',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * Cache duration in minutes.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 33,
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
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 51,
                'endLine' => 51,
                'startTokenPos' => 103,
                'startFilePos' => 1002,
                'endTokenPos' => 104,
                'endFilePos' => 1003,
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 33,
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
 * Create a new Replicate AI driver instance.
 *
 * @param array $config
 */',
        'startLine' => 51,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'aliasName' => NULL,
      ),
      'getDriverName' => 
      array (
        'name' => 'getDriverName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of this driver.
 *
 * @return string
 */',
        'startLine' => 70,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'aliasName' => NULL,
      ),
      'getFileExtensionFromUrl' => 
      array (
        'name' => 'getFileExtensionFromUrl',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 48,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get file extension from URL
 *
 * @param string $url
 * @return string
 */',
        'startLine' => 81,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'aliasName' => NULL,
      ),
      'generateImage' => 
      array (
        'name' => 'generateImage',
        'parameters' => 
        array (
          'messages' => 
          array (
            'name' => 'messages',
            'default' => NULL,
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
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 35,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 102,
                'endLine' => 102,
                'startTokenPos' => 391,
                'startFilePos' => 2442,
                'endTokenPos' => 392,
                'endFilePos' => 2443,
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
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 52,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 102,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'aliasName' => NULL,
      ),
      'fetchImageContent' => 
      array (
        'name' => 'fetchImageContent',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 42,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Fetch image content from URL
 *
 * @param string $url The image URL to fetch
 * @return string Image content
 * @throws \\Exception
 */',
        'startLine' => 240,
        'endLine' => 268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'aliasName' => NULL,
      ),
      'makeRequest' => 
      array (
        'name' => 'makeRequest',
        'parameters' => 
        array (
          'endpoint' => 
          array (
            'name' => 'endpoint',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 36,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 279,
                'endLine' => 279,
                'startTokenPos' => 1594,
                'startFilePos' => 9161,
                'endTokenPos' => 1595,
                'endFilePos' => 9162,
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
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 54,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'method' => 
          array (
            'name' => 'method',
            'default' => 
            array (
              'code' => '\'POST\'',
              'attributes' => 
              array (
                'startLine' => 279,
                'endLine' => 279,
                'startTokenPos' => 1604,
                'startFilePos' => 9182,
                'endTokenPos' => 1604,
                'endFilePos' => 9187,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 279,
            'endLine' => 279,
            'startColumn' => 72,
            'endColumn' => 94,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Make an API request to Replicate.
 *
 * @param string $endpoint The endpoint path
 * @param array $data The payload to send
 * @param string $method HTTP method (POST, GET, etc.)
 * @return array The response data
 * @throws \\Exception
 */',
        'startLine' => 279,
        'endLine' => 324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'aliasName' => NULL,
      ),
      'sendToChat' => 
      array (
        'name' => 'sendToChat',
        'parameters' => 
        array (
          'messages' => 
          array (
            'name' => 'messages',
            'default' => NULL,
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 32,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 335,
                'endLine' => 335,
                'startTokenPos' => 1937,
                'startFilePos' => 10994,
                'endTokenPos' => 1938,
                'endFilePos' => 10995,
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 49,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Send messages to chat and get a response.
 * Not implemented for this driver as it\'s primarily for image generation.
 *
 * @param array $messages Array of messages
 * @param array $options Additional options
 * @return string|array
 * @throws \\Exception
 */',
        'startLine' => 335,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\ReplicateAiDriver',
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