<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/Providers/HandleWithTools.php-PHPStan\BetterReflection\Reflection\ReflectionClass-NeuronAI\Providers\HandleWithTools
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-528f7c63b7d44b6ae8570d14ea430f1374dc72b869b223d9d483745f2da69f37-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'NeuronAI\\Providers\\HandleWithTools',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/Providers/HandleWithTools.php',
      ),
    ),
    'namespace' => 'NeuronAI\\Providers',
    'name' => 'NeuronAI\\Providers\\HandleWithTools',
    'shortName' => 'HandleWithTools',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 44,
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
        'declaringClassName' => 'NeuronAI\\Providers\\HandleWithTools',
        'implementingClassName' => 'NeuronAI\\Providers\\HandleWithTools',
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
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 53,
            'startFilePos' => 417,
            'endTokenPos' => 54,
            'endFilePos' => 418,
          ),
        ),
        'docComment' => '/**
 * It can contain Neuron Tool instances or tool providers definitions
 *
 * @var array<ToolInterface|ProviderToolInterface>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 32,
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
      'setTools' => 
      array (
        'name' => 'setTools',
        'parameters' => 
        array (
          'tools' => 
          array (
            'name' => 'tools',
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
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 30,
            'endColumn' => 41,
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
            'name' => 'NeuronAI\\Providers\\AIProviderInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Providers',
        'declaringClassName' => 'NeuronAI\\Providers\\HandleWithTools',
        'implementingClassName' => 'NeuronAI\\Providers\\HandleWithTools',
        'currentClassName' => 'NeuronAI\\Providers\\HandleWithTools',
        'aliasName' => NULL,
      ),
      'findTool' => 
      array (
        'name' => 'findTool',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 30,
            'endColumn' => 41,
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
            'name' => 'NeuronAI\\Tools\\ToolInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Providers',
        'declaringClassName' => 'NeuronAI\\Providers\\HandleWithTools',
        'implementingClassName' => 'NeuronAI\\Providers\\HandleWithTools',
        'currentClassName' => 'NeuronAI\\Providers\\HandleWithTools',
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