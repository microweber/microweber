<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Services/UserManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\Services\UserManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-08b79db146ed4617867210a633561918cce6215f2b6299a0bd75f8ac8ea22492',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\Services\\UserManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Services/UserManager.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User\\Services',
    'name' => 'MicroweberPackages\\User\\Services\\UserManager',
    'shortName' => 'UserManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 1926,
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
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'name' => 'app',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var \\MicroweberPackages\\App\\LaravelApplication */',
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'socialite' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'name' => 'socialite',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var SocialiteManager */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'nice_name_cache' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'name' => 'nice_name_cache',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 559,
            'endLine' => 559,
            'startTokenPos' => 3091,
            'startFilePos' => 17597,
            'endTokenPos' => 3093,
            'endFilePos' => 17603,
          ),
        ),
        'docComment' => '/**
 * Function to get user printable name by given ID.
 *
 * @param        $id
 * @param string $mode
 *
 * @return string
 *
 * @example
 * <code>
 * //get user name for user with id 10
 * $this->nice_name(10, \'full\');
 * </code>
 *
 * @uses $this->get_by_id()
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 559,
        'endLine' => 559,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'force_save' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'name' => 'force_save',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 837,
            'endLine' => 837,
            'startTokenPos' => 4907,
            'startFilePos' => 27162,
            'endTokenPos' => 4907,
            'endFilePos' => 27166,
          ),
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
        'attributes' => 
        array (
        ),
        'startLine' => 837,
        'endLine' => 837,
        'startColumn' => 5,
        'endColumn' => 31,
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
                'startLine' => 34,
                'endLine' => 34,
                'startTokenPos' => 122,
                'startFilePos' => 1050,
                'endTokenPos' => 122,
                'endFilePos' => 1053,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
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
        'startLine' => 34,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'is_admin' => 
      array (
        'name' => 'is_admin',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'id' => 
      array (
        'name' => 'id',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 65,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'login' => 
      array (
        'name' => 'login',
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 27,
            'endColumn' => 33,
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
 * Allows you to login a user into the system.
 *
 * It also sets user session when the user is logged. <br />
 * On 5 unsuccessful logins, blocks the ip for few minutes <br />
 *
 *
 * @param array|string $params You can pass parameter as string or as array.
 * @param mixed|string $params [\'email\'] optional If you set  it will use this email for login
 * @param mixed|string $params [\'password\'] optional Use password for login, it gets trough $this->hash_pass() function
 *
 * @return array|bool
 *
 * @example
 * <code>
 * //login with username
 * $this->login(\'username=test&password=pass\')
 * </code>
 * @example
 * <code>
 * //login with email
 * $this->login(\'email=my@email.com&password=pass\')
 * </code>
 * @example
 * <code>
 * //login hashed password
 * $this->login(\'email=my@email.com&password_hashed=c4ca4238a0b923820dcc509a6f75849b\')
 * </code>
 *
 * @category Users
 *
 * @uses     $this->hash_pass()
 * @uses     parse_str()
 * @uses     $this->get_all()
 * @uses     $this->session_set()
 * @uses     app()->log_manager->get()
 * @uses     app()->log_manager->save()
 * @uses     $this->login_set_failed_attempt()
 * @uses     $this->update_last_login_time()
 * @uses     $this->app->event_manager->trigger()
 * @function $this->login()
 *
 * @see      _table() For the database table fields
 */',
        'startLine' => 123,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'logout' => 
      array (
        'name' => 'logout',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 1468,
                'startFilePos' => 8997,
                'endTokenPos' => 1468,
                'endFilePos' => 9001,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 28,
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
        'docComment' => NULL,
        'startLine' => 257,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'is_logged' => 
      array (
        'name' => 'is_logged',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 291,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'login_as' => 
      array (
        'name' => 'login_as',
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
            'startLine' => 311,
            'endLine' => 311,
            'startColumn' => 30,
            'endColumn' => 36,
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
        'startLine' => 311,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'has_access' => 
      array (
        'name' => 'has_access',
        'parameters' => 
        array (
          'function_name' => 
          array (
            'name' => 'function_name',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 319,
                'endLine' => 319,
                'startTokenPos' => 1894,
                'startFilePos' => 10719,
                'endTokenPos' => 1894,
                'endFilePos' => 10720,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 319,
            'endLine' => 319,
            'startColumn' => 32,
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
        'docComment' => NULL,
        'startLine' => 319,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'admin_access' => 
      array (
        'name' => 'admin_access',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 330,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'codeLogin' => 
      array (
        'name' => 'codeLogin',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allows you to login a user into the system.
 *
 * It also sets user session when the user is logged. <br />
 * On 5 unsuccessful logins, blocks the ip for few minutes <br />
 *
 *
 * @param array|string $params You can pass parameter as string or as array.
 * @param mixed|string $params [\'email\'] optional If you set  it will use this email for login
 * @param mixed|string $params [\'password\'] optional Use password for login, it gets trough $this->hash_pass() function
 *
 * @return array|bool
 *
 * @example
 * <code>
 * //login with username
 * $this->login(\'username=test&password=pass\')
 * </code>
 * @example
 * <code>
 * //login with email
 * $this->login(\'email=my@email.com&password=pass\')
 * </code>
 * @example
 * <code>
 * //login hashed password
 * $this->login(\'email=my@email.com&password_hashed=c4ca4238a0b923820dcc509a6f75849b\')
 * </code>
 *
 * @category Users
 *
 * @uses     $this->hash_pass()
 * @uses     parse_str()
 * @uses     $this->get_all()
 * @uses     $this->session_set()
 * @uses     app()->log_manager->get()
 * @uses     app()->log_manager->save()
 * @uses     $this->login_set_failed_attempt()
 * @uses     $this->update_last_login_time()
 * @uses     $this->app->event_manager->trigger()
 * @function $this->login()
 * @deprecated this function is deprecated
 * @see      _table() For the database table fields
 */',
        'startLine' => 381,
        'endLine' => 421,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'attributes' => 
      array (
        'name' => 'attributes',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 423,
                'endLine' => 423,
                'startTokenPos' => 2355,
                'startFilePos' => 14104,
                'endTokenPos' => 2355,
                'endFilePos' => 14108,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 423,
            'endLine' => 423,
            'startColumn' => 32,
            'endColumn' => 47,
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
        'startLine' => 423,
        'endLine' => 449,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'data_fields' => 
      array (
        'name' => 'data_fields',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 451,
                'endLine' => 451,
                'startTokenPos' => 2559,
                'startFilePos' => 14844,
                'endTokenPos' => 2559,
                'endFilePos' => 14848,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 451,
            'endLine' => 451,
            'startColumn' => 33,
            'endColumn' => 48,
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
        'startLine' => 451,
        'endLine' => 477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'has_picture' => 
      array (
        'name' => 'has_picture',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 479,
                'endLine' => 479,
                'startTokenPos' => 2763,
                'startFilePos' => 15570,
                'endTokenPos' => 2763,
                'endFilePos' => 15574,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 479,
            'endLine' => 479,
            'startColumn' => 33,
            'endColumn' => 48,
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
        'startLine' => 479,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'picture' => 
      array (
        'name' => 'picture',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 494,
                'endLine' => 494,
                'startTokenPos' => 2851,
                'startFilePos' => 15874,
                'endTokenPos' => 2851,
                'endFilePos' => 15878,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 494,
            'endLine' => 494,
            'startColumn' => 29,
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
        'docComment' => NULL,
        'startLine' => 494,
        'endLine' => 511,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'name' => 
      array (
        'name' => 'name',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 526,
                'endLine' => 526,
                'startTokenPos' => 2994,
                'startFilePos' => 16914,
                'endTokenPos' => 2994,
                'endFilePos' => 16918,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 526,
            'endLine' => 526,
            'startColumn' => 26,
            'endColumn' => 41,
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
                'startLine' => 526,
                'endLine' => 526,
                'startTokenPos' => 3001,
                'startFilePos' => 16929,
                'endTokenPos' => 3001,
                'endFilePos' => 16934,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 526,
            'endLine' => 526,
            'startColumn' => 44,
            'endColumn' => 57,
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
        'startLine' => 526,
        'endLine' => 540,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'nice_name' => 
      array (
        'name' => 'nice_name',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 561,
                'endLine' => 561,
                'startTokenPos' => 3106,
                'startFilePos' => 17643,
                'endTokenPos' => 3106,
                'endFilePos' => 17647,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 561,
            'endLine' => 561,
            'startColumn' => 31,
            'endColumn' => 41,
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
                'startLine' => 561,
                'endLine' => 561,
                'startTokenPos' => 3113,
                'startFilePos' => 17658,
                'endTokenPos' => 3113,
                'endFilePos' => 17663,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 561,
            'endLine' => 561,
            'startColumn' => 44,
            'endColumn' => 57,
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
        'startLine' => 561,
        'endLine' => 649,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'register' => 
      array (
        'name' => 'register',
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
            'startLine' => 652,
            'endLine' => 652,
            'startColumn' => 30,
            'endColumn' => 36,
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
        'startLine' => 652,
        'endLine' => 690,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'after_register' => 
      array (
        'name' => 'after_register',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 692,
            'endLine' => 692,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'suppress_output' => 
          array (
            'name' => 'suppress_output',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 692,
                'endLine' => 692,
                'startTokenPos' => 4043,
                'startFilePos' => 21556,
                'endTokenPos' => 4043,
                'endFilePos' => 21559,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 692,
            'endLine' => 692,
            'startColumn' => 46,
            'endColumn' => 68,
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
        'startLine' => 692,
        'endLine' => 723,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'register_email_send' => 
      array (
        'name' => 'register_email_send',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 725,
                'endLine' => 725,
                'startTokenPos' => 4284,
                'startFilePos' => 22601,
                'endTokenPos' => 4284,
                'endFilePos' => 22605,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 725,
            'endLine' => 725,
            'startColumn' => 41,
            'endColumn' => 56,
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
        'startLine' => 725,
        'endLine' => 783,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'csrf_validate' => 
      array (
        'name' => 'csrf_validate',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 785,
            'endLine' => 785,
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
        'docComment' => NULL,
        'startLine' => 785,
        'endLine' => 800,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'hash_pass' => 
      array (
        'name' => 'hash_pass',
        'parameters' => 
        array (
          'pass' => 
          array (
            'name' => 'pass',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 802,
            'endLine' => 802,
            'startColumn' => 31,
            'endColumn' => 35,
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
        'startLine' => 802,
        'endLine' => 807,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
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
            'startLine' => 839,
            'endLine' => 839,
            'startColumn' => 26,
            'endColumn' => 32,
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
        'startLine' => 839,
        'endLine' => 1041,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'login_set_attempt' => 
      array (
        'name' => 'login_set_attempt',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'array()',
              'attributes' => 
              array (
                'startLine' => 1043,
                'endLine' => 1043,
                'startTokenPos' => 6703,
                'startFilePos' => 35436,
                'endTokenPos' => 6705,
                'endFilePos' => 35442,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1043,
            'endLine' => 1043,
            'startColumn' => 39,
            'endColumn' => 55,
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
        'startLine' => 1043,
        'endLine' => 1089,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'login_set_success_attempt' => 
      array (
        'name' => 'login_set_success_attempt',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'array()',
              'attributes' => 
              array (
                'startLine' => 1091,
                'endLine' => 1091,
                'startTokenPos' => 7080,
                'startFilePos' => 37177,
                'endTokenPos' => 7082,
                'endFilePos' => 37183,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1091,
            'endLine' => 1091,
            'startColumn' => 47,
            'endColumn' => 63,
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
        'startLine' => 1091,
        'endLine' => 1098,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'login_set_failed_attempt' => 
      array (
        'name' => 'login_set_failed_attempt',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'array()',
              'attributes' => 
              array (
                'startLine' => 1100,
                'endLine' => 1100,
                'startTokenPos' => 7151,
                'startFilePos' => 37500,
                'endTokenPos' => 7153,
                'endFilePos' => 37506,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1100,
            'endLine' => 1100,
            'startColumn' => 46,
            'endColumn' => 62,
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
        'startLine' => 1100,
        'endLine' => 1107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1109,
                'endLine' => 1109,
                'startTokenPos' => 7222,
                'startFilePos' => 37802,
                'endTokenPos' => 7222,
                'endFilePos' => 37806,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1109,
            'endLine' => 1109,
            'startColumn' => 25,
            'endColumn' => 39,
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
        'startLine' => 1109,
        'endLine' => 1124,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'get_by_email' => 
      array (
        'name' => 'get_by_email',
        'parameters' => 
        array (
          'email' => 
          array (
            'name' => 'email',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1126,
            'endLine' => 1126,
            'startColumn' => 34,
            'endColumn' => 39,
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
        'startLine' => 1126,
        'endLine' => 1137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'get_by_username' => 
      array (
        'name' => 'get_by_username',
        'parameters' => 
        array (
          'username' => 
          array (
            'name' => 'username',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1139,
            'endLine' => 1139,
            'startColumn' => 37,
            'endColumn' => 45,
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
        'startLine' => 1139,
        'endLine' => 1150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1152,
            'endLine' => 1152,
            'startColumn' => 28,
            'endColumn' => 32,
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
        'startLine' => 1152,
        'endLine' => 1172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'reset_password_from_link' => 
      array (
        'name' => 'reset_password_from_link',
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
            'startLine' => 1174,
            'endLine' => 1174,
            'startColumn' => 46,
            'endColumn' => 52,
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
        'startLine' => 1174,
        'endLine' => 1250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'session_end' => 
      array (
        'name' => 'session_end',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1252,
        'endLine' => 1256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'send_forgot_password' => 
      array (
        'name' => 'send_forgot_password',
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
            'startLine' => 1258,
            'endLine' => 1258,
            'startColumn' => 42,
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
        'startLine' => 1258,
        'endLine' => 1281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'social_login' => 
      array (
        'name' => 'social_login',
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
            'startLine' => 1283,
            'endLine' => 1283,
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
        'startLine' => 1283,
        'endLine' => 1323,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'make_logged' => 
      array (
        'name' => 'make_logged',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1325,
            'endLine' => 1325,
            'startColumn' => 33,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'remember' => 
          array (
            'name' => 'remember',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1325,
                'endLine' => 1325,
                'startTokenPos' => 8838,
                'startFilePos' => 44381,
                'endTokenPos' => 8838,
                'endFilePos' => 44385,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1325,
            'endLine' => 1325,
            'startColumn' => 43,
            'endColumn' => 59,
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
        'startLine' => 1325,
        'endLine' => 1374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'get_by_id' => 
      array (
        'name' => 'get_by_id',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1385,
            'endLine' => 1385,
            'startColumn' => 31,
            'endColumn' => 33,
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
 * Generic function to get the user by id.
 * Uses the getUsers function to get the data.
 *
 * @param
 *            int id
 *
 * @return array
 */',
        'startLine' => 1385,
        'endLine' => 1399,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'update_last_login_time' => 
      array (
        'name' => 'update_last_login_time',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1401,
        'endLine' => 1416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'social_login_process' => 
      array (
        'name' => 'social_login_process',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1418,
                'endLine' => 1418,
                'startTokenPos' => 9439,
                'startFilePos' => 47246,
                'endTokenPos' => 9439,
                'endFilePos' => 47250,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1418,
            'endLine' => 1418,
            'startColumn' => 42,
            'endColumn' => 56,
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
        'startLine' => 1418,
        'endLine' => 1556,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'count' => 
      array (
        'name' => 'count',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1558,
        'endLine' => 1566,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'get_all' => 
      array (
        'name' => 'get_all',
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
            'startLine' => 1581,
            'endLine' => 1581,
            'startColumn' => 29,
            'endColumn' => 35,
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
 * @function get_users
 *
 * @param $params array|string;
 * @params $params[\'username\'] string username for user
 * @params $params[\'email\'] string email for user
 * @params $params[\'password\'] string password for user
 *
 *
 * @usage $this->get_all(\'email=my_email\');
 *
 * @return array of users;
 */',
        'startLine' => 1581,
        'endLine' => 1617,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'register_url' => 
      array (
        'name' => 'register_url',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1619,
        'endLine' => 1642,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'logout_url' => 
      array (
        'name' => 'logout_url',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1644,
        'endLine' => 1669,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'login_url' => 
      array (
        'name' => 'login_url',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1671,
        'endLine' => 1694,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'profile_url' => 
      array (
        'name' => 'profile_url',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1696,
        'endLine' => 1719,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'forgot_password_url' => 
      array (
        'name' => 'forgot_password_url',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1721,
        'endLine' => 1741,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'session_set' => 
      array (
        'name' => 'session_set',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1743,
            'endLine' => 1743,
            'startColumn' => 33,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'val' => 
          array (
            'name' => 'val',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1743,
            'endLine' => 1743,
            'startColumn' => 40,
            'endColumn' => 43,
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
        'startLine' => 1743,
        'endLine' => 1748,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'csrf_form' => 
      array (
        'name' => 'csrf_form',
        'parameters' => 
        array (
          'unique_form_name' => 
          array (
            'name' => 'unique_form_name',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1750,
                'endLine' => 1750,
                'startTokenPos' => 11697,
                'startFilePos' => 57498,
                'endTokenPos' => 11697,
                'endFilePos' => 57502,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1750,
            'endLine' => 1750,
            'startColumn' => 31,
            'endColumn' => 55,
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
        'startLine' => 1750,
        'endLine' => 1761,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'session_all' => 
      array (
        'name' => 'session_all',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1763,
        'endLine' => 1768,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'session_id' => 
      array (
        'name' => 'session_id',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1770,
        'endLine' => 1773,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'session_get' => 
      array (
        'name' => 'session_get',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1775,
            'endLine' => 1775,
            'startColumn' => 33,
            'endColumn' => 37,
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
        'startLine' => 1775,
        'endLine' => 1780,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'session_del' => 
      array (
        'name' => 'session_del',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1782,
            'endLine' => 1782,
            'startColumn' => 33,
            'endColumn' => 37,
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
        'startLine' => 1782,
        'endLine' => 1785,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'csrf_token' => 
      array (
        'name' => 'csrf_token',
        'parameters' => 
        array (
          'unique_form_name' => 
          array (
            'name' => 'unique_form_name',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1787,
                'endLine' => 1787,
                'startTokenPos' => 11869,
                'startFilePos' => 58204,
                'endTokenPos' => 11869,
                'endFilePos' => 58208,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1787,
            'endLine' => 1787,
            'startColumn' => 32,
            'endColumn' => 56,
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
        'startLine' => 1787,
        'endLine' => 1790,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'socialite_config' => 
      array (
        'name' => 'socialite_config',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1792,
                'endLine' => 1792,
                'startTokenPos' => 11893,
                'startFilePos' => 58302,
                'endTokenPos' => 11893,
                'endFilePos' => 58306,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1792,
            'endLine' => 1792,
            'startColumn' => 38,
            'endColumn' => 54,
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
        'startLine' => 1792,
        'endLine' => 1845,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'terms_accept' => 
      array (
        'name' => 'terms_accept',
        'parameters' => 
        array (
          'tos_name' => 
          array (
            'name' => 'tos_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1847,
            'endLine' => 1847,
            'startColumn' => 34,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'user_id_or_email' => 
          array (
            'name' => 'user_id_or_email',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1847,
                'endLine' => 1847,
                'startTokenPos' => 12412,
                'startFilePos' => 61179,
                'endTokenPos' => 12412,
                'endFilePos' => 61183,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1847,
            'endLine' => 1847,
            'startColumn' => 45,
            'endColumn' => 69,
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
        'startLine' => 1847,
        'endLine' => 1852,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'terms_check' => 
      array (
        'name' => 'terms_check',
        'parameters' => 
        array (
          'tos_name' => 
          array (
            'name' => 'tos_name',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1854,
                'endLine' => 1854,
                'startTokenPos' => 12453,
                'startFilePos' => 61342,
                'endTokenPos' => 12453,
                'endFilePos' => 61346,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1854,
            'endLine' => 1854,
            'startColumn' => 33,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'user_id_or_email' => 
          array (
            'name' => 'user_id_or_email',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1854,
                'endLine' => 1854,
                'startTokenPos' => 12460,
                'startFilePos' => 61369,
                'endTokenPos' => 12460,
                'endFilePos' => 61373,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1854,
            'endLine' => 1854,
            'startColumn' => 52,
            'endColumn' => 76,
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
        'startLine' => 1854,
        'endLine' => 1860,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      'get_shipping_address' => 
      array (
        'name' => 'get_shipping_address',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1863,
        'endLine' => 1898,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'aliasName' => NULL,
      ),
      '__check_id_has_ability_to_edit_user' => 
      array (
        'name' => '__check_id_has_ability_to_edit_user',
        'parameters' => 
        array (
          'user_id' => 
          array (
            'name' => 'user_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1900,
            'endLine' => 1900,
            'startColumn' => 58,
            'endColumn' => 65,
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
        'startLine' => 1900,
        'endLine' => 1925,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\UserManager',
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