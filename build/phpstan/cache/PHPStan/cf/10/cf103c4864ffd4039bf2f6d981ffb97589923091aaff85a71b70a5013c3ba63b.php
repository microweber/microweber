<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Services/GoogleTrendsService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\AiTools\Services\GoogleTrendsService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-af4b846b580f47f70161059d7efebb7e885253834608c6251c603529b93e7f8e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/AiTools/Services/GoogleTrendsService.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\AiTools\\Services',
    'name' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
    'shortName' => 'GoogleTrendsService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Google Trends Service
 *
 * Fetches trending search queries and data from Google Trends API.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 537,
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
      'GENERAL_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'GENERAL_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/explore\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 46,
            'startFilePos' => 362,
            'endTokenPos' => 46,
            'endFilePos' => 407,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 84,
      ),
      'INTEREST_OVER_TIME_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'INTEREST_OVER_TIME_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/widgetdata/multiline\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 57,
            'startFilePos' => 458,
            'endTokenPos' => 57,
            'endFilePos' => 516,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 108,
      ),
      'RELATED_QUERIES_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'RELATED_QUERIES_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/widgetdata/relatedsearches\'',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 68,
            'startFilePos' => 564,
            'endTokenPos' => 68,
            'endFilePos' => 628,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 111,
      ),
      'SUGGESTIONS_AUTOCOMPLETE_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'SUGGESTIONS_AUTOCOMPLETE_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/autocomplete\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 79,
            'startFilePos' => 685,
            'endTokenPos' => 79,
            'endFilePos' => 735,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 106,
      ),
      'COMPARED_GEO_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'COMPARED_GEO_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/widgetdata/comparedgeo\'',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 90,
            'startFilePos' => 780,
            'endTokenPos' => 90,
            'endFilePos' => 840,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 104,
      ),
      'CATEGORIES_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'CATEGORIES_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/explore/pickers/category\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 101,
            'startFilePos' => 883,
            'endTokenPos' => 101,
            'endFilePos' => 945,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 104,
      ),
      'GEO_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'GEO_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/explore/pickers/geo\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 112,
            'startFilePos' => 981,
            'endTokenPos' => 112,
            'endFilePos' => 1038,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 92,
      ),
      'DAILY_SEARCH_TRENDS_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'DAILY_SEARCH_TRENDS_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/dailytrends\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 123,
            'startFilePos' => 1090,
            'endTokenPos' => 123,
            'endFilePos' => 1139,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 100,
      ),
      'REAL_TIME_SEARCH_TRENDS_ENDPOINT' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'REAL_TIME_SEARCH_TRENDS_ENDPOINT',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'https://trends.google.com/trends/api/realtimetrends\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 134,
            'startFilePos' => 1195,
            'endTokenPos' => 134,
            'endFilePos' => 1247,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 107,
      ),
    ),
    'immediateProperties' => 
    array (
      'options' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'options',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[\'hl\' => \'en-US\', \'tz\' => 0, \'geo\' => \'US\', \'time\' => \'today 12-m\', \'category\' => 0]',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 34,
            'startTokenPos' => 145,
            'startFilePos' => 1280,
            'endTokenPos' => 182,
            'endFilePos' => 1410,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'proxyConfigs' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'name' => 'proxyConfigs',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 194,
            'startFilePos' => 1449,
            'endTokenPos' => 194,
            'endFilePos' => 1452,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 40,
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
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 209,
                'startFilePos' => 1505,
                'endTokenPos' => 210,
                'endFilePos' => 1506,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 33,
            'endColumn' => 51,
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
        'startLine' => 38,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getTrendingQueries' => 
      array (
        'name' => 'getTrendingQueries',
        'parameters' => 
        array (
          'keyword' => 
          array (
            'name' => 'keyword',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 48,
                'endLine' => 48,
                'startTokenPos' => 254,
                'startFilePos' => 1744,
                'endTokenPos' => 255,
                'endFilePos' => 1745,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 57,
            'endColumn' => 75,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get trending search queries for a specific keyword
 */',
        'startLine' => 48,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getDailySearchTrends' => 
      array (
        'name' => 'getDailySearchTrends',
        'parameters' => 
        array (
          'ns' => 
          array (
            'name' => 'ns',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 494,
                'startFilePos' => 3023,
                'endTokenPos' => 494,
                'endFilePos' => 3024,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get daily search trends
 */',
        'startLine' => 84,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getRealTimeSearchTrends' => 
      array (
        'name' => 'getRealTimeSearchTrends',
        'parameters' => 
        array (
          'cat' => 
          array (
            'name' => 'cat',
            'default' => 
            array (
              'code' => '\'all\'',
              'attributes' => 
              array (
                'startLine' => 108,
                'endLine' => 108,
                'startTokenPos' => 686,
                'startFilePos' => 3771,
                'endTokenPos' => 686,
                'endFilePos' => 3775,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 108,
            'endLine' => 108,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'fi' => 
          array (
            'name' => 'fi',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 695,
                'startFilePos' => 3796,
                'endTokenPos' => 695,
                'endFilePos' => 3796,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'fs' => 
          array (
            'name' => 'fs',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 110,
                'endLine' => 110,
                'startTokenPos' => 704,
                'startFilePos' => 3817,
                'endTokenPos' => 704,
                'endFilePos' => 3817,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 110,
            'endLine' => 110,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'ri' => 
          array (
            'name' => 'ri',
            'default' => 
            array (
              'code' => '300',
              'attributes' => 
              array (
                'startLine' => 111,
                'endLine' => 111,
                'startTokenPos' => 713,
                'startFilePos' => 3838,
                'endTokenPos' => 713,
                'endFilePos' => 3840,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 9,
            'endColumn' => 21,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'rs' => 
          array (
            'name' => 'rs',
            'default' => 
            array (
              'code' => '20',
              'attributes' => 
              array (
                'startLine' => 112,
                'endLine' => 112,
                'startTokenPos' => 722,
                'startFilePos' => 3861,
                'endTokenPos' => 722,
                'endFilePos' => 3862,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'sort' => 
          array (
            'name' => 'sort',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 113,
                'endLine' => 113,
                'startTokenPos' => 731,
                'startFilePos' => 3885,
                'endTokenPos' => 731,
                'endFilePos' => 3885,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 9,
            'endColumn' => 21,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get real-time search trends
 */',
        'startLine' => 107,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getSuggestionsAutocomplete' => 
      array (
        'name' => 'getSuggestionsAutocomplete',
        'parameters' => 
        array (
          'keyword' => 
          array (
            'name' => 'keyword',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 48,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get autocomplete suggestions for a keyword
 */',
        'startLine' => 140,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getRelatedQueries' => 
      array (
        'name' => 'getRelatedQueries',
        'parameters' => 
        array (
          'keyword' => 
          array (
            'name' => 'keyword',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 159,
            'endLine' => 159,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 159,
            'endLine' => 159,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get related queries for a keyword
 */',
        'startLine' => 159,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getExploreToken' => 
      array (
        'name' => 'getExploreToken',
        'parameters' => 
        array (
          'keyword' => 
          array (
            'name' => 'keyword',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 38,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 55,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get explore token for a keyword
 */',
        'startLine' => 183,
        'endLine' => 225,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'fetchRelatedQueriesWithToken' => 
      array (
        'name' => 'fetchRelatedQueriesWithToken',
        'parameters' => 
        array (
          'token' => 
          array (
            'name' => 'token',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 230,
            'endLine' => 230,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Fetch related queries using token
 */',
        'startLine' => 230,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'formatTrendingQueries' => 
      array (
        'name' => 'formatTrendingQueries',
        'parameters' => 
        array (
          'relatedQueries' => 
          array (
            'name' => 'relatedQueries',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 304,
            'endLine' => 304,
            'startColumn' => 44,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'suggestions' => 
          array (
            'name' => 'suggestions',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 304,
            'endLine' => 304,
            'startColumn' => 67,
            'endColumn' => 84,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'originalKeyword' => 
          array (
            'name' => 'originalKeyword',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 304,
            'endLine' => 304,
            'startColumn' => 87,
            'endColumn' => 109,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Format trending queries into a standardized structure
 */',
        'startLine' => 304,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'calculateRelevanceScore' => 
      array (
        'name' => 'calculateRelevanceScore',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 351,
            'endLine' => 351,
            'startColumn' => 46,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'originalKeyword' => 
          array (
            'name' => 'originalKeyword',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 351,
            'endLine' => 351,
            'startColumn' => 61,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Calculate relevance score based on keyword similarity
 */',
        'startLine' => 351,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'isDuplicate' => 
      array (
        'name' => 'isDuplicate',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'existingQueries' => 
          array (
            'name' => 'existingQueries',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 49,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if a query is already in the results
 */',
        'startLine' => 388,
        'endLine' => 399,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getData' => 
      array (
        'name' => 'getData',
        'parameters' => 
        array (
          'url' => 
          array (
            'name' => 'url',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 404,
            'endLine' => 404,
            'startColumn' => 30,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 404,
            'endLine' => 404,
            'startColumn' => 43,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Make HTTP request to Google Trends API
 */',
        'startLine' => 404,
        'endLine' => 441,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'generateProductQueries' => 
      array (
        'name' => 'generateProductQueries',
        'parameters' => 
        array (
          'trendingQueries' => 
          array (
            'name' => 'trendingQueries',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 446,
            'endLine' => 446,
            'startColumn' => 44,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'productCategories' => 
          array (
            'name' => 'productCategories',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 446,
                'endLine' => 446,
                'startTokenPos' => 3056,
                'startFilePos' => 15160,
                'endTokenPos' => 3057,
                'endFilePos' => 15161,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 446,
            'endLine' => 446,
            'startColumn' => 68,
            'endColumn' => 96,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generate product search queries from trending data
 */',
        'startLine' => 446,
        'endLine' => 497,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'isProductFocused' => 
      array (
        'name' => 'isProductFocused',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 502,
            'endLine' => 502,
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if a query is product-focused
 */',
        'startLine' => 502,
        'endLine' => 519,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'getOptions' => 
      array (
        'name' => 'getOptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 521,
        'endLine' => 524,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'setOptions' => 
      array (
        'name' => 'setOptions',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 526,
            'endLine' => 526,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 526,
        'endLine' => 530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'aliasName' => NULL,
      ),
      'setProxyConfigs' => 
      array (
        'name' => 'setProxyConfigs',
        'parameters' => 
        array (
          'proxy' => 
          array (
            'name' => 'proxy',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 532,
            'endLine' => 532,
            'startColumn' => 37,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 532,
        'endLine' => 536,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\AiTools\\Services',
        'declaringClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'implementingClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
        'currentClassName' => 'MicroweberPackages\\AiTools\\Services\\GoogleTrendsService',
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