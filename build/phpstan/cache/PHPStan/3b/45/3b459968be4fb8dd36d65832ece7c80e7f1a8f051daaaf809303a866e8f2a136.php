<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/helpers/helpers.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-user_name
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-2ac55feb1da2915fa1a6c873a19011115b42fb8db239cf14cf72037c056505b1',
   'data' => 
  array (
    'name' => 'user_name',
    'parameters' => 
    array (
      'user_id' => 
      array (
        'name' => 'user_id',
        'default' => 
        array (
          'code' => '\\false',
          'attributes' => 
          array (
            'startLine' => 279,
            'endLine' => 279,
            'startTokenPos' => 1149,
            'startFilePos' => 5710,
            'endTokenPos' => 1149,
            'endFilePos' => 5714,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 279,
        'endLine' => 279,
        'startColumn' => 20,
        'endColumn' => 35,
        'parameterIndex' => 0,
        'isOptional' => true,
      ),
      'mode' => 
      array (
        'name' => 'mode',
        'default' => 
        array (
          'code' => '\'full\'',
          'attributes' => 
          array (
            'startLine' => 279,
            'endLine' => 279,
            'startTokenPos' => 1156,
            'startFilePos' => 5725,
            'endTokenPos' => 1156,
            'endFilePos' => 5730,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 279,
        'endLine' => 279,
        'startColumn' => 38,
        'endColumn' => 51,
        'parameterIndex' => 1,
        'isOptional' => true,
      ),
    ),
    'returnsReference' => false,
    'returnType' => NULL,
    'attributes' => 
    array (
    ),
    'docComment' => '/**
 * @function user_name
 * gets the user\'s FULL name
 *
 * @param        $user_id the id of the user. If false it will use the curent user (you)
 * @param string $mode full|first|last|username
 *                        \'full\' //prints full name (first +last)
 *                        \'first\' //prints first name
 *                        \'last\' //prints last name
 *                        \'username\' //prints username
 *
 * @return string
 */',
    'startLine' => 279,
    'endLine' => 282,
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
        'name' => 'user_name',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/helpers/helpers.php',
      ),
    ),
  ),
));