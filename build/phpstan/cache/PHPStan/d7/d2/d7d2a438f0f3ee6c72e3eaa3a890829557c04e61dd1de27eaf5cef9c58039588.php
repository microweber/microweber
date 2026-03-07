<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../openai-php/client/src/Contracts/ClientContract.php-PHPStan\BetterReflection\Reflection\ReflectionClass-OpenAI\Contracts\ClientContract
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6cd85560c43aeb1781a7b69d12ebb94954affb995dc3d7e4957b15360ef427d3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'OpenAI\\Contracts\\ClientContract',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../openai-php/client/src/Contracts/ClientContract.php',
      ),
    ),
    'namespace' => 'OpenAI\\Contracts',
    'name' => 'OpenAI\\Contracts\\ClientContract',
    'shortName' => 'ClientContract',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 132,
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
            'name' => 'OpenAI\\Contracts\\Resources\\CompletionsContract',
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
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\ChatContract',
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
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\EmbeddingsContract',
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
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\AudioContract',
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
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\EditsContract',
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
 * @deprecated OpenAI has deprecated this endpoint and will stop working by January 4, 2024.
 * https://openai.com/blog/gpt-4-api-general-availability#deprecation-of-the-edits-api
 */',
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\FilesContract',
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
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\ModelsContract',
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
        'startLine' => 73,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\FineTuningContract',
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
        'startLine' => 80,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\FineTunesContract',
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
        'startLine' => 89,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\ModerationsContract',
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
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 55,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\ImagesContract',
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
        'startLine' => 103,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 45,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\AssistantsContract',
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
        'startLine' => 110,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
        'startLine' => 117,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
            'name' => 'OpenAI\\Contracts\\Resources\\BatchesContract',
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
        'startLine' => 124,
        'endLine' => 124,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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
        'startLine' => 131,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts',
        'declaringClassName' => 'OpenAI\\Contracts\\ClientContract',
        'implementingClassName' => 'OpenAI\\Contracts\\ClientContract',
        'currentClassName' => 'OpenAI\\Contracts\\ClientContract',
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