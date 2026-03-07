<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Models/AgentChatMessage.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Models\AgentChatMessage
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-574da4a086bcf4213bfbd485a684dc950fe434d4c37a1c8221e4ac7f4b6419d3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Models/AgentChatMessage.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Models',
    'name' => 'Modules\\Ai\\Models\\AgentChatMessage',
    'shortName' => 'AgentChatMessage',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 87,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'chat_id\', \'role\', \'content\', \'metadata\', \'agent_type\', \'processed_at\']',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 27,
            'startTokenPos' => 79,
            'startFilePos' => 512,
            'endTokenPos' => 99,
            'endFilePos' => 638,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'metadata\' => \'array\', \'processed_at\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 32,
            'startTokenPos' => 108,
            'startFilePos' => 665,
            'endTokenPos' => 124,
            'endFilePos' => 740,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Ai\\Database\\Factories\\AgentChatMessageFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 15,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'chat' => 
      array (
        'name' => 'chat',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'searches' => 
      array (
        'name' => 'searches',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'scopeByRole' => 
      array (
        'name' => 'scopeByRole',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'role' => 
          array (
            'name' => 'role',
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 41,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'scopeByAgentType' => 
      array (
        'name' => 'scopeByAgentType',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 38,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'agentType' => 
          array (
            'name' => 'agentType',
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
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 46,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 49,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'scopeProcessed' => 
      array (
        'name' => 'scopeProcessed',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 36,
            'endColumn' => 41,
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
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'scopeUnprocessed' => 
      array (
        'name' => 'scopeUnprocessed',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 38,
            'endColumn' => 43,
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
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'isUser' => 
      array (
        'name' => 'isUser',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 64,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'isAssistant' => 
      array (
        'name' => 'isAssistant',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'isSystem' => 
      array (
        'name' => 'isSystem',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'aliasName' => NULL,
      ),
      'getProcessingTime' => 
      array (
        'name' => 'getProcessingTime',
        'parameters' => 
        array (
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
                  'name' => 'float',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 79,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Models',
        'declaringClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'implementingClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
        'currentClassName' => 'Modules\\Ai\\Models\\AgentChatMessage',
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