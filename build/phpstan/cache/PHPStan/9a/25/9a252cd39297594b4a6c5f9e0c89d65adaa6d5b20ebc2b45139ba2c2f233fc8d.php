<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/OpenAiDriver.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Services\Drivers\OpenAiDriver
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-d544dc5479a0c28266924ab20d19f32b94e454ff554348f3c6c1ad09ca3e23c1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/OpenAiDriver.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Services\\Drivers',
    'name' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
    'shortName' => 'OpenAiDriver',
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
    'endLine' => 182,
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
      'client' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'name' => 'client',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Client',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The OpenAI client instance.
 *
 * @var Client
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'model' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'name' => 'model',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'gpt-4o-mini\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 47,
            'startFilePos' => 296,
            'endTokenPos' => 47,
            'endFilePos' => 308,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'useCache' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
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
 * Whether to use caching or not.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
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
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
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
        'startLine' => 32,
        'endLine' => 32,
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
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 82,
                'startFilePos' => 672,
                'endTokenPos' => 83,
                'endFilePos' => 673,
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
            'startLine' => 39,
            'endLine' => 39,
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
 * Create a new OpenAI driver instance.
 *
 * @param array $config
 */',
        'startLine' => 39,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
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
            'startLine' => 84,
            'endLine' => 84,
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
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 250,
                'startFilePos' => 2807,
                'endTokenPos' => 251,
                'endFilePos' => 2808,
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
            'startLine' => 84,
            'endLine' => 84,
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
 * @param array $messages Array of messages in the format:
 *                       [
 *                           [\'role\' => \'system\', \'content\' => \'System message\'],
 *                           [\'role\' => \'user\', \'content\' => \'User message\'],
 *                           [\'role\' => \'assistant\', \'content\' => \'Assistant response\'],
 *                           [\'role\' => \'function\', \'name\' => \'function_name\', \'content\' => \'Function response\']
 *                       ]
 * @param array $options Additional options including:
 *                      - functions: Array of function definitions for the AI to call
 *                      - function_call: Optional specific function to call
 *                      - tools: Array of tool definitions (newer API format)
 *                      - tool_choice: Optional specific tool to use
 *                      - model: AI model to use
 *                      - temperature: Sampling temperature
 *                      - max_tokens: Maximum tokens in response
 * @param array|null $schema JSON schema for response formatting
 * @return string|array The generated content or function call response array containing:
 *                      [\'function_call\' => object, \'content\' => ?string]
 *                      or a JSON formatted response based on the provided schema
 */',
        'startLine' => 84,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
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
        'startLine' => 178,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\OpenAiDriver',
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