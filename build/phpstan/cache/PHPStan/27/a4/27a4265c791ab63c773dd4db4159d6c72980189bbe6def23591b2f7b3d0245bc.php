<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Cache/CacheFileHandler/MemoryCacheFileHandler.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Cache\CacheFileHandler\MemoryCacheFileHandler
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-317605301a7dd284cf09f86fa3a24dc2f6223c8721bca6b20737de9d30d18d64',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Cache/CacheFileHandler/MemoryCacheFileHandler.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Cache\\CacheFileHandler',
    'name' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
    'shortName' => 'MemoryCacheFileHandler',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 51,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\CacheFileHandler',
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
      'cacheMemory' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'implementingClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'name' => 'cacheMemory',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'files\' => []]',
          'attributes' => 
          array (
            'startLine' => 7,
            'endLine' => 7,
            'startTokenPos' => 23,
            'startFilePos' => 143,
            'endTokenPos' => 28,
            'endFilePos' => 155,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 7,
        'endLine' => 7,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'readMetaAndLock' => 
      array (
        'name' => 'readMetaAndLock',
        'parameters' => 
        array (
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 10,
            'endLine' => 10,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'lock' => 
          array (
            'name' => 'lock',
            'default' => 
            array (
              'code' => 'LOCK_SH',
              'attributes' => 
              array (
                'startLine' => 10,
                'endLine' => 10,
                'startTokenPos' => 48,
                'startFilePos' => 264,
                'endTokenPos' => 48,
                'endFilePos' => 270,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 10,
            'endLine' => 10,
            'startColumn' => 44,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 10,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Cache\\CacheFileHandler',
        'declaringClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'implementingClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'currentClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'aliasName' => NULL,
      ),
      'writeToCache' => 
      array (
        'name' => 'writeToCache',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 34,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 47,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'dp' => 
          array (
            'name' => 'dp',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 23,
                'endLine' => 23,
                'startTokenPos' => 127,
                'startFilePos' => 618,
                'endTokenPos' => 128,
                'endFilePos' => 619,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 54,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Deletes and closes file.
 * @param  resource $handle
 */',
        'startLine' => 23,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Cache\\CacheFileHandler',
        'declaringClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'implementingClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'currentClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'aliasName' => NULL,
      ),
      'readData' => 
      array (
        'name' => 'readData',
        'parameters' => 
        array (
          'meta' => 
          array (
            'name' => 'meta',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'MicroweberPackages\\Cache\\CacheFileHandler',
        'declaringClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'implementingClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
        'currentClassName' => 'MicroweberPackages\\Cache\\CacheFileHandler\\MemoryCacheFileHandler',
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