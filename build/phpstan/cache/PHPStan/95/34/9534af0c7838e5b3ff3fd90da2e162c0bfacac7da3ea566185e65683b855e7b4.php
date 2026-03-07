<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Schema/ForeignKeyDefinition.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Schema\ForeignKeyDefinition
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a80bf9b79e164e87e364c504ed2e24de74a491acc961ef6eeb1704997840698a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Schema/ForeignKeyDefinition.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Schema',
    'name' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
    'shortName' => 'ForeignKeyDefinition',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method ForeignKeyDefinition deferrable(bool $value = true) Set the foreign key as deferrable (PostgreSQL)
 * @method ForeignKeyDefinition initiallyImmediate(bool $value = true) Set the default time to check the constraint (PostgreSQL)
 * @method ForeignKeyDefinition on(string $table) Specify the referenced table
 * @method ForeignKeyDefinition onDelete(string $action) Add an ON DELETE action
 * @method ForeignKeyDefinition onUpdate(string $action) Add an ON UPDATE action
 * @method ForeignKeyDefinition references(string|array $columns) Specify the referenced column(s)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 96,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Fluent',
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
      'cascadeOnUpdate' => 
      array (
        'name' => 'cascadeOnUpdate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that updates should cascade.
 *
 * @return $this
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
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'restrictOnUpdate' => 
      array (
        'name' => 'restrictOnUpdate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that updates should be restricted.
 *
 * @return $this
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
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'nullOnUpdate' => 
      array (
        'name' => 'nullOnUpdate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that updates should set the foreign key value to null.
 *
 * @return $this
 */',
        'startLine' => 42,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'noActionOnUpdate' => 
      array (
        'name' => 'noActionOnUpdate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that updates should have "no action".
 *
 * @return $this
 */',
        'startLine' => 52,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'cascadeOnDelete' => 
      array (
        'name' => 'cascadeOnDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that deletes should cascade.
 *
 * @return $this
 */',
        'startLine' => 62,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'restrictOnDelete' => 
      array (
        'name' => 'restrictOnDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that deletes should be restricted.
 *
 * @return $this
 */',
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'nullOnDelete' => 
      array (
        'name' => 'nullOnDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that deletes should set the foreign key value to null.
 *
 * @return $this
 */',
        'startLine' => 82,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'aliasName' => NULL,
      ),
      'noActionOnDelete' => 
      array (
        'name' => 'noActionOnDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that deletes should have "no action".
 *
 * @return $this
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Schema',
        'declaringClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'implementingClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
        'currentClassName' => 'Illuminate\\Database\\Schema\\ForeignKeyDefinition',
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