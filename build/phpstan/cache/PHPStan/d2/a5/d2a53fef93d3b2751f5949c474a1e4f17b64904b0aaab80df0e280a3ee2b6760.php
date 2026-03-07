<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/Chat/Messages/ToolCallResultMessage.php-PHPStan\BetterReflection\Reflection\ReflectionClass-NeuronAI\Chat\Messages\ToolCallResultMessage
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0cf6546bfe0cb37a5f9cf8be5fd9e87afb14c69ce2fb3976c8687315dec7feac-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/Chat/Messages/ToolCallResultMessage.php',
      ),
    ),
    'namespace' => 'NeuronAI\\Chat\\Messages',
    'name' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
    'shortName' => 'ToolCallResultMessage',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static static make(ToolInterface[] $tools)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 17,
    'endLine' => 50,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'NeuronAI\\Chat\\Messages\\UserMessage',
    'implementsClassNames' => 
    array (
      0 => 'Stringable',
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
        'declaringClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'implementingClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
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
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 33,
        'endColumn' => 54,
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
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 33,
            'endColumn' => 54,
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
 * @param array<ToolInterface> $tools
 */',
        'startLine' => 22,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\Messages',
        'declaringClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'implementingClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'currentClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
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
 * @return array<ToolInterface>
 */',
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\Messages',
        'declaringClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'implementingClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'currentClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'aliasName' => NULL,
      ),
      'jsonSerialize' => 
      array (
        'name' => 'jsonSerialize',
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
        'startLine' => 35,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\Messages',
        'declaringClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'implementingClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'currentClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
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
        'docComment' => NULL,
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\Messages',
        'declaringClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'implementingClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
        'currentClassName' => 'NeuronAI\\Chat\\Messages\\ToolCallResultMessage',
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