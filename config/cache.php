<?php

return [

/*
|--------------------------------------------------------------------------
| Default Cache Store
|--------------------------------------------------------------------------
|
| This option controls the default cache connection that gets used while
| using this caching library. This connection is used when another is
| not explicitly specified when executing a given caching function.
|
| Supported: "array", "database", "file", "memcached", "redis", "dynamodb"
|
*/

'default' => env('CACHE_STORE') ?: env('CACHE_DRIVER') ?: 'file',

/*
|--------------------------------------------------------------------------
| Cache Stores
|--------------------------------------------------------------------------
|
| Here you may define all of the cache "stores" for your application as
| well as their drivers. You may even define multiple stores for the
| same cache driver to group types of items stored in your caches.
|
*/

'stores' => [

'array' => [
'driver' => 'array',
'serialize' => false,
],

'database' => [
'driver' => 'database',
'table' => env('DB_CACHE_TABLE', 'cache'),
'connection' => env('DB_CACHE_CONNECTION'),
'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
],

'file' => [
'driver' => 'file',
'path' => storage_path('framework/cache/data'),
'lock_path' => storage_path('framework/cache/data'),
],

'memcached' => [
'driver' => 'memcached',
'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
'sasl' => [
env('MEMCACHED_USERNAME'),
env('MEMCACHED_PASSWORD'),
],
'options' => [
// Memcached::OPT_CONNECT_TIMEOUT => 2000,
],
'servers' => [
[
'host' => env('MEMCACHED_HOST', '127.0.0.1'),
'port' => env('MEMCACHED_PORT', 11211),
'weight' => 100,
],
],
],

'redis' => [
'driver' => 'redis',
'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
],

'redis_session' => [
'driver' => 'redis',
'connection' => env('REDIS_SESSION_CONNECTION', 'session'),
],

'dynamodb' => [
'driver' => 'dynamodb',
'key' => env('AWS_ACCESS_KEY_ID'),
'secret' => env('AWS_SECRET_ACCESS_KEY'),
'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
'endpoint' => env('DYNAMODB_ENDPOINT'),
],

'octane' => [
'driver' => 'octane',
],

],

/*
|--------------------------------------------------------------------------
| Cache Key Prefix
|--------------------------------------------------------------------------
|
| When utilizing the APC, database, memcached, Redis, and DynamoDB cache
| stores, there might be other applications using the same cache. For
| that reason, you may prefix every cache key to avoid collisions.
|
*/

'prefix' => env('CACHE_PREFIX', \Illuminate\Support\Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

/*
|--------------------------------------------------------------------------
| Cache Time To Live (TTL)
|--------------------------------------------------------------------------
|
| The default cache TTL in seconds. This is used when no explicit TTL
| is provided. Set to null for no expiration.
|
*/

'ttl' => env('CACHE_TTL', 3600),

/*
|--------------------------------------------------------------------------
| Cache Lock Configuration
|--------------------------------------------------------------------------
|
| Cache locks are used to prevent concurrent execution of the same
| operation. These options control the default behavior of cache locks.
|
*/

'lock' => [
'prefix' => env('CACHE_LOCK_PREFIX', 'lock_'),
'ttl' => env('CACHE_LOCK_TTL', 60),
'retry_interval' => env('CACHE_LOCK_RETRY_INTERVAL', 100),
],

];
