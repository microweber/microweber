<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Database/Utils.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Database\Utils
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-cd2071450e7916d4f3f2a8d94c577a0823fc404c237c21d9400edb9e8885a7c8',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Database\\Utils',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Database/Utils.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Database',
    'name' => 'MicroweberPackages\\Database\\Utils',
    'shortName' => 'Utils',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Database Utils class.
 *
 * Query helper class
 *
 * @category Database
 * @desc     Various utils functions to work with the database
 *
 * @property \\MicroweberPackages\\Application $app
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 31,
    'endLine' => 785,
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
      'cache_seconds' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'name' => 'cache_seconds',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '36000',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 52,
            'startFilePos' => 704,
            'endTokenPos' => 52,
            'endFilePos' => 708,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table_prefix' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'name' => 'table_prefix',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 287,
        'endLine' => 287,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'default_limit' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'name' => 'default_limit',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '30',
          'attributes' => 
          array (
            'startLine' => 312,
            'endLine' => 312,
            'startTokenPos' => 2018,
            'startFilePos' => 10208,
            'endTokenPos' => 2018,
            'endFilePos' => 10209,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 312,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table_fields' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'name' => 'table_fields',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 314,
            'endLine' => 314,
            'startTokenPos' => 2027,
            'startFilePos' => 10240,
            'endTokenPos' => 2029,
            'endFilePos' => 10246,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 314,
        'endLine' => 314,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'get_fields_fields_memory' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'name' => 'get_fields_fields_memory',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 383,
            'endLine' => 383,
            'startTokenPos' => 2260,
            'startFilePos' => 12093,
            'endTokenPos' => 2261,
            'endFilePos' => 12094,
          ),
        ),
        'docComment' => '/**
 * Gets all field names from a DB table.
 *
 * @param            $table          string
 *                                   - table name
 * @param array|bool $exclude_fields array
 *                                   - fields to exclude
 *
 * @return array
 *
 * @author  Peter Ivanov
 *
 * @version 1.0
 *
 * @since   Version 1.0
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 383,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 49,
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
      'build_tables' => 
      array (
        'name' => 'build_tables',
        'parameters' => 
        array (
          'tables' => 
          array (
            'name' => 'tables',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 34,
            'endColumn' => 40,
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
        'startLine' => 35,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'build_table' => 
      array (
        'name' => 'build_table',
        'parameters' => 
        array (
          'table_name' => 
          array (
            'name' => 'table_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fields_to_add' => 
          array (
            'name' => 'fields_to_add',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 46,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'use_cache' => 
          array (
            'name' => 'use_cache',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 42,
                'endLine' => 42,
                'startTokenPos' => 113,
                'startFilePos' => 947,
                'endTokenPos' => 113,
                'endFilePos' => 951,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 62,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 42,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'table_exists' => 
      array (
        'name' => 'table_exists',
        'parameters' => 
        array (
          'table_name' => 
          array (
            'name' => 'table_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 34,
            'endColumn' => 44,
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
        'startLine' => 67,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      '_exec_table_builder' => 
      array (
        'name' => '_exec_table_builder',
        'parameters' => 
        array (
          'table_name' => 
          array (
            'name' => 'table_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 42,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fields_to_add' => 
          array (
            'name' => 'fields_to_add',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 55,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 76,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'assoc_table_name' => 
      array (
        'name' => 'assoc_table_name',
        'parameters' => 
        array (
          'assoc_name' => 
          array (
            'name' => 'assoc_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 180,
            'endLine' => 180,
            'startColumn' => 38,
            'endColumn' => 48,
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
        'startLine' => 180,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'listTables' => 
      array (
        'name' => 'listTables',
        'parameters' => 
        array (
          'only_cms_tables' => 
          array (
            'name' => 'only_cms_tables',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 187,
                'endLine' => 187,
                'startTokenPos' => 1111,
                'startFilePos' => 6051,
                'endTokenPos' => 1111,
                'endFilePos' => 6054,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 187,
            'endLine' => 187,
            'startColumn' => 32,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'with_prefix' => 
          array (
            'name' => 'with_prefix',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 187,
                'endLine' => 187,
                'startTokenPos' => 1115,
                'startFilePos' => 6069,
                'endTokenPos' => 1115,
                'endFilePos' => 6073,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 187,
            'endLine' => 187,
            'startColumn' => 56,
            'endColumn' => 73,
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
        'startLine' => 187,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'get_tables_list' => 
      array (
        'name' => 'get_tables_list',
        'parameters' => 
        array (
          'only_cms_tables' => 
          array (
            'name' => 'only_cms_tables',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 192,
                'endLine' => 192,
                'startTokenPos' => 1144,
                'startFilePos' => 6211,
                'endTokenPos' => 1144,
                'endFilePos' => 6214,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 192,
            'endLine' => 192,
            'startColumn' => 34,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'with_prefix' => 
          array (
            'name' => 'with_prefix',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 192,
                'endLine' => 192,
                'startTokenPos' => 1148,
                'startFilePos' => 6229,
                'endTokenPos' => 1148,
                'endFilePos' => 6233,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 192,
            'endLine' => 192,
            'startColumn' => 58,
            'endColumn' => 75,
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
        'startLine' => 192,
        'endLine' => 245,
        'startColumn' => 2,
        'endColumn' => 2,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'get_table_ddl' => 
      array (
        'name' => 'get_table_ddl',
        'parameters' => 
        array (
          'full_table_name' => 
          array (
            'name' => 'full_table_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 246,
            'endLine' => 246,
            'startColumn' => 35,
            'endColumn' => 50,
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
        'startLine' => 246,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'get_sql_engine' => 
      array (
        'name' => 'get_sql_engine',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 272,
        'endLine' => 277,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'get_prefix' => 
      array (
        'name' => 'get_prefix',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 279,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'real_table_name' => 
      array (
        'name' => 'real_table_name',
        'parameters' => 
        array (
          'assoc_name' => 
          array (
            'name' => 'assoc_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 37,
            'endColumn' => 47,
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
        'startLine' => 289,
        'endLine' => 310,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'map_array_to_table' => 
      array (
        'name' => 'map_array_to_table',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 40,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'array' => 
          array (
            'name' => 'array',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 334,
            'endLine' => 334,
            'startColumn' => 48,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns an array that contains only keys that has the same names as the table fields from the database.
 *
 * @param string
 * @param array
 *
 * @return array
 *
 * @example
 * <code>
 * $table = $this->table_prefix.\'content\';
 * $data = array();
 * $data[\'id\'] = 1;
 * $data[\'non_ex\'] = \'i do not exist and will be removed\';
 * $criteria = $this->map_array_to_table($table, $array);
 * var_dump($criteria);
 * </code>
 */',
        'startLine' => 334,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'get_fields' => 
      array (
        'name' => 'get_fields',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 32,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'use_cache' => 
          array (
            'name' => 'use_cache',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 385,
                'endLine' => 385,
                'startTokenPos' => 2277,
                'startFilePos' => 12150,
                'endTokenPos' => 2277,
                'endFilePos' => 12153,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 40,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'advanced_info' => 
          array (
            'name' => 'advanced_info',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 385,
                'endLine' => 385,
                'startTokenPos' => 2284,
                'startFilePos' => 12173,
                'endTokenPos' => 2284,
                'endFilePos' => 12177,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 59,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 385,
        'endLine' => 503,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'guess_cache_group' => 
      array (
        'name' => 'guess_cache_group',
        'parameters' => 
        array (
          'group' => 
          array (
            'name' => 'group',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 39,
            'endColumn' => 44,
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
        'startLine' => 505,
        'endLine' => 508,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'update_position_field' => 
      array (
        'name' => 'update_position_field',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => 'array()',
              'attributes' => 
              array (
                'startLine' => 510,
                'endLine' => 510,
                'startTokenPos' => 3021,
                'startFilePos' => 16235,
                'endTokenPos' => 3023,
                'endFilePos' => 16241,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 51,
            'endColumn' => 65,
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
        'startLine' => 510,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'copy_row_by_id' => 
      array (
        'name' => 'copy_row_by_id',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 531,
            'endLine' => 531,
            'startColumn' => 36,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 531,
                'endLine' => 531,
                'startTokenPos' => 3155,
                'startFilePos' => 16812,
                'endTokenPos' => 3155,
                'endFilePos' => 16812,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 531,
            'endLine' => 531,
            'startColumn' => 44,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'field_name' => 
          array (
            'name' => 'field_name',
            'default' => 
            array (
              'code' => '\'id\'',
              'attributes' => 
              array (
                'startLine' => 531,
                'endLine' => 531,
                'startTokenPos' => 3162,
                'startFilePos' => 16829,
                'endTokenPos' => 3162,
                'endFilePos' => 16832,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 531,
            'endLine' => 531,
            'startColumn' => 53,
            'endColumn' => 70,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 531,
        'endLine' => 542,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'clean_input' => 
      array (
        'name' => 'clean_input',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 545,
            'endLine' => 545,
            'startColumn' => 33,
            'endColumn' => 38,
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
        'startLine' => 545,
        'endLine' => 571,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'escape_string' => 
      array (
        'name' => 'escape_string',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 587,
            'endLine' => 587,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Escapes a string from sql injection.
 *
 * @param string|array $value to escape
 *
 * @return string|array Escaped string
 * @return mixed        Es
 *
 * @example
 * <code>
 * //escape sql string
 *  $results = $this->escape_string($_POST[\'email\']);
 * </code>
 */',
        'startLine' => 587,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'add_table_index' => 
      array (
        'name' => 'add_table_index',
        'parameters' => 
        array (
          'aIndexName' => 
          array (
            'name' => 'aIndexName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 613,
                'endLine' => 613,
                'startTokenPos' => 3645,
                'startFilePos' => 19139,
                'endTokenPos' => 3645,
                'endFilePos' => 19142,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'aTable' => 
          array (
            'name' => 'aTable',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 613,
                'endLine' => 613,
                'startTokenPos' => 3650,
                'startFilePos' => 19153,
                'endTokenPos' => 3650,
                'endFilePos' => 19156,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
            'startColumn' => 55,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'aOnColumns' => 
          array (
            'name' => 'aOnColumns',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 613,
                'endLine' => 613,
                'startTokenPos' => 3655,
                'startFilePos' => 19171,
                'endTokenPos' => 3655,
                'endFilePos' => 19174,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
            'startColumn' => 69,
            'endColumn' => 84,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 613,
        'endLine' => 620,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'import_sql_file' => 
      array (
        'name' => 'import_sql_file',
        'parameters' => 
        array (
          'full_path_to_file' => 
          array (
            'name' => 'full_path_to_file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 631,
            'endLine' => 631,
            'startColumn' => 37,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Imposts SQL file in the DB.
 *
 * @param $full_path_to_file
 *
 * @return bool
 * @category   Database
 *
 */',
        'startLine' => 631,
        'endLine' => 653,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'remove_sql_remarks' => 
      array (
        'name' => 'remove_sql_remarks',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 655,
            'endLine' => 655,
            'startColumn' => 40,
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
        'startLine' => 655,
        'endLine' => 673,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'remove_comments_from_sql_string' => 
      array (
        'name' => 'remove_comments_from_sql_string',
        'parameters' => 
        array (
          'output' => 
          array (
            'name' => 'output',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 687,
            'endLine' => 687,
            'startColumn' => 53,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Will strip the sql comment lines out of an given sql string.
 *
 * @param $output the SQL string with comments
 *
 * @return string $output the SQL string without comments
 *
 * @example
 * <code>
 *  sql_remove_comments($sql_str);
 * </code>
 */',
        'startLine' => 687,
        'endLine' => 707,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'query_log' => 
      array (
        'name' => 'query_log',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 709,
        'endLine' => 712,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
        'aliasName' => NULL,
      ),
      'split_sql_file' => 
      array (
        'name' => 'split_sql_file',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 714,
            'endLine' => 714,
            'startColumn' => 36,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'delimiter' => 
          array (
            'name' => 'delimiter',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 714,
            'endLine' => 714,
            'startColumn' => 42,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 714,
        'endLine' => 784,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Database',
        'declaringClassName' => 'MicroweberPackages\\Database\\Utils',
        'implementingClassName' => 'MicroweberPackages\\Database\\Utils',
        'currentClassName' => 'MicroweberPackages\\Database\\Utils',
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