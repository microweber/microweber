<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/SiteStats/Models/ContentViewCounter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\SiteStats\Models\ContentViewCounter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-25f6d726d7f3f08eac7ca90b05c982c05d4b005bf286461e1d9e8d8ac84defee',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/SiteStats/Models/ContentViewCounter.php',
      ),
    ),
    'namespace' => 'Modules\\SiteStats\\Models',
    'name' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
    'shortName' => 'ContentViewCounter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 83,
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
      'cacheSeconds' => 
      array (
        'declaringClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'name' => 'cacheSeconds',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '600',
          'attributes' => 
          array (
            'startLine' => 9,
            'endLine' => 9,
            'startTokenPos' => 19,
            'startFilePos' => 100,
            'endTokenPos' => 19,
            'endFilePos' => 102,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 9,
        'endLine' => 9,
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
      'getCountViewsForContent' => 
      array (
        'name' => 'getCountViewsForContent',
        'parameters' => 
        array (
          'contentId' => 
          array (
            'name' => 'contentId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 11,
            'endLine' => 11,
            'startColumn' => 45,
            'endColumn' => 54,
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
        'startLine' => 11,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\SiteStats\\Models',
        'declaringClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'currentClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'aliasName' => NULL,
      ),
      'getMostViewedForContentForPeriod' => 
      array (
        'name' => 'getMostViewedForContentForPeriod',
        'parameters' => 
        array (
          'contentId' => 
          array (
            'name' => 'contentId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 54,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'period' => 
          array (
            'name' => 'period',
            'default' => 
            array (
              'code' => '\'daily\'',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 227,
                'startFilePos' => 1083,
                'endTokenPos' => 227,
                'endFilePos' => 1089,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 66,
            'endColumn' => 82,
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
        'startLine' => 38,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\SiteStats\\Models',
        'declaringClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'currentClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'aliasName' => NULL,
      ),
      'getDateRangeByPeriod' => 
      array (
        'name' => 'getDateRangeByPeriod',
        'parameters' => 
        array (
          'period' => 
          array (
            'name' => 'period',
            'default' => 
            array (
              'code' => '\'daily\'',
              'attributes' => 
              array (
                'startLine' => 55,
                'endLine' => 55,
                'startTokenPos' => 381,
                'startFilePos' => 1904,
                'endTokenPos' => 381,
                'endFilePos' => 1910,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 42,
            'endColumn' => 58,
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
        'startLine' => 55,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\SiteStats\\Models',
        'declaringClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
        'currentClassName' => 'Modules\\SiteStats\\Models\\ContentViewCounter',
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