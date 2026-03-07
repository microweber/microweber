<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Services/TosManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\User\Services\TosManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-897d70655a1e22ae159044d420597a8e4019fff6922f4f4741ef33db928af336',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\User\\Services\\TosManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/User/Services/TosManager.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\User\\Services',
    'name' => 'MicroweberPackages\\User\\Services\\TosManager',
    'shortName' => 'TosManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 6,
    'endLine' => 83,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\Database\\Crud',
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
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'name' => 'app',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var \\MicroweberPackages\\App\\LaravelApplication */',
        'attributes' => 
        array (
        ),
        'startLine' => 9,
        'endLine' => 9,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'name' => 'table',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'terms_accept_log\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 34,
            'startFilePos' => 219,
            'endTokenPos' => 34,
            'endFilePos' => 236,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
        'startColumn' => 5,
        'endColumn' => 39,
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
                'startLine' => 13,
                'endLine' => 13,
                'startTokenPos' => 47,
                'startFilePos' => 279,
                'endTokenPos' => 47,
                'endFilePos' => 282,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 13,
            'endLine' => 13,
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
        'startLine' => 13,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
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
            'startLine' => 22,
            'endLine' => 22,
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
                'startLine' => 22,
                'endLine' => 22,
                'startTokenPos' => 106,
                'startFilePos' => 482,
                'endTokenPos' => 106,
                'endFilePos' => 486,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
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
        'startLine' => 22,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
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
                'startLine' => 53,
                'endLine' => 53,
                'startTokenPos' => 300,
                'startFilePos' => 1288,
                'endTokenPos' => 300,
                'endFilePos' => 1292,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
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
                'startLine' => 53,
                'endLine' => 53,
                'startTokenPos' => 307,
                'startFilePos' => 1315,
                'endTokenPos' => 307,
                'endFilePos' => 1319,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 53,
            'endLine' => 53,
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
        'startLine' => 53,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\User\\Services',
        'declaringClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'implementingClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
        'currentClassName' => 'MicroweberPackages\\User\\Services\\TosManager',
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