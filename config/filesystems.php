<?php return array (
  'default' => 'local',
  'cloud' => 's3',
  'disks' => 
  array (
    'local' => 
    array (
      'driver' => 'local',
      'root' => '/home/ash/dev/web/mw3/storage/app',
    ),
    's3' => 
    array (
      'driver' => 's3',
      'key' => 'your-key',
      'secret' => 'your-secret',
      'bucket' => 'your-bucket',
    ),
    'rackspace' => 
    array (
      'driver' => 'rackspace',
      'username' => 'your-username',
      'key' => 'your-key',
      'container' => 'your-container',
      'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
      'region' => 'IAD',
    ),
  ),
);