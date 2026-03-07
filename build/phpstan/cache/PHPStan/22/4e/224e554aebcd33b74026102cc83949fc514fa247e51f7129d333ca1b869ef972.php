<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Pagination/CursorPaginator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Contracts\Pagination\CursorPaginator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cd7f53ff74df5877feb36a4032dc3a1df60d2eb0973943e7ea0723011d87f0d8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Contracts/Pagination/CursorPaginator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Contracts\\Pagination',
    'name' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
    'shortName' => 'CursorPaginator',
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
          'cursor' => 
          array (
            'name' => 'cursor',
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
            'endColumn' => 31,
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
 * Get the URL for a given cursor.
 *
 * @param  \\Illuminate\\Pagination\\Cursor|null  $cursor
 * @return string
 */',
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
                'startFilePos' => 548,
                'endTokenPos' => 42,
                'endFilePos' => 551,
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
                'startFilePos' => 749,
                'endTokenPos' => 58,
                'endFilePos' => 752,
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'aliasName' => NULL,
      ),
      'withQueryString' => 
      array (
        'name' => 'withQueryString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add all current query string values to the paginator.
 *
 * @return $this
 */',
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 28,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'aliasName' => NULL,
      ),
      'previousCursor' => 
      array (
        'name' => 'previousCursor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "cursor" of the previous set of items.
 *
 * @return \\Illuminate\\Pagination\\Cursor|null
 */',
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'aliasName' => NULL,
      ),
      'nextCursor' => 
      array (
        'name' => 'nextCursor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "cursor" of the next set of items.
 *
 * @return \\Illuminate\\Pagination\\Cursor|null
 */',
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 33,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 30,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'aliasName' => NULL,
      ),
      'cursor' => 
      array (
        'name' => 'cursor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current cursor being paginated.
 *
 * @return \\Illuminate\\Pagination\\Cursor|null
 */',
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 29,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 31,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Contracts\\Pagination',
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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
                'startFilePos' => 2645,
                'endTokenPos' => 206,
                'endFilePos' => 2648,
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
                'startFilePos' => 2659,
                'endTokenPos' => 214,
                'endFilePos' => 2660,
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
        'declaringClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'implementingClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
        'currentClassName' => 'Illuminate\\Contracts\\Pagination\\CursorPaginator',
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