<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Pagination/AbstractPaginator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Pagination\AbstractPaginator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-54e43a5d3f334a3faab0f24ab4cc1193f73c801b6bcd875634640825d15d241f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Pagination\\AbstractPaginator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Pagination/AbstractPaginator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Pagination',
    'name' => 'Illuminate\\Pagination\\AbstractPaginator',
    'shortName' => 'AbstractPaginator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @mixin \\Illuminate\\Support\\Collection<TKey, TValue>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 802,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\Htmlable',
      1 => 'Stringable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\ForwardsCalls',
      1 => 'Illuminate\\Support\\Traits\\Tappable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'items' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'items',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * All of the items being paginated.
 *
 * @var \\Illuminate\\Support\\Collection<TKey, TValue>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'perPage' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'perPage',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The number of items to be shown per page.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'currentPage' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'currentPage',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The current page being "viewed".
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'path' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'path',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'/\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 101,
            'startFilePos' => 983,
            'endTokenPos' => 101,
            'endFilePos' => 985,
          ),
        ),
        'docComment' => '/**
 * The base path to assign to all URLs.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'query' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'query',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 112,
            'startFilePos' => 1101,
            'endTokenPos' => 113,
            'endFilePos' => 1102,
          ),
        ),
        'docComment' => '/**
 * The query parameters to add to all URLs.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fragment' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'fragment',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The URL fragment to add to all URLs.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'pageName' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'pageName',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'page\'',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 131,
            'startFilePos' => 1348,
            'endTokenPos' => 131,
            'endFilePos' => 1353,
          ),
        ),
        'docComment' => '/**
 * The query string variable used to store the page.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'onEachSide' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'onEachSide',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '3',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 142,
            'startFilePos' => 1494,
            'endTokenPos' => 142,
            'endFilePos' => 1494,
          ),
        ),
        'docComment' => '/**
 * The number of links to display on each side of current page link.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'options' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'options',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The paginator options.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'currentPathResolver' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'currentPathResolver',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The current path resolver callback.
 *
 * @var \\Closure
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'currentPageResolver' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'currentPageResolver',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The current page resolver callback.
 *
 * @var \\Closure
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'queryStringResolver' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'queryStringResolver',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The query string resolver callback.
 *
 * @var \\Closure
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 107,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'viewFactoryResolver' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'viewFactoryResolver',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The view factory resolver callback.
 *
 * @var \\Closure
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 114,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultView' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'defaultView',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'pagination::tailwind\'',
          'attributes' => 
          array (
            'startLine' => 121,
            'endLine' => 121,
            'startTokenPos' => 198,
            'startFilePos' => 2229,
            'endTokenPos' => 198,
            'endFilePos' => 2250,
          ),
        ),
        'docComment' => '/**
 * The default pagination view.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 121,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 56,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultSimpleView' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'name' => 'defaultSimpleView',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'pagination::simple-tailwind\'',
          'attributes' => 
          array (
            'startLine' => 128,
            'endLine' => 128,
            'startTokenPos' => 211,
            'startFilePos' => 2380,
            'endTokenPos' => 211,
            'endFilePos' => 2408,
          ),
        ),
        'docComment' => '/**
 * The default "simple" pagination view.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 128,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 69,
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
      'isValidPageNumber' => 
      array (
        'name' => 'isValidPageNumber',
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
            'startLine' => 136,
            'endLine' => 136,
            'startColumn' => 42,
            'endColumn' => 46,
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
 * Determine if the given value is a valid page number.
 *
 * @param  int  $page
 * @return bool
 */',
        'startLine' => 136,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Get the URL for the previous page.
 *
 * @return string|null
 */',
        'startLine' => 146,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'getUrlRange' => 
      array (
        'name' => 'getUrlRange',
        'parameters' => 
        array (
          'start' => 
          array (
            'name' => 'start',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'end' => 
          array (
            'name' => 'end',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 160,
            'endLine' => 160,
            'startColumn' => 41,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a range of pagination URLs.
 *
 * @param  int  $start
 * @param  int  $end
 * @return array
 */',
        'startLine' => 160,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
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
            'startLine' => 173,
            'endLine' => 173,
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
 * Get the URL for a given page number.
 *
 * @param  int  $page
 * @return string
 */',
        'startLine' => 173,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
                'startLine' => 200,
                'endLine' => 200,
                'startTokenPos' => 513,
                'startFilePos' => 4291,
                'endTokenPos' => 513,
                'endFilePos' => 4294,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
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
        'startLine' => 200,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
            'startLine' => 218,
            'endLine' => 218,
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
                'startLine' => 218,
                'endLine' => 218,
                'startTokenPos' => 569,
                'startFilePos' => 4670,
                'endTokenPos' => 569,
                'endFilePos' => 4673,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 218,
            'endLine' => 218,
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
        'startLine' => 218,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'appendArray' => 
      array (
        'name' => 'appendArray',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
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
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 36,
            'endColumn' => 46,
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
 * Add an array of query string values.
 *
 * @param  array  $keys
 * @return $this
 */',
        'startLine' => 237,
        'endLine' => 244,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
        'startLine' => 251,
        'endLine' => 258,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'addQuery' => 
      array (
        'name' => 'addQuery',
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
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 33,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 267,
            'endLine' => 267,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a query string value to the paginator.
 *
 * @param  string  $key
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 267,
        'endLine' => 274,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'buildFragment' => 
      array (
        'name' => 'buildFragment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the full fragment portion of a URL.
 *
 * @return string
 */',
        'startLine' => 281,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'loadMorph' => 
      array (
        'name' => 'loadMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 31,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relations' => 
          array (
            'name' => 'relations',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
            'startColumn' => 42,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Load a set of relationships onto the mixed relationship collection.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @return $this
 */',
        'startLine' => 293,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'loadMorphCount' => 
      array (
        'name' => 'loadMorphCount',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 307,
            'endLine' => 307,
            'startColumn' => 36,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relations' => 
          array (
            'name' => 'relations',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 307,
            'endLine' => 307,
            'startColumn' => 47,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Load a set of relationship counts onto the mixed relationship collection.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @return $this
 */',
        'startLine' => 307,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Get the slice of items being paginated.
 *
 * @return array<TKey, TValue>
 */',
        'startLine' => 319,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Get the number of the first item in the slice.
 *
 * @return int|null
 */',
        'startLine' => 329,
        'endLine' => 332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Get the number of the last item in the slice.
 *
 * @return int|null
 */',
        'startLine' => 339,
        'endLine' => 342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'through' => 
      array (
        'name' => 'through',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 350,
            'endLine' => 350,
            'startColumn' => 29,
            'endColumn' => 46,
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
 * Transform each item in the slice of items using a callback.
 *
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 350,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Get the number of items shown per page.
 *
 * @return int
 */',
        'startLine' => 362,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
        'startLine' => 372,
        'endLine' => 375,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'onFirstPage' => 
      array (
        'name' => 'onFirstPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the paginator is on the first page.
 *
 * @return bool
 */',
        'startLine' => 382,
        'endLine' => 385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'onLastPage' => 
      array (
        'name' => 'onLastPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the paginator is on the last page.
 *
 * @return bool
 */',
        'startLine' => 392,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Get the current page.
 *
 * @return int
 */',
        'startLine' => 402,
        'endLine' => 405,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'getPageName' => 
      array (
        'name' => 'getPageName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the query string variable used to store the page.
 *
 * @return string
 */',
        'startLine' => 412,
        'endLine' => 415,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'setPageName' => 
      array (
        'name' => 'setPageName',
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
            'startLine' => 423,
            'endLine' => 423,
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
        'docComment' => '/**
 * Set the query string variable used to store the page.
 *
 * @param  string  $name
 * @return $this
 */',
        'startLine' => 423,
        'endLine' => 428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'withPath' => 
      array (
        'name' => 'withPath',
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
            'startLine' => 436,
            'endLine' => 436,
            'startColumn' => 30,
            'endColumn' => 34,
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
 * Set the base path to assign to all URLs.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 436,
        'endLine' => 439,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'setPath' => 
      array (
        'name' => 'setPath',
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
            'startLine' => 447,
            'endLine' => 447,
            'startColumn' => 29,
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
 * Set the base path to assign to all URLs.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 447,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'onEachSide' => 
      array (
        'name' => 'onEachSide',
        'parameters' => 
        array (
          'count' => 
          array (
            'name' => 'count',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 32,
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
        'docComment' => '/**
 * Set the number of links to display on each side of current page link.
 *
 * @param  int  $count
 * @return $this
 */',
        'startLine' => 460,
        'endLine' => 465,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
        'startLine' => 472,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'resolveCurrentPath' => 
      array (
        'name' => 'resolveCurrentPath',
        'parameters' => 
        array (
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => '\'/\'',
              'attributes' => 
              array (
                'startLine' => 483,
                'endLine' => 483,
                'startTokenPos' => 1350,
                'startFilePos' => 10067,
                'endTokenPos' => 1350,
                'endFilePos' => 10069,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 483,
            'endLine' => 483,
            'startColumn' => 47,
            'endColumn' => 60,
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
 * Resolve the current request path or return the default value.
 *
 * @param  string  $default
 * @return string
 */',
        'startLine' => 483,
        'endLine' => 490,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'currentPathResolver' => 
      array (
        'name' => 'currentPathResolver',
        'parameters' => 
        array (
          'resolver' => 
          array (
            'name' => 'resolver',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 498,
            'endLine' => 498,
            'startColumn' => 48,
            'endColumn' => 64,
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
 * Set the current request path resolver callback.
 *
 * @param  \\Closure  $resolver
 * @return void
 */',
        'startLine' => 498,
        'endLine' => 501,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'resolveCurrentPage' => 
      array (
        'name' => 'resolveCurrentPage',
        'parameters' => 
        array (
          'pageName' => 
          array (
            'name' => 'pageName',
            'default' => 
            array (
              'code' => '\'page\'',
              'attributes' => 
              array (
                'startLine' => 510,
                'endLine' => 510,
                'startTokenPos' => 1429,
                'startFilePos' => 10722,
                'endTokenPos' => 1429,
                'endFilePos' => 10727,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 47,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 510,
                'endLine' => 510,
                'startTokenPos' => 1436,
                'startFilePos' => 10741,
                'endTokenPos' => 1436,
                'endFilePos' => 10741,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 67,
            'endColumn' => 78,
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
 * Resolve the current page or return the default value.
 *
 * @param  string  $pageName
 * @param  int  $default
 * @return int
 */',
        'startLine' => 510,
        'endLine' => 517,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'currentPageResolver' => 
      array (
        'name' => 'currentPageResolver',
        'parameters' => 
        array (
          'resolver' => 
          array (
            'name' => 'resolver',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
            'startColumn' => 48,
            'endColumn' => 64,
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
 * Set the current page resolver callback.
 *
 * @param  \\Closure  $resolver
 * @return void
 */',
        'startLine' => 525,
        'endLine' => 528,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'resolveQueryString' => 
      array (
        'name' => 'resolveQueryString',
        'parameters' => 
        array (
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 536,
                'endLine' => 536,
                'startTokenPos' => 1520,
                'startFilePos' => 11386,
                'endTokenPos' => 1520,
                'endFilePos' => 11389,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 536,
            'endLine' => 536,
            'startColumn' => 47,
            'endColumn' => 61,
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
 * Resolve the query string or return the default value.
 *
 * @param  string|array|null  $default
 * @return string
 */',
        'startLine' => 536,
        'endLine' => 543,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'queryStringResolver' => 
      array (
        'name' => 'queryStringResolver',
        'parameters' => 
        array (
          'resolver' => 
          array (
            'name' => 'resolver',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 551,
            'endLine' => 551,
            'startColumn' => 48,
            'endColumn' => 64,
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
 * Set with query string resolver callback.
 *
 * @param  \\Closure  $resolver
 * @return void
 */',
        'startLine' => 551,
        'endLine' => 554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'viewFactory' => 
      array (
        'name' => 'viewFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an instance of the view factory from the resolver.
 *
 * @return \\Illuminate\\Contracts\\View\\Factory
 */',
        'startLine' => 561,
        'endLine' => 564,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'viewFactoryResolver' => 
      array (
        'name' => 'viewFactoryResolver',
        'parameters' => 
        array (
          'resolver' => 
          array (
            'name' => 'resolver',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 572,
            'endLine' => 572,
            'startColumn' => 48,
            'endColumn' => 64,
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
 * Set the view factory resolver callback.
 *
 * @param  \\Closure  $resolver
 * @return void
 */',
        'startLine' => 572,
        'endLine' => 575,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'defaultView' => 
      array (
        'name' => 'defaultView',
        'parameters' => 
        array (
          'view' => 
          array (
            'name' => 'view',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 583,
            'endLine' => 583,
            'startColumn' => 40,
            'endColumn' => 44,
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
 * Set the default pagination view.
 *
 * @param  string  $view
 * @return void
 */',
        'startLine' => 583,
        'endLine' => 586,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'defaultSimpleView' => 
      array (
        'name' => 'defaultSimpleView',
        'parameters' => 
        array (
          'view' => 
          array (
            'name' => 'view',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 594,
            'endLine' => 594,
            'startColumn' => 46,
            'endColumn' => 50,
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
 * Set the default "simple" pagination view.
 *
 * @param  string  $view
 * @return void
 */',
        'startLine' => 594,
        'endLine' => 597,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'useTailwind' => 
      array (
        'name' => 'useTailwind',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that Tailwind styling should be used for generated links.
 *
 * @return void
 */',
        'startLine' => 604,
        'endLine' => 608,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'useBootstrap' => 
      array (
        'name' => 'useBootstrap',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that Bootstrap 4 styling should be used for generated links.
 *
 * @return void
 */',
        'startLine' => 615,
        'endLine' => 618,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'useBootstrapThree' => 
      array (
        'name' => 'useBootstrapThree',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that Bootstrap 3 styling should be used for generated links.
 *
 * @return void
 */',
        'startLine' => 625,
        'endLine' => 629,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'useBootstrapFour' => 
      array (
        'name' => 'useBootstrapFour',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that Bootstrap 4 styling should be used for generated links.
 *
 * @return void
 */',
        'startLine' => 636,
        'endLine' => 640,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'useBootstrapFive' => 
      array (
        'name' => 'useBootstrapFive',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that Bootstrap 5 styling should be used for generated links.
 *
 * @return void
 */',
        'startLine' => 647,
        'endLine' => 651,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'getIterator' => 
      array (
        'name' => 'getIterator',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an iterator for the items.
 *
 * @return \\ArrayIterator<TKey, TValue>
 */',
        'startLine' => 658,
        'endLine' => 661,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
 * Determine if the list of items is empty.
 *
 * @return bool
 */',
        'startLine' => 668,
        'endLine' => 671,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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
        'startLine' => 678,
        'endLine' => 681,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'count' => 
      array (
        'name' => 'count',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of items for the current page.
 *
 * @return int
 */',
        'startLine' => 688,
        'endLine' => 691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'getCollection' => 
      array (
        'name' => 'getCollection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the paginator\'s underlying collection.
 *
 * @return \\Illuminate\\Support\\Collection<TKey, TValue>
 */',
        'startLine' => 698,
        'endLine' => 701,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'setCollection' => 
      array (
        'name' => 'setCollection',
        'parameters' => 
        array (
          'collection' => 
          array (
            'name' => 'collection',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Support\\Collection',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 709,
            'endLine' => 709,
            'startColumn' => 35,
            'endColumn' => 56,
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
 * Set the paginator\'s underlying collection.
 *
 * @param  \\Illuminate\\Support\\Collection<TKey, TValue>  $collection
 * @return $this
 */',
        'startLine' => 709,
        'endLine' => 714,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'getOptions' => 
      array (
        'name' => 'getOptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the paginator options.
 *
 * @return array
 */',
        'startLine' => 721,
        'endLine' => 724,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'offsetExists' => 
      array (
        'name' => 'offsetExists',
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
            'startLine' => 732,
            'endLine' => 732,
            'startColumn' => 34,
            'endColumn' => 37,
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
 * Determine if the given item exists.
 *
 * @param  TKey  $key
 * @return bool
 */',
        'startLine' => 732,
        'endLine' => 735,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'offsetGet' => 
      array (
        'name' => 'offsetGet',
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
            'startLine' => 743,
            'endLine' => 743,
            'startColumn' => 31,
            'endColumn' => 34,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the item at the given offset.
 *
 * @param  TKey  $key
 * @return TValue|null
 */',
        'startLine' => 743,
        'endLine' => 746,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'offsetSet' => 
      array (
        'name' => 'offsetSet',
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
            'startLine' => 755,
            'endLine' => 755,
            'startColumn' => 31,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 755,
            'endLine' => 755,
            'startColumn' => 37,
            'endColumn' => 42,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the item at the given offset.
 *
 * @param  TKey|null  $key
 * @param  TValue  $value
 * @return void
 */',
        'startLine' => 755,
        'endLine' => 758,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'offsetUnset' => 
      array (
        'name' => 'offsetUnset',
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
            'startLine' => 766,
            'endLine' => 766,
            'startColumn' => 33,
            'endColumn' => 36,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Unset the item at the given key.
 *
 * @param  TKey  $key
 * @return void
 */',
        'startLine' => 766,
        'endLine' => 769,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      'toHtml' => 
      array (
        'name' => 'toHtml',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the contents of the paginator to HTML.
 *
 * @return string
 */',
        'startLine' => 776,
        'endLine' => 779,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 788,
            'endLine' => 788,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 788,
            'endLine' => 788,
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Make dynamic calls into the collection.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 */',
        'startLine' => 788,
        'endLine' => 791,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the contents of the paginator when casting to a string.
 *
 * @return string
 */',
        'startLine' => 798,
        'endLine' => 801,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'implementingClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
        'currentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
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