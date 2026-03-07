<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/DatabaseTransactionsManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\DatabaseTransactionsManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-aeb0e32e62d997650c7bd8a372ca051ef1f2990f0f62d94948f63f4ba3db7143-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/DatabaseTransactionsManager.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database',
    'name' => 'Illuminate\\Database\\DatabaseTransactionsManager',
    'shortName' => 'DatabaseTransactionsManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 248,
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
      'committedTransactions' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'name' => 'committedTransactions',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * All of the committed transactions.
 *
 * @var \\Illuminate\\Support\\Collection<int, \\Illuminate\\Database\\DatabaseTransactionRecord>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'pendingTransactions' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'name' => 'pendingTransactions',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * All of the pending transactions.
 *
 * @var \\Illuminate\\Support\\Collection<int, \\Illuminate\\Database\\DatabaseTransactionRecord>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'currentTransaction' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'name' => 'currentTransaction',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 40,
            'startFilePos' => 616,
            'endTokenPos' => 41,
            'endFilePos' => 617,
          ),
        ),
        'docComment' => '/**
 * The current transaction.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 39,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new database transactions manager instance.
 *
 * @return void
 */',
        'startLine' => 35,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'begin' => 
      array (
        'name' => 'begin',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
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
            'startColumn' => 27,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'level' => 
          array (
            'name' => 'level',
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
            'startColumn' => 40,
            'endColumn' => 45,
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
 * Start a new database transaction.
 *
 * @param  string  $connection
 * @param  int  $level
 * @return void
 */',
        'startLine' => 48,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'commit' => 
      array (
        'name' => 'commit',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 28,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'levelBeingCommitted' => 
          array (
            'name' => 'levelBeingCommitted',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 41,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'newTransactionLevel' => 
          array (
            'name' => 'newTransactionLevel',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 63,
            'endColumn' => 82,
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
 * Commit the root database transaction and execute callbacks.
 *
 * @param  string  $connection
 * @param  int  $levelBeingCommitted
 * @param  int  $newTransactionLevel
 * @return array
 */',
        'startLine' => 69,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'stageTransactions' => 
      array (
        'name' => 'stageTransactions',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 39,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'levelBeingCommitted' => 
          array (
            'name' => 'levelBeingCommitted',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 52,
            'endColumn' => 71,
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
 * Move relevant pending transactions to a committed state.
 *
 * @param  string  $connection
 * @param  int  $levelBeingCommitted
 * @return void
 */',
        'startLine' => 108,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'rollback' => 
      array (
        'name' => 'rollback',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 30,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'newTransactionLevel' => 
          array (
            'name' => 'newTransactionLevel',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 43,
            'endColumn' => 62,
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
 * Rollback the active database transaction.
 *
 * @param  string  $connection
 * @param  int  $newTransactionLevel
 * @return void
 */',
        'startLine' => 130,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'removeAllTransactionsForConnection' => 
      array (
        'name' => 'removeAllTransactionsForConnection',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
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
            'startColumn' => 59,
            'endColumn' => 69,
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
 * Remove all pending, completed, and current transactions for the given connection name.
 *
 * @param  string  $connection
 * @return void
 */',
        'startLine' => 159,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'removeCommittedTransactionsThatAreChildrenOf' => 
      array (
        'name' => 'removeCommittedTransactionsThatAreChildrenOf',
        'parameters' => 
        array (
          'transaction' => 
          array (
            'name' => 'transaction',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\DatabaseTransactionRecord',
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
            'startColumn' => 69,
            'endColumn' => 106,
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
 * Remove all transactions that are children of the given transaction.
 *
 * @param  \\Illuminate\\Database\\DatabaseTransactionRecord  $transaction
 * @return void
 */',
        'startLine' => 178,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'addCallback' => 
      array (
        'name' => 'addCallback',
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
            'startLine' => 199,
            'endLine' => 199,
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
 * Register a transaction callback.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 199,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'callbackApplicableTransactions' => 
      array (
        'name' => 'callbackApplicableTransactions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the transactions that are applicable to callbacks.
 *
 * @return \\Illuminate\\Support\\Collection<int, \\Illuminate\\Database\\DatabaseTransactionRecord>
 */',
        'startLine' => 213,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'afterCommitCallbacksShouldBeExecuted' => 
      array (
        'name' => 'afterCommitCallbacksShouldBeExecuted',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 224,
            'endLine' => 224,
            'startColumn' => 58,
            'endColumn' => 63,
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
 * Determine if after commit callbacks should be executed for the given transaction level.
 *
 * @param  int  $level
 * @return bool
 */',
        'startLine' => 224,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'getPendingTransactions' => 
      array (
        'name' => 'getPendingTransactions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the pending transactions.
 *
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 234,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'aliasName' => NULL,
      ),
      'getCommittedTransactions' => 
      array (
        'name' => 'getCommittedTransactions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the committed transactions.
 *
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 244,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database',
        'declaringClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'implementingClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
        'currentClassName' => 'Illuminate\\Database\\DatabaseTransactionsManager',
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