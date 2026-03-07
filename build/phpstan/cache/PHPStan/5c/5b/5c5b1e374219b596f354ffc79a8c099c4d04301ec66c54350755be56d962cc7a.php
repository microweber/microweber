<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Ai/Workflows/GeneralAgentWorkflow.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Ai\Workflows\GeneralAgentWorkflow
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-b4c44e98992e7154dc9c3b07f4acc3db577c4db2d1fb75d6cd8c1a314e443c43',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Ai/Workflows/GeneralAgentWorkflow.php',
      ),
    ),
    'namespace' => 'Modules\\Ai\\Workflows',
    'name' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
    'shortName' => 'GeneralAgentWorkflow',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 35,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'NeuronAI\\Workflow\\Workflow',
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
      'agentFactory' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'implementingClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'name' => 'agentFactory',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Ai\\Services\\AgentFactory',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 9,
        'endColumn' => 44,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'routingAgent' => 
      array (
        'declaringClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'implementingClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'name' => 'routingAgent',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Modules\\Ai\\Agents\\BaseAgent',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 9,
        'endColumn' => 41,
        'isPromoted' => true,
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
          'userQuery' => 
          array (
            'name' => 'userQuery',
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
            'startLine' => 21,
            'endLine' => 21,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'agentFactory' => 
          array (
            'name' => 'agentFactory',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Ai\\Services\\AgentFactory',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 9,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'routingAgent' => 
          array (
            'name' => 'routingAgent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\Ai\\Agents\\BaseAgent',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 9,
            'endColumn' => 41,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws WorkflowException
 */',
        'startLine' => 20,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Ai\\Workflows',
        'declaringClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'implementingClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'currentClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'aliasName' => NULL,
      ),
      'nodes' => 
      array (
        'name' => 'nodes',
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
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Modules\\Ai\\Workflows',
        'declaringClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'implementingClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
        'currentClassName' => 'Modules\\Ai\\Workflows\\GeneralAgentWorkflow',
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