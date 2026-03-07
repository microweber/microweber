<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Concerns/BuildsWhereDateClauses.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Concerns\BuildsWhereDateClauses
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-74c34940e42b272f7d35821c46ab7477fca13dcbe72d8e03bf8686335df3b278-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Concerns/BuildsWhereDateClauses.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Concerns',
    'name' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
    'shortName' => 'BuildsWhereDateClauses',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 250,
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
      'wherePast' => 
      array (
        'name' => 'wherePast',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 31,
            'endColumn' => 38,
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
 * Add a where clause to determine if a "date" column is in the past to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 16,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereNowOrPast' => 
      array (
        'name' => 'whereNowOrPast',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 36,
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
 * Add a where clause to determine if a "date" column is in the past or now to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 27,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWherePast' => 
      array (
        'name' => 'orWherePast',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 33,
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
 * Add an "or where" clause to determine if a "date" column is in the past to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereNowOrPast' => 
      array (
        'name' => 'orWhereNowOrPast',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startColumn' => 38,
            'endColumn' => 45,
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
 * Add a where clause to determine if a "date" column is in the past or now to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 49,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereFuture' => 
      array (
        'name' => 'whereFuture',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 60,
            'endLine' => 60,
            'startColumn' => 33,
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
 * Add a where clause to determine if a "date" column is in the future to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereNowOrFuture' => 
      array (
        'name' => 'whereNowOrFuture',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 71,
            'endLine' => 71,
            'startColumn' => 38,
            'endColumn' => 45,
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
 * Add a where clause to determine if a "date" column is in the future or now to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 71,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereFuture' => 
      array (
        'name' => 'orWhereFuture',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 35,
            'endColumn' => 42,
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
 * Add an "or where" clause to determine if a "date" column is in the future to the query.
 *
 * @param  array|string  $columns
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
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereNowOrFuture' => 
      array (
        'name' => 'orWhereNowOrFuture',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 40,
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
 * Add an "or where" clause to determine if a "date" column is in the future or now to the query.
 *
 * @param  array|string  $columns
 * @return $this
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
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'wherePastOrFuture' => 
      array (
        'name' => 'wherePastOrFuture',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 42,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 52,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 104,
            'endLine' => 104,
            'startColumn' => 63,
            'endColumn' => 70,
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
 * Add an "where" clause to determine if a "date" column is in the past or future.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 104,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereToday' => 
      array (
        'name' => 'whereToday',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 32,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 125,
                'endLine' => 125,
                'startTokenPos' => 383,
                'startFilePos' => 3276,
                'endTokenPos' => 383,
                'endFilePos' => 3280,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 42,
            'endColumn' => 57,
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
 * Add a "where date" clause to determine if a "date" column is today to the query.
 *
 * @param  array|string  $columns
 * @param  string  $boolean
 * @return $this
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
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereBeforeToday' => 
      array (
        'name' => 'whereBeforeToday',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 38,
            'endColumn' => 45,
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
 * Add a "where date" clause to determine if a "date" column is before today.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereTodayOrBefore' => 
      array (
        'name' => 'whereTodayOrBefore',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startColumn' => 40,
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
 * Add a "where date" clause to determine if a "date" column is today or before to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 147,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereAfterToday' => 
      array (
        'name' => 'whereAfterToday',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 158,
            'endLine' => 158,
            'startColumn' => 37,
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
 * Add a "where date" clause to determine if a "date" column is after today.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 158,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereTodayOrAfter' => 
      array (
        'name' => 'whereTodayOrAfter',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 169,
            'endLine' => 169,
            'startColumn' => 39,
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
 * Add a "where date" clause to determine if a "date" column is today or after to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 169,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereToday' => 
      array (
        'name' => 'orWhereToday',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 180,
            'endLine' => 180,
            'startColumn' => 34,
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
 * Add an "or where date" clause to determine if a "date" column is today to the query.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 180,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereBeforeToday' => 
      array (
        'name' => 'orWhereBeforeToday',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 191,
            'endLine' => 191,
            'startColumn' => 40,
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
 * Add an "or where date" clause to determine if a "date" column is before today.
 *
 * @param  array|string  $columns
 * @return $this
 */',
        'startLine' => 191,
        'endLine' => 194,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereTodayOrBefore' => 
      array (
        'name' => 'orWhereTodayOrBefore',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 42,
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
 * Add an "or where date" clause to determine if a "date" column is today or before to the query.
 *
 * @param  array|string  $columns
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 203,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereAfterToday' => 
      array (
        'name' => 'orWhereAfterToday',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 215,
            'endLine' => 215,
            'startColumn' => 39,
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
 * Add an "or where date" clause to determine if a "date" column is after today.
 *
 * @param  array|string  $columns
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 215,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'orWhereTodayOrAfter' => 
      array (
        'name' => 'orWhereTodayOrAfter',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 227,
            'endLine' => 227,
            'startColumn' => 41,
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
 * Add an "or where date" clause to determine if a "date" column is today or after to the query.
 *
 * @param  array|string  $columns
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 227,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'aliasName' => NULL,
      ),
      'whereTodayBeforeOrAfter' => 
      array (
        'name' => 'whereTodayBeforeOrAfter',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 48,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 58,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 69,
            'endColumn' => 76,
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
 * Add a "where date" clause to determine if a "date" column is today or after to the query.
 *
 * @param  array|string  $columns
 * @param  string  $operator
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 240,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
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