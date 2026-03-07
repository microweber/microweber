<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/AiServiceInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Services\Drivers\AiServiceInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-a20fb4a22f8c962580a2ea795ad389bdc060f18a38178b511537d144e0c47b41',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Services/Drivers/AiServiceInterface.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Services\\Drivers',
    'name' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
    'shortName' => 'AiServiceInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 42,
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
            'startLine' => 26,
            'endLine' => 26,
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
                'startLine' => 26,
                'endLine' => 26,
                'startTokenPos' => 32,
                'startFilePos' => 1305,
                'endTokenPos' => 33,
                'endFilePos' => 1306,
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
            'startLine' => 26,
            'endLine' => 26,
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
 *                      - model: AI model to use
 *                      - temperature: Sampling temperature
 *                      - max_tokens: Maximum tokens in response
 * @return string|array The generated content or function call response array containing:
 *                      [\'function_call\' => object, \'content\' => ?string]
 */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 83,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'aliasName' => NULL,
      ),
      'getActiveDriver' => 
      array (
        'name' => 'getActiveDriver',
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
 * Get the name of the currently active AI driver.
 *
 * @return string
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'aliasName' => NULL,
      ),
      'setActiveDriver' => 
      array (
        'name' => 'setActiveDriver',
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 37,
            'endColumn' => 50,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the active AI driver.
 *
 * @param string $driver
 * @return void
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Services\\Drivers',
        'declaringClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'implementingClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
        'currentClassName' => 'Modules\\Ai\\Services\\Drivers\\AiServiceInterface',
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