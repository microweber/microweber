<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/FileManager/Http/Controllers/Api/FileManagerApiController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\FileManager\Http\Controllers\Api\FileManagerApiController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-5a43bc94b99bf58b0c4670300ab42685b6b0192efd83fb88862aeb94aa6cf2cb',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/FileManager/Http/Controllers/Api/FileManagerApiController.php',
      ),
    ),
    'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
    'name' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
    'shortName' => 'FileManagerApiController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 362,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\App\\Http\\Controllers\\Controller',
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
      'onDisk' => 
      array (
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'name' => 'onDisk',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'public\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 62,
            'startFilePos' => 448,
            'endTokenPos' => 62,
            'endFilePos' => 455,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 30,
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
      'list' => 
      array (
        'name' => 'list',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 26,
            'endColumn' => 41,
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
        'startLine' => 17,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'currentClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'aliasName' => NULL,
      ),
      'paginateArray' => 
      array (
        'name' => 'paginateArray',
        'parameters' => 
        array (
          'items' => 
          array (
            'name' => 'items',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '50',
              'attributes' => 
              array (
                'startLine' => 193,
                'endLine' => 193,
                'startTokenPos' => 1325,
                'startFilePos' => 6424,
                'endTokenPos' => 1325,
                'endFilePos' => 6425,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 43,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'page' => 
          array (
            'name' => 'page',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 193,
                'endLine' => 193,
                'startTokenPos' => 1332,
                'startFilePos' => 6436,
                'endTokenPos' => 1332,
                'endFilePos' => 6439,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 58,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 193,
                'endLine' => 193,
                'startTokenPos' => 1339,
                'startFilePos' => 6453,
                'endTokenPos' => 1340,
                'endFilePos' => 6454,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 193,
            'endLine' => 193,
            'startColumn' => 72,
            'endColumn' => 84,
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
 * The attributes that are mass assignable.
 *
 * @var array
 */',
        'startLine' => 193,
        'endLine' => 198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'currentClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'aliasName' => NULL,
      ),
      'rename' => 
      array (
        'name' => 'rename',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 26,
            'endColumn' => 41,
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
        'startLine' => 200,
        'endLine' => 235,
        'startColumn' => 3,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'currentClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 28,
            'endColumn' => 43,
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
        'startLine' => 237,
        'endLine' => 283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'currentClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'aliasName' => NULL,
      ),
      'createFolder' => 
      array (
        'name' => 'createFolder',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 285,
            'endLine' => 285,
            'startColumn' => 34,
            'endColumn' => 49,
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
        'startLine' => 285,
        'endLine' => 341,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'currentClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'aliasName' => NULL,
      ),
      'pathAutoCleanString' => 
      array (
        'name' => 'pathAutoCleanString',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 343,
            'endLine' => 343,
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
        'startLine' => 343,
        'endLine' => 361,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Modules\\FileManager\\Http\\Controllers\\Api',
        'declaringClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'implementingClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
        'currentClassName' => 'Modules\\FileManager\\Http\\Controllers\\Api\\FileManagerApiController',
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