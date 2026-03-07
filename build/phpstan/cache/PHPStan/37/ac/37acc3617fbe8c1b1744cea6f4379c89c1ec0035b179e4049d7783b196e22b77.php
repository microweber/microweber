<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Cache.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Facades\Cache
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a4fbd385c6738a2261ee0177caefc728238fa3b31618216083cc940e801791c2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Facades\\Cache',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Support/Facades/Cache.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Facades',
    'name' => 'Illuminate\\Support\\Facades\\Cache',
    'shortName' => 'Cache',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Contracts\\Cache\\Repository store(string|null $name = null)
 * @method static \\Illuminate\\Contracts\\Cache\\Repository driver(string|null $driver = null)
 * @method static \\Illuminate\\Contracts\\Cache\\Repository resolve(string $name)
 * @method static \\Illuminate\\Cache\\Repository build(array $config)
 * @method static \\Illuminate\\Cache\\Repository repository(\\Illuminate\\Contracts\\Cache\\Store $store, array $config = [])
 * @method static void refreshEventDispatcher()
 * @method static string getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 * @method static \\Illuminate\\Cache\\CacheManager forgetDriver(array|string|null $name = null)
 * @method static void purge(string|null $name = null)
 * @method static \\Illuminate\\Cache\\CacheManager extend(string $driver, \\Closure $callback)
 * @method static \\Illuminate\\Cache\\CacheManager setApplication(\\Illuminate\\Contracts\\Foundation\\Application $app)
 * @method static bool has(array|string $key)
 * @method static bool missing(string $key)
 * @method static mixed get(array|string $key, mixed $default = null)
 * @method static array many(array $keys)
 * @method static iterable getMultiple(iterable $keys, mixed $default = null)
 * @method static mixed pull(array|string $key, mixed $default = null)
 * @method static bool put(array|string $key, mixed $value, \\DateTimeInterface|\\DateInterval|int|null $ttl = null)
 * @method static bool set(string $key, mixed $value, null|int|\\DateInterval $ttl = null)
 * @method static bool putMany(array $values, \\DateTimeInterface|\\DateInterval|int|null $ttl = null)
 * @method static bool setMultiple(iterable $values, null|int|\\DateInterval $ttl = null)
 * @method static bool add(string $key, mixed $value, \\DateTimeInterface|\\DateInterval|int|null $ttl = null)
 * @method static int|bool increment(string $key, mixed $value = 1)
 * @method static int|bool decrement(string $key, mixed $value = 1)
 * @method static bool forever(string $key, mixed $value)
 * @method static mixed remember(string $key, \\Closure|\\DateTimeInterface|\\DateInterval|int|null $ttl, \\Closure $callback)
 * @method static mixed sear(string $key, \\Closure $callback)
 * @method static mixed rememberForever(string $key, \\Closure $callback)
 * @method static mixed flexible(string $key, array $ttl, callable $callback, array|null $lock = null)
 * @method static bool forget(string $key)
 * @method static bool delete(string $key)
 * @method static bool deleteMultiple(iterable $keys)
 * @method static bool clear()
 * @method static \\Illuminate\\Cache\\TaggedCache tags(array|mixed $names)
 * @method static string|null getName()
 * @method static bool supportsTags()
 * @method static int|null getDefaultCacheTime()
 * @method static \\Illuminate\\Cache\\Repository setDefaultCacheTime(int|null $seconds)
 * @method static \\Illuminate\\Contracts\\Cache\\Store getStore()
 * @method static \\Illuminate\\Cache\\Repository setStore(\\Illuminate\\Contracts\\Cache\\Store $store)
 * @method static \\Illuminate\\Contracts\\Events\\Dispatcher|null getEventDispatcher()
 * @method static void setEventDispatcher(\\Illuminate\\Contracts\\Events\\Dispatcher $events)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 * @method static bool flush()
 * @method static string getPrefix()
 * @method static \\Illuminate\\Contracts\\Cache\\Lock lock(string $name, int $seconds = 0, string|null $owner = null)
 * @method static \\Illuminate\\Contracts\\Cache\\Lock restoreLock(string $name, string $owner)
 *
 * @see \\Illuminate\\Cache\\CacheManager
 * @see \\Illuminate\\Cache\\Repository
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 62,
    'endLine' => 73,
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
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Support\\Facades',
        'declaringClassName' => 'Illuminate\\Support\\Facades\\Cache',
        'implementingClassName' => 'Illuminate\\Support\\Facades\\Cache',
        'currentClassName' => 'Illuminate\\Support\\Facades\\Cache',
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