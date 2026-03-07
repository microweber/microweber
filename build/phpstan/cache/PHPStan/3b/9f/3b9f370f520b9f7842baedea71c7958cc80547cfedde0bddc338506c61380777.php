<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Queue/Job.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Queue\Job
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-345852a087186cc33cdd53887b34654ec68bc1283f6a017c4303f5187bbc440c-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Queue\\Job',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Queue/Job.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Queue',
    'name' => 'Illuminate\\Contracts\\Queue\\Job',
    'shortName' => 'Job',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 164,
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
      'uuid' => 
      array (
        'name' => 'uuid',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the UUID of the job.
 *
 * @return string|null
 */',
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 27,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'getJobId' => 
      array (
        'name' => 'getJobId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the job identifier.
 *
 * @return string
 */',
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'payload' => 
      array (
        'name' => 'payload',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the decoded body of the job.
 *
 * @return array
 */',
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'fire' => 
      array (
        'name' => 'fire',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Fire the job.
 *
 * @return void
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 27,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'release' => 
      array (
        'name' => 'release',
        'parameters' => 
        array (
          'delay' => 
          array (
            'name' => 'delay',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 41,
                'endLine' => 41,
                'startTokenPos' => 69,
                'startFilePos' => 660,
                'endTokenPos' => 69,
                'endFilePos' => 660,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 29,
            'endColumn' => 38,
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
 * Release the job back into the queue after (n) seconds.
 *
 * @param  int  $delay
 * @return void
 */',
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'isReleased' => 
      array (
        'name' => 'isReleased',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the job was released back into the queue.
 *
 * @return bool
 */',
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete the job from the queue.
 *
 * @return void
 */',
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'isDeleted' => 
      array (
        'name' => 'isDeleted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the job has been deleted.
 *
 * @return bool
 */',
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'isDeletedOrReleased' => 
      array (
        'name' => 'isDeletedOrReleased',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the job has been deleted or released.
 *
 * @return bool
 */',
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'attempts' => 
      array (
        'name' => 'attempts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of times the job has been attempted.
 *
 * @return int
 */',
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'hasFailed' => 
      array (
        'name' => 'hasFailed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the job has been marked as a failure.
 *
 * @return bool
 */',
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'markAsFailed' => 
      array (
        'name' => 'markAsFailed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Mark the job as "failed".
 *
 * @return void
 */',
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'fail' => 
      array (
        'name' => 'fail',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 98,
                'endLine' => 98,
                'startTokenPos' => 162,
                'startFilePos' => 1756,
                'endTokenPos' => 162,
                'endFilePos' => 1759,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 26,
            'endColumn' => 34,
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
 * Delete the job, call the "failed" method, and raise the failed job event.
 *
 * @param  \\Throwable|null  $e
 * @return void
 */',
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'maxTries' => 
      array (
        'name' => 'maxTries',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of times to attempt a job.
 *
 * @return int|null
 */',
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'maxExceptions' => 
      array (
        'name' => 'maxExceptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the maximum number of exceptions allowed, regardless of attempts.
 *
 * @return int|null
 */',
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'timeout' => 
      array (
        'name' => 'timeout',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of seconds the job can run.
 *
 * @return int|null
 */',
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'retryUntil' => 
      array (
        'name' => 'retryUntil',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the timestamp indicating when the job should timeout.
 *
 * @return int|null
 */',
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'getName' => 
      array (
        'name' => 'getName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the queued job class.
 *
 * @return string
 */',
        'startLine' => 133,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'resolveName' => 
      array (
        'name' => 'resolveName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the resolved name of the queued job class.
 *
 * Resolves the name of "wrapped" jobs such as class-based handlers.
 *
 * @return string
 */',
        'startLine' => 142,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'getConnectionName' => 
      array (
        'name' => 'getConnectionName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the connection the job belongs to.
 *
 * @return string
 */',
        'startLine' => 149,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'getQueue' => 
      array (
        'name' => 'getQueue',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the queue the job belongs to.
 *
 * @return string
 */',
        'startLine' => 156,
        'endLine' => 156,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'aliasName' => NULL,
      ),
      'getRawBody' => 
      array (
        'name' => 'getRawBody',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the raw body string for the job.
 *
 * @return string
 */',
        'startLine' => 163,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Queue',
        'declaringClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'implementingClassName' => 'Illuminate\\Contracts\\Queue\\Job',
        'currentClassName' => 'Illuminate\\Contracts\\Queue\\Job',
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