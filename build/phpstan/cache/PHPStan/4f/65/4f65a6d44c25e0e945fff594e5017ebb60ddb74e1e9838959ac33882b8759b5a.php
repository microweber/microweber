<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/common.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-cache_save
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-59c362ba4ab1efda04fa0bebc699471b71eb4a8a87d2982ef4ce6e1b2dce1b3b',
   'data' => 
  array (
    'name' => 'cache_save',
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
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 21,
        'endColumn' => 34,
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
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 37,
        'endColumn' => 45,
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
            'startLine' => 168,
            'endLine' => 168,
            'startTokenPos' => 539,
            'startFilePos' => 3736,
            'endTokenPos' => 539,
            'endFilePos' => 3743,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 48,
        'endColumn' => 70,
        'parameterIndex' => 2,
        'isOptional' => true,
      ),
      'expiration_in_seconds' => 
      array (
        'name' => 'expiration_in_seconds',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 168,
            'endLine' => 168,
            'startTokenPos' => 546,
            'startFilePos' => 3771,
            'endTokenPos' => 546,
            'endFilePos' => 3775,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 73,
        'endColumn' => 102,
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
 * @example
 * <code>
 * //store custom data in cache
 * $data = array(\'something\' => \'some_value\');
 * $cache_id = \'my_cache_id\';
 * $cache_content = cache_save($data, $cache_id, \'my_cache_group\');
 * </code>
 *
 * @param mixed $data_to_cache
 *                                      your data, anything that can be serialized
 * @param string $cache_id
 *                                      id of the cache, you must define it because you will use it later to
 *                                      retrieve the cached content.
 * @param string $cache_group
 *                                      (default is \'global\') - this is the subfolder in the cache dir.
 * @param int $expiration_in_seconds
 *
 * @return bool
 */',
    'startLine' => 168,
    'endLine' => 171,
    'startColumn' => 1,
    'endColumn' => 1,
    'couldThrow' => false,
    'isClosure' => false,
    'isGenerator' => false,
    'isVariadic' => false,
    'isStatic' => false,
    'namespace' => NULL,
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'cache_save',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/common.php',
      ),
    ),
  ),
));