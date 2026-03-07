<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/other.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-scan_for_modules
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-e0580ad2bbb412f1dba506e2d8e444cb2648693c3cf25b483388970f1e638770',
   'data' => 
  array (
    'name' => 'scan_for_modules',
    'parameters' => 
    array (
      'options' => 
      array (
        'name' => 'options',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 126,
            'endLine' => 126,
            'startTokenPos' => 401,
            'startFilePos' => 2972,
            'endTokenPos' => 401,
            'endFilePos' => 2976,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 27,
        'endColumn' => 42,
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
 * Function modules list from the db or them the disk.
 *
 * @param array $params
 *
 *
 * Example:
 * $params = array();
 * $params[\'dir_name\'] = \'/path/\'; //get modules in dir
 * $params[\'skip_save\'] = true; //if true skips module install
 * $params[\'skip_cache\'] = true; // skip_cache
 *
 * $params[\'cache_group\'] = \'modules/global\'; // allows custom cache group
 * $params[\'cleanup_db\'] = true; //if true will reinstall all modules if skip_save is false
 * $params[\'is_elements\'] = true;  //if true will list files from the MW_ELEMENTS_DIR
 *
 * $data = scan_for_modules($params);
 * @return mixed Array with modules or false
 *
 */',
    'startLine' => 126,
    'endLine' => 129,
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
        'name' => 'scan_for_modules',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/App/functions/other.php',
      ),
    ),
  ),
));