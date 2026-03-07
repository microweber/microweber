<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/DB.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\DB
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-333938f44a918488d343fd9defce7dac3e48505117514b5bf1020618b7feabe7-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\DB',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/DB.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\DB',
    'shortName' => 'DB',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Database\\Connection connection(string|null $name = null)
 * @method static \\Illuminate\\Database\\ConnectionInterface build(array $config)
 * @method static string calculateDynamicConnectionName(array $config)
 * @method static \\Illuminate\\Database\\ConnectionInterface connectUsing(string $name, array $config, bool $force = false)
 * @method static void purge(string|null $name = null)
 * @method static void disconnect(string|null $name = null)
 * @method static \\Illuminate\\Database\\Connection reconnect(string|null $name = null)
 * @method static mixed usingConnection(string $name, callable $callback)
 * @method static string getDefaultConnection()
 * @method static void setDefaultConnection(string $name)
 * @method static string[] supportedDrivers()
 * @method static string[] availableDrivers()
 * @method static void extend(string $name, callable $resolver)
 * @method static void forgetExtension(string $name)
 * @method static array getConnections()
 * @method static void setReconnector(callable $reconnector)
 * @method static \\Illuminate\\Database\\DatabaseManager setApplication(\\Illuminate\\Contracts\\Foundation\\Application $app)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 * @method static void useDefaultQueryGrammar()
 * @method static void useDefaultSchemaGrammar()
 * @method static void useDefaultPostProcessor()
 * @method static \\Illuminate\\Database\\Schema\\Builder getSchemaBuilder()
 * @method static \\Illuminate\\Database\\Query\\Builder table(\\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Contracts\\Database\\Query\\Expression|string $table, string|null $as = null)
 * @method static \\Illuminate\\Database\\Query\\Builder query()
 * @method static mixed selectOne(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static mixed scalar(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static array selectFromWriteConnection(string $query, array $bindings = [])
 * @method static array select(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static array selectResultSets(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static \\Generator cursor(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static bool insert(string $query, array $bindings = [])
 * @method static int update(string $query, array $bindings = [])
 * @method static int delete(string $query, array $bindings = [])
 * @method static bool statement(string $query, array $bindings = [])
 * @method static int affectingStatement(string $query, array $bindings = [])
 * @method static bool unprepared(string $query)
 * @method static int|null threadCount()
 * @method static array pretend(\\Closure $callback)
 * @method static mixed withoutPretending(\\Closure $callback)
 * @method static void bindValues(\\PDOStatement $statement, array $bindings)
 * @method static array prepareBindings(array $bindings)
 * @method static void logQuery(string $query, array $bindings, float|null $time = null)
 * @method static void whenQueryingForLongerThan(\\DateTimeInterface|\\Carbon\\CarbonInterval|float|int $threshold, callable $handler)
 * @method static void allowQueryDurationHandlersToRunAgain()
 * @method static float totalQueryDuration()
 * @method static void resetTotalQueryDuration()
 * @method static void reconnectIfMissingConnection()
 * @method static \\Illuminate\\Database\\Connection beforeStartingTransaction(\\Closure $callback)
 * @method static \\Illuminate\\Database\\Connection beforeExecuting(\\Closure $callback)
 * @method static void listen(\\Closure $callback)
 * @method static \\Illuminate\\Contracts\\Database\\Query\\Expression raw(mixed $value)
 * @method static string escape(string|float|int|bool|null $value, bool $binary = false)
 * @method static bool hasModifiedRecords()
 * @method static void recordsHaveBeenModified(bool $value = true)
 * @method static \\Illuminate\\Database\\Connection setRecordModificationState(bool $value)
 * @method static void forgetRecordModificationState()
 * @method static \\Illuminate\\Database\\Connection useWriteConnectionWhenReading(bool $value = true)
 * @method static \\PDO getPdo()
 * @method static \\PDO|\\Closure|null getRawPdo()
 * @method static \\PDO getReadPdo()
 * @method static \\PDO|\\Closure|null getRawReadPdo()
 * @method static \\Illuminate\\Database\\Connection setPdo(\\PDO|\\Closure|null $pdo)
 * @method static \\Illuminate\\Database\\Connection setReadPdo(\\PDO|\\Closure|null $pdo)
 * @method static string|null getName()
 * @method static string|null getNameWithReadWriteType()
 * @method static mixed getConfig(string|null $option = null)
 * @method static string getDriverName()
 * @method static string getDriverTitle()
 * @method static \\Illuminate\\Database\\Query\\Grammars\\Grammar getQueryGrammar()
 * @method static \\Illuminate\\Database\\Connection setQueryGrammar(\\Illuminate\\Database\\Query\\Grammars\\Grammar $grammar)
 * @method static \\Illuminate\\Database\\Schema\\Grammars\\Grammar getSchemaGrammar()
 * @method static \\Illuminate\\Database\\Connection setSchemaGrammar(\\Illuminate\\Database\\Schema\\Grammars\\Grammar $grammar)
 * @method static \\Illuminate\\Database\\Query\\Processors\\Processor getPostProcessor()
 * @method static \\Illuminate\\Database\\Connection setPostProcessor(\\Illuminate\\Database\\Query\\Processors\\Processor $processor)
 * @method static \\Illuminate\\Contracts\\Events\\Dispatcher getEventDispatcher()
 * @method static \\Illuminate\\Database\\Connection setEventDispatcher(\\Illuminate\\Contracts\\Events\\Dispatcher $events)
 * @method static void unsetEventDispatcher()
 * @method static \\Illuminate\\Database\\Connection setTransactionManager(\\Illuminate\\Database\\DatabaseTransactionsManager $manager)
 * @method static void unsetTransactionManager()
 * @method static bool pretending()
 * @method static array getQueryLog()
 * @method static array getRawQueryLog()
 * @method static void flushQueryLog()
 * @method static void enableQueryLog()
 * @method static void disableQueryLog()
 * @method static bool logging()
 * @method static string getDatabaseName()
 * @method static \\Illuminate\\Database\\Connection setDatabaseName(string $database)
 * @method static \\Illuminate\\Database\\Connection setReadWriteType(string|null $readWriteType)
 * @method static string getTablePrefix()
 * @method static \\Illuminate\\Database\\Connection setTablePrefix(string $prefix)
 * @method static \\Illuminate\\Database\\Grammar withTablePrefix(\\Illuminate\\Database\\Grammar $grammar)
 * @method static void withoutTablePrefix(\\Closure $callback)
 * @method static string getServerVersion()
 * @method static void resolverFor(string $driver, \\Closure $callback)
 * @method static \\Closure|null getResolver(string $driver)
 * @method static mixed transaction(\\Closure $callback, int $attempts = 1)
 * @method static void beginTransaction()
 * @method static void commit()
 * @method static void rollBack(int|null $toLevel = null)
 * @method static int transactionLevel()
 * @method static void afterCommit(callable $callback)
 *
 * @see \\Illuminate\\Database\\DatabaseManager
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 121,
    'endLine' => 149,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Support\\Facades\\Facade',
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
      'prohibitDestructiveCommands' => 
      array (
        'name' => 'prohibitDestructiveCommands',
        'parameters' => 
        array (
          'prohibit' => 
          array (
            'name' => 'prohibit',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 131,
                'endLine' => 131,
                'startTokenPos' => 60,
                'startFilePos' => 7956,
                'endTokenPos' => 60,
                'endFilePos' => 7959,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 56,
            'endColumn' => 76,
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
 * Indicate if destructive Artisan commands should be prohibited.
 *
 * Prohibits: db:wipe, migrate:fresh, migrate:refresh, and migrate:reset
 *
 * @param  bool  $prohibit
 * @return void
 */',
        'startLine' => 131,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\DB',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\DB',
        'currentClassName' => 'Illuminate\\Support\\Facades\\DB',
        'aliasName' => NULL,
      ),
      'getFacadeAccessor' => 
      array (
        'name' => 'getFacadeAccessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the registered name of the component.
 *
 * @return string
 */',
        'startLine' => 145,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\DB',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\DB',
        'currentClassName' => 'Illuminate\\Support\\Facades\\DB',
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