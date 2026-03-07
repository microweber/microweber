<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/helpers/helpers.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-save_user
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-2ac55feb1da2915fa1a6c873a19011115b42fb8db239cf14cf72037c056505b1',
   'data' => 
  array (
    'name' => 'save_user',
    'parameters' => 
    array (
      'params' => 
      array (
        'name' => 'params',
        'default' => NULL,
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 142,
        'endLine' => 142,
        'startColumn' => 20,
        'endColumn' => 26,
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
 * Allows you to save users in the database.
 *
 * By default it have security rules.
 *
 * If you are admin you can save any user in the system;
 *
 * However if you are regular user you must post param id with the current user id;
 *
 * @param  $params
 * @param  $params [\'id\'] = $user_id; // REQUIRED , you must set the user id.
 *                 For security reasons, to make new user please use user_register() function that requires captcha
 *                 or write your own save_user wrapper function that sets  mw_var(\'force_save_user\',true);
 *                 and pass its params to save_user();
 * @param  $params [\'is_active\'] = 1; //default is \'n\'
 *
 * @usage
 *
 * $upd = array();
 * $upd[\'id\'] = 1;
 * $upd[\'email\'] = $params[\'new_email\'];
 * $upd[\'password\'] = $params[\'passwordhash\'];
 * mw_var(\'force_save_user\', false|true); // if true you want to make new user or foce save ... skips id check and is admin check
 * mw_var(\'save_user_no_pass_hash\', false|true); //if true skips pass hash function and saves password it as is in the request, please hash the password before that or ensure its hashed
 * $s = save_user($upd);
 *
 * @return bool|int
 */',
    'startLine' => 142,
    'endLine' => 145,
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
        'name' => 'save_user',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/helpers/helpers.php',
      ),
    ),
  ),
));