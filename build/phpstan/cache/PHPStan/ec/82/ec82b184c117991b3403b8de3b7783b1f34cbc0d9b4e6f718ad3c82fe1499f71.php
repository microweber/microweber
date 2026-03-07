<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/Query/SortCombinator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\Query\SortCombinator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ba503aa3f2283825abaca637da7d8555168f221a93f8e31e9028fe5869a990a3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\Query\\SortCombinator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/Query/SortCombinator.php',
      ),
    ),
    'namespace' => 'League\\Csv\\Query',
    'name' => 'League\\Csv\\Query\\SortCombinator',
    'shortName' => 'SortCombinator',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @phpstan-type Ordering Sort|Closure(mixed, mixed): int
 * @phpstan-type OrderingExtended Sort|Closure(mixed, mixed): int|callable(mixed, mixed): int
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 45,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'League\\Csv\\Query\\Sort',
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
      'append' => 
      array (
        'name' => 'append',
        'parameters' => 
        array (
          'sorts' => 
          array (
            'name' => 'sorts',
            'default' => NULL,
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
                      'name' => 'League\\Csv\\Query\\Sort',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 28,
            'endColumn' => 49,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified sorting algorithm
 * added after the currently registered sorting algorithms.
 *
 * This method MUST retain the state of the current instance,
 * and return an instance that contains the specified changes.
 *
 * @param Ordering ...$sorts
 */',
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'League\\Csv\\Query',
        'declaringClassName' => 'League\\Csv\\Query\\SortCombinator',
        'implementingClassName' => 'League\\Csv\\Query\\SortCombinator',
        'currentClassName' => 'League\\Csv\\Query\\SortCombinator',
        'aliasName' => NULL,
      ),
      'prepend' => 
      array (
        'name' => 'prepend',
        'parameters' => 
        array (
          'sorts' => 
          array (
            'name' => 'sorts',
            'default' => NULL,
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
                      'name' => 'League\\Csv\\Query\\Sort',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 29,
            'endColumn' => 50,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an instance with the specified sorting algorithm
 * added before the currently registered sorting algorithms.
 *
 * This method MUST retain the state of the current instance,
 * and return an instance that contains the specified changes.
 *
 * @param Ordering ...$sorts
 */',
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'League\\Csv\\Query',
        'declaringClassName' => 'League\\Csv\\Query\\SortCombinator',
        'implementingClassName' => 'League\\Csv\\Query\\SortCombinator',
        'currentClassName' => 'League\\Csv\\Query\\SortCombinator',
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