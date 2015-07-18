<?php return array (
  'default' => 'file',
  'stores' => 
  array (
    'apc' => 
    array (
      'driver' => 'apc',
    ),
    'array' => 
    array (
      'driver' => 'array',
    ),
    'database' => 
    array (
      'driver' => 'database',
      'table' => 'cache',
      'connection' => NULL,
    ),
    'file' => 
    array (
      'driver' => 'file',
      'path' => '/home/ash/dev/web/mw3/storage/framework/cache',
    ),
    'memcached' => 
    array (
      'driver' => 'memcached',
      'servers' => 
      array (
        0 => 
        array (
          'host' => '127.0.0.1',
          'port' => 11211,
          'weight' => 100,
        ),
      ),
    ),
    'redis' => 
    array (
      'driver' => 'redis',
      'connection' => 'default',
    ),
  ),
  'prefix' => 'laravel',
);