<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/Chat/History/ChatHistoryInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-NeuronAI\Chat\History\ChatHistoryInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-e0a486d0131ec43d477365b01bfcf516f1fe4528e04f1145b9bf77da82f48527-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../inspector-apm/neuron-ai/src/Chat/History/ChatHistoryInterface.php',
      ),
    ),
    'namespace' => 'NeuronAI\\Chat\\History',
    'name' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
    'shortName' => 'ChatHistoryInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 29,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'JsonSerializable',
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
      'addMessage' => 
      array (
        'name' => 'addMessage',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'NeuronAI\\Chat\\Messages\\Message',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 12,
            'endLine' => 12,
            'startColumn' => 32,
            'endColumn' => 47,
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
            'name' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\History',
        'declaringClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'implementingClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'currentClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'aliasName' => NULL,
      ),
      'getMessages' => 
      array (
        'name' => 'getMessages',
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
 * @return Message[]
 */',
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\History',
        'declaringClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'implementingClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'currentClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'aliasName' => NULL,
      ),
      'getLastMessage' => 
      array (
        'name' => 'getLastMessage',
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
                  'name' => 'NeuronAI\\Chat\\Messages\\Message',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
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
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\History',
        'declaringClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'implementingClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'currentClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'aliasName' => NULL,
      ),
      'setMessages' => 
      array (
        'name' => 'setMessages',
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
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 33,
            'endColumn' => 47,
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
            'name' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param Message[] $messages
 */',
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\History',
        'declaringClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'implementingClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'currentClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'aliasName' => NULL,
      ),
      'flushAll' => 
      array (
        'name' => 'flushAll',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\History',
        'declaringClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'implementingClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'currentClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'aliasName' => NULL,
      ),
      'calculateTotalUsage' => 
      array (
        'name' => 'calculateTotalUsage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'NeuronAI\\Chat\\History',
        'declaringClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'implementingClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
        'currentClassName' => 'NeuronAI\\Chat\\History\\ChatHistoryInterface',
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