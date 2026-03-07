<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Agents/AgentRoutingOutput.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Agents\AgentRoutingOutput
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-eff960e409884b1ebe00508dcfd560f860927a15ec94db02500cf41547a0ec2a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Agents/AgentRoutingOutput.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Agents',
    'name' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
    'shortName' => 'AgentRoutingOutput',
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
    'endLine' => 34,
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
      'agent_type' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'implementingClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'name' => 'agent_type',
        'modifiers' => 1,
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
        'docComment' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'NeuronAI\\StructuredOutput\\SchemaProperty',
            'isRepeated' => false,
            'arguments' => 
            array (
              'description' => 
              array (
                'code' => '\'The domain/type of agent that should handle this query. Options: "content", "shop", "customer", "general"\'',
                'attributes' => 
                array (
                  'startLine' => 12,
                  'endLine' => 12,
                  'startTokenPos' => 33,
                  'startFilePos' => 180,
                  'endTokenPos' => 33,
                  'endFilePos' => 286,
                ),
              ),
              'required' => 
              array (
                'code' => 'true',
                'attributes' => 
                array (
                  'startLine' => 13,
                  'endLine' => 13,
                  'startTokenPos' => 39,
                  'startFilePos' => 307,
                  'endTokenPos' => 39,
                  'endFilePos' => 310,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 11,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'confidence' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'implementingClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'name' => 'confidence',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'NeuronAI\\StructuredOutput\\SchemaProperty',
            'isRepeated' => false,
            'arguments' => 
            array (
              'description' => 
              array (
                'code' => '\'Confidence level in the routing decision (0.0 to 1.0)\'',
                'attributes' => 
                array (
                  'startLine' => 18,
                  'endLine' => 18,
                  'startTokenPos' => 58,
                  'startFilePos' => 394,
                  'endTokenPos' => 58,
                  'endFilePos' => 448,
                ),
              ),
              'required' => 
              array (
                'code' => 'true',
                'attributes' => 
                array (
                  'startLine' => 19,
                  'endLine' => 19,
                  'startTokenPos' => 64,
                  'startFilePos' => 469,
                  'endTokenPos' => 64,
                  'endFilePos' => 472,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 17,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'reasoning' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'implementingClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'name' => 'reasoning',
        'modifiers' => 1,
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
        'docComment' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'NeuronAI\\StructuredOutput\\SchemaProperty',
            'isRepeated' => false,
            'arguments' => 
            array (
              'description' => 
              array (
                'code' => '\'Brief explanation of why this agent was chosen\'',
                'attributes' => 
                array (
                  'startLine' => 24,
                  'endLine' => 24,
                  'startTokenPos' => 83,
                  'startFilePos' => 555,
                  'endTokenPos' => 83,
                  'endFilePos' => 602,
                ),
              ),
              'required' => 
              array (
                'code' => 'true',
                'attributes' => 
                array (
                  'startLine' => 25,
                  'endLine' => 25,
                  'startTokenPos' => 89,
                  'startFilePos' => 623,
                  'endTokenPos' => 89,
                  'endFilePos' => 626,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 23,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'context' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'implementingClassName' => 'Modules\\Ai\\Agents\\AgentRoutingOutput',
        'name' => 'context',
        'modifiers' => 1,
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
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 127,
            'startFilePos' => 841,
            'endTokenPos' => 128,
            'endFilePos' => 842,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'NeuronAI\\StructuredOutput\\SchemaProperty',
            'isRepeated' => false,
            'arguments' => 
            array (
              'description' => 
              array (
                'code' => '\'Any additional context or parameters to pass to the specialized agent\'',
                'attributes' => 
                array (
                  'startLine' => 30,
                  'endLine' => 30,
                  'startTokenPos' => 108,
                  'startFilePos' => 709,
                  'endTokenPos' => 108,
                  'endFilePos' => 779,
                ),
              ),
              'required' => 
              array (
                'code' => 'false',
                'attributes' => 
                array (
                  'startLine' => 31,
                  'endLine' => 31,
                  'startTokenPos' => 114,
                  'startFilePos' => 800,
                  'endTokenPos' => 114,
                  'endFilePos' => 804,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 29,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 31,
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