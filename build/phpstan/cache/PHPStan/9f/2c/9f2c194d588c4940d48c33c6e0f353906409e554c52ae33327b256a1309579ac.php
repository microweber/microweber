<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/OllamaAiDriver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Services\Drivers\OllamaAiDriver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-16bbd38557194034981e2dfdbdc944d5c08ad40a3145c437ebfc9948529c3424',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/OllamaAiDriver.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Services\\Drivers',
    'name' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
    'shortName' => 'OllamaAiDriver',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 211,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Modules\\Ai\\Services\\Drivers\\BaseDriver',
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
      'apiUrl' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'name' => 'apiUrl',
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
 * The Ollama API URL
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultModel' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'name' => 'defaultModel',
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
 * The default model to use
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'useCache' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
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
 * Whether to use caching
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
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
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
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
 * Cache duration in minutes
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
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
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 77,
                'startFilePos' => 725,
                'endTokenPos' => 78,
                'endFilePos' => 726,
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
            'startLine' => 43,
            'endLine' => 43,
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
 * Create a new Ollama driver instance.
 *
 * @param array $config
 */',
        'startLine' => 43,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
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
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
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
            'startLine' => 70,
            'endLine' => 70,
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
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 210,
                'startFilePos' => 1536,
                'endTokenPos' => 211,
                'endFilePos' => 1537,
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
            'startLine' => 70,
            'endLine' => 70,
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
 *
 * @param array $messages Array of messages
 * @param array $options Additional options
 * @return string|array The generated content
 */',
        'startLine' => 70,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'aliasName' => NULL,
      ),
      'formatMessagesForOllama' => 
      array (
        'name' => 'formatMessagesForOllama',
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
            'startLine' => 145,
            'endLine' => 145,
            'startColumn' => 48,
            'endColumn' => 62,
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
 * Format messages for Ollama API.
 *
 * @param array $messages
 * @return string
 */',
        'startLine' => 145,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'aliasName' => NULL,
      ),
      'makeRequest' => 
      array (
        'name' => 'makeRequest',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 180,
            'endLine' => 180,
            'startColumn' => 36,
            'endColumn' => 46,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Make a request to the Ollama API.
 *
 * @param array $data The payload to send
 * @return array The response from Ollama
 * @throws \\Exception If the request fails
 */',
        'startLine' => 180,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OllamaAiDriver',
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