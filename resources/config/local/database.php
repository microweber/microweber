<?php return array (
  'fetch' => 8,
  'default' => 'mysql',
  'connections' => 
  array (
    'sqlite' => 
    array (
      'driver' => 'sqlite',
      'database' => 'C:\\Users\\Boyan\\Code\\web\\laravel-develop/storage/database.sqlite',
      'prefix' => '',
    ),
    'mysql' => 
    array (
      'driver' => 'mysql',
      'host' => '192.168.0.101',
      'database' => 'tst5',
      'username' => '11',
      'password' => '11',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => 'bobafet_',
    ),
    'pgsql' => 
    array (
      'driver' => 'pgsql',
      'host' => 'localhost',
      'database' => 'forge',
      'username' => 'forge',
      'password' => '',
      'charset' => 'utf8',
      'prefix' => '',
      'schema' => 'public',
    ),
    'sqlsrv' => 
    array (
      'driver' => 'sqlsrv',
      'host' => 'localhost',
      'database' => 'database',
      'username' => 'root',
      'password' => '',
      'prefix' => '',
    ),
  ),
  'migrations' => 'migrations',
  'redis' => 
  array (
    'cluster' => false,
    'default' => 
    array (
      'host' => '127.0.0.1',
      'port' => 6379,
      'database' => 0,
    ),
  ),
);