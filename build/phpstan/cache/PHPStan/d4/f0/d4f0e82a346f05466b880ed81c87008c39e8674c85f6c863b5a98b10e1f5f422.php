<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../openai-php/client/src/Contracts/Resources/ChatContract.php-PHPStan\BetterReflection\Reflection\ReflectionClass-OpenAI\Contracts\Resources\ChatContract
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5a5d68fb07ca429c4c58d435b5da32b13b201961614c581324b577bd19209297-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'OpenAI\\Contracts\\Resources\\ChatContract',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../openai-php/client/src/Contracts/Resources/ChatContract.php',
      ),
    ),
    'namespace' => 'OpenAI\\Contracts\\Resources',
    'name' => 'OpenAI\\Contracts\\Resources\\ChatContract',
    'shortName' => 'ChatContract',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 29,
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
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 28,
            'endColumn' => 44,
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
            'name' => 'OpenAI\\Responses\\Chat\\CreateResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a completion for the chat message
 *
 * @see https://platform.openai.com/docs/api-reference/chat/create
 *
 * @param  array<string, mixed>  $parameters
 */',
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts\\Resources',
        'declaringClassName' => 'OpenAI\\Contracts\\Resources\\ChatContract',
        'implementingClassName' => 'OpenAI\\Contracts\\Resources\\ChatContract',
        'currentClassName' => 'OpenAI\\Contracts\\Resources\\ChatContract',
        'aliasName' => NULL,
      ),
      'createStreamed' => 
      array (
        'name' => 'createStreamed',
        'parameters' => 
        array (
          'parameters' => 
          array (
            'name' => 'parameters',
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
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 36,
            'endColumn' => 52,
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
            'name' => 'OpenAI\\Responses\\StreamResponse',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a streamed completion for the chat message
 *
 * @see https://platform.openai.com/docs/api-reference/chat/create
 *
 * @param  array<string, mixed>  $parameters
 * @return StreamResponse<CreateStreamedResponse>
 */',
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 70,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'OpenAI\\Contracts\\Resources',
        'declaringClassName' => 'OpenAI\\Contracts\\Resources\\ChatContract',
        'implementingClassName' => 'OpenAI\\Contracts\\Resources\\ChatContract',
        'currentClassName' => 'OpenAI\\Contracts\\Resources\\ChatContract',
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