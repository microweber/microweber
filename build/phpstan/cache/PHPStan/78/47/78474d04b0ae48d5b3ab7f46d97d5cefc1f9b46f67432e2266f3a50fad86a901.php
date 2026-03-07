<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Pagination/Paginator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Pagination\Paginator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-07ffeaed75657e0d8d1a739447ea4a441d071148996d77365492ea96b465aa0b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Pagination/Paginator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Pagination',
    'name' => 'Illuminate\\Contracts\\Pagination\\Paginator',
    'shortName' => 'Paginator',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 129,
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
    ),
    'immediateMethods' => 
    array (
      'url' => 
      array (
        'name' => 'url',
        'parameters' => 
        array (
          'page' => 
          array (
            'name' => 'page',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 18,
            'endLine' => 18,
            'startColumn' => 25,
            'endColumn' => 29,
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
 * Get the URL for a given page.
 *
 * @param  int  $page
 * @return string
 */',
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'appends' => 
      array (
        'name' => 'appends',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 29,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 27,
                'endLine' => 27,
                'startTokenPos' => 42,
                'startFilePos' => 505,
                'endTokenPos' => 42,
                'endFilePos' => 508,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 35,
            'endColumn' => 47,
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
 * Add a set of query string values to the paginator.
 *
 * @param  array|string|null  $key
 * @param  string|null  $value
 * @return $this
 */',
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'fragment' => 
      array (
        'name' => 'fragment',
        'parameters' => 
        array (
          'fragment' => 
          array (
            'name' => 'fragment',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 35,
                'endLine' => 35,
                'startTokenPos' => 58,
                'startFilePos' => 706,
                'endTokenPos' => 58,
                'endFilePos' => 709,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 30,
            'endColumn' => 45,
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
 * Get / set the URL fragment to be appended to URLs.
 *
 * @param  string|null  $fragment
 * @return $this|string|null
 */',
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'nextPageUrl' => 
      array (
        'name' => 'nextPageUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * The URL for the next page, or null.
 *
 * @return string|null
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'previousPageUrl' => 
      array (
        'name' => 'previousPageUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the URL for the previous page, or null.
 *
 * @return string|null
 */',
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'items' => 
      array (
        'name' => 'items',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the items being paginated.
 *
 * @return array<TKey, TValue>
 */',
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'firstItem' => 
      array (
        'name' => 'firstItem',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "index" of the first item being paginated.
 *
 * @return int|null
 */',
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'lastItem' => 
      array (
        'name' => 'lastItem',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "index" of the last item being paginated.
 *
 * @return int|null
 */',
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'perPage' => 
      array (
        'name' => 'perPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine how many items are being shown per page.
 *
 * @return int
 */',
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'currentPage' => 
      array (
        'name' => 'currentPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine the current page being paginated.
 *
 * @return int
 */',
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'hasPages' => 
      array (
        'name' => 'hasPages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if there are enough items to split into multiple pages.
 *
 * @return bool
 */',
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'hasMorePages' => 
      array (
        'name' => 'hasMorePages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if there are more items in the data store.
 *
 * @return bool
 */',
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'path' => 
      array (
        'name' => 'path',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the base path for paginator generated URLs.
 *
 * @return string|null
 */',
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 27,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'isEmpty' => 
      array (
        'name' => 'isEmpty',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the list of items is empty or not.
 *
 * @return bool
 */',
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'isNotEmpty' => 
      array (
        'name' => 'isNotEmpty',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the list of items is not empty.
 *
 * @return bool
 */',
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
          'view' => 
          array (
            'name' => 'view',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 128,
                'endLine' => 128,
                'startTokenPos' => 206,
                'startFilePos' => 2527,
                'endTokenPos' => 206,
                'endFilePos' => 2530,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 28,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 128,
                'endLine' => 128,
                'startTokenPos' => 213,
                'startFilePos' => 2541,
                'endTokenPos' => 214,
                'endFilePos' => 2542,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 42,
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
 * Render the paginator using a given view.
 *
 * @param  string|null  $view
 * @param  array  $data
 * @return string
 */',
        'startLine' => 128,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\Paginator',
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