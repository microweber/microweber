<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Validation/Rules/Unique.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Validation\Rules\Unique
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0949c76b42739442858b50b372c26845f237c7f021eb88badd96daeb71692825-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Validation\\Rules\\Unique',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Validation/Rules/Unique.php',
      ),
    ),
    'namespace' => 'Illuminate\\Validation\\Rules',
    'name' => 'Illuminate\\Validation\\Rules\\Unique',
    'shortName' => 'Unique',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 76,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Stringable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Conditionable',
      1 => 'Illuminate\\Validation\\Rules\\DatabaseRule',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'ignore' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'name' => 'ignore',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The ID that should be ignored.
 *
 * @var mixed
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'idColumn' => 
      array (
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'name' => 'idColumn',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'id\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 55,
            'startFilePos' => 429,
            'endTokenPos' => 55,
            'endFilePos' => 432,
          ),
        ),
        'docComment' => '/**
 * The name of the ID column.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 31,
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
      'ignore' => 
      array (
        'name' => 'ignore',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 28,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'idColumn' => 
          array (
            'name' => 'idColumn',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 34,
                'endLine' => 34,
                'startTokenPos' => 73,
                'startFilePos' => 640,
                'endTokenPos' => 73,
                'endFilePos' => 643,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Ignore the given ID during the unique check.
 *
 * @param  mixed  $id
 * @param  string|null  $idColumn
 * @return $this
 */',
        'startLine' => 34,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'aliasName' => NULL,
      ),
      'ignoreModel' => 
      array (
        'name' => 'ignoreModel',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'idColumn' => 
          array (
            'name' => 'idColumn',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 53,
                'endLine' => 53,
                'startTokenPos' => 149,
                'startFilePos' => 1106,
                'endTokenPos' => 149,
                'endFilePos' => 1109,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
            'startColumn' => 41,
            'endColumn' => 56,
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
 * Ignore the given model during the unique check.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model  $model
 * @param  string|null  $idColumn
 * @return $this
 */',
        'startLine' => 53,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert the rule to a validation string.
 *
 * @return string
 */',
        'startLine' => 66,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Validation\\Rules',
        'declaringClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'implementingClassName' => 'Illuminate\\Validation\\Rules\\Unique',
        'currentClassName' => 'Illuminate\\Validation\\Rules\\Unique',
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