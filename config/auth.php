<?php return array (
  'driver' => 'eloquent',
  'model' => 'App\\User',
  'table' => 'users',
  'password' => 
  array (
    'email' => 'emails.auth.password',
    'table' => 'password_resets',
    'expire' => 60,
  ),
);