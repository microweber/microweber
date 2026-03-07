<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/Managers/CacheManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\App\Managers\CacheManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-2e9022f29de639050a937b4f35290f123848039c5142008b1cc32bfc1571bf11',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/Managers/CacheManager.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\App\\Managers',
    'name' => 'MicroweberPackages\\App\\Managers\\CacheManager',
    'shortName' => 'CacheManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Cache class.
 *
 * These functions will allow you to save and get data from the MW cache system
 *
 * @category Cache
 * @desc     These functions will allow you to save and get data from the MW cache system
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 156,
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
      'app' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'name' => 'app',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * An instance of the Microweber Application class.
 *
 * @var
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'adapter' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'name' => 'adapter',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * An instance of the cache adapter to use.
 *
 * @var
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 20,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'app' => 
          array (
            'name' => 'app',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 30,
                'endLine' => 30,
                'startTokenPos' => 44,
                'startFilePos' => 606,
                'endTokenPos' => 44,
                'endFilePos' => 609,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Managers',
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'currentClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'data_to_cache' => 
          array (
            'name' => 'data_to_cache',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 26,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cache_id' => 
          array (
            'name' => 'cache_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'cache_group' => 
          array (
            'name' => 'cache_group',
            'default' => 
            array (
              'code' => '\'global\'',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 138,
                'startFilePos' => 1812,
                'endTokenPos' => 138,
                'endFilePos' => 1819,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 53,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'expiration' => 
          array (
            'name' => 'expiration',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 66,
                'endLine' => 66,
                'startTokenPos' => 145,
                'startFilePos' => 1836,
                'endTokenPos' => 145,
                'endFilePos' => 1840,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 78,
            'endColumn' => 96,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Stores your data in the cache.
 * It can store any value that can be serialized, such as strings, array, etc.
 *
 * @param mixed  $data_to_cache
 *                              your data, anything that can be serialized
 * @param string $cache_id
 *                              id of the cache, you must define it because you will use it later to
 *                              retrieve the cached content.
 * @param string $cache_group
 *                              (default is \'global\') - this is the subfolder in the cache dir.
 *
 * @return bool
 *
 * @example
 * <code>
 * //store custom data in cache
 * $data = array(\'something\' => \'some_value\');
 * $cache_id = \'my_cache_id\';
 * $cache_content = mw()->cache_manager->save($data, $cache_id, \'my_cache_group\');
 * </code>
 */',
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Managers',
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'currentClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'cache_id' => 
          array (
            'name' => 'cache_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 25,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cache_group' => 
          array (
            'name' => 'cache_group',
            'default' => 
            array (
              'code' => '\'global\'',
              'attributes' => 
              array (
                'startLine' => 91,
                'endLine' => 91,
                'startTokenPos' => 188,
                'startFilePos' => 2569,
                'endTokenPos' => 188,
                'endFilePos' => 2576,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 36,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'timeout' => 
          array (
            'name' => 'timeout',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 91,
                'endLine' => 91,
                'startTokenPos' => 195,
                'startFilePos' => 2590,
                'endTokenPos' => 195,
                'endFilePos' => 2594,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 91,
            'endLine' => 91,
            'startColumn' => 61,
            'endColumn' => 76,
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
 *  Gets the data from the cache.
 *
 *  If data is not found it return false
 *     *
 *
 * @param string $cache_id    id of the cache
 * @param string $cache_group (default is \'global\') - this is the subfolder in the cache dir.
 * @param bool   $timeout
 *
 * @return mixed returns array of cached data or false
 *
 * @example
 * <code>
 *
 * $cache_id = \'my_cache_\'.crc32($sql_query_string);
 * $cache_content = mw()->cache_manager->get($cache_id, \'my_cache_group\');
 *
 * </code>
 */',
        'startLine' => 91,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Managers',
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'currentClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
          'cache_group' => 
          array (
            'name' => 'cache_group',
            'default' => 
            array (
              'code' => '\'global\'',
              'attributes' => 
              array (
                'startLine' => 119,
                'endLine' => 119,
                'startTokenPos' => 232,
                'startFilePos' => 3418,
                'endTokenPos' => 232,
                'endFilePos' => 3425,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 28,
            'endColumn' => 50,
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
 * Deletes cache for given $cache_group recursively.
 *
 * @param string $cache_group
 *                            (default is \'global\') - this is the subfolder in the cache dir.
 *
 * @return bool
 *
 * @example
 * <code>
 * //delete the cache for the content
 *  mw()->cache_manager->delete("content");
 *
 * //delete the cache for the content with id 1
 *  mw()->cache_manager->delete("content/1");
 *
 * //delete the cache for users
 *  mw()->cache_manager->delete("users");
 *
 * //delete the cache for your custom table eg. my_table
 * mw()->cache_manager->delete("my_table");
 * </code>
 */',
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Managers',
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'currentClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'aliasName' => NULL,
      ),
      'clear' => 
      array (
        'name' => 'clear',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Clears all cache data.
 *
 * @example
 *          <code>
 *          //delete all cache
 *          mw()->cache_manager->clear();
 *          </code>
 *
 * @return bool
 */',
        'startLine' => 135,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Managers',
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'currentClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'aliasName' => NULL,
      ),
      'debug' => 
      array (
        'name' => 'debug',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prints cache debug information.
 *
 * @return array
 *
 * @example
 * <code>
 * //get cache items info
 *  $cached_items = mw()->cache_manager->debug();
 * print_r($cached_items);
 * </code>
 */',
        'startLine' => 152,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\App\\Managers',
        'declaringClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'implementingClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
        'currentClassName' => 'MicroweberPackages\\App\\Managers\\CacheManager',
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