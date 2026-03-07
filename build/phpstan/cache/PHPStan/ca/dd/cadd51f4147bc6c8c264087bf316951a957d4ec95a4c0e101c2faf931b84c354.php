<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Concerns/ConfiguresPrompts.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Console\Concerns\ConfiguresPrompts
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5591c1fb7037e89709bead3f7529d9ec651e3d99e1b39da04ea587504e620af3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Console/Concerns/ConfiguresPrompts.php',
      ),
    ),
    'namespace' => 'Illuminate\\Console\\Concerns',
    'name' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
    'shortName' => 'ConfiguresPrompts',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 20,
    'endLine' => 290,
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
      'configurePrompts' => 
      array (
        'name' => 'configurePrompts',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Console\\Input\\InputInterface',
                'isIdentifier' => false,
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
            'startColumn' => 41,
            'endColumn' => 61,
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
 * Configure the prompt fallbacks.
 *
 * @param  \\Symfony\\Component\\Console\\Input\\InputInterface  $input
 * @return void
 */',
        'startLine' => 28,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'promptUntilValid' => 
      array (
        'name' => 'promptUntilValid',
        'parameters' => 
        array (
          'prompt' => 
          array (
            'name' => 'prompt',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'required' => 
          array (
            'name' => 'required',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 50,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'validate' => 
          array (
            'name' => 'validate',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 61,
            'endColumn' => 69,
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
 * Prompt the user until the given validation callback passes.
 *
 * @param  \\Closure  $prompt
 * @param  bool|string  $required
 * @param  \\Closure|null  $validate
 * @return mixed
 */',
        'startLine' => 123,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'validatePrompt' => 
      array (
        'name' => 'validatePrompt',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 161,
            'endLine' => 161,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 161,
            'endLine' => 161,
            'startColumn' => 47,
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
        'docComment' => '/**
 * Validate the given prompt value using the validator.
 *
 * @param  mixed  $value
 * @param  mixed  $rules
 * @return ?string
 */',
        'startLine' => 161,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'getPromptValidatorInstance' => 
      array (
        'name' => 'getPromptValidatorInstance',
        'parameters' => 
        array (
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 51,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 59,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 67,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'messages' => 
          array (
            'name' => 'messages',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 194,
                'endLine' => 194,
                'startTokenPos' => 1341,
                'startFilePos' => 6616,
                'endTokenPos' => 1342,
                'endFilePos' => 6617,
              ),
            ),
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
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 75,
            'endColumn' => 94,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 194,
                'endLine' => 194,
                'startTokenPos' => 1351,
                'startFilePos' => 6640,
                'endTokenPos' => 1352,
                'endFilePos' => 6641,
              ),
            ),
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
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 97,
            'endColumn' => 118,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validator instance that should be used to validate prompts.
 *
 * @param  mixed  $field
 * @param  mixed  $value
 * @param  mixed  $rules
 * @param  array  $messages
 * @param  array  $attributes
 * @return \\Illuminate\\Validation\\Validator
 */',
        'startLine' => 194,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'validationMessages' => 
      array (
        'name' => 'validationMessages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation messages that should be used during prompt validation.
 *
 * @return array
 */',
        'startLine' => 209,
        'endLine' => 212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'validationAttributes' => 
      array (
        'name' => 'validationAttributes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the validation attributes that should be used during prompt validation.
 *
 * @return array
 */',
        'startLine' => 219,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'restorePrompts' => 
      array (
        'name' => 'restorePrompts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Restore the prompts output.
 *
 * @return void
 */',
        'startLine' => 229,
        'endLine' => 232,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'selectFallback' => 
      array (
        'name' => 'selectFallback',
        'parameters' => 
        array (
          'label' => 
          array (
            'name' => 'label',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 242,
            'endLine' => 242,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 242,
            'endLine' => 242,
            'startColumn' => 45,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 242,
                'endLine' => 242,
                'startTokenPos' => 1510,
                'startFilePos' => 7751,
                'endTokenPos' => 1510,
                'endFilePos' => 7754,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 242,
            'endLine' => 242,
            'startColumn' => 55,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Select fallback.
 *
 * @param  string  $label
 * @param  array  $options
 * @param  string|int|null  $default
 * @return string|int
 */',
        'startLine' => 242,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'aliasName' => NULL,
      ),
      'multiselectFallback' => 
      array (
        'name' => 'multiselectFallback',
        'parameters' => 
        array (
          'label' => 
          array (
            'name' => 'label',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 50,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 262,
                'endLine' => 262,
                'startTokenPos' => 1594,
                'startFilePos' => 8266,
                'endTokenPos' => 1595,
                'endFilePos' => 8267,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 60,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'required' => 
          array (
            'name' => 'required',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 262,
                'endLine' => 262,
                'startTokenPos' => 1602,
                'startFilePos' => 8282,
                'endTokenPos' => 1602,
                'endFilePos' => 8286,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 262,
            'endLine' => 262,
            'startColumn' => 75,
            'endColumn' => 91,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Multi-select fallback.
 *
 * @param  string  $label
 * @param  array  $options
 * @param  array  $default
 * @param  bool|string  $required
 * @return array
 */',
        'startLine' => 262,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Console\\Concerns',
        'declaringClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'implementingClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
        'currentClassName' => 'Illuminate\\Console\\Concerns\\ConfiguresPrompts',
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