<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Content/Support/PagingLinks.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Content\Support\PagingLinks
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-87aa38b87290960efda63b287a976c59e37964d7efff2b8718edb7551da9bbac',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Content\\Support\\PagingLinks',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Content/Support/PagingLinks.php',
      ),
    ),
    'namespace' => 'Modules\\Content\\Support',
    'name' => 'Modules\\Content\\Support\\PagingLinks',
    'shortName' => 'PagingLinks',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 113,
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
        'declaringClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'implementingClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'name' => 'app',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var \\MicroweberPackages\\App\\LaravelApplication */',
        'attributes' => 
        array (
        ),
        'startLine' => 8,
        'endLine' => 8,
        'startColumn' => 5,
        'endColumn' => 16,
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
                'startLine' => 10,
                'endLine' => 10,
                'startTokenPos' => 30,
                'startFilePos' => 179,
                'endTokenPos' => 30,
                'endFilePos' => 182,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 10,
            'endLine' => 10,
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
        'startLine' => 10,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Support',
        'declaringClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'implementingClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'currentClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'base_url' => 
          array (
            'name' => 'base_url',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 19,
                'endLine' => 19,
                'startTokenPos' => 86,
                'startFilePos' => 354,
                'endTokenPos' => 86,
                'endFilePos' => 358,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 25,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'pages_count' => 
          array (
            'name' => 'pages_count',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 19,
                'endLine' => 19,
                'startTokenPos' => 93,
                'startFilePos' => 376,
                'endTokenPos' => 93,
                'endFilePos' => 380,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 44,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'paging_param' => 
          array (
            'name' => 'paging_param',
            'default' => 
            array (
              'code' => '\'current_page\'',
              'attributes' => 
              array (
                'startLine' => 19,
                'endLine' => 19,
                'startTokenPos' => 100,
                'startFilePos' => 399,
                'endTokenPos' => 100,
                'endFilePos' => 412,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 66,
            'endColumn' => 95,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'keyword_param' => 
          array (
            'name' => 'keyword_param',
            'default' => 
            array (
              'code' => '\'keyword\'',
              'attributes' => 
              array (
                'startLine' => 19,
                'endLine' => 19,
                'startTokenPos' => 107,
                'startFilePos' => 432,
                'endTokenPos' => 107,
                'endFilePos' => 440,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 19,
            'endLine' => 19,
            'startColumn' => 98,
            'endColumn' => 123,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 19,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Support',
        'declaringClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'implementingClassName' => 'Modules\\Content\\Support\\PagingLinks',
        'currentClassName' => 'Modules\\Content\\Support\\PagingLinks',
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