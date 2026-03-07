<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Option/helpers/options.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-get_option
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-5887c51f06b5a2b6207b156e483d0905eee733d3c4594a6890183dc91d741eb2',
   'data' => 
  array (
    'name' => 'get_option',
    'parameters' => 
    array (
      'key' => 
      array (
        'name' => 'key',
        'default' => NULL,
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 21,
        'endColumn' => 24,
        'parameterIndex' => 0,
        'isOptional' => false,
      ),
      'option_group' => 
      array (
        'name' => 'option_group',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 315,
            'startFilePos' => 1488,
            'endTokenPos' => 315,
            'endFilePos' => 1492,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 27,
        'endColumn' => 47,
        'parameterIndex' => 1,
        'isOptional' => true,
      ),
      'return_full' => 
      array (
        'name' => 'return_full',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 322,
            'startFilePos' => 1510,
            'endTokenPos' => 322,
            'endFilePos' => 1514,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 50,
        'endColumn' => 69,
        'parameterIndex' => 2,
        'isOptional' => true,
      ),
      'orderby' => 
      array (
        'name' => 'orderby',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 329,
            'startFilePos' => 1528,
            'endTokenPos' => 329,
            'endFilePos' => 1532,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 72,
        'endColumn' => 87,
        'parameterIndex' => 3,
        'isOptional' => true,
      ),
      'module' => 
      array (
        'name' => 'module',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 336,
            'startFilePos' => 1545,
            'endTokenPos' => 336,
            'endFilePos' => 1549,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 90,
        'endColumn' => 104,
        'parameterIndex' => 4,
        'isOptional' => true,
      ),
    ),
    'returnsReference' => false,
    'returnType' => NULL,
    'attributes' => 
    array (
    ),
    'docComment' => '/**
 * Getting options from the database.
 *
 * @param $key array|string - if array it will replace the db params
 * @param $option_group string - your option group
 * @param $return_full bool - if true it will return the whole db row as array rather then just the value
 * @param $module string - if set it will store option for module
 * Example usage:
 * get_option(\'my_key\', \'my_group\');
 */',
    'startLine' => 50,
    'endLine' => 53,
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
        'name' => 'get_option',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Option/helpers/options.php',
      ),
    ),
  ),
));