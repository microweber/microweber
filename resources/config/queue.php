array (
  'default' => 'sync',
  'connections' => 
  array (
    'sync' => 
    array (
      'driver' => 'sync',
    ),
    'beanstalkd' => 
    array (
      'driver' => 'beanstalkd',
      'host' => 'localhost',
      'queue' => 'default',
      'ttr' => 60,
    ),
    'sqs' => 
    array (
      'driver' => 'sqs',
      'key' => 'your-public-key',
      'secret' => 'your-secret-key',
      'queue' => 'your-queue-url',
      'region' => 'us-east-1',
    ),
    'iron' => 
    array (
      'driver' => 'iron',
      'host' => 'mq-aws-us-east-1.iron.io',
      'token' => 'your-token',
      'project' => 'your-project-id',
      'queue' => 'your-queue-name',
      'encrypt' => true,
    ),
    'redis' => 
    array (
      'driver' => 'redis',
      'queue' => 'default',
    ),
  ),
  'failed' => 
  array (
    'database' => 'mysql',
    'table' => 'failed_jobs',
  ),
)