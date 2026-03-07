<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Queue/InteractsWithUniqueJobs.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Queue\InteractsWithUniqueJobs
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-28d1f1ae89927e732785059a3a4c7654be3d37f8baa2b2be7dedc0491e0dea65-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Queue/InteractsWithUniqueJobs.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation\\Queue',
    'name' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
    'shortName' => 'InteractsWithUniqueJobs',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 55,
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
      'addUniqueJobInformationToContext' => 
      array (
        'name' => 'addUniqueJobInformationToContext',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 54,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Store unique job information in the context in case we can\'t resolve the job on the queue side.
 *
 * @param  mixed  $job
 * @return void
 */',
        'startLine' => 17,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Queue',
        'declaringClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'implementingClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'currentClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'aliasName' => NULL,
      ),
      'removeUniqueJobInformationFromContext' => 
      array (
        'name' => 'removeUniqueJobInformationFromContext',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 59,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove the unique job information from the context.
 *
 * @param  mixed  $job
 * @return void
 */',
        'startLine' => 33,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation\\Queue',
        'declaringClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'implementingClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'currentClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'aliasName' => NULL,
      ),
      'getUniqueJobCacheStore' => 
      array (
        'name' => 'getUniqueJobCacheStore',
        'parameters' => 
        array (
          'job' => 
          array (
            'name' => 'job',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 49,
            'endLine' => 49,
            'startColumn' => 47,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine the cache store used by the unique job to acquire locks.
 *
 * @param  mixed  $job
 * @return string|null
 */',
        'startLine' => 49,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation\\Queue',
        'declaringClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'implementingClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
        'currentClassName' => 'Illuminate\\Foundation\\Queue\\InteractsWithUniqueJobs',
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