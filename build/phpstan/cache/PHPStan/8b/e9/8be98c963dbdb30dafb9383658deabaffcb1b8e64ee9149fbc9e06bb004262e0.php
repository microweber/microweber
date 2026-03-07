<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/common.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-cache_clear
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-59c362ba4ab1efda04fa0bebc699471b71eb4a8a87d2982ef4ce6e1b2dce1b3b',
   'data' => 
  array (
    'name' => 'cache_clear',
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
            'startLine' => 286,
            'endLine' => 286,
            'startTokenPos' => 1073,
            'startFilePos' => 6526,
            'endTokenPos' => 1073,
            'endFilePos' => 6533,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 286,
        'endLine' => 286,
        'startColumn' => 22,
        'endColumn' => 44,
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
 *  cache_clear("content");
 *
 * //delete the cache for the content with id 1
 *  cache_clear("content/1");
 *
 * //delete the cache for users
 *  cache_clear("users");
 *
 * //delete the cache for your custom table eg. my_table
 * cache_clear("my_table");
 * </code>
 */',
    'startLine' => 286,
    'endLine' => 289,
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
        'name' => 'cache_clear',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/common.php',
      ),
    ),
  ),
));