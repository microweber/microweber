<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Validation/Validator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Validation\Validator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7f8f550cf09517775ef67da2175363118a91c3f879b710f6f9ce63bb602c5ad0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Validation\\Validator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Validation/Validator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Validation',
    'name' => 'Illuminate\\Contracts\\Validation\\Validator',
    'shortName' => 'Validator',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 65,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\MessageProvider',
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
      'validate' => 
      array (
        'name' => 'validate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the validator\'s rules against its data.
 *
 * @return array
 *
 * @throws \\Illuminate\\Validation\\ValidationException
 */',
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'validated' => 
      array (
        'name' => 'validated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the attributes and values that were validated.
 *
 * @return array
 *
 * @throws \\Illuminate\\Validation\\ValidationException
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'fails' => 
      array (
        'name' => 'fails',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the data fails the validation rules.
 *
 * @return bool
 */',
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'failed' => 
      array (
        'name' => 'failed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the failed validation rules.
 *
 * @return array
 */',
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'sometimes' => 
      array (
        'name' => 'sometimes',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
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
            'startColumn' => 31,
            'endColumn' => 40,
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
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
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
            'startColumn' => 51,
            'endColumn' => 68,
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
 * Add conditions to a given field based on a Closure.
 *
 * @param  string|array  $attribute
 * @param  string|array  $rules
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 70,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'after' => 
      array (
        'name' => 'after',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 27,
            'endColumn' => 35,
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
 * Add an after validation callback.
 *
 * @param  callable|string  $callback
 * @return $this
 */',
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'aliasName' => NULL,
      ),
      'errors' => 
      array (
        'name' => 'errors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the validation error messages.
 *
 * @return \\Illuminate\\Support\\MessageBag
 */',
        'startLine' => 64,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Validation',
        'declaringClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'implementingClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
        'currentClassName' => 'Illuminate\\Contracts\\Validation\\Validator',
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