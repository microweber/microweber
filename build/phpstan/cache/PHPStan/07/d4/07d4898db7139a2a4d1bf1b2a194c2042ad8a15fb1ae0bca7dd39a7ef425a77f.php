<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Frontend/Http/Controllers/ServeStaticFileContoller.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Frontend\Http\Controllers\ServeStaticFileContoller
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-fc56595621dd3bf92747051171761a45c2b11b3a877eae330c4daebe2c982826',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Frontend/Http/Controllers/ServeStaticFileContoller.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Frontend\\Http\\Controllers',
    'name' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
    'shortName' => 'ServeStaticFileContoller',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 93,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Routing\\Controller',
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
      'skip_ext' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'implementingClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'name' => 'skip_ext',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'php\', \'phtml\', \'php7\', \'.htaccess\', \'.env\']',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 43,
            'startFilePos' => 256,
            'endTokenPos' => 57,
            'endFilePos' => 300,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 69,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'inline_disposition' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'implementingClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'name' => 'inline_disposition',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'pdf\', \'docx\', \'doc\', \'xls\']',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 66,
            'startFilePos' => 336,
            'endTokenPos' => 77,
            'endFilePos' => 364,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 63,
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
      'serveFromUserfiles' => 
      array (
        'name' => 'serveFromUserfiles',
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
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 40,
            'endColumn' => 55,
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
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException
 */',
        'startLine' => 19,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Frontend\\Http\\Controllers',
        'declaringClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'implementingClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'currentClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'aliasName' => NULL,
      ),
      'sendResponse' => 
      array (
        'name' => 'sendResponse',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 35,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 42,
            'endColumn' => 49,
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
        'startLine' => 30,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\Frontend\\Http\\Controllers',
        'declaringClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'implementingClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
        'currentClassName' => 'MicroweberPackages\\Frontend\\Http\\Controllers\\ServeStaticFileContoller',
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