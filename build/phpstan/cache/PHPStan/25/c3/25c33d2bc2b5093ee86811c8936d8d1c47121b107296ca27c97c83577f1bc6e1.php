<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Pagination/Paginator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Pagination\Paginator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8b6fd9ede4cf2c1001eaa0b4c35b35a9a8af8edfa8d732f625a09255beda13fa-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Pagination\\Paginator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Pagination/Paginator.php',
      ),
    ),
    'namespace' => 'Illuminate\\Pagination',
    'name' => 'Illuminate\\Pagination\\Paginator',
    'shortName' => 'Paginator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @extends AbstractPaginator<TKey, TValue>
 *
 * @implements Arrayable<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 * @implements PaginatorContract<TKey, TValue>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 188,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Pagination\\AbstractPaginator',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\Arrayable',
      1 => 'ArrayAccess',
      2 => 'Countable',
      3 => 'IteratorAggregate',
      4 => 'Illuminate\\Contracts\\Support\\Jsonable',
      5 => 'JsonSerializable',
      6 => 'Illuminate\\Contracts\\Pagination\\Paginator',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'hasMore' => 
      array (
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'name' => 'hasMore',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Determine if there are more items in the data source.
 *
 * @return bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 23,
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 41,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'currentPage' => 
          array (
            'name' => 'currentPage',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 44,
                'endLine' => 44,
                'startTokenPos' => 110,
                'startFilePos' => 1265,
                'endTokenPos' => 110,
                'endFilePos' => 1268,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 51,
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
                'startLine' => 44,
                'endLine' => 44,
                'startTokenPos' => 119,
                'startFilePos' => 1288,
                'endTokenPos' => 120,
                'endFilePos' => 1289,
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
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 72,
            'endColumn' => 90,
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
 * Create a new paginator instance.
 *
 * @param  Collection<TKey, TValue>|Arrayable<TKey, TValue>|iterable<TKey, TValue>  $items
 * @param  int  $perPage
 * @param  int|null  $currentPage
 * @param  array  $options  (path, query, fragment, pageName)
 * @return void
 */',
        'startLine' => 44,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'setCurrentPage' => 
      array (
        'name' => 'setCurrentPage',
        'parameters' => 
        array (
          'currentPage' => 
          array (
            'name' => 'currentPage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 39,
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
 * Get the current page for the request.
 *
 * @param  int  $currentPage
 * @return int
 */',
        'startLine' => 65,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'setItems' => 
      array (
        'name' => 'setItems',
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
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Set the items for the paginator.
 *
 * @param  Collection<TKey, TValue>|Arrayable<TKey, TValue>|iterable<TKey, TValue>|null  $items
 * @return void
 */',
        'startLine' => 78,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
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
 * Get the URL for the next page.
 *
 * @return string|null
 */',
        'startLine' => 92,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'links' => 
      array (
        'name' => 'links',
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
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 421,
                'startFilePos' => 2871,
                'endTokenPos' => 421,
                'endFilePos' => 2874,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 27,
            'endColumn' => 38,
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
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 428,
                'startFilePos' => 2885,
                'endTokenPos' => 429,
                'endFilePos' => 2886,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
            'startColumn' => 41,
            'endColumn' => 50,
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
 * Render the paginator using the given view.
 *
 * @param  string|null  $view
 * @param  array  $data
 * @return string
 */',
        'startLine' => 106,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
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
                'startLine' => 118,
                'endLine' => 118,
                'startTokenPos' => 461,
                'startFilePos' => 3170,
                'endTokenPos' => 461,
                'endFilePos' => 3173,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 118,
            'endLine' => 118,
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
                'startLine' => 118,
                'endLine' => 118,
                'startTokenPos' => 468,
                'startFilePos' => 3184,
                'endTokenPos' => 469,
                'endFilePos' => 3185,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 118,
            'endLine' => 118,
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
 * Render the paginator using the given view.
 *
 * @param  string|null  $view
 * @param  array  $data
 * @return \\Illuminate\\Contracts\\Support\\Htmlable
 */',
        'startLine' => 118,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'hasMorePagesWhen' => 
      array (
        'name' => 'hasMorePagesWhen',
        'parameters' => 
        array (
          'hasMore' => 
          array (
            'name' => 'hasMore',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 131,
                'endLine' => 131,
                'startTokenPos' => 527,
                'startFilePos' => 3537,
                'endTokenPos' => 527,
                'endFilePos' => 3540,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 131,
            'endLine' => 131,
            'startColumn' => 38,
            'endColumn' => 52,
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
 * Manually indicate that the paginator does have more pages.
 *
 * @param  bool  $hasMore
 * @return $this
 */',
        'startLine' => 131,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
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
 * Determine if there are more items in the data source.
 *
 * @return bool
 */',
        'startLine' => 143,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the instance as an array.
 *
 * @return array
 */',
        'startLine' => 153,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'jsonSerialize' => 
      array (
        'name' => 'jsonSerialize',
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
        'docComment' => '/**
 * Convert the object into something JSON serializable.
 *
 * @return array
 */',
        'startLine' => 173,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
        'aliasName' => NULL,
      ),
      'toJson' => 
      array (
        'name' => 'toJson',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 184,
                'endLine' => 184,
                'startTokenPos' => 730,
                'startFilePos' => 4726,
                'endTokenPos' => 730,
                'endFilePos' => 4726,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 184,
            'endLine' => 184,
            'startColumn' => 28,
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
        'docComment' => '/**
 * Convert the object to its JSON representation.
 *
 * @param  int  $options
 * @return string
 */',
        'startLine' => 184,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Pagination',
        'declaringClassName' => 'Illuminate\\Pagination\\Paginator',
        'implementingClassName' => 'Illuminate\\Pagination\\Paginator',
        'currentClassName' => 'Illuminate\\Pagination\\Paginator',
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