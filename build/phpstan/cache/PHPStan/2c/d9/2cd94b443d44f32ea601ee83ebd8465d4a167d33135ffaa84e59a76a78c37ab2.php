<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Concerns\ManagesTransactions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-90ce05a1fcf900671b8f23a3352edf74d8f0b1f58ede7f12872efcf7472753c3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Concerns',
    'name' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
    'shortName' => 'ManagesTransactions',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 353,
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
      'transaction' => 
      array (
        'name' => 'transaction',
        'parameters' => 
        array (
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
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attempts' => 
          array (
            'name' => 'attempts',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 23,
                'endLine' => 23,
                'startTokenPos' => 50,
                'startFilePos' => 484,
                'endTokenPos' => 50,
                'endFilePos' => 484,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 52,
            'endColumn' => 64,
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
 * @template TReturn of mixed
 *
 * Execute a Closure within a transaction.
 *
 * @param  (\\Closure(static): TReturn)  $callback
 * @param  int  $attempts
 * @return TReturn
 *
 * @throws \\Throwable
 */',
        'startLine' => 23,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'handleTransactionException' => 
      array (
        'name' => 'handleTransactionException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 51,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'currentAttempt' => 
          array (
            'name' => 'currentAttempt',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 65,
            'endColumn' => 79,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 85,
            'endLine' => 85,
            'startColumn' => 82,
            'endColumn' => 93,
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
 * Handle an exception encountered when running a transacted statement.
 *
 * @param  \\Throwable  $e
 * @param  int  $currentAttempt
 * @param  int  $maxAttempts
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 85,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'beginTransaction' => 
      array (
        'name' => 'beginTransaction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Start a new database transaction.
 *
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 121,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'createTransaction' => 
      array (
        'name' => 'createTransaction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a transaction within the database.
 *
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 145,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'createSavepoint' => 
      array (
        'name' => 'createSavepoint',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a save point within the database.
 *
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 167,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'handleBeginTransactionException' => 
      array (
        'name' => 'handleBeginTransactionException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 182,
            'endLine' => 182,
            'startColumn' => 56,
            'endColumn' => 67,
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
 * Handle an exception from a transaction beginning.
 *
 * @param  \\Throwable  $e
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 182,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'commit' => 
      array (
        'name' => 'commit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Commit the active database transaction.
 *
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 200,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'handleCommitTransactionException' => 
      array (
        'name' => 'handleCommitTransactionException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 57,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'currentAttempt' => 
          array (
            'name' => 'currentAttempt',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 71,
            'endColumn' => 85,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 88,
            'endColumn' => 99,
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
 * Handle an exception encountered when committing a transaction.
 *
 * @param  \\Throwable  $e
 * @param  int  $currentAttempt
 * @param  int  $maxAttempts
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 229,
        'endLine' => 242,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'rollBack' => 
      array (
        'name' => 'rollBack',
        'parameters' => 
        array (
          'toLevel' => 
          array (
            'name' => 'toLevel',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 252,
                'endLine' => 252,
                'startTokenPos' => 972,
                'startFilePos' => 6947,
                'endTokenPos' => 972,
                'endFilePos' => 6950,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 252,
            'endLine' => 252,
            'startColumn' => 30,
            'endColumn' => 44,
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
 * Rollback the active database transaction.
 *
 * @param  int|null  $toLevel
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 252,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'performRollBack' => 
      array (
        'name' => 'performRollBack',
        'parameters' => 
        array (
          'toLevel' => 
          array (
            'name' => 'toLevel',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 291,
            'endLine' => 291,
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
 * Perform a rollback within the database.
 *
 * @param  int  $toLevel
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 291,
        'endLine' => 304,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'handleRollBackException' => 
      array (
        'name' => 'handleRollBackException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Throwable',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 314,
            'endLine' => 314,
            'startColumn' => 48,
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
 * Handle an exception from a rollback.
 *
 * @param  \\Throwable  $e
 * @return void
 *
 * @throws \\Throwable
 */',
        'startLine' => 314,
        'endLine' => 325,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'transactionLevel' => 
      array (
        'name' => 'transactionLevel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of active transactions.
 *
 * @return int
 */',
        'startLine' => 332,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'aliasName' => NULL,
      ),
      'afterCommit' => 
      array (
        'name' => 'afterCommit',
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
            'startLine' => 345,
            'endLine' => 345,
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
 * Execute the callback after a transaction commits.
 *
 * @param  callable  $callback
 * @return void
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 345,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'implementingClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
        'currentClassName' => 'Illuminate\\Database\\Concerns\\ManagesTransactions',
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