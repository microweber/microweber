<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasRelationships.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Eloquent\Concerns\HasRelationships
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2b568b99e8e4e3140e1ad02f01593fa31c8937105002cc417d7ed3a01e57ecaa-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasRelationships.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
    'name' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
    'shortName' => 'HasRelationships',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 997,
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
      'relations' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'name' => 'relations',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 125,
            'startFilePos' => 1184,
            'endTokenPos' => 126,
            'endFilePos' => 1185,
          ),
        ),
        'docComment' => '/**
 * The loaded relationships for the model.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'touches' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'name' => 'touches',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 137,
            'startFilePos' => 1312,
            'endTokenPos' => 138,
            'endFilePos' => 1313,
          ),
        ),
        'docComment' => '/**
 * The relationships that should be touched on save.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'manyMethods' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'name' => 'manyMethods',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'belongsToMany\', \'morphToMany\', \'morphedByMany\']',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 49,
            'startTokenPos' => 151,
            'startFilePos' => 1440,
            'endTokenPos' => 162,
            'endFilePos' => 1503,
          ),
        ),
        'docComment' => '/**
 * The many to many relationship methods.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'relationResolvers' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'name' => 'relationResolvers',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 175,
            'startFilePos' => 1630,
            'endTokenPos' => 176,
            'endFilePos' => 1631,
          ),
        ),
        'docComment' => '/**
 * The relation resolver callbacks.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 45,
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
      'relationResolver' => 
      array (
        'name' => 'relationResolver',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 38,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 46,
            'endColumn' => 49,
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
 * Get the dynamic relation resolver if defined or inherited, or return null.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $class
 * @param  string  $key
 * @return Closure|null
 */',
        'startLine' => 67,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'resolveRelationUsing' => 
      array (
        'name' => 'resolveRelationUsing',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 49,
            'endColumn' => 53,
            'parameterIndex' => 0,
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
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 56,
            'endColumn' => 72,
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
 * Define a dynamic relation resolver.
 *
 * @param  string  $name
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 87,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'hasOne' => 
      array (
        'name' => 'hasOne',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
            'startColumn' => 28,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 105,
                'endLine' => 105,
                'startTokenPos' => 333,
                'startFilePos' => 3055,
                'endTokenPos' => 333,
                'endFilePos' => 3058,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
            'startColumn' => 38,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 105,
                'endLine' => 105,
                'startTokenPos' => 340,
                'startFilePos' => 3073,
                'endTokenPos' => 340,
                'endFilePos' => 3076,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 105,
            'endLine' => 105,
            'startColumn' => 58,
            'endColumn' => 73,
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
 * Define a one-to-one relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string|null  $foreignKey
 * @param  string|null  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasOne<TRelatedModel, $this>
 */',
        'startLine' => 105,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newHasOne' => 
      array (
        'name' => 'newHasOne',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 50,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 65,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 78,
            'endColumn' => 86,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new HasOne relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string  $foreignKey
 * @param  string  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasOne<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'hasOneThrough' => 
      array (
        'name' => 'hasOneThrough',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'through' => 
          array (
            'name' => 'through',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 45,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'firstKey' => 
          array (
            'name' => 'firstKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 147,
                'endLine' => 147,
                'startTokenPos' => 484,
                'startFilePos' => 4713,
                'endTokenPos' => 484,
                'endFilePos' => 4716,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 55,
            'endColumn' => 70,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'secondKey' => 
          array (
            'name' => 'secondKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 147,
                'endLine' => 147,
                'startTokenPos' => 491,
                'startFilePos' => 4732,
                'endTokenPos' => 491,
                'endFilePos' => 4735,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 73,
            'endColumn' => 89,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 147,
                'endLine' => 147,
                'startTokenPos' => 498,
                'startFilePos' => 4750,
                'endTokenPos' => 498,
                'endFilePos' => 4753,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 92,
            'endColumn' => 107,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'secondLocalKey' => 
          array (
            'name' => 'secondLocalKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 147,
                'endLine' => 147,
                'startTokenPos' => 505,
                'startFilePos' => 4774,
                'endTokenPos' => 505,
                'endFilePos' => 4777,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 110,
            'endColumn' => 131,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a has-one-through relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TIntermediateModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  class-string<TIntermediateModel>  $through
 * @param  string|null  $firstKey
 * @param  string|null  $secondKey
 * @param  string|null  $localKey
 * @param  string|null  $secondLocalKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasOneThrough<TRelatedModel, TIntermediateModel, $this>
 */',
        'startLine' => 147,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newHasOneThrough' => 
      array (
        'name' => 'newHasOneThrough',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'farParent' => 
          array (
            'name' => 'farParent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 57,
            'endColumn' => 72,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'throughParent' => 
          array (
            'name' => 'throughParent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 75,
            'endColumn' => 94,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'firstKey' => 
          array (
            'name' => 'firstKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 97,
            'endColumn' => 105,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'secondKey' => 
          array (
            'name' => 'secondKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 108,
            'endColumn' => 117,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 120,
            'endColumn' => 128,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
          'secondLocalKey' => 
          array (
            'name' => 'secondLocalKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 178,
            'endLine' => 178,
            'startColumn' => 131,
            'endColumn' => 145,
            'parameterIndex' => 6,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new HasOneThrough relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TIntermediateModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $farParent
 * @param  TIntermediateModel  $throughParent
 * @param  string  $firstKey
 * @param  string  $secondKey
 * @param  string  $localKey
 * @param  string  $secondLocalKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasOneThrough<TRelatedModel, TIntermediateModel, TDeclaringModel>
 */',
        'startLine' => 178,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphOne' => 
      array (
        'name' => 'morphOne',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 30,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 40,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 195,
                'endLine' => 195,
                'startTokenPos' => 698,
                'startFilePos' => 6719,
                'endTokenPos' => 698,
                'endFilePos' => 6722,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 195,
                'endLine' => 195,
                'startTokenPos' => 705,
                'startFilePos' => 6731,
                'endTokenPos' => 705,
                'endFilePos' => 6734,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 61,
            'endColumn' => 70,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 195,
                'endLine' => 195,
                'startTokenPos' => 712,
                'startFilePos' => 6749,
                'endTokenPos' => 712,
                'endFilePos' => 6752,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 73,
            'endColumn' => 88,
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
 * Define a polymorphic one-to-one relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string  $name
 * @param  string|null  $type
 * @param  string|null  $id
 * @param  string|null  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphOne<TRelatedModel, $this>
 */',
        'startLine' => 195,
        'endLine' => 204,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newMorphOne' => 
      array (
        'name' => 'newMorphOne',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 36,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 67,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 74,
            'endColumn' => 76,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 79,
            'endColumn' => 87,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new MorphOne relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string  $type
 * @param  string  $id
 * @param  string  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphOne<TRelatedModel, TDeclaringModel>
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
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'belongsTo' => 
      array (
        'name' => 'belongsTo',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 235,
            'endLine' => 235,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 235,
                'endLine' => 235,
                'startTokenPos' => 874,
                'startFilePos' => 8242,
                'endTokenPos' => 874,
                'endFilePos' => 8245,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 235,
            'endLine' => 235,
            'startColumn' => 41,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'ownerKey' => 
          array (
            'name' => 'ownerKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 235,
                'endLine' => 235,
                'startTokenPos' => 881,
                'startFilePos' => 8260,
                'endTokenPos' => 881,
                'endFilePos' => 8263,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 235,
            'endLine' => 235,
            'startColumn' => 61,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 235,
                'endLine' => 235,
                'startTokenPos' => 888,
                'startFilePos' => 8278,
                'endTokenPos' => 888,
                'endFilePos' => 8281,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 235,
            'endLine' => 235,
            'startColumn' => 79,
            'endColumn' => 94,
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
 * Define an inverse one-to-one or many relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string|null  $foreignKey
 * @param  string|null  $ownerKey
 * @param  string|null  $relation
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<TRelatedModel, $this>
 */',
        'startLine' => 235,
        'endLine' => 261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newBelongsTo' => 
      array (
        'name' => 'newBelongsTo',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'child' => 
          array (
            'name' => 'child',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 53,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 67,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'ownerKey' => 
          array (
            'name' => 'ownerKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 80,
            'endColumn' => 88,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 91,
            'endColumn' => 99,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new BelongsTo relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $child
 * @param  string  $foreignKey
 * @param  string  $ownerKey
 * @param  string  $relation
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 276,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphTo' => 
      array (
        'name' => 'morphTo',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 290,
                'endLine' => 290,
                'startTokenPos' => 1091,
                'startFilePos' => 10611,
                'endTokenPos' => 1091,
                'endFilePos' => 10614,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 290,
            'endLine' => 290,
            'startColumn' => 29,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 290,
                'endLine' => 290,
                'startTokenPos' => 1098,
                'startFilePos' => 10625,
                'endTokenPos' => 1098,
                'endFilePos' => 10628,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 290,
            'endLine' => 290,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 290,
                'endLine' => 290,
                'startTokenPos' => 1105,
                'startFilePos' => 10637,
                'endTokenPos' => 1105,
                'endFilePos' => 10640,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 290,
            'endLine' => 290,
            'startColumn' => 57,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'ownerKey' => 
          array (
            'name' => 'ownerKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 290,
                'endLine' => 290,
                'startTokenPos' => 1112,
                'startFilePos' => 10655,
                'endTokenPos' => 1112,
                'endFilePos' => 10658,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 290,
            'endLine' => 290,
            'startColumn' => 69,
            'endColumn' => 84,
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
 * Define a polymorphic, inverse one-to-one or many relationship.
 *
 * @param  string|null  $name
 * @param  string|null  $type
 * @param  string|null  $id
 * @param  string|null  $ownerKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<\\Illuminate\\Database\\Eloquent\\Model, $this>
 */',
        'startLine' => 290,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphEagerTo' => 
      array (
        'name' => 'morphEagerTo',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 44,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 51,
            'endColumn' => 53,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'ownerKey' => 
          array (
            'name' => 'ownerKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 56,
            'endColumn' => 64,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a polymorphic, inverse one-to-one or many relationship.
 *
 * @param  string  $name
 * @param  string  $type
 * @param  string  $id
 * @param  string|null  $ownerKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<\\Illuminate\\Database\\Eloquent\\Model, $this>
 */',
        'startLine' => 318,
        'endLine' => 323,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphInstanceTo' => 
      array (
        'name' => 'morphInstanceTo',
        'parameters' => 
        array (
          'target' => 
          array (
            'name' => 'target',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 49,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 56,
            'endColumn' => 60,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 63,
            'endColumn' => 65,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'ownerKey' => 
          array (
            'name' => 'ownerKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 68,
            'endColumn' => 76,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a polymorphic, inverse one-to-one or many relationship.
 *
 * @param  string  $target
 * @param  string  $name
 * @param  string  $type
 * @param  string  $id
 * @param  string|null  $ownerKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<\\Illuminate\\Database\\Eloquent\\Model, $this>
 */',
        'startLine' => 335,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newMorphTo' => 
      array (
        'name' => 'newMorphTo',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 66,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'ownerKey' => 
          array (
            'name' => 'ownerKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 79,
            'endColumn' => 87,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
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
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 90,
            'endColumn' => 94,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 97,
            'endColumn' => 105,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new MorphTo relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string  $foreignKey
 * @param  string|null  $ownerKey
 * @param  string  $type
 * @param  string  $relation
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 360,
        'endLine' => 363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'getActualClassNameForMorph' => 
      array (
        'name' => 'getActualClassNameForMorph',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 55,
            'endColumn' => 60,
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
 * Retrieve the actual class name for a given morph class.
 *
 * @param  string  $class
 * @return string
 */',
        'startLine' => 371,
        'endLine' => 374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'guessBelongsToRelation' => 
      array (
        'name' => 'guessBelongsToRelation',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Guess the "belongs to" relationship name.
 *
 * @return string
 */',
        'startLine' => 381,
        'endLine' => 386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'through' => 
      array (
        'name' => 'through',
        'parameters' => 
        array (
          'relationship' => 
          array (
            'name' => 'relationship',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 404,
            'endLine' => 404,
            'startColumn' => 29,
            'endColumn' => 41,
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
 * Create a pending has-many-through or has-one-through relationship.
 *
 * @template TIntermediateModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  string|\\Illuminate\\Database\\Eloquent\\Relations\\HasMany<TIntermediateModel, covariant $this>|\\Illuminate\\Database\\Eloquent\\Relations\\HasOne<TIntermediateModel, covariant $this>  $relationship
 * @return (
 *     $relationship is string
 *     ? \\Illuminate\\Database\\Eloquent\\PendingHasThroughRelationship<\\Illuminate\\Database\\Eloquent\\Model, $this>
 *     : (
 *          $relationship is \\Illuminate\\Database\\Eloquent\\Relations\\HasMany<TIntermediateModel, $this>
 *          ? \\Illuminate\\Database\\Eloquent\\PendingHasThroughRelationship<TIntermediateModel, $this, \\Illuminate\\Database\\Eloquent\\Relations\\HasMany<TIntermediateModel, $this>>
 *          : \\Illuminate\\Database\\Eloquent\\PendingHasThroughRelationship<TIntermediateModel, $this, \\Illuminate\\Database\\Eloquent\\Relations\\HasOne<TIntermediateModel, $this>>
 *     )
 * )
 */',
        'startLine' => 404,
        'endLine' => 411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'hasMany' => 
      array (
        'name' => 'hasMany',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 423,
            'endLine' => 423,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 423,
                'endLine' => 423,
                'startTokenPos' => 1601,
                'startFilePos' => 15839,
                'endTokenPos' => 1601,
                'endFilePos' => 15842,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 423,
            'endLine' => 423,
            'startColumn' => 39,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 423,
                'endLine' => 423,
                'startTokenPos' => 1608,
                'startFilePos' => 15857,
                'endTokenPos' => 1608,
                'endFilePos' => 15860,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 423,
            'endLine' => 423,
            'startColumn' => 59,
            'endColumn' => 74,
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
 * Define a one-to-many relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string|null  $foreignKey
 * @param  string|null  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasMany<TRelatedModel, $this>
 */',
        'startLine' => 423,
        'endLine' => 434,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newHasMany' => 
      array (
        'name' => 'newHasMany',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 448,
            'endLine' => 448,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 448,
            'endLine' => 448,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'foreignKey' => 
          array (
            'name' => 'foreignKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 448,
            'endLine' => 448,
            'startColumn' => 66,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 448,
            'endLine' => 448,
            'startColumn' => 79,
            'endColumn' => 87,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new HasMany relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string  $foreignKey
 * @param  string  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasMany<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 448,
        'endLine' => 451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'hasManyThrough' => 
      array (
        'name' => 'hasManyThrough',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'through' => 
          array (
            'name' => 'through',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 46,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'firstKey' => 
          array (
            'name' => 'firstKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 467,
                'endLine' => 467,
                'startTokenPos' => 1754,
                'startFilePos' => 17527,
                'endTokenPos' => 1754,
                'endFilePos' => 17530,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 56,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'secondKey' => 
          array (
            'name' => 'secondKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 467,
                'endLine' => 467,
                'startTokenPos' => 1761,
                'startFilePos' => 17546,
                'endTokenPos' => 1761,
                'endFilePos' => 17549,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 74,
            'endColumn' => 90,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 467,
                'endLine' => 467,
                'startTokenPos' => 1768,
                'startFilePos' => 17564,
                'endTokenPos' => 1768,
                'endFilePos' => 17567,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 93,
            'endColumn' => 108,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'secondLocalKey' => 
          array (
            'name' => 'secondLocalKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 467,
                'endLine' => 467,
                'startTokenPos' => 1775,
                'startFilePos' => 17588,
                'endTokenPos' => 1775,
                'endFilePos' => 17591,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 111,
            'endColumn' => 132,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a has-many-through relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TIntermediateModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  class-string<TIntermediateModel>  $through
 * @param  string|null  $firstKey
 * @param  string|null  $secondKey
 * @param  string|null  $localKey
 * @param  string|null  $secondLocalKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasManyThrough<TRelatedModel, TIntermediateModel, $this>
 */',
        'startLine' => 467,
        'endLine' => 484,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newHasManyThrough' => 
      array (
        'name' => 'newHasManyThrough',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 42,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'farParent' => 
          array (
            'name' => 'farParent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 58,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'throughParent' => 
          array (
            'name' => 'throughParent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 76,
            'endColumn' => 95,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'firstKey' => 
          array (
            'name' => 'firstKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 98,
            'endColumn' => 106,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'secondKey' => 
          array (
            'name' => 'secondKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 109,
            'endColumn' => 118,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 121,
            'endColumn' => 129,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
          'secondLocalKey' => 
          array (
            'name' => 'secondLocalKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 132,
            'endColumn' => 146,
            'parameterIndex' => 6,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new HasManyThrough relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TIntermediateModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $farParent
 * @param  TIntermediateModel  $throughParent
 * @param  string  $firstKey
 * @param  string  $secondKey
 * @param  string  $localKey
 * @param  string  $secondLocalKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasManyThrough<TRelatedModel, TIntermediateModel, TDeclaringModel>
 */',
        'startLine' => 502,
        'endLine' => 505,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphMany' => 
      array (
        'name' => 'morphMany',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 519,
                'endLine' => 519,
                'startTokenPos' => 1968,
                'startFilePos' => 19589,
                'endTokenPos' => 1968,
                'endFilePos' => 19592,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
            'startColumn' => 48,
            'endColumn' => 59,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 519,
                'endLine' => 519,
                'startTokenPos' => 1975,
                'startFilePos' => 19601,
                'endTokenPos' => 1975,
                'endFilePos' => 19604,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
            'startColumn' => 62,
            'endColumn' => 71,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 519,
                'endLine' => 519,
                'startTokenPos' => 1982,
                'startFilePos' => 19619,
                'endTokenPos' => 1982,
                'endFilePos' => 19622,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
            'startColumn' => 74,
            'endColumn' => 89,
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
 * Define a polymorphic one-to-many relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string  $name
 * @param  string|null  $type
 * @param  string|null  $id
 * @param  string|null  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphMany<TRelatedModel, $this>
 */',
        'startLine' => 519,
        'endLine' => 531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newMorphMany' => 
      array (
        'name' => 'newMorphMany',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 53,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 68,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 75,
            'endColumn' => 77,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'localKey' => 
          array (
            'name' => 'localKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 80,
            'endColumn' => 88,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new MorphMany relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string  $type
 * @param  string  $id
 * @param  string  $localKey
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphMany<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 546,
        'endLine' => 549,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'belongsToMany' => 
      array (
        'name' => 'belongsToMany',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 565,
                'endLine' => 565,
                'startTokenPos' => 2150,
                'startFilePos' => 21537,
                'endTokenPos' => 2150,
                'endFilePos' => 21540,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'foreignPivotKey' => 
          array (
            'name' => 'foreignPivotKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 565,
                'endLine' => 565,
                'startTokenPos' => 2157,
                'startFilePos' => 21562,
                'endTokenPos' => 2157,
                'endFilePos' => 21565,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
            'startColumn' => 60,
            'endColumn' => 82,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'relatedPivotKey' => 
          array (
            'name' => 'relatedPivotKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 565,
                'endLine' => 565,
                'startTokenPos' => 2164,
                'startFilePos' => 21587,
                'endTokenPos' => 2164,
                'endFilePos' => 21590,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 565,
            'endLine' => 565,
            'startColumn' => 85,
            'endColumn' => 107,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'parentKey' => 
          array (
            'name' => 'parentKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 566,
                'endLine' => 566,
                'startTokenPos' => 2171,
                'startFilePos' => 21640,
                'endTokenPos' => 2171,
                'endFilePos' => 21643,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 566,
            'endLine' => 566,
            'startColumn' => 35,
            'endColumn' => 51,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'relatedKey' => 
          array (
            'name' => 'relatedKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 566,
                'endLine' => 566,
                'startTokenPos' => 2178,
                'startFilePos' => 21660,
                'endTokenPos' => 2178,
                'endFilePos' => 21663,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 566,
            'endLine' => 566,
            'startColumn' => 54,
            'endColumn' => 71,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 566,
                'endLine' => 566,
                'startTokenPos' => 2185,
                'startFilePos' => 21678,
                'endTokenPos' => 2185,
                'endFilePos' => 21681,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 566,
            'endLine' => 566,
            'startColumn' => 74,
            'endColumn' => 89,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a many-to-many relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string|class-string<\\Illuminate\\Database\\Eloquent\\Model>|null  $table
 * @param  string|null  $foreignPivotKey
 * @param  string|null  $relatedPivotKey
 * @param  string|null  $parentKey
 * @param  string|null  $relatedKey
 * @param  string|null  $relation
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany<TRelatedModel, $this>
 */',
        'startLine' => 565,
        'endLine' => 596,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newBelongsToMany' => 
      array (
        'name' => 'newBelongsToMany',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 57,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 72,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'foreignPivotKey' => 
          array (
            'name' => 'foreignPivotKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 80,
            'endColumn' => 95,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'relatedPivotKey' => 
          array (
            'name' => 'relatedPivotKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 98,
            'endColumn' => 113,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'parentKey' => 
          array (
            'name' => 'parentKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 615,
            'endLine' => 615,
            'startColumn' => 41,
            'endColumn' => 50,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
          'relatedKey' => 
          array (
            'name' => 'relatedKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 615,
            'endLine' => 615,
            'startColumn' => 53,
            'endColumn' => 63,
            'parameterIndex' => 6,
            'isOptional' => false,
          ),
          'relationName' => 
          array (
            'name' => 'relationName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 615,
                'endLine' => 615,
                'startTokenPos' => 2398,
                'startFilePos' => 23983,
                'endTokenPos' => 2398,
                'endFilePos' => 23986,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 615,
            'endLine' => 615,
            'startColumn' => 66,
            'endColumn' => 85,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new BelongsToMany relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string|class-string<\\Illuminate\\Database\\Eloquent\\Model>  $table
 * @param  string  $foreignPivotKey
 * @param  string  $relatedPivotKey
 * @param  string  $parentKey
 * @param  string  $relatedKey
 * @param  string|null  $relationName
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 614,
        'endLine' => 618,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphToMany' => 
      array (
        'name' => 'morphToMany',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 33,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 636,
                'endLine' => 636,
                'startTokenPos' => 2454,
                'startFilePos' => 24793,
                'endTokenPos' => 2454,
                'endFilePos' => 24796,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 50,
            'endColumn' => 62,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'foreignPivotKey' => 
          array (
            'name' => 'foreignPivotKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 636,
                'endLine' => 636,
                'startTokenPos' => 2461,
                'startFilePos' => 24818,
                'endTokenPos' => 2461,
                'endFilePos' => 24821,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 65,
            'endColumn' => 87,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'relatedPivotKey' => 
          array (
            'name' => 'relatedPivotKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 637,
                'endLine' => 637,
                'startTokenPos' => 2468,
                'startFilePos' => 24875,
                'endTokenPos' => 2468,
                'endFilePos' => 24878,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 637,
            'endLine' => 637,
            'startColumn' => 33,
            'endColumn' => 55,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'parentKey' => 
          array (
            'name' => 'parentKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 637,
                'endLine' => 637,
                'startTokenPos' => 2475,
                'startFilePos' => 24894,
                'endTokenPos' => 2475,
                'endFilePos' => 24897,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 637,
            'endLine' => 637,
            'startColumn' => 58,
            'endColumn' => 74,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'relatedKey' => 
          array (
            'name' => 'relatedKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 638,
                'endLine' => 638,
                'startTokenPos' => 2482,
                'startFilePos' => 24946,
                'endTokenPos' => 2482,
                'endFilePos' => 24949,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 638,
            'endLine' => 638,
            'startColumn' => 33,
            'endColumn' => 50,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 638,
                'endLine' => 638,
                'startTokenPos' => 2489,
                'startFilePos' => 24964,
                'endTokenPos' => 2489,
                'endFilePos' => 24967,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 638,
            'endLine' => 638,
            'startColumn' => 53,
            'endColumn' => 68,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
          'inverse' => 
          array (
            'name' => 'inverse',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 638,
                'endLine' => 638,
                'startTokenPos' => 2496,
                'startFilePos' => 24981,
                'endTokenPos' => 2496,
                'endFilePos' => 24985,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 638,
            'endLine' => 638,
            'startColumn' => 71,
            'endColumn' => 86,
            'parameterIndex' => 8,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a polymorphic many-to-many relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string  $name
 * @param  string|null  $table
 * @param  string|null  $foreignPivotKey
 * @param  string|null  $relatedPivotKey
 * @param  string|null  $parentKey
 * @param  string|null  $relatedKey
 * @param  string|null  $relation
 * @param  bool  $inverse
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphToMany<TRelatedModel, $this>
 */',
        'startLine' => 636,
        'endLine' => 667,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newMorphToMany' => 
      array (
        'name' => 'newMorphToMany',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 687,
            'endLine' => 687,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 687,
            'endLine' => 687,
            'startColumn' => 55,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 687,
            'endLine' => 687,
            'startColumn' => 70,
            'endColumn' => 74,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 687,
            'endLine' => 687,
            'startColumn' => 77,
            'endColumn' => 82,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'foreignPivotKey' => 
          array (
            'name' => 'foreignPivotKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 687,
            'endLine' => 687,
            'startColumn' => 85,
            'endColumn' => 100,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
          'relatedPivotKey' => 
          array (
            'name' => 'relatedPivotKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 688,
            'endLine' => 688,
            'startColumn' => 39,
            'endColumn' => 54,
            'parameterIndex' => 5,
            'isOptional' => false,
          ),
          'parentKey' => 
          array (
            'name' => 'parentKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 688,
            'endLine' => 688,
            'startColumn' => 57,
            'endColumn' => 66,
            'parameterIndex' => 6,
            'isOptional' => false,
          ),
          'relatedKey' => 
          array (
            'name' => 'relatedKey',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 688,
            'endLine' => 688,
            'startColumn' => 69,
            'endColumn' => 79,
            'parameterIndex' => 7,
            'isOptional' => false,
          ),
          'relationName' => 
          array (
            'name' => 'relationName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 689,
                'endLine' => 689,
                'startTokenPos' => 2736,
                'startFilePos' => 27193,
                'endTokenPos' => 2736,
                'endFilePos' => 27196,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 689,
            'endLine' => 689,
            'startColumn' => 39,
            'endColumn' => 58,
            'parameterIndex' => 8,
            'isOptional' => true,
          ),
          'inverse' => 
          array (
            'name' => 'inverse',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 689,
                'endLine' => 689,
                'startTokenPos' => 2743,
                'startFilePos' => 27210,
                'endTokenPos' => 2743,
                'endFilePos' => 27214,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 689,
            'endLine' => 689,
            'startColumn' => 61,
            'endColumn' => 76,
            'parameterIndex' => 9,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Instantiate a new MorphToMany relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>  $query
 * @param  TDeclaringModel  $parent
 * @param  string  $name
 * @param  string  $table
 * @param  string  $foreignPivotKey
 * @param  string  $relatedPivotKey
 * @param  string  $parentKey
 * @param  string  $relatedKey
 * @param  string|null  $relationName
 * @param  bool  $inverse
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphToMany<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 687,
        'endLine' => 693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'morphedByMany' => 
      array (
        'name' => 'morphedByMany',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 45,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 710,
                'endLine' => 710,
                'startTokenPos' => 2805,
                'startFilePos' => 28029,
                'endTokenPos' => 2805,
                'endFilePos' => 28032,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'foreignPivotKey' => 
          array (
            'name' => 'foreignPivotKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 710,
                'endLine' => 710,
                'startTokenPos' => 2812,
                'startFilePos' => 28054,
                'endTokenPos' => 2812,
                'endFilePos' => 28057,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 67,
            'endColumn' => 89,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'relatedPivotKey' => 
          array (
            'name' => 'relatedPivotKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 711,
                'endLine' => 711,
                'startTokenPos' => 2819,
                'startFilePos' => 28113,
                'endTokenPos' => 2819,
                'endFilePos' => 28116,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 711,
            'endLine' => 711,
            'startColumn' => 35,
            'endColumn' => 57,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'parentKey' => 
          array (
            'name' => 'parentKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 711,
                'endLine' => 711,
                'startTokenPos' => 2826,
                'startFilePos' => 28132,
                'endTokenPos' => 2826,
                'endFilePos' => 28135,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 711,
            'endLine' => 711,
            'startColumn' => 60,
            'endColumn' => 76,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'relatedKey' => 
          array (
            'name' => 'relatedKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 711,
                'endLine' => 711,
                'startTokenPos' => 2833,
                'startFilePos' => 28152,
                'endTokenPos' => 2833,
                'endFilePos' => 28155,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 711,
            'endLine' => 711,
            'startColumn' => 79,
            'endColumn' => 96,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 711,
                'endLine' => 711,
                'startTokenPos' => 2840,
                'startFilePos' => 28170,
                'endTokenPos' => 2840,
                'endFilePos' => 28173,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 711,
            'endLine' => 711,
            'startColumn' => 99,
            'endColumn' => 114,
            'parameterIndex' => 7,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Define a polymorphic, inverse many-to-many relationship.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $related
 * @param  string  $name
 * @param  string|null  $table
 * @param  string|null  $foreignPivotKey
 * @param  string|null  $relatedPivotKey
 * @param  string|null  $parentKey
 * @param  string|null  $relatedKey
 * @param  string|null  $relation
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphToMany<TRelatedModel, $this>
 */',
        'startLine' => 710,
        'endLine' => 724,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'guessBelongsToManyRelation' => 
      array (
        'name' => 'guessBelongsToManyRelation',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the relationship name of the belongsToMany relationship.
 *
 * @return string|null
 */',
        'startLine' => 731,
        'endLine' => 741,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'joiningTable' => 
      array (
        'name' => 'joiningTable',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 750,
            'endLine' => 750,
            'startColumn' => 34,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'instance' => 
          array (
            'name' => 'instance',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 750,
                'endLine' => 750,
                'startTokenPos' => 3023,
                'startFilePos' => 29522,
                'endTokenPos' => 3023,
                'endFilePos' => 29525,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 750,
            'endLine' => 750,
            'startColumn' => 44,
            'endColumn' => 59,
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
 * Get the joining table name for a many-to-many relation.
 *
 * @param  string  $related
 * @param  \\Illuminate\\Database\\Eloquent\\Model|null  $instance
 * @return string
 */',
        'startLine' => 750,
        'endLine' => 767,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'joiningTableSegment' => 
      array (
        'name' => 'joiningTableSegment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get this model\'s half of the intermediate table name for belongsToMany relationships.
 *
 * @return string
 */',
        'startLine' => 774,
        'endLine' => 777,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'touches' => 
      array (
        'name' => 'touches',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 785,
            'endLine' => 785,
            'startColumn' => 29,
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
 * Determine if the model touches a given relation.
 *
 * @param  string  $relation
 * @return bool
 */',
        'startLine' => 785,
        'endLine' => 788,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'touchOwners' => 
      array (
        'name' => 'touchOwners',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Touch the owning relations of the model.
 *
 * @return void
 */',
        'startLine' => 795,
        'endLine' => 810,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'getMorphs' => 
      array (
        'name' => 'getMorphs',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 820,
            'endLine' => 820,
            'startColumn' => 34,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 820,
            'endLine' => 820,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 820,
            'endLine' => 820,
            'startColumn' => 48,
            'endColumn' => 50,
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
 * Get the polymorphic relationship columns.
 *
 * @param  string  $name
 * @param  string  $type
 * @param  string  $id
 * @return array
 */',
        'startLine' => 820,
        'endLine' => 823,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'getMorphClass' => 
      array (
        'name' => 'getMorphClass',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the class name for polymorphic relations.
 *
 * @return string
 */',
        'startLine' => 830,
        'endLine' => 847,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newRelatedInstance' => 
      array (
        'name' => 'newRelatedInstance',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 857,
            'endLine' => 857,
            'startColumn' => 43,
            'endColumn' => 48,
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
 * Create a new model instance for a related model.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $class
 * @return TRelatedModel
 */',
        'startLine' => 857,
        'endLine' => 864,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'newRelatedThroughInstance' => 
      array (
        'name' => 'newRelatedThroughInstance',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 874,
            'endLine' => 874,
            'startColumn' => 50,
            'endColumn' => 55,
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
 * Create a new model instance for a related "through" model.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  class-string<TRelatedModel>  $class
 * @return TRelatedModel
 */',
        'startLine' => 874,
        'endLine' => 877,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'getRelations' => 
      array (
        'name' => 'getRelations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all the loaded relations for the instance.
 *
 * @return array
 */',
        'startLine' => 884,
        'endLine' => 887,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'getRelation' => 
      array (
        'name' => 'getRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 895,
            'endLine' => 895,
            'startColumn' => 33,
            'endColumn' => 41,
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
 * Get a specified relationship.
 *
 * @param  string  $relation
 * @return mixed
 */',
        'startLine' => 895,
        'endLine' => 898,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'relationLoaded' => 
      array (
        'name' => 'relationLoaded',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 906,
            'endLine' => 906,
            'startColumn' => 36,
            'endColumn' => 39,
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
 * Determine if the given relation is loaded.
 *
 * @param  string  $key
 * @return bool
 */',
        'startLine' => 906,
        'endLine' => 909,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'setRelation' => 
      array (
        'name' => 'setRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 918,
            'endLine' => 918,
            'startColumn' => 33,
            'endColumn' => 41,
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
            'startLine' => 918,
            'endLine' => 918,
            'startColumn' => 44,
            'endColumn' => 49,
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
 * Set the given relationship on the model.
 *
 * @param  string  $relation
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 918,
        'endLine' => 923,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'unsetRelation' => 
      array (
        'name' => 'unsetRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 931,
            'endLine' => 931,
            'startColumn' => 35,
            'endColumn' => 43,
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
 * Unset a loaded relationship.
 *
 * @param  string  $relation
 * @return $this
 */',
        'startLine' => 931,
        'endLine' => 936,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'setRelations' => 
      array (
        'name' => 'setRelations',
        'parameters' => 
        array (
          'relations' => 
          array (
            'name' => 'relations',
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
            'startLine' => 944,
            'endLine' => 944,
            'startColumn' => 34,
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
 * Set the entire relations array on the model.
 *
 * @param  array  $relations
 * @return $this
 */',
        'startLine' => 944,
        'endLine' => 949,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'withoutRelations' => 
      array (
        'name' => 'withoutRelations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Duplicate the instance and unset all the loaded relations.
 *
 * @return $this
 */',
        'startLine' => 956,
        'endLine' => 961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'unsetRelations' => 
      array (
        'name' => 'unsetRelations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Unset all the loaded relations for the instance.
 *
 * @return $this
 */',
        'startLine' => 968,
        'endLine' => 973,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'getTouchedRelations' => 
      array (
        'name' => 'getTouchedRelations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the relationships that are touched on save.
 *
 * @return array
 */',
        'startLine' => 980,
        'endLine' => 983,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'aliasName' => NULL,
      ),
      'setTouchedRelations' => 
      array (
        'name' => 'setTouchedRelations',
        'parameters' => 
        array (
          'touches' => 
          array (
            'name' => 'touches',
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
            'startLine' => 991,
            'endLine' => 991,
            'startColumn' => 41,
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
 * Set the relationships that are touched on save.
 *
 * @param  array  $touches
 * @return $this
 */',
        'startLine' => 991,
        'endLine' => 996,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
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