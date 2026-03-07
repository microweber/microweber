<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Collections/Traits/EnumeratesValues.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Support\Traits\EnumeratesValues
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7e7bbd10d455cdf0d049c7e4dfb07f45717bfb51059e6b7d81137508e9f1ebd2-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Collections/Traits/EnumeratesValues.php',
      ),
    ),
    'namespace' => 'Illuminate\\Support\\Traits',
    'name' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
    'shortName' => 'EnumeratesValues',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $average
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $avg
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $contains
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $doesntContain
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $each
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $every
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $filter
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $first
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $flatMap
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $groupBy
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $keyBy
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $last
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $map
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $max
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $min
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $partition
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $percentage
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $reject
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $skipUntil
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $skipWhile
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $some
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $sortBy
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $sortByDesc
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $sum
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $takeUntil
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $takeWhile
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $unique
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $unless
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $until
 * @property-read HigherOrderCollectionProxy<TKey, TValue> $when
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 60,
    'endLine' => 1188,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Conditionable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'escapeWhenCastingToString' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'name' => 'escapeWhenCastingToString',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 115,
            'startFilePos' => 2846,
            'endTokenPos' => 115,
            'endFilePos' => 2850,
          ),
        ),
        'docComment' => '/**
 * Indicates that the object\'s string representation should be escaped when __toString is invoked.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'proxies' => 
      array (
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'name' => 'proxies',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'average\', \'avg\', \'contains\', \'doesntContain\', \'each\', \'every\', \'filter\', \'first\', \'flatMap\', \'groupBy\', \'keyBy\', \'last\', \'map\', \'max\', \'min\', \'partition\', \'percentage\', \'reject\', \'skipUntil\', \'skipWhile\', \'some\', \'sortBy\', \'sortByDesc\', \'sum\', \'takeUntil\', \'takeWhile\', \'unique\', \'unless\', \'until\', \'when\']',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 107,
            'startTokenPos' => 128,
            'startFilePos' => 2980,
            'endTokenPos' => 220,
            'endFilePos' => 3534,
          ),
        ),
        'docComment' => '/**
 * The methods that can be proxied.
 *
 * @var array<int, string>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 6,
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
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
          'items' => 
          array (
            'name' => 'items',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 118,
                'endLine' => 118,
                'startTokenPos' => 237,
                'startFilePos' => 3913,
                'endTokenPos' => 238,
                'endFilePos' => 3914,
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
        'docComment' => '/**
 * Create a new collection instance if the value isn\'t one already.
 *
 * @template TMakeKey of array-key
 * @template TMakeValue
 *
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable<TMakeKey, TMakeValue>|iterable<TMakeKey, TMakeValue>|null  $items
 * @return static<TMakeKey, TMakeValue>
 */',
        'startLine' => 118,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'wrap' => 
      array (
        'name' => 'wrap',
        'parameters' => 
        array (
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
            'startLine' => 131,
            'endLine' => 131,
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
 * Wrap the given value in a collection if applicable.
 *
 * @template TWrapValue
 *
 * @param  iterable<array-key, TWrapValue>|TWrapValue  $value
 * @return static<array-key, TWrapValue>
 */',
        'startLine' => 131,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'unwrap' => 
      array (
        'name' => 'unwrap',
        'parameters' => 
        array (
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
            'startLine' => 147,
            'endLine' => 147,
            'startColumn' => 35,
            'endColumn' => 40,
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
 * Get the underlying items from the given collection if applicable.
 *
 * @template TUnwrapKey of array-key
 * @template TUnwrapValue
 *
 * @param  array<TUnwrapKey, TUnwrapValue>|static<TUnwrapKey, TUnwrapValue>  $value
 * @return array<TUnwrapKey, TUnwrapValue>
 */',
        'startLine' => 147,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'empty' => 
      array (
        'name' => 'empty',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new instance with no items.
 *
 * @return static
 */',
        'startLine' => 157,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'times' => 
      array (
        'name' => 'times',
        'parameters' => 
        array (
          'number' => 
          array (
            'name' => 'number',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 171,
            'endLine' => 171,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 171,
                'endLine' => 171,
                'startTokenPos' => 389,
                'startFilePos' => 5302,
                'endTokenPos' => 389,
                'endFilePos' => 5305,
              ),
            ),
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
                      'name' => 'callable',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 171,
            'endLine' => 171,
            'startColumn' => 43,
            'endColumn' => 68,
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
 * Create a new collection by invoking the callback a given amount of times.
 *
 * @template TTimesValue
 *
 * @param  int  $number
 * @param  (callable(int): TTimesValue)|null  $callback
 * @return static<int, TTimesValue>
 */',
        'startLine' => 171,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'avg' => 
      array (
        'name' => 'avg',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 188,
                'endLine' => 188,
                'startTokenPos' => 458,
                'startFilePos' => 5703,
                'endTokenPos' => 458,
                'endFilePos' => 5706,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 188,
            'endLine' => 188,
            'startColumn' => 25,
            'endColumn' => 40,
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
 * Get the average value of a given key.
 *
 * @param  (callable(TValue): float|int)|string|null  $callback
 * @return float|int|null
 */',
        'startLine' => 188,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'average' => 
      array (
        'name' => 'average',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 210,
                'endLine' => 210,
                'startTokenPos' => 599,
                'startFilePos' => 6317,
                'endTokenPos' => 599,
                'endFilePos' => 6320,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 210,
            'endLine' => 210,
            'startColumn' => 29,
            'endColumn' => 44,
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
 * Alias for the "avg" method.
 *
 * @param  (callable(TValue): float|int)|string|null  $callback
 * @return float|int|null
 */',
        'startLine' => 210,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'some' => 
      array (
        'name' => 'some',
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
            'startLine' => 223,
            'endLine' => 223,
            'startColumn' => 26,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 223,
                'endLine' => 223,
                'startTokenPos' => 631,
                'startFilePos' => 6627,
                'endTokenPos' => 631,
                'endFilePos' => 6630,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 223,
            'endLine' => 223,
            'startColumn' => 32,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 223,
                'endLine' => 223,
                'startTokenPos' => 638,
                'startFilePos' => 6642,
                'endTokenPos' => 638,
                'endFilePos' => 6645,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 223,
            'endLine' => 223,
            'startColumn' => 50,
            'endColumn' => 62,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Alias for the "contains" method.
 *
 * @param  (callable(TValue, TKey): bool)|TValue|string  $key
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 223,
        'endLine' => 226,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'dd' => 
      array (
        'name' => 'dd',
        'parameters' => 
        array (
          'args' => 
          array (
            'name' => 'args',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 234,
            'endLine' => 234,
            'startColumn' => 24,
            'endColumn' => 31,
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
 * Dump the given arguments and terminate execution.
 *
 * @param  mixed  ...$args
 * @return never
 */',
        'startLine' => 234,
        'endLine' => 237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'dump' => 
      array (
        'name' => 'dump',
        'parameters' => 
        array (
          'args' => 
          array (
            'name' => 'args',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 245,
            'endLine' => 245,
            'startColumn' => 26,
            'endColumn' => 33,
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
 * Dump the items.
 *
 * @param  mixed  ...$args
 * @return $this
 */',
        'startLine' => 245,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'each' => 
      array (
        'name' => 'each',
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
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 26,
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
        'docComment' => '/**
 * Execute a callback over each item.
 *
 * @param  callable(TValue, TKey): mixed  $callback
 * @return $this
 */',
        'startLine' => 258,
        'endLine' => 267,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'eachSpread' => 
      array (
        'name' => 'eachSpread',
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
            'startLine' => 275,
            'endLine' => 275,
            'startColumn' => 32,
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
        'docComment' => '/**
 * Execute a callback over each nested chunk of items.
 *
 * @param  callable(...mixed): mixed  $callback
 * @return static
 */',
        'startLine' => 275,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'every' => 
      array (
        'name' => 'every',
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
            'startLine' => 292,
            'endLine' => 292,
            'startColumn' => 27,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 292,
                'endLine' => 292,
                'startTokenPos' => 863,
                'startFilePos' => 8142,
                'endTokenPos' => 863,
                'endFilePos' => 8145,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 292,
            'endLine' => 292,
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 292,
                'endLine' => 292,
                'startTokenPos' => 870,
                'startFilePos' => 8157,
                'endTokenPos' => 870,
                'endFilePos' => 8160,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 292,
            'endLine' => 292,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if all items pass the given truth test.
 *
 * @param  (callable(TValue, TKey): bool)|TValue|string  $key
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 292,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'firstWhere' => 
      array (
        'name' => 'firstWhere',
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
            'startLine' => 317,
            'endLine' => 317,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 317,
                'endLine' => 317,
                'startTokenPos' => 984,
                'startFilePos' => 8781,
                'endTokenPos' => 984,
                'endFilePos' => 8784,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 317,
            'endLine' => 317,
            'startColumn' => 38,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 317,
                'endLine' => 317,
                'startTokenPos' => 991,
                'startFilePos' => 8796,
                'endTokenPos' => 991,
                'endFilePos' => 8799,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 317,
            'endLine' => 317,
            'startColumn' => 56,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the first item by the given key value pair.
 *
 * @param  callable|string  $key
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return TValue|null
 */',
        'startLine' => 317,
        'endLine' => 320,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'value' => 
      array (
        'name' => 'value',
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
            'startLine' => 331,
            'endLine' => 331,
            'startColumn' => 27,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 331,
                'endLine' => 331,
                'startTokenPos' => 1031,
                'startFilePos' => 9204,
                'endTokenPos' => 1031,
                'endFilePos' => 9207,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 331,
            'endLine' => 331,
            'startColumn' => 33,
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
 * Get a single key\'s value from the first matching item in the collection.
 *
 * @template TValueDefault
 *
 * @param  string  $key
 * @param  TValueDefault|(\\Closure(): TValueDefault)  $default
 * @return TValue|TValueDefault
 */',
        'startLine' => 331,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'ensure' => 
      array (
        'name' => 'ensure',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 350,
            'endLine' => 350,
            'startColumn' => 28,
            'endColumn' => 32,
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
 * Ensure that every item in the collection is of the expected type.
 *
 * @template TEnsureOfType
 *
 * @param  class-string<TEnsureOfType>|array<array-key, class-string<TEnsureOfType>>|\'string\'|\'int\'|\'float\'|\'bool\'|\'array\'|\'null\'  $type
 * @return static<TKey, TEnsureOfType>
 *
 * @throws \\UnexpectedValueException
 */',
        'startLine' => 350,
        'endLine' => 367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
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
 * Determine if the collection is not empty.
 *
 * @phpstan-assert-if-true TValue $this->first()
 * @phpstan-assert-if-true TValue $this->last()
 *
 * @phpstan-assert-if-false null $this->first()
 * @phpstan-assert-if-false null $this->last()
 *
 * @return bool
 */',
        'startLine' => 380,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'mapSpread' => 
      array (
        'name' => 'mapSpread',
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
            'startLine' => 393,
            'endLine' => 393,
            'startColumn' => 31,
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
        'docComment' => '/**
 * Run a map over each nested chunk of items.
 *
 * @template TMapSpreadValue
 *
 * @param  callable(mixed...): TMapSpreadValue  $callback
 * @return static<TKey, TMapSpreadValue>
 */',
        'startLine' => 393,
        'endLine' => 400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'mapToGroups' => 
      array (
        'name' => 'mapToGroups',
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
            'startLine' => 413,
            'endLine' => 413,
            'startColumn' => 33,
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
 * Run a grouping map over the items.
 *
 * The callback should return an associative array with a single key/value pair.
 *
 * @template TMapToGroupsKey of array-key
 * @template TMapToGroupsValue
 *
 * @param  callable(TValue, TKey): array<TMapToGroupsKey, TMapToGroupsValue>  $callback
 * @return static<TMapToGroupsKey, static<int, TMapToGroupsValue>>
 */',
        'startLine' => 413,
        'endLine' => 418,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'flatMap' => 
      array (
        'name' => 'flatMap',
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
            'startLine' => 429,
            'endLine' => 429,
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
 * Map a collection and flatten the result by a single level.
 *
 * @template TFlatMapKey of array-key
 * @template TFlatMapValue
 *
 * @param  callable(TValue, TKey): (\\Illuminate\\Support\\Collection<TFlatMapKey, TFlatMapValue>|array<TFlatMapKey, TFlatMapValue>)  $callback
 * @return static<TFlatMapKey, TFlatMapValue>
 */',
        'startLine' => 429,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'mapInto' => 
      array (
        'name' => 'mapInto',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 442,
            'endLine' => 442,
            'startColumn' => 29,
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
 * Map the values into a new class.
 *
 * @template TMapIntoValue
 *
 * @param  class-string<TMapIntoValue>  $class
 * @return static<TKey, TMapIntoValue>
 */',
        'startLine' => 442,
        'endLine' => 449,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'min' => 
      array (
        'name' => 'min',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 457,
                'endLine' => 457,
                'startTokenPos' => 1484,
                'startFilePos' => 12911,
                'endTokenPos' => 1484,
                'endFilePos' => 12914,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 457,
            'endLine' => 457,
            'startColumn' => 25,
            'endColumn' => 40,
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
 * Get the min value of a given key.
 *
 * @param  (callable(TValue):mixed)|string|null  $callback
 * @return mixed
 */',
        'startLine' => 457,
        'endLine' => 464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'max' => 
      array (
        'name' => 'max',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 472,
                'endLine' => 472,
                'startTokenPos' => 1591,
                'startFilePos' => 13387,
                'endTokenPos' => 1591,
                'endFilePos' => 13390,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 472,
            'endLine' => 472,
            'startColumn' => 25,
            'endColumn' => 40,
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
 * Get the max value of a given key.
 *
 * @param  (callable(TValue):mixed)|string|null  $callback
 * @return mixed
 */',
        'startLine' => 472,
        'endLine' => 481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'forPage' => 
      array (
        'name' => 'forPage',
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
            'startLine' => 490,
            'endLine' => 490,
            'startColumn' => 29,
            'endColumn' => 33,
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
            'startLine' => 490,
            'endLine' => 490,
            'startColumn' => 36,
            'endColumn' => 43,
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
 * "Paginate" the collection by slicing it into a smaller collection.
 *
 * @param  int  $page
 * @param  int  $perPage
 * @return static
 */',
        'startLine' => 490,
        'endLine' => 495,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'partition' => 
      array (
        'name' => 'partition',
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
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 31,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 505,
                'endLine' => 505,
                'startTokenPos' => 1758,
                'startFilePos' => 14388,
                'endTokenPos' => 1758,
                'endFilePos' => 14391,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 505,
                'endLine' => 505,
                'startTokenPos' => 1765,
                'startFilePos' => 14403,
                'endTokenPos' => 1765,
                'endFilePos' => 14406,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 505,
            'endLine' => 505,
            'startColumn' => 55,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Partition the collection into two arrays using the given callback or key.
 *
 * @param  (callable(TValue, TKey): bool)|TValue|string  $key
 * @param  TValue|string|null  $operator
 * @param  TValue|null  $value
 * @return static<int<0, 1>, static<TKey, TValue>>
 */',
        'startLine' => 505,
        'endLine' => 523,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'percentage' => 
      array (
        'name' => 'percentage',
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
            'startLine' => 532,
            'endLine' => 532,
            'startColumn' => 32,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'precision' => 
          array (
            'name' => 'precision',
            'default' => 
            array (
              'code' => '2',
              'attributes' => 
              array (
                'startLine' => 532,
                'endLine' => 532,
                'startTokenPos' => 1926,
                'startFilePos' => 15175,
                'endTokenPos' => 1926,
                'endFilePos' => 15175,
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
            'startLine' => 532,
            'endLine' => 532,
            'startColumn' => 52,
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
        'docComment' => '/**
 * Calculate the percentage of items that pass a given truth test.
 *
 * @param  (callable(TValue, TKey): bool)  $callback
 * @param  int  $precision
 * @return float|null
 */',
        'startLine' => 532,
        'endLine' => 542,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'sum' => 
      array (
        'name' => 'sum',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 550,
                'endLine' => 550,
                'startTokenPos' => 1998,
                'startFilePos' => 15569,
                'endTokenPos' => 1998,
                'endFilePos' => 15572,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 550,
            'endLine' => 550,
            'startColumn' => 25,
            'endColumn' => 40,
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
 * Get the sum of the given values.
 *
 * @param  (callable(TValue): mixed)|string|null  $callback
 * @return mixed
 */',
        'startLine' => 550,
        'endLine' => 557,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whenEmpty' => 
      array (
        'name' => 'whenEmpty',
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
            'startLine' => 568,
            'endLine' => 568,
            'startColumn' => 31,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 568,
                'endLine' => 568,
                'startTokenPos' => 2083,
                'startFilePos' => 16163,
                'endTokenPos' => 2083,
                'endFilePos' => 16166,
              ),
            ),
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
                      'name' => 'callable',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 568,
            'endLine' => 568,
            'startColumn' => 51,
            'endColumn' => 75,
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
 * Apply the callback if the collection is empty.
 *
 * @template TWhenEmptyReturnType
 *
 * @param  (callable($this): TWhenEmptyReturnType)  $callback
 * @param  (callable($this): TWhenEmptyReturnType)|null  $default
 * @return $this|TWhenEmptyReturnType
 */',
        'startLine' => 568,
        'endLine' => 571,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whenNotEmpty' => 
      array (
        'name' => 'whenNotEmpty',
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
            'startLine' => 582,
            'endLine' => 582,
            'startColumn' => 34,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 582,
                'endLine' => 582,
                'startTokenPos' => 2130,
                'startFilePos' => 16637,
                'endTokenPos' => 2130,
                'endFilePos' => 16640,
              ),
            ),
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
                      'name' => 'callable',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 582,
            'endLine' => 582,
            'startColumn' => 54,
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
 * Apply the callback if the collection is not empty.
 *
 * @template TWhenNotEmptyReturnType
 *
 * @param  callable($this): TWhenNotEmptyReturnType  $callback
 * @param  (callable($this): TWhenNotEmptyReturnType)|null  $default
 * @return $this|TWhenNotEmptyReturnType
 */',
        'startLine' => 582,
        'endLine' => 585,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'unlessEmpty' => 
      array (
        'name' => 'unlessEmpty',
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
            'startLine' => 596,
            'endLine' => 596,
            'startColumn' => 33,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 596,
                'endLine' => 596,
                'startTokenPos' => 2177,
                'startFilePos' => 17109,
                'endTokenPos' => 2177,
                'endFilePos' => 17112,
              ),
            ),
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
                      'name' => 'callable',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 596,
            'endLine' => 596,
            'startColumn' => 53,
            'endColumn' => 77,
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
 * Apply the callback unless the collection is empty.
 *
 * @template TUnlessEmptyReturnType
 *
 * @param  callable($this): TUnlessEmptyReturnType  $callback
 * @param  (callable($this): TUnlessEmptyReturnType)|null  $default
 * @return $this|TUnlessEmptyReturnType
 */',
        'startLine' => 596,
        'endLine' => 599,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'unlessNotEmpty' => 
      array (
        'name' => 'unlessNotEmpty',
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
            'startLine' => 610,
            'endLine' => 610,
            'startColumn' => 36,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 610,
                'endLine' => 610,
                'startTokenPos' => 2217,
                'startFilePos' => 17587,
                'endTokenPos' => 2217,
                'endFilePos' => 17590,
              ),
            ),
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
                      'name' => 'callable',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 610,
            'endLine' => 610,
            'startColumn' => 56,
            'endColumn' => 80,
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
 * Apply the callback unless the collection is not empty.
 *
 * @template TUnlessNotEmptyReturnType
 *
 * @param  callable($this): TUnlessNotEmptyReturnType  $callback
 * @param  (callable($this): TUnlessNotEmptyReturnType)|null  $default
 * @return $this|TUnlessNotEmptyReturnType
 */',
        'startLine' => 610,
        'endLine' => 613,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'where' => 
      array (
        'name' => 'where',
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
            'startLine' => 623,
            'endLine' => 623,
            'startColumn' => 27,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 623,
                'endLine' => 623,
                'startTokenPos' => 2252,
                'startFilePos' => 17896,
                'endTokenPos' => 2252,
                'endFilePos' => 17899,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 623,
            'endLine' => 623,
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 623,
                'endLine' => 623,
                'startTokenPos' => 2259,
                'startFilePos' => 17911,
                'endTokenPos' => 2259,
                'endFilePos' => 17914,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 623,
            'endLine' => 623,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filter items by the given key value pair.
 *
 * @param  callable|string  $key
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return static
 */',
        'startLine' => 623,
        'endLine' => 626,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereNull' => 
      array (
        'name' => 'whereNull',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 634,
                'endLine' => 634,
                'startTokenPos' => 2296,
                'startFilePos' => 18183,
                'endTokenPos' => 2296,
                'endFilePos' => 18186,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 634,
            'endLine' => 634,
            'startColumn' => 31,
            'endColumn' => 41,
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
 * Filter items where the value for the given key is null.
 *
 * @param  string|null  $key
 * @return static
 */',
        'startLine' => 634,
        'endLine' => 637,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereNotNull' => 
      array (
        'name' => 'whereNotNull',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 645,
                'endLine' => 645,
                'startTokenPos' => 2328,
                'startFilePos' => 18434,
                'endTokenPos' => 2328,
                'endFilePos' => 18437,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 645,
            'endLine' => 645,
            'startColumn' => 34,
            'endColumn' => 44,
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
 * Filter items where the value for the given key is not null.
 *
 * @param  string|null  $key
 * @return static
 */',
        'startLine' => 645,
        'endLine' => 648,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereStrict' => 
      array (
        'name' => 'whereStrict',
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
            'startLine' => 657,
            'endLine' => 657,
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
            'startLine' => 657,
            'endLine' => 657,
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
 * Filter items by the given key value pair using strict comparison.
 *
 * @param  string  $key
 * @param  mixed  $value
 * @return static
 */',
        'startLine' => 657,
        'endLine' => 660,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereIn' => 
      array (
        'name' => 'whereIn',
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
            'startLine' => 670,
            'endLine' => 670,
            'startColumn' => 29,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 670,
            'endLine' => 670,
            'startColumn' => 35,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 670,
                'endLine' => 670,
                'startTokenPos' => 2403,
                'startFilePos' => 19062,
                'endTokenPos' => 2403,
                'endFilePos' => 19066,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 670,
            'endLine' => 670,
            'startColumn' => 44,
            'endColumn' => 58,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filter items by the given key value pair.
 *
 * @param  string  $key
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|iterable  $values
 * @param  bool  $strict
 * @return static
 */',
        'startLine' => 670,
        'endLine' => 675,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereInStrict' => 
      array (
        'name' => 'whereInStrict',
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
            'startLine' => 684,
            'endLine' => 684,
            'startColumn' => 35,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 684,
            'endLine' => 684,
            'startColumn' => 41,
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
 * Filter items by the given key value pair using strict comparison.
 *
 * @param  string  $key
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|iterable  $values
 * @return static
 */',
        'startLine' => 684,
        'endLine' => 687,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereBetween' => 
      array (
        'name' => 'whereBetween',
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
            'startLine' => 696,
            'endLine' => 696,
            'startColumn' => 34,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 696,
            'endLine' => 696,
            'startColumn' => 40,
            'endColumn' => 46,
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
 * Filter items such that the value of the given key is between the given values.
 *
 * @param  string  $key
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|iterable  $values
 * @return static
 */',
        'startLine' => 696,
        'endLine' => 699,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereNotBetween' => 
      array (
        'name' => 'whereNotBetween',
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
            'startLine' => 708,
            'endLine' => 708,
            'startColumn' => 37,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 708,
            'endLine' => 708,
            'startColumn' => 43,
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
        'docComment' => '/**
 * Filter items such that the value of the given key is not between the given values.
 *
 * @param  string  $key
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|iterable  $values
 * @return static
 */',
        'startLine' => 708,
        'endLine' => 713,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereNotIn' => 
      array (
        'name' => 'whereNotIn',
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
            'startLine' => 723,
            'endLine' => 723,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 723,
            'endLine' => 723,
            'startColumn' => 38,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 723,
                'endLine' => 723,
                'startTokenPos' => 2626,
                'startFilePos' => 20673,
                'endTokenPos' => 2626,
                'endFilePos' => 20677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 723,
            'endLine' => 723,
            'startColumn' => 47,
            'endColumn' => 61,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filter items by the given key value pair.
 *
 * @param  string  $key
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|iterable  $values
 * @param  bool  $strict
 * @return static
 */',
        'startLine' => 723,
        'endLine' => 728,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereNotInStrict' => 
      array (
        'name' => 'whereNotInStrict',
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
            'startLine' => 737,
            'endLine' => 737,
            'startColumn' => 38,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 737,
            'endLine' => 737,
            'startColumn' => 44,
            'endColumn' => 50,
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
 * Filter items by the given key value pair using strict comparison.
 *
 * @param  string  $key
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|iterable  $values
 * @return static
 */',
        'startLine' => 737,
        'endLine' => 740,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'whereInstanceOf' => 
      array (
        'name' => 'whereInstanceOf',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 750,
            'endLine' => 750,
            'startColumn' => 37,
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
        'docComment' => '/**
 * Filter the items, removing any items that don\'t match the given type(s).
 *
 * @template TWhereInstanceOf
 *
 * @param  class-string<TWhereInstanceOf>|array<array-key, class-string<TWhereInstanceOf>>  $type
 * @return static<TKey, TWhereInstanceOf>
 */',
        'startLine' => 750,
        'endLine' => 765,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'pipe' => 
      array (
        'name' => 'pipe',
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
            'startLine' => 775,
            'endLine' => 775,
            'startColumn' => 26,
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
        'docComment' => '/**
 * Pass the collection to the given callback and return the result.
 *
 * @template TPipeReturnType
 *
 * @param  callable($this): TPipeReturnType  $callback
 * @return TPipeReturnType
 */',
        'startLine' => 775,
        'endLine' => 778,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'pipeInto' => 
      array (
        'name' => 'pipeInto',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
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
            'startColumn' => 30,
            'endColumn' => 35,
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
 * Pass the collection into a new class.
 *
 * @template TPipeIntoValue
 *
 * @param  class-string<TPipeIntoValue>  $class
 * @return TPipeIntoValue
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
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'pipeThrough' => 
      array (
        'name' => 'pipeThrough',
        'parameters' => 
        array (
          'callbacks' => 
          array (
            'name' => 'callbacks',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 799,
            'endLine' => 799,
            'startColumn' => 33,
            'endColumn' => 42,
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
 * Pass the collection through a series of callable pipes and return the result.
 *
 * @param  array<callable>  $callbacks
 * @return mixed
 */',
        'startLine' => 799,
        'endLine' => 805,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'reduce' => 
      array (
        'name' => 'reduce',
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
            'startLine' => 817,
            'endLine' => 817,
            'startColumn' => 28,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'initial' => 
          array (
            'name' => 'initial',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 817,
                'endLine' => 817,
                'startTokenPos' => 2930,
                'startFilePos' => 23244,
                'endTokenPos' => 2930,
                'endFilePos' => 23247,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 817,
            'endLine' => 817,
            'startColumn' => 48,
            'endColumn' => 62,
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
 * Reduce the collection to a single value.
 *
 * @template TReduceInitial
 * @template TReduceReturnType
 *
 * @param  callable(TReduceInitial|TReduceReturnType, TValue, TKey): TReduceReturnType  $callback
 * @param  TReduceInitial  $initial
 * @return TReduceReturnType
 */',
        'startLine' => 817,
        'endLine' => 826,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'reduceSpread' => 
      array (
        'name' => 'reduceSpread',
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
            'startLine' => 837,
            'endLine' => 837,
            'startColumn' => 34,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'initial' => 
          array (
            'name' => 'initial',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 837,
            'endLine' => 837,
            'startColumn' => 54,
            'endColumn' => 64,
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
 * Reduce the collection to multiple aggregate values.
 *
 * @param  callable  $callback
 * @param  mixed  ...$initial
 * @return array
 *
 * @throws \\UnexpectedValueException
 */',
        'startLine' => 837,
        'endLine' => 853,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'reduceWithKeys' => 
      array (
        'name' => 'reduceWithKeys',
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
            'startLine' => 865,
            'endLine' => 865,
            'startColumn' => 36,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'initial' => 
          array (
            'name' => 'initial',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 865,
                'endLine' => 865,
                'startTokenPos' => 3119,
                'startFilePos' => 24682,
                'endTokenPos' => 3119,
                'endFilePos' => 24685,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 865,
            'endLine' => 865,
            'startColumn' => 56,
            'endColumn' => 70,
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
 * Reduce an associative collection to a single value.
 *
 * @template TReduceWithKeysInitial
 * @template TReduceWithKeysReturnType
 *
 * @param  callable(TReduceWithKeysInitial|TReduceWithKeysReturnType, TValue, TKey): TReduceWithKeysReturnType  $callback
 * @param  TReduceWithKeysInitial  $initial
 * @return TReduceWithKeysReturnType
 */',
        'startLine' => 865,
        'endLine' => 868,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'reject' => 
      array (
        'name' => 'reject',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 876,
                'endLine' => 876,
                'startTokenPos' => 3151,
                'startFilePos' => 24985,
                'endTokenPos' => 3151,
                'endFilePos' => 24988,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 876,
            'endLine' => 876,
            'startColumn' => 28,
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
        'docComment' => '/**
 * Create a collection of all elements that do not pass a given truth test.
 *
 * @param  (callable(TValue, TKey): bool)|bool|TValue  $callback
 * @return static
 */',
        'startLine' => 876,
        'endLine' => 885,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'tap' => 
      array (
        'name' => 'tap',
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
            'startLine' => 893,
            'endLine' => 893,
            'startColumn' => 25,
            'endColumn' => 42,
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
 * Pass the collection to the given callback and then return it.
 *
 * @param  callable($this): mixed  $callback
 * @return $this
 */',
        'startLine' => 893,
        'endLine' => 898,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'unique' => 
      array (
        'name' => 'unique',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 907,
                'endLine' => 907,
                'startTokenPos' => 3265,
                'startFilePos' => 25779,
                'endTokenPos' => 3265,
                'endFilePos' => 25782,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 907,
            'endLine' => 907,
            'startColumn' => 28,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 907,
                'endLine' => 907,
                'startTokenPos' => 3272,
                'startFilePos' => 25795,
                'endTokenPos' => 3272,
                'endFilePos' => 25799,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 907,
            'endLine' => 907,
            'startColumn' => 41,
            'endColumn' => 55,
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
 * Return only unique items from the collection array.
 *
 * @param  (callable(TValue, TKey): mixed)|string|null  $key
 * @param  bool  $strict
 * @return static
 */',
        'startLine' => 907,
        'endLine' => 920,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'uniqueStrict' => 
      array (
        'name' => 'uniqueStrict',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 928,
                'endLine' => 928,
                'startTokenPos' => 3388,
                'startFilePos' => 26371,
                'endTokenPos' => 3388,
                'endFilePos' => 26374,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 928,
            'endLine' => 928,
            'startColumn' => 34,
            'endColumn' => 44,
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
 * Return only unique items from the collection array using strict comparison.
 *
 * @param  (callable(TValue, TKey): mixed)|string|null  $key
 * @return static
 */',
        'startLine' => 928,
        'endLine' => 931,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'collect' => 
      array (
        'name' => 'collect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Collect the values into a collection.
 *
 * @return \\Illuminate\\Support\\Collection<TKey, TValue>
 */',
        'startLine' => 938,
        'endLine' => 941,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
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
 * Get the collection of items as a plain array.
 *
 * @return array<TKey, mixed>
 */',
        'startLine' => 948,
        'endLine' => 951,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
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
 * @return array<TKey, mixed>
 */',
        'startLine' => 958,
        'endLine' => 971,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
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
                'startLine' => 979,
                'endLine' => 979,
                'startTokenPos' => 3620,
                'startFilePos' => 27659,
                'endTokenPos' => 3620,
                'endFilePos' => 27659,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 979,
            'endLine' => 979,
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
 * Get the collection of items as JSON.
 *
 * @param  int  $options
 * @return string
 */',
        'startLine' => 979,
        'endLine' => 982,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'getCachingIterator' => 
      array (
        'name' => 'getCachingIterator',
        'parameters' => 
        array (
          'flags' => 
          array (
            'name' => 'flags',
            'default' => 
            array (
              'code' => '\\CachingIterator::CALL_TOSTRING',
              'attributes' => 
              array (
                'startLine' => 990,
                'endLine' => 990,
                'startTokenPos' => 3654,
                'startFilePos' => 27906,
                'endTokenPos' => 3656,
                'endFilePos' => 27935,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 990,
            'endLine' => 990,
            'startColumn' => 40,
            'endColumn' => 78,
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
 * Get a CachingIterator instance.
 *
 * @param  int  $flags
 * @return \\CachingIterator
 */',
        'startLine' => 990,
        'endLine' => 993,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
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
 * Convert the collection to its string representation.
 *
 * @return string
 */',
        'startLine' => 1000,
        'endLine' => 1005,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'escapeWhenCastingToString' => 
      array (
        'name' => 'escapeWhenCastingToString',
        'parameters' => 
        array (
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1013,
                'endLine' => 1013,
                'startTokenPos' => 3732,
                'startFilePos' => 28526,
                'endTokenPos' => 3732,
                'endFilePos' => 28529,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1013,
            'endLine' => 1013,
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
 * Indicate that the model\'s string representation should be escaped when __toString is invoked.
 *
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 1013,
        'endLine' => 1018,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'proxy' => 
      array (
        'name' => 'proxy',
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
            'startLine' => 1026,
            'endLine' => 1026,
            'startColumn' => 34,
            'endColumn' => 40,
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
 * Add a method to the list of proxied methods.
 *
 * @param  string  $method
 * @return void
 */',
        'startLine' => 1026,
        'endLine' => 1029,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      '__get' => 
      array (
        'name' => '__get',
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
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 27,
            'endColumn' => 30,
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
 * Dynamically access collection proxies.
 *
 * @param  string  $key
 * @return mixed
 *
 * @throws \\Exception
 */',
        'startLine' => 1039,
        'endLine' => 1046,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'getArrayableItems' => 
      array (
        'name' => 'getArrayableItems',
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
            'startLine' => 1054,
            'endLine' => 1054,
            'startColumn' => 42,
            'endColumn' => 47,
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
 * Results array of items from Collection or Arrayable.
 *
 * @param  mixed  $items
 * @return array<TKey, TValue>
 */',
        'startLine' => 1054,
        'endLine' => 1070,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'operatorForWhere' => 
      array (
        'name' => 'operatorForWhere',
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
            'startLine' => 1080,
            'endLine' => 1080,
            'startColumn' => 41,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1080,
                'endLine' => 1080,
                'startTokenPos' => 4028,
                'startFilePos' => 30409,
                'endTokenPos' => 4028,
                'endFilePos' => 30412,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1080,
            'endLine' => 1080,
            'startColumn' => 47,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1080,
                'endLine' => 1080,
                'startTokenPos' => 4035,
                'startFilePos' => 30424,
                'endTokenPos' => 4035,
                'endFilePos' => 30427,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1080,
            'endLine' => 1080,
            'startColumn' => 65,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an operator checker callback.
 *
 * @param  callable|string  $key
 * @param  string|null  $operator
 * @param  mixed  $value
 * @return \\Closure
 */',
        'startLine' => 1080,
        'endLine' => 1129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'useAsCallable' => 
      array (
        'name' => 'useAsCallable',
        'parameters' => 
        array (
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
            'startLine' => 1137,
            'endLine' => 1137,
            'startColumn' => 38,
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
        'docComment' => '/**
 * Determine if the given value is callable, but not a string.
 *
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 1137,
        'endLine' => 1140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'valueRetriever' => 
      array (
        'name' => 'valueRetriever',
        'parameters' => 
        array (
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
            'startLine' => 1148,
            'endLine' => 1148,
            'startColumn' => 39,
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
 * Get a value retrieving callback.
 *
 * @param  callable|string|null  $value
 * @return callable
 */',
        'startLine' => 1148,
        'endLine' => 1155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'equality' => 
      array (
        'name' => 'equality',
        'parameters' => 
        array (
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
            'startLine' => 1163,
            'endLine' => 1163,
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
 * Make a function to check an item\'s equality.
 *
 * @param  mixed  $value
 * @return \\Closure(mixed): bool
 */',
        'startLine' => 1163,
        'endLine' => 1166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'negate' => 
      array (
        'name' => 'negate',
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
            'startLine' => 1174,
            'endLine' => 1174,
            'startColumn' => 31,
            'endColumn' => 47,
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
 * Make a function using another function, by negating its result.
 *
 * @param  \\Closure  $callback
 * @return \\Closure
 */',
        'startLine' => 1174,
        'endLine' => 1177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'aliasName' => NULL,
      ),
      'identity' => 
      array (
        'name' => 'identity',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Make a function that returns what\'s passed to it.
 *
 * @return \\Closure(TValue): TValue
 */',
        'startLine' => 1184,
        'endLine' => 1187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Support\\Traits',
        'declaringClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'implementingClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
        'currentClassName' => 'Illuminate\\Support\\Traits\\EnumeratesValues',
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