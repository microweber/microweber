<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../doctrine/dbal/src/Connection.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Doctrine\DBAL\Connection
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f43ccc752c9c53bfed27033253cdc3d7b0aa34d35ae7ee3206984a9c66481316-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Doctrine\\DBAL\\Connection',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../doctrine/dbal/src/Connection.php',
      ),
    ),
    'namespace' => 'Doctrine\\DBAL',
    'name' => 'Doctrine\\DBAL\\Connection',
    'shortName' => 'Connection',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A database abstraction-level connection that implements features like transaction isolation levels,
 * configuration, emulated transaction nesting, lazy connecting and more.
 *
 * @phpstan-import-type Params from DriverManager
 * @phpstan-type WrapperParameterType = string|Type|ParameterType|ArrayParameterType
 * @phpstan-type WrapperParameterTypeArray = array<
 *    int<0, max>,
 *    WrapperParameterType>|array<string, WrapperParameterType
 *  >
 * @phpstan-consistent-constructor
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 63,
    'endLine' => 1422,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Doctrine\\DBAL\\ServerVersionProvider',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      '_conn' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => '_conn',
        'modifiers' => 2,
        'type' => 
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
                  'name' => 'Doctrine\\DBAL\\Driver\\Connection',
                  'isIdentifier' => false,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 68,
            'endLine' => 68,
            'startTokenPos' => 280,
            'startFilePos' => 2350,
            'endTokenPos' => 280,
            'endFilePos' => 2353,
          ),
        ),
        'docComment' => '/**
 * The wrapped driver connection.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_config' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => '_config',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Configuration',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'autoCommit' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'autoCommit',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 75,
            'endLine' => 75,
            'startTokenPos' => 300,
            'startFilePos' => 2499,
            'endTokenPos' => 300,
            'endFilePos' => 2502,
          ),
        ),
        'docComment' => '/**
 * The current auto-commit mode of this connection.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 75,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'transactionNestingLevel' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'transactionNestingLevel',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 80,
            'endLine' => 80,
            'startTokenPos' => 313,
            'startFilePos' => 2603,
            'endTokenPos' => 313,
            'endFilePos' => 2603,
          ),
        ),
        'docComment' => '/**
 * The transaction nesting level.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 80,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'transactionIsolationLevel' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'transactionIsolationLevel',
        'modifiers' => 4,
        'type' => 
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
                  'name' => 'Doctrine\\DBAL\\TransactionIsolationLevel',
                  'isIdentifier' => false,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 85,
            'endLine' => 85,
            'startTokenPos' => 327,
            'startFilePos' => 2786,
            'endTokenPos' => 327,
            'endFilePos' => 2789,
          ),
        ),
        'docComment' => '/**
 * The currently active transaction isolation level or NULL before it has been determined.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 73,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'params' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'params',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The parameters used during creation of the Connection instance.
 *
 * @var array<string,mixed>
 * @phpstan-var Params
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'platform' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'platform',
        'modifiers' => 4,
        'type' => 
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
                  'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
                  'isIdentifier' => false,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 350,
            'startFilePos' => 3124,
            'endTokenPos' => 350,
            'endFilePos' => 3127,
          ),
        ),
        'docComment' => '/**
 * The database platform object used by the connection or NULL before it\'s initialized.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 47,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exceptionConverter' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'exceptionConverter',
        'modifiers' => 4,
        'type' => 
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
                  'name' => 'Doctrine\\DBAL\\Driver\\API\\ExceptionConverter',
                  'isIdentifier' => false,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 362,
            'startFilePos' => 3185,
            'endTokenPos' => 362,
            'endFilePos' => 3188,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 59,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'parser' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'parser',
        'modifiers' => 4,
        'type' => 
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
                  'name' => 'Doctrine\\DBAL\\SQL\\Parser',
                  'isIdentifier' => false,
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
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 101,
            'endLine' => 101,
            'startTokenPos' => 374,
            'startFilePos' => 3245,
            'endTokenPos' => 374,
            'endFilePos' => 3248,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 101,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 59,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isRollbackOnly' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'isRollbackOnly',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 106,
            'endLine' => 106,
            'startTokenPos' => 387,
            'startFilePos' => 3391,
            'endTokenPos' => 387,
            'endFilePos' => 3395,
          ),
        ),
        'docComment' => '/**
 * Flag that indicates whether the current transaction is marked for rollback only.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'schemaManagerFactory' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'schemaManagerFactory',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Schema\\SchemaManagerFactory',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 55,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'driver' => 
      array (
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'name' => 'driver',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Driver',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 123,
        'endLine' => 123,
        'startColumn' => 9,
        'endColumn' => 32,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 121,
            'endLine' => 122,
            'startColumn' => 9,
            'endColumn' => 21,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'driver' => 
          array (
            'name' => 'driver',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Driver',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 9,
            'endColumn' => 32,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'config' => 
          array (
            'name' => 'config',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 124,
                'endLine' => 124,
                'startTokenPos' => 429,
                'startFilePos' => 4019,
                'endTokenPos' => 429,
                'endFilePos' => 4022,
              ),
            ),
            'type' => 
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
                      'name' => 'Doctrine\\DBAL\\Configuration',
                      'isIdentifier' => false,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 9,
            'endColumn' => 37,
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
 * Initializes a new instance of the Connection class.
 *
 * @internal The connection can be only instantiated by the driver manager.
 *
 * @param array<string, mixed> $params The connection parameters.
 * @param Driver               $driver The driver to use.
 * @param Configuration|null   $config The configuration, optional.
 * @phpstan-param Params $params
 */',
        'startLine' => 120,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getParams' => 
      array (
        'name' => 'getParams',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the parameters used during instantiation.
 *
 * @internal
 *
 * @return array<string,mixed>
 * @phpstan-return Params
 */',
        'startLine' => 142,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getDatabase' => 
      array (
        'name' => 'getDatabase',
        'parameters' => 
        array (
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
 * Gets the name of the currently selected database.
 *
 * @return string|null The name of the database or NULL if a database is not selected.
 *                     The platforms which don\'t support the concept of a database (e.g. embedded databases)
 *                     must always return a string as an indicator of an implicitly selected database.
 *
 * @throws Exception
 */',
        'startLine' => 156,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getDriver' => 
      array (
        'name' => 'getDriver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Driver',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the DBAL driver instance.
 */',
        'startLine' => 170,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getConfiguration' => 
      array (
        'name' => 'getConfiguration',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Configuration',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the Configuration used by the Connection.
 */',
        'startLine' => 178,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getDatabasePlatform' => 
      array (
        'name' => 'getDatabasePlatform',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Platforms\\AbstractPlatform',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the DatabasePlatform for the connection.
 *
 * @throws Exception
 */',
        'startLine' => 188,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'createExpressionBuilder' => 
      array (
        'name' => 'createExpressionBuilder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Query\\Expression\\ExpressionBuilder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates an expression builder for the connection.
 */',
        'startLine' => 208,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'connect' => 
      array (
        'name' => 'connect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Driver\\Connection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Establishes the connection with the database and returns the underlying connection.
 *
 * @throws Exception
 */',
        'startLine' => 218,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getServerVersion' => 
      array (
        'name' => 'getServerVersion',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * {@inheritDoc}
 *
 * @throws Exception
 */',
        'startLine' => 242,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'isAutoCommit' => 
      array (
        'name' => 'isAutoCommit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the current auto-commit mode for this connection.
 *
 * @see    setAutoCommit
 *
 * @return bool True if auto-commit mode is currently enabled for this connection, false otherwise.
 */',
        'startLine' => 254,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'setAutoCommit' => 
      array (
        'name' => 'setAutoCommit',
        'parameters' => 
        array (
          'autoCommit' => 
          array (
            'name' => 'autoCommit',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 274,
            'endLine' => 274,
            'startColumn' => 35,
            'endColumn' => 50,
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
 * Sets auto-commit mode for this connection.
 *
 * If a connection is in auto-commit mode, then all its SQL statements will be executed and committed as individual
 * transactions. Otherwise, its SQL statements are grouped into transactions that are terminated by a call to either
 * the method commit or the method rollback. By default, new connections are in auto-commit mode.
 *
 * NOTE: If this method is called during a transaction and the auto-commit mode is changed, the transaction is
 * committed. If this method is called and the auto-commit mode is not changed, the call is a no-op.
 *
 * @see isAutoCommit
 *
 * @throws ConnectionException
 * @throws DriverException
 */',
        'startLine' => 274,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchAssociative' => 
      array (
        'name' => 'fetchAssociative',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 302,
            'endLine' => 302,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 302,
                'endLine' => 302,
                'startTokenPos' => 1098,
                'startFilePos' => 9306,
                'endTokenPos' => 1099,
                'endFilePos' => 9307,
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
            'startLine' => 302,
            'endLine' => 302,
            'startColumn' => 53,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 302,
                'endLine' => 302,
                'startTokenPos' => 1108,
                'startFilePos' => 9325,
                'endTokenPos' => 1109,
                'endFilePos' => 9326,
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
            'startLine' => 302,
            'endLine' => 302,
            'startColumn' => 73,
            'endColumn' => 89,
            'parameterIndex' => 2,
            'isOptional' => true,
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
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
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
 * Prepares and executes an SQL query and returns the first row of the result
 * as an associative array.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return array<string, mixed>|false False is returned if no rows are found.
 *
 * @throws Exception
 */',
        'startLine' => 302,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchNumeric' => 
      array (
        'name' => 'fetchNumeric',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 318,
                'endLine' => 318,
                'startTokenPos' => 1160,
                'startFilePos' => 9866,
                'endTokenPos' => 1161,
                'endFilePos' => 9867,
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 49,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 318,
                'endLine' => 318,
                'startTokenPos' => 1170,
                'startFilePos' => 9885,
                'endTokenPos' => 1171,
                'endFilePos' => 9886,
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 69,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
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
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
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
 * Prepares and executes an SQL query and returns the first row of the result
 * as a numerically indexed array.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return list<mixed>|false False is returned if no rows are found.
 *
 * @throws Exception
 */',
        'startLine' => 318,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchOne' => 
      array (
        'name' => 'fetchOne',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 30,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 334,
                'endLine' => 334,
                'startTokenPos' => 1222,
                'startFilePos' => 10413,
                'endTokenPos' => 1223,
                'endFilePos' => 10414,
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
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 45,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 334,
                'endLine' => 334,
                'startTokenPos' => 1232,
                'startFilePos' => 10432,
                'endTokenPos' => 1233,
                'endFilePos' => 10433,
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
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 65,
            'endColumn' => 81,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the value of a single column
 * of the first row of the result.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return mixed|false False is returned if no rows are found.
 *
 * @throws Exception
 */',
        'startLine' => 334,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'isConnected' => 
      array (
        'name' => 'isConnected',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Whether an actual connection to the database is established.
 *
 * @phpstan-assert-if-true !null $this->_conn
 */',
        'startLine' => 344,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'isTransactionActive' => 
      array (
        'name' => 'isTransactionActive',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks whether a transaction is currently active.
 *
 * @return bool TRUE if a transaction is currently active, FALSE otherwise.
 */',
        'startLine' => 354,
        'endLine' => 357,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getCriteriaCondition' => 
      array (
        'name' => 'getCriteriaCondition',
        'parameters' => 
        array (
          'criteria' => 
          array (
            'name' => 'criteria',
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
            'startLine' => 366,
            'endLine' => 366,
            'startColumn' => 43,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds condition based on the criteria to the query components
 *
 * @param array<string, mixed> $criteria Map of key columns to their values
 *
 * @return array{list<string>, list<mixed>, list<string>}
 */',
        'startLine' => 366,
        'endLine' => 382,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
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
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 28,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'criteria' => 
          array (
            'name' => 'criteria',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 396,
                'endLine' => 396,
                'startTokenPos' => 1466,
                'startFilePos' => 12322,
                'endTokenPos' => 1467,
                'endFilePos' => 12323,
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
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 43,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 396,
                'endLine' => 396,
                'startTokenPos' => 1476,
                'startFilePos' => 12341,
                'endTokenPos' => 1477,
                'endFilePos' => 12342,
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
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 65,
            'endColumn' => 81,
            'parameterIndex' => 2,
            'isOptional' => true,
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
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
 * Executes an SQL DELETE statement on a table.
 *
 * Table expression and columns are not escaped and are not safe for user-input.
 *
 * @param array<string, mixed>                                                                  $criteria
 * @param array<int<0,max>, string|ParameterType|Type>|array<string, string|ParameterType|Type> $types
 *
 * @return int|numeric-string The number of affected rows.
 *
 * @throws Exception
 */',
        'startLine' => 396,
        'endLine' => 411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'close' => 
      array (
        'name' => 'close',
        'parameters' => 
        array (
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
 * Closes the connection.
 */',
        'startLine' => 416,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'setTransactionIsolation' => 
      array (
        'name' => 'setTransactionIsolation',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\TransactionIsolationLevel',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 429,
            'endLine' => 429,
            'startColumn' => 45,
            'endColumn' => 76,
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
 * Sets the transaction isolation level.
 *
 * @param TransactionIsolationLevel $level The level to set.
 *
 * @throws Exception
 */',
        'startLine' => 429,
        'endLine' => 434,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getTransactionIsolation' => 
      array (
        'name' => 'getTransactionIsolation',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\TransactionIsolationLevel',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the currently active transaction isolation level.
 *
 * @return TransactionIsolationLevel The current transaction isolation level.
 *
 * @throws Exception
 */',
        'startLine' => 443,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
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
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 28,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 43,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'criteria' => 
          array (
            'name' => 'criteria',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 461,
                'endLine' => 461,
                'startTokenPos' => 1734,
                'startFilePos' => 14454,
                'endTokenPos' => 1735,
                'endFilePos' => 14455,
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
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 56,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 461,
                'endLine' => 461,
                'startTokenPos' => 1744,
                'startFilePos' => 14473,
                'endTokenPos' => 1745,
                'endFilePos' => 14474,
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
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 78,
            'endColumn' => 94,
            'parameterIndex' => 3,
            'isOptional' => true,
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
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
 * Executes an SQL UPDATE statement on a table.
 *
 * Table expression and columns are not escaped and are not safe for user-input.
 *
 * @param array<string, mixed>                                                                  $data
 * @param array<string, mixed>                                                                  $criteria
 * @param array<int<0,max>, string|ParameterType|Type>|array<string, string|ParameterType|Type> $types
 *
 * @return int|numeric-string The number of affected rows.
 *
 * @throws Exception
 */',
        'startLine' => 461,
        'endLine' => 488,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'insert' => 
      array (
        'name' => 'insert',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
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
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 28,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
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
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 43,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 502,
                'endLine' => 502,
                'startTokenPos' => 2013,
                'startFilePos' => 15940,
                'endTokenPos' => 2014,
                'endFilePos' => 15941,
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
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 56,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => true,
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
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
 * Inserts a table row with specified data.
 *
 * Table expression and columns are not escaped and are not safe for user-input.
 *
 * @param array<string, mixed>                                                                  $data
 * @param array<int<0,max>, string|ParameterType|Type>|array<string, string|ParameterType|Type> $types
 *
 * @return int|numeric-string The number of affected rows.
 *
 * @throws Exception
 */',
        'startLine' => 502,
        'endLine' => 524,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'extractTypeValues' => 
      array (
        'name' => 'extractTypeValues',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 534,
            'endLine' => 534,
            'startColumn' => 40,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
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
            'startLine' => 534,
            'endLine' => 534,
            'startColumn' => 56,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Extract ordered type list from an ordered column list and type map.
 *
 * @param array<int, string>                                                             $columns
 * @param array<int, string|ParameterType|Type>|array<string, string|ParameterType|Type> $types
 *
 * @return array<int<0, max>, string|ParameterType|Type>
 */',
        'startLine' => 534,
        'endLine' => 543,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'quoteIdentifier' => 
      array (
        'name' => 'quoteIdentifier',
        'parameters' => 
        array (
          'identifier' => 
          array (
            'name' => 'identifier',
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
            'startLine' => 559,
            'endLine' => 559,
            'startColumn' => 37,
            'endColumn' => 54,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Quotes a string so it can be safely used as a table or column name, even if
 * it is a reserved name.
 *
 * Delimiting style depends on the underlying database platform that is being used.
 *
 * NOTE: Just because you CAN use quoted identifiers does not mean
 * you SHOULD use them. In general, they end up causing way more
 * problems than they solve.
 *
 * @param string $identifier The identifier to be quoted.
 *
 * @return string The quoted identifier.
 */',
        'startLine' => 559,
        'endLine' => 562,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'quote' => 
      array (
        'name' => 'quote',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 568,
            'endLine' => 568,
            'startColumn' => 27,
            'endColumn' => 39,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The usage of this method is discouraged. Use prepared statements
 * or {@see AbstractPlatform::quoteStringLiteral()} instead.
 */',
        'startLine' => 568,
        'endLine' => 571,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchAllNumeric' => 
      array (
        'name' => 'fetchAllNumeric',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 583,
            'endLine' => 583,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 583,
                'endLine' => 583,
                'startTokenPos' => 2368,
                'startFilePos' => 18575,
                'endTokenPos' => 2369,
                'endFilePos' => 18576,
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
            'startLine' => 583,
            'endLine' => 583,
            'startColumn' => 52,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 583,
                'endLine' => 583,
                'startTokenPos' => 2378,
                'startFilePos' => 18594,
                'endTokenPos' => 2379,
                'endFilePos' => 18595,
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
            'startLine' => 583,
            'endLine' => 583,
            'startColumn' => 72,
            'endColumn' => 88,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an array of numeric arrays.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return list<list<mixed>>
 *
 * @throws Exception
 */',
        'startLine' => 583,
        'endLine' => 586,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchAllAssociative' => 
      array (
        'name' => 'fetchAllAssociative',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 598,
            'endLine' => 598,
            'startColumn' => 41,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 598,
                'endLine' => 598,
                'startTokenPos' => 2428,
                'startFilePos' => 19082,
                'endTokenPos' => 2429,
                'endFilePos' => 19083,
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
            'startLine' => 598,
            'endLine' => 598,
            'startColumn' => 56,
            'endColumn' => 73,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 598,
                'endLine' => 598,
                'startTokenPos' => 2438,
                'startFilePos' => 19101,
                'endTokenPos' => 2439,
                'endFilePos' => 19102,
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
            'startLine' => 598,
            'endLine' => 598,
            'startColumn' => 76,
            'endColumn' => 92,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an array of associative arrays.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return list<array<string,mixed>>
 *
 * @throws Exception
 */',
        'startLine' => 598,
        'endLine' => 601,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchAllKeyValue' => 
      array (
        'name' => 'fetchAllKeyValue',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 614,
                'endLine' => 614,
                'startTokenPos' => 2488,
                'startFilePos' => 19664,
                'endTokenPos' => 2489,
                'endFilePos' => 19665,
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
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 53,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 614,
                'endLine' => 614,
                'startTokenPos' => 2498,
                'startFilePos' => 19683,
                'endTokenPos' => 2499,
                'endFilePos' => 19684,
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
            'startLine' => 614,
            'endLine' => 614,
            'startColumn' => 73,
            'endColumn' => 89,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an associative array with the keys
 * mapped to the first column and the values mapped to the second column.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return array<mixed,mixed>
 *
 * @throws Exception
 */',
        'startLine' => 614,
        'endLine' => 617,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchAllAssociativeIndexed' => 
      array (
        'name' => 'fetchAllAssociativeIndexed',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 631,
            'endLine' => 631,
            'startColumn' => 48,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 631,
                'endLine' => 631,
                'startTokenPos' => 2548,
                'startFilePos' => 20327,
                'endTokenPos' => 2549,
                'endFilePos' => 20328,
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
            'startLine' => 631,
            'endLine' => 631,
            'startColumn' => 63,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 631,
                'endLine' => 631,
                'startTokenPos' => 2558,
                'startFilePos' => 20346,
                'endTokenPos' => 2559,
                'endFilePos' => 20347,
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
            'startLine' => 631,
            'endLine' => 631,
            'startColumn' => 83,
            'endColumn' => 99,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an associative array with the keys mapped
 * to the first column and the values being an associative array representing the rest of the columns
 * and their values.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return array<mixed,array<string,mixed>>
 *
 * @throws Exception
 */',
        'startLine' => 631,
        'endLine' => 634,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'fetchFirstColumn' => 
      array (
        'name' => 'fetchFirstColumn',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 646,
            'endLine' => 646,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 646,
                'endLine' => 646,
                'startTokenPos' => 2608,
                'startFilePos' => 20833,
                'endTokenPos' => 2609,
                'endFilePos' => 20834,
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
            'startLine' => 646,
            'endLine' => 646,
            'startColumn' => 53,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 646,
                'endLine' => 646,
                'startTokenPos' => 2618,
                'startFilePos' => 20852,
                'endTokenPos' => 2619,
                'endFilePos' => 20853,
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
            'startLine' => 646,
            'endLine' => 646,
            'startColumn' => 73,
            'endColumn' => 89,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an array of the first column values.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return list<mixed>
 *
 * @throws Exception
 */',
        'startLine' => 646,
        'endLine' => 649,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'iterateNumeric' => 
      array (
        'name' => 'iterateNumeric',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 661,
            'endLine' => 661,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 661,
                'endLine' => 661,
                'startTokenPos' => 2668,
                'startFilePos' => 21360,
                'endTokenPos' => 2669,
                'endFilePos' => 21361,
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
            'startLine' => 661,
            'endLine' => 661,
            'startColumn' => 51,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 661,
                'endLine' => 661,
                'startTokenPos' => 2678,
                'startFilePos' => 21379,
                'endTokenPos' => 2679,
                'endFilePos' => 21380,
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
            'startLine' => 661,
            'endLine' => 661,
            'startColumn' => 71,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an iterator over rows represented as numeric arrays.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return Traversable<int,list<mixed>>
 *
 * @throws Exception
 */',
        'startLine' => 661,
        'endLine' => 664,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'iterateAssociative' => 
      array (
        'name' => 'iterateAssociative',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 677,
            'endLine' => 677,
            'startColumn' => 40,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 677,
                'endLine' => 677,
                'startTokenPos' => 2728,
                'startFilePos' => 21914,
                'endTokenPos' => 2729,
                'endFilePos' => 21915,
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
            'startLine' => 677,
            'endLine' => 677,
            'startColumn' => 55,
            'endColumn' => 72,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 677,
                'endLine' => 677,
                'startTokenPos' => 2738,
                'startFilePos' => 21933,
                'endTokenPos' => 2739,
                'endFilePos' => 21934,
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
            'startLine' => 677,
            'endLine' => 677,
            'startColumn' => 75,
            'endColumn' => 91,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an iterator over rows represented
 * as associative arrays.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return Traversable<int,array<string,mixed>>
 *
 * @throws Exception
 */',
        'startLine' => 677,
        'endLine' => 680,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'iterateKeyValue' => 
      array (
        'name' => 'iterateKeyValue',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 693,
            'endLine' => 693,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 693,
                'endLine' => 693,
                'startTokenPos' => 2788,
                'startFilePos' => 22497,
                'endTokenPos' => 2789,
                'endFilePos' => 22498,
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
            'startLine' => 693,
            'endLine' => 693,
            'startColumn' => 52,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 693,
                'endLine' => 693,
                'startTokenPos' => 2798,
                'startFilePos' => 22516,
                'endTokenPos' => 2799,
                'endFilePos' => 22517,
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
            'startLine' => 693,
            'endLine' => 693,
            'startColumn' => 72,
            'endColumn' => 88,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an iterator with the keys
 * mapped to the first column and the values mapped to the second column.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return Traversable<mixed,mixed>
 *
 * @throws Exception
 */',
        'startLine' => 693,
        'endLine' => 696,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'iterateAssociativeIndexed' => 
      array (
        'name' => 'iterateAssociativeIndexed',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 47,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 710,
                'endLine' => 710,
                'startTokenPos' => 2848,
                'startFilePos' => 23161,
                'endTokenPos' => 2849,
                'endFilePos' => 23162,
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
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 62,
            'endColumn' => 79,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 710,
                'endLine' => 710,
                'startTokenPos' => 2858,
                'startFilePos' => 23180,
                'endTokenPos' => 2859,
                'endFilePos' => 23181,
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
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 82,
            'endColumn' => 98,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an iterator with the keys mapped
 * to the first column and the values being an associative array representing the rest of the columns
 * and their values.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return Traversable<mixed,array<string,mixed>>
 *
 * @throws Exception
 */',
        'startLine' => 710,
        'endLine' => 713,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'iterateColumn' => 
      array (
        'name' => 'iterateColumn',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
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
            'startLine' => 725,
            'endLine' => 725,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 725,
                'endLine' => 725,
                'startTokenPos' => 2908,
                'startFilePos' => 23685,
                'endTokenPos' => 2909,
                'endFilePos' => 23686,
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
            'startLine' => 725,
            'endLine' => 725,
            'startColumn' => 50,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 725,
                'endLine' => 725,
                'startTokenPos' => 2918,
                'startFilePos' => 23704,
                'endTokenPos' => 2919,
                'endFilePos' => 23705,
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
            'startLine' => 725,
            'endLine' => 725,
            'startColumn' => 70,
            'endColumn' => 86,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares and executes an SQL query and returns the result as an iterator over the first column values.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return Traversable<int,mixed>
 *
 * @throws Exception
 */',
        'startLine' => 725,
        'endLine' => 728,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'prepare' => 
      array (
        'name' => 'prepare',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
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
            'startLine' => 737,
            'endLine' => 737,
            'startColumn' => 29,
            'endColumn' => 39,
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
            'name' => 'Doctrine\\DBAL\\Statement',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepares an SQL statement.
 *
 * @param string $sql The SQL statement to prepare.
 *
 * @throws Exception
 */',
        'startLine' => 737,
        'endLine' => 748,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'executeQuery' => 
      array (
        'name' => 'executeQuery',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
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
            'startLine' => 761,
            'endLine' => 761,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 762,
                'endLine' => 762,
                'startTokenPos' => 3059,
                'startFilePos' => 24675,
                'endTokenPos' => 3060,
                'endFilePos' => 24676,
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
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 763,
                'endLine' => 763,
                'startTokenPos' => 3069,
                'startFilePos' => 24702,
                'endTokenPos' => 3070,
                'endFilePos' => 24703,
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
            'startLine' => 763,
            'endLine' => 763,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'qcp' => 
          array (
            'name' => 'qcp',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 764,
                'endLine' => 764,
                'startTokenPos' => 3080,
                'startFilePos' => 24740,
                'endTokenPos' => 3080,
                'endFilePos' => 24743,
              ),
            ),
            'type' => 
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
                      'name' => 'Doctrine\\DBAL\\Cache\\QueryCacheProfile',
                      'isIdentifier' => false,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 764,
            'endLine' => 764,
            'startColumn' => 9,
            'endColumn' => 38,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Result',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Executes an, optionally parameterized, SQL query.
 *
 * If the query is parametrized, a prepared statement is used.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @throws Exception
 */',
        'startLine' => 760,
        'endLine' => 789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'executeCacheQuery' => 
      array (
        'name' => 'executeCacheQuery',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
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
            'startLine' => 800,
            'endLine' => 800,
            'startColumn' => 39,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
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
            'startLine' => 800,
            'endLine' => 800,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
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
            'startLine' => 800,
            'endLine' => 800,
            'startColumn' => 67,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'qcp' => 
          array (
            'name' => 'qcp',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Cache\\QueryCacheProfile',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 800,
            'endLine' => 800,
            'startColumn' => 81,
            'endColumn' => 102,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Result',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Executes a caching query.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @throws CacheException
 * @throws Exception
 */',
        'startLine' => 800,
        'endLine' => 849,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'executeStatement' => 
      array (
        'name' => 'executeStatement',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
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
            'startLine' => 870,
            'endLine' => 870,
            'startColumn' => 38,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 870,
                'endLine' => 870,
                'startTokenPos' => 3710,
                'startFilePos' => 27986,
                'endTokenPos' => 3711,
                'endFilePos' => 27987,
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
            'startLine' => 870,
            'endLine' => 870,
            'startColumn' => 51,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 870,
                'endLine' => 870,
                'startTokenPos' => 3720,
                'startFilePos' => 28005,
                'endTokenPos' => 3721,
                'endFilePos' => 28006,
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
            'startLine' => 870,
            'endLine' => 870,
            'startColumn' => 71,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => true,
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
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
 * Executes an SQL statement with the given parameters and returns the number of affected rows.
 *
 * Could be used for:
 *  - DML statements: INSERT, UPDATE, DELETE, etc.
 *  - DDL statements: CREATE, DROP, ALTER, etc.
 *  - DCL statements: GRANT, REVOKE, etc.
 *  - Session control statements: ALTER SESSION, SET, DECLARE, etc.
 *  - Other statements that don\'t yield a row set.
 *
 * This method supports PDO binding types as well as DBAL mapping types.
 *
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return int|numeric-string
 *
 * @throws Exception
 */',
        'startLine' => 870,
        'endLine' => 890,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getTransactionNestingLevel' => 
      array (
        'name' => 'getTransactionNestingLevel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the current transaction nesting level.
 *
 * @return int The nesting level. A value of 0 means there\'s no active transaction.
 */',
        'startLine' => 897,
        'endLine' => 900,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'lastInsertId' => 
      array (
        'name' => 'lastInsertId',
        'parameters' => 
        array (
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
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
 * Returns the ID of the last inserted row.
 *
 * If the underlying driver does not support identity columns, an exception is thrown.
 *
 * @throws Exception
 */',
        'startLine' => 909,
        'endLine' => 916,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'transactional' => 
      array (
        'name' => 'transactional',
        'parameters' => 
        array (
          'func' => 
          array (
            'name' => 'func',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 934,
            'endLine' => 934,
            'startColumn' => 35,
            'endColumn' => 47,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Executes a function in a transaction.
 *
 * The function gets passed this Connection instance as an (optional) parameter.
 *
 * If an exception occurs during execution of the function or transaction commit,
 * the transaction is rolled back and the exception re-thrown.
 *
 * @param Closure(self):T $func The function to execute transactionally.
 *
 * @return T The value returned by $func
 *
 * @throws Throwable
 *
 * @template T
 */',
        'startLine' => 934,
        'endLine' => 971,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'setNestTransactionsWithSavepoints' => 
      array (
        'name' => 'setNestTransactionsWithSavepoints',
        'parameters' => 
        array (
          'nestTransactionsWithSavepoints' => 
          array (
            'name' => 'nestTransactionsWithSavepoints',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 980,
            'endLine' => 980,
            'startColumn' => 55,
            'endColumn' => 90,
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
 * Sets if nested transactions should use savepoints.
 *
 * @deprecated No replacement planned
 *
 * @throws Exception
 */',
        'startLine' => 980,
        'endLine' => 995,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getNestTransactionsWithSavepoints' => 
      array (
        'name' => 'getNestTransactionsWithSavepoints',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets if nested transactions should use savepoints.
 *
 * @deprecated No replacement planned
 */',
        'startLine' => 1002,
        'endLine' => 1012,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      '_getNestedTransactionSavePointName' => 
      array (
        'name' => '_getNestedTransactionSavePointName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the savepoint name to use for nested transactions.
 */',
        'startLine' => 1017,
        'endLine' => 1020,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'beginTransaction' => 
      array (
        'name' => 'beginTransaction',
        'parameters' => 
        array (
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
        'docComment' => '/** @throws Exception */',
        'startLine' => 1023,
        'endLine' => 1034,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'commit' => 
      array (
        'name' => 'commit',
        'parameters' => 
        array (
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
        'docComment' => '/** @throws Exception */',
        'startLine' => 1037,
        'endLine' => 1062,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'updateTransactionStateAfterCommit' => 
      array (
        'name' => 'updateTransactionStateAfterCommit',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 1064,
        'endLine' => 1075,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'commitAll' => 
      array (
        'name' => 'commitAll',
        'parameters' => 
        array (
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
 * Commits all current nesting transactions.
 *
 * @throws Exception
 */',
        'startLine' => 1082,
        'endLine' => 1095,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'rollBack' => 
      array (
        'name' => 'rollBack',
        'parameters' => 
        array (
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
        'docComment' => '/** @throws Exception */',
        'startLine' => 1098,
        'endLine' => 1124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'createSavepoint' => 
      array (
        'name' => 'createSavepoint',
        'parameters' => 
        array (
          'savepoint' => 
          array (
            'name' => 'savepoint',
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
            'startLine' => 1133,
            'endLine' => 1133,
            'startColumn' => 37,
            'endColumn' => 53,
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
 * Creates a new savepoint.
 *
 * @param string $savepoint The name of the savepoint to create.
 *
 * @throws Exception
 */',
        'startLine' => 1133,
        'endLine' => 1142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'releaseSavepoint' => 
      array (
        'name' => 'releaseSavepoint',
        'parameters' => 
        array (
          'savepoint' => 
          array (
            'name' => 'savepoint',
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
            'startLine' => 1151,
            'endLine' => 1151,
            'startColumn' => 38,
            'endColumn' => 54,
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
 * Releases the given savepoint.
 *
 * @param string $savepoint The name of the savepoint to release.
 *
 * @throws Exception
 */',
        'startLine' => 1151,
        'endLine' => 1164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'rollbackSavepoint' => 
      array (
        'name' => 'rollbackSavepoint',
        'parameters' => 
        array (
          'savepoint' => 
          array (
            'name' => 'savepoint',
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
            'startLine' => 1173,
            'endLine' => 1173,
            'startColumn' => 39,
            'endColumn' => 55,
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
 * Rolls back to the given savepoint.
 *
 * @param string $savepoint The name of the savepoint to rollback to.
 *
 * @throws Exception
 */',
        'startLine' => 1173,
        'endLine' => 1182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getNativeConnection' => 
      array (
        'name' => 'getNativeConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Provides access to the native database connection.
 *
 * @return resource|object
 *
 * @throws Exception
 */',
        'startLine' => 1191,
        'endLine' => 1194,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'createSchemaManager' => 
      array (
        'name' => 'createSchemaManager',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Schema\\AbstractSchemaManager',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a SchemaManager that can be used to inspect or change the
 * database schema through the connection.
 *
 * @throws Exception
 */',
        'startLine' => 1202,
        'endLine' => 1205,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'setRollbackOnly' => 
      array (
        'name' => 'setRollbackOnly',
        'parameters' => 
        array (
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
 * Marks the current transaction so that the only possible
 * outcome for the transaction to be rolled back.
 *
 * @throws ConnectionException If no transaction is active.
 */',
        'startLine' => 1213,
        'endLine' => 1220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'isRollbackOnly' => 
      array (
        'name' => 'isRollbackOnly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks whether the current transaction is marked for rollback only.
 *
 * @throws ConnectionException If no transaction is active.
 */',
        'startLine' => 1227,
        'endLine' => 1234,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'convertToDatabaseValue' => 
      array (
        'name' => 'convertToDatabaseValue',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 44,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 58,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts a given value to its database representation according to the conversion
 * rules of a specific DBAL mapping type.
 *
 * @param mixed  $value The value to convert.
 * @param string $type  The name of the DBAL mapping type.
 *
 * @return mixed The converted value.
 *
 * @throws Exception
 */',
        'startLine' => 1247,
        'endLine' => 1250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'convertToPHPValue' => 
      array (
        'name' => 'convertToPHPValue',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1263,
            'endLine' => 1263,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 1263,
            'endLine' => 1263,
            'startColumn' => 53,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts a given value to its PHP representation according to the conversion
 * rules of a specific DBAL mapping type.
 *
 * @param mixed  $value The value to convert.
 * @param string $type  The name of the DBAL mapping type.
 *
 * @return mixed The converted type.
 *
 * @throws Exception
 */',
        'startLine' => 1263,
        'endLine' => 1266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'bindParameters' => 
      array (
        'name' => 'bindParameters',
        'parameters' => 
        array (
          'stmt' => 
          array (
            'name' => 'stmt',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Driver\\Statement',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1277,
            'endLine' => 1277,
            'startColumn' => 37,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
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
            'startLine' => 1277,
            'endLine' => 1277,
            'startColumn' => 60,
            'endColumn' => 72,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
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
            'startLine' => 1277,
            'endLine' => 1277,
            'startColumn' => 75,
            'endColumn' => 86,
            'parameterIndex' => 2,
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
 * Binds a set of parameters, some or all of which are typed with a PDO binding type
 * or DBAL mapping type, to a given statement.
 *
 * @param list<mixed>|array<string, mixed>                                               $params
 * @param array<int, string|ParameterType|Type>|array<string, string|ParameterType|Type> $types
 *
 * @throws Exception
 */',
        'startLine' => 1277,
        'endLine' => 1308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'getBindingInfo' => 
      array (
        'name' => 'getBindingInfo',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1320,
            'endLine' => 1320,
            'startColumn' => 37,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
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
                      'name' => 'Doctrine\\DBAL\\ParameterType',
                      'isIdentifier' => false,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Doctrine\\DBAL\\Types\\Type',
                      'isIdentifier' => false,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1320,
            'endLine' => 1320,
            'startColumn' => 51,
            'endColumn' => 81,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the binding type of a given type.
 *
 * @param mixed                     $value The value to bind.
 * @param string|ParameterType|Type $type  The type to bind.
 *
 * @return array{mixed, ParameterType} [0] => the (escaped) value, [1] => the binding type.
 *
 * @throws Exception
 */',
        'startLine' => 1320,
        'endLine' => 1334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'createQueryBuilder' => 
      array (
        'name' => 'createQueryBuilder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Query\\QueryBuilder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a new instance of a SQL query builder.
 */',
        'startLine' => 1339,
        'endLine' => 1342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'convertExceptionDuringQuery' => 
      array (
        'name' => 'convertExceptionDuringQuery',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Driver\\Exception',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1351,
            'endLine' => 1351,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sql' => 
          array (
            'name' => 'sql',
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
            'startLine' => 1352,
            'endLine' => 1352,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1353,
                'endLine' => 1353,
                'startTokenPos' => 5763,
                'startFilePos' => 41898,
                'endTokenPos' => 5764,
                'endFilePos' => 41899,
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
            'startLine' => 1353,
            'endLine' => 1353,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1354,
                'endLine' => 1354,
                'startTokenPos' => 5773,
                'startFilePos' => 41925,
                'endTokenPos' => 5774,
                'endFilePos' => 41926,
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
            'startLine' => 1354,
            'endLine' => 1354,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Exception\\DriverException',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @internal
 *
 * @param list<mixed>|array<string,mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 */',
        'startLine' => 1350,
        'endLine' => 1357,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'convertException' => 
      array (
        'name' => 'convertException',
        'parameters' => 
        array (
          'e' => 
          array (
            'name' => 'e',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Driver\\Exception',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1360,
            'endLine' => 1360,
            'startColumn' => 44,
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
            'name' => 'Doctrine\\DBAL\\Exception\\DriverException',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @internal */',
        'startLine' => 1360,
        'endLine' => 1363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'expandArrayParameters' => 
      array (
        'name' => 'expandArrayParameters',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
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
            'startLine' => 1375,
            'endLine' => 1375,
            'startColumn' => 44,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
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
            'startLine' => 1375,
            'endLine' => 1375,
            'startColumn' => 57,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
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
            'startLine' => 1375,
            'endLine' => 1375,
            'startColumn' => 72,
            'endColumn' => 83,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param list<mixed>|array<string, mixed> $params
 * @phpstan-param WrapperParameterTypeArray $types
 *
 * @return array{
 *     string,
 *     list<mixed>|array<string, mixed>,
 *     array<int<0, max>, string|ParameterType|Type>|array<string, string|ParameterType|Type>
 * }
 */',
        'startLine' => 1375,
        'endLine' => 1407,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
        'aliasName' => NULL,
      ),
      'handleDriverException' => 
      array (
        'name' => 'handleDriverException',
        'parameters' => 
        array (
          'driverException' => 
          array (
            'name' => 'driverException',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Doctrine\\DBAL\\Driver\\Exception',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1410,
            'endLine' => 1410,
            'startColumn' => 9,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
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
                      'name' => 'Doctrine\\DBAL\\Query',
                      'isIdentifier' => false,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1411,
            'endLine' => 1411,
            'startColumn' => 9,
            'endColumn' => 21,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Doctrine\\DBAL\\Exception\\DriverException',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1409,
        'endLine' => 1421,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Doctrine\\DBAL',
        'declaringClassName' => 'Doctrine\\DBAL\\Connection',
        'implementingClassName' => 'Doctrine\\DBAL\\Connection',
        'currentClassName' => 'Doctrine\\DBAL\\Connection',
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