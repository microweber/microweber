<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/HandleTools.php-PHPStan\BetterReflection\Reflection\ReflectionClass-NeuronAI\HandleTools
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c11e63fa46e3bc12ab203fd4317a4368593cdb6a414230d50e34a1db8ed9feb6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'NeuronAI\\HandleTools',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/HandleTools.php',
      ),
    ),
    'namespace' => 'NeuronAI',
    'name' => 'NeuronAI\\HandleTools',
    'shortName' => 'HandleTools',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 203,
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
      'tools' => 
      array (
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'name' => 'tools',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 138,
            'startFilePos' => 869,
            'endTokenPos' => 139,
            'endFilePos' => 870,
          ),
        ),
        'docComment' => '/**
 * Registered tools.
 *
 * @var ToolInterface[]|ToolkitInterface[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'toolsBootstrapCache' => 
      array (
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'name' => 'toolsBootstrapCache',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 152,
            'startFilePos' => 961,
            'endTokenPos' => 153,
            'endFilePos' => 962,
          ),
        ),
        'docComment' => '/**
 * @var ToolInterface[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'toolMaxTries' => 
      array (
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'name' => 'toolMaxTries',
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
        'default' => 
        array (
          'code' => '5',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 166,
            'startFilePos' => 1055,
            'endTokenPos' => 166,
            'endFilePos' => 1055,
          ),
        ),
        'docComment' => '/**
 * Global max tries for all tools.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'toolAttempts' => 
      array (
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'name' => 'toolAttempts',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 179,
            'startFilePos' => 1142,
            'endTokenPos' => 180,
            'endFilePos' => 1143,
          ),
        ),
        'docComment' => '/**
 * @var array<string, int>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 39,
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
      'toolMaxTries' => 
      array (
        'name' => 'toolMaxTries',
        'parameters' => 
        array (
          'tries' => 
          array (
            'name' => 'tries',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 34,
            'endColumn' => 43,
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
            'name' => 'NeuronAI\\Agent',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 53,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
        'aliasName' => NULL,
      ),
      'tools' => 
      array (
        'name' => 'tools',
        'parameters' => 
        array (
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
 * Override to provide tools to the agent.
 *
 * @return array<ToolInterface|ToolkitInterface|ProviderToolInterface>
 */',
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
        'aliasName' => NULL,
      ),
      'getTools' => 
      array (
        'name' => 'getTools',
        'parameters' => 
        array (
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
 * @return array<ToolInterface|ToolkitInterface|ProviderToolInterface>
 */',
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
        'aliasName' => NULL,
      ),
      'bootstrapTools' => 
      array (
        'name' => 'bootstrapTools',
        'parameters' => 
        array (
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
 * If toolkits have already bootstrapped, this function
 * just traverses the array of tools without any action.
 *
 * @return ToolInterface[]
 */',
        'startLine' => 83,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
        'aliasName' => NULL,
      ),
      'addTool' => 
      array (
        'name' => 'addTool',
        'parameters' => 
        array (
          'tools' => 
          array (
            'name' => 'tools',
            'default' => NULL,
            'type' => 
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
                      'name' => 'NeuronAI\\Tools\\ToolInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'NeuronAI\\Tools\\Toolkits\\ToolkitInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'NeuronAI\\Tools\\ProviderToolInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  3 => 
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 139,
            'endLine' => 139,
            'startColumn' => 29,
            'endColumn' => 93,
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
            'name' => 'NeuronAI\\AgentInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add tools.
 *
 * @param  ToolInterface|ToolkitInterface|ProviderToolInterface|array<ToolInterface|ToolkitInterface|ProviderToolInterface>  $tools
 * @throws AgentException
 */',
        'startLine' => 139,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
        'aliasName' => NULL,
      ),
      'executeTools' => 
      array (
        'name' => 'executeTools',
        'parameters' => 
        array (
          'toolCallMessage' => 
          array (
            'name' => 'toolCallMessage',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'NeuronAI\\Chat\\Messages\\ToolCallMessage',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 167,
            'endLine' => 167,
            'startColumn' => 37,
            'endColumn' => 68,
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
            'name' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Execute all tools from a tool call message.
 *
 * This method can be overridden to implement custom execution strategies,
 * such as concurrent execution, while reusing the single tool execution logic.
 *
 * @param ToolCallMessage $toolCallMessage The message containing tools to execute
 * @return ToolCallResultMessage The result of all tool executions
 * @throws ToolMaxTriesException If a tool exceeds its maximum retry attempts
 * @throws Throwable
 */',
        'startLine' => 167,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
        'aliasName' => NULL,
      ),
      'executeSingleTool' => 
      array (
        'name' => 'executeSingleTool',
        'parameters' => 
        array (
          'tool' => 
          array (
            'name' => 'tool',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'NeuronAI\\Tools\\ToolInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 184,
            'endLine' => 184,
            'startColumn' => 42,
            'endColumn' => 60,
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
 * Execute a single tool with proper error handling and retry logic.
 *
 * @throws ToolMaxTriesException If the tool exceeds its maximum retry attempts
 * @throws Throwable If the tool execution fails
 */',
        'startLine' => 184,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'NeuronAI',
        'declaringClassName' => 'NeuronAI\\HandleTools',
        'implementingClassName' => 'NeuronAI\\HandleTools',
        'currentClassName' => 'NeuronAI\\HandleTools',
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