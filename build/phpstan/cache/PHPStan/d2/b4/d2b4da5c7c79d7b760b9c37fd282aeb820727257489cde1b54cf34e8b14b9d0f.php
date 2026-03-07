<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Validation/Rule.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Validation\Rule
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9158c0b24468d882f46f9270e5590eee0733dcd2214eb59aaa2c4ac89e127c6a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Validation\\Rule',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Validation/Rule.php',
      ),
    ),
    'namespace' => 'Illuminate\\Validation',
    'name' => 'Illuminate\\Validation\\Rule',
    'shortName' => 'Rule',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 246,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'can' => 
      array (
        'name' => 'can',
        'parameters' => 
        array (
          'ability' => 
          array (
            'name' => 'ability',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 32,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 42,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a can constraint builder instance.
 *
 * @param  string  $ability
 * @param  mixed  ...$arguments
 * @return \\Illuminate\\Validation\\Rules\\Can
 */',
        'startLine' => 35,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'when' => 
      array (
        'name' => 'when',
        'parameters' => 
        array (
          'condition' => 
          array (
            'name' => 'condition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 33,
            'endColumn' => 42,
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
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'defaultRules' => 
          array (
            'name' => 'defaultRules',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 48,
                'endLine' => 48,
                'startTokenPos' => 162,
                'startFilePos' => 1722,
                'endTokenPos' => 163,
                'endFilePos' => 1723,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 53,
            'endColumn' => 70,
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
 * Apply the given rules if the given condition is truthy.
 *
 * @param  callable|bool  $condition
 * @param  \\Illuminate\\Contracts\\Validation\\ValidationRule|\\Illuminate\\Contracts\\Validation\\InvokableRule|\\Illuminate\\Contracts\\Validation\\Rule|\\Closure|array|string  $rules
 * @param  \\Illuminate\\Contracts\\Validation\\ValidationRule|\\Illuminate\\Contracts\\Validation\\InvokableRule|\\Illuminate\\Contracts\\Validation\\Rule|\\Closure|array|string  $defaultRules
 * @return \\Illuminate\\Validation\\ConditionalRules
 */',
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'unless' => 
      array (
        'name' => 'unless',
        'parameters' => 
        array (
          'condition' => 
          array (
            'name' => 'condition',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 35,
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
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 47,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'defaultRules' => 
          array (
            'name' => 'defaultRules',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 61,
                'endLine' => 61,
                'startTokenPos' => 206,
                'startFilePos' => 2425,
                'endTokenPos' => 207,
                'endFilePos' => 2426,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 55,
            'endColumn' => 72,
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
 * Apply the given rules if the given condition is falsy.
 *
 * @param  callable|bool  $condition
 * @param  \\Illuminate\\Contracts\\Validation\\ValidationRule|\\Illuminate\\Contracts\\Validation\\InvokableRule|\\Illuminate\\Contracts\\Validation\\Rule|\\Closure|array|string  $rules
 * @param  \\Illuminate\\Contracts\\Validation\\ValidationRule|\\Illuminate\\Contracts\\Validation\\InvokableRule|\\Illuminate\\Contracts\\Validation\\Rule|\\Closure|array|string  $defaultRules
 * @return \\Illuminate\\Validation\\ConditionalRules
 */',
        'startLine' => 61,
        'endLine' => 64,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'array' => 
      array (
        'name' => 'array',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 72,
                'endLine' => 72,
                'startTokenPos' => 244,
                'startFilePos' => 2708,
                'endTokenPos' => 244,
                'endFilePos' => 2711,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
            'startColumn' => 34,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an array rule builder instance.
 *
 * @param  array|null  $keys
 * @return \\Illuminate\\Validation\\Rules\\ArrayRule
 */',
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'forEach' => 
      array (
        'name' => 'forEach',
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
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 36,
            'endColumn' => 44,
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
 * Create a new nested rule set.
 *
 * @param  callable  $callback
 * @return \\Illuminate\\Validation\\NestedRules
 */',
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'unique' => 
      array (
        'name' => 'unique',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'NULL\'',
              'attributes' => 
              array (
                'startLine' => 95,
                'endLine' => 95,
                'startTokenPos' => 308,
                'startFilePos' => 3260,
                'endTokenPos' => 308,
                'endFilePos' => 3265,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a unique constraint builder instance.
 *
 * @param  string  $table
 * @param  string  $column
 * @return \\Illuminate\\Validation\\Rules\\Unique
 */',
        'startLine' => 95,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'exists' => 
      array (
        'name' => 'exists',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'NULL\'',
              'attributes' => 
              array (
                'startLine' => 107,
                'endLine' => 107,
                'startTokenPos' => 345,
                'startFilePos' => 3562,
                'endTokenPos' => 345,
                'endFilePos' => 3567,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an exists constraint builder instance.
 *
 * @param  string  $table
 * @param  string  $column
 * @return \\Illuminate\\Validation\\Rules\\Exists
 */',
        'startLine' => 107,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'in' => 
      array (
        'name' => 'in',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 31,
            'endColumn' => 37,
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
 * Get an in rule builder instance.
 *
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|\\BackedEnum|\\UnitEnum|array|string  $values
 * @return \\Illuminate\\Validation\\Rules\\In
 */',
        'startLine' => 118,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'notIn' => 
      array (
        'name' => 'notIn',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 133,
            'endLine' => 133,
            'startColumn' => 34,
            'endColumn' => 40,
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
 * Get a not_in rule builder instance.
 *
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|\\BackedEnum|\\UnitEnum|array|string  $values
 * @return \\Illuminate\\Validation\\Rules\\NotIn
 */',
        'startLine' => 133,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'requiredIf' => 
      array (
        'name' => 'requiredIf',
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
            'startLine' => 148,
            'endLine' => 148,
            'startColumn' => 39,
            'endColumn' => 47,
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
 * Get a required_if rule builder instance.
 *
 * @param  callable|bool  $callback
 * @return \\Illuminate\\Validation\\Rules\\RequiredIf
 */',
        'startLine' => 148,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'excludeIf' => 
      array (
        'name' => 'excludeIf',
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
            'startLine' => 159,
            'endLine' => 159,
            'startColumn' => 38,
            'endColumn' => 46,
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
 * Get a exclude_if rule builder instance.
 *
 * @param  callable|bool  $callback
 * @return \\Illuminate\\Validation\\Rules\\ExcludeIf
 */',
        'startLine' => 159,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'prohibitedIf' => 
      array (
        'name' => 'prohibitedIf',
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
            'startLine' => 170,
            'endLine' => 170,
            'startColumn' => 41,
            'endColumn' => 49,
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
 * Get a prohibited_if rule builder instance.
 *
 * @param  callable|bool  $callback
 * @return \\Illuminate\\Validation\\Rules\\ProhibitedIf
 */',
        'startLine' => 170,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'date' => 
      array (
        'name' => 'date',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a date rule builder instance.
 *
 * @return \\Illuminate\\Validation\\Rules\\Date
 */',
        'startLine' => 180,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'email' => 
      array (
        'name' => 'email',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an email rule builder instance.
 *
 * @return \\Illuminate\\Validation\\Rules\\Email
 */',
        'startLine' => 190,
        'endLine' => 193,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'enum' => 
      array (
        'name' => 'enum',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 201,
            'endLine' => 201,
            'startColumn' => 33,
            'endColumn' => 37,
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
 * Get an enum rule builder instance.
 *
 * @param  class-string  $type
 * @return \\Illuminate\\Validation\\Rules\\Enum
 */',
        'startLine' => 201,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'file' => 
      array (
        'name' => 'file',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a file rule builder instance.
 *
 * @return \\Illuminate\\Validation\\Rules\\File
 */',
        'startLine' => 211,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'imageFile' => 
      array (
        'name' => 'imageFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an image file rule builder instance.
 *
 * @return \\Illuminate\\Validation\\Rules\\ImageFile
 */',
        'startLine' => 221,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'dimensions' => 
      array (
        'name' => 'dimensions',
        'parameters' => 
        array (
          'constraints' => 
          array (
            'name' => 'constraints',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 232,
                'endLine' => 232,
                'startTokenPos' => 711,
                'startFilePos' => 6528,
                'endTokenPos' => 712,
                'endFilePos' => 6529,
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
            'startLine' => 232,
            'endLine' => 232,
            'startColumn' => 39,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a dimensions rule builder instance.
 *
 * @param  array  $constraints
 * @return \\Illuminate\\Validation\\Rules\\Dimensions
 */',
        'startLine' => 232,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
        'aliasName' => NULL,
      ),
      'numeric' => 
      array (
        'name' => 'numeric',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a numeric rule builder instance.
 *
 * @return \\Illuminate\\Validation\\Rules\\Numeric
 */',
        'startLine' => 242,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Validation',
        'declaringClassName' => 'Illuminate\\Validation\\Rule',
        'implementingClassName' => 'Illuminate\\Validation\\Rule',
        'currentClassName' => 'Illuminate\\Validation\\Rule',
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