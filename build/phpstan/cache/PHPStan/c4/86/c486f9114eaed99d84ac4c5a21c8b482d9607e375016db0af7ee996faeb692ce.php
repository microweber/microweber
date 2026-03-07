<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/common.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-cache_get
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-59c362ba4ab1efda04fa0bebc699471b71eb4a8a87d2982ef4ce6e1b2dce1b3b',
   'data' => 
  array (
    'name' => 'cache_get',
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
        'startLine' => 140,
        'endLine' => 140,
        'startColumn' => 20,
        'endColumn' => 28,
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
            'startLine' => 140,
            'endLine' => 140,
            'startTokenPos' => 489,
            'startFilePos' => 2717,
            'endTokenPos' => 489,
            'endFilePos' => 2724,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 140,
        'endLine' => 140,
        'startColumn' => 31,
        'endColumn' => 53,
        'parameterIndex' => 1,
        'isOptional' => true,
      ),
      'expiration' => 
      array (
        'name' => 'expiration',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 140,
            'endLine' => 140,
            'startTokenPos' => 496,
            'startFilePos' => 2741,
            'endTokenPos' => 496,
            'endFilePos' => 2745,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 140,
        'endLine' => 140,
        'startColumn' => 56,
        'endColumn' => 74,
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
 *
 *
 * @example
 * <code>
 *
 * $cache_id = \'my_cache_\'.crc32($sql_query_string);
 * $cache_content = cache_get_content($cache_id, \'my_cache_group\');
 *
 * </code>
 *
 * @param string $cache_id id of the cache
 * @param string $cache_group (default is \'global\') - this is the subfolder in the cache dir.
 * @param bool $expiration_in_seconds You can pass custom cache object or leave false.
 *
 * @return mixed returns array of cached data or false
 */',
    'startLine' => 140,
    'endLine' => 143,
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
        'name' => 'cache_get',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/common.php',
      ),
    ),
  ),
));