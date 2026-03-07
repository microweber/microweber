<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Relations/Concerns/CanBeOneOfMany.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Eloquent\Relations\Concerns\CanBeOneOfMany
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-38bc5d2eb7c99bc89747c75f7d17e019e2ccec21afb3441a35136977b66681bc-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Relations/Concerns/CanBeOneOfMany.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
    'name' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
    'shortName' => 'CanBeOneOfMany',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 332,
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
      'isOneOfMany' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'name' => 'isOneOfMany',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 51,
            'startFilePos' => 409,
            'endTokenPos' => 51,
            'endFilePos' => 413,
          ),
        ),
        'docComment' => '/**
 * Determines whether the relationship is one-of-many.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'relationName' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'name' => 'relationName',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The name of the relationship.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'oneOfManySubQuery' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'name' => 'oneOfManySubQuery',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The one of many inner join subselect query builder instance.
 *
 * @var \\Illuminate\\Database\\Eloquent\\Builder<*>|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 33,
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
      'addOneOfManySubQueryConstraints' => 
      array (
        'name' => 'addOneOfManySubQueryConstraints',
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
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 62,
            'endColumn' => 75,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 87,
                'startFilePos' => 1058,
                'endTokenPos' => 87,
                'endFilePos' => 1061,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 78,
            'endColumn' => 91,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'aggregate' => 
          array (
            'name' => 'aggregate',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 94,
                'startFilePos' => 1077,
                'endTokenPos' => 94,
                'endFilePos' => 1080,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 94,
            'endColumn' => 110,
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
 * Add constraints for inner join subselect for one of many relationships.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<*>  $query
 * @param  string|null  $column
 * @param  string|null  $aggregate
 * @return void
 */',
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 112,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'getOneOfManySubQuerySelectColumns' => 
      array (
        'name' => 'getOneOfManySubQuerySelectColumns',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the columns the determine the relationship groups.
 *
 * @return array|string
 */',
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'addOneOfManyJoinSubQueryConstraints' => 
      array (
        'name' => 'addOneOfManyJoinSubQueryConstraints',
        'parameters' => 
        array (
          'join' => 
          array (
            'name' => 'join',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Query\\JoinClause',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 66,
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
 * Add join query constraints for one of many relationships.
 *
 * @param  \\Illuminate\\Database\\Query\\JoinClause  $join
 * @return void
 */',
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 83,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'ofMany' => 
      array (
        'name' => 'ofMany',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'id\'',
              'attributes' => 
              array (
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 139,
                'startFilePos' => 1866,
                'endTokenPos' => 139,
                'endFilePos' => 1869,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'aggregate' => 
          array (
            'name' => 'aggregate',
            'default' => 
            array (
              'code' => '\'MAX\'',
              'attributes' => 
              array (
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 146,
                'startFilePos' => 1885,
                'endTokenPos' => 146,
                'endFilePos' => 1889,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 44,
            'endColumn' => 61,
            'parameterIndex' => 1,
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
                'startLine' => 70,
                'endLine' => 70,
                'startTokenPos' => 153,
                'startFilePos' => 1904,
                'endTokenPos' => 153,
                'endFilePos' => 1907,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 64,
            'endColumn' => 79,
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
 * Indicate that the relation is a single result of a larger one-to-many relationship.
 *
 * @param  string|array|null  $column
 * @param  string|\\Closure|null  $aggregate
 * @param  string|null  $relation
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 70,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'latestOfMany' => 
      array (
        'name' => 'latestOfMany',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'id\'',
              'attributes' => 
              array (
                'startLine' => 152,
                'endLine' => 152,
                'startTokenPos' => 646,
                'startFilePos' => 4382,
                'endTokenPos' => 646,
                'endFilePos' => 4385,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
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
                'startLine' => 152,
                'endLine' => 152,
                'startTokenPos' => 653,
                'startFilePos' => 4400,
                'endTokenPos' => 653,
                'endFilePos' => 4403,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 152,
            'endLine' => 152,
            'startColumn' => 50,
            'endColumn' => 65,
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
 * Indicate that the relation is the latest single result of a larger one-to-many relationship.
 *
 * @param  string|array|null  $column
 * @param  string|null  $relation
 * @return $this
 */',
        'startLine' => 152,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'oldestOfMany' => 
      array (
        'name' => 'oldestOfMany',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'id\'',
              'attributes' => 
              array (
                'startLine' => 166,
                'endLine' => 166,
                'startTokenPos' => 721,
                'startFilePos' => 4852,
                'endTokenPos' => 721,
                'endFilePos' => 4855,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 166,
            'endLine' => 166,
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
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
                'startLine' => 166,
                'endLine' => 166,
                'startTokenPos' => 728,
                'startFilePos' => 4870,
                'endTokenPos' => 728,
                'endFilePos' => 4873,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 166,
            'endLine' => 166,
            'startColumn' => 50,
            'endColumn' => 65,
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
 * Indicate that the relation is the oldest single result of a larger one-to-many relationship.
 *
 * @param  string|array|null  $column
 * @param  string|null  $relation
 * @return $this
 */',
        'startLine' => 166,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'getDefaultOneOfManyJoinAlias' => 
      array (
        'name' => 'getDefaultOneOfManyJoinAlias',
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
            'startLine' => 179,
            'endLine' => 179,
            'startColumn' => 53,
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
 * Get the default alias for the one of many inner join clause.
 *
 * @param  string  $relation
 * @return string
 */',
        'startLine' => 179,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'newOneOfManySubQuery' => 
      array (
        'name' => 'newOneOfManySubQuery',
        'parameters' => 
        array (
          'groupBy' => 
          array (
            'name' => 'groupBy',
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
            'startColumn' => 45,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 194,
                'endLine' => 194,
                'startTokenPos' => 843,
                'startFilePos' => 5796,
                'endTokenPos' => 843,
                'endFilePos' => 5799,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 55,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'aggregate' => 
          array (
            'name' => 'aggregate',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 194,
                'endLine' => 194,
                'startTokenPos' => 850,
                'startFilePos' => 5815,
                'endTokenPos' => 850,
                'endFilePos' => 5818,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 194,
            'endLine' => 194,
            'startColumn' => 72,
            'endColumn' => 88,
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
 * Get a new query for the related model, grouping the query by the given column, often the foreign key of the relationship.
 *
 * @param  string|array  $groupBy
 * @param  array<string>|null  $columns
 * @param  string|null  $aggregate
 * @return \\Illuminate\\Database\\Eloquent\\Builder<*>
 */',
        'startLine' => 194,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'addOneOfManyJoinSubQuery' => 
      array (
        'name' => 'addOneOfManyJoinSubQuery',
        'parameters' => 
        array (
          'parent' => 
          array (
            'name' => 'parent',
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
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 49,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subQuery' => 
          array (
            'name' => 'subQuery',
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
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 66,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'on' => 
          array (
            'name' => 'on',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 85,
            'endColumn' => 87,
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
 * Add the join subquery to the given query on the given column and the relationship\'s foreign key.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<*>  $parent
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<*>  $subQuery
 * @param  array<string>  $on
 * @return void
 */',
        'startLine' => 231,
        'endLine' => 244,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'mergeOneOfManyJoinsTo' => 
      array (
        'name' => 'mergeOneOfManyJoinsTo',
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
            'startLine' => 252,
            'endLine' => 252,
            'startColumn' => 46,
            'endColumn' => 59,
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
 * Merge the relationship query joins to the given query builder.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<*>  $query
 * @return void
 */',
        'startLine' => 252,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'getRelationQuery' => 
      array (
        'name' => 'getRelationQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the query builder that will contain the relationship constraints.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<*>
 */',
        'startLine' => 264,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'getOneOfManySubQuery' => 
      array (
        'name' => 'getOneOfManySubQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the one of many inner join subselect builder instance.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<*>|void
 */',
        'startLine' => 276,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'qualifySubSelectColumn' => 
      array (
        'name' => 'qualifySubSelectColumn',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 287,
            'endLine' => 287,
            'startColumn' => 44,
            'endColumn' => 50,
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
 * Get the qualified column name for the one-of-many relationship using the subselect join query\'s alias.
 *
 * @param  string  $column
 * @return string
 */',
        'startLine' => 287,
        'endLine' => 290,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'qualifyRelatedColumn' => 
      array (
        'name' => 'qualifyRelatedColumn',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 45,
            'endColumn' => 51,
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
 * Qualify related column using the related table name if it is not already qualified.
 *
 * @param  string  $column
 * @return string
 */',
        'startLine' => 298,
        'endLine' => 301,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'guessRelationship' => 
      array (
        'name' => 'guessRelationship',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Guess the "hasOne" relationship\'s name via backtrace.
 *
 * @return string
 */',
        'startLine' => 308,
        'endLine' => 311,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'isOneOfMany' => 
      array (
        'name' => 'isOneOfMany',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine whether the relationship is a one-of-many relationship.
 *
 * @return bool
 */',
        'startLine' => 318,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'aliasName' => NULL,
      ),
      'getRelationName' => 
      array (
        'name' => 'getRelationName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the relationship.
 *
 * @return string
 */',
        'startLine' => 328,
        'endLine' => 331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\CanBeOneOfMany',
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