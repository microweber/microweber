<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../openai-php/client/src/Client.php-PHPStan\BetterReflection\Reflection\ReflectionClass-OpenAI\Client
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-11a3a28ff4e560b6db607459a9c6022996b8810fe3594a4c56225a9375921136-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'OpenAI\\Client',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../openai-php/client/src/Client.php',
      ),
    ),
    'namespace' => 'OpenAI',
    'name' => 'OpenAI\\Client',
    'shortName' => 'Client',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 27,
    'endLine' => 189,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'OpenAI\\Contracts\\ClientContract',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'transporter' => 
      array (
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'name' => 'transporter',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Contracts\\TransporterContract',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 33,
        'endColumn' => 81,
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
          'transporter' => 
          array (
            'name' => 'transporter',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'OpenAI\\Contracts\\TransporterContract',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 33,
            'endColumn' => 81,
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
 * Creates a Client instance with the given API token.
 */',
        'startLine' => 32,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'completions' => 
      array (
        'name' => 'completions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Completions',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Given a prompt, the model will return one or more predicted completions, and can also return the probabilities
 * of alternative tokens at each position.
 *
 * @see https://platform.openai.com/docs/api-reference/completions
 */',
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
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
            'name' => 'OpenAI\\Resources\\Chat',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Given a chat conversation, the model will return a chat completion response.
 *
 * @see https://platform.openai.com/docs/api-reference/chat
 */',
        'startLine' => 53,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'embeddings' => 
      array (
        'name' => 'embeddings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Embeddings',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a vector representation of a given input that can be easily consumed by machine learning models and algorithms.
 *
 * @see https://platform.openai.com/docs/api-reference/embeddings
 */',
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'audio' => 
      array (
        'name' => 'audio',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Audio',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Learn how to turn audio into text.
 *
 * @see https://platform.openai.com/docs/api-reference/audio
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'edits' => 
      array (
        'name' => 'edits',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Edits',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Given a prompt and an instruction, the model will return an edited version of the prompt.
 *
 * @see https://platform.openai.com/docs/api-reference/edits
 */',
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'files' => 
      array (
        'name' => 'files',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Files',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Files are used to upload documents that can be used with features like Fine-tuning.
 *
 * @see https://platform.openai.com/docs/api-reference/files
 */',
        'startLine' => 93,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'models' => 
      array (
        'name' => 'models',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Models',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * List and describe the various models available in the API.
 *
 * @see https://platform.openai.com/docs/api-reference/models
 */',
        'startLine' => 103,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'fineTuning' => 
      array (
        'name' => 'fineTuning',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\FineTuning',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Manage fine-tuning jobs to tailor a model to your specific training data.
 *
 * @see https://platform.openai.com/docs/api-reference/fine-tuning
 */',
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'fineTunes' => 
      array (
        'name' => 'fineTunes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\FineTunes',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Manage fine-tuning jobs to tailor a model to your specific training data.
 *
 * @see https://platform.openai.com/docs/api-reference/fine-tunes
 * @deprecated OpenAI has deprecated this endpoint and will stop working by January 4, 2024.
 * https://openai.com/blog/gpt-3-5-turbo-fine-tuning-and-api-updates#updated-gpt-3-models
 */',
        'startLine' => 125,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'moderations' => 
      array (
        'name' => 'moderations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Moderations',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Given an input text, outputs if the model classifies it as violating OpenAI\'s content policy.
 *
 * @see https://platform.openai.com/docs/api-reference/moderations
 */',
        'startLine' => 135,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'images' => 
      array (
        'name' => 'images',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Images',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Given a prompt and/or an input image, the model will generate a new image.
 *
 * @see https://platform.openai.com/docs/api-reference/images
 */',
        'startLine' => 145,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'assistants' => 
      array (
        'name' => 'assistants',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Assistants',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build assistants that can call models and use tools to perform tasks.
 *
 * @see https://platform.openai.com/docs/api-reference/assistants
 */',
        'startLine' => 155,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'threads' => 
      array (
        'name' => 'threads',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Contracts\\Resources\\ThreadsContract',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create threads that assistants can interact with.
 *
 * @see https://platform.openai.com/docs/api-reference/threads
 */',
        'startLine' => 165,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'batches' => 
      array (
        'name' => 'batches',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Resources\\Batches',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create large batches of API requests for asynchronous processing. The Batch API returns completions within 24 hours.
 *
 * @see https://platform.openai.com/docs/api-reference/batch
 */',
        'startLine' => 175,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
        'aliasName' => NULL,
      ),
      'vectorStores' => 
      array (
        'name' => 'vectorStores',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'OpenAI\\Contracts\\Resources\\VectorStoresContract',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create and update vector stores that assistants can interact with
 *
 * @see https://platform.openai.com/docs/api-reference/vector-stores
 */',
        'startLine' => 185,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI',
        'declaringClassName' => 'OpenAI\\Client',
        'implementingClassName' => 'OpenAI\\Client',
        'currentClassName' => 'OpenAI\\Client',
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